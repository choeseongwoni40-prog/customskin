<?php
/**
 * Footer template
 */
?>

<!-- 앵커 광고 - 하단 -->
<div id="anchor-ad-bottom"></div>

<footer class="site-footer">
    <div class="footer-content">
        <div class="footer-left">
            <div class="footer-brand"><?php bloginfo('name'); ?></div>
            <ul class="footer-info">
                <li>📍 <?php echo get_option('footer_address', '사업자 주소'); ?></li>
                <li>🏢 사업자 번호: <?php echo get_option('business_number', '123-45-67890'); ?></li>
            </ul>
        </div>
        <div class="footer-right">
            <p>제작자: 아로스</p>
            <p>홈페이지: <a href="https://aros100.com" target="_blank">바로가기</a></p>
            <p class="footer-copyright">Copyrights © 2020 All Rights Reserved by (주)아백</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
