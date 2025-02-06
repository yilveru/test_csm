<?php
/**
 * Plugin Name: Product of the Day
 * Description: Shown feature products of the day
 * Version: 1.0
 * Author: Yilver Andres Uran Herrera
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
      
spl_autoload_register(function ($class) {
    $prefix = 'FeatureProducts\\';
    $base_dir = plugin_dir_path(__FILE__) . 'src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use FeatureProducts\FeatureProductsPlugin;
use FeatureProducts\Database\DatabaseManager;
use FeatureProducts\Controllers\ProductController;
use FeatureProducts\Views\ProductView;
use FeatureProducts\Models\ProductModel;

// Initialize the plugin
function initialize_feature_products_plugin() {
    $database_manager = new DatabaseManager();
    $product_model = new ProductModel($database_manager);
    $product_view = new ProductView();
    $product_controller = new ProductController($product_model, $product_view);
    $plugin = new FeatureProductsPlugin($product_controller);
    $plugin->init();
}

add_action('plugins_loaded', 'initialize_feature_products_plugin');

// Activation hook
register_activation_hook(__FILE__, function() {
    $database_manager = new DatabaseManager();
    $database_manager->create_table();
});
