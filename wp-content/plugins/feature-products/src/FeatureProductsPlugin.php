<?php
namespace FeatureProducts;

use FeatureProducts\Controllers\ProductController;

class FeatureProductsPlugin {
    private $book_controller;

    public function __construct(ProductController $product_controller) {
        $this->product_controller = $product_controller;
    }

    public function init() {
        add_action('admin_menu', [$this, 'add_menu_pages']);
        add_action('admin_init', [$this->product_controller, 'handle_actions']);
        add_action('init', [$this, 'register_shortcodes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }


    public function add_menu_pages() {
        add_menu_page('Feature products CRUD', 'Feature products CRUD', 'manage_options', 'feature-products', [$this->product_controller, 'list_products']);
        add_submenu_page('feature-products', 'Add New Product', 'Add New', 'manage_options', 'feature-products-add', [$this->product_controller, 'add_edit_product']);
    }

    public function register_shortcodes() {
        add_shortcode('feature_products', [$this, 'display_feature_products']);
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'feature-products-style',
            plugin_dir_url(__FILE__) . '../assets/style.css',
            [],
            '1.0.0'
        );
    }

    public function display_feature_products() {
        return $this->product_controller->list_feature_products();
    }
}