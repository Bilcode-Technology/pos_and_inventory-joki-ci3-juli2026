<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all suppliers ordered by name ascending
     * @return array
     */
    public function get_all() {
        $this->db->order_by('nama_supplier', 'ASC');
        return $this->db->get('supplier')->result();
    }

    /**
     * Get single supplier by ID
     * @param int $id
     * @return object
     */
    public function get_by_id($id) {
        return $this->db->get_where('supplier', array('id_supplier' => $id))->row();
    }

    /**
     * Insert new supplier
     * @param array $data
     * @return int
     */
    public function insert($data) {
        $this->db->insert('supplier', $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing supplier by ID
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $this->db->where('id_supplier', $id);
        return $this->db->update('supplier', $data);
    }

    /**
     * Check if supplier has linked purchase orders before deletion
     * @param int $id
     * @return bool
     */
    public function has_purchases($id) {
        $this->db->where('id_supplier', $id);
        return ($this->db->count_all_results('pembelian') > 0);
    }

    /**
     * Delete supplier by ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        if ($this->has_purchases($id)) {
            return FALSE;
        }
        $this->db->where('id_supplier', $id);
        return $this->db->delete('supplier');
    }
}
