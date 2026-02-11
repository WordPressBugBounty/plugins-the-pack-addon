<?php
$link = thepack_get_that_link($settings['url']);
$btn = $settings['url']['url'] ? '<a '.$link.' class="tp-read">'.$settings['btn'].'</a>' : '';
?>

<div class="tb-altimage tp-d-flex tp-no-overflow tp-pos-rel <?php echo esc_attr($settings['algn']);?>">
		<div class="bgthumb fullink tbtr">
      <?php echo thepack_ft_images($settings['img']['id'],'full','tbtr');?>
    </div>
    <div class="icon-wrap tp-dinflex">
      <?php echo the_pack_render_icon( $settings['icon']);?>
    </div>
    <div class="content-wrap">
        <?php echo thepack_build_html($settings['title'],'h3','title');?>
        <?php echo thepack_build_html($settings['desc'],'p','desc');?> 
        <?php echo thepack_build_html($btn);?>
    </div>
</div> 