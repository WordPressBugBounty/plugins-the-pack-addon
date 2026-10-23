<?php
	$id = substr($this->get_id_int(), 0, 3);
	$active_sale = isset($_GET['onsale']) ? 'class="chosen"' : '';
	$active_stock = isset($_GET['instock']) ? 'class="chosen"' : '';
	$active_featured = isset($_GET['featured']) ? 'class="chosen"' : '';
	$widget_class = 'tp-filter-wrap wcapf-ajax-term-filter';
	$title_cls = 'filter-heading '.esc_attr($settings['accor']);

	$stock = $settings['stock'] ? '<li '.$active_stock.'><a href="javascript:void(0)" data-key="instock" data-value="yes" data-multiple-filter="1">'.esc_attr($settings['stock']).'</a></li>' : '';
	$sale = $settings['sale'] ? '<li '.$active_sale.'><a href="javascript:void(0)" data-key="onsale" data-value="yes" data-multiple-filter="1">'.esc_attr($settings['sale']).'</a></li>' : '';
	$featured = $settings['feat'] ? '<li '.$active_featured.'><a href="javascript:void(0)" data-key="featured" data-value="yes" data-multiple-filter="1">'.esc_attr($settings['feat']).'</a></li>' : '';

	echo '<div id="wcapf-stock-filter-'.$id.'" class="'.$widget_class.' widget_wcapf-stock-filter">';
	echo thepack_build_html($settings['title'],'h3',$title_cls);
	echo '
		<div class="wcapf-layered-nav"><ul>
			'.$stock.$sale.$featured.'
		</ul></div>	
	';
	echo '</div>';	 
