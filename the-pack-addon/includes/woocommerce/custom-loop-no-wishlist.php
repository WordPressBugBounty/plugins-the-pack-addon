<?php
defined( 'ABSPATH' ) || exit;

if ( wp_doing_ajax() ) {
	//var_dump($settings);
}	 
$shop_page_url = wc_get_page_permalink( 'shop' );
?> 
<div class="no-wishlist">
	<?php 
		echo the_pack_render_icon($settings['icon'], 'tp-icon');
		echo thepack_build_html($settings['title'],'h3','title');
		echo thepack_build_html($settings['desc'],'p','desc');
		if($settings['btn']){
			echo '<a class="back-to-shop" href="'.$shop_page_url.'">'.$settings['btn'].'</a>';
		}
	?>
</div>
