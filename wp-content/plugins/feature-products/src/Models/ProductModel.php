<?php
namespace FeatureProducts\Models;

use FeatureProducts\Database\DatabaseManager;
class ProductModel {
    private $db;
    public function __construct(DatabaseManager $db) {
        $this->db = $db;
    }
    public function get_products($filter = []) {
        return $this->db->get_products($filter);
    }
    public function get_product($id) {
        return $this->db->get_product($id);
    }

    public function add_product($data) {
        return $this->db->add_product($data);
    }
    public function update_product($id, $data) {
        return $this->db->update_product($id, $data);
    }
    public function delete_product($id) {
        return $this->db->delete_product($id);
    }
}