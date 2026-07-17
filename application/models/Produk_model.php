<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all products along with their category name
     * @return array
     */
    public function get_all() {
        $this->db->select('produk.*, kategori.nama_kategori');
        $this->db->from('produk');
        $this->db->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left');
        $this->db->order_by('produk.nama_produk', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Get single product by ID along with its category name
     * @param int $id
     * @return object
     */
    public function get_by_id($id) {
        $this->db->select('produk.*, kategori.nama_kategori');
        $this->db->from('produk');
        $this->db->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left');
        $this->db->where('produk.id_produk', $id);
        return $this->db->get()->row();
    }

    /**
     * Get product by specific product code
     * @param string $kode_produk
     * @return object
     */
    public function get_by_kode($kode_produk) {
        $this->db->select('produk.*, kategori.nama_kategori');
        $this->db->from('produk');
        $this->db->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left');
        $this->db->where('produk.kode_produk', $kode_produk);
        return $this->db->get()->row();
    }

    /**
     * Get products with low stock (below or equal to threshold) for Owner Dashboard
     * @param int $threshold Default threshold is 10
     * @return array
     */
    public function get_low_stock($threshold = 10) {
        $this->db->select('produk.*, kategori.nama_kategori');
        $this->db->from('produk');
        $this->db->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left');
        $this->db->where('produk.stok <=', $threshold);
        $this->db->order_by('produk.stok', 'ASC');
        return $this->db->get()->result();
    }

    /**
     * Check if product code already exists
     * @param string $kode_produk
     * @param int|null $exclude_id
     * @return bool
     */
    public function is_duplicate_code($kode_produk, $exclude_id = NULL) {
        $this->db->where('kode_produk', $kode_produk);
        if ($exclude_id !== NULL) {
            $this->db->where('id_produk !=', $exclude_id);
        }
        return ($this->db->count_all_results('produk') > 0);
    }

    /**
     * Insert new product
     * @param array $data
     * @return int
     */
    public function insert($data) {
        $this->db->insert('produk', $data);
        return $this->db->insert_id();
    }

    /**
     * Update product by ID
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $this->db->where('id_produk', $id);
        return $this->db->update('produk', $data);
    }

    /**
     * Check if product has transaction history before deletion
     * @param int $id
     * @return bool
     */
    public function has_transactions($id) {
        $sales = $this->db->get_where('detail_penjualan', array('id_produk' => $id))->count_all_results();
        $purchases = $this->db->get_where('detail_pembelian', array('id_produk' => $id))->count_all_results();
        return ($sales > 0 || $purchases > 0);
    }

    /**
     * Delete product by ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        if ($this->has_transactions($id)) {
            return FALSE;
        }
        $this->db->where('id_produk', $id);
        return $this->db->delete('produk');
    }
}
