<?php
if ( empty( $product ) ) {
	return;
}
if( $product->is_on_sale()){
	$settings['price_cls'] = 'sale-price';
} else {
	$settings['price_cls'] = 'regular-price';
}

woocommerce_template_single_price();
 
?>

<style>
	.price ins{
		text-decoration: none;
	}
	.price del{
		opacity:1;
	}
.price del bdi::after {
  content: ''; 
  position: absolute;
  top: 50%; 
  left: 0;
  right: 0;
  height: 1px;
  background: currentColor; 
  transform: translateY(-50%); 
}
.price del bdi{
    position: relative;
}
.price del{
	text-decoration: none;
}
.elementor-widget-tp_wooprice .price,.woocommerce div.product form.variations_form.cart{
	margin-bottom: 0px !important;
}
</style>