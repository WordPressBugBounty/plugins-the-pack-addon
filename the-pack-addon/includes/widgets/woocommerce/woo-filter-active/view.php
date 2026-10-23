<?php
use Elementor\Plugin;

	$active_filters = The_Pack_Pro_Woo_Filter_Init::getChosenFilters();
	$active_filters = $active_filters['active_filters'];
	$found = false;
	$html = '';
	$btn = esc_attr($settings['abtn']);
	if (sizeof($active_filters) > 0) {
		$found = true;
		$html .= '<div class="wcapf-active-filters">';

			foreach ($active_filters as $key => $active_filter) {
				if ($key === 'term') {
					foreach ($active_filter as $data_key => $terms) {
						foreach ($terms as $term_id => $term_name) {
							$html .= '<a href="javascript:void(0)" data-key="' . $data_key . '" data-value="' . $term_id . '"><i aria-hidden="true" class="bi-x"></i>' . $term_name . '</a>';
						}
					}
				}

				if ($key === 'keyword') { 
					$html .= '<a href="javascript:void(0)" data-key="keyword"><i aria-hidden="true" class="bi-x"></i>' . $settings['search'].' '. $active_filter . '</a>';
				}
				if ($key === 'rating') { 
					$width = $active_filter*20;
					$html .= '<a href="javascript:void(0)" data-key="rating"><i aria-hidden="true" class="bi-x"></i><span class="tscore"><span style="width: '.$width.'%"></span></span></a>';
				}

				if ($key === 'onsale') {
					$html .= '<a href="javascript:void(0)" data-key="onsale"><i aria-hidden="true" class="bi-x"></i>' . $settings['onsale'].'</a>';
				}
				if ($key === 'instock') {
					$html .= '<a href="javascript:void(0)" data-key="instock"><i aria-hidden="true" class="bi-x"></i>' . $settings['stock'] . '</a>';
				}
				if ($key === 'featured') {
					$html .= '<a href="javascript:void(0)" data-key="featured"><i aria-hidden="true" class="bi-x"></i>' . $settings['feat'] . '</a>';
				}
				if ($key === 'orderby') {
					$html .= '<a href="javascript:void(0)" data-key="orderby"><i aria-hidden="true" class="bi-x"></i>' . $settings['order'].' '.$active_filter . '</a>';
				}

				if ($key === 'min_price') {
					$html .= '<a href="javascript:void(0)" data-key="min-price"><i aria-hidden="true" class="bi-x"></i>' . $settings['mipr'].' '.$active_filter . '</a>';
				}

				if ($key === 'max_price') {
					$html .= '<a href="javascript:void(0)" data-key="max-price"><i aria-hidden="true" class="bi-x"></i>' . $settings['mxpr'].' '.$active_filter . '</a>';
				}

			}

			if (!empty($btn)) {
				if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
					$link = home_url();
				} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id('shop') ) ) {
					$link = get_post_type_archive_link( 'product' );
				} else {
					$current_term = get_queried_object();
					$link = get_term_link( $current_term );
				}

				/**
				 * Search Arg.
				 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
				 */
				if ( get_search_query() ) {
					$link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
				}

				// Post Type Arg

				if ( isset( $_GET['post_type'] ) ) {
					$link = add_query_arg( 'post_type', wc_clean( $_GET['post_type'] ), $link );
				}

				$html .= '<a href="javascript:void(0)" class="reset" data-location="'.$link.'"><i aria-hidden="true" class="bi-x"></i>'.$btn.'</a>';
			} 

		$html .= '</div>';
	}

	if ($found === false) {
		$widget_class = 'wcapf-widget-hidden woocommerce wcapf-ajax-term-filter';
	} else {
		$widget_class = 'woocommerce wcapf-ajax-term-filter';
	}
	
	
	if (Plugin::instance()->editor->is_edit_mode() ) {
		echo '<section id="wcapf-active-filter" class="woocommerce wcapf-ajax-term-filter widget_wcapf-active-filters"><div class="wcapf-active-filters"><a href="#"><i aria-hidden="true" class="bi-x"></i>Decor</a><a href="#"><span class="tscore"><span style="width: 20%"></span></span></a><a href="#" class="reset"><i aria-hidden="true" class="bi-x"></i>Remove all</a></div></section>';
	} else {
		echo '<section id="wcapf-active-filter" class="'.$widget_class.' widget_wcapf-active-filters">';
		echo $html;
		echo '</section>';
	}	