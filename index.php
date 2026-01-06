<?php
/**
 * Main template file
 */
get_header(); ?>

<main class="site-content">
    <?php if (have_posts()) : ?>
        
        <!-- 인트로 섹션 -->
        <div class="intro-section">
            <span class="intro-badge">신청마감 D-3일</span>
            <p class="intro-sub">숨은 보험금 1분만에 찾기!</p>
            <h2 class="intro-title">숨은 지원금 찾기</h2>
        </div>

        <!-- 정보 박스 -->
        <div class="info-box">
            <div class="info-box-header">
                <span>🏷️</span>
                <span class="info-box-title">신청 안하면 절대 못 받아요</span>
            </div>
            <div class="info-box-amount">1인 평균 127만원 환급</div>
            <p class="info-box-desc">대한민국 92%가 놓치고 있는 정부 지원금! 지금 확인하고 혜택 놓치지 마세요.</p>
        </div>

        <!-- 광고 - 상단 네이티브 -->
        <div class="ad-container">
            <?php echo get_native_ad(); ?>
        </div>

        <!-- 지원금 카드 그리드 -->
        <div class="support-cards-grid">
            <?php
            $args = array(
                'post_type' => 'support_card',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            
            $card_query = new WP_Query($args);
            $card_count = 0;
            
            if ($card_query->have_posts()) :
                while ($card_query->have_posts()) : $card_query->the_post();
                    $card_count++;
                    $is_featured = ($card_count === 1);
                    get_template_part('template-parts/content', 'card', array('featured' => $is_featured));
                    
                    // 광고 삽입 (3번째, 6번째 카드 후)
                    if ($card_count === 3 || $card_count === 6) {
                        echo '<div class="native-ad-card">' . get_native_ad() . '</div>';
                    }
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <!-- 히어로 CTA -->
        <div class="hero-cta-section">
            <span class="hero-urgent">🔥 신청마감 D-3일</span>
            <h2 class="hero-title">
                나의 <span class="hero-highlight">숨은 지원금</span> 찾기
            </h2>
            <p class="hero-amount">신청자 <strong>1인 평균 127만원</strong> 수령</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="hero-cta-btn">
                30초만에 내 지원금 확인 <span>→</span>
            </a>
        </div>

    <?php else : ?>
        <p>게시글이 없습니다.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
