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

class The_Pack_Woo_Add_To_Cart extends Widget_Base
{
    public function get_name()
    {
        return 'tp_wooaddtocart';
    }

    public function get_title()
    {
        return esc_html__('Woo add to cart', 'the-pack-addon');
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
                'label' => esc_html__('Options', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'preview',
            [
                'label' => esc_html__('Preview product', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => thepack_drop_posts('product'),
                'multiple' => false,
            ]
        );

        $this->add_control(
            'buynow',
            [
                'label' => esc_html__('Add Buy Now Button', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ] 
        );

        $this->add_control(
            'buynowt',
            [
                'label' => esc_html__('Buy Now label', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Buy Now',
                'label_block' => true,
            ] 
        );

        $this->add_control(
            'action',
            [
                'label' => esc_html__('Click action', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Do nothing', 'the-pack-addon'),
                    'cart' => esc_html__('Redirect to cart', 'the-pack-addon'),
                    'checkout' => esc_html__('Redirect to checkout', 'the-pack-addon'),
                ]
            ]
        );

        $this->add_control(
            'btnuz',
            [
                'label' => esc_html__('Use as single product', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ] 
        );

        $this->add_control(
            'cart_txt',
            [
                'label' => esc_html__('Add to cart label', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ] 
        );

        $this->add_control(
            'cart_txt_ex',
            [
                'label' => esc_html__('External add to cart label', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
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
            'gap',
            [
                'label' => esc_html__('Gap', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tpsinglecart:not(.external) form.cart,{{WRAPPER}} .woocommerce-variation-add-to-cart' => 'gap: {{SIZE}}{{UNIT}};', 
                    '{{WRAPPER}} form.cart:not(.variations_form) .quantity' => 'margin-left: -{{SIZE}}{{UNIT}};',
                    // '{{WRAPPER}} form.cart:not(.variations_form) .tp-add-to-cart' => 'margin-right: -{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'btht',
            [
                'label' => esc_html__('Height', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'hdqt',
            [
                'label' => esc_html__('Hide quantity', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .quantity' => 'display: none;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_swatch',
            [
                'label' => esc_html__('Swatch', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'swlty',
                'selector' => '{{WRAPPER}} .cart .variations label',
                'label' => esc_html__('Label Typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'swklrt',
            [
                'label' => esc_html__('Label Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cart .variations label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sw2lin',
            [
                'label' => esc_html__('Linebreak after label', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .variations tr' => 'flex-direction: column;',
                ],
            ]
        );
        $this->add_responsive_control(
            'swgp',   
            [
                'label' => esc_html__('Label gap', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .variations tr' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'swidf',   
            [
                'label' => esc_html__('Label width', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,   
                'range' => [
                    'px' => [
                        'max' => 200,
                    ]
                ],                                 
                'selectors' => [
                    '{{WRAPPER}} .variations th' => 'flex:0 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'swcrk',
            [
                'label' => esc_html__('Cross color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .with_cross:after,{{WRAPPER}} .with_cross:before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('itb');

        $this->start_controls_tab(
            'itb1',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'swcw',   
            [
                'label' => esc_html__('Width & height', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,    
                'default' => [
                    'size' => 30,
                ],                                
                'selectors' => [
                    '{{WRAPPER}} .wcvs-swatch-color > .variation_check' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'swkbrgp',   
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .wcvs-swatch-color > .variation_check' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'itb2',
            [
                'label' => esc_html__('Label', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'swlb_','.wcvs-swatch-label .variation_check',['bg','padding','border','radius']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'itb3',
            [
                'label' => esc_html__('Image', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'swigwd',   
            [
                'label' => esc_html__('Width', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .wcvs-swatch-image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'swight',   
            [
                'label' => esc_html__('Height', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .wcvs-swatch-image img' => 'height: {{SIZE}}{{UNIT}};object-fit:cover;',
                ],
            ]
        );
        $this->add_control(
            'swkiggp',   
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .wcvs-swatch-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'itb4',
            [
                'label' => esc_html__('Tooltip', 'the-pack-addon'),
            ]
        );
        $this->add_control(
            'swttbg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wcvs-color-tooltip' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .wcvs-color-tooltip:after' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );
        do_action('the_pack_typo', $this,'swtt_','.wcvs-color-tooltip',['padding','radius']);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_btn',
            [
                'label' => esc_html__('Button', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btnflw',
            [
                'label' => esc_html__('Full width button', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .tpsinglecart form.cart' => 'flex-wrap:wrap;',
                ],                
            ] 
        );

        $this->add_control(
            'btnips',
            [
                'label' => esc_html__('Left icon', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button' => 'flex-direction:row-reverse;',
                ],                
            ] 
        );

        $this->add_control(
            'btbg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button,{{WRAPPER}} .wc-forward' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->add_control(
            'btclr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button,{{WRAPPER}} .wc-forward' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'btbgh',
            [
                'label' => esc_html__('Hover background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover,{{WRAPPER}} .wc-forward:hover' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'btclrh',
            [
                'label' => esc_html__('Hover color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button:hover,{{WRAPPER}} .wc-forward:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btyp',
                'selector' => '{{WRAPPER}} .single_add_to_cart_button,{{WRAPPER}} .wc-forward',
                'label' => esc_html__('Typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'btbr',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .single_add_to_cart_button,{{WRAPPER}} .wc-forward' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_qty',
            [
                'label' => esc_html__('Quantity', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'qtbg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .qty,{{WRAPPER}} .qtbtn' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'qtklr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .qty,{{WRAPPER}} .qtbtn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'qt_typo',
                'selector' => '{{WRAPPER}} .qty,{{WRAPPER}} .qtbtn',
                'label' => esc_html__('Typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'qtwid',
            [
                'label' => esc_html__('Input width', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .quantity .qty' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'qtbclr',
            [
                'label' => esc_html__('Border color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .qtbtn' => 'border:1px solid {{VALUE}};',
                    '{{WRAPPER}} .qty' => 'border-top-color:{{VALUE}};border-bottom-color:{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'qitht',
            [
                'label' => esc_html__('Plus/minus width', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .qtbtn' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();     

        $this->start_controls_section(
            'section_vpdr',
            [
                'label' => esc_html__('Variation Price', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gtt');

        $this->start_controls_tab(
            't1t',
            [
                'label' => esc_html__('Sale', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'stl_','del bdi',['']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't2t',
            [
                'label' => esc_html__('Regular', 'the-pack-addon'),
            ]
        );
   
        do_action('the_pack_typo', $this,'rtg_','bdi',['']);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    public function minus() {
		echo '<button type="button" class="minus qtbtn" >-</button>';
	}

    public function plus() {
		echo '<button type="button" class="plus qtbtn" >+</button>';
	}


    public function single_add_cart(){
        global $product;
        $product_type = $product->get_type();
        $settings = $this->get_settings();

        if (is_product ()) {
            switch ( $product_type ) {
                case 'external':
                    return $settings['cart_txt_ex'];
    
                break;
                case 'grouped':
                    return $settings['cart_txt'];
    
                break;
                case 'simple':
                    return $settings['cart_txt'];
    
                break;
                case 'variable':
                    return $settings['cart_txt'];
    
                break;
                default:
                    return esc_html__( 'Read More', 'the-pack-addon' );
            }
        }
    }

    public function start_btn_wrap(){
		global $product;
		if ( $product->get_type() == 'external' ) {
			return;
		}        
        echo '<div class="tp-add-to-cart">'; 
    }

    public function end_btn_wrap(){
		global $product;
		if ( $product->get_type() == 'external' ) {
			return;
		}        
        echo '</div>'; 
    }

	public function buy_now_button() {

		global $product;
		if ( $product->get_type() == 'external' ) {
			return;
		}
        $settings = $this->get_settings();

		printf( '<button value="%d" class="single_add_to_cart_button buy_now_button button alt">%s</button>', $product->get_ID(), esc_attr($settings['buynowt']) );
	}

    protected function render()
    {    
        $settings = $this->get_settings();
        global $product;
        add_action( 'woocommerce_before_quantity_input_field', [ $this, 'minus' ] ,9);
        add_action( 'woocommerce_after_quantity_input_field', [ $this, 'plus' ] ,9);
        add_action( 'woocommerce_after_add_to_cart_quantity', [ $this, 'start_btn_wrap'],9);
        add_action( 'woocommerce_after_add_to_cart_button', [ $this, 'end_btn_wrap'],9);
        
        if ( $settings['buynow']){
            add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'buy_now_button' ), 80 );
        }

        if ( $settings['cart_txt'] || $settings['cart_txt_ex'] ){
            add_action( 'woocommerce_product_single_add_to_cart_text', [ $this, 'single_add_cart' ] ,9);
        } 

        $preview  = isset( $_GET['preview'] ) ? sanitize_text_field(wp_unslash($_GET['preview'])) : '';//phpcs:disable WordPress.Security.NonceVerification.Recommended 
        if (Plugin::instance()->editor->is_edit_mode() || $preview == 'true' || $settings['btnuz'] ) {
            $product = wc_get_product($settings['preview']);
        }        
        require dirname(__FILE__) . '/view.php';
    } 
}

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Add_To_Cart());
