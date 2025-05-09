<?php
namespace ThePackKitThemeBuilder\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use ThePackKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use ThePackKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Excerpt extends Tag {
	public function get_name() {
		return 'post-excerpt';
	}

	public function get_title() {
		return esc_html__( 'Post Excerpt', 'the-pack-addon'  );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	protected function register_controls() {

		$this->add_control(
			'max_length',
			[
				'label' => esc_html__( 'Excerpt Length', 'the-pack-addon'  ),
				'type' => Controls_Manager::NUMBER,
			]
		);
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		// Allow only a real `post_excerpt` and not the trimmed `post_content` from the `get_the_excerpt` filter
		$post = get_post();

		if ( ! $post || empty( $post->post_excerpt ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$max_length = (int) $settings['max_length'];
		$excerpt = $post->post_excerpt;

		$excerpt = thepack_addon_kit_helper()->trim_words( $excerpt, $max_length );

		echo wp_kses_post( $excerpt );
	}
}
