<?php

$content ='';
foreach ($settings['tabs'] as $a) {
    $text = $a['type'] == 'text' ? thepack_build_html('<span>'.$a['txt'].'</span>','span','txt elementor-repeater-item-'.$a['_id'].'') : '';
    $img = $a['type'] == 'img' ? wp_get_attachment_image($a['img']['id'], 'full','',["class" => 'img elementor-repeater-item-'.$a['_id'].'']) : '';
    $icon = $a['type'] == 'icon' ? the_pack_render_icon($a['icon'],'txt icon elementor-repeater-item-'.$a['_id'].'') : '';
    $br = $a['type'] == 'br' ? '<br>' : '';
    $content .= $text.$img.$icon.$br ;
}
echo '<div class="tp-mixed-title">'.wp_kses_post($content).'</div>'; 
?>
