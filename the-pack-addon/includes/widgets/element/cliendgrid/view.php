<?php
$swiper_opt = the_pack_swiper_markup($settings);
  
switch ($settings['disp']) {
    case 'slider':
        $cls = 'swiper-slide';
        break;

    case 'grid':
        $cls = 'items';
        break;
 
    default:
}

if ($settings['disp'] == 'slider') {
    echo '<div class="swiper-container tpswiper clientslide" data-xld =\'' . wp_kses_post(wp_json_encode($swiper_opt['settings'])) . '\'>
                <div class="swiper-wrapper tb-clientwrap1">';?>
                    <?php //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?> 
                    <?php echo thepack_build_html($this->content($settings['items'], $cls));?>
                </div>
                <?php //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php echo $swiper_opt['nav']; ?>
            </div>';
<?php } else {
    //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    echo '<div class="tb-clientwrap1"><div class="tb-clientgrid">' . thepack_build_html($this->content($settings['items'], $cls)) . '</div></div>';
}
