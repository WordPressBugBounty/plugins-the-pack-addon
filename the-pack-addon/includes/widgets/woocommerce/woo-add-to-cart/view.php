<?php
global $product;
if ( empty( $product ) ) {
	return;
}
$url = '';   
switch ($settings['action']) {
    case 'cart':
        $url = wc_get_cart_url();
        break;
    case 'checkout':
        $url = wc_get_checkout_url();
        break;
}

// add_filter( 'woocommerce_get_stock_html', function ( $html, $product ) {
//     return '';
//   }, 10, 2);
 
$options = [
  'redirect'=>$url,
];  

$cls = $product->get_type();
echo '<div class="tpsinglecart '.$cls.'" data-xld =\'' . wp_json_encode($options) . '\'>';
  woocommerce_template_single_add_to_cart();
echo '</div>';
?>