<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pembelian (Incoming Stock / Purchase Order) Controller
 */
class Pembelian extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pembelian_model');
        $this->load->model('Produk_model');
        $this->load->model('Supplier_model');
    }

    /**
     * List all purchase transactions
     */
    public function index() {
        $data['title'] = 'Daftar Transaksi Pembelian Stok';
        $data['pembelian'] = $this->Pembelian_model->get_all();
        $this->render('pembelian/index_view', $data);
    }

    /**
     * Load Create Pembelian / Incoming Stock Form
     */
    public function create() {
        $data['title'] = 'Input Pembelian Stok Masuk';
        $data['supplier'] = $this->Supplier_model->get_all();
        $data['produk'] = $this->Produk_model->get_all();
        $data['no_referensi'] = $this->Pembelian_model->generate_no_referensi();
        $this->render('pembelian/create_view', $data);
    }

    /**
     * Store purchase transaction submitted via AJAX/POST
     */
    public function store() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id_supplier = (int)$this->input->post('id_supplier');
        $no_referensi = $this->input->post('no_referensi', TRUE);
        $total_harga = (float)$this->input->post('total_harga');
        $items = $this->input->post('items'); // Array of items [{id_produk, kuantitas, harga_beli, subtotal}]

        if ($id_supplier <= 0 || empty($no_referensi) || empty($items) || !is_array($items)) {
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Supplier harus dipilih dan keranjang pembelian tidak boleh kosong!'
            ));
            return;
        }

        // Validate products
        foreach ($items as $item) {
            $prd = $this->Produk_model->get_by_id($item['id_produk']);
            if (!$prd) {
                echo json_encode(array(
                    'status'  => 'error',
                    'message' => 'Produk dengan ID ' . $item['id_produk'] . ' tidak ditemukan!'
                ));
                return;
            }
        }

        $status = $this->input->post('status', TRUE);
        if (!in_array($status, ['pending', 'selesai', 'batal'])) {
            $status = 'pending';
        }

        // Format data_pembelian header
        $data_pembelian = array(
            'id_supplier'       => $id_supplier,
            'id_user'           => $this->session->userdata('id_user'),
            'no_referensi'      => $no_referensi,
            'total_harga'       => $total_harga,
            'status'            => $status,
            'tanggal_pembelian' => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s')
        );

        // Execute atomic database transaction
        $success = $this->Pembelian_model->insert_pembelian($data_pembelian, $items);

        if ($success) {
            $inserted = $this->db->get_where('pembelian', array('no_referensi' => $no_referensi))->row();
            echo json_encode(array(
                'status'       => 'success',
                'message'      => 'Transaksi pembelian Ref. ' . $no_referensi . ' berhasil disimpan dan stok produk telah bertambah!',
                'id_pembelian' => $inserted ? $inserted->id_pembelian : 0
            ));
        } else {
            echo json_encode(array(
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem saat menyimpan transaksi pembelian!'
            ));
        }
    }

    /**
     * View purchase order details
     * @param int $id_pembelian
     */
    public function detail($id_pembelian) {
        $header = $this->Pembelian_model->get_header_by_id($id_pembelian);
        if (!$header) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => 'Transaksi pembelian tidak ditemukan.'));
                return;
            }
            $this->session->set_flashdata('error', 'Transaksi pembelian tidak ditemukan.');
            redirect('pembelian');
        }

        $detail = $this->Pembelian_model->get_detail_by_id($id_pembelian);

        if ($this->input->is_ajax_request()) {
            echo json_encode(array(
                'status' => 'success',
                'header' => $header,
                'detail' => $detail
            ));
            return;
        }

        $data['title'] = 'Detail Pembelian: ' . $header->no_referensi;
        $data['header'] = $header;
        $data['detail'] = $detail;
        $this->render('pembelian/detail_view', $data);
    }
}
