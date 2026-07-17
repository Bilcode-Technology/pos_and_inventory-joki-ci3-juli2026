<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users Master Data Controller
 * Restricted strictly to 'owner' role.
 */
class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->restrict_role(array('owner'));
        $this->load->model('User_model');
    }

    /**
     * List all users
     */
    public function index() {
        $data['title'] = 'Manajemen Users';
        $data['users'] = $this->User_model->get_all();
        $this->render('users/index_view', $data);
    }

    /**
     * Show form to add new user
     */
    public function create() {
        $data['title'] = 'Tambah User Baru';
        $this->render('users/create_view', $data);
    }

    /**
     * Store new user
     */
    public function store() {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]|max_length[50]|is_unique[users.username]', array(
            'is_unique' => 'Username ini sudah terdaftar.'
        ));
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('nama_user', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,owner]');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $data = array(
                'username'   => $this->input->post('username', TRUE),
                'password'   => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'nama_user'  => $this->input->post('nama_user', TRUE),
                'role'       => $this->input->post('role', TRUE),
                'created_at' => date('Y-m-d H:i:s')
            );

            if ($this->User_model->insert($data)) {
                $this->session->set_flashdata('success', 'User berhasil ditambahkan.');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan user.');
                redirect('users/create');
            }
        }
    }

    /**
     * Show form to edit user
     * @param int $id
     */
    public function edit($id) {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('users');
        }

        $data['title'] = 'Edit User: ' . $user->username;
        $data['user'] = $user;
        $this->render('users/edit_view', $data);
    }

    /**
     * Update user details
     * @param int $id
     */
    public function update($id) {
        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('users');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]|max_length[50]');
        $this->form_validation->set_rules('nama_user', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,owner]');

        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password Baru', 'min_length[6]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
        } else {
            // Check username uniqueness if changed
            $new_username = $this->input->post('username', TRUE);
            if ($new_username !== $user->username && $this->User_model->username_exists($new_username, $id)) {
                $this->session->set_flashdata('error', 'Username "' . html_escape($new_username) . '" sudah digunakan oleh user lain.');
                redirect('users/edit/' . $id);
                return;
            }

            $data = array(
                'username'  => $new_username,
                'nama_user' => $this->input->post('nama_user', TRUE),
                'role'      => $this->input->post('role', TRUE)
            );

            if ($this->input->post('password')) {
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
            }

            if ($this->User_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data user berhasil diperbarui.');
                redirect('users');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data user.');
                redirect('users/edit/' . $id);
            }
        }
    }

    /**
     * Delete user by ID
     * @param int $id
     */
    public function delete($id) {
        if ($id == $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif login!');
            redirect('users');
            return;
        }

        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user. Data mungkin terhubung dengan riwayat transaksi.');
        }
        redirect('users');
    }
}
