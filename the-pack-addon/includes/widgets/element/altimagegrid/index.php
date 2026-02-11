<?php
namespace ThePackAddon\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\utils;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

// Exit if accessed directly

class thepack_altimggrid extends Widget_Base
{
    public function get_name()
    {
        return 'tb_altimggrid';
    }

    public function get_title()
    {
        return esc_html__('Imagegrid alt', 'the-pack-addon');
    }

    public function get_icon()
    {
        return 'dashicons dashicons-admin-site';
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
            'title',
            [
                'label' => esc_html__('Label', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'the-pack-addon'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'desc',
            [
                'label' => esc_html__('Description', 'the-pack-addon'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'img',
            [
                'label' => esc_html__('Image', 'the-pack-addon'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'btn',
            [
                'label' => esc_html__('Button Label', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Apply',
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => esc_html__('Link', 'the-pack-addon'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => esc_html__('http://your-link.com', 'the-pack-addon'),
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
        $this->add_control(
            'tmpl',
            [
                'label' => esc_html__('Extra style', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'one' => [
                        'title' => esc_html__('One', 'the-pack-addon'),
                        'icon' => 'eicon-tabs',
                    ],

                    'two' => [
                        'title' => esc_html__('Two', 'the-pack-addon'),
                        'icon' => 'eicon-text-field',
                    ],

                ],
                'default' => 'one',
            ]
        );
        $this->add_control(
            'algn',
            [
                'label' => esc_html__('Alignment', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'alignleft' => [
                        'title' => esc_html__('Left', 'the-pack-addon'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'aligncenter' => [
                        'title' => esc_html__('Center', 'the-pack-addon'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'alignright' => [
                        'title' => esc_html__('Right', 'the-pack-addon'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
            ]
        );
        $this->add_responsive_control(
            'ght',
            [
                'label' => esc_html__('Height', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                    ]
                ],
                'size_units' => ['px','vh'],
                'selectors' => [
                    '{{WRAPPER}} .tb-altimage' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'gpad',
            [
                'label' => esc_html__('Padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['em', 'px'],
                'selectors' => [
                    '{{WRAPPER}} .tb-altimage' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'brd',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tb-altimage' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'pc',
            [
                'label' => esc_html__('Background color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tb-altimage::after' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'pch',
            [
                'label' => esc_html__('Background hover color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tb-altimage .fullink::before' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tx',
            [
                'label' => esc_html__('Hover color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tb-altimage:hover .content-wrap > *' => 'color: {{VALUE}};',
                ],
            ]
        );        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_cnb',
            [
                'label' => esc_html__('Content', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('mtabu');

        $this->start_controls_tab(
            'm1',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
            ]
        );
        do_action('the_pack_typo', $this,'tl_','.title',['margin']);
        $this->end_controls_tab();

        $this->start_controls_tab(
            'm2',
            [
                'label' => esc_html__('Sub', 'the-pack-addon'),
            ]
        );
        do_action('the_pack_typo', $this,'sb_','.desc',['margin']);
        $this->end_controls_tab();

        $this->start_controls_tab(
            'm3',
            [
                'label' => esc_html__('Button', 'the-pack-addon'),
            ]
        );
        do_action('the_pack_typo', $this,'bt_','.tp-read',['bg','padding','radius']);

        $this->end_controls_tab();        

        $this->end_controls_section();

        $this->start_controls_section(
            'section_ik',
            [
                'label' => esc_html__('Icon', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'idf',
            [
                'label' => esc_html__('Width and height', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .icon-wrap' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'ioi',
            [
                'label' => esc_html__('Size', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .icon-wrap i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .icon-wrap img' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'ibrd',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .icon-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'ipc',
            [
                'label' => esc_html__('Background color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-wrap' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'ipoc',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .icon-wrap' => 'color: {{VALUE}};',
                ],
            ]
        );        
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings();
        if (!preg_match("/[^[:alnum:]_\/-]/",$settings['tmpl'])) {
            include plugin_dir_path(__FILE__) . $settings['tmpl'] . '.php';
        }
    }
}

$widgets_manager->register(new \ThePackAddon\Widgets\thepack_altimggrid());
