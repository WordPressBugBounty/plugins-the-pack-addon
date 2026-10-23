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

class The_Pack_Woo_Filter_Active extends Widget_Base
{

    public function get_name()
    {
        return 'tp_wfactive_filter';
    }

    public function get_title()
    {
        return esc_html__('Filter Active', 'the-pack-addon');
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
            'section_dta',
            [
                'label' => esc_html__('Data', 'the-pack-addon'),
            ]
        );
        $this->add_control(
            'abtn',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Remove all filter', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Remove all',
            ]
        );
        $this->add_control(
            'search',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Search', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Search For:',
            ]
        );   
        $this->add_control(
            'onsale',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Onsale', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Onsale',
            ]
        );              
        $this->add_control(
            'stock',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Stock', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Instock',
            ]
        ); 
        $this->add_control(
            'feat',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Search', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Featured:',
            ]
        );  
        $this->add_control(
            'order',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Search', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Orderby:',
            ]
        );                

        $this->add_control(
            'mxpr',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Max price', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Max Price:',
            ]
        );
        $this->add_control(
            'mipr',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Min price', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Min Price:',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_gnrl',
            [
                'label' => esc_html__('Style', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        do_action('the_pack_typo', $this,'tl_','a',['bg','padding','radius','border','hover']);

        $this->add_control(
            'rtk',
            [
                'label' => esc_html__('Rating color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tscore' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'ratk',
            [
                'label' => esc_html__('Active rating color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tscore span' => 'color: {{VALUE}};',
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

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Filter_Active());
