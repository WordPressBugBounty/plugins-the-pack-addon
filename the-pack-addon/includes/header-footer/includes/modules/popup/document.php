<?php
namespace ThePackKitThemeBuilder\Modules\Popup;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use ThePackKitThemeBuilder\Modules\Popup\DisplaySettings\Base;
use ThePackKitThemeBuilder\Modules\Popup\DisplaySettings\Timing;
use ThePackKitThemeBuilder\Modules\Popup\DisplaySettings\Triggers;
use ThePackKitThemeBuilder\Modules\ThemeBuilder\Documents\Theme_Section_Document;
use ThePackKitThemeBuilder\Modules\ThemeBuilder\Module as ThemeBuilderModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Document extends Theme_Section_Document {

	const DISPLAY_SETTINGS_META_KEY = '_elementor_popup_display_settings';

	/**
	 * @var Base[]
	 */
	private $display_settings;

	public static function get_type() {
		return 'popup';
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = 'popup';
		$properties['location'] = 'popup';
		$properties['support_kit'] = true;
		$properties['support_site_editor'] = false;

		return $properties;
	}

	public static function get_title() {
		return esc_html__( 'Popup', 'the-pack-addon'  );
	}

	public static function get_plural_title() {
		return esc_html__( 'Popups', 'the-pack-addon'  );
	}

	public function get_display_settings() {
		if ( ! $this->display_settings ) {
			$settings = $this->get_display_settings_data();

			if ( ! $settings ) {
				$settings = [
					'triggers' => [],
					'timing' => [],
				];
			}

			$id = $this->get_main_id();

			$this->display_settings = [
				'triggers' => new Triggers( [
					'id' => $id,
					'settings' => $settings['triggers'],
				] ),
				'timing' => new Timing( [
					'id' => $id,
					'settings' => $settings['timing'],
				] ),
			];
		}

		return $this->display_settings;
	}

	public function get_initial_config() {
		$config = parent::get_initial_config();

		$display_settings = $this->get_display_settings();

		$config['displaySettings'] = [
			'triggers' => [
				'controls' => $display_settings['triggers']->get_controls(),
				'settings' => $display_settings['triggers']->get_settings(),
			],
			'timing' => [
				'controls' => $display_settings['timing']->get_controls(),
				'settings' => $display_settings['timing']->get_settings(),
			],
		];

		$config['container'] = '.elementor-popup-modal .dialog-widget-content';

		return $config;
	}

	public function get_name() {
		return 'popup';
	}

	public function get_css_wrapper_selector() {
		return '#elementor-popup-modal-' . $this->get_main_id();
	}

	public function get_display_settings_data() {
		return $this->get_main_meta( self::DISPLAY_SETTINGS_META_KEY );
	}

	public function save_display_settings_data( $display_settings_data ) {
		$this->update_main_meta( self::DISPLAY_SETTINGS_META_KEY, $display_settings_data );
	}

	public function get_frontend_settings() {
		$settings = parent::get_frontend_settings();

		$display_settings = $this->get_display_settings();

		// Disable triggers if the popup is not printed by the theme builder conditions.
		// avoid auto show the popup if it's enqueued by a dynamic tag and etc.)
		$popups_by_condition = ThemeBuilderModule::instance()->get_conditions_manager()->get_documents_for_location( 'popup' );

		if ( $popups_by_condition && isset( $popups_by_condition[ $this->get_main_id() ] ) ) {
			$settings['triggers'] = $display_settings['triggers']->get_frontend_settings();
		}

		$settings['timing'] = $display_settings['timing']->get_frontend_settings();

		return $settings;
	}

	public function get_export_data() {
		$data = parent::get_export_data();

		$display_settings = $this->get_display_settings();

		$data['display_settings'] = [
			'triggers' => $display_settings['triggers']->get_frontend_settings(),
			'timing' => $display_settings['timing']->get_frontend_settings(),
		];

		return $data;
	}

	public function import( array $data ) {
		parent::import( $data );

		$this->save_display_settings_data( $data['display_settings'] );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'popup_layout',
			[
				'label' => esc_html__( 'Layout', 'the-pack-addon'  ),
				'tab' => Controls_Manager::TAB_SETTINGS,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Width', 'the-pack-addon'  ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vw' ],
				'default' => [
					'size' => 640,
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'height_type',
			[
				'label' => esc_html__( 'Height', 'the-pack-addon'  ),
				'type' => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto' => esc_html__( 'Fit To Content', 'the-pack-addon'  ),
					'fit_to_screen' => esc_html__( 'Fit To Screen', 'the-pack-addon'  ),
					'custom' => esc_html__( 'Custom', 'the-pack-addon'  ),
				],
				'selectors_dictionary' => [
					'fit_to_screen' => '100vh',
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'height: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => esc_html__( 'Custom Height', 'the-pack-addon'  ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'condition' => [
					'height_type' => 'custom',
				],
				'default' => [
					'size' => 380,
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label' => esc_html__( 'Content Position', 'the-pack-addon'  ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'the-pack-addon'  ),
					'center' => esc_html__( 'Center', 'the-pack-addon'  ),
					'bottom' => esc_html__( 'Bottom', 'the-pack-addon'  ),
				],
				'condition' => [
					'height_type!' => 'auto',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'position_heading',
			[
				'label' => esc_html__( 'Position', 'the-pack-addon'  ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'horizontal_position',
			[
				'label' => esc_html__( 'Horizontal', 'the-pack-addon'  ),
				'type' => Controls_Manager::CHOOSE,
				'toggle' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'the-pack-addon'  ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'the-pack-addon'  ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'the-pack-addon'  ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'justify-content: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_position',
			[
				'label' => esc_html__( 'Vertical', 'the-pack-addon'  ),
				'type' => Controls_Manager::CHOOSE,
				'toggle' => false,
				'default' => 'center',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'the-pack-addon'  ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'the-pack-addon'  ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'the-pack-addon'  ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
			]
		);

		$this->add_control(
			'overlay',
			[
				'label' => esc_html__( 'Overlay', 'the-pack-addon'  ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'the-pack-addon'  ),
				'label_on' => esc_html__( 'Show', 'the-pack-addon'  ),
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => 'pointer-events: all',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'close_button',
			[
				'label' => esc_html__( 'Close Button', 'the-pack-addon'  ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'the-pack-addon'  ),
				'label_on' => esc_html__( 'Show', 'the-pack-addon'  ),
				'default' => 'yes',
				'selectors' => [
					// Using flex to make sure that each icon type (i or SVG) will be vertically aligned.
					'{{WRAPPER}} .dialog-close-button' => 'display: flex',
				],
			]
		);

		$this->add_responsive_control(
			'entrance_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'the-pack-addon'  ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'exit_animation',
			[
				'label' => esc_html__( 'Exit Animation', 'the-pack-addon'  ),
				'type' => Controls_Manager::EXIT_ANIMATION,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'entrance_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'the-pack-addon'  ) . ' (sec)',
				'type' => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'default' => [
					'size' => 1.2,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-widget-content' => 'animation-duration: {{SIZE}}s',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'entrance_animation',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'exit_animation',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
			]
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->start_controls_section(
			'section_page_style',
			[
				'label' => esc_html__( 'Popup', 'the-pack-addon'  ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'  => 'background',
				'selector' => '{{WRAPPER}} .dialog-widget-content',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'  => 'border',
				'selector' => '{{WRAPPER}} .dialog-widget-content',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'the-pack-addon'  ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dialog-widget-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .dialog-widget-content',
				'fields_options' => [
					'box_shadow_type' => [
						'default' => 'yes',
					],
					'box_shadow' => [
						'default' => [
							'horizontal' => 2,
							'vertical' => 8,
							'blur' => 23,
							'spread' => 3,
							'color' => 'rgba(0,0,0,0.2)',
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay',
			[
				'label' => esc_html__( 'Overlay', 'the-pack-addon'  ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'overlay' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => 'rgba(0,0,0,.8)',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_close_button',
			[
				'label' => esc_html__( 'Close Button', 'the-pack-addon'  ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'close_button!' => '',
				],
			]
		);

		$this->add_control(
			'close_button_position',
			[
				'label' => esc_html__( 'Position', 'the-pack-addon'  ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Inside', 'the-pack-addon'  ),
					'outside' => esc_html__( 'Outside', 'the-pack-addon'  ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'close_button_vertical',
			[
				'label' => esc_html__( 'Vertical Position', 'the-pack-addon'  ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'max' => 100,
						'min' => 0,
						'step' => 0.1,
					],
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button' => 'top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'close_button_horizontal',
			[
				'label' => esc_html__( 'Horizontal Position', 'the-pack-addon'  ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'max' => 100,
						'min' => 0,
						'step' => 0.1,
					],
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .dialog-close-button' => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .dialog-close-button' => 'left: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'after',
			]
		);

		$this->start_controls_tabs( 'close_button_style_tabs' );

		$this->start_controls_tab(
			'tab_x_button_normal',
			[
				'label' => esc_html__( 'Normal', 'the-pack-addon'  ),
			]
		);

		$this->add_control(
			'close_button_color',
			[
				'label' => esc_html__( 'Color', 'the-pack-addon'  ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .dialog-close-button svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'the-pack-addon'  ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_x_button_hover',
			[
				'label' => esc_html__( 'Hover', 'the-pack-addon'  ),
			]
		);

		$this->add_control(
			'close_button_hover_color',
			[
				'label' => esc_html__( 'Color', 'the-pack-addon'  ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'the-pack-addon'  ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'the-pack-addon'  ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_advanced',
			[
				'label' => esc_html__( 'Advanced', 'the-pack-addon'  ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'close_button_delay',
			[
				'label' => esc_html__( 'Show Close Button After', 'the-pack-addon'  ) . ' (sec)',
				'type' => Controls_Manager::NUMBER,
				'min' => 0.1,
				'max' => 60,
				'step' => 0.1,
				'condition' => [
					'close_button' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'close_automatically',
			[
				'label' => esc_html__( 'Automatically Close After', 'the-pack-addon'  ) . ' (sec)',
				'type' => Controls_Manager::NUMBER,
				'min' => 0.1,
				'max' => 60,
				'step' => 0.1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'prevent_close_on_background_click',
			[
				'label' => esc_html__( 'Prevent Closing on Overlay', 'the-pack-addon'  ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'prevent_close_on_esc_key',
			[
				'label' => esc_html__( 'Prevent Closing on ESC key', 'the-pack-addon'  ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'prevent_scroll',
			[
				'label' => esc_html__( 'Disable Page Scrolling', 'the-pack-addon'  ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'avoid_multiple_popups',
			[
				'label' => esc_html__( 'Avoid Multiple Popups', 'the-pack-addon'  ),
				'type' => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'If the user has seen another popup on the page hide this popup', 'the-pack-addon'  ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'open_selector',
			[
				'label' => esc_html__( 'Open By Selector', 'the-pack-addon'  ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '#id, .class', 'the-pack-addon'  ),
				'description' => esc_html__( 'In order to open a popup on selector click, please set your Popup Conditions', 'the-pack-addon'  ),
				'frontend_available' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => esc_html__( 'Margin', 'the-pack-addon'  ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .dialog-widget-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Padding', 'the-pack-addon'  ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'classes',
			[
				'label' => esc_html__( 'CSS Classes', 'the-pack-addon'  ),
				'type' => Controls_Manager::TEXT,
				'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'the-pack-addon'  ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

        thepack_kit()->elementor()->controls_manager->add_custom_css_controls( $this );
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();
		$config['type'] = 'popup';
		$config['default_route'] = 'templates/popups';
		$config['autoImportSettings'] = true;

		return $config;
	}
}
