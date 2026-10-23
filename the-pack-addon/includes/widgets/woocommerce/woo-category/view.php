<?php 
if (ctype_alnum($settings['style']) ){
	require plugin_dir_path(__FILE__) . 'style_'.esc_attr($settings['style']) . '.php';
}
?>