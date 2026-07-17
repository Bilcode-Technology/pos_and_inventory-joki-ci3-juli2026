<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_stok_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get chronological stock history with product details
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function get_all($limit = NULL, $offset = NULL) {
        $this->db->select('riwayat_stok.*, produk.kode_produk, produk.nama_produk, produk.stok AS current_stok');
        $this->db->from('riwayat_stok');
        $this->db->join('produk', 'produk.id_produk = riwayat_stok.id_produk', 'inner');
        $this->db->order_by('riwayat_stok.tanggal', 'DESC');
        $this->db->order_by('riwayat_stok.id_riwayat', 'DESC');
        
        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Get stock history for a specific product
     * @param int $id_produk
     * @return array
     */
    public function get_by_produk($id_produk) {
        $this->db->select('riwayat_stok.*, produk.kode_produk, produk.nama_produk');
        $this->db->from('riwayat_stok');
        $this->db->join('produk', 'produk.id_produk = riwayat_stok.id_produk', 'inner');
        $this->db->where('riwayat_stok.id_produk', $id_produk);
        $this->db->order_by('riwayat_stok.tanggal', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Filter stock history by date range or movement type ('masuk' / 'keluar')
     * @param string|null $start_date
     * @param string|null $end_date
     * @param string|null $jenis_pergerakan
     * @return array
     */
    public function filter($start_date = NULL, $end_date = NULL, $jenis_pergerakan = NULL) {
        $this->db->select('riwayat_stok.*, produk.kode_produk, produk.nama_produk, produk.stok AS current_stok');
        $this->db->from('riwayat_stok');
        $this->db->join('produk', 'produk.id_produk = riwayat_stok.id_produk', 'inner');
        
        if (!empty($start_date)) {
            $this->db->where('riwayat_stok.tanggal >=', $start_date . ' 00:00:00');
        }
        if (!empty($end_date)) {
            $this->db->where('riwayat_stok.tanggal <=', $end_date . ' 23:59:59');
        }
        if (!empty($jenis_pergerakan) && in_array($jenis_pergerakan, array('masuk', 'keluar'))) {
            $this->db->where('riwayat_stok.jenis_pergerakan', $jenis_pergerakan);
        }
        
        $this->db->order_by('riwayat_stok.tanggal', 'DESC');
        $this->db->order_by('riwayat_stok.id_riwayat', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Insert stock history record
     * @param array $data
     * @return int
     */
    public function insert($data) {
        $this->db->insert('riwayat_stok', $data);
        return $this->db->insert_id();
    }
}
