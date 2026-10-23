<?php
use Elementor\Plugin;		
	$id = substr($this->get_id_int(), 0, 3);	
	// price range for all published products
	$unfiltered_price_range = The_Pack_Pro_Woo_Filter_Init::getPriceRange(false);
	$html = '';
	// to be sure that these values are number
	$min_val = $max_val = 0;

	if (sizeof($unfiltered_price_range) === 2) {
		$min_val = $unfiltered_price_range[0];
		$max_val = $unfiltered_price_range[1];
	}
		// get values from url
		$set_min_val = null;
		if (isset($_GET['min-price']) && !empty($_GET['min-price'])) {
			$set_min_val = (int)$_GET['min-price'];
		}

		$set_max_val = null;
		if (isset($_GET['max-price']) && !empty($_GET['max-price'])) {
			$set_max_val = (int)$_GET['max-price'];
		}

		$html .= '<div class="wcapf-price-filter-wrapper">';
			$html .= '<div id="wcapf-noui-slider" class="noUi-extended" data-min="' . $min_val . '" data-max="' . $max_val . '" data-set-min="' . $set_min_val . '" data-set-max="' . $set_max_val . '"></div>';
			$html .= '<div class="slider-values">';
				$html .= '<span class="slider-value-label">'.$settings['pt'].'</span>';
				$html .= '<span class="slider-value-range"><span class="wcapf-slider-value" id="wcapf-noui-slider-value-min"></span>-<span class="wcapf-slider-value" id="wcapf-noui-slider-value-max"></span></span>';
			$html .= '</div>';
		$html .= '</div>';


	$title_cls = 'filter-heading '.$settings['accor'];

	if (Plugin::instance()->editor->is_edit_mode() ) {
		echo '<div id="wcapf-price-filter" class="tp-filter-wrap wcapf-price-filter-widget widget_wcapf-price-filter">'.thepack_build_html($settings['title'],'h3',$title_cls).'<div class="wcapf-price-filter-wrapper"><div id="wcapf-noui-slider" class="noUi-extended noUi-target noUi-ltr noUi-horizontal noUi-background" data-min="2" data-max="90" data-set-min="20" data-set-max="50"><div class="noUi-base"><div class="noUi-origin" style="left: 20.4545%;"><div class="noUi-handle noUi-handle-lower"></div></div><div class="noUi-origin" style="left: 54.5455%;"><div class="noUi-handle noUi-handle-upper"></div></div></div></div><div class="slider-values"><span class="slider-value-label">'.$settings['pt'].'</span><span class="slider-value-range"><span class="wcapf-slider-value" id="wcapf-noui-slider-value-min">25৳&nbsp;</span>-<span class="wcapf-slider-value" id="wcapf-noui-slider-value-max">60৳&nbsp;</span></span></div></div></div>';
	} else {
			echo '<div id="wcapf-price-filter-'.$id.'" class="tp-filter-wrap wcapf-price-filter-widget widget_wcapf-price-filter">';
			echo thepack_build_html($settings['title'],'h3',$title_cls);
			echo $html;
			echo '</div>';
	}			