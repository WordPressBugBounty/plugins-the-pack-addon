<?php
	$id = substr($this->get_id_int(), 0, 3);
	$active_1star = isset($_GET['rating']) && $_GET['rating']=='1'  ? 'class="chosen"' : '';
	$active_2star = isset($_GET['rating']) && $_GET['rating']=='2'  ? 'class="chosen"' : '';
	$active_3star = isset($_GET['rating']) && $_GET['rating']=='3'  ? 'class="chosen"' : '';
	$active_4star = isset($_GET['rating']) && $_GET['rating']=='4'  ? 'class="chosen"' : '';
	$active_5star = isset($_GET['rating']) && $_GET['rating']=='5'  ? 'class="chosen"' : '';

	$widget_class = 'tp-filter-wrap wcapf-ajax-term-filter';
	$title_cls = 'filter-heading '.$settings['accor'];
	echo '<div id="wcapf-rating-filter-'.$id.'" class="'.$widget_class.' widget_wcapf-stock-filter">';
	echo thepack_build_html($settings['title'],'h3',$title_cls);
	echo '
		<div class="wcapf-layered-nav"><ul>
			<li '.$active_1star.'><a href="javascript:void(0)" data-key="rating" data-value="1"><span class="tscore"><span style="width: 20%"></span></span></a></li>
			<li '.$active_2star.'><a href="javascript:void(0)" data-key="rating" data-value="2"><span class="tscore"><span style="width: 40%"></span></span></a></li>
			<li '.$active_3star.'><a href="javascript:void(0)" data-key="rating" data-value="3"><span class="tscore"><span style="width: 60%"></span></span></a></li>
			<li '.$active_4star.'><a href="javascript:void(0)" data-key="rating" data-value="4"><span class="tscore"><span style="width: 80%"></span></span></a></li>
			<li '.$active_5star.'><a href="javascript:void(0)" data-key="rating" data-value="5"><span class="tscore"><span style="width: 100%"></span></span></a></li>
		</ul></div>	
	';
	echo '</div>';	 
