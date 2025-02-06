<?php
namespace FeatureProducts\Views;

class ProductView {
    public function render_list_page($products, $message = '') {
        ?>
        <div class="wrap">
            <h1>Products</h1>
            <?php if ($message) : ?>
                <div class="updated"><p><?php echo esc_html($message); ?></p></div>
            <?php endif; ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=feature-products-add')); ?>" class="button button-primary">Add new product</a>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Feature</th>
                        <th>Total clicks</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo esc_html($product->name); ?></td>
                            <td><?php echo $product->feature ? 'Yes' : 'No'; ?></td>
                            <td><?php echo $product->click_count; ?></td>
                            <td><?php echo esc_html($product->created_at); ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=feature-products-add&action=edit&id=' . $product->id)); ?>">Edit</a> |
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=feature-products&action=delete&id=' . $product->id), 'delete_product_' . $product->id); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    public function render_add_edit_page($product = null) {
        $id = $product ? $product->id : 0;
        wp_enqueue_media();
        ?>
        <div class="wrap">
            <h1><?php echo $id ? 'Edit Product' : 'Add New Product'; ?></h1>
            <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=feature-products-add')); ?>">
                <?php wp_nonce_field('feature_products_action', 'feature_products_nonce'); ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <table class="form-table">
                    <tr>
                        <th><label for="Feature">Feature</label></th>
                        <td><input type="checkbox" name="feature" id="feature" <?php echo $product->feature ? "checked" : ""; ?>></td>
                    </tr>
                    <tr>
                        <th><label for="name">Name</label></th>
                        <td><input type="text" name="name" id="name" value="<?php echo esc_attr($product->name ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <th><label for="summary">Summary</label></th>
                        <td><textarea name="summary" id="summary" rows="5" cols="40"><?php echo esc_attr($product->summary ?? ''); ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="image">Image</label></th>
                        <td>
                            <input type="text" name="image" id="image" value="<?php echo esc_attr($product->image_url ?? ''); ?>" required>
                            <button type="button" class="button" id="potd_select_image">Select Image</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        <div id="image_preview">
                            <?php if ( !empty( $product->image_url ) ) : ?>
                                <img src="<?php echo esc_url( $product->image_url ); ?>" style="max-width: 150px; height: auto;">
                            <?php endif; ?>
                        </div>
                        </td>
                    </tr>
                </table>
                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Product"></p>
            </form>
        </div>
        <script>
            jQuery(document).ready(function($) {
                $('#image, #potd_select_image').click(function(e) {
                    e.preventDefault();

                    var image_frame = wp.media({
                        title: 'Select Image',
                        button: {
                            text: 'Use this image'
                        },
                        multiple: false
                    });

                    image_frame.on('select', function() {
                        var attachment = image_frame.state().get('selection').first().toJSON();
                        $('#image').val(attachment.url);  // Set the URL of the image
                    });

                    image_frame.open();
                });
            });
        </script>
        <?php
    }
    public function render_list_feature($products) {
        if (empty($products)) {
            return "<p>No products available.</p>";
        }

        $svg = '<svg fill="#000000" width="12px" height="12px" viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg"><path d="M 33.3436 54.4141 L 36.6014 54.4141 C 39.5311 54.4141 42.0624 54.2266 43.7265 53.8281 C 46.9609 53.0313 48.9764 50.7813 48.9764 47.8984 C 48.9764 47.3125 48.8828 46.7500 48.7187 46.2109 C 50.3358 45.0391 51.2497 43.2344 51.2497 41.2891 C 51.2497 40.3281 51.0625 39.3906 50.7107 38.6172 C 51.7887 37.5156 52.4454 35.8984 52.4454 34.1875 C 52.4454 33.0859 52.1641 31.9141 51.6716 31.0703 C 52.3513 30.1094 52.7267 28.8203 52.7267 27.3906 C 52.7267 23.8984 50.0079 21.1328 46.5155 21.1328 L 37.6562 21.1328 C 37.0936 21.1328 36.7187 20.8750 36.7187 20.4063 C 36.7187 17.8516 40.7030 11.9219 40.7030 7.1641 C 40.7030 3.9297 38.4530 1.5859 35.3358 1.5859 C 33.0390 1.5859 31.4921 2.7813 29.9687 5.6875 C 27.1093 11.1719 23.7343 16.0235 18.1562 22.8203 L 13.7030 22.8203 C 7.9140 22.8203 3.2733 29.3594 3.2733 37.3516 C 3.2733 45.2500 8.3827 51.8125 14.5936 51.8125 L 22.3280 51.8125 C 25.4218 53.4297 29.1718 54.4141 33.3436 54.4141 Z M 36.6249 50.8750 L 33.3671 50.8281 C 23.5468 50.7578 16.8905 45.2031 16.8905 37.2344 C 16.8905 32.1719 18.0155 28.9375 21.2030 24.6719 C 24.7421 19.9610 29.6171 14.3125 33.1327 7.3047 C 34.0702 5.4297 34.5624 5.1250 35.3358 5.1250 C 36.4843 5.1250 37.1640 5.8516 37.1640 7.1641 C 37.1640 10.9610 33.1796 16.7266 33.1796 20.4063 C 33.1796 23.0547 35.3827 24.6719 38.1718 24.6719 L 46.5155 24.6719 C 48.0625 24.6719 49.1876 25.8437 49.1876 27.3906 C 49.1876 28.5156 48.8358 29.2422 47.9219 30.1328 C 47.6876 30.3672 47.5700 30.6016 47.5700 30.8594 C 47.5700 31.0469 47.6406 31.2578 47.8047 31.4453 C 48.5780 32.5703 48.9063 33.2735 48.9063 34.1875 C 48.9063 35.3125 48.3673 36.25 47.3123 37.0703 C 46.8671 37.3984 46.6327 37.7735 46.6327 38.1719 C 46.6327 38.3125 46.6562 38.4766 46.7499 38.6406 C 47.4294 39.9297 47.7107 40.4453 47.7107 41.2891 C 47.7107 42.5547 46.9140 43.5156 45.2265 44.3828 C 44.8749 44.5703 44.6640 44.8516 44.6640 45.2031 C 44.6640 45.3438 44.7109 45.4844 44.7811 45.6484 C 45.3671 47.0781 45.4374 47.3125 45.4374 47.8984 C 45.4374 49.0469 44.5936 49.9610 42.8827 50.3828 C 41.4999 50.7344 39.2968 50.8984 36.6249 50.8750 Z M 14.5936 48.2734 C 10.4452 48.2734 6.8124 43.2813 6.8124 37.3516 C 6.8124 31.3047 10.0702 26.3594 13.7030 26.3594 L 15.8593 26.3594 C 14.1014 29.6406 13.3514 33.0859 13.3514 37.1641 C 13.3514 41.5703 14.9452 45.3672 17.7811 48.2734 Z"/></svg>';

        $output = '<section class="wp-block-group alignwide">';
        $output .= '<div class="potf-feature-products">';
        foreach ($products as $product) {
            $output .= '<div class="product-card">';
            $output .= '<div class="badge">'.$svg.'</div>';
            $output .= '<div class="product-tumb">';
            $output .= '<img src="' . esc_url($product->image_url) . '" alt="' . esc_attr($product->name) . '">';
            $output .= '</div>';
            $output .= '<div class="product-details">';
            $output .= '<h4>' . esc_html($product->name) . '</h4>';
            $output .= '<p>' . esc_html($product->summary) . '</p>';
            $output .= '</div>';
            $output .= '</div>';
        }
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }
}