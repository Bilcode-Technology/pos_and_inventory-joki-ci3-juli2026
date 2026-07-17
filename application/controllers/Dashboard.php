<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard Controller
 * Extends MY_Controller to ensure only logged-in users can access.
 */
class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Penjualan_model');
        $this->load->model('Pembelian_model');
        $this->load->model('Produk_model');
        $this->load->model('Kategori_model');
        $this->load->model('Supplier_model');
    }

    /**
     * Render Dashboard tailored by role (Owner vs Admin)
     */
    public function index() {
        $role = $this->session->userdata('role');
        $data['title'] = 'Dashboard Summary';
        $data['role'] = $role;

        if ($role === 'owner') {
            // Owner Dashboard: Monitoring summary metrics & low stock alerts
            $data['total_penjualan_bulan'] = $this->Penjualan_model->get_total_penjualan_bulan_ini();
            $data['total_pembelian_bulan'] = $this->Pembelian_model->get_total_pembelian_bulan_ini();
            
            $all_produk = $this->Produk_model->get_all();
            $data['total_active_products'] = count($all_produk);
            $data['total_categories'] = count($this->Kategori_model->get_all());
            $data['total_suppliers'] = count($this->Supplier_model->get_all());
            
            // Low Stock Alerts (threshold <= 15 items)
            $data['low_stock_alerts'] = $this->Produk_model->get_low_stock(15);
            
            // Recent 5 sales transactions
            $all_penjualan = $this->Penjualan_model->get_all();
            $data['recent_sales'] = array_slice($all_penjualan, 0, 5);
        } else {
            // Admin Dashboard: Operational quick links and basic live stats
            $all_produk = $this->Produk_model->get_all();
            $data['total_active_products'] = count($all_produk);
            $data['low_stock_alerts'] = $this->Produk_model->get_low_stock(15);
            
            $all_penjualan = $this->Penjualan_model->get_all();
            $data['recent_sales'] = array_slice($all_penjualan, 0, 5);
        }

        $this->render('dashboard/index_view', $data);
    }
}
