<?php
// Ẩn header setting defautl của WP
add_filter('show_admin_bar', '__return_false');

// Cho phép theme hỗ trợ ảnh đại diện
add_theme_support('post-thumbnails');


// tạo đường dẫn đến css js và các dẫn link khác
function proxyflow_theme_enqueue_assets()
{
    // 🌀 Nhúng Tailwind qua CDN
    wp_enqueue_style('main-style', get_stylesheet_directory_uri() . '/style.css', array(), filemtime(get_stylesheet_directory() . '/style.css'));

    // Gọi Link CDN Font awesome
    wp_enqueue_style('font-icon', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '1.0', 'all');

    // Gọi Link URL Font text
    wp_enqueue_style('font-text', '///fonts.googleapis.com', array(), '1.0', 'all');

    // Gọi thêm các file CSS con trong thư mục /css/
    wp_enqueue_style('proxyflow-blog', get_template_directory_uri() . '/css/blog.css', array(), filemtime(get_stylesheet_directory() . '/css/blog.css'));
    wp_enqueue_style('proxyflow-post', get_template_directory_uri() . '/css/post.css', array(), filemtime(get_stylesheet_directory() . '/css/post.css'));
    wp_enqueue_style('proxyflow-reviews', get_template_directory_uri() . '/css/reviews.css', array(), filemtime(get_stylesheet_directory() . '/css/reviews.css'));
    wp_enqueue_style('proxyflow-home', get_template_directory_uri() . '/css/home.css', array(), filemtime(get_stylesheet_directory() . '/css/home.css'));
    wp_enqueue_style('proxyflow-style', get_template_directory_uri() . '/css/style.css', array(), filemtime(get_stylesheet_directory() . '/css/style.css'));


    // Gọi file JS trong thư mục /js/
    wp_enqueue_script('proxyflow-blog', get_template_directory_uri() . '/js/blog.js', array('jquery'), filemtime(get_template_directory() . '/js/blog.js'), true);
    wp_enqueue_script('proxyflow-post', get_template_directory_uri() . '/js/post.js', array('jquery'), filemtime(get_template_directory() . '/js/post.js'), true);
    wp_enqueue_script('proxyflow-home', get_template_directory_uri() . '/js/home.js', array('jquery'), filemtime(get_template_directory() . '/js/home.js'), true);
    wp_enqueue_script('proxyflow-oxylabs', get_template_directory_uri() . '/js/oxylabs.js', array('jquery'), filemtime(get_template_directory() . '/js/oxylabs.js'), true);
}
add_action('wp_enqueue_scripts', 'proxyflow_theme_enqueue_assets');


// ======================================== CPT ===================================
