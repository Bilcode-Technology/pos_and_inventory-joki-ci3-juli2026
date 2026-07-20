<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Atomic Database Transaction to Record Incoming Purchase Order, Line Items, Stock Addition, and Audit Log
     * @param array $data_pembelian Header data (id_supplier, id_user, no_referensi, total_harga, tanggal_pembelian)
     * @param array $data_detail Array of line items (id_produk, kuantitas, harga_beli, subtotal)
     * @return bool Transaction status (TRUE on success, FALSE on rollback)
     */
    public function insert_pembelian($data_pembelian, $data_detail) {
        // Start Atomic Database Transaction
        $this->db->trans_start();

        // Step A: Insert into pembelian header table and get the inserted ID
        $this->db->insert('pembelian', $data_pembelian);
        $id_pembelian = $this->db->insert_id();

        // Step B: Loop through detail items
        foreach ($data_detail as $item) {
            // 1. Insert into detail_pembelian table
            $detail_row = array(
                'id_pembelian' => $id_pembelian,
                'id_produk'    => $item['id_produk'],
                'kuantitas'    => (int)$item['kuantitas'],
                'harga_beli'   => (float)$item['harga_beli'],
                'subtotal'     => (float)$item['subtotal']
            );
            $this->db->insert('detail_pembelian', $detail_row);

            $status = isset($data_pembelian['status']) ? $data_pembelian['status'] : 'pending';
            if ($status === 'selesai') {
                // 2. Update produk table to increase stock atomically (`stok = stok + kuantitas`)
                $this->db->set('stok', 'stok + ' . (int)$item['kuantitas'], FALSE);
                $this->db->where('id_produk', $item['id_produk']);
                $this->db->update('produk');

                // 3. Insert record into riwayat_stok table
                $riwayat_row = array(
                    'id_produk'        => $item['id_produk'],
                    'jenis_pergerakan' => 'masuk',
                    'kuantitas'        => (int)$item['kuantitas'],
                    'referensi_id'     => $id_pembelian,
                    'tanggal'          => isset($data_pembelian['tanggal_pembelian']) ? $data_pembelian['tanggal_pembelian'] : date('Y-m-d H:i:s'),
                    'keterangan'       => 'Pembelian Ref: ' . $data_pembelian['no_referensi']
                );
                $this->db->insert('riwayat_stok', $riwayat_row);
            }
        }

        // Complete the transaction
        $this->db->trans_complete();

        // Return transaction status (TRUE if committed, FALSE if rolled back)
        return $this->db->trans_status();
    }

    /**
     * Get all purchase transactions with supplier name and user name
     * @return array
     */
    public function get_all() {
        $this->db->select('pembelian.*, supplier.nama_supplier, users.nama_user, users.username');
        $this->db->from('pembelian');
        $this->db->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left');
        $this->db->join('users', 'users.id_user = pembelian.id_user', 'left');
        $this->db->order_by('pembelian.tanggal_pembelian', 'DESC');
        $this->db->order_by('pembelian.id_pembelian', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Get single purchase header by ID with supplier and user details
     * @param int $id_pembelian
     * @return object
     */
    public function get_header_by_id($id_pembelian) {
        $this->db->select('pembelian.*, supplier.nama_supplier, supplier.no_telp, supplier.alamat, users.nama_user, users.username');
        $this->db->from('pembelian');
        $this->db->join('supplier', 'supplier.id_supplier = pembelian.id_supplier', 'left');
        $this->db->join('users', 'users.id_user = pembelian.id_user', 'left');
        $this->db->where('pembelian.id_pembelian', $id_pembelian);
        return $this->db->get()->row();
    }

    /**
     * Get line items of a specific purchase order with product details
     * @param int $id_pembelian
     * @return array
     */
    public function get_detail_by_id($id_pembelian) {
        $this->db->select('detail_pembelian.*, produk.kode_produk, produk.nama_produk');
        $this->db->from('detail_pembelian');
        $this->db->join('produk', 'produk.id_produk = detail_pembelian.id_produk', 'left');
        $this->db->where('detail_pembelian.id_pembelian', $id_pembelian);
        return $this->db->get()->result();
    }

    /**
     * Generate unique automatic purchase reference number (e.g., PO-20260717-0001)
     * @return string
     */
    public function generate_no_referensi() {
        $prefix = 'PO-' . date('Ymd') . '-';
        $this->db->like('no_referensi', $prefix, 'after');
        $this->db->order_by('id_pembelian', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('pembelian')->row();

        if ($last) {
            $last_num = (int) substr($last->no_referensi, -4);
            $next_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_num = '0001';
        }
        return $prefix . $next_num;
    }

    /**
     * Get Total Pembelian for current month (Owner Dashboard metric)
     * @return float
     */
    public function get_total_pembelian_bulan_ini() {
        $this->db->select_sum('total_harga');
        $this->db->where('MONTH(tanggal_pembelian)', date('m'));
        $this->db->where('YEAR(tanggal_pembelian)', date('Y'));
        $row = $this->db->get('pembelian')->row();
        return ($row && $row->total_harga) ? (float)$row->total_harga : 0.00;
    }
}
