<?php 
	$id = substr($this->get_id_int(), 0, 3);
	$enable_multiple = ('yes' === $settings['multiple']);
	$show_count = ('yes' === $settings['count']);
	$enable_hierarchy = true;
	$show_children_only = ('yes' === $settings['children']);
	$display_type = $settings['display'];

	$attribute_name = $settings['cat'];
	$taxonomy   = 'pa_' . $attribute_name;
	$query_type = $settings['query'];
	$data_key   = ($query_type === 'and') ? 'attra-' . $attribute_name : 'attro-' . $attribute_name;

	// parse url
	$url = $_SERVER['QUERY_STRING'];
	parse_str($url, $url_array);

	$attr_args = array(
		'taxonomy'           => $taxonomy,
		'data_key'           => $data_key,
		'query_type'         => $query_type,
		'enable_multiple'    => $enable_multiple,
		'show_count'         => $show_count,
		'enable_hierarchy'   => $enable_hierarchy,
		'show_children_only' => $show_children_only,
		'url_array'          => $url_array
	);

	// if display type list
	if ($display_type === 'list') {
		$output = wcapf_list_terms($attr_args);
	} elseif ($display_type === 'dropdown') {
		$output = wcapf_dropdown_terms($attr_args);
	}

	$html = $output['html'];
	$found = $output['found'];

	if ($found === false) {
		$widget_class = 'wcapf-widget-hidden woocommerce wcapf-ajax-term-filter';
	} else {
		$widget_class = 'woocommerce wcapf-ajax-term-filter';
	}

	$title_cls = 'filter-heading '.$settings['accor'];

	echo '<div id="wcapf-attribute-filter-'.$id.'" class="'.$widget_class.' widget_wcapf-attribute-filter">';
	echo thepack_build_html($settings['title'],'h3',$title_cls);
	echo $html;

	echo '</div>';


	