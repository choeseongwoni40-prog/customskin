<?php
/**
 * Theme functions and definitions
 */

// 테마 설정
function testtheme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    register_nav_menus(array(
        'primary' => '주 메뉴',
    ));
}
add_action('after_setup_theme', 'testtheme_setup');

// 스타일 & 스크립트 등록
function testtheme_scripts() {
    wp_enqueue_style('testtheme-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_script('testtheme-custom', get_template_directory_uri() . '/custom.js', array('jquery'), '1.0.0', true);
    
    // 광고 스크립트
    if (get_option('adsense_publisher_id')) {
        wp_enqueue_script('google-adsense', 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . get_option('adsense_publisher_id'), array(), null, false);
        add_filter('script_loader_tag', 'testtheme_async_scripts', 10, 2);
    }
    
    // Ajax 설정
    wp_localize_script('testtheme-custom', 'themeAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('theme_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'testtheme_scripts');

// 스크립트 async 속성 추가
function testtheme_async_scripts($tag, $handle) {
    if ('google-adsense' === $handle) {
        return str_replace(' src', ' async crossorigin="anonymous" src', $tag);
    }
    return $tag;
}

// 관리자 메뉴 추가
function testtheme_admin_menu() {
    add_menu_page(
        '테마 설정',
        '지원금 테마',
        'manage_options',
        'testtheme-settings',
        'testtheme_admin_page',
        'dashicons-admin-generic',
        3
    );
}
add_action('admin_menu', 'testtheme_admin_menu');

// 관리자 페이지
function testtheme_admin_page() {
    require_once get_template_directory() . '/admin-page.php';
}

// 광고 코드 분석 및 변환
function analyze_and_convert_ad_code($ad_code) {
    if (empty($ad_code)) return false;
    
    // Publisher ID 추출
    preg_match('/data-ad-client=["'](ca-pub-[^"']+)["']/i', $ad_code, $pub_matches);
    // Ad Slot 추출
    preg_match('/data-ad-slot=["'](\d+)["']/i', $ad_code, $slot_matches);
    
    if (!empty($pub_matches[1])) {
        update_option('adsense_publisher_id', $pub_matches[1]);
    }
    if (!empty($slot_matches[1])) {
        update_option('adsense_ad_slot', $slot_matches[1]);
    }
    
    return array(
        'publisher_id' => $pub_matches[1] ?? '',
        'ad_slot' => $slot_matches[1] ?? ''
    );
}

// 네이티브 광고 생성 (고클릭률 스타일)
function get_native_ad() {
    $pub_id = get_option('adsense_publisher_id');
    if (!$pub_id) return '';
    
    return '
    <div class="native-ad-wrapper" style="background: #f8f9fa; border-radius: 16px; padding: 20px; border: 2px solid #e3f2fd;">
        <p style="font-size: 12px; color: #666; margin-bottom: 8px; text-align: center;">추천 정보</p>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-format="fluid"
             data-ad-layout-key="-6t+ed+2i-1n-4w"
             data-ad-client="' . esc_attr($pub_id) . '"
             data-ad-slot="' . esc_attr(get_option('adsense_ad_slot', '')) . '"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>';
}

// 카스텀 포스트 타입: 지원금 카드
function testtheme_register_support_cards() {
    register_post_type('support_card', array(
        'labels' => array(
            'name' => '지원금 카드',
            'singular_name' => '카드',
            'add_new' => '카드 추가',
            'add_new_item' => '새 카드 추가',
            'edit_item' => '카드 편집',
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-id-alt',
        'supports' => array('title', 'editor', 'custom-fields'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'testtheme_register_support_cards');

// 카드 메타박스
function testtheme_add_card_metaboxes() {
    add_meta_box(
        'testtheme_card_details',
        '카드 상세 정보',
        'testtheme_card_metabox_callback',
        'support_card',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'testtheme_add_card_metaboxes');

function testtheme_card_metabox_callback($post) {
    wp_nonce_field('testtheme_save_card_data', 'testtheme_card_nonce');
    
    $amount = get_post_meta($post->ID, '_card_amount', true);
    $amount_sub = get_post_meta($post->ID, '_card_amount_sub', true);
    $target = get_post_meta($post->ID, '_card_target', true);
    $period = get_post_meta($post->ID, '_card_period', true);
    $link = get_post_meta($post->ID, '_card_link', true);
    
    echo '<p><label>금액/혜택: <input type="text" name="card_amount" value="' . esc_attr($amount) . '" style="width:100%;" /></label></p>';
    echo '<p><label>부가 설명: <input type="text" name="card_amount_sub" value="' . esc_attr($amount_sub) . '" style="width:100%;" /></label></p>';
    echo '<p><label>지원대상: <input type="text" name="card_target" value="' . esc_attr($target) . '" style="width:100%;" maxlength="20" /></label></p>';
    echo '<p><label>신청시기: <input type="text" name="card_period" value="' . esc_attr($period) . '" style="width:100%;" /></label></p>';
    echo '<p><label>링크 URL: <input type="url" name="card_link" value="' . esc_attr($link) . '" style="width:100%;" /></label></p>';
}

function testtheme_save_card_data($post_id) {
    if (!isset($_POST['testtheme_card_nonce']) || !wp_verify_nonce($_POST['testtheme_card_nonce'], 'testtheme_save_card_data')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['card_amount'])) {
        update_post_meta($post_id, '_card_amount', sanitize_text_field($_POST['card_amount']));
    }
    if (isset($_POST['card_amount_sub'])) {
        update_post_meta($post_id, '_card_amount_sub', sanitize_text_field($_POST['card_amount_sub']));
    }
    if (isset($_POST['card_target'])) {
        update_post_meta($post_id, '_card_target', sanitize_text_field($_POST['card_target']));
    }
    if (isset($_POST['card_period'])) {
        update_post_meta($post_id, '_card_period', sanitize_text_field($_POST['card_period']));
    }
    if (isset($_POST['card_link'])) {
        update_post_meta($post_id, '_card_link', esc_url_raw($_POST['card_link']));
    }
}
add_action('save_post', 'testtheme_save_card_data');
?>
