
<div class="tp-woo-product-layout">
<?php
    $class = 'grid';
    switch ($settings['disp']) {
        case "grid":
            $class = "tp-d-flex tp-gutter";
            break;
        case "slider":
            $class = "swiper-wrapper";
            break;
        case "masonry":
            $class = "tp-masonry";
            break;
    }
?>

<?php
do_action( 'woocommerce_before_main_content' );   
// column_per_row. 

if ( woocommerce_product_loop() ) {
	do_action( 'woocommerce_before_shop_loop' );
	echo '<div class="result-item '.$class.'">';
	 
	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );
			
			echo '<div class="swiper-slide">';
			include THE_PACK_PLUGIN_DIR . 'includes/woocommerce/custom-loop.php';
			echo '</div>';
			
		}
	}  
	echo '</div>';

	do_action( 'woocommerce_after_shop_loop' );
	
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );

}
do_action( 'woocommerce_after_main_content' );

?>
</div> 
<style>
    .tp-before-shop .woocommerce-result-count,.tp-before-shop .woocommerce-ordering{
        margin-bottom: 0px;
    }
	.tp-before-shop{
		justify-content: space-between;
	}
	.tp-before-shop .orderby{
		field-sizing: content;
		width: auto;		
		padding: 0;
	}
	.tp-before-shop-inner{
		gap: 15px;
	}
	.tp-before-shop .side-filter-btn,.tp-before-shop .orderby{
		cursor: pointer;
	}
	.tp-woo-product-layout .woocommerce-pagination .page-numbers{
		display: flex !important;
align-items: center;
    justify-content: center;		
	}
	.tp-woo-product-layout .woocommerce-pagination .page-numbers li{
		border-right: 0px !important;
	}
	.tp-woo-product-layout .woocommerce-pagination>.page-numbers{
		border:none !important;
	}
	.side-filter-btn{
		display: none;
	}
	@media only screen and (max-width: 1025px) {
		.tp-before-shop .woocommerce-result-count{
			display: none;
		}
	}
</style>


