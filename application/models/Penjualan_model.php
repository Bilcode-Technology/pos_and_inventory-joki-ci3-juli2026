<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Atomic Database Transaction to Record POS Sale, Line Items, Stock Deduction, and Audit Log
     * @param array $data_penjualan Header data (id_user, no_faktur, total_harga, tanggal_penjualan)
     * @param array $data_detail Array of line items (id_produk, kuantitas, harga_satuan, subtotal)
     * @return bool Transaction status (TRUE on success, FALSE on rollback)
     */
    public function insert_penjualan($data_penjualan, $data_detail) {
        // Start Atomic Database Transaction
        $this->db->trans_start();

        // Step A: Insert into penjualan header table and get the inserted ID
        $this->db->insert('penjualan', $data_penjualan);
        $id_penjualan = $this->db->insert_id();

        // Step B: Loop through detail items
        foreach ($data_detail as $item) {
            // 1. Insert into detail_penjualan table
            $detail_row = array(
                'id_penjualan' => $id_penjualan,
                'id_produk'    => $item['id_produk'],
                'kuantitas'    => (int)$item['kuantitas'],
                'harga_satuan' => (float)$item['harga_satuan'],
                'subtotal'     => (float)$item['subtotal']
            );
            $this->db->insert('detail_penjualan', $detail_row);

            // 2. Update produk table to decrease stock atomically (`stok = stok - kuantitas`)
            $this->db->set('stok', 'stok - ' . (int)$item['kuantitas'], FALSE);
            $this->db->where('id_produk', $item['id_produk']);
            $this->db->update('produk');

            // 3. Insert record into riwayat_stok table
            $riwayat_row = array(
                'id_produk'        => $item['id_produk'],
                'jenis_pergerakan' => 'keluar',
                'kuantitas'        => (int)$item['kuantitas'],
                'referensi_id'     => $id_penjualan,
                'tanggal'          => isset($data_penjualan['tanggal_penjualan']) ? $data_penjualan['tanggal_penjualan'] : date('Y-m-d H:i:s'),
                'keterangan'       => 'Penjualan No Faktur: ' . $data_penjualan['no_faktur']
            );
            $this->db->insert('riwayat_stok', $riwayat_row);
        }

        // Complete the transaction
        $this->db->trans_complete();

        // Return transaction status (TRUE if committed, FALSE if rolled back)
        return $this->db->trans_status();
    }

    /**
     * Get all sales transactions with user/cashier name
     * @return array
     */
    public function get_all() {
        $this->db->select('penjualan.*, users.nama_user, users.username');
        $this->db->from('penjualan');
        $this->db->join('users', 'users.id_user = penjualan.id_user', 'left');
        $this->db->order_by('penjualan.tanggal_penjualan', 'DESC');
        $this->db->order_by('penjualan.id_penjualan', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get single sale header by ID with cashier name
     * @param int $id_penjualan
     * @return object
     */
    public function get_header_by_id($id_penjualan) {
        $this->db->select('penjualan.*, users.nama_user, users.username');
        $this->db->from('penjualan');
        $this->db->join('users', 'users.id_user = penjualan.id_user', 'left');
        $this->db->where('penjualan.id_penjualan', $id_penjualan);
        return $this->db->get()->row();
    }

    /**
     * Get line items of a specific sale transaction with product details
     * @param int $id_penjualan
     * @return array
     */
    public function get_detail_by_id($id_penjualan) {
        $this->db->select('detail_penjualan.*, produk.kode_produk, produk.nama_produk');
        $this->db->from('detail_penjualan');
        $this->db->join('produk', 'produk.id_produk = detail_penjualan.id_produk', 'left');
        $this->db->where('detail_penjualan.id_penjualan', $id_penjualan);
        return $this->db->get()->result();
    }

    /**
     * Generate unique automatic invoice number (e.g., INV-20260717-0001)
     * @return string
     */
    public function generate_no_faktur() {
        $prefix = 'INV-' . date('Ymd') . '-';
        $this->db->like('no_faktur', $prefix, 'after');
        $this->db->order_by('id_penjualan', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('penjualan')->row();

        if ($last) {
            $last_num = (int) substr($last->no_faktur, -4);
            $next_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_num = '0001';
        }
        return $prefix . $next_num;
    }

    /**
     * Get Total Penjualan for current month (Owner Dashboard metric)
     * @return float
     */
    public function get_total_penjualan_bulan_ini() {
        $this->db->select_sum('total_harga');
        $this->db->where('MONTH(tanggal_penjualan)', date('m'));
        $this->db->where('YEAR(tanggal_penjualan)', date('Y'));
        $row = $this->db->get('penjualan')->row();
        return ($row && $row->total_harga) ? (float)$row->total_harga : 0.00;
    }
}
