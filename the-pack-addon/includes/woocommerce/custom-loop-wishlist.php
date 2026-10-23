<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0  swiper-slide
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
if ( wp_doing_ajax() ) {
	//var_dump($settings);
}
$id = $product->get_id();	 

?> 
<div <?php wc_product_class(); ?>>
	<?php
	echo '<div class="tp-woo-style-one com-hover">';
	include THE_PACK_PLUGIN_DIR . 'includes/woocommerce/parts/product-thumb-wishlist.php';
	
	echo '<div class="product-bottom">';
	call_user_func('The_Pack_Woo_Helper::product_title');	
	call_user_func('The_Pack_Woo_Helper::product_cat',$product->get_id());	
	call_user_func('The_Pack_Woo_Helper::product_rating',$product,$settings);
	echo '<div class="price-wrap">'.$product->get_price_html().'</div>';
	echo '</div>';
	echo '</div>';
	?> 
</div> 