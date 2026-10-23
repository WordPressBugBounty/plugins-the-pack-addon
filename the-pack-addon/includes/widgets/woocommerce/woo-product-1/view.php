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

    echo build_filter_nav($settings,'query');
?>

<?php
   if ($wp_query->have_posts()) {
       echo '<div class="result-item '.$class.'">';
       if ( wc_get_loop_prop( 'total' ) ) {
           while ( $wp_query->have_posts() ) { 
               $wp_query->the_post();
               /** 
                * Hook: woocommerce_shop_loop.  
                */
                do_action( 'woocommerce_shop_loop' );
                
                echo '<div class="swiper-slide">';
                include THE_PACK_PLUGIN_DIR . 'includes/woocommerce/custom-loop.php';
                echo '</div>';
           }
        } 
       echo '</div> <!--$parent_cls-->';
   }

?>
</div>
   