<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Verify login credentials against hashed password stored in DB
     * @param string $username
     * @param string $password Raw input password
     * @return object|bool User object if valid, FALSE otherwise
     */
    public function verify_login($username, $password) {
        $user = $this->db->get_where('users', array('username' => $username))->row();
        
        if ($user) {
            // Check password using password_verify against the stored bcrypt hash
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return FALSE;
    }

    /**
     * Get all users ordered by creation date descending
     * @return array
     */
    public function get_all() {
        $this->db->order_by('id_user', 'DESC');
        return $this->db->get('users')->result();
    }

    /**
     * Get single user by ID
     * @param int $id
     * @return object
     */
    public function get_by_id($id) {
        return $this->db->get_where('users', array('id_user' => $id))->row();
    }

    /**
     * Check if username already exists (for registration / edit uniqueness check)
     * @param string $username
     * @param int|null $exclude_id ID to exclude during edit
     * @return bool
     */
    public function username_exists($username, $exclude_id = NULL) {
        $this->db->where('username', $username);
        if ($exclude_id !== NULL) {
            $this->db->where('id_user !=', $exclude_id);
        }
        return ($this->db->count_all_results('users') > 0);
    }

    /**
     * Insert new user
     * @param array $data Must include hashed password if password is set
     * @return int Inserted ID
     */
    public function insert($data) {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing user by ID
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $this->db->where('id_user', $id);
        return $this->db->update('users', $data);
    }

    /**
     * Delete user by ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $this->db->where('id_user', $id);
        return $this->db->delete('users');
    }
}
