<?php
global $product;
$id = $product->get_id();
$gallery_image_ids = $product->get_gallery_image_ids();
$thumbid = [get_post_thumbnail_id( $id )];
$first_gallery_image_id = $gallery_image_ids ? $gallery_image_ids[0] : '';
$merge_ids = array_merge($gallery_image_ids,$thumbid);
$unique_ids = array_unique($merge_ids);
echo '<div class="tp-thumb-wrap tp-pos-rel tp-no-overflow">'; 
echo '<span class="dev-ash-wishlist-remove tp-dinflex" data-product-id="'.$id.'"><i class="tpbi bi-x"></i></span>';
echo thepack_ft_images($id,'full');
echo '<span class="hover-swap tbtr">'.thepack_ft_images($first_gallery_image_id,'full').'</span>';
include THE_PACK_PLUGIN_DIR . 'includes/woocommerce/parts/add-to-cart.php';
echo '</div>';

