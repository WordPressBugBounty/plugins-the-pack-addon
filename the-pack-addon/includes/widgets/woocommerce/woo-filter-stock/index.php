<?php
namespace ThePackAddon\Widgets;
use Elementor\Plugin;
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

class The_Pack_Woo_Filter_Stock extends Widget_Base
{

    public function get_name()
    {
        return 'tp_wfstok';
    }

    public function get_title()
    {
        return esc_html__('Filter Stock', 'the-pack-addon');
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
            'section_data',
            [
                'label' => esc_html__('General', 'the-pack-addon'),
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,               
            ]
        );

        $this->add_control(
            'stock',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Stock label', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Instock',
            ]
        );

        $this->add_control(
            'sale',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Sale label', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Onsale',
            ]
        );

        $this->add_control(
            'feat',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Feature label', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Featured',
            ]
        );

        $this->add_control(
            'accor',
            [
                'label' => esc_html__('Enable accordion', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'return_value'=> 'has-accordion'
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
            'gin',
            [
                'label' => esc_html__('Inline list', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .wcapf-layered-nav>ul' => 'flex-direction:row;',
                ],
            ]
        );
        $this->add_control(
            'hick',
            [
                'label' => esc_html__('Hide checkbox', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} li::before' => 'display:none;',
                ],
            ]
        ); 

        $this->add_responsive_control(
            'ggp',   
            [
                'label' => esc_html__('Spacing', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .wcapf-layered-nav>ul' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_hdi',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        ); 

        do_action('the_pack_typo', $this,'hdg_','.filter-heading',['margin']);
        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_list',
            [
                'label' => esc_html__('List style', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        ); 

        $this->add_responsive_control(
            'cgp',
            [
                'label' => esc_html__('Gap', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} a' => 'gap: {{SIZE}}{{UNIT}}',
                ],

            ]
        );

        do_action('the_pack_typo', $this,'lst_','a',['padding','bg','radius','border','hover']);

        $this->add_control(
            'acbg',
            [
                'label' => esc_html__('Active background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .chosen>a' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'acklr',
            [
                'label' => esc_html__('Active color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .chosen>a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'acbklr',
            [
                'label' => esc_html__('Active border color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .chosen>a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_checkbx',
            [
                'label' => esc_html__('Checkbox', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hick!' => 'yes',
                ],                
            ]
        ); 

        $this->add_control(
            'ckwh',   
            [
                'label' => esc_html__('Width and height', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} li::before' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ckbrd',   
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} li::before' => 'border-radius:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ckbvh',   
            [
                'label' => esc_html__('Vertical position', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER, 
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],                                    
                'selectors' => [
                    '{{WRAPPER}} li::before' => 'top:{{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'ckbc',
            [
                'label' => esc_html__('Border color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} li::before' => 'border:1px solid {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ckabc',
            [
                'label' => esc_html__('Active background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} li.chosen::before' => 'background:{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ckakl',
            [
                'label' => esc_html__('Active color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} li.chosen::before' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }


    protected function render()
    {
        $settings = $this->get_settings();      
        require dirname(__FILE__) . '/view.php';
    }
}

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Filter_Stock());
