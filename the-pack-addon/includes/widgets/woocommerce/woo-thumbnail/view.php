<?php

if (isset($_POST['id'])) {
$product = wc_get_product($_POST['id']);
} else {
global $product;
}

if ( empty( $product ) ) {
	return;
}

$id = $product->get_id();
$gallery_image_ids = $product->get_gallery_image_ids();
$thumbid = [get_post_thumbnail_id( $id )];
$merge_ids = array_merge($gallery_image_ids,$thumbid);
$unique_ids = array_unique($merge_ids);

$main = $nav = '';
foreach ($unique_ids as $item) {
	$main .= '<div class="swiper-slide"><a href="'.wp_get_attachment_url($item,'full').'">'.thepack_ft_images($item,$settings['img_size'],'tp-hover-thumb').'</a></div>';
  $nav .= '<div class="swiper-slide">'.thepack_ft_images($item,'thumbnail','tp-hover-thumb').'</div>';
}

if (ctype_alnum($settings['disp']) ){
    require dirname(__FILE__) . '/' .esc_attr($settings['disp']).'.php';
}

?>
 