<?php
namespace ThePackAddon\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class The_Pack_Woo_Wishlist_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'tp_woowishlist';
    }
	public function get_style_depends() {
        
		return ['elementor-icons-tpbootstrap'];
	}
    
    public function get_title()
    {
        return esc_html__('Woo wishlist', 'the-pack-addon');
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
            'section_gnrl',
            [
                'label' => esc_html__('General', 'the-pack-addon'),
            ]
        );

        $this->add_control( 
            'icon',
            [
                'type' => Controls_Manager::ICONS,
                'label' => esc_html__('Icon', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'title',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Label', 'the-pack-addon'),
                'label_block' => true,
                'default' => 'Empty Wishlist',
            ]
        );

        $this->add_control(
            'desc',
            [
                'type' => Controls_Manager::TEXTAREA,
                'label' => esc_html__('Description', 'the-pack-addon'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'btn',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('Button label', 'the-pack-addon'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_conten',
            [
                'label' => esc_html__('General', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wdidth',
            [
                'label' => esc_html__('Column width', 'the-pack-addon'),
                'type' => Controls_Manager::NUMBER,
                'default' => '33.33',
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide' => 'width: {{VALUE}}%;flex:0 0 {{VALUE}}%;', 
                ],
            ]
        );

        $this->add_responsive_control( 
            'gap',
            [
                'label' => esc_html__('Gap','the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tp-gutter' => 'margin: 0 0 0 -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tp-gutter>.swiper-slide' => 'padding: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}};',
                ],             
            ]
        );

        $this->add_control(
            'gbgkl',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide>div' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gpdr',
            [
                'label' => esc_html__('Padding','the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide>div' => 'padding: {{SIZE}}{{UNIT}};',
                ],               
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_remove',
            [
                'label' => esc_html__('Remove', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );   

        $this->add_responsive_control(
            'remp',
            [
                'label' => esc_html__('Position', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .dev-ash-wishlist-remove' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        do_action('the_pack_box_control', $this,'rem_','.dev-ash-wishlist-remove',['bg','color','radius','border','width','height']);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_ttl',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        do_action('the_pack_typo', $this,'tl_','.tp-title',['margin','clamp']);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_btn',
            [
                'label' => esc_html__('Button', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        do_action('the_pack_typo', $this,'btn_','.button',['bg','padding','border','hover']);

        $this->add_control(
            'btl1',
            [
                'label' => esc_html__('Primary loader color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'default'=>'#fff',
                'selectors' => [
                    '{{WRAPPER}} a.button.loading::after' => 'border-left: 2px solid {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btl2',
            [
                'label' => esc_html__('Secondary loader color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'default'=>'#000',
                'selectors' => [
                    '{{WRAPPER}} a.button.loading::after' => 'border-right: 2px solid {{VALUE}};border-top: 2px solid {{VALUE}};border-bottom: 2px solid {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_prc',
            [
                'label' => esc_html__('Price', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'prmgd',
            [
                'label' => esc_html__('Margin', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,            
                'selectors' => [
                    '{{WRAPPER}} .price-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('gt');

        $this->start_controls_tab(
            't1',
            [
                'label' => esc_html__('Sale', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'sclr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price-wrap del bdi' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'styp',
                'label' => esc_html__('Typography', 'the-pack-addon'),
                'selector' => '{{WRAPPER}} .price-wrap del bdi',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            't2',
            [
                'label' => esc_html__('Regular', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'rclr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price-wrap bdi' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'rtyp',
                'label' => esc_html__('Typography', 'the-pack-addon'),
                'selector' => '{{WRAPPER}} .price-wrap bdi',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_cat',
            [
                'label' => esc_html__('Category', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'catmrg',
            [
                'label' => esc_html__('Margin', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,            
                'selectors' => [
                    '{{WRAPPER}} .tp-product-cat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'catypo',
                'selector' => '{{WRAPPER}} .tp-product-cat a',
                'label' => esc_html__('Typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'ca_col',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-product-cat a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_stng',
            [
                'label' => esc_html__('Rating', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'rt_fs',
            [
                'label' => esc_html__('Font size', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER, 
                                                                           
               'selectors' => [
                    '{{WRAPPER}} .tscore' => 'font-size:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'rt_clr',
            [
                'label' => esc_html__('Main color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,            
                'selectors' => [
                    '{{WRAPPER}} .tscore' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rt_aclr',
            [
                'label' => esc_html__('Active color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,            
                'selectors' => [
                    '{{WRAPPER}} .tscore span' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'rttypo',
                'selector' => '{{WRAPPER}} .count',
                'label' => esc_html__('Typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'rt_col',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_noprod',
            [
                'label' => esc_html__('Empty Wishlist', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        ); 

        $this->add_responsive_control(
            'falgn',
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
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .no-wishlist' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('gts');

        $this->start_controls_tab(
            't1d',
            [
                'label' => esc_html__('Icon', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_box_control', $this,'ifx_','.tp-icon',['color','font-size']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't2d',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'dfx_','.title',['margin']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't3d',
            [
                'label' => esc_html__('Desc', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'dfdx_','.desc',['margin']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't4d',
            [
                'label' => esc_html__('Button', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'bt6n_','.back-to-shop',['bg','padding','border','hover']);

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

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Wishlist_Widget());
