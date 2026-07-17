<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Supplier Master Data Controller
 */
class Supplier extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Supplier_model');
    }

    public function index() {
        $data['title'] = 'Manajemen Supplier';
        $data['supplier'] = $this->Supplier_model->get_all();
        $this->render('supplier/index_view', $data);
    }

    public function create() {
        $data['title'] = 'Tambah Supplier Baru';
        $this->render('supplier/create_view', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'trim|max_length[30]');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = array(
                'nama_supplier' => $this->input->post('nama_supplier', TRUE),
                'no_telp'       => $this->input->post('no_telp', TRUE),
                'alamat'        => $this->input->post('alamat', TRUE),
                'created_at'    => date('Y-m-d H:i:s')
            );

            if ($this->Supplier_model->insert($data)) {
                $this->session->set_flashdata('success', 'Supplier berhasil ditambahkan.');
                redirect('supplier');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan supplier.');
                redirect('supplier/create');
            }
        }
    }

    public function edit($id) {
        $sup = $this->Supplier_model->get_by_id($id);
        if (!$sup) {
            $this->session->set_flashdata('error', 'Supplier tidak ditemukan.');
            redirect('supplier');
        }

        $data['title'] = 'Edit Supplier: ' . $sup->nama_supplier;
        $data['supplier'] = $sup;
        $this->render('supplier/edit_view', $data);
    }

    public function update($id) {
        $sup = $this->Supplier_model->get_by_id($id);
        if (!$sup) {
            $this->session->set_flashdata('error', 'Supplier tidak ditemukan.');
            redirect('supplier');
        }

        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('no_telp', 'Nomor Telepon', 'trim|max_length[30]');
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            $data = array(
                'nama_supplier' => $this->input->post('nama_supplier', TRUE),
                'no_telp'       => $this->input->post('no_telp', TRUE),
                'alamat'        => $this->input->post('alamat', TRUE)
            );

            if ($this->Supplier_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data supplier berhasil diperbarui.');
                redirect('supplier');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data supplier.');
                redirect('supplier/edit/' . $id);
            }
        }
    }

    public function delete($id) {
        if ($this->Supplier_model->delete($id)) {
            $this->session->set_flashdata('success', 'Supplier berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus supplier karena sudah terkait dengan transaksi pembelian.');
        }
        redirect('supplier');
    }
}
