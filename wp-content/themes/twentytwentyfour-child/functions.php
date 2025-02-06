<?php

function enqueue_child_theme_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}
add_action('wp_enqueue_scripts', 'enqueue_child_theme_styles');

function add_custom_banner() {
    get_template_part('parts/banner');
}
add_action('wp_body_open', 'add_custom_banner');