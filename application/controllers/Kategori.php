<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kategori Master Data Controller
 */
class Kategori extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Kategori_model');
    }

    public function index() {
        $data['title'] = 'Manajemen Kategori Produk';
        $data['kategori'] = $this->Kategori_model->get_all();
        $this->render('kategori/index_view', $data);
    }

    public function create() {
        $data['title'] = 'Tambah Kategori Baru';
        $this->render('kategori/create_view', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim|max_length[100]|is_unique[kategori.nama_kategori]', array(
            'is_unique' => 'Kategori dengan nama ini sudah ada.'
        ));

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = array(
                'nama_kategori' => $this->input->post('nama_kategori', TRUE),
                'created_at'    => date('Y-m-d H:i:s')
            );

            if ($this->Kategori_model->insert($data)) {
                $this->session->set_flashdata('success', 'Kategori baru berhasil ditambahkan.');
                redirect('kategori');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kategori.');
                redirect('kategori/create');
            }
        }
    }

    public function edit($id) {
        $kat = $this->Kategori_model->get_by_id($id);
        if (!$kat) {
            $this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
            redirect('kategori');
        }

        $data['title'] = 'Edit Kategori';
        $data['kategori'] = $kat;
        $this->render('kategori/edit_view', $data);
    }

    public function update($id) {
        $kat = $this->Kategori_model->get_by_id($id);
        if (!$kat) {
            $this->session->set_flashdata('error', 'Kategori tidak ditemukan.');
            redirect('kategori');
        }

        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $nama = $this->input->post('nama_kategori', TRUE);
            if ($nama !== $kat->nama_kategori && $this->Kategori_model->is_duplicate($nama, $id)) {
                $this->session->set_flashdata('error', 'Kategori "' . html_escape($nama) . '" sudah ada.');
                redirect('kategori/edit/' . $id);
                return;
            }

            $data = array('nama_kategori' => $nama);
            if ($this->Kategori_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
                redirect('kategori');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui kategori.');
                redirect('kategori/edit/' . $id);
            }
        }
    }

    public function delete($id) {
        if ($this->Kategori_model->delete($id)) {
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kategori karena masih terdapat produk yang menggunakan kategori ini.');
        }
        redirect('kategori');
    }
}
