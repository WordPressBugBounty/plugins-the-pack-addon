<?php
global $product;
$id = $product->get_id();
$gallery_image_ids = $product->get_gallery_image_ids();
$thumbid = [get_post_thumbnail_id( $id )];
$merge_ids = array_merge($gallery_image_ids,$thumbid);
$unique_ids = array_unique($merge_ids);

$option = [
	'type'=> $settings['onsale'],
	'label'=> 'Sale',
];

$first = 0;
$info = $nav = '';
foreach ($unique_ids as $item) {
    $first++;
    $first_element = $first == '1' ? 'current' : '';  
    $src = wp_get_attachment_image_src( $item, $img_size );
    if($src){
        $info .= '<div data-src="'.$src[0].'" class="tp-item ' . $first_element . '" data-tab="'.$first.'"></div>';
    } 

    $nav .= '<span class="thumb-dot '.$first_element.' dot-'.$first.'"></span>'; 
    
}
echo '<div class="tp-thumb-hover-slide-wrap tp-pos-rel">';
echo '<div class="tp-grid-thumb-wrap">';
echo $info;
echo '</div>';
echo thepack_ft_images($id,$img_size,'tp-hover-thumb');
if(count($unique_ids)>1){
    echo '<div class="thumb-dot-wrap">'.$nav.'</div>';
}
call_user_func('The_Pack_Woo_Helper::quick_hover_actions');
call_user_func('The_Pack_Woo_Helper::on_sale', $product,$option);
call_user_func('The_Pack_Woo_Helper::product_sale_end',$id);
include THE_PACK_PLUGIN_DIR . 'includes/woocommerce/parts/add-to-cart.php';
echo '</div>';
 