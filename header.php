<?php
/**
 * Header template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- 전면 광고 컨테이너 -->
<div id="interstitial-ad-overlay"></div>

<header class="site-header">
    <div class="header-container">
        <?php if (has_custom_logo()) : ?>
            <div class="site-logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php endif; ?>
        <h1 class="site-title"><?php echo esc_html('테스트테마' ?: get_bloginfo('name')); ?></h1>
    </div>
</header>

<nav class="tab-navigation">
    <div class="tab-container">
        <ul class="tab-menu">

        </ul>
    </div>
</nav>

<!-- 앵커 광고 - 상단 -->
<div id="anchor-ad-top"></div>
