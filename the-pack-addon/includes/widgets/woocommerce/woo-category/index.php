<?php
namespace ThePackAddon\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\utils;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class The_Pack_Woo_Category extends Widget_Base
{

    public function get_name()
    {
        return 'tp_woocat';
    }

    public function get_title()
    {
        return esc_html__('Woo category', 'the-pack-addon');
    }

    public function get_icon()
    {
        return 'dashicons dashicons-admin-network';
    }

    public function get_categories()
    {
        return ['thepack-woo'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_options',
            [
                'label' => esc_html__('Query', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'tmpl',
            [
                'label' => esc_html__('Extra style', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'icon' => [
                        'title' => esc_html__('Icon', 'the-pack-addon'),
                        'icon' => 'eicon-tabs',
                    ],

                    'img' => [
                        'title' => esc_html__('Image', 'the-pack-addon'),
                        'icon' => 'eicon-text-field',
                    ],

                ],
                'default' => 'icon',
            ]
        );

         $this->add_control(
            'tax',
            [
                'label' => esc_html__('Taxonomy', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT, 
                'options' => thepaack_drop_taxolist(),
                'label_block' => true,
            ]
        );
         $this->add_control(
            'cat',
            [
                'label' => esc_html__('Taxonomy terms', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT, 
                'options' => thepack_drop_cat(),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'icon',
            [
                'type' => Controls_Manager::ICONS,
                'label' => esc_html__('Icon', 'the-pack-addon'),
                'label_block' => true,
                'condition' => [
                    'tmpl' => 'icon',
                ],                 
            ]
        );

        $this->add_control(
            'img',
            [
                'type' => Controls_Manager::MEDIA,
                'label' => esc_html__('Image', 'the-pack-addon'),
                'label_block' => true,
                'condition' => [
                    'tmpl' => 'img',
                ],                 
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_gnrl',
            [
                'label' => esc_html__('General', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__('Template', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => esc_html__('One', 'the-pack-addon'),
                        'icon' => 'eicon-gallery-grid',
                    ],

                    '2' => [
                        'title' => esc_html__('Two', 'the-pack-addon'),
                        'icon' => 'eicon-slider-album',
                    ]

                ],
                'default' => '1',
            ]
        );

        do_action('the_pack_flex', $this,'gf_','.tp-woo-category-box',['']);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_conten',
            [
                'label' => esc_html__('Content', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gag',
            [
                'label' => esc_html__('Alignment', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'the-pack-addon'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'the-pack-addon'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'the-pack-addon'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cat-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hcount',
            [
                'label' => esc_html__('Hide counter', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .count' => 'display: none;',
                ],                
            ]
        ); 

        $this->start_controls_tabs('gt');

        $this->start_controls_tab(
            't1',
            [
                'label' => esc_html__('Thumb', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'n_color',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .inactikn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ifs',   
            [
                'label' => esc_html__('Icon size', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .inactikn' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        do_action('the_pack_box_control', $this,'bxr_','.media',['bg','padding','radius','border','width','height']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't2',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'tdl_','.title',['margin']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't3',
            [
                'label' => esc_html__('Count', 'the-pack-addon'),
                'condition' => [
                    'hcount!' => 'yes',
                ],                
            ]
        );

        do_action('the_pack_typo', $this,'cny_','.count',[]);

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

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Category());
