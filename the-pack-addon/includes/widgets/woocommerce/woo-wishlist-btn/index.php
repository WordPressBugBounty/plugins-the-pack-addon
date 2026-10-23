<?php
namespace ThePackAddon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\utils;

if (!defined('ABSPATH')) {
    exit;
}

class The_Pack_Woo_Wishlist_Btn extends Widget_Base
{
    public function get_name()
    {
        return 'tp_woo_wish_btn';
    }

    public function get_title()
    {
        return esc_html__('Wishlist Button', 'the-pack-addon');
    }

    public function get_icon()
    {
        return 'dashicons dashicons-controls-repeat';
    }

    public function get_categories()
    {
        return ['ashelement-addons'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_process_1',
            [
                'label' => esc_html__('Content', 'the-pack-addon'),
            ]
        );
        
        $this->add_control(
            'ikn',
            [
                'label' => esc_html__('Icon', 'thepackpro'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_general',
            [
                'label' => esc_html__('General', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tctb');

        $this->start_controls_tab(
            'e1',
            [
                'label' => esc_html__('Icon', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'avw',
            [
                'label' => esc_html__('Width', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tp-wish-icon' => 'flex: 0 0 {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'ibg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-wish-icon' => 'background:{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avrd',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tp-wish-icon' => 'border-radius:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'nv_bclr',
            [
                'label' => esc_html__('Border color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-wish-icon' => 'border:1px solid {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ikl',
            [
                'label' => esc_html__('Icon color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-wish-icon' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'isx',
            [
                'label' => esc_html__('Icon size', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tp-wish-icon' => 'font-size:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'e2',
            [
                'label' => esc_html__('Count', 'the-pack-addon'),
            ]
        );
        
        do_action('the_pack_typo', $this,'cxlt_','.tp-wish-count',['bg','padding','radius','border']);

        $this->add_responsive_control(
            'cxvp',
            [
                'label' => esc_html__('Vertical position', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -100,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-wish-count' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cxhp',
            [
                'label' => esc_html__('Horizontal position', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-wish-count' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings();
        require dirname(__FILE__) . '/view.php';
    }
}

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Wishlist_Btn());
