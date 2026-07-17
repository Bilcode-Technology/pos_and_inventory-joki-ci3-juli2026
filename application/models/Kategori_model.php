<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all categories ordered by name ascending
     * @return array
     */
    public function get_all() {
        $this->db->order_by('nama_kategori', 'ASC');
        return $this->db->get('kategori')->result();
    }

    /**
     * Get single category by ID
     * @param int $id
     * @return object
     */
    public function get_by_id($id) {
        return $this->db->get_where('kategori', array('id_kategori' => $id))->row();
    }

    /**
     * Check if category name already exists
     * @param string $nama_kategori
     * @param int|null $exclude_id
     * @return bool
     */
    public function is_duplicate($nama_kategori, $exclude_id = NULL) {
        $this->db->where('nama_kategori', $nama_kategori);
        if ($exclude_id !== NULL) {
            $this->db->where('id_kategori !=', $exclude_id);
        }
        return ($this->db->count_all_results('kategori') > 0);
    }

    /**
     * Insert new category
     * @param array $data
     * @return int
     */
    public function insert($data) {
        $this->db->insert('kategori', $data);
        return $this->db->insert_id();
    }

    /**
     * Update category by ID
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $this->db->where('id_kategori', $id);
        return $this->db->update('kategori', $data);
    }

    /**
     * Check if category has products before deletion
     * @param int $id
     * @return bool
     */
    public function has_products($id) {
        $this->db->where('id_kategori', $id);
        return ($this->db->count_all_results('produk') > 0);
    }

    /**
     * Delete category by ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        // Prevent deletion if products exist under this category
        if ($this->has_products($id)) {
            return FALSE;
        }
        $this->db->where('id_kategori', $id);
        return $this->db->delete('kategori');
    }
}
