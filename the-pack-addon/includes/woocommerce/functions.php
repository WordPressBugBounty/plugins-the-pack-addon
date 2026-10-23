<?php

use Elementor\Plugin;
function tp_woo_comments_template($settings){
    require dirname(__FILE__) . '/comments.php';  
}

class The_Pack_Woo_Helper {

	public function __construct(){

		add_action('woocommerce_loop_add_to_cart_link', [__CLASS__, 'before_after_btn_wrap']);
		add_filter('wc_get_template_part', [__CLASS__, 'override_woocommerce_template_part'],9, 3);
		add_filter( 'woocommerce_checkout_order_review', [__CLASS__,'checkout_heading'] );

		add_action('wp_ajax_tp_add_cart_single_product',[__CLASS__,'add_cart_single_product_ajax']);
		add_action('wp_ajax_nopriv_tp_add_cart_single_product',[__CLASS__,'add_cart_single_product_ajax']);

		add_filter( 'loop_shop_per_page', [__CLASS__,'product_per_page'], 10);

		add_action('wp_ajax_the_pack_quickview',[__CLASS__,'the_pack_quickview']);
		add_action('wp_ajax_nopriv_the_pack_quickview',[__CLASS__,'the_pack_quickview']);
		add_filter('woocommerce_add_to_cart_fragments',[__CLASS__,'cart_count_fragments']);

		add_action('wp_enqueue_scripts',[__CLASS__,'quickview_assets']);

	}

	static function quickview_assets() {
		
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}

	static function cart_count_fragments( $fragments ) {
		global $woocommerce;
		$count = $woocommerce->cart->cart_contents_count;

		$fragments['.cart-count'] = '<span class="cart-count">'.$count.'</span>';
		return $fragments;
	}

	static function get_cookie_name() {
        $name = 'thepack_compare_woo';
        if ( is_multisite() ){
            $name .= '_' . get_current_blog_id();
        }
        return $name;
    }

	static function get_compared_products() {
        $cookie_name = self::get_cookie_name();
        return isset( $_COOKIE[ $cookie_name ] ) ? json_decode( sanitize_text_field(wp_unslash( $_COOKIE[ $cookie_name ] )), true ) : array();
    }

	static function is_product_in_compare( $id ) {
        $list = self::get_compared_products();
        return in_array( $id, $list, true );
    }

	static function add_to_compare( $id ) {

        $cookie_name = self::get_cookie_name();

        if ( self::is_product_in_compare( $id ) ) {
            //$this->compare_json_response();
        }

        $products = self::get_compared_products();

        $products[] = $id;

        setcookie( $cookie_name, wp_json_encode( array_unique($products) ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );

        $_COOKIE[$cookie_name] = wp_json_encode( $products );

		return array_unique($products);

    }

	static function the_pack_quickview(){
		//phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		if ( ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce' ) ) {
            wp_die();
        } 

		if ( isset( $_POST['id'] ) && (int) sanitize_text_field(wp_unslash($_POST['id'])) ) {
            global $post, $product, $woocommerce;
            $id      = ( int ) sanitize_text_field(wp_unslash($_POST['id']));
            $post    = get_post( $id );
            $product = wc_get_product( $id );
			$template_id = the_pack_get_global_settings('tpwooquick');
			
			?>

	 	    <script>
		 	    var wc_add_to_cart_variation_params = {"ajax_url":"\/wp-admin\/admin-ajax.php"};     
	            jQuery.getScript("<?php echo $woocommerce->plugin_url(); ?>/assets/js/frontend/add-to-cart-variation.min.js"); 
	 	    </script>

			<?php
			echo do_shortcode('[THEPACK_INSERT_TPL id="' . $template_id . '"]');
			//$out= self::add_to_compare( $id );		
			//var_dump($out); 
		}
		
		wp_die();
	}
	static function product_per_page(){
		//phpcs:disable WordPress.Security.NonceVerification.Recommended
		$getopt  = isset( $_GET['perpage'] ) ? sanitize_text_field(wp_unslash($_GET['perpage'])) : '';
		if($getopt){
			return $getopt;
		}

	}

	static function add_cart_single_product_ajax(){

		if ( ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce' ) ) {
            wp_die();
        } 
		
		add_action( 'wp_loaded', [ 'WC_Form_Handler', 'add_to_cart_action' ], 20 );
		
		if ( is_callable( [ 'WC_AJAX', 'get_refreshed_fragments' ] ) ) {
			WC_AJAX::get_refreshed_fragments();
		}
	
		wp_die();	
	}

	static function checkout_heading(){
		echo '<h3>Your order</h3>';
	}

	static function override_woocommerce_template_part( $template, $slug, $name ) {
		$template_directory = THE_PACK_PLUGIN_DIR . 'includes/woocommerce/';
		if ( $name ) {
			$path = $template_directory . "{$slug}-{$name}.php";
		} else {
			$path = $template_directory . "{$slug}.php";
		}
		return file_exists( $path ) ? $path : $template;
	}

	static function before_after_btn_wrap( $add_to_cart_html){

		$before = '<div class="tp-add-to-cart">'; 
		$after = '</div>';  
	
		return $before . $add_to_cart_html . $after;
	}

	static function quick_hover_actions(){

		global $product;
		$id = $product->get_id();
		echo '
			<div class="tp-quick-actions">
				<a data-id="'.$id.'" class="tp-quick-view tp-loader-btn" href="#"><i class="main bi-eye"></i><i class="working bi-arrow-repeat"></i></a>
				'.do_shortcode('[dev-ash-wishlist]').'
			</div>
		';
	}

	static function on_sale($product,$option){
		if ( !$product->is_on_sale() ){
			return;
		}
		if( $product->is_type('variable')){
			$percentages = [];
		
			$prices = $product->get_variation_prices();
		
			foreach( $prices['price'] as $key => $price ){
			  if( $prices['regular_price'][$key] !== $price ){
				$percentages[] = round(100 - ($prices['sale_price'][$key] / $prices['regular_price'][$key] * 100));
				$amount_off[] = $prices['regular_price'][$key] - $prices['sale_price'][$key];
			  }
			}
			$percentage = round(max($percentages)) . '%';
			$amount_off_s = '-'.get_woocommerce_currency_symbol().'' . round(max($amount_off));
		  } else {
			$regular_price = (float) $product->get_regular_price();
			$sale_price    = (float) $product->get_sale_price();
		
			$percentage    = round(100 - ($sale_price / $regular_price * 100)) . '%';

			$amount_off_s = '-'.get_woocommerce_currency_symbol().'' . ($regular_price - $sale_price);
		  }
		  if($option['type']=='percen'){
			$out = $percentage;
		  } elseif($option['type']=='price'){
			$out = $amount_off_s;
		  } else{
			$out = $option['label'];
		  }
		  //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		  echo '<span class="tp-onsale tp-dinflex">'.thepack_build_html($out).'</span>';
	}

	static function product_cat($id){
		//phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<div class="tp-product-cat">'.wc_get_product_category_list( $id ).'</div>';
	}
	static function product_sale_end($id){
		$today = strtotime( "today" );
		$end_date = (int)get_post_meta( $id, '_sale_price_dates_to', true );
		if($end_date>$today){
			$date =  date("j M y", $end_date);
			$data = [
				'date' => esc_attr($date),
				'day' => 'd',
				'hour' => 'h',
				'min' => 'm',
				'sec' => 's'
			];
			echo '<div class="countdown tbtr" data-xld =\'' . wp_json_encode($data) . '\'></div>';
		}
	}
	static function product_thumbnail($size=''){   
		//phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<a href="'.esc_attr(get_permalink()).'">'.woocommerce_get_product_thumbnail($size).'</a>';
	}
	static function product_title(){
		the_title( sprintf( '<h2 class="tp-title tp-clamp"><a href="%s" rel="bookmark">', esc_attr( esc_url( get_permalink() ) ) ),'</a></h2>');
	}
 
	static function product_rating($product,$hide=''){
		if ( empty( $product ) || ! wc_review_ratings_enabled() ) {
			return;
		}	
		$average = $product->get_average_rating();
		$rating_count = $product->get_rating_count();
		$width = $average*20 .'%';

		if (Elementor\Plugin::instance()->editor->is_edit_mode()){
			echo '<div class="tp-avarage-rating tp-dinflex"><span class="tscore"><span style="width: 80%"></span></span><span class="count">(5)</span></div>';
		 } elseif ( $hide  ){
			//phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<div class="tp-avarage-rating tp-dinflex"><span class="tscore"><span style="width: '.$width.'"></span></span><span class="count">('.esc_attr($rating_count).')</span></div>';
		} elseif($rating_count > 0) {
			//phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '<div class="tp-avarage-rating tp-dinflex"><span class="tscore"><span style="width: '.$width.'"></span></span><span class="count">('.esc_attr($rating_count).')</span></div>';
		}

	} 

	static function tp_get_cat_url($termid){
		global $wp;
		if ( '' === get_option( 'permalink_structure' ) ) {
			$link = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		} else {
			$link = preg_replace( '%\/page/[0-9]+%', '', add_query_arg( null, null ) );
		}
		 
		if(isset($_GET['filter_cat'])){//phpcs:disable WordPress.Security.NonceVerification.Recommended
			$explode_old = explode(',',sanitize_text_field(wp_unslash($_GET['filter_cat'])));//phpcs:disable WordPress.Security.NonceVerification.Recommended
			$explode_termid = explode(',',$termid);
			
			if(in_array($termid, $explode_old)){
				$data = array_diff( $explode_old, $explode_termid);
				$checkbox = 'checked';
			} else {
				$data = array_merge($explode_termid , $explode_old);
			}
		} else {
			$data = array($termid);
		}
		
		$dataimplode = implode(',',$data);
		
		if(empty($dataimplode)){
			$link = remove_query_arg('filter_cat',$link);
		} else {
			$link = add_query_arg('filter_cat',implode(',',$data),$link);
		}
		
		return $link;
	}
	
	static function get_filtered_price() {
		global $wpdb;
	
		$args       = WC()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();
	
		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = WC()->query->get_main_tax_query();
		}
	
		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}
	
		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );
		$search     = WC_Query::get_main_search_query_sql();
	
		$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$search_query_sql = $search ? ' AND ' . $search : '';
	
		$sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';
	
		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

		//phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared
		return $wpdb->get_row( $sql );
	}   
	

	static function bf_shop_filter($opt){
		$selector = $opt['sidebar_selector'] ? 'data-target="'.$opt['sidebar_selector'].'"' : '';
        echo '<div class="tp-before-shop tp-d-flex tp-al-center tp-pos-rel">';
			echo '<div class="tp-before-shop-inner tp-dinflex">';
				echo '<span '.$selector.' class="side-filter-btn"><i aria-hidden="true" class="tpbi bi-arrow-down-up"></i> Filter</span>';
				woocommerce_result_count();
			echo '</div>';
			woocommerce_catalog_ordering();
        echo '</div>';
	}

}
if ( class_exists( 'WooCommerce' ) ) {
	new The_Pack_Woo_Helper();
}


function build_filter_nav($settings,$prefix){

	if($settings['hide_head']){
		return;
	}
	$data_source = $settings[$prefix.'_'.'query_type'];
	$first = 0;
	$out = '';
	$heading = thepack_build_html($settings['ttl'],'h3','filter-title');
	if($data_source=='category'){
		
		foreach ($settings[$prefix.'_'.'terms'] as $cat_id) {
			$first++; 
			$active_cls = $first == 1 ? 'current' : '';
			$termg = get_term_by('slug', $cat_id['meta'], $settings[$prefix.'_'.'taxonomy']);
			$term = get_term($termg->term_id);
			$title = $cat_id['label'] ? $cat_id['label'] : $term->name;
			$out .= '<li data-tax="'.$settings[$prefix.'_'.'taxonomy'].'" data-term-id="'.$term->slug.'" class="woo-filter-item '.$active_cls.'">'.$title.'</li>';
		}

	} elseif($data_source=='custom'){

		foreach ($settings[$prefix.'_'.'customs'] as $cat_id) {
			$first++;
			$active_cls = $first == 1 ? 'current' : '';
			$out .= '<li data-term-id="'.$cat_id['meta'].'" class="woo-filter-item '.$active_cls.'">'.$cat_id['label'].'</li>';
		}

	} else {

	} 

	return '<div class="woo-filter-head tp-d-flex tp-al-center tp-wrap-wrap">'.$heading.'<ul data-source="'.$data_source.'" class="tp-woo-filter tp-d-flex tp-wrap-wrap raw-style">'.$out.'</ul></div>';
}

function build_woo_query($settings,$prefix){
 
	$wp_query_args = [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $settings[$prefix.'_'.'num']['size'],
	];

	$data_source = $settings[$prefix.'_'.'query_type'];

	if($data_source=='category'){

		$cat = thepack_buildermeta_to_string($settings[$prefix.'_'.'terms']);
		$first_cat = explode(',' ,$cat);  
		if($first_cat){
			$wp_query_args['tax_query'] = [
				[
					'taxonomy' => $settings[$prefix.'_'.'taxonomy'],
					'field' => 'slug',
					'terms' => $first_cat[0],					
				],
			];
		} 

	} elseif($data_source=='related'){

		$product_id = get_the_ID();
		$related_ids = wc_get_related_products( $product_id);
		$wp_query_args['post__in'] = $related_ids;


	}elseif($data_source=='category_term'){

		$cat = thepack_buildermeta_to_string($settings[$prefix.'_'.'terms']);
		$first_cat = explode(',' ,$cat);  

		$wp_query_args['tax_query'] = [
			[
				'taxonomy' => $settings[$prefix.'_'.'taxonomy'],
				'field' => 'slug',
				'terms' => $first_cat,					
			],
		];

	} elseif($data_source=='custom'){

		if (empty($settings[$prefix.'_'.'customs'])) {
			return;
		}
		$type = $settings[$prefix.'_'.'customs'][0]['meta'];

		if($type=='sale'){

			$wp_query_args['meta_query'] = [
				'relation' => 'OR',
				[
					'key'     => '_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric'			
				],
				[
					'key'     => '_min_variation_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric'			
				],
			];	

		} else if($type=='featured'){

			$wp_query_args['tax_query'] = [
				[
					'taxonomy' => 'product_visibility',
					'field' => 'name',
					'terms' => 'featured',	
					'operator' => 'IN',				
				],
			];

		} else if($type=='topsell'){

			$wp_query_args['meta_key'] = 'total_sales';
			$wp_query_args['orderby'] = 'meta_value_num';
			$wp_query_args['order'] = 'DESC';

		} else if ($type=='endingsale'){

			$today = strtotime( "today" );
			$wp_query_args['meta_query'] = [
				'relation' => 'AND',
				[
					'key'     => '_sale_price_dates_to',
					'value'   => $today,
					'compare' => '>', // Check for dates in the past
					'type'    => 'numeric'			
				],
			]; 
			
		} else if($type=='new'){

			$wp_query_args['orderby'] = 'date';
			$wp_query_args['order'] = 'DESC';

		}

	} else {

		$id = $settings[$prefix.'_'.'id_query'];
		$wp_query_args['post__in'] = $id;
		$wp_query_args['orderby'] = 'post__in';		
	}

	return $wp_query_args;
}

add_filter( 'loop_shop_per_page', 'the_pack_woo_shop_posts', 20 );
function the_pack_woo_shop_posts( $cols ) {

  $posts = the_pack_get_global_settings('tpwooshopost');	
  $data = isset( $posts['size'] ) ? $posts['size'] : '';
  return $data;
}

//add_filter( 'woocommerce_pagination_args', 'custom_woocommerce_pagination_arrows' );

function custom_woocommerce_pagination_arrows( $args ) {
    $args['prev_text'] = '<i aria-hidden="true" class="tpbi bi-chevron-left"></i>'; // Example: Left Double Angle Quote
    $args['next_text'] = '<i aria-hidden="true" class="tpbi bi-chevron-right"></i>'; // Example: Right Double Angle Quote
    return $args;
}
