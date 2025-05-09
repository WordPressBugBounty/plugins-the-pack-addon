<?php

namespace ThePackKitThemeBuilder\Modules\Popup\DisplaySettings;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Triggers extends Base {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @since  2.4.0
	 * @access public
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'popup_triggers';
	}

	protected function register_controls() {
		$this->start_controls_section( 'triggers' );

		$this->start_settings_group( 'page_load', esc_html__( 'On Page Load', 'the-pack-addon'  ) );

		$this->add_settings_group_control(
			'delay',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Within', 'the-pack-addon'  ) . ' (sec)',
				'default' => 0,
				'min' => 0,
				'step' => 0.1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'scrolling', esc_html__( 'On Scroll', 'the-pack-addon'  ) );

		$this->add_settings_group_control(
			'direction',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Direction', 'the-pack-addon'  ),
				'default' => 'down',
				'options' => [
					'down' => esc_html__( 'Down', 'the-pack-addon'  ),
					'up' => esc_html__( 'Up', 'the-pack-addon'  ),
				],
			]
		);

		$this->add_settings_group_control(
			'offset',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Within', 'the-pack-addon'  ) . ' (%)',
				'default' => 50,
				'min' => 1,
				'max' => 100,
				'condition' => [
					'direction' => 'down',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'scrolling_to', esc_html__( 'On Scroll To Element', 'the-pack-addon'  ) );

		$this->add_settings_group_control(
			'selector',
			[
				'type' => Controls_Manager::TEXT,
				'label' => esc_html__( 'Selector', 'the-pack-addon'  ),
				'placeholder' => '.my-class',
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'click', esc_html__( 'On Click', 'the-pack-addon'  ) );

		$this->add_settings_group_control(
			'times',
			[
				'label' => esc_html__( 'Clicks', 'the-pack-addon'  ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'inactivity', esc_html__( 'After Inactivity', 'the-pack-addon'  ) );

		$this->add_settings_group_control(
			'time',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => esc_html__( 'Within', 'the-pack-addon'  ) . ' (sec)',
				'default' => 30,
				'min' => 1,
				'step' => 0.1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'exit_intent', esc_html__( 'On Page Exit Intent', 'the-pack-addon'  ) );

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
