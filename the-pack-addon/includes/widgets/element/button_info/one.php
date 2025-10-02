<?php
$link = thepack_get_that_link($settings['flink']);
$icon = $settings['ikn'] ? the_pack_render_icon($settings['ikn'], 'actikn') : '';
?>

<a <?php echo wp_kses_post($link);?> class="tp-btn-2">
    <span class="tp-icon duplicated com-bg"><?php echo wp_kses_post($icon);?></span>
    <span class="tp-text com-bg"><?php echo esc_attr($settings['title']);?></span>
    <span class="tp-icon main com-bg"><?php echo wp_kses_post($icon);?></span>
</a>
