<?php
$icon = the_pack_render_icon( $settings['tap'],'open');
$tap = $settings['btn'] || $settings['tap']  ? '<span class="tp-tap">'.$settings['btn'].$icon.'</span>' : '';
$close_sidebar = the_pack_render_icon( $settings['tpoffclose'],'close-menu');
?>

<div class="tp-off-sidebar xlmega-header"> 
    <?php //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?php echo thepack_build_html($tap);?>
    <div class="offsidebar tpbefore <?php echo esc_attr($settings['pos']);?>">
        
            <?php
            echo thepack_build_html($close_sidebar);
            echo do_shortcode('[THEPACK_INSERT_TPL id="' . $settings['template'] . '"]');
            ?> 
        
    </div>
    <div class="click-capture"></div>
</div>
