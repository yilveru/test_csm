<?php
namespace FeatureProducts\Database;

class DatabaseManager {
    private $wpdb;
    private $table_name;
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $this->wpdb->prefix . 'feature_products';
    }


    public function create_table() {
        $charset_collate = $this->wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_feature_product_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL COMMENT 'Product name',
            summary varchar(255) NOT NULL COMMENT 'Short description of the product',
            image_url varchar(255) NOT NULL COMMENT 'Path of the image of the product',
            feature tinyint(1) DEFAULT 0 COMMENT 'To know if the to find out if the product is the highlight of the day, 1: yes, 0: no	',
            click_count int DEFAULT 0 COMMENT 'Click count',
            created_at datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Created at',
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    public function get_products($filter = []) {
        $where = [];
        $params = [];

        if (!empty($filter)) {
            foreach ($filter as $key => $value) {
                $where[] = "{$key} = %s";
                $params[] = $value;
            }
        }

        $sql = "SELECT * FROM {$this->table_name}";
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        return $this->wpdb->get_results($this->wpdb->prepare($sql, ...$params));
    }
    public function get_product($id) {
        return $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $id));
    }
    public function add_product($data) {
        return $this->wpdb->insert($this->table_name, $data);
    }
    public function update_product($id, $data) {
        return $this->wpdb->update($this->table_name, $data, ['id' => $id]);
    }
    public function delete_product($id) {
        return $this->wpdb->delete($this->table_name, ['id' => $id]);
    }
}