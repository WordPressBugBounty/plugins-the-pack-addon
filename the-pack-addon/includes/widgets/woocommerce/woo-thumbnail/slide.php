<?php
$icon = $settings['arrow'] ? the_pack_render_icon($settings['arrow'], '') : '';

$slider_options = [
    'type' => esc_attr($settings['disp']),
    'navigation' => esc_attr($settings['navi']),
    'thumb_item' => esc_attr($settings['ti']['size']),
    'thumb_gap' => esc_attr($settings['tgap']['size']),
];

echo '<div data-xld =\'' . wp_json_encode($slider_options). '\' class="thepack-product-images ' . esc_attr($settings['navi']) . '">';
 
?>

  <div class="swiper mySwiper2">
    <div class="swiper-wrapper">
      <?php echo $main;?>
    </div> 
    <?php if($settings['nav']){?> 
      <div class="product-thunb-prev thumb-arrow"><?php echo $icon;?></div>
      <div class="product-thunb-next thumb-arrow"><?php echo $icon;?></div>
    <?php } ?>
    <div class="swiper-pagination"></div>
  </div>
  <?php if($settings['navi']=='bottom_thumb' || $settings['navi']=='left_thumb'){?>
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
        <?php echo $nav;?>
        </div>
    </div>
  <?php } ?>
</div>  
<style>
.thepack-product-images.left_thumb{
  display: flex;
    flex-direction: row-reverse;  
}
.thepack-product-images.left_thumb .mySwiper{
flex: 0 0 80px;  
}
.thepack-product-images.hide-thumb-controls .mySwiper{
  display: none;
}
.thepack-product-images .product-thunb-next,.thepack-product-images .product-thunb-prev{
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1;  
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;    
}
.thepack-product-images .product-thunb-next{
  left:10px;
}
.thepack-product-images .product-thunb-prev{
  right:10px;
}
.thepack-product-images .product-thunb-prev i::before{
transform: rotate(180deg);  
}
.bottom_thumb .swiper.mySwiper{
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    bottom: 0px;
    max-width: 250px;
}

.bottom_thumb .mySwiper img{
    border: 1px solid blue;
    cursor: pointer;
}

</style> 