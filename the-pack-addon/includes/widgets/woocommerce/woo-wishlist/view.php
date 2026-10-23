<?php
$wishlist = new Dev_Ash_Wishlist();
$id = $wishlist->get_wishlist_items(); 
	$wp_query_args = [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	];

	$wp_query_args['post__in'] = $id;
	$wp_query = new \WP_Query( $wp_query_args );
	wc_set_loop_prop( 'total', $wp_query->found_posts );

    echo '<div class="result-item tp-d-flex tp-gutter">';
   if ($wp_query->have_posts() && $id) {
       if ( wc_get_loop_prop( 'total' ) ) {
           while ( $wp_query->have_posts() ) { 
               $wp_query->the_post(); 
               /** 
                * Hook: woocommerce_shop_loop.  
                */
                do_action( 'woocommerce_shop_loop' );
                
                echo '<div class="swiper-slide tp-d-flex">';
                include THE_PACK_PLUGIN_DIR . 'includes/woocommerce/custom-loop-wishlist.php';
                echo '</div>';
           }
        } 
       
   } else {

    include THE_PACK_PLUGIN_DIR . 'includes/woocommerce/custom-loop-no-wishlist.php';

   }
   echo '</div> <!--$parent_cls-->';
?>

<style>
	.dev-ash-wishlist-remove{
		position:absolute;
		z-index: 1;
        cursor: pointer;
	}
    .result-item.loading .swiper-slide {
        filter: blur(2px);
    }    
    .result-item .no-wishlist {
        text-align: center;
        flex: 0 0 100%;
    }

</style>