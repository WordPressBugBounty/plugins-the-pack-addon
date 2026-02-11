<?php
$content = $label = '';
foreach ($settings['tabs'] as $a) {
    $icon = the_pack_render_icon($a['icon'], 'actikn');
    $pre = thepack_build_html($a['pre'],'span','pre');
    $label .= '<li class="tbtr">'.$pre.'<div class="tp-flex-equal">'.$icon.'<span>' . $a['title'] . '</span></div></li>';
    $content .= '<div class="tab-content">' . $this->icon_image($a) . '</div>';
}
$cls = $settings['style'].' '.$settings['preabs'];
?> 
<div class="tp-tab tp-tab-1 tp-flex-equal <?php echo esc_attr($cls);?>">
    <ul class="tab-area tp-flex-equal raw-style">
      <?php //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		  <?php echo thepack_build_html($label); ?>
    </ul>
    <div class="tab-wrap">
      <?php //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>  
		  <?php echo thepack_build_html($content); ?>
    </div>
</div>
 
 