<?php
// Get the current post ID
$post_id = get_queried_object_id();

// Get the menu by ID
$menu_id = 5;
$menu_post = get_post($menu_id);
$banner_image = get_stylesheet_directory_uri() . '/assets/images/banner-default.png';
if ($menu_post) {
    $blocks = parse_blocks($menu_post->post_content);
    foreach ($blocks as $block) {
        if ($block['blockName'] === 'core/navigation-submenu') {
            if ($block['attrs']['className'] == "rootA") {
                foreach ($block['innerBlocks'] as $inner_block) {
                    if (($inner_block['blockName'] === 'core/navigation-link') && ($inner_block['attrs']['id'] == $post_id)) {
                        $banner_image = get_stylesheet_directory_uri() . '/assets/images/banner-a.png';
                        break;
                    }
                }
            } elseif ($block['attrs']['className'] == "rootB") {
                foreach ($block['innerBlocks'] as $inner_block) {
                    if (($inner_block['blockName'] === 'core/navigation-link') && ($inner_block['attrs']['id'] == $post_id)) {
                        $banner_image = get_stylesheet_directory_uri() . '/assets/images/banner-b.png';
                        break;
                    }
                }
                
            }
        }
    }
}   
?>

<div class="custom-banner" style="background-image: url('<?php echo esc_url($banner_image); ?>');">
</div>