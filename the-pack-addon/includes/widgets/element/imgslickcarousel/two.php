<?php
$link = thepack_get_that_link($settings['url']);
$btnicon = the_pack_render_icon($settings['btni'],'tbtr');
$btn = $link ? '<a class="tpbtn tp-dinflex" ' . $link . '>'.$btnicon.'</a>' : '';
$label = thepack_build_html($settings['ttl'], 'h3', 'title com-text');
$desc = thepack_build_html($settings['dsc'], 'p', 'desc com-text');
?>
<div class="tpigbx tbtr style-two tp-pos-rel tp-no-overflow">
    <div class="inner">
        <?php echo wp_kses_post($label.$desc);?> 
        <div class="imgwrap tbtr"><?php echo thepack_ft_images($settings['img']['id'],'full');?></div>
        <?php echo wp_kses_post($btn);?> 
    </div>
</div>