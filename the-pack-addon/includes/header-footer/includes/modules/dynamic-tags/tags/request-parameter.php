<?php
namespace ThePackKitThemeBuilder\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use ThePackKitThemeBuilder\Modules\DynamicTags\Tags\Base\Tag;
use ThePackKitThemeBuilder\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Request_Parameter extends Tag {
	public function get_name() {
		return 'request-arg';
	}

	public function get_title() {
		return esc_html__( 'Request Parameter', 'the-pack-addon'  );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [
			Module::TEXT_CATEGORY,
			Module::POST_META_CATEGORY,
		];
	}

	public function render() {
		$settings = $this->get_settings();
		$request_type = isset( $settings['request_type'] ) ? strtoupper( $settings['request_type'] ) : false;
		$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : false;
		$value = '';

		if ( ! $param_name || ! $request_type ) {
			return '';
		}

		switch ( $request_type ) {
			case 'POST':
				if ( ! isset( $_POST[ $param_name ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
					return '';
				}
				$value = sanitize_text_field(wp_unslash($_POST[ $param_name ])); // phpcs:ignore WordPress.Security.NonceVerification.Missing
				break; 
			case 'GET': 
				if ( ! isset( $_GET[ $param_name ] ) ) { // phpcs:ignore
					return '';
				}
				$value = sanitize_text_field(wp_unslash($_GET[ $param_name ])); // phpcs:ignore
				break;
			case 'QUERY_VAR':
				$value = get_query_var( $param_name );
				break;
		}
		echo htmlentities( wp_kses_post( $value ) );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	protected function register_controls() {
		$this->add_control(
			'request_type',
			[
				'label'   => esc_html__( 'Type', 'the-pack-addon'  ),
				'type' => Controls_Manager::SELECT,
				'default' => 'get',
				'options' => [
					'get' => 'Get',
					'post' => 'Post',
					'query_var' => 'Query Var',
				],
			]
		);
		$this->add_control(
			'param_name',
			[
				'label'   => esc_html__( 'Parameter Name', 'the-pack-addon'  ),
				'type' => Controls_Manager::TEXT,
			]
		);
	}
}
