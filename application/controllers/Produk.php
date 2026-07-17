<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Produk Master Data Controller
 * Note: Stock editing is strictly blocked during update to maintain audit integrity.
 */
class Produk extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Produk_model');
        $this->load->model('Kategori_model');
        $this->load->model('Riwayat_stok_model');
    }

    /**
     * List all products with current stock dynamically displayed
     */
    public function index() {
        $data['title'] = 'Catalog Produk & Live Stok';
        $data['produk'] = $this->Produk_model->get_all();
        $this->render('produk/index_view', $data);
    }

    /**
     * Show form to create new product
     */
    public function create() {
        $data['title'] = 'Tambah Produk Baru';
        $data['kategori'] = $this->Kategori_model->get_all();
        $this->render('produk/create_view', $data);
    }

    /**
     * Store new product
     */
    public function store() {
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('kode_produk', 'Kode Produk', 'required|trim|max_length[50]|is_unique[produk.kode_produk]', array(
            'is_unique' => 'Kode produk ini sudah digunakan.'
        ));
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $initial_stock = (int)$this->input->post('stok');
            if ($initial_stock < 0) {
                $initial_stock = 0;
            }

            $data = array(
                'id_kategori' => (int)$this->input->post('id_kategori'),
                'kode_produk' => $this->input->post('kode_produk', TRUE),
                'nama_produk' => $this->input->post('nama_produk', TRUE),
                'harga_jual'  => (float)$this->input->post('harga_jual'),
                'stok'        => $initial_stock,
                'created_at'  => date('Y-m-d H:i:s')
            );

            $id_produk = $this->Produk_model->insert($data);
            if ($id_produk) {
                // If initial stock was provided, log it in riwayat_stok
                if ($initial_stock > 0) {
                    $riwayat = array(
                        'id_produk'        => $id_produk,
                        'jenis_pergerakan' => 'masuk',
                        'kuantitas'        => $initial_stock,
                        'referensi_id'     => NULL,
                        'tanggal'          => date('Y-m-d H:i:s'),
                        'keterangan'       => 'Stok Awal Pembuatan Produk'
                    );
                    $this->Riwayat_stok_model->insert($riwayat);
                }

                $this->session->set_flashdata('success', 'Produk baru berhasil ditambahkan.');
                redirect('produk');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan produk.');
                redirect('produk/create');
            }
        }
    }

    /**
     * Show form to edit product
     * @param int $id
     */
    public function edit($id) {
        $prd = $this->Produk_model->get_by_id($id);
        if (!$prd) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('produk');
        }

        $data['title'] = 'Edit Produk: ' . $prd->nama_produk;
        $data['produk'] = $prd;
        $data['kategori'] = $this->Kategori_model->get_all();
        $this->render('produk/edit_view', $data);
    }

    /**
     * Update product details (stok editing is strictly blocked)
     * @param int $id
     */
    public function update($id) {
        $prd = $this->Produk_model->get_by_id($id);
        if (!$prd) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('produk');
        }

        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('kode_produk', 'Kode Produk', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $kode = $this->input->post('kode_produk', TRUE);
            if ($kode !== $prd->kode_produk && $this->Produk_model->is_duplicate_code($kode, $id)) {
                $this->session->set_flashdata('error', 'Kode produk "' . html_escape($kode) . '" sudah digunakan.');
                redirect('produk/edit/' . $id);
                return;
            }

            // Notice: We strictly DO NOT update 'stok' here to enforce inventory audit rules
            $data = array(
                'id_kategori' => (int)$this->input->post('id_kategori'),
                'kode_produk' => $kode,
                'nama_produk' => $this->input->post('nama_produk', TRUE),
                'harga_jual'  => (float)$this->input->post('harga_jual')
            );

            if ($this->Produk_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data produk berhasil diperbarui.');
                redirect('produk');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui produk.');
                redirect('produk/edit/' . $id);
            }
        }
    }

    /**
     * Delete product by ID
     * @param int $id
     */
    public function delete($id) {
        if ($this->Produk_model->delete($id)) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk karena sudah memiliki riwayat transaksi (penjualan/pembelian).');
        }
        redirect('produk');
    }
}
