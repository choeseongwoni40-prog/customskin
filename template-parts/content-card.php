<?php
/**
 * Template part for displaying support cards
 */

$is_featured = $args['featured'] ?? false;
$amount = get_post_meta(get_the_ID(), '_card_amount', true);
$amount_sub = get_post_meta(get_the_ID(), '_card_amount_sub', true);
$target = get_post_meta(get_the_ID(), '_card_target', true);
$period = get_post_meta(get_the_ID(), '_card_period', true);
$link = get_post_meta(get_the_ID(), '_card_link', true) ?: get_permalink();
?>

<a href="<?php echo esc_url($link); ?>" class="support-card<?php echo $is_featured ? ' featured' : ''; ?>">
    <div class="card-highlight">
        <?php if ($is_featured) : ?>
            <span class="card-badge">🔥 인기</span>
        <?php endif; ?>
        <div class="card-amount"><?php echo esc_html($amount ?: '혜택 정보'); ?></div>
        <div class="card-amount-sub"><?php echo esc_html($amount_sub); ?></div>
    </div>
    
    <div class="card-content">
        <h3 class="card-title"><?php the_title(); ?></h3>
        <p class="card-desc"><?php echo wp_trim_words(get_the_content(), 20); ?></p>
        
        <div class="card-details">
            <?php if ($target) : ?>
            <div class="card-row">
                <span class="card-label">지원대상</span>
                <span class="card-value"><?php echo esc_html($target); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($period) : ?>
            <div class="card-row">
                <span class="card-label">신청시기</span>
                <span class="card-value"><?php echo esc_html($period); ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="card-btn">
            지금 바로 신청하기 <span>→</span>
        </div>
    </div>
</a>
