<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Penjualan (Point of Sales / Kasir) Controller
 */
class Penjualan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Penjualan_model');
        $this->load->model('Produk_model');
    }

    /**
     * List all sales transactions
     */
    public function index() {
        $data['title'] = 'Daftar Transaksi Penjualan';
        $data['penjualan'] = $this->Penjualan_model->get_all();
        $this->render('penjualan/index_view', $data);
    }

    /**
     * Load POS Kasir Interface
     */
    public function pos() {
        $data['title'] = 'Kasir Point of Sales (POS)';
        $data['produk'] = $this->Produk_model->get_all();
        $data['kode_transaksi'] = $this->Penjualan_model->generate_no_faktur();
        $this->render('penjualan/pos_view', $data);
    }

    /**
     * Alias for create() pointing to pos()
     */
    public function create() {
        $this->pos();
    }

    /**
     * Store POS transaction submitted via AJAX/POST
     */
    public function store() {
        // Only accept AJAX POST requests
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $kode_transaksi = $this->input->post('kode_transaksi', TRUE);
        $total_harga = (float)$this->input->post('total_harga');
        
        // New fields
        $id_member = $this->input->post('id_member') ? (int)$this->input->post('id_member') : NULL;
        $nama_pembeli = $this->input->post('nama_pembeli', TRUE) ?: NULL;
        $diskon = (float)$this->input->post('diskon');
        $pajak = (float)$this->input->post('pajak');
        $grand_total = (float)$this->input->post('grand_total');
        $metode_transaksi = $this->input->post('metode_transaksi', TRUE) ?: 'tunai';
        $status = $this->input->post('status', TRUE) ?: 'selesai';

        $items = $this->input->post('items'); // Array of items [{id_produk, kuantitas, harga_satuan, subtotal}]

        if (empty($kode_transaksi) || empty($items) || !is_array($items)) {
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Keranjang belanja masih kosong atau data tidak valid!'
            ));
            return;
        }

        // Validate stock availability before processing transaction
        foreach ($items as $item) {
            $prd = $this->Produk_model->get_by_id($item['id_produk']);
            if (!$prd) {
                echo json_encode(array(
                    'status'  => 'error',
                    'message' => 'Produk dengan ID ' . $item['id_produk'] . ' tidak ditemukan!'
                ));
                return;
            }
            if ((int)$item['kuantitas'] > $prd->stok) {
                echo json_encode(array(
                    'status'  => 'error',
                    'message' => 'Stok produk "' . $prd->nama_produk . '" tidak mencukupi! (Tersedia: ' . $prd->stok . ', Diminta: ' . $item['kuantitas'] . ')'
                ));
                return;
            }
        }

        // Format data_penjualan header
        $data_penjualan = array(
            'id_user'           => $this->session->userdata('id_user'),
            'id_member'         => $id_member,
            'nama_pembeli'      => $nama_pembeli,
            'kode_transaksi'         => $kode_transaksi,
            'total_harga'       => $total_harga,
            'diskon'            => $diskon,
            'pajak'             => $pajak,
            'grand_total'       => $grand_total,
            'metode_transaksi'  => $metode_transaksi,
            'status'            => $status,
            'tanggal_penjualan' => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s')
        );

        // Execute atomic database transaction
        $success = $this->Penjualan_model->insert_penjualan($data_penjualan, $items);

        if ($success) {
            $inserted = $this->db->get_where('transaksi', array('kode_transaksi' => $kode_transaksi))->row();
            echo json_encode(array(
                'status'       => 'success',
                'message'      => 'Transaksi penjualan No. ' . $kode_transaksi . ' berhasil disimpan!',
                'id_penjualan' => $inserted ? $inserted->id_penjualan : 0
            ));
        } else {
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem saat menyimpan transaksi!'
            ));
        }
    }

    /**
     * View or get invoice details
     * @param int $id_penjualan
     */
    public function detail($id_penjualan) {
        $header = $this->Penjualan_model->get_header_by_id($id_penjualan);
        if (!$header) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => 'Transaksi tidak ditemukan.'));
                return;
            }
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan.');
            redirect('penjualan');
        }

        $detail = $this->Penjualan_model->get_detail_by_id($id_penjualan);

        if ($this->input->is_ajax_request()) {
            echo json_encode(array(
                'status' => 'success',
                'header' => $header,
                'detail' => $detail
            ));
            return;
        }

        $data['title'] = 'Detail Faktur: ' . $header->kode_transaksi;
        $data['header'] = $header;
        $data['detail'] = $detail;
        $this->render('penjualan/detail_view', $data);
    }
}
