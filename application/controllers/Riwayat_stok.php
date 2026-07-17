<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Riwayat Stok Controller
 * Chronological audit trail of all product stock inflows and outflows.
 */
class Riwayat_stok extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Riwayat_stok_model');
        $this->load->model('Produk_model');
    }

    /**
     * Display stock movement history with optional filtering
     */
    public function index() {
        $data['title'] = 'Audit & Riwayat Pergerakan Stok';
        
        // Check for filter inputs
        $start_date = $this->input->get('start_date', TRUE);
        $end_date = $this->input->get('end_date', TRUE);
        $jenis = $this->input->get('jenis_pergerakan', TRUE);

        if (!empty($start_date) || !empty($end_date) || !empty($jenis)) {
            $data['riwayat'] = $this->Riwayat_stok_model->filter($start_date, $end_date, $jenis);
        } else {
            $data['riwayat'] = $this->Riwayat_stok_model->get_all();
        }

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['jenis_pergerakan'] = $jenis;

        $this->render('riwayat_stok/index_view', $data);
    }
}
