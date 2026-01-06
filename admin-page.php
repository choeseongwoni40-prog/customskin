<?php
/**
 * Admin Settings Page
 */
if (!defined('ABSPATH')) exit;

// 설정 저장
if (isset($_POST['save_settings']) && check_admin_referer('theme_settings_nonce')) {
    if (isset($_POST['ad_code'])) {
        $ad_code = wp_unslash($_POST['ad_code']);
        analyze_and_convert_ad_code($ad_code);
        update_option('theme_raw_ad_code', $ad_code);
    }
    
    if (isset($_POST['footer_address'])) {
        update_option('footer_address', sanitize_text_field($_POST['footer_address']));
    }
    
    if (isset($_POST['business_number'])) {
        update_option('business_number', sanitize_text_field($_POST['business_number']));
    }
    
    echo '<div class="notice notice-success"><p>설정이 저장되었습니다.</p></div>';
}

$ad_code = get_option('theme_raw_ad_code', '');
$footer_address = get_option('footer_address', '');
$business_number = get_option('business_number', '');
?>

<div class="wrap">
    <h1>지원금 테마 설정</h1>
    
    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div style="margin-bottom: 30px;">
            <h2>광고 설정</h2>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">애드센스 광고 코드</label>
                <textarea id="ad_code" name="ad_code" rows="6" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: monospace;"><?php echo esc_textarea($ad_code); ?></textarea>
                <p style="color: #666; margin-top: 8px;">애드센스 광고 코드를 붙여넣으면 자동으로 전면, 앵커, 네이티브 광고로 변환됩니다.</p>
                <?php if (get_option('adsense_publisher_id')): ?>
                    <p style="color: green;">✓ 현재 Publisher ID: <?php echo esc_html(get_option('adsense_publisher_id')); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div style="margin-bottom: 30px;">
            <h2>푸터 설정</h2>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">사업자 주소</label>
                <input type="text" id="footer_address" name="footer_address" value="<?php echo esc_attr($footer_address); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" />
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 8px;">사업자 번호</label>
                <input type="text" id="business_number" name="business_number" value="<?php echo esc_attr($business_number); ?>" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;" />
            </div>
        </div>
        
        <button onclick="saveSettings()" class="button button-primary" style="padding: 10px 20px; font-size: 16px;">설정 저장</button>
    </div>
    
    <div style="background: #f0f9ff; border-left: 4px solid #3182f6; padding: 20px; margin-top: 30px; border-radius: 4px;">
        <h3>💡 사용 가이드</h3>
        <ul style="line-height: 1.8;">
            <li><strong>광고 설정:</strong> 애드센스 코드를 한 번만 입력하면 자동으로 여러 광고 형태로 변환됩니다.</li>
            <li><strong>전면 광고:</strong> 페이지 전환 시 1분 간격으로 자동 표시됩니다.</li>
            <li><strong>앵커 광고:</strong> 상단과 하단에 고정되어 표시됩니다.</li>
            <li><strong>네이티브 광고:</strong> 카드 사이사이에 자연스럽게 삽입됩니다.</li>
            <li><strong>지원금 카드:</strong> <a href="<?php echo admin_url('edit.php?post_type=support_card'); ?>">여기</a>에서 새 카드를 추가하면 자동으로 메인 페이지에 표시됩니다.</li>
        </ul>
    </div>
</div>

<script>
function saveSettings() {
    const data = new FormData();
    data.append('action', 'save_theme_settings');
    data.append('nonce', '<?php echo wp_create_nonce("theme_settings_save"); ?>');
    data.append('ad_code', document.getElementById('ad_code').value);
    data.append('footer_address', document.getElementById('footer_address').value);
    data.append('business_number', document.getElementById('business_number').value);
    
    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('설정이 저장되었습니다.');
            location.reload();
        } else {
            alert('저장 중 오류가 발생했습니다.');
        }
    });
}
</script>

<?php
// AJAX 핸들러
add_action('wp_ajax_save_theme_settings', function() {
    check_ajax_referer('theme_settings_save', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('권한이 없습니다.');
    }
    
    if (isset($_POST['ad_code'])) {
        $ad_code = wp_unslash($_POST['ad_code']);
        analyze_and_convert_ad_code($ad_code);
        update_option('theme_raw_ad_code', $ad_code);
    }
    
    if (isset($_POST['footer_address'])) {
        update_option('footer_address', sanitize_text_field($_POST['footer_address']));
    }
    
    if (isset($_POST['business_number'])) {
        update_option('business_number', sanitize_text_field($_POST['business_number']));
    }
    
    wp_send_json_success('설정이 저장되었습니다.');
});
?>
