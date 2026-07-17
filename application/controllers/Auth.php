<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication Controller (Login, Register, Logout)
 * Extends CI_Controller so guests can access it freely.
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    /**
     * Display Login Page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->session->userdata('logged_in') && $this->session->userdata('id_user')) {
            redirect('dashboard');
        }

        $data['title'] = 'Login System';
        $this->load->view('layout/header', $data);
        $this->load->view('auth/login_view', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Process Login Form Submission
     */
    public function process_login() {
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->login();
        } else {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password', TRUE);

            $user = $this->User_model->verify_login($username, $password);

            if ($user) {
                // Set Session Data
                $session_data = array(
                    'id_user'   => $user->id_user,
                    'username'  => $user->username,
                    'nama_user' => $user->nama_user,
                    'role'      => $user->role,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($session_data);
                $this->session->set_flashdata('success', 'Selamat datang, ' . html_escape($user->nama_user) . '!');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Username atau Password salah! Silakan coba lagi.');
                redirect('auth/login');
            }
        }
    }

    /**
     * Display Register Page
     */
    public function register() {
        if ($this->session->userdata('logged_in') && $this->session->userdata('id_user')) {
            redirect('dashboard');
        }

        $data['title'] = 'Register Account';
        $this->load->view('layout/header', $data);
        $this->load->view('auth/register_view', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Process Register Form Submission
     */
    public function process_register() {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]|max_length[50]|is_unique[users.username]', array(
            'is_unique' => 'Username ini sudah terdaftar. Silakan pilih username lain.'
        ));
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|matches[password]');
        $this->form_validation->set_rules('nama_user', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[admin,owner]');

        if ($this->form_validation->run() === FALSE) {
            $this->register();
        } else {
            $data = array(
                'username'   => $this->input->post('username', TRUE),
                'password'   => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'nama_user'  => $this->input->post('nama_user', TRUE),
                'role'       => $this->input->post('role', TRUE),
                'created_at' => date('Y-m-d H:i:s')
            );

            if ($this->User_model->insert($data)) {
                $this->session->set_flashdata('success', 'Pendaftaran akun berhasil! Silakan login dengan akun baru Anda.');
                redirect('auth/login');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan akun.');
                redirect('auth/register');
            }
        }
    }

    /**
     * Logout and destroy session
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
