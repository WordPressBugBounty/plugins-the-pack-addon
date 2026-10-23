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

class The_Pack_Woo_Action_Menu extends Widget_Base
{

    public function get_name()
    {
        return 'tp_woo_action_menu';
    }

    public function get_title()
    {
        return esc_html__('Action Menu', 'the-pack-addon');
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
            'user',
            [
                'type' => Controls_Manager::ICONS,
                'label' => esc_html__('User icon', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'sign',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => esc_html__('Sign in heading', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'wel',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => esc_html__('Welcome heading', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'signsub',
            [
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => esc_html__('Sign in sub heading', 'the-pack-addon'),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'end',
            [
                'type' => Controls_Manager::SELECT,
                'options' => $this->thepaack_drop_taxolist(),
                'label_block' => true,
                'label' => esc_html__('End point', 'the-pack-addon'),
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'type' => Controls_Manager::ICONS,
                'label' => esc_html__('Icon', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'tabs',
            [
                'type' => Controls_Manager::REPEATER,
                'prevent_empty' => false,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ end }}}',
            ]
        );

        $this->add_control(
            'arrow',
            [
                'type' => Controls_Manager::ICONS,
                'label' => esc_html__('Arrow icon', 'the-pack-addon'),
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
            'thgp',
            [
                'label' => esc_html__('Thumb content gap', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .action-wrap' => 'gap: {{SIZE}}{{UNIT}};',
                ],

            ]
        ); 

        $this->start_controls_tabs('tctb');

        $this->start_controls_tab(
            'e1',
            [
                'label' => esc_html__('Avatar', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'avw',
            [
                'label' => esc_html__('Width', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .avatar-icon' => 'flex: 0 0 {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_control(
            'ibg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .avatar-icon' => 'background:{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'avrd',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .avatar-icon' => 'border-radius:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'nv_bclr',
            [
                'label' => esc_html__('Border color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .avatar-icon' => 'border:1px solid {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ikl',
            [
                'label' => esc_html__('Icon color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .avatar-icon' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'isx',
            [
                'label' => esc_html__('Icon size', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .avatar-icon' => 'font-size:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'e2',
            [
                'label' => esc_html__('Heading', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'tl_','.heading',['margin']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'e3',
            [
                'label' => esc_html__('Sub Title', 'the-pack-addon'),
            ]
        );
        
        do_action('the_pack_typo', $this,'sbl_','.sub',[]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_ik',
            [
                'label' => esc_html__('Drop down', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        ); 
        $this->add_control(
            'chwpt',
            [
                'label' => esc_html__('Wrapper padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'chbrd',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .sub-menu' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'sbxd',
                'selector' => '{{WRAPPER}} .sub-menu',
                'label' => esc_html__('Box shadow', 'the-pack-addon'),
            ]
        );                

        $this->add_responsive_control(
            'ralgn',
            [
                'label' => esc_html__('Start from right', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .sub-menu' => 'right:0;left:auto;',
                ],
            ]
        );

        $this->add_control(
            'ipc',
            [
                'label' => esc_html__('Background color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sub-menu' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'chwid',
            [
                'label' => esc_html__('Width', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .sub-menu' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'igap',
            [
                'label' => esc_html__('Icon gap', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .sub-menu a' => 'gap: {{SIZE}}{{UNIT}};display:flex;',
                ],
            ]
        );
       
        $this->start_controls_tabs('joker');

        $this->start_controls_tab(
            'j1',
            [
                'label' => esc_html__('Icon', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'iclr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sub-menu i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'j2',
            [
                'label' => esc_html__('Text', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'mlt_','.sub-menu a',['padding']);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function thepaack_drop_taxolist() {

        $taxonomies = wc_get_account_menu_items();

        $categories = [];

        if( $taxonomies ){
            foreach( $taxonomies as $taxonomy => $label ){
                $categories[$taxonomy] = $label;
            }
        }
        return $categories;
    }

    protected function render()
    {
        $settings = $this->get_settings();
        $preview  = isset( $_GET['preview'] ) ? sanitize_text_field(wp_unslash($_GET['preview'])) : '';//phpcs:disable WordPress.Security.NonceVerification.Recommended 
        if (Plugin::instance()->editor->is_edit_mode() || $preview == 'true') {
            //$product = wc_get_product($settings['preview']);        
        } else {
            //global $product;
            //$product = wc_get_product();            
        }         
        require dirname(__FILE__) . '/view.php';
    }

    protected function get_endpoint_label_from_slug($endpoint_slug){

        $endpoint_labels = get_option( 'woocommerce_myaccount_endpoint_labels', array() );
        $default_endpoints = array(
            'orders'          => __( 'Orders', 'the-pack-addon' ),
            'view-order'      => __( 'View Order', 'the-pack-addon' ),
            'downloads'       => __( 'Downloads', 'the-pack-addon' ),
            'edit-account'    => __( 'Account details', 'the-pack-addon' ),
            'edit-address'    => __( 'Addresses', 'the-pack-addon' ),
            'payment-methods' => __( 'Payment methods', 'the-pack-addon' ),
            'customer-logout' => __( 'Logout', 'the-pack-addon' ),
            'dashboard'       => __( 'Dashboard', 'the-pack-addon' ),
        );

        $all_labels = array_merge( $default_endpoints, $endpoint_labels );
        return isset( $all_labels[ $endpoint_slug ] ) ? $all_labels[ $endpoint_slug ] : $endpoint_slug;

    }
}

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Action_Menu());
