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

class The_Pack_Woo_Filter_Price extends Widget_Base
{

    public function get_name()
    {
        return 'tp_wfprice';
    }

    public function get_title()
    {
        return esc_html__('Filter Price', 'the-pack-addon');
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
                'label' => esc_html__('Data', 'the-pack-addon'),
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
            'pt',
            [
                'label' => esc_html__('Handle title', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,               
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
                'label' => esc_html__('Slider', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bgr',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .noUi-background' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'ght',   
            [
                'label' => esc_html__('Height', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .noUi-background' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'hfgr',
            [
                'label' => esc_html__('Handle background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .noUi-handle' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hhw',   
            [
                'label' => esc_html__('Handle width', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .noUi-handle' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .noUi-origin' => 'right: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .noUi-extended' => 'padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hde',   
            [
                'label' => esc_html__('Handle height', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .noUi-handle' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hrtde',   
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .noUi-handle' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );        

        $this->add_responsive_control(
            'hvde',   
            [
                'label' => esc_html__('Handle vertical position', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],                                  
                'selectors' => [
                    '{{WRAPPER}} .noUi-handle' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'hvbsw',   
            [
                'label' => esc_html__('Bottom spacing', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                                   
                'selectors' => [
                    '{{WRAPPER}} .noUi-extended' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('gt');

        $this->start_controls_tab(
            't1',
            [
                'label' => esc_html__('Label', 'the-pack-addon'),
            ]
        );
        do_action('the_pack_typo', $this,'cntr_','.slider-value-label',[]);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't2',
            [
                'label' => esc_html__('Price', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'cnt5r_','.slider-value-range',[]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

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

    }


    protected function render()
    {
        $settings = $this->get_settings();      
        require dirname(__FILE__) . '/view.php';
    }
}

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Filter_Price());
