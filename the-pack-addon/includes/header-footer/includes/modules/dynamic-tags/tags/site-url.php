<?php
namespace ThePackKitThemeBuilder\Modules\DynamicTags\Tags;

use ThePackKitThemeBuilder\Modules\DynamicTags\Tags\Base\Data_Tag;
use ThePackKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Site_URL extends Data_Tag {

	public function get_name() {
		return 'site-url';
	}

	public function get_title() {
		return esc_html__( 'Site URL', 'the-pack-addon'  );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	public function get_value( array $options = [] ) {
		return home_url();
	}
}

