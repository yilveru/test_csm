<?php
namespace FeatureProducts\Controllers;

use FeatureProducts\Models\ProductModel;
use FeatureProducts\Views\ProductView;
class ProductController {
    private $model;
    private $view;
    public function __construct(ProductModel $model, ProductView $view) {
        $this->model = $model;
        $this->view = $view;
    }
    public function handle_actions() {
        if (isset($_GET['page']) && $_GET['page'] == 'feature-products') {
            if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
                check_admin_referer('delete_product_' . $_GET['id']);
                $this->model->delete_product($_GET['id']);
                wp_safe_redirect(admin_url('admin.php?page=feature-products&message=deleted'));
                exit;
            }
        }
        if (isset($_GET['page']) && $_GET['page'] == 'feature-products-add') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                check_admin_referer('feature_products_action', 'feature_products_nonce');
                $data = [
                    'name' => sanitize_text_field($_POST['name']),
                    'summary' => sanitize_text_field($_POST['summary']),
                    'image_url' => sanitize_text_field($_POST['image']),
                    'feature' => isset($_POST['feature']) ? 1 : 0,
                ];
                $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
                
                if ($id) {
                    $this->model->update_product($id, $data);
                    $message = 'updated';
                } else {
                    $data['created_at'] = current_time('mysql');
                    $this->model->add_product($data);
                    $message = 'added';
                }
                
                wp_safe_redirect(admin_url('admin.php?page=feature-products&message=' . $message));
                exit;
            }
        }
    }

    public function list_products() {
        $products = $this->model->get_products();
        $message = '';
        if (isset($_GET['message'])) {
            switch ($_GET['message']) {
                case 'deleted':
                    $message = 'Product deleted successfully.';
                    break;
                case 'added':
                    $message = 'Product added successfully.';
                    break;
                case 'updated':
                    $message = 'Product updated successfully.';
                    break;
            }
        }
        $this->view->render_list_page($products, $message);
    }

    public function list_feature_products() {
        $products = $this->model->get_products(["feature" => 1]);
        return $this->view->render_list_feature($products);
    }


    public function add_edit_product() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $product = $id ? $this->model->get_product($id) : null;
        $this->view->render_add_edit_page($product);
    }
}