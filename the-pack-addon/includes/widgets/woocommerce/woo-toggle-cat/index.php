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

class The_Pack_Woo_Toggle_Cat extends Widget_Base
{
    public function get_name()
    {
        return 'tp_woo_tog_cat';
    }

    public function get_title()
    {
        return esc_html__('Toggle Category', 'the-pack-addon');
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
            'hydlbl',
            [
                'label' => esc_html__('Hide button', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );        

        $this->add_control(
            'btik',
            [
                'label' => esc_html__('Button Icon', 'thepackpro'),
                'type' => Controls_Manager::ICONS,
            ]
        );
        $this->add_control(
            'btl',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Button Label', 'the-pack-addon'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'subop',
            [
                'label' => esc_html__('Open submenu', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'open-sub'
            ]
        );

        $this->add_control(
            'absmnu',
            [
                'label' => esc_html__('Absolute position', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'tp-pos-abs'
            ]
        );

        $this->add_control(
            'hydmenu',
            [
                'label' => esc_html__('Hide menu', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .site-categories' => 'display:none;',
                ],
            ]
        );

        $cust_links = new \Elementor\Repeater();

        $cust_links->add_control(
            'item_text',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Menu Item', 'the-pack-addon'),
                'label_block' => true,
            ]
        );

        $cust_links->add_control(
            'icon',
            [
                'label' => esc_html__('Icons', 'the-pack-addon'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $cust_links->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'the-pack-addon'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'the-pack-addon'),
                'show_external' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $cust_links->add_control(
            'has_sub',
            [
                'label' => esc_html__('Have Sub Menu', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'the-pack-addon'),
                'label_off' => esc_html__('No', 'the-pack-addon'),
                'return_value' => 'yes',
            ]
        );

        $cust_links->add_control(
            'menu_register',
            [
                'label' => esc_html__('Menu', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT2,
                'options' => thepack_drop_menu_select(),
                'condition' => [
                    'has_sub' => 'yes',
                ],
                'label_block' => true
            ]
        );

        $this->add_control(
            'menus',
            [
                'label' => esc_html__('Menu', 'the-pack-addon'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $cust_links->get_controls(),
                'prevent_empty' => false,
                'title_field' => '{{{ item_text }}}',
                'default' => [
                    [
                        'item_text' => esc_html__('Home', 'the-pack-addon'),
                    ],
                    [
                        'item_text' => esc_html__('Portfolio', 'the-pack-addon'),
                    ],
                ],
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

        $this->add_responsive_control(
            'gad',
            [
                'label' => esc_html__('Padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .category-toggle,{{WRAPPER}} .tp-menu-wrap>li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'bklr',
            [
                'label' => esc_html__('Border Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .category-toggle' => 'border:1px solid {{VALUE}};',
                    '{{WRAPPER}} .sub-menu' => 'border:1px solid {{VALUE}};;border-left:0;',
                    '{{WRAPPER}} .tp-menu-wrap' => 'border:1px solid {{VALUE}};border-top:0;',
                    '{{WRAPPER}} .site-categories > ul > li + li' => 'border-top:1px solid {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gbg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .site-categories,{{WRAPPER}} .category-toggle,{{WRAPPER}} .sub-menu' => 'background:{{VALUE}};',
                ],
            ]
        );  
        $this->add_responsive_control(
            'igp',
            [
                'label' => esc_html__('Icon gap', 'the-pack-addon'),
                'type'=>Controls_Manager::SLIDER,   
                'selectors' => [
                    '{{WRAPPER}} .menu-item-main-info,{{WRAPPER}} .label-wrap' => 'gap:{{SIZE}}{{UNIT}};',
                ],
            ]
        ); 

        $this->add_responsive_control(
            'svgw',
            [
                'label' => esc_html__('Custom svg width', 'the-pack-addon'),
                'type'=>Controls_Manager::SLIDER,   
                'selectors' => [
                    '{{WRAPPER}} .tp-custom-svg svg' => 'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );  

        $this->add_responsive_control(
            'btwdf',
            [
                'label' => esc_html__('Button Width', 'the-pack-addon'),
                'type'=>Controls_Manager::SLIDER,   
                'range' => [
                    'px' => [
                        'max' => 800,
                    ],
                ],                
                'selectors' => [
                    '{{WRAPPER}} .tp-toggle-cat-wrap' => 'width:{{SIZE}}{{UNIT}};',
                ],
            ]
        );          

        $this->end_controls_section();

        $this->start_controls_section(
            'section_label',
            [
                'label' => esc_html__('Button', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,              
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btyp',
                'selector' => '{{WRAPPER}} .label-wrap',
                'label' => esc_html__('Typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'btklr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .label-wrap' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .drop-icon path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btbdrz',
            [
                'label' => esc_html__('Border width', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .category-toggle' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_menu',
            [
                'label' => esc_html__('Menu', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,              
            ]
        );

        do_action('the_pack_typo', $this,'mnu_','.tp-menu-wrap>li>a',[]);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_smenu',
            [
                'label' => esc_html__('Sub menu', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,              
            ]
        );

        $this->add_responsive_control(
            'sbpd',
            [
                'label' => esc_html__('Wrapper padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        do_action('the_pack_typo', $this,'sb_','.sub-menu a',['padding']);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings();
        require dirname(__FILE__) . '/view.php';
    }
}

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Toggle_Cat());
