<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class TP_Container_Hover
{
    /**
     * Initialize
     *
     * @since 1.0.0
     *
     * @access public
     */
    public static function init()
    {
        add_action('elementor/element/container/section_layout_container/after_section_end', [
            __CLASS__,
            'tp_element_translate'
        ], 10, 2);
        add_action('elementor/element/common/_section_style/after_section_end', [
            __CLASS__,
            'tp_element_translate'
        ], 20, 2);        
        add_action('elementor/frontend/container/before_render', [
            __CLASS__,
            'before_render_options'
        ], 10, 2);
    }

    public static function before_render_options($element)
    {
        $settings = $element->get_settings();

        // if (isset($settings['cont_url']['url']) && !empty($settings['cont_url']['url'])) {
        //     $element->add_render_attribute('_wrapper', 'class', 'tp-clickable-column');
        //     $element->add_render_attribute('_wrapper', 'style', 'cursor: pointer;');
        //     $element->add_render_attribute('_wrapper', 'data-column-clickable', $settings['cont_url']['url']);
        //     $element->add_render_attribute('_wrapper', 'data-column-clickable-blank', $settings['cont_url']['is_external'] ? '_blank' : '_self');
        // }
    }

    public static function tp_element_translate($element, $args)
    { 
        if ('container' == $element->get_name()) {
            $selector = '{{WRAPPER}}';
        } else {
            $selector = '{{WRAPPER}}>.elementor-widget-container';
        }

        $element->start_controls_section(
            'container_hov',
            [
                'label' => esc_html__('Hover background', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $element->add_control(
            'tp_hover_en',
            [
                'label' => esc_html__('Enable', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'tp-container-hover'
            ]
        );
   
        $element->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'tp_hover_v',
                'selector' => '{{WRAPPER}}.tp-container-hoveryes::after',
                'label' => esc_html__('Background', 'the-pack-addon'),
            ]
        );

        $element->end_controls_section();

        $element->start_controls_section(
            'container_invf',
            [
                'label' => esc_html__('Mask radius', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $element->add_control(
            'tpconid',
            [
                'label' => esc_html__('Select style', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => __( 'None', 'the-pack-addon'  ),
                    'tp_inverted_1' => __( 'One', 'the-pack-addon'  ),
                    'tp_inverted_2' => __( 'Two', 'the-pack-addon'  ),
                    'tp_inverted_3' => __( 'Three', 'the-pack-addon'  ),
                    'tp_inverted_4' => __( 'Four', 'the-pack-addon'  ),
                    'tp_inverted_5' => __( 'Five', 'the-pack-addon'  ),
                    'tp_inverted_6' => __( 'Six', 'the-pack-addon'  ),
                    'tp_inverted_7' => __( 'Seven', 'the-pack-addon'  ),
                    'tp_inverted_8' => __( 'Eight', 'the-pack-addon'  ),
                ], 
                'prefix_class' => ''               
            ]
        );

        $element->add_responsive_control(
            'tpconrd',   
            [
                'label' => esc_html__('Radius', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'selectors' => [
                    $selector => '--mskradius:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tpconid!' => '',
                ],                 
            ]
        );
        $element->add_responsive_control(
            'tpcurrd',   
            [
                'label' => esc_html__('Curve size', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'selectors' => [
                    $selector => '--mskcurv:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tpconid!' => '',
                ],                 
            ]
        );
        $element->add_responsive_control(
            'tpcurx',   
            [
                'label' => esc_html__('Horizontal position', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'selectors' => [
                    $selector => '--mskx:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tpconid!' => ['','tp_inverted_5','tp_inverted_6','tp_inverted_7','tp_inverted_8'],
                ],                 
            ]
        );
        $element->add_responsive_control(
            'tpcury',   
            [
                'label' => esc_html__('Vertical position', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'selectors' => [
                    $selector => '--msky:{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'tpconid!' => ['','tp_inverted_5','tp_inverted_6','tp_inverted_7','tp_inverted_8'],
                ],                 
            ]
        ); 

        $element->add_responsive_control(
            'tpcurxp',   
            [
                'label' => esc_html__('Curvature depth', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'selectors' => [
                    $selector => '--mskxp:{{SIZE}}deg;',
                ],
                'condition' => [
                    'tpconid!' => ['','tp_inverted_1','tp_inverted_2','tp_inverted_3','tp_inverted_4'],
                ],                 
            ]
        );
        $element->add_responsive_control(
            'tpcuryp',   
            [
                'label' => esc_html__('Vertical position', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'selectors' => [
                    $selector => '--mskyp:{{SIZE}}%;',
                ],
                'condition' => [
                    'tpconid!' => ['','tp_inverted_1','tp_inverted_2','tp_inverted_3','tp_inverted_4'],
                ],                 
            ]
        ); 
        $element->end_controls_section();        
    }
}

TP_Container_Hover::init();
