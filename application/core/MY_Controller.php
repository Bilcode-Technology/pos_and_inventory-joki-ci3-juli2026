<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Controller for Authenticated Pages
 * Automatically checks session status and provides role-based access control helper.
 */
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Ensure user is logged in
        if (!$this->session->userdata('id_user') || !$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu untuk mengakses modul ini.');
            redirect('auth/login');
        }
    }

    /**
     * Restrict route access to specific user roles
     * @param array $allowed_roles Array of allowed roles, e.g. ['owner'] or ['admin', 'owner']
     */
    protected function restrict_role($allowed_roles) {
        $user_role = $this->session->userdata('role');
        if (!in_array($user_role, (array)$allowed_roles)) {
            $this->session->set_flashdata('error', 'Akses Ditolak! Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            redirect('dashboard');
        }
    }

    /**
     * Master Layout Rendering Helper
     * Loads header, sidebar, specific content view, and footer.
     * @param string $view Path to content view inside application/views/
     * @param array $data Data to pass to views
     */
    protected function render($view, $data = array()) {
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('layout/footer', $data);
    }
}
