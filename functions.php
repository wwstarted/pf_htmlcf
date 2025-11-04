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
    wp_enqueue_style('wpadmin-style', get_template_directory_uri() . '/css/functions.css', array(), filemtime(get_stylesheet_directory() . '/css/functions.css'));


    // Gọi file JS trong thư mục /js/
    wp_enqueue_script('proxyflow-blog', get_template_directory_uri() . '/js/blog.js', array('jquery'), filemtime(get_template_directory() . '/js/blog.js'), true);
    wp_enqueue_script('proxyflow-post', get_template_directory_uri() . '/js/post.js', array('jquery'), filemtime(get_template_directory() . '/js/post.js'), true);
    wp_enqueue_script('proxyflow-home', get_template_directory_uri() . '/js/home.js', array('jquery'), filemtime(get_template_directory() . '/js/home.js'), true);
    wp_enqueue_script('proxyflow-oxylabs', get_template_directory_uri() . '/js/oxylabs.js', array('jquery'), filemtime(get_template_directory() . '/js/oxylabs.js'), true);
}
add_action('wp_enqueue_scripts', 'proxyflow_theme_enqueue_assets');

// CATEGORY & SINGLE POST PAGE =================================

//=========================================CPT posts
function create_cpt_posts_cpt()
{
    $labels = array(
        'name' => 'CPT_Posts',
        'singular_name' => 'CPT_Post',
        'menu_name' => 'CPT_Posts',
        'all_items' => 'All CPT_Posts',
        'add_new_item' => 'Add New CPT_Post',
        'edit_item' => 'Edit CPT_Post'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true // quan trọng: enable REST API
    );

    register_post_type('cpt_posts', $args);
}
add_action('init', 'create_cpt_posts_cpt');

function add_post_desc_metabox()
{
    add_meta_box(
        'post_desc_box',             // ID
        'Post Description',          // Tiêu đề box
        'render_post_desc_metabox',  // Callback render nội dung
        'cpt_posts',                     // CPT bạn muốn thêm
        'normal',                    // Vị trí
        'high'                       // Ưu tiên
    );
}
add_action('add_meta_boxes', 'add_post_desc_metabox');

// Hàm hiển thị trình soạn thảo
function render_post_desc_metabox($post)
{
    // Lấy dữ liệu đã lưu (nếu có)
    $desc = get_post_meta($post->ID, '_post_desc', true);

    // Sử dụng trình soạn thảo TinyMCE có toolbar đầy đủ
    wp_editor(
        $desc, // nội dung đã lưu
        'post_desc', // tên field
        array(
            'textarea_name' => 'post_desc',
            'media_buttons' => true, // cho phép chèn ảnh
            'textarea_rows' => 10,
            'teeny' => false, // false = hiển thị đầy đủ toolbar
            'quicktags' => true, // cho phép dùng HTML nhanh
        )
    );
}

function save_post_desc_metabox($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (isset($_POST['post_desc'])) {
        update_post_meta($post_id, '_post_desc', wp_kses_post($_POST['post_desc']));
    }
}
add_action('save_post', 'save_post_desc_metabox');

function create_post_category_taxonomy()
{
    $labels = array(
        'name' => 'Post Categories',
        'singular_name' => 'Post Category',
        'menu_name' => 'Categories',
        'all_items' => 'All Categories',
        'edit_item' => 'Edit Category',
        'update_item' => 'Update Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => 'New Category Name',
        'search_items' => 'Search Categories',
        'popular_items' => 'Popular Categories',
        'separate_items_with_commas' => 'Separate categories with commas',
        'add_or_remove_items' => 'Add or remove categories',
        'choose_from_most_used' => 'Choose from the most used categories',
        'not_found' => 'No categories found.'
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true, // true = dạng checkbox tree như Category
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true, // quan trọng: để REST API hoạt động
        'rewrite' => array('slug' => 'post-category'),
    );

    register_taxonomy('post_category', array('cpt_posts'), $args);
}
add_action('init', 'create_post_category_taxonomy');



function create_post_tags_taxonomy()
{
    $labels = array(
        'name' => 'Post Tags',
        'singular_name' => 'Post Tag',
        'search_items' => 'Search Post Tags',
        'popular_items' => 'Popular Post Tags',
        'all_items' => 'All Post Tags',
        'edit_item' => 'Edit Post Tag',
        'update_item' => 'Update Post Tag',
        'add_new_item' => 'Add New Post Tag',
        'new_item_name' => 'New Post Tag Name',
        'separate_items_with_commas' => 'Separate tags with commas',
        'add_or_remove_items' => 'Add or remove tags',
        'choose_from_most_used' => 'Choose from the most used tags',
        'menu_name' => 'Tags',
    );

    $args = array(
        'hierarchical' => false, // false = kiểu tag, true = kiểu category
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'update_count_callback' => '_update_post_term_count',
        'show_in_rest' => true, // Quan trọng để hiển thị trong Gutenberg + REST API
        'rewrite' => array('slug' => 'post-tag'),
    );

    register_taxonomy('post_tag', 'cpt_posts', $args);
}
add_action('init', 'create_post_tags_taxonomy');

// Thêm tag name vào meta của REST API cho CPT
add_filter('rest_prepare_cpt_posts', function ($response, $post, $request) {
    $data = $response->get_data();

    // Lấy danh sách tag name
    $tags = wp_get_post_terms($post->ID, 'post_tag', ['fields' => 'names']);

    // Gắn vào meta
    $data['meta']['_post_tag'] = $tags;

    // Cập nhật response
    $response->set_data($data);
    return $response;
}, 10, 3);




// CF cpt posts
function register_post_item_meta_fields()
{
    register_post_meta('cpt_posts', 'post_author', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ]);

    register_post_meta('cpt_posts', 'post_date', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ]);

    register_post_meta('cpt_posts', '_post_desc', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ]);

    register_post_meta('cpt_posts', 'post_sdesc', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ]);

    register_post_meta('cpt_posts', 'post_image', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
    ]);


}
add_action('init', 'register_post_item_meta_fields');



// ======================================== CPT ===================================
// ================================= CPT Provider ==================================
function create_providers_cpt()
{
    $labels = array(
        'name' => 'Providers',
        'singular_name' => 'Provider',
        'menu_name' => 'Providers',
        'all_items' => 'All Providers',
        'add_new_item' => 'Add New Providers',
        'edit_item' => 'Edit Providers'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true // quan trọng: enable REST API
    );

    register_post_type('providers', $args);
}
add_action('init', 'create_providers_cpt');


// ================================= META BOX PROVIDER ===================================
//================= Đăng ký meta box====================
function provider_home_info_meta_box()
{
    add_meta_box(
        'provider_home_info',
        'Provider Information (Home Page)',
        'provider_home_info_callback',
        'providers',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'provider_home_info_meta_box');

//================ Render meta box HTML=======================
function provider_home_info_callback($post)
{
    wp_nonce_field('provider_home_info_nonce', 'provider_home_info_nonce_field');

    // Lấy dữ liệu đã lưu (unserialize)
    $saved_data = get_post_meta($post->ID, '_provider_data', true);

    // Nếu có dữ liệu serialize thì unserialize
    if (!empty($saved_data) && is_string($saved_data)) {
        $saved_data = maybe_unserialize($saved_data);
    }

    // Set default values
    $tags = isset($saved_data['tags']) && is_array($saved_data['tags']) ? $saved_data['tags'] : array();
    $logo = isset($saved_data['logo']) ? $saved_data['logo'] : '';
    $thumbnail = isset($saved_data['thumbnail']) ? $saved_data['thumbnail'] : '';
    $summary = isset($saved_data['summary']) ? $saved_data['summary'] : '';
    $rating = isset($saved_data['rating']) ? $saved_data['rating'] : '';
    $advanced = isset($saved_data['advanced']) && is_array($saved_data['advanced']) ? $saved_data['advanced'] : array();
    $price = isset($saved_data['price']) ? $saved_data['price'] : '';
    ?>

    <style>
        .provider-meta-box {
            padding: 20px;
        }

        .provider-field {
            margin-bottom: 25px;
        }

        .provider-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .provider-field input[type="text"],
        .provider-field input[type="number"],
        .provider-field textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .provider-field textarea {
            min-height: 80px;
        }

        .repeatable-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .repeatable-item input {
            flex: 1;
        }

        .btn-add,
        .btn-remove {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-add {
            background: #0073aa;
            color: white;
        }

        .btn-add:hover {
            background: #005a87;
        }

        .btn-remove {
            background: #dc3232;
            color: white;
            padding: 8px 12px;
        }

        .btn-remove:hover {
            background: #a00;
        }

        .field-description {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            font-style: italic;
        }
    </style>

    <div class="provider-meta-box">

        <!-- Tags -->
        <div class="provider-field">
            <label>Tags</label>
            <div id="tags-container">
                <?php
                if (!empty($tags)) {
                    foreach ($tags as $index => $tag) {
                        echo '<div class="repeatable-item">
                                <input type="text" name="provider_tags[]" value="' . esc_attr($tag) . '" placeholder="Nhập tag (vd: Best Overall)">
                                <button type="button" class="btn-remove remove-tag">✕</button>
                              </div>';
                    }
                } else {
                    echo '<div class="repeatable-item">
                            <input type="text" name="provider_tags[]" value="" placeholder="Best Overall">
                            <button type="button" class="btn-remove remove-tag">✕</button>
                          </div>';
                }
                ?>
            </div>
            <button type="button" class="btn-add add-tag">ADD</button>
        </div>

        <!-- Logo URL -->
        <div class="provider-field">
            <label>Logo URL</label>
            <input type="text" name="provider_logo" id="provider_logo" value="<?php echo esc_attr($logo); ?>"
                placeholder="https://example.com/logo.png">
            <?php if (!empty($logo)): ?>
                <div id="logo-preview" style="margin-top: 10px;">
                    <img src="<?php echo esc_url($logo); ?>"
                        style="max-width: 150px; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                </div>
            <?php else: ?>
                <div id="logo-preview" style="margin-top: 10px; display: none;">
                    <img src=""
                        style="max-width: 150px; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                </div>
            <?php endif; ?>
        </div>
        <!-- Thumbnail URL -->
        <div class="provider-field">
            <label>Thumbnail URL</label>
            <input type="text" name="provider_thumbnail" id="provider_thumbnail" value="<?php echo esc_attr($thumbnail); ?>"
                placeholder="https://example.com/thumbnail.jpg">
            <?php if (!empty($thumbnail)): ?>
                <div id="thumbnail-preview" style="margin-top: 10px;">
                    <img src="<?php echo esc_url($thumbnail); ?>"
                        style="max-width: 300px; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                </div>
            <?php else: ?>
                <div id="thumbnail-preview" style="margin-top: 10px; display: none;">
                    <img src=""
                        style="max-width: 300px; height: auto; border: 1px solid #ddd; border-radius: 4px; padding: 5px;">
                </div>
            <?php endif; ?>
        </div>

        <!-- Summary -->
        <div class="provider-field">
            <label>Summary</label>
            <textarea name="provider_summary"
                placeholder="Premium residential proxies with 100M+ IP pool"><?php echo esc_textarea($summary); ?></textarea>
            <!-- <p class="field-description">Mô tả ngắn gọn về provider hiển thị trên trang home</p> -->
        </div>

        <!-- Rating -->
        <div class="provider-field">
            <label>Rating</label>
            <input type="number" name="provider_rating" value="<?php echo esc_attr($rating); ?>" step="0.1" min="0" max="5"
                placeholder="0.0">
            <p class="field-description">Điểm đánh giá từ 0 đến 5</p>
        </div>

        <!-- Advanced Features -->
        <div class="provider-field">
            <label>Advanced Features</label>
            <div id="advanced-container">
                <?php
                if (!empty($advanced)) {
                    foreach ($advanced as $index => $feature) {
                        echo '<div class="repeatable-item">
                                <input type="text" name="provider_advanced[]" value="' . esc_attr($feature) . '" placeholder="195+ Countries, 99.9% Uptime...">
                                <button type="button" class="btn-remove remove-advanced">✕</button>
                              </div>';
                    }
                } else {
                    echo '<div class="repeatable-item">
                            <input type="text" name="provider_advanced[]" value="" placeholder="195+ Countries">
                            <button type="button" class="btn-remove remove-advanced">✕</button>
                          </div>';
                }
                ?>
            </div>
            <button type="button" class="btn-add add-advanced">ADD</button>
            <!-- <p class="field-description">Các tính năng nổi bật (195+ Countries, 99.9% Uptime, 24/7 Support...)</p> -->
        </div>

        <!-- Price -->
        <div class="provider-field">
            <label>Price</label>
            <input type="number" name="provider_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0"
                placeholder="0.0">
            <!-- <p class="field-description">Giá khởi điểm (chỉ nhập số, vd: 15 cho $15/GB)</p> -->
        </div>

    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Preview logo khi nhập URL
            $('#provider_logo').on('input', function () {
                var logoUrl = $(this).val();
                if (logoUrl) {
                    $('#logo-preview img').attr('src', logoUrl);
                    $('#logo-preview').show();
                } else {
                    $('#logo-preview').hide();
                }
            });

            // Add Tag
            $('.add-tag').on('click', function () {
                var html = '<div class="repeatable-item">' +
                    '<input type="text" name="provider_tags[]" value="" placeholder="Nhập tag">' +
                    '<button type="button" class="btn-remove remove-tag">✕</button>' +
                    '</div>';
                $('#tags-container').append(html);
            });

            // Remove Tag
            $(document).on('click', '.remove-tag', function () {
                if ($('#tags-container .repeatable-item').length > 1) {
                    $(this).closest('.repeatable-item').remove();
                } else {
                    alert('Phải có ít nhất 1 tag!');
                }
            });

            // Add Advanced Feature
            $('.add-advanced').on('click', function () {
                var html = '<div class="repeatable-item">' +
                    '<input type="text" name="provider_advanced[]" value="" placeholder="Nhập feature">' +
                    '<button type="button" class="btn-remove remove-advanced">✕</button>' +
                    '</div>';
                $('#advanced-container').append(html);
            });

            // Remove Advanced Feature
            $(document).on('click', '.remove-advanced', function () {
                if ($('#advanced-container .repeatable-item').length > 1) {
                    $(this).closest('.repeatable-item').remove();
                } else {
                    alert('Phải có ít nhất 1 feature!');
                }
            });

            // Preview thumbnail khi nhập URL
            $('#provider_thumbnail').on('input', function () {
                var thumbnailUrl = $(this).val();
                if (thumbnailUrl) {
                    $('#thumbnail-preview img').attr('src', thumbnailUrl);
                    $('#thumbnail-preview').show();
                } else {
                    $('#thumbnail-preview').hide();
                }
            });
        });
    </script>

    <?php
}

// Lưu dữ liệu
function save_provider_home_info($post_id)
{
    // Kiểm tra nonce
    if (
        !isset($_POST['provider_home_info_nonce_field']) ||
        !wp_verify_nonce($_POST['provider_home_info_nonce_field'], 'provider_home_info_nonce')
    ) {
        return;
    }

    // Kiểm tra autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Kiểm tra quyền
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Chuẩn bị dữ liệu để serialize
    $provider_data = array(
        'tags' => array(),
        'logo' => '',
        'thumbnail' => '',
        'summary' => '',
        'rating' => 0,
        'advanced' => array(),
        'price' => 0
    );

    // Thu thập dữ liệu từ form
    if (isset($_POST['provider_tags'])) {
        // $provider_data['tags'] = array_filter($_POST['provider_tags']);
        $provider_data['tags'] = array_map('wp_kses_post', array_filter($_POST['provider_tags']));
    }

    if (isset($_POST['provider_logo'])) {
        $provider_data['logo'] = esc_url_raw($_POST['provider_logo']);
    }

    if (isset($_POST['provider_thumbnail'])) {
        $provider_data['thumbnail'] = esc_url_raw($_POST['provider_thumbnail']);
    }

    if (isset($_POST['provider_summary'])) {
        // $provider_data['summary'] = sanitize_textarea_field($_POST['provider_summary']);
        $provider_data['summary'] = wp_kses_post($_POST['provider_summary']);
    }

    if (isset($_POST['provider_rating'])) {
        $provider_data['rating'] = floatval($_POST['provider_rating']);
    }

    if (isset($_POST['provider_advanced'])) {
        // $provider_data['advanced'] = array_filter($_POST['provider_advanced']);
        $provider_data['advanced'] = array_map('wp_kses_post', array_filter($_POST['provider_advanced']));

    }

    if (isset($_POST['provider_price'])) {
        $provider_data['price'] = floatval($_POST['provider_price']);
    }

    // Serialize dữ liệu và lưu vào 1 meta key duy nhất
    update_post_meta($post_id, '_provider_data', maybe_serialize($provider_data));
}
add_action('save_post_providers', 'save_provider_home_info');

// ================================= META BOX DESCRIPTION ===================================
// Đăng ký meta box
function provider_description_meta_box()
{
    add_meta_box(
        'provider_description',
        'Provider Description (Detail Page)',
        'provider_description_callback',
        'providers',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'provider_description_meta_box');

// Render meta box HTML
function provider_description_callback($post)
{
    wp_nonce_field('provider_description_nonce', 'provider_description_nonce_field');

    // Lấy dữ liệu đã lưu
    $saved_data = get_post_meta($post->ID, '_provider_data', true);

    if (!empty($saved_data) && is_string($saved_data)) {
        $saved_data = maybe_unserialize($saved_data);
    }

    // Lấy data description
    $desc = isset($saved_data['description']) ? $saved_data['description'] : array();

    // Set default values cho Overview section
    $overview = isset($desc['overview']) ? $desc['overview'] : '';
    $our_verdict = isset($desc['our_verdict']) ? $desc['our_verdict'] : '';
    $best_for = isset($desc['best_for']) && is_array($desc['best_for']) ? $desc['best_for'] : array();
    $not_ideal_for = isset($desc['not_ideal_for']) && is_array($desc['not_ideal_for']) ? $desc['not_ideal_for'] : array();

    // Set default values cho Detailed Ratings section
    $detailed_ratings = isset($desc['detailed_ratings']) && is_array($desc['detailed_ratings']) ? $desc['detailed_ratings'] : array();

    // Set default values cho Pricing Plans section
    $pricing_plans = isset($desc['pricing_plans']) && is_array($desc['pricing_plans']) ? $desc['pricing_plans'] : array();

    // Set default values cho Features Overview section
    $features_overview = isset($desc['features_overview']) && is_array($desc['features_overview']) ? $desc['features_overview'] : array();

    // Set default values cho Perfect For section 
    $perfect_for = isset($desc['perfect_for']) && is_array($desc['perfect_for']) ? $desc['perfect_for'] : array();

    // Set default values cho Security & Support sections 
    $security = isset($desc['security']) && is_array($desc['security']) ? $desc['security'] : array(
        'encryption' => array(),
        'compliance' => array(),
        'authentication' => array(),
        'privacy' => array()
    );

    $support = isset($desc['support']) && is_array($desc['support']) ? $desc['support'] : array(
        'availability' => array(),
        'support_channels' => array(),
        'languages' => array(),
        'resources' => array()
    );

    // Set default values cho User Reviews & FAQ sections
    $user_reviews = isset($desc['user_reviews']) && is_array($desc['user_reviews']) ? $desc['user_reviews'] : array();
    $faq = isset($desc['faq']) && is_array($desc['faq']) ? $desc['faq'] : array();


    ?>

    <style>
        .desc-meta-box {
            padding: 20px;
        }

        .desc-section {
            margin-bottom: 35px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #0073aa;
        }

        .desc-section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #0073aa;
            text-transform: uppercase;
        }

        .desc-field {
            margin-bottom: 20px;
        }

        .desc-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .desc-field textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .desc-repeatable-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .desc-repeatable-item input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .desc-btn-add,
        .desc-btn-remove {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .desc-btn-add {
            background: #0073aa;
            color: white;
        }

        .desc-btn-add:hover {
            background: #005a87;
        }

        .desc-btn-remove {
            background: #dc3232;
            color: white;
            padding: 8px 12px;
        }

        .desc-btn-remove:hover {
            background: #a00;
        }

        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .overview-group {
            border: 2px solid #0073aa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            background: white;
            position: relative;

        }

        .rating-group {
            border: 2px solid #0073aa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            background: white;
            position: relative;
        }

        .rating-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .rating-group-number {
            font-weight: 700;
            color: #0073aa;
            font-size: 15px;
        }

        .rating-group input[type="text"],
        .rating-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .rating-group textarea {
            min-height: 60px;
        }

        .rating-group input[type="number"] {
            width: 100px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .rating-label {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
            display: block;
        }

        .plan-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
            position: relative;
        }

        .plan-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .plan-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .plan-basic-info {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .plan-features-list {
            margin-top: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 6px;
        }

        .plan-features-list label {
            font-weight: 600;
            color: #0073aa;
            margin-bottom: 10px;
            display: block;
        }

        .plan-feature-item {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            align-items: center;
        }

        .plan-feature-item input {
            flex: 1;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .plan-feature-item .desc-btn-remove {
            padding: 6px 10px;
        }

        .feature-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
        }

        .feature-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .feature-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .feature-group-main-title {
            margin-bottom: 15px;
        }

        .feature-group-main-title input {
            width: 100%;
            padding: 10px;
            border: 2px solid #0073aa;
            border-radius: 6px;
            font-weight: 600;
        }

        .feature-items-list {
            margin-top: 15px;
            padding: 15px;
            background: #f0fdf4;
            border-radius: 6px;
        }

        .feature-item-group {
            border: 2px solid #0073aa;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 6px;
            background: white;
        }

        .feature-item-group input,
        .feature-item-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .feature-item-group textarea {
            min-height: 50px;
        }

        .feature-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .perfect-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
            position: relative;
        }

        .perfect-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .perfect-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .perfect-field {
            margin-bottom: 15px;
        }

        .perfect-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 13px;
            color: #555;
        }

        .perfect-field input,
        .perfect-field textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .perfect-field textarea {
            min-height: 80px;
        }

        .perfect-field textarea.icon-field {
            min-height: 120px;
            font-family: monospace;
            font-size: 12px;
        }

        .security-support-box {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
        }

        .security-support-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }

        .security-support-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .security-support-item h4 {
            font-size: 14px;
            font-weight: 600;
            color: #0073aa;
            margin: 0 0 12px 0;
            text-transform: uppercase;
        }

        .security-support-list {
            margin-bottom: 10px;
        }

        .security-support-list-item {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
            align-items: center;
        }

        .security-support-list-item input {
            flex: 1;
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .security-support-list-item .desc-btn-remove {
            padding: 6px 10px;
            font-size: 12px;
        }

        .btn-add-small {
            padding: 6px 12px;
            font-size: 12px;
            background: #0073aa;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-add-small:hover {
            background: #0073aa;
        }

        .support-box {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
        }

        .support-box .security-support-item h4 {
            color: #0073aa;
        }

        .support-box .btn-add-small {
            background: #0073aa;
        }

        .support-box .btn-add-small:hover {
            background: #0073aa;
        }

        .review-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
            position: relative;
        }

        .review-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .review-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .review-fields-grid {
            display: grid;
            grid-template-columns: 100px 1fr 1fr 120px;
            gap: 12px;
            margin-bottom: 15px;
        }

        .review-field {
            margin-bottom: 12px;
        }

        .review-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 12px;
            color: #666;
        }

        .review-field input,
        .review-field textarea,
        .review-field select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .review-field textarea {
            min-height: 80px;
            resize: vertical;
        }

        .review-field select {
            cursor: pointer;
        }

        .faq-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
            position: relative;
        }

        .faq-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .faq-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .faq-field {
            margin-bottom: 15px;
        }

        .faq-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 13px;
            color: #0073aa;
        }

        .faq-field textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 13px;
        }

        .faq-field.question textarea {
            min-height: 60px;
            font-weight: 600;
        }

        .faq-field.answer textarea {
            min-height: 100px;
        }

        .metric-group {
            border: 2px solid #0073aa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            background: white;
            position: relative;
        }

        .metric-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0073aa;
        }

        .metric-group-title {
            font-weight: 700;
            color: #0073aa;
            font-size: 16px;
        }

        .metric-field {
            margin-bottom: 15px;
        }

        .metric-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 13px;
            color: #555;
        }

        .metric-field input,
        .metric-field textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .metric-field textarea.icon-field {
            min-height: 120px;
            font-family: monospace;
            font-size: 12px;
        }


        @media (max-width: 768px) {
            .review-fields-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="desc-meta-box">

        <!-- ========== SECTION: OVERVIEW ========== -->
        <div class="desc-section">
            <div class="desc-section-title">Overview Section</div>

            <div class="overview-group">

                <!-- Overview Text -->
                <div class="desc-field">
                    <label>Overview</label>
                    <textarea name="desc_overview"
                        placeholder="Oxylabs is a premium proxy service provider offering one of the largest residential IP pools..."><?php echo esc_textarea($overview); ?></textarea>
                </div>

                <!-- Our Verdict -->
                <div class="desc-field">
                    <label>Our Verdict</label>
                    <textarea name="desc_our_verdict"
                        placeholder="Oxylabs stands out as one of the most reliable and feature-rich proxy providers..."><?php echo esc_textarea($our_verdict); ?></textarea>
                </div>

                <!-- Best For & Not Ideal For - 2 columns -->
                <div class="two-columns">

                    <!-- Best For -->
                    <div class="desc-field">
                        <label>✅ Best For</label>
                        <div id="best-for-container">
                            <?php
                            if (!empty($best_for)) {
                                foreach ($best_for as $item) {
                                    echo '<div class="desc-repeatable-item">
                                        <input type="text" name="desc_best_for[]" value="' . esc_attr($item) . '" placeholder="Enterprise web scraping">
                                        <button type="button" class="desc-btn-remove remove-best-for">✕</button>
                                      </div>';
                                }
                            } else {
                                echo '<div class="desc-repeatable-item">
                                    <input type="text" name="desc_best_for[]" value="" placeholder="Enterprise web scraping">
                                    <button type="button" class="desc-btn-remove remove-best-for">✕</button>
                                  </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="desc-btn-add add-best-for">ADD</button>
                    </div>

                    <!-- Not Ideal For -->
                    <div class="desc-field">
                        <label>❌ Not Ideal For</label>
                        <div id="not-ideal-for-container">
                            <?php
                            if (!empty($not_ideal_for)) {
                                foreach ($not_ideal_for as $item) {
                                    echo '<div class="desc-repeatable-item">
                                        <input type="text" name="desc_not_ideal_for[]" value="' . esc_attr($item) . '" placeholder="Small personal projects">
                                        <button type="button" class="desc-btn-remove remove-not-ideal-for">✕</button>
                                      </div>';
                                }
                            } else {
                                echo '<div class="desc-repeatable-item">
                                    <input type="text" name="desc_not_ideal_for[]" value="" placeholder="Small personal projects">
                                    <button type="button" class="desc-btn-remove remove-not-ideal-for">✕</button>
                                  </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="desc-btn-add add-not-ideal-for">ADD</button>
                    </div>

                </div>

            </div>
        </div>

        <!-- ========== SECTION: DETAILED RATINGS ========== -->
        <div class="desc-section">
            <div class="desc-section-title">Detailed Ratings Section</div>

            <div id="detailed-ratings-container">
                <?php
                if (!empty($detailed_ratings)) {
                    foreach ($detailed_ratings as $index => $rating_item) {
                        $title = isset($rating_item['title']) ? $rating_item['title'] : '';
                        $summary = isset($rating_item['summary']) ? $rating_item['summary'] : '';
                        $rating = isset($rating_item['rating']) ? $rating_item['rating'] : '';
                        ?>
                        <div class="rating-group">
                            <div class="rating-group-header">
                                <span class="rating-group-number">Rating #<?php echo $index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-rating-group">✕</button>
                            </div>

                            <label class="rating-label">Title</label>
                            <input type="text" name="desc_rating_title[]" value="<?php echo esc_attr($title); ?>"
                                placeholder="Performance">

                            <label class="rating-label">Summary</label>
                            <textarea name="desc_rating_summary[]"
                                placeholder="Exceptional speed and reliability"><?php echo esc_textarea($summary); ?></textarea>

                            <label class="rating-label">Rating (0-5)</label>
                            <input type="number" name="desc_rating_value[]" value="<?php echo esc_attr($rating); ?>" step="0.1"
                                min="0" max="5" placeholder="4.9">
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="rating-group">
                        <div class="rating-group-header">
                            <span class="rating-group-number">Rating #1</span>
                            <button type="button" class="desc-btn-remove remove-rating-group">✕</button>
                        </div>

                        <label class="rating-label">Title</label>
                        <input type="text" name="desc_rating_title[]" value="" placeholder="Performance">

                        <label class="rating-label">Summary</label>
                        <textarea name="desc_rating_summary[]" placeholder="Exceptional speed and reliability"></textarea>

                        <label class="rating-label">Rating (0-5)</label>
                        <input type="number" name="desc_rating_value[]" value="" step="0.1" min="0" max="5" placeholder="4.9">
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-rating-group">ADD</button>
        </div>

        <!-- ========== SECTION: PRICING PLANS ========== -->
        <div class="desc-section">
            <div class="desc-section-title">Pricing Plans Section</div>

            <div id="pricing-plans-container">
                <?php
                if (!empty($pricing_plans)) {
                    foreach ($pricing_plans as $plan_index => $plan) {
                        $plan_title = isset($plan['title']) ? $plan['title'] : '';
                        $plan_price = isset($plan['price']) ? $plan['price'] : '';
                        $plan_min = isset($plan['min']) ? $plan['min'] : '';
                        $plan_features = isset($plan['features']) && is_array($plan['features']) ? $plan['features'] : array();
                        ?>
                        <div class="plan-group" data-plan-index="<?php echo $plan_index; ?>">
                            <div class="plan-group-header">
                                <span class="plan-group-title">Plan #<?php echo $plan_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-plan-group">✕</button>
                            </div>

                            <div class="plan-basic-info">
                                <div>
                                    <label class="rating-label">Plan Title</label>
                                    <input type="text" name="pricing_plan_title[]" value="<?php echo esc_attr($plan_title); ?>"
                                        placeholder="Starter">
                                </div>
                                <div>
                                    <label class="rating-label">Price</label>
                                    <input type="text" name="pricing_plan_price[]" value="<?php echo esc_attr($plan_price); ?>"
                                        placeholder="$15">
                                </div>
                                <div>
                                    <label class="rating-label">Minimum</label>
                                    <input type="text" name="pricing_plan_min[]" value="<?php echo esc_attr($plan_min); ?>"
                                        placeholder="10GB">
                                </div>
                            </div>

                            <div class="plan-features-list">
                                <label>Plan Features</label>
                                <div class="plan-features-container">
                                    <?php
                                    if (!empty($plan_features)) {
                                        foreach ($plan_features as $feature) {
                                            echo '<div class="plan-feature-item">
                                                    <input type="text" name="pricing_plan_features_' . $plan_index . '[]" value="' . esc_attr($feature) . '" placeholder="10GB bandwidth">
                                                    <button type="button" class="desc-btn-remove remove-plan-feature">✕</button>
                                                  </div>';
                                        }
                                    } else {
                                        echo '<div class="plan-feature-item">
                                                <input type="text" name="pricing_plan_features_' . $plan_index . '[]" value="" placeholder="10GB bandwidth">
                                                <button type="button" class="desc-btn-remove remove-plan-feature">✕</button>
                                              </div>';
                                    }
                                    ?>
                                </div>
                                <button type="button" class="desc-btn-add add-plan-feature" style="margin-top: 10px;">ADD</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="plan-group" data-plan-index="0">
                        <div class="plan-group-header">
                            <span class="plan-group-title">Plan #1</span>
                            <button type="button" class="desc-btn-remove remove-plan-group">✕</button>
                        </div>

                        <div class="plan-basic-info">
                            <div>
                                <label class="rating-label">Plan Title</label>
                                <input type="text" name="pricing_plan_title[]" value="" placeholder="Starter">
                            </div>
                            <div>
                                <label class="rating-label">Price</label>
                                <input type="text" name="pricing_plan_price[]" value="" placeholder="$15">
                            </div>
                            <div>
                                <label class="rating-label">Minimum</label>
                                <input type="text" name="pricing_plan_min[]" value="" placeholder="10GB">
                            </div>
                        </div>

                        <div class="plan-features-list">
                            <label>Plan Features</label>
                            <div class="plan-features-container">
                                <div class="plan-feature-item">
                                    <input type="text" name="pricing_plan_features_0[]" value="" placeholder="10GB bandwidth">
                                    <button type="button" class="desc-btn-remove remove-plan-feature">✕</button>
                                </div>
                            </div>
                            <button type="button" class="desc-btn-add add-plan-feature" style="margin-top: 10px;">ADD</button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-plan-group">ADD</button>
        </div>

        <!-- ========== SECTION: FEATURES OVERVIEW ========== -->
        <div class="desc-section">
            <div class="desc-section-title">Features Overview Section</div>

            <div id="features-overview-container">
                <?php
                if (!empty($features_overview)) {
                    foreach ($features_overview as $group_index => $feature_group) {
                        $group_title = isset($feature_group['title']) ? $feature_group['title'] : '';
                        $group_items = isset($feature_group['items']) && is_array($feature_group['items']) ? $feature_group['items'] : array();
                        ?>
                        <div class="feature-group" data-group-index="<?php echo $group_index; ?>">
                            <div class="feature-group-header">
                                <span class="feature-group-title">Feature Group #<?php echo $group_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-feature-group">✕</button>
                            </div>

                            <div class="feature-group-main-title">
                                <label class="rating-label">Group Title</label>
                                <input type="text" name="feature_group_title[]" value="<?php echo esc_attr($group_title); ?>"
                                    placeholder="Proxy Types">
                            </div>

                            <div class="feature-items-list">
                                <label class="rating-label">Features in this Group</label>
                                <div class="feature-items-container">
                                    <?php
                                    if (!empty($group_items)) {
                                        foreach ($group_items as $item_index => $item) {
                                            $item_title = isset($item['title']) ? $item['title'] : '';
                                            $item_summary = isset($item['summary']) ? $item['summary'] : '';
                                            ?>
                                            <div class="feature-item-group">
                                                <div class="feature-item-header">
                                                    <label class="rating-label" style="margin: 0;">Item
                                                        #<?php echo $item_index + 1; ?></label>
                                                    <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                                </div>
                                                <input type="text" name="feature_item_title_<?php echo $group_index; ?>[]"
                                                    value="<?php echo esc_attr($item_title); ?>" placeholder="Residential Proxies">
                                                <textarea name="feature_item_summary_<?php echo $group_index; ?>[]"
                                                    placeholder="100M+ real residential IPs"><?php echo esc_textarea($item_summary); ?></textarea>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="feature-item-group">
                                            <div class="feature-item-header">
                                                <label class="rating-label" style="margin: 0;">Item #1</label>
                                                <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                            </div>
                                            <input type="text" name="feature_item_title_<?php echo $group_index; ?>[]" value=""
                                                placeholder="Residential Proxies">
                                            <textarea name="feature_item_summary_<?php echo $group_index; ?>[]"
                                                placeholder="100M+ real residential IPs"></textarea>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="feature-group" data-group-index="0">
                        <div class="feature-group-header">
                            <span class="feature-group-title">Feature Group #1</span>
                            <button type="button" class="desc-btn-remove remove-feature-group">✕</button>
                        </div>

                        <div class="feature-group-main-title">
                            <label class="rating-label">Group Title</label>
                            <input type="text" name="feature_group_title[]" value="" placeholder="Proxy Types">
                        </div>

                        <div class="feature-items-list">
                            <label class="rating-label">Features in this Group</label>
                            <div class="feature-items-container">
                                <div class="feature-item-group">
                                    <div class="feature-item-header">
                                        <label class="rating-label" style="margin: 0;">Item #1</label>
                                        <button type="button" class="desc-btn-remove remove-feature-item">✕</button>
                                    </div>
                                    <input type="text" name="feature_item_title_0[]" value="" placeholder="Residential Proxies">
                                    <textarea name="feature_item_summary_0[]"
                                        placeholder="100M+ real residential IPs"></textarea>
                                </div>
                            </div>
                            <button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">ADD</button>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-feature-group">ADD</button>
        </div>

        <!-- ========== SECTION: PERFORMANCE METRICS ========== -->
        <div class="desc-section">
            <div class="desc-section-title">Performance Metrics Section</div>

            <div id="performance-metrics-container">
                <?php
                $performance_metrics = isset($desc['performance_metrics']) && is_array($desc['performance_metrics']) ? $desc['performance_metrics'] : array();

                if (!empty($performance_metrics)) {
                    foreach ($performance_metrics as $metric_index => $metric) {
                        $icon = isset($metric['icon']) ? $metric['icon'] : '';
                        $tag = isset($metric['tag']) ? $metric['tag'] : '';
                        $title = isset($metric['title']) ? $metric['title'] : '';
                        $value = isset($metric['value']) ? $metric['value'] : '';
                        $subtitle = isset($metric['subtitle']) ? $metric['subtitle'] : '';
                        ?>
                        <div class="metric-group" data-metric-index="<?php echo $metric_index; ?>">
                            <div class="metric-group-header">
                                <span class="metric-group-title">Metric #<?php echo $metric_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-metric-group">✕</button>
                            </div>

                            <div class="metric-field">
                                <label>Icon Code (HTML/SVG)</label>
                                <textarea name="metric_icon[]" class="icon-field"
                                    placeholder='<svg>...</svg> or HTML icon code'><?php echo esc_textarea($icon); ?></textarea>
                            </div>

                            <div class="metric-field">
                                <label>Tag (Label)</label>
                                <input type="text" name="metric_tag[]" value="<?php echo esc_attr($tag); ?>"
                                    placeholder="Excellent">
                            </div>

                            <div class="metric-field">
                                <label>Title</label>
                                <input type="text" name="metric_title[]" value="<?php echo esc_attr($title); ?>"
                                    placeholder="Success Rate">
                            </div>

                            <div class="metric-field">
                                <label>Value (Display)</label>
                                <input type="text" name="metric_value[]" value="<?php echo esc_attr($value); ?>"
                                    placeholder="99.5% or 0.45s or 10,000">
                            </div>

                            <div class="metric-field">
                                <label>Subtitle (Description)</label>
                                <input type="text" name="metric_subtitle[]" value="<?php echo esc_attr($subtitle); ?>"
                                    placeholder="Success Rate">
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="metric-group" data-metric-index="0">
                        <div class="metric-group-header">
                            <span class="metric-group-title">Metric #1</span>
                            <button type="button" class="desc-btn-remove remove-metric-group">✕</button>
                        </div>

                        <div class="metric-field">
                            <label>Icon Code (HTML/SVG)</label>
                            <textarea name="metric_icon[]" class="icon-field"
                                placeholder='<svg>...</svg> or HTML icon code'></textarea>
                        </div>

                        <div class="metric-field">
                            <label>Tag (Label)</label>
                            <input type="text" name="metric_tag[]" value="" placeholder="Excellent">
                        </div>

                        <div class="metric-field">
                            <label>Title</label>
                            <input type="text" name="metric_title[]" value="" placeholder="Success Rate">
                        </div>

                        <div class="metric-field">
                            <label>Value (Display)</label>
                            <input type="text" name="metric_value[]" value="" placeholder="99.5% or 0.45s or 10,000">
                        </div>

                        <div class="metric-field">
                            <label>Subtitle (Description)</label>
                            <input type="text" name="metric_subtitle[]" value="" placeholder="Success Rate">
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-metric-group">ADD</button>
        </div>

        <!-- ========== SECTION: PERFECT FOR ========== -->
        <div class="desc-section">
            <div class="desc-section-title">Perfect For Section</div>

            <div id="perfect-for-container">
                <?php
                if (!empty($perfect_for)) {
                    foreach ($perfect_for as $pf_index => $pf_item) {
                        $pf_title = isset($pf_item['title']) ? $pf_item['title'] : '';
                        $pf_icon = isset($pf_item['icon']) ? $pf_item['icon'] : '';
                        $pf_summary = isset($pf_item['summary']) ? $pf_item['summary'] : '';
                        $pf_desc = isset($pf_item['desc']) ? $pf_item['desc'] : '';
                        ?>
                        <div class="perfect-group" data-perfect-index="<?php echo $pf_index; ?>">
                            <div class="perfect-group-header">
                                <span class="perfect-group-title">Use Case #<?php echo $pf_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-perfect-group">✕</button>
                            </div>

                            <div class="perfect-field">
                                <label>Title</label>
                                <input type="text" name="perfect_for_title[]" value="<?php echo esc_attr($pf_title); ?>"
                                    placeholder="Web Scraping">
                            </div>

                            <div class="perfect-field">
                                <label>Icon Code (HTML/SVG)</label>
                                <textarea name="perfect_for_icon[]" class="icon-field"
                                    placeholder="<svg>...</svg> or HTML icon code"><?php echo esc_textarea($pf_icon); ?></textarea>
                            </div>

                            <div class="perfect-field">
                                <label>Summary</label>
                                <textarea name="perfect_for_summary[]"
                                    placeholder="Extract data from websites at scale without getting blocked..."><?php echo esc_textarea($pf_summary); ?></textarea>
                            </div>

                            <div class="perfect-field">
                                <label>Description</label>
                                <textarea name="perfect_for_desc[]"
                                    placeholder="Perfect for large-scale data extraction operations..."><?php echo esc_textarea($pf_desc); ?></textarea>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="perfect-group" data-perfect-index="0">
                        <div class="perfect-group-header">
                            <span class="perfect-group-title">Use Case #1</span>
                            <button type="button" class="desc-btn-remove remove-perfect-group">✕</button>
                        </div>

                        <div class="perfect-field">
                            <label>Title</label>
                            <input type="text" name="perfect_for_title[]" value="" placeholder="Web Scraping">
                        </div>

                        <div class="perfect-field">
                            <label>Icon Code (HTML/SVG)</label>
                            <textarea name="perfect_for_icon[]" class="icon-field"
                                placeholder="<svg>...</svg> or HTML icon code"></textarea>
                        </div>

                        <div class="perfect-field">
                            <label>Summary</label>
                            <textarea name="perfect_for_summary[]"
                                placeholder="Extract data from websites at scale without getting blocked..."></textarea>
                        </div>

                        <div class="perfect-field">
                            <label>Description</label>
                            <textarea name="perfect_for_desc[]"
                                placeholder="Perfect for large-scale data extraction operations..."></textarea>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-perfect-group">ADD</button>
        </div>

        <!-- ========== SECTION: SECURITY & COMPLIANCE ========== -->
        <div class="desc-section">
            <div class="desc-section-title">Security & Compliance Section</div>

            <div class="security-support-box">
                <div class="security-support-grid">

                    <!-- Encryption -->
                    <div class="security-support-item">
                        <h4>Encryption</h4>
                        <div class="security-support-list" id="encryption-list">
                            <?php
                            $encryption = isset($security['encryption']) && is_array($security['encryption']) ? $security['encryption'] : array();
                            if (!empty($encryption)) {
                                foreach ($encryption as $item) {
                                    echo '<div class="security-support-list-item">
                                    <input type="text" name="security_encryption[]" value="' . esc_attr($item) . '" placeholder="256-bit SSL/TLS">
                                    <button type="button" class="desc-btn-remove remove-encryption">✕</button>
                                  </div>';
                                }
                            } else {
                                echo '<div class="security-support-list-item">
                                <input type="text" name="security_encryption[]" value="" placeholder="256-bit SSL/TLS">
                                <button type="button" class="desc-btn-remove remove-encryption">✕</button>
                              </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="btn-add-small add-encryption">ADD</button>
                    </div>

                    <!-- Compliance -->
                    <div class="security-support-item">
                        <h4>Compliance</h4>
                        <div class="security-support-list" id="compliance-list">
                            <?php
                            $compliance = isset($security['compliance']) && is_array($security['compliance']) ? $security['compliance'] : array();
                            if (!empty($compliance)) {
                                foreach ($compliance as $item) {
                                    echo '<div class="security-support-list-item">
                                    <input type="text" name="security_compliance[]" value="' . esc_attr($item) . '" placeholder="GDPR">
                                    <button type="button" class="desc-btn-remove remove-compliance">✕</button>
                                  </div>';
                                }
                            } else {
                                echo '<div class="security-support-list-item">
                                <input type="text" name="security_compliance[]" value="" placeholder="GDPR">
                                <button type="button" class="desc-btn-remove remove-compliance">✕</button>
                              </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="btn-add-small add-compliance">ADD</button>
                    </div>

                    <!-- Authentication -->
                    <div class="security-support-item">
                        <h4>Authentication</h4>
                        <div class="security-support-list" id="authentication-list">
                            <?php
                            $authentication = isset($security['authentication']) && is_array($security['authentication']) ? $security['authentication'] : array();
                            if (!empty($authentication)) {
                                foreach ($authentication as $item) {
                                    echo '<div class="security-support-list-item">
                                    <input type="text" name="security_authentication[]" value="' . esc_attr($item) . '" placeholder="2FA">
                                    <button type="button" class="desc-btn-remove remove-authentication">✕</button>
                                  </div>';
                                }
                            } else {
                                echo '<div class="security-support-list-item">
                                <input type="text" name="security_authentication[]" value="" placeholder="2FA">
                                <button type="button" class="desc-btn-remove remove-authentication">✕</button>
                              </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="btn-add-small add-authentication">ADD</button>
                    </div>

                    <!-- Privacy -->
                    <div class="security-support-item">
                        <h4>Privacy</h4>
                        <div class="security-support-list" id="privacy-list">
                            <?php
                            $privacy = isset($security['privacy']) && is_array($security['privacy']) ? $security['privacy'] : array();
                            if (!empty($privacy)) {
                                foreach ($privacy as $item) {
                                    echo '<div class="security-support-list-item">
                                    <input type="text" name="security_privacy[]" value="' . esc_attr($item) . '" placeholder="Strict no-logs policy">
                                    <button type="button" class="desc-btn-remove remove-privacy">✕</button>
                                  </div>';
                                }
                            } else {
                                echo '<div class="security-support-list-item">
                                <input type="text" name="security_privacy[]" value="" placeholder="Strict no-logs policy">
                                <button type="button" class="desc-btn-remove remove-privacy">✕</button>
                              </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="btn-add-small add-privacy">ADD</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ========== SECTION: CUSTOMER SUPPORT ========== -->
        <div class="desc-section">
            <div class="desc-section-title">Customer Support Section</div>

            <div class="security-support-box support-box">
                <div class="security-support-grid">

                    <!-- Availability -->
                    <div class="security-support-item">
                        <h4>Availability</h4>
                        <div class="security-support-list" id="availability-list">
                            <?php
                            $availability = isset($support['availability']) && is_array($support['availability']) ? $support['availability'] : array();
                            if (!empty($availability)) {
                                foreach ($availability as $item) {
                                    echo '<div class="security-support-list-item">
                                    <input type="text" name="support_availability[]" value="' . esc_attr($item) . '" placeholder="24/7/365">
                                    <button type="button" class="desc-btn-remove remove-availability">✕</button>
                                  </div>';
                                }
                            } else {
                                echo '<div class="security-support-list-item">
                                <input type="text" name="support_availability[]" value="" placeholder="24/7/365">
                                <button type="button" class="desc-btn-remove remove-availability">✕</button>
                              </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="btn-add-small add-availability">ADD</button>
                    </div>

                    <!-- Support Channels -->
                    <div class="security-support-item">
                        <h4>Support Channels</h4>
                        <div class="security-support-list" id="support-channels-list">
                            <?php
                            $support_channels = isset($support['support_channels']) && is_array($support['support_channels']) ? $support['support_channels'] : array();
                            if (!empty($support_channels)) {
                                foreach ($support_channels as $item) {
                                    echo '<div class="security-support-list-item">
                                    <input type="text" name="support_channels[]" value="' . esc_attr($item) . '" placeholder="Live Chat">
                                    <button type="button" class="desc-btn-remove remove-support-channel">✕</button>
                                  </div>';
                                }
                            } else {
                                echo '<div class="security-support-list-item">
                                <input type="text" name="support_channels[]" value="" placeholder="Live Chat">
                                <button type="button" class="desc-btn-remove remove-support-channel">✕</button>
                              </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="btn-add-small add-support-channel">ADD</button>
                    </div>

                    <!-- Languages -->
                    <div class="security-support-item">
                        <h4>Languages</h4>
                        <div class="security-support-list" id="languages-list">
                            <?php
                            $languages = isset($support['languages']) && is_array($support['languages']) ? $support['languages'] : array();
                            if (!empty($languages)) {
                                foreach ($languages as $item) {
                                    echo '<div class="security-support-list-item">
                                    <input type="text" name="support_languages[]" value="' . esc_attr($item) . '" placeholder="English">
                                    <button type="button" class="desc-btn-remove remove-language">✕</button>
                                  </div>';
                                }
                            } else {
                                echo '<div class="security-support-list-item">
                                <input type="text" name="support_languages[]" value="" placeholder="English">
                                <button type="button" class="desc-btn-remove remove-language">✕</button>
                              </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="btn-add-small add-language">ADD</button>
                    </div>

                    <!-- Resources -->
                    <div class="security-support-item">
                        <h4>Resources</h4>
                        <div class="security-support-list" id="resources-list">
                            <?php
                            $resources = isset($support['resources']) && is_array($support['resources']) ? $support['resources'] : array();
                            if (!empty($resources)) {
                                foreach ($resources as $item) {
                                    echo '<div class="security-support-list-item">
                                    <input type="text" name="support_resources[]" value="' . esc_attr($item) . '" placeholder="Comprehensive API docs">
                                    <button type="button" class="desc-btn-remove remove-resource">✕</button>
                                  </div>';
                                }
                            } else {
                                echo '<div class="security-support-list-item">
                                <input type="text" name="support_resources[]" value="" placeholder="Comprehensive API docs">
                                <button type="button" class="desc-btn-remove remove-resource">✕</button>
                              </div>';
                            }
                            ?>
                        </div>
                        <button type="button" class="btn-add-small add-resource">ADD</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ========== SECTION: USER REVIEWS ========== -->
        <div class="desc-section">
            <div class="desc-section-title">User Reviews Section</div>

            <div id="user-reviews-container">
                <?php
                if (!empty($user_reviews)) {
                    foreach ($user_reviews as $review_index => $review) {
                        $rating = isset($review['rating']) ? $review['rating'] : '5';
                        $comment = isset($review['comment']) ? $review['comment'] : '';
                        $author_name = isset($review['author_name']) ? $review['author_name'] : '';
                        $author_role = isset($review['author_role']) ? $review['author_role'] : '';
                        $date = isset($review['date']) ? $review['date'] : '';
                        ?>
                        <div class="review-group" data-review-index="<?php echo $review_index; ?>">
                            <div class="review-group-header">
                                <span class="review-group-title">Review #<?php echo $review_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-review-group">✕</button>
                            </div>

                            <div class="review-fields-grid">
                                <div class="review-field">
                                    <label>Rating</label>
                                    <select name="review_rating[]">
                                        <option value="5" <?php selected($rating, '5'); ?>>⭐⭐⭐⭐⭐</option>
                                        <option value="4" <?php selected($rating, '4'); ?>>⭐⭐⭐⭐</option>
                                        <option value="3" <?php selected($rating, '3'); ?>>⭐⭐⭐</option>
                                        <option value="2" <?php selected($rating, '2'); ?>>⭐⭐</option>
                                        <option value="1" <?php selected($rating, '1'); ?>>⭐</option>
                                    </select>
                                </div>

                                <div class="review-field">
                                    <label>Author Name</label>
                                    <input type="text" name="review_author_name[]" value="<?php echo esc_attr($author_name); ?>"
                                        placeholder="Sarah Johnson">
                                </div>

                                <div class="review-field">
                                    <label>Author Role</label>
                                    <input type="text" name="review_author_role[]" value="<?php echo esc_attr($author_role); ?>"
                                        placeholder="Freelancer - Designer">
                                </div>

                                <div class="review-field">
                                    <label>Date</label>
                                    <input type="text" name="review_date[]" value="<?php echo esc_attr($date); ?>"
                                        placeholder="3 months ago">
                                </div>
                            </div>

                            <div class="review-field">
                                <label>Comment</label>
                                <textarea name="review_comment[]"
                                    placeholder="Oxylabs has been instrumental in our data collection operations..."><?php echo esc_textarea($comment); ?></textarea>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="review-group" data-review-index="0">
                        <div class="review-group-header">
                            <span class="review-group-title">Review #1</span>
                            <button type="button" class="desc-btn-remove remove-review-group">✕</button>
                        </div>

                        <div class="review-fields-grid">
                            <div class="review-field">
                                <label>Rating</label>
                                <select name="review_rating[]">
                                    <option value="5">⭐⭐⭐⭐⭐</option>
                                    <option value="4">⭐⭐⭐⭐</option>
                                    <option value="3">⭐⭐⭐</option>
                                    <option value="2">⭐⭐</option>
                                    <option value="1">⭐</option>
                                </select>
                            </div>

                            <div class="review-field">
                                <label>Author Name</label>
                                <input type="text" name="review_author_name[]" value="" placeholder="Sarah Johnson">
                            </div>

                            <div class="review-field">
                                <label>Author Role</label>
                                <input type="text" name="review_author_role[]" value="" placeholder="Freelancer - Designer">
                            </div>

                            <div class="review-field">
                                <label>Date</label>
                                <input type="text" name="review_date[]" value="" placeholder="3 months ago">
                            </div>
                        </div>

                        <div class="review-field">
                            <label>Comment</label>
                            <textarea name="review_comment[]"
                                placeholder="Oxylabs has been instrumental in our data collection operations..."></textarea>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-review-group">
                <ADDress>ADD</ADDress>
            </button>
        </div>

        <!-- ========== SECTION: FAQ ========== -->
        <div class="desc-section">
            <div class="desc-section-title">FAQ Section</div>

            <div id="faq-container">
                <?php
                if (!empty($faq)) {
                    foreach ($faq as $faq_index => $faq_item) {
                        $question = isset($faq_item['question']) ? $faq_item['question'] : '';
                        $answer = isset($faq_item['answer']) ? $faq_item['answer'] : '';
                        ?>
                        <div class="faq-group" data-faq-index="<?php echo $faq_index; ?>">
                            <div class="faq-group-header">
                                <span class="faq-group-title">FAQ #<?php echo $faq_index + 1; ?></span>
                                <button type="button" class="desc-btn-remove remove-faq-group">✕</button>
                            </div>

                            <div class="faq-field question">
                                <label>Question</label>
                                <textarea name="faq_question[]"
                                    placeholder="What is the minimum commitment?"><?php echo esc_textarea($question); ?></textarea>
                            </div>

                            <div class="faq-field answer">
                                <label>Answer</label>
                                <textarea name="faq_answer[]"
                                    placeholder="There is no long-term commitment required. You can start with..."><?php echo esc_textarea($answer); ?></textarea>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="faq-group" data-faq-index="0">
                        <div class="faq-group-header">
                            <span class="faq-group-title">FAQ #1</span>
                            <button type="button" class="desc-btn-remove remove-faq-group">✕</button>
                        </div>

                        <div class="faq-field question">
                            <label>Question</label>
                            <textarea name="faq_question[]" placeholder="What is the minimum commitment?"></textarea>
                        </div>

                        <div class="faq-field answer">
                            <label>Answer</label>
                            <textarea name="faq_answer[]"
                                placeholder="There is no long-term commitment required. You can start with..."></textarea>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <button type="button" class="desc-btn-add add-faq-group">ADD</button>
        </div>

    </div>

    <script>
        jQuery(document).ready(function ($) {
            // Function để update số thứ tự rating groups
            function updateRatingNumbers() {
                $('#detailed-ratings-container .rating-group').each(function (index) {
                    $(this).find('.rating-group-number').text('Rating #' + (index + 1));
                });
            }

            // Function để update số thứ tự plan groups
            function updatePlanNumbers() {
                $('#pricing-plans-container .plan-group').each(function (index) {
                    $(this).attr('data-plan-index', index);
                    $(this).find('.plan-group-title').text('Plan #' + (index + 1));
                    // Update name attributes cho features
                    $(this).find('.plan-features-container input').attr('name', 'pricing_plan_features_' + index + '[]');
                });
            }

            // Function để update số thứ tự feature groups
            function updateFeatureGroupNumbers() {
                $('#features-overview-container .feature-group').each(function (index) {
                    $(this).attr('data-group-index', index);
                    $(this).find('.feature-group-title').text('Feature Group #' + (index + 1));
                    // Update name attributes
                    $(this).find('.feature-items-container input').attr('name', 'feature_item_title_' + index + '[]');
                    $(this).find('.feature-items-container textarea').attr('name', 'feature_item_summary_' + index + '[]');
                });
            }

            // Function để update số thứ tự feature items trong group
            function updateFeatureItemNumbers(container) {
                container.find('.feature-item-group').each(function (index) {
                    $(this).find('.feature-item-header label').text('Item #' + (index + 1));
                });
            }

            // Add Best For
            $('.add-best-for').on('click', function () {
                var html = '<div class="desc-repeatable-item">' +
                    '<input type="text" name="desc_best_for[]" value="" placeholder="Enter item">' +
                    '<button type="button" class="desc-btn-remove remove-best-for">✕</button>' +
                    '</div>';
                $('#best-for-container').append(html);
            });

            // Remove Best For
            $(document).on('click', '.remove-best-for', function () {
                if ($('#best-for-container .desc-repeatable-item').length > 1) {
                    $(this).closest('.desc-repeatable-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Add Not Ideal For
            $('.add-not-ideal-for').on('click', function () {
                var html = '<div class="desc-repeatable-item">' +
                    '<input type="text" name="desc_not_ideal_for[]" value="" placeholder="Enter item">' +
                    '<button type="button" class="desc-btn-remove remove-not-ideal-for">✕</button>' +
                    '</div>';
                $('#not-ideal-for-container').append(html);
            });

            // Remove Not Ideal For
            $(document).on('click', '.remove-not-ideal-for', function () {
                if ($('#not-ideal-for-container .desc-repeatable-item').length > 1) {
                    $(this).closest('.desc-repeatable-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Add Rating Group
            $('.add-rating-group').on('click', function () {
                var count = $('#detailed-ratings-container .rating-group').length + 1;
                var html = '<div class="rating-group">' +
                    '<div class="rating-group-header">' +
                    '<span class="rating-group-number">Rating #' + count + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-rating-group">✕ Remove</button>' +
                    '</div>' +
                    '<label class="rating-label">Title</label>' +
                    '<input type="text" name="desc_rating_title[]" value="" placeholder="Performance">' +
                    '<label class="rating-label">Summary</label>' +
                    '<textarea name="desc_rating_summary[]" placeholder="Exceptional speed and reliability"></textarea>' +
                    '<label class="rating-label">Rating (0-5)</label>' +
                    '<input type="number" name="desc_rating_value[]" value="" step="0.1" min="0" max="5" placeholder="4.9">' +
                    '</div>';
                $('#detailed-ratings-container').append(html);
            });

            // Remove Rating Group
            $(document).on('click', '.remove-rating-group', function () {
                if ($('#detailed-ratings-container .rating-group').length > 1) {
                    $(this).closest('.rating-group').remove();
                    updateRatingNumbers();
                } else {
                    alert('Phải có ít nhất 1 rating!');
                }
            });

            // Add Plan Group
            $('.add-plan-group').on('click', function () {
                var count = $('#pricing-plans-container .plan-group').length;
                var html = '<div class="plan-group" data-plan-index="' + count + '">' +
                    '<div class="plan-group-header">' +
                    '<span class="plan-group-title">Plan #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-plan-group">✕ Remove Plan</button>' +
                    '</div>' +
                    '<div class="plan-basic-info">' +
                    '<div>' +
                    '<label class="rating-label">Plan Title</label>' +
                    '<input type="text" name="pricing_plan_title[]" value="" placeholder="Starter">' +
                    '</div>' +
                    '<div>' +
                    '<label class="rating-label">Price</label>' +
                    '<input type="text" name="pricing_plan_price[]" value="" placeholder="$15">' +
                    '</div>' +
                    '<div>' +
                    '<label class="rating-label">Minimum</label>' +
                    '<input type="text" name="pricing_plan_min[]" value="" placeholder="10GB">' +
                    '</div>' +
                    '</div>' +
                    '<div class="plan-features-list">' +
                    '<label>Plan Features</label>' +
                    '<div class="plan-features-container">' +
                    '<div class="plan-feature-item">' +
                    '<input type="text" name="pricing_plan_features_' + count + '[]" value="" placeholder="10GB bandwidth">' +
                    '<button type="button" class="desc-btn-remove remove-plan-feature">✕</button>' +
                    '</div>' +
                    '</div>' +
                    '<button type="button" class="desc-btn-add add-plan-feature" style="margin-top: 10px;">+ Add Feature</button>' +
                    '</div>' +
                    '</div>';
                $('#pricing-plans-container').append(html);
            });

            // Remove Plan Group
            $(document).on('click', '.remove-plan-group', function () {
                if ($('#pricing-plans-container .plan-group').length > 1) {
                    $(this).closest('.plan-group').remove();
                    updatePlanNumbers();
                } else {
                    alert('Phải có ít nhất 1 plan!');
                }
            });

            // Add Plan Feature
            $(document).on('click', '.add-plan-feature', function () {
                var planGroup = $(this).closest('.plan-group');
                var planIndex = planGroup.attr('data-plan-index');
                var html = '<div class="plan-feature-item">' +
                    '<input type="text" name="pricing_plan_features_' + planIndex + '[]" value="" placeholder="Feature">' +
                    '<button type="button" class="desc-btn-remove remove-plan-feature">✕</button>' +
                    '</div>';
                planGroup.find('.plan-features-container').append(html);
            });

            // Remove Plan Feature
            $(document).on('click', '.remove-plan-feature', function () {
                var container = $(this).closest('.plan-features-container');
                if (container.find('.plan-feature-item').length > 1) {
                    $(this).closest('.plan-feature-item').remove();
                } else {
                    alert('Phải có ít nhất 1 feature!');
                }
            });

            // Add Feature Group
            $('.add-feature-group').on('click', function () {
                var count = $('#features-overview-container .feature-group').length;
                var html = '<div class="feature-group" data-group-index="' + count + '">' +
                    '<div class="feature-group-header">' +
                    '<span class="feature-group-title">Feature Group #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-feature-group">✕ Remove Group</button>' +
                    '</div>' +
                    '<div class="feature-group-main-title">' +
                    '<label class="rating-label">Group Title</label>' +
                    '<input type="text" name="feature_group_title[]" value="" placeholder="Proxy Types">' +
                    '</div>' +
                    '<div class="feature-items-list">' +
                    '<label class="rating-label">Features in this Group</label>' +
                    '<div class="feature-items-container">' +
                    '<div class="feature-item-group">' +
                    '<div class="feature-item-header">' +
                    '<label class="rating-label" style="margin: 0;">Item #1</label>' +
                    '<button type="button" class="desc-btn-remove remove-feature-item">✕</button>' +
                    '</div>' +
                    '<input type="text" name="feature_item_title_' + count + '[]" value="" placeholder="Residential Proxies">' +
                    '<textarea name="feature_item_summary_' + count + '[]" placeholder="100M+ real residential IPs"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '<button type="button" class="desc-btn-add add-feature-item" style="margin-top: 10px;">+ Add Feature Item</button>' +
                    '</div>' +
                    '</div>';
                $('#features-overview-container').append(html);
            });

            // Remove Feature Group
            $(document).on('click', '.remove-feature-group', function () {
                if ($('#features-overview-container .feature-group').length > 1) {
                    $(this).closest('.feature-group').remove();
                    updateFeatureGroupNumbers();
                } else {
                    alert('Phải có ít nhất 1 feature group!');
                }
            });

            // Add Feature Item
            $(document).on('click', '.add-feature-item', function () {
                var featureGroup = $(this).closest('.feature-group');
                var groupIndex = featureGroup.attr('data-group-index');
                var itemsContainer = featureGroup.find('.feature-items-container');
                var itemCount = itemsContainer.find('.feature-item-group').length + 1;

                var html = '<div class="feature-item-group">' +
                    '<div class="feature-item-header">' +
                    '<label class="rating-label" style="margin: 0;">Item #' + itemCount + '</label>' +
                    '<button type="button" class="desc-btn-remove remove-feature-item">✕</button>' +
                    '</div>' +
                    '<input type="text" name="feature_item_title_' + groupIndex + '[]" value="" placeholder="Feature Title">' +
                    '<textarea name="feature_item_summary_' + groupIndex + '[]" placeholder="Feature summary"></textarea>' +
                    '</div>';
                itemsContainer.append(html);
            });

            // Remove Feature Item
            $(document).on('click', '.remove-feature-item', function () {
                var itemsContainer = $(this).closest('.feature-items-container');
                if (itemsContainer.find('.feature-item-group').length > 1) {
                    $(this).closest('.feature-item-group').remove();
                    updateFeatureItemNumbers(itemsContainer);
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });


            // Function để update số thứ tự perfect for groups
            function updatePerfectForNumbers() {
                $('#perfect-for-container .perfect-group').each(function (index) {
                    $(this).attr('data-perfect-index', index);
                    $(this).find('.perfect-group-title').text('Use Case #' + (index + 1));
                });
            }

            // Add Perfect For Group
            $('.add-perfect-group').on('click', function () {
                var count = $('#perfect-for-container .perfect-group').length;
                var html = '<div class="perfect-group" data-perfect-index="' + count + '">' +
                    '<div class="perfect-group-header">' +
                    '<span class="perfect-group-title">Use Case #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-perfect-group">✕ Remove</button>' +
                    '</div>' +
                    '<div class="perfect-field">' +
                    '<label>Title</label>' +
                    '<input type="text" name="perfect_for_title[]" value="" placeholder="Web Scraping">' +
                    '</div>' +
                    '<div class="perfect-field">' +
                    '<label>Icon Code (HTML/SVG)</label>' +
                    '<textarea name="perfect_for_icon[]" class="icon-field" placeholder="<svg>...</svg> or HTML icon code"></textarea>' +
                    '</div>' +
                    '<div class="perfect-field">' +
                    '<label>Summary</label>' +
                    '<textarea name="perfect_for_summary[]" placeholder="Extract data from websites at scale..."></textarea>' +
                    '</div>' +
                    '<div class="perfect-field">' +
                    '<label>Description</label>' +
                    '<textarea name="perfect_for_desc[]" placeholder="Perfect for large-scale data extraction..."></textarea>' +
                    '</div>' +
                    '</div>';
                $('#perfect-for-container').append(html);
            });

            // Remove Perfect For Group
            $(document).on('click', '.remove-perfect-group', function () {
                if ($('#perfect-for-container .perfect-group').length > 1) {
                    $(this).closest('.perfect-group').remove();
                    updatePerfectForNumbers();
                } else {
                    alert('Phải có ít nhất 1 use case!');
                }
            });


            // ========== SECURITY & COMPLIANCE ==========

            // Encryption
            $('.add-encryption').on('click', function () {
                var html = '<div class="security-support-list-item">' +
                    '<input type="text" name="security_encryption[]" value="" placeholder="256-bit SSL/TLS">' +
                    '<button type="button" class="desc-btn-remove remove-encryption">✕</button>' +
                    '</div>';
                $('#encryption-list').append(html);
            });

            $(document).on('click', '.remove-encryption', function () {
                if ($('#encryption-list .security-support-list-item').length > 1) {
                    $(this).closest('.security-support-list-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Compliance
            $('.add-compliance').on('click', function () {
                var html = '<div class="security-support-list-item">' +
                    '<input type="text" name="security_compliance[]" value="" placeholder="GDPR">' +
                    '<button type="button" class="desc-btn-remove remove-compliance">✕</button>' +
                    '</div>';
                $('#compliance-list').append(html);
            });

            $(document).on('click', '.remove-compliance', function () {
                if ($('#compliance-list .security-support-list-item').length > 1) {
                    $(this).closest('.security-support-list-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Authentication
            $('.add-authentication').on('click', function () {
                var html = '<div class="security-support-list-item">' +
                    '<input type="text" name="security_authentication[]" value="" placeholder="2FA">' +
                    '<button type="button" class="desc-btn-remove remove-authentication">✕</button>' +
                    '</div>';
                $('#authentication-list').append(html);
            });

            $(document).on('click', '.remove-authentication', function () {
                if ($('#authentication-list .security-support-list-item').length > 1) {
                    $(this).closest('.security-support-list-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Privacy
            $('.add-privacy').on('click', function () {
                var html = '<div class="security-support-list-item">' +
                    '<input type="text" name="security_privacy[]" value="" placeholder="Strict no-logs policy">' +
                    '<button type="button" class="desc-btn-remove remove-privacy">✕</button>' +
                    '</div>';
                $('#privacy-list').append(html);
            });

            $(document).on('click', '.remove-privacy', function () {
                if ($('#privacy-list .security-support-list-item').length > 1) {
                    $(this).closest('.security-support-list-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // ========== CUSTOMER SUPPORT ==========

            // Availability
            $('.add-availability').on('click', function () {
                var html = '<div class="security-support-list-item">' +
                    '<input type="text" name="support_availability[]" value="" placeholder="24/7/365">' +
                    '<button type="button" class="desc-btn-remove remove-availability">✕</button>' +
                    '</div>';
                $('#availability-list').append(html);
            });

            $(document).on('click', '.remove-availability', function () {
                if ($('#availability-list .security-support-list-item').length > 1) {
                    $(this).closest('.security-support-list-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Support Channels
            $('.add-support-channel').on('click', function () {
                var html = '<div class="security-support-list-item">' +
                    '<input type="text" name="support_channels[]" value="" placeholder="Live Chat">' +
                    '<button type="button" class="desc-btn-remove remove-support-channel">✕</button>' +
                    '</div>';
                $('#support-channels-list').append(html);
            });

            $(document).on('click', '.remove-support-channel', function () {
                if ($('#support-channels-list .security-support-list-item').length > 1) {
                    $(this).closest('.security-support-list-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Languages
            $('.add-language').on('click', function () {
                var html = '<div class="security-support-list-item">' +
                    '<input type="text" name="support_languages[]" value="" placeholder="English">' +
                    '<button type="button" class="desc-btn-remove remove-language">✕</button>' +
                    '</div>';
                $('#languages-list').append(html);
            });

            $(document).on('click', '.remove-language', function () {
                if ($('#languages-list .security-support-list-item').length > 1) {
                    $(this).closest('.security-support-list-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Resources
            $('.add-resource').on('click', function () {
                var html = '<div class="security-support-list-item">' +
                    '<input type="text" name="support_resources[]" value="" placeholder="Comprehensive API docs">' +
                    '<button type="button" class="desc-btn-remove remove-resource">✕</button>' +
                    '</div>';
                $('#resources-list').append(html);
            });

            $(document).on('click', '.remove-resource', function () {
                if ($('#resources-list .security-support-list-item').length > 1) {
                    $(this).closest('.security-support-list-item').remove();
                } else {
                    alert('Phải có ít nhất 1 item!');
                }
            });

            // Function để update số thứ tự review groups
            function updateReviewNumbers() {
                $('#user-reviews-container .review-group').each(function (index) {
                    $(this).attr('data-review-index', index);
                    $(this).find('.review-group-title').text('Review #' + (index + 1));
                });
            }

            // Function để update số thứ tự FAQ groups
            function updateFaqNumbers() {
                $('#faq-container .faq-group').each(function (index) {
                    $(this).attr('data-faq-index', index);
                    $(this).find('.faq-group-title').text('FAQ #' + (index + 1));
                });
            }

            // ========== USER REVIEWS ==========

            // Add Review Group
            $('.add-review-group').on('click', function () {
                var count = $('#user-reviews-container .review-group').length;
                var html = '<div class="review-group" data-review-index="' + count + '">' +
                    '<div class="review-group-header">' +
                    '<span class="review-group-title">Review #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-review-group">✕ Remove</button>' +
                    '</div>' +
                    '<div class="review-fields-grid">' +
                    '<div class="review-field">' +
                    '<label>Rating</label>' +
                    '<select name="review_rating[]">' +
                    '<option value="5">⭐⭐⭐⭐⭐</option>' +
                    '<option value="4">⭐⭐⭐⭐</option>' +
                    '<option value="3">⭐⭐⭐</option>' +
                    '<option value="2">⭐⭐</option>' +
                    '<option value="1">⭐</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="review-field">' +
                    '<label>Author Name</label>' +
                    '<input type="text" name="review_author_name[]" value="" placeholder="Sarah Johnson">' +
                    '</div>' +
                    '<div class="review-field">' +
                    '<label>Author Role</label>' +
                    '<input type="text" name="review_author_role[]" value="" placeholder="Freelancer - Designer">' +
                    '</div>' +
                    '<div class="review-field">' +
                    '<label>Date</label>' +
                    '<input type="text" name="review_date[]" value="" placeholder="3 months ago">' +
                    '</div>' +
                    '</div>' +
                    '<div class="review-field">' +
                    '<label>Comment</label>' +
                    '<textarea name="review_comment[]" placeholder="Oxylabs has been instrumental..."></textarea>' +
                    '</div>' +
                    '</div>';
                $('#user-reviews-container').append(html);
            });

            // Remove Review Group
            $(document).on('click', '.remove-review-group', function () {
                if ($('#user-reviews-container .review-group').length > 1) {
                    $(this).closest('.review-group').remove();
                    updateReviewNumbers();
                } else {
                    alert('Phải có ít nhất 1 review!');
                }
            });

            // ========== FAQ ==========

            // Add FAQ Group
            $('.add-faq-group').on('click', function () {
                var count = $('#faq-container .faq-group').length;
                var html = '<div class="faq-group" data-faq-index="' + count + '">' +
                    '<div class="faq-group-header">' +
                    '<span class="faq-group-title">FAQ #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-faq-group">✕ Remove</button>' +
                    '</div>' +
                    '<div class="faq-field question">' +
                    '<label>Question</label>' +
                    '<textarea name="faq_question[]" placeholder="What is the minimum commitment?"></textarea>' +
                    '</div>' +
                    '<div class="faq-field answer">' +
                    '<label>Answer</label>' +
                    '<textarea name="faq_answer[]" placeholder="There is no long-term commitment required..."></textarea>' +
                    '</div>' +
                    '</div>';
                $('#faq-container').append(html);
            });

            // Remove FAQ Group
            $(document).on('click', '.remove-faq-group', function () {
                if ($('#faq-container .faq-group').length > 1) {
                    $(this).closest('.faq-group').remove();
                    updateFaqNumbers();
                } else {
                    alert('Phải có ít nhất 1 FAQ!');
                }
            });

            // Function để update số thứ tự metric groups
            function updateMetricNumbers() {
                $('#performance-metrics-container .metric-group').each(function (index) {
                    $(this).attr('data-metric-index', index);
                    $(this).find('.metric-group-title').text('Metric #' + (index + 1));
                });
            }

            // Add Metric Group
            $('.add-metric-group').on('click', function () {
                var count = $('#performance-metrics-container .metric-group').length;
                var html = '<div class="metric-group" data-metric-index="' + count + '">' +
                    '<div class="metric-group-header">' +
                    '<span class="metric-group-title">Metric #' + (count + 1) + '</span>' +
                    '<button type="button" class="desc-btn-remove remove-metric-group">✕</button>' +
                    '</div>' +
                    '<div class="metric-field">' +
                    '<label>Icon Code (HTML/SVG)</label>' +
                    '<textarea name="metric_icon[]" class="icon-field" placeholder="<svg>...</svg> or HTML icon code"></textarea>' +
                    '</div>' +
                    '<div class="metric-field">' +
                    '<label>Tag (Label)</label>' +
                    '<input type="text" name="metric_tag[]" value="" placeholder="Excellent">' +
                    '</div>' +
                    '<div class="metric-field">' +
                    '<label>Title</label>' +
                    '<input type="text" name="metric_title[]" value="" placeholder="Success Rate">' +
                    '</div>' +
                    '<div class="metric-field">' +
                    '<label>Value (Display)</label>' +
                    '<input type="text" name="metric_value[]" value="" placeholder="99.5% or 0.45s or 10,000">' +
                    '</div>' +
                    '<div class="metric-field">' +
                    '<label>Subtitle (Description)</label>' +
                    '<input type="text" name="metric_subtitle[]" value="" placeholder="Success Rate">' +
                    '</div>' +
                    '</div>';
                $('#performance-metrics-container').append(html);
            });

            // Remove Metric Group
            $(document).on('click', '.remove-metric-group', function () {
                if ($('#performance-metrics-container .metric-group').length > 1) {
                    $(this).closest('.metric-group').remove();
                    updateMetricNumbers();
                } else {
                    alert('Phải có ít nhất 1 metric!');
                }
            });

        });


    </script>

    <?php
}

// Lưu dữ liệu Description
function save_provider_description($post_id)
{
    // Kiểm tra nonce
    if (
        !isset($_POST['provider_description_nonce_field']) ||
        !wp_verify_nonce($_POST['provider_description_nonce_field'], 'provider_description_nonce')
    ) {
        return;
    }

    // Kiểm tra autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Kiểm tra quyền
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Lấy data hiện tại (để merge với home info data)
    // $existing_data = get_post_meta($post_id, '_provider_data', true);
    // if (!empty($existing_data) && is_string($existing_data)) {
    //     $existing_data = unserialize($existing_data);
    // }
    // if (!is_array($existing_data)) {
    //     $existing_data = array();
    // }
    $existing_data = get_post_meta($post_id, '_provider_data', true);
    $existing_data = maybe_unserialize($existing_data);

    if (!is_array($existing_data)) {
        $existing_data = array();
    }


    // Chuẩn bị description data
    $description_data = array(
        'overview' => '',
        'our_verdict' => '',
        'best_for' => array(),
        'not_ideal_for' => array(),
        'detailed_ratings' => array(),
        'pricing_plans' => array(),
        'features_overview' => array(),
        'perfect_for' => array(),
        'security' => array(
            'encryption' => array(),
            'compliance' => array(),
            'authentication' => array(),
            'privacy' => array()
        ),
        'support' => array(
            'availability' => array(),
            'support_channels' => array(),
            'languages' => array(),
            'resources' => array()
        ),
        'user_reviews' => array(),
        'faq' => array(),
        'performance_metrics' => array()
    );

    // Thu thập dữ liệu từ form
    if (isset($_POST['desc_overview'])) {
        $description_data['overview'] = wp_kses_post($_POST['desc_overview']);
    }

    if (isset($_POST['desc_our_verdict'])) {
        $description_data['our_verdict'] = wp_kses_post($_POST['desc_our_verdict']);
    }

    if (isset($_POST['desc_best_for'])) {
        $description_data['best_for'] = array_filter(array_map('wp_kses_post', $_POST['desc_best_for']));
    }

    if (isset($_POST['desc_not_ideal_for'])) {
        $description_data['not_ideal_for'] = array_filter(array_map('wp_kses_post', $_POST['desc_not_ideal_for']));
    }

    // Thu thập Detailed Ratings
    if (isset($_POST['desc_rating_title']) && is_array($_POST['desc_rating_title'])) {
        $titles = $_POST['desc_rating_title'];
        $summaries = isset($_POST['desc_rating_summary']) ? $_POST['desc_rating_summary'] : array();
        $ratings = isset($_POST['desc_rating_value']) ? $_POST['desc_rating_value'] : array();

        foreach ($titles as $index => $title) {
            if (!empty($title)) {
                $description_data['detailed_ratings'][] = array(
                    'title' => wp_kses_post($title),
                    'summary' => isset($summaries[$index]) ? wp_kses_post($summaries[$index]) : '',
                    'rating' => isset($ratings[$index]) ? floatval($ratings[$index]) : 0
                );
            }
        }
    }

    // Thu thập Pricing Plans
    if (isset($_POST['pricing_plan_title']) && is_array($_POST['pricing_plan_title'])) {
        $plan_titles = $_POST['pricing_plan_title'];
        $plan_prices = isset($_POST['pricing_plan_price']) ? $_POST['pricing_plan_price'] : array();
        $plan_mins = isset($_POST['pricing_plan_min']) ? $_POST['pricing_plan_min'] : array();

        foreach ($plan_titles as $plan_index => $plan_title) {
            if (!empty($plan_title)) {
                // Thu thập features cho plan này
                $plan_features = array();
                $features_key = 'pricing_plan_features_' . $plan_index;
                if (isset($_POST[$features_key]) && is_array($_POST[$features_key])) {
                    $plan_features = array_filter(array_map('wp_kses_post', $_POST[$features_key]));
                }

                $description_data['pricing_plans'][] = array(
                    'title' => wp_kses_post($plan_title),
                    'price' => isset($plan_prices[$plan_index]) ? wp_kses_post($plan_prices[$plan_index]) : '',
                    'min' => isset($plan_mins[$plan_index]) ? wp_kses_post($plan_mins[$plan_index]) : '',
                    'features' => $plan_features
                );
            }
        }
    }

    // Thu thập Features Overview
    if (isset($_POST['feature_group_title']) && is_array($_POST['feature_group_title'])) {
        $group_titles = $_POST['feature_group_title'];

        foreach ($group_titles as $group_index => $group_title) {
            if (!empty($group_title)) {
                // Thu thập items cho group này
                $group_items = array();
                $item_titles_key = 'feature_item_title_' . $group_index;
                $item_summaries_key = 'feature_item_summary_' . $group_index;

                if (isset($_POST[$item_titles_key]) && is_array($_POST[$item_titles_key])) {
                    $item_titles = $_POST[$item_titles_key];
                    $item_summaries = isset($_POST[$item_summaries_key]) ? $_POST[$item_summaries_key] : array();

                    foreach ($item_titles as $item_index => $item_title) {
                        if (!empty($item_title)) {
                            $group_items[] = array(
                                'title' => wp_kses_post($item_title),
                                'summary' => isset($item_summaries[$item_index]) ? wp_kses_post($item_summaries[$item_index]) : ''
                            );
                        }
                    }
                }

                $description_data['features_overview'][] = array(
                    'title' => wp_kses_post($group_title),
                    'items' => $group_items
                );
            }
        }
    }
    // Thu thập Perfect For
    if (isset($_POST['perfect_for_title']) && is_array($_POST['perfect_for_title'])) {
        $pf_titles = $_POST['perfect_for_title'];
        $pf_icons = isset($_POST['perfect_for_icon']) ? $_POST['perfect_for_icon'] : array();
        $pf_summaries = isset($_POST['perfect_for_summary']) ? $_POST['perfect_for_summary'] : array();
        $pf_descs = isset($_POST['perfect_for_desc']) ? $_POST['perfect_for_desc'] : array();

        foreach ($pf_titles as $pf_index => $pf_title) {
            if (!empty($pf_title)) {
                $description_data['perfect_for'][] = array(
                    'title' => wp_kses_post($pf_title),
                    'icon' => isset($pf_icons[$pf_index]) ? wp_kses_post($pf_icons[$pf_index]) : '',
                    'summary' => isset($pf_summaries[$pf_index]) ? wp_kses_post($pf_summaries[$pf_index]) : '',
                    'desc' => isset($pf_descs[$pf_index]) ? wp_kses_post($pf_descs[$pf_index]) : ''
                );
            }
        }
    }

    // Thu thập Security & Compliance
    if (isset($_POST['security_encryption']) && is_array($_POST['security_encryption'])) {
        $description_data['security']['encryption'] = array_filter(array_map('wp_kses_post', $_POST['security_encryption']));
    }
    if (isset($_POST['security_compliance']) && is_array($_POST['security_compliance'])) {
        $description_data['security']['compliance'] = array_filter(array_map('wp_kses_post', $_POST['security_compliance']));
    }
    if (isset($_POST['security_authentication']) && is_array($_POST['security_authentication'])) {
        $description_data['security']['authentication'] = array_filter(array_map('wp_kses_post', $_POST['security_authentication']));
    }
    if (isset($_POST['security_privacy']) && is_array($_POST['security_privacy'])) {
        $description_data['security']['privacy'] = array_filter(array_map('wp_kses_post', $_POST['security_privacy']));
    }

    // Thu thập Customer Support
    if (isset($_POST['support_availability']) && is_array($_POST['support_availability'])) {
        $description_data['support']['availability'] = array_filter(array_map('wp_kses_post', $_POST['support_availability']));
    }
    if (isset($_POST['support_channels']) && is_array($_POST['support_channels'])) {
        $description_data['support']['support_channels'] = array_filter(array_map('wp_kses_post', $_POST['support_channels']));
    }
    if (isset($_POST['support_languages']) && is_array($_POST['support_languages'])) {
        $description_data['support']['languages'] = array_filter(array_map('wp_kses_post', $_POST['support_languages']));
    }
    if (isset($_POST['support_resources']) && is_array($_POST['support_resources'])) {
        $description_data['support']['resources'] = array_filter(array_map('wp_kses_post', $_POST['support_resources']));
    }
    // Thu thập User Reviews
    if (isset($_POST['review_rating']) && is_array($_POST['review_rating'])) {
        $ratings = $_POST['review_rating'];
        $comments = isset($_POST['review_comment']) ? $_POST['review_comment'] : array();
        $author_names = isset($_POST['review_author_name']) ? $_POST['review_author_name'] : array();
        $author_roles = isset($_POST['review_author_role']) ? $_POST['review_author_role'] : array();
        $dates = isset($_POST['review_date']) ? $_POST['review_date'] : array();

        foreach ($ratings as $review_index => $rating) {
            $description_data['user_reviews'][] = array(
                'rating' => wp_kses_post($rating),
                'comment' => isset($comments[$review_index]) ? wp_kses_post($comments[$review_index]) : '',
                'author_name' => isset($author_names[$review_index]) ? wp_kses_post($author_names[$review_index]) : '',
                'author_role' => isset($author_roles[$review_index]) ? wp_kses_post($author_roles[$review_index]) : '',
                'date' => isset($dates[$review_index]) ? wp_kses_post($dates[$review_index]) : ''
            );
        }
    }

    // Thu thập FAQ
    if (isset($_POST['faq_question']) && is_array($_POST['faq_question'])) {
        $questions = $_POST['faq_question'];
        $answers = isset($_POST['faq_answer']) ? $_POST['faq_answer'] : array();

        foreach ($questions as $faq_index => $question) {
            if (!empty($question)) {
                $description_data['faq'][] = array(
                    'question' => wp_kses_post($question),
                    'answer' => isset($answers[$faq_index]) ? wp_kses_post($answers[$faq_index]) : ''
                );
            }
        }
    }

    if (isset($_POST['metric_icon']) && is_array($_POST['metric_icon'])) {
        $metric_icons = $_POST['metric_icon'];
        $metric_tags = isset($_POST['metric_tag']) ? $_POST['metric_tag'] : array();
        $metric_titles = isset($_POST['metric_title']) ? $_POST['metric_title'] : array();
        $metric_values = isset($_POST['metric_value']) ? $_POST['metric_value'] : array();
        $metric_subtitles = isset($_POST['metric_subtitle']) ? $_POST['metric_subtitle'] : array();

        foreach ($metric_icons as $metric_index => $metric_icon) {
            $description_data['performance_metrics'][] = array(
                'icon' => wp_kses_post($metric_icon),
                'tag' => isset($metric_tags[$metric_index]) ? wp_kses_post($metric_tags[$metric_index]) : '',
                'title' => isset($metric_titles[$metric_index]) ? wp_kses_post($metric_titles[$metric_index]) : '',
                'value' => isset($metric_values[$metric_index]) ? wp_kses_post($metric_values[$metric_index]) : '',
                'subtitle' => isset($metric_subtitles[$metric_index]) ? wp_kses_post($metric_subtitles[$metric_index]) : ''
            );
        }
    }

    // Merge với data cũ
    $existing_data['description'] = $description_data;

    // Serialize và lưu
    // update_post_meta($post_id, '_provider_data', serialize($existing_data));
    update_post_meta($post_id, '_provider_data', maybe_serialize($existing_data));

}
add_action('save_post_providers', 'save_provider_description');


// ==================================== REST API ===================================
// Thêm field provider_data vào REST API endpoint mặc định
function add_provider_data_to_rest_api()
{
    register_rest_field('providers', 'provider_data', array(
        'get_callback' => 'get_provider_data_for_api',
        'update_callback' => null,
        'schema' => array(
            'description' => 'Provider data (unserialized)',
            'type' => 'object'
        )
    ));
}
add_action('rest_api_init', 'add_provider_data_to_rest_api');

// Callback để lấy dữ liệu provider_data
function get_provider_data_for_api($object)
{
    $provider_id = $object['id'];

    // Lấy dữ liệu serialize từ meta
    $provider_data = get_post_meta($provider_id, '_provider_data', true);

    // Unserialize nếu có dữ liệu
    if (!empty($provider_data) && is_string($provider_data)) {
        $provider_data = maybe_unserialize($provider_data);
    }

    // Trả về data đã unserialize hoặc object rỗng
    if (is_array($provider_data)) {
        // Lấy home info data
        $home_data = array(
            'tags' => isset($provider_data['tags']) ? $provider_data['tags'] : array(),
            'logo' => isset($provider_data['logo']) ? $provider_data['logo'] : '',
            'thumbnail' => isset($provider_data['thumbnail']) ? $provider_data['thumbnail'] : '',
            'summary' => isset($provider_data['summary']) ? $provider_data['summary'] : '',
            'rating' => isset($provider_data['rating']) ? floatval($provider_data['rating']) : 0,
            'advanced' => isset($provider_data['advanced']) ? $provider_data['advanced'] : array(),
            'price' => isset($provider_data['price']) ? floatval($provider_data['price']) : 0
        );

        // Lấy description data (tất cả sections)
        $description_data = array();
        if (isset($provider_data['description']) && is_array($provider_data['description'])) {
            $desc = $provider_data['description'];

            $description_data = array(
                'overview' => isset($desc['overview']) ? $desc['overview'] : '',
                'our_verdict' => isset($desc['our_verdict']) ? $desc['our_verdict'] : '',
                'best_for' => isset($desc['best_for']) ? $desc['best_for'] : array(),
                'not_ideal_for' => isset($desc['not_ideal_for']) ? $desc['not_ideal_for'] : array(),
                'detailed_ratings' => isset($desc['detailed_ratings']) ? $desc['detailed_ratings'] : array(),
                'pricing_plans' => isset($desc['pricing_plans']) ? $desc['pricing_plans'] : array(),
                'features_overview' => isset($desc['features_overview']) ? $desc['features_overview'] : array(),
                'perfect_for' => isset($desc['perfect_for']) ? $desc['perfect_for'] : array(),
                'security' => isset($desc['security']) ? $desc['security'] : array(
                    'encryption' => array(),
                    'compliance' => array(),
                    'authentication' => array(),
                    'privacy' => array()
                ),
                'support' => isset($desc['support']) ? $desc['support'] : array(
                    'availability' => array(),
                    'support_channels' => array(),
                    'languages' => array(),
                    'resources' => array()
                ),
                'user_reviews' => isset($desc['user_reviews']) ? $desc['user_reviews'] : array(),
                'faq' => isset($desc['faq']) ? $desc['faq'] : array(),
                'performance_metrics' => isset($desc['performance_metrics']) ? $desc['performance_metrics'] : array(),

            );
        }

        // Merge home data và description data
        return array_merge($home_data, array('description' => $description_data));
    }

    // Return default structure nếu không có data
    return array(
        'tags' => array(),
        'logo' => '',
        'thumbnail' => '',
        'summary' => '',
        'rating' => 0,
        'advanced' => array(),
        'price' => 0,
        'description' => array(
            'overview' => '',
            'our_verdict' => '',
            'best_for' => array(),
            'not_ideal_for' => array(),
            'detailed_ratings' => array(),
            'pricing_plans' => array(),
            'features_overview' => array(),
            'perfect_for' => array(),
            'security' => array(
                'encryption' => array(),
                'compliance' => array(),
                'authentication' => array(),
                'privacy' => array()
            ),
            'support' => array(
                'availability' => array(),
                'support_channels' => array(),
                'languages' => array(),
                'resources' => array()
            ),
            'user_reviews' => array(),
            'faq' => array(),
            'performance_metrics'=>array()
        )
    );
}



