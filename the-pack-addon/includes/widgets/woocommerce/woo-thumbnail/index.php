<?php
namespace ThePackAddon\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\utils;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class The_Pack_Woo_Thumbnail extends Widget_Base
{

    // public function __construct( $data = [], $args = null ) {
	// 	parent::__construct( $data, $args );
    //     add_filter('woocommerce_single_product_carousel_options', function() use ( $data ){
    //         if ( ! $data ) {
    //             return;
    //         }            
    //         if ( $data["settings"]['controlnav'] == 'true'){
    //             $options['controlNav'] = true;
    //         } elseif ( $data["settings"]['controlnav'] == 'false' ){
    //             $options['controlNav'] = false;
    //         } else{
    //             $options['controlNav'] = 'thumbnails';
    //         }
    //         $options['animation'] = "slide" ;
    //         $options['directionNav'] = array_key_exists('direction', $data["settings"]);
    //         return $options;
    //     });       
	// }

    // Enqueue styles
	public function get_style_depends() {
		return ['swiper','e-swiper'];
	}

	// Enqueue scripts
	public function get_script_depends() {
		return ['swiper'];
	}
    public function get_name()
    {
        return 'tp_woothumb';
    }   

    public function get_title()
    {
        return esc_html__('Woo thumbnail', 'the-pack-addon');
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
                'label' => esc_html__('Settings', 'the-pack-addon'),
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
            'img_size',
            [
                'label' => esc_html__('Main image size', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => thepack_image_size_choose(),
                'multiple' => false,
            ]
        );

        $this->add_control(
            'on_sale',
            [
                'label' => esc_html__('On sale type', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'percen' => [
                        'title' => esc_html__('Percentage', 'the-pack-addon'),
                        'icon' => 'eicon-editor-close',
                    ],

                    'price' => [
                        'title' => esc_html__('Price', 'the-pack-addon'),
                        'icon' => 'eicon-circle-o',
                    ],

                    'txt' => [
                        'title' => esc_html__('Text', 'the-pack-addon'),
                        'icon' => 'eicon-image-bold',
                    ],

                ],
                'default' => 'percen',
            ]
        );

        $this->add_control(
            'sale_txt',
            [
                'type' => Controls_Manager::TEXT,
                'label' => esc_html__('On sale label', 'the-pack-addon'),
                'condition' => [
                    'on_sale' => 'txt',
                ],                
                'default' => 'Sale',
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
            'disp',
            [
                'label' => esc_html__('Display type', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'the-pack-addon'),
                        'icon' => 'eicon-editor-close',
                    ],
                    'slide' => [
                        'title' => esc_html__('Slide', 'the-pack-addon'),
                        'icon' => 'eicon-circle-o',
                    ],
                ],
            ]
        );

         $this->add_control(
            'navi',
            [
                'label' => esc_html__('Navigation', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'disp' => 'slide',
                ],                 
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'the-pack-addon'),
                        'icon' => 'eicon-editor-close',
                    ],
                    'dot' => [
                        'title' => esc_html__('Dot', 'the-pack-addon'),
                        'icon' => 'eicon-circle-o',
                    ],
                    'bottom_thumb' => [
                        'title' => esc_html__('Bottom thumbnail', 'the-pack-addon'),
                        'icon' => 'eicon-circle-o',
                    ], 
                    'left_thumb' => [
                        'title' => esc_html__('Left thumbnail', 'the-pack-addon'),
                        'icon' => 'eicon-circle-o',
                    ],                                         
                ],
            ]
        );

        $this->add_control(
            'nav',   
            [
                'label' => esc_html__('Enable navigation arrow', 'the-pack-addon'), 
                'type' => Controls_Manager::SWITCHER,                    
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
                'condition' => [
                    'disp' => ['grid'], 
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__('Gap','the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tp-gutter' => 'margin: -{{SIZE}}{{UNIT}} 0 0 -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .tp-gutter>.swiper-slide' => 'padding: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'disp' => ['grid'], 
                ],                
            ]
        );

        $this->add_control(
            'hideplus',   
            [
                'label' => esc_html__('Hide fullscreen icon', 'the-pack-addon'), 
                'type' => Controls_Manager::SWITCHER, 
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide a::before' => 'display: none;',
                ],                                   
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_sale',
            [
                'label' => esc_html__('Sale', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,              
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_arrow',
            [
                'label' => esc_html__('Arrow', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'nav' => 'yes',
                ],                
            ]
        );

        $this->add_control(
            'arrow',
            [
                'label' => esc_html__('Arrow icon', 'the-pack-addon'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
            ] 
        );

        $this->add_responsive_control(
            'arsz',   
            [
                'label' => esc_html__('Font size', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .thumb-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'arwh',   
            [
                'label' => esc_html__('Width & height', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .thumb-arrow' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'arbr',   
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .thumb-arrow' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'arbg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .thumb-arrow' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'arclr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .thumb-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_thmb',
            [
                'label' => esc_html__('Thumbnail', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navi' => ['bottom_thumb','left_thumb'],
                ],                
            ]
        );
 
        $this->add_responsive_control(
            'ti',   
            [
                'label' => esc_html__('Item per slide', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'default' => [
                    'size' => 4,
                ],                                  
            ]
        );
        $this->add_responsive_control(
            'tgap',   
            [
                'label' => esc_html__('Item gap', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,  
                'default' => [
                    'size' => 20,
                ],                                  
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_dot',
            [
                'label' => esc_html__('Dot', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navi' => 'dot',
                ],                
            ]
        );

        $this->add_control(
            'dal',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dot_sp',
            [
                'label' => esc_html__('Spacing', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bulle' => 'margin:0px {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dvp',
            [
                'label' => esc_html__('Vertical position', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -500,
                        'max' => 500,
                        'step' => 1,
                    ],

                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination' => 'bottom:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'dt-m',
            [
                'label' => esc_html__('Main color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination span' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dt-s',
            [
                'label' => esc_html__('Active color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination span.swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_hidepld',
            [
                'label' => esc_html__('Fullscreen Icon', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,      
                'condition' => [
                    'hideplus!' => 'true',
                ],                        
            ]
        );

        do_action('the_pack_box_control', $this,'fxd_','.swiper-slide a::before',['bg','radius','border','width','height']);

        $this->end_controls_section();

    }

    public function flash_sale($html, $post, $product){
        $settings = $this->get_settings();
        $option = [
            'type'=> $settings['on_sale'],
            'label'=> $settings['sale_txt'],
        ];
        call_user_func('The_Pack_Woo_Helper::on_sale', $product,$option);
    }
    protected function render()
    {    
        global $product;
        $settings = $this->get_settings();  
        add_filter( 'woocommerce_sale_flash', [ $this, 'flash_sale' ] ,9,3);  
        $preview  = isset( $_GET['preview'] ) ? sanitize_text_field(wp_unslash($_GET['preview'])) : '';//phpcs:disable WordPress.Security.NonceVerification.Recommended
        if (Plugin::instance()->editor->is_edit_mode() || $preview == 'true') {
            $product = wc_get_product($settings['preview']);        
        }        
        require dirname(__FILE__) . '/view.php'; 
    }
}

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Thumbnail());
