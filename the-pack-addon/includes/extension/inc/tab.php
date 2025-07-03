<?php

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH')) {
    exit;
}
 
class The_Pack_Tab_Extra_Control
{
    public static function init()
    {
        add_action('elementor/element/nested-tabs/section_box_style/after_section_end', [
            __CLASS__,
            'tp_callback_function'
        ], 10, 2);
    }

    public static function tp_callback_function($element, $args)
    {
        $element->start_controls_section(
            'tpbtnsx',
            [
                'label' => esc_html__('Extra style', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $element->add_control(
            'tp_ishx',
            [
                'label' => esc_html__('Fade effect', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .e-active' => 'animation-name: fadeInOpacity;animation-timing-function: ease-in;animation-duration: .5s;',
                ],
            ]
        );

        $element->add_control(
            'tp_ht',
            [
                'label' => esc_html__('Hover title', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .e-n-tab-title' => 'cursor: pointer;',
                ],
            ]
        );

        $element->end_controls_section();
    }
}

The_Pack_Tab_Extra_Control::init();
