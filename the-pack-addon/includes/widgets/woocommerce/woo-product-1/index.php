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

class The_Pack_Woo_Product_Template_One extends Widget_Base
{

    public function get_name() 
    {
        return 'tp_woo_one';
    }
    // Enqueue styles
	public function get_style_depends() {
		return ['swiper','e-swiper','elementor-icons-tpbootstrap'];
	}

	// Enqueue scripts
	public function get_script_depends() {
		return ['swiper'];
	}
    public function get_title()
    {
        return esc_html__('Product One', 'the-pack-addon');
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
                'label' => esc_html__('Data', 'the-pack-addon'),
            ]
        );

        $this->add_group_control(
            Tp_Woo_Group_Query::get_type(),
            [
                'name' => 'query',
            ]
        );

        $this->add_control(
            'arc_query',
            [
                'label' => esc_html__('Enable archive query', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_head',
            [
                'label' => esc_html__('Hide heading', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,            
            ]
        );

        $this->add_control(
            'ttl',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'hide_head!' => 'yes',
                ],                  
            ]
        );

        $this->add_control(
            'img_size',
            [
                'label' => esc_html__('Image size', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => thepack_image_size_choose(),
                'multiple' => false,
            ]
        );

        $this->add_control(
            'onsale',
            [
                'label' => esc_html__('Onsale display', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'percen' => [
                        'title' => esc_html__('Percentage', 'the-pack-addon'),
                        'icon' => 'eicon-tabs',
                    ],

                    'price' => [
                        'title' => esc_html__('Price', 'the-pack-addon'),
                        'icon' => 'eicon-text-field',
                    ],

                    'saletex' => [
                        'title' => esc_html__('Text', 'the-pack-addon'),
                        'icon' => 'eicon-folder-o',
                    ]
                ],
                'default' => 'percen',
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
                'label' => esc_html__('Display', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'the-pack-addon'),
                        'icon' => 'eicon-gallery-grid',
                    ],
                    'slider' => [
                        'title' => esc_html__('Slider', 'the-pack-addon'),
                        'icon' => 'eicon-slider-album',
                    ],
                    'masonry' => [
                        'title' => esc_html__('Masonry', 'the-pack-addon'),
                        'icon' => 'eicon-slider-album',
                    ]                    
                ],
                'default' => 'grid',
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
                    'disp' => ['grid','masonry'], 
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
                    '{{WRAPPER}} .tp-masonry>.swiper-slide' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'disp' => ['grid','masonry'], 
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

        $this->add_control(
            'gbdrx',
            [
                'label' => esc_html__('Border color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide>div' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .result-item' => 'border-inline-start-color: {{VALUE}};-webkit-border-start-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'gbrdy',
            [
                'label' => esc_html__('Border radius','the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .swiper-slide>div' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],               
            ]
        );

        $this->add_control(
            'sidefil',
            [
                'label' => esc_html__('Side filter','the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'hide_head!' => 'yes',
                    'arc_query!' => 'yes',
                ],  
                'selectors' => [
                    '{{WRAPPER}} .tp-woo-product-layout' => 'display: flex;',
                ],                               
            ]
        );

        $this->add_responsive_control(
            'sidefildir',
            [
                'label' => esc_html__('Flex direction', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'sidefil' => 'yes',
                ],                 
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'the-pack-addon'),
                        'icon' => 'eicon-gallery-grid',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'the-pack-addon'),
                        'icon' => 'eicon-slider-album',
                    ],
                    'row-reverse' => [
                        'title' => esc_html__('Row reverse', 'the-pack-addon'),
                        'icon' => 'eicon-slider-album',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Row reverse', 'the-pack-addon'),
                        'icon' => 'eicon-slider-album',
                    ]                                          
                ],
                'selectors' => [
                    '{{WRAPPER}} .tp-woo-product-layout' => 'flex-direction: {{VALUE}};',
                ],                
            ]
        );

        $this->add_responsive_control(
            'sidebrwd',
            [
                'label' => esc_html__('Sidebar width', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 500,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woo-filter-head' => 'flex:0 0 {{SIZE}}{{UNIT}};flex-direction:column;',
                ],
                'condition' => [
                    'sidefil' => 'yes',
                ], 
            ]
        );

        $this->add_responsive_control(
            'ftbdx',
            [
                'label' => esc_html__('Filter bottom spacing', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tp-before-shop,{{WRAPPER}} .woo-filter-head' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filtohead',
            [
                'label' => esc_html__('Top filter area', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arc_query' => 'yes',
                ],                
            ]
        ); 
        
        do_action('the_pack_box_control', $this,'tpxf_','.tp-before-shop',['bg','padding','radius','border']);

        $this->start_controls_tabs('yx1');

        $this->start_controls_tab(
            'ot1',
            [
                'label' => esc_html__('Button', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'showftbt',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__('Show button', 'the-pack-addon'),
                'selectors' => [
                    '{{WRAPPER}} .side-filter-btn' => 'display: block;',
                ],                 
            ]
        );

        $this->add_control(
            'sidebar_selector',
            [ 
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'label' => esc_html__('Sidebar selector', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'ftby_','.side-filter-btn',['bg','padding','radius','border']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ot2',
            [
                'label' => esc_html__('Result', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'ftcout_','.woocommerce-result-count',[]);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'ot3',
            [
                'label' => esc_html__('Order', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'ftord_','.orderby',['bg','padding','radius','border']);

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();

        $this->start_controls_section(
            'section_filthead',
            [
                'label' => esc_html__('Filter header', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'hide_head!' => 'yes',
                    'arc_query!' => 'yes',
                ],                
            ]
        ); 

        do_action('the_pack_flex', $this,'tpwf_','.woo-filter-head',[]);

        $this->start_controls_tabs('nx1');

        $this->start_controls_tab(
            'xt1',
            [
                'label' => esc_html__('Title', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'tdl_','.filter-title',['margin']);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'xt2',
            [
                'label' => esc_html__('Filters', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'fwfil',
            [
                'label' => esc_html__('Full width','the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .tp-woo-filter' => 'flex-direction: column;width: 100%;',
                ],               
            ]
        );

        $this->add_responsive_control(
            'gt3e',
            [
                'label' => esc_html__('Gap','the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tp-woo-filter' => 'gap: {{SIZE}}{{UNIT}};',
                ],               
            ]
        );

        do_action('the_pack_typo', $this,'twl_','.woo-filter-item',['']);
        
        $this->add_control(
            'hc_color',
            [
                'label' => esc_html__('Hover color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-woo-filter .current,{{WRAPPER}} .tp-woo-filter li:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
            'section_quicka',
            [
                'label' => esc_html__('Quick action', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'qaps',   
            [
                'label' => esc_html__('Position', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .tp-quick-actions' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );        

        $this->add_control(
            'qabg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-quick-actions' => 'background:{{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'qa_klr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-quick-actions i' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'qar-pad',
            [
                'label' => esc_html__('Padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['em', 'px'],
                'selectors' => [
                    '{{WRAPPER}} .tp-quick-actions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'qarbdr',   
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER,                    
                'selectors' => [
                    '{{WRAPPER}} .tp-quick-actions' => 'border-radius: {{SIZE}}{{UNIT}};',
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

        $this->add_control(
            'slpad',
            [
                'label' => esc_html__('Padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,            
                'selectors' => [
                    '{{WRAPPER}} .tp-onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slbg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-onsale' => 'background: {{VALUE}};',
                ],
            ]
        );
 
        $this->add_control(
            'slclr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tp-onsale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sl_typo',
                'selector' => '{{WRAPPER}} .tp-onsale',
                'label' => esc_html__('Typography', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'slbr',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tp-onsale' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'slpos',
            [
                'label' => esc_html__('Top & left position', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],                
                'selectors' => [
                    '{{WRAPPER}} .tp-onsale' => 'top: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};position:absolute;',
                ],

            ]
        );

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
            'section_pgntin',
            [
                'label' => esc_html__('Pagination', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'arc_query' => 'yes',
                ],
            ]
        );

        do_action('the_pack_number_pagination', $this,'.woocommerce-pagination');

        $this->end_controls_section();

    }

    public function before_shop_loop(){
        $settings = $this->get_settings();
        call_user_func('The_Pack_Woo_Helper::bf_shop_filter',$settings);
        
    }

    public function pagination_arrows(){

        $args['prev_text'] = '<i aria-hidden="true" class="tpbi bi-chevron-left"></i>';  
        $args['next_text'] = '<i aria-hidden="true" class="tpbi bi-chevron-right"></i>'; 
        $args['type'] = 'list';
        return $args;
    }

    protected function render()
    {
        $settings = $this->get_settings();

        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
        add_action( 'woocommerce_before_shop_loop', [ $this, 'before_shop_loop' ]);

        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
        remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 ); 

        add_filter( 'woocommerce_pagination_args', [ $this, 'pagination_arrows' ],999);
        
        if($settings['arc_query'] ){
            global $wp_query, $post;
            $file = 'archieve';
            
            if (Plugin::instance()->editor->is_edit_mode()) {

                $wp_query_args = [ 
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'posts_per_page' => $settings['query_num']['size'], 
                ];

                $wp_query = new \WP_Query( $wp_query_args );
                wc_set_loop_prop( 'total', $wp_query->found_posts );
                wc_set_loop_prop( 'total_pages', $wp_query->max_num_pages );
            }
                    
        } else {
            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
            $wp_query_args = build_woo_query($settings,'query');
            $file = 'view';
            $wp_query = new \WP_Query( $wp_query_args );
            wc_set_loop_prop( 'total', $wp_query->found_posts );
            wc_set_loop_prop( 'total_pages', $wp_query->max_num_pages );            
        }
 
        require dirname(__FILE__) . '/'.$file.'.php';
                
    }
}

$widgets_manager->register(new \ThePackAddon\Widgets\The_Pack_Woo_Product_Template_One());
