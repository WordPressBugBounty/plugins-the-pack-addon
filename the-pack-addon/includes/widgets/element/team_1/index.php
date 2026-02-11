<?php
namespace ThePackAddon\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\utils;

if (!defined('ABSPATH')) {
    exit;
}

class thepack_team1 extends Widget_Base
{
    public function get_name()
    {
        return 'tb_team1';
    }
 
    public function get_title()
    {
        return esc_html__('Team 1', 'the-pack-addon');
    }

    public function get_icon()
    {
        return 'dashicons dashicons-post-status';
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
            'name',
            [
                'label' => esc_html__('Name', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Mr Wick',
            ]
        );

        $this->add_control(
            'pos',
            [
                'label' => esc_html__('Position', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Google,Gamer',
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => esc_html__('Link', 'the-pack-addon'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'avatar',
            [
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
                'label' => esc_html__('Avatar', 'the-pack-addon'),
            ]
        );

        $repeater1 = new \Elementor\Repeater();

        $repeater1->add_control(
            'url',
            [
                'label' => esc_html__('Social url', 'the-pack-addon'),
                'type' => Controls_Manager::URL,
            ]
        );

        $repeater1->add_control(
            'icon',
            [
                'label' => esc_html__('Social icon', 'the-pack-addon'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'items',
            [
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater1->get_controls(),
                'prevent_empty' => false,
                'title_field' => '{{{ elementor.helpers.renderIcon( this, icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}}',
            ]
        );

        $this->add_control(
            'plus',
            [
                'label' => esc_html__('Link icon', 'the-pack-addon'),
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

        $this->add_control(
            'tmpl',
            [
                'label' => esc_html__('Template', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'one' => [
                        'title' => esc_html__('One', 'the-pack-addon'),
                        'icon' => 'eicon-gallery-grid',
                    ],
                    'two' => [
                        'title' => esc_html__('Two', 'the-pack-addon'),
                        'icon' => 'eicon-slider-album',
                    ]
                ],
                'default' => 'one',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_img',
            [
                'label' => esc_html__('Thumbnail', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'nxcd',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .thumb-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'tht',
            [
                'label' => esc_html__('Height', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 800,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .thumb-wrap img' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_cnt',
            [
                'label' => esc_html__('Content', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            's1pad',
            [
                'label' => esc_html__('Padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .tpinfo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('gt');

        $this->start_controls_tab(
            't1',
            [
                'label' => esc_html__('Name', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'nm_','.name',['margin']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't2',
            [
                'label' => esc_html__('Position', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'ps_','.pos');

        $this->end_controls_tab();

        $this->start_controls_tab(
            't3',
            [
                'label' => esc_html__('Button', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'bradt',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .more-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'brahr',
            [
                'label' => esc_html__('Width and height', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .more-btn' => 'flex: 0 0 {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'f_bg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .more-btn' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'f_bgx',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .more-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'f_bgh',
            [
                'label' => esc_html__('Hover background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .more-btn:hover' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'p_bgh',
            [
                'label' => esc_html__('Hover color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .more-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_scf',
            [
                'label' => esc_html__('Social', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'fdir',
            [
                'label' => esc_html__('Flex direction', 'thepackpro'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'column' => [
                        'title' => esc_html__('Column', 'thepackpro'),
                        'icon' => 'eicon-image',
                    ],
                    'row' => [
                        'title' => esc_html__('Row', 'thepackpro'),
                        'icon' => 'eicon-image',
                    ],                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .social' => 'flex-direction: {{VALUE}};',
                ],                
            ]
        );
        $this->add_responsive_control(
            'xgw',
            [
                'label' => esc_html__('Icon gap', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .social' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'obradt',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .social a' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'nhy',
            [
                'label' => esc_html__('Width and height', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .social a' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'obrfdc',
            [
                'label' => esc_html__('Icon size', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .social a' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'xf_bg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .social a' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'xf_bgx',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .social a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'xof_bgh',
            [
                'label' => esc_html__('Hover background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .social a:hover' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            '8f_bgh',
            [
                'label' => esc_html__('Hover color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .social a:hover' => 'color: {{VALUE}};',
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

$widgets_manager->register(new \ThePackAddon\Widgets\thepack_team1());
