<?php
namespace ThePackAddon\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography; 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
 
class Woo_Product_Tabs extends Widget_Base {

    public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->the_hooks();
	}

	public function get_name() {
		return 'tp-woo-tabs';
	}

	public function get_title() {
		return esc_html__( 'Product Tabs', 'the-pack-addon' );
	}

	public function get_icon() {
		return 'eicon-product-tabs';
	}
    public function get_categories()
    {
        return ['thepack-woo'];
    }

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_options',
			[
				'label' => esc_html__( 'Options', 'the-pack-addon' ),
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
            'hide_desc',
            [
                'label' => esc_html__('Hide description', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_info',
            [
                'label' => esc_html__('Hide additional info', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_review',
            [
                'label' => esc_html__('Hide review', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'des_lbl',
            [
                'label' => esc_html__('Description label', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'hide_desc!' => 'yes',
                ],                
            ]
        );

        $this->add_control(
            'adi_lbl',
            [
                'label' => esc_html__('Info label', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'hide_info!' => 'yes',
                ],                
            ]
        );

        $this->add_control(
            'rate_lbl',
            [
                'label' => esc_html__('Review label', 'the-pack-addon'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
                    'hide_review!' => 'yes',
                ],                
            ]
        );

        $this->add_control(
            'disp',
            [
                'label' => esc_html__('Display', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'tab' => [
                        'title' => esc_html__('Tab', 'the-pack-addon'),
                        'icon' => 'eicon-folder',
                    ],
                    'accordion' => [
                        'title' => esc_html__('Accordion', 'the-pack-addon'),
                        'icon' => 'eicon-folder-o',
                    ]
                ],
                'default' => 'tab',
            ]
        );

        $this->add_control(
            'hide_heading',
            [
                'label' => esc_html__('Hide heading', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'closed',
            [
                'label' => esc_html__('Collapsed all', 'the-pack-addon'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

		$this->end_controls_section();

        $this->start_controls_section(
            'section_gen_style',
            [
                'label' => esc_html__('General', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tbstyle',
            [
                'label' => esc_html__('Style', 'the-pack-addon'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'sone' => [
                        'title' => esc_html__('One', 'the-pack-addon'),
                        'icon' => 'eicon-folder',
                    ],
                    'stwo' => [
                        'title' => esc_html__('Two', 'the-pack-addon'),
                        'icon' => 'eicon-folder-o',
                    ]
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .tab-name',
                'label' => esc_html__('Typography', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__('Gap','the-pack-addon'),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tpwootab .wc-tabs' => 'gap:{{SIZE}}{{UNIT}};',
                ],               
            ]
        );

        $this->add_responsive_control(
            'tpad',
            [
                'label' => esc_html__('Padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['em', 'px'],
                'selectors' => [
                    '{{WRAPPER}} .tab-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'btbdr',
            [
                'label' => esc_html__('Bottom border', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wc-tabs' => 'border-bottom:1px solid {{VALUE}};',
                    '{{WRAPPER}} .tpwooaccordion details' => 'border-top:1px solid {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'thck',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tab-name' => 'color:{{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'thckhr',
            [
                'label' => esc_html__('Hover color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tab-name:hover,{{WRAPPER}} .active .tab-name,{{WRAPPER}} .woocommerce-tabs [open] .tab-name' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tcmpad',
            [
                'label' => esc_html__('Content margin', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['em', 'px'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-tabs .panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_descr',
            [
                'label' => esc_html__('Description', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,              
            ]
        );

        do_action('the_pack_typo', $this,'dsc_','.woocommerce-Tabs-panel--description',[]);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_additional',
            [
                'label' => esc_html__('Additional Information', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,              
            ]
        );

        $this->start_controls_tabs('gtx');

        $this->start_controls_tab(
            't1d',
            [
                'label' => esc_html__('Label', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'adl_','.shop_attributes th',[]);

        $this->end_controls_tab();

        $this->start_controls_tab(
            't2x',
            [
                'label' => esc_html__('Value', 'the-pack-addon'),
            ]
        );

        do_action('the_pack_typo', $this,'adlv_','.shop_attributes td',[]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_review',
            [
                'label' => esc_html__('Reviews', 'the-pack-addon'),
                'tab' => Controls_Manager::TAB_STYLE,              
            ]
        );

        do_action('the_pack_typo', $this,'cmt_','.woocommerce-Reviews-title',['margin']);

        $this->start_controls_tabs('gt');

        $this->start_controls_tab(
            't1',
            [
                'label' => esc_html__('Avatar', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'thm_wid',
            [
                'label' => esc_html__('Thumbnail width', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER, 
                'default' => [
                    'size' => 70,
                ],                                                                           
                'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment .avatar-wrap' => 'flex:0 0 {{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->add_responsive_control(
            'thm_brd',
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER, 
                                                                         
                'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment .avatar-wrap img' => 'border-radius:{{SIZE}}{{UNIT}};',
                ],

            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            't2',
            [
                'label' => esc_html__('Comment', 'the-pack-addon'),
            ]
        );

        $this->add_responsive_control(
            'cmt_wrp',
            [
                'label' => esc_html__('Comment list wrapper spacing', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER, 
                                                         
               'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment li' => 'gap:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'cmt_lyst',
            [
                'label' => esc_html__('Comment list spacing', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER, 
                                                                           
               'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment li+li' => 'margin-top:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'rt_hd',
            [
                'label' => esc_html__('Star rating', 'the-pack-addon'),
                'type' =>Controls_Manager::HEADING,
                'separator' => 'after',                                                            
            ]
        );

        $this->add_responsive_control(
            'rt_fs',
            [
                'label' => esc_html__('Font size', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER, 
                                                                           
               'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment  .tscore,{{WRAPPER}} .stars a' => 'font-size:{{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'rt_clr',
            [
                'label' => esc_html__('Main color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,            
                'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment  .tscore' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rt_aclr',
            [
                'label' => esc_html__('Active color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,            
                'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment  .tscore span,{{WRAPPER}} .stars a' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'nm_hd',
            [
                'label' => esc_html__('Author meta', 'the-pack-addon'),
                'type' =>Controls_Manager::HEADING,
                'separator' => 'after',                                                            
            ]
        );

        $this->add_responsive_control(
            'mtmrg',
            [
                'label' => esc_html__('Margin', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,                  
                'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment .author-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nmypo',
                'selector' => '{{WRAPPER}} .tp-woocomment-comment  .author-meta .author',
                'label' => esc_html__('Name typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'nm_clr',
            [
                'label' => esc_html__('Name color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,            
                'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment  .author-meta .author' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dt_clr',
            [
                'label' => esc_html__('Date color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,            
                'selectors' => [
                    '{{WRAPPER}} .tp-woocomment-comment  .author-meta time' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dtypo',
                'selector' => '{{WRAPPER}} .tp-woocomment-comment  .author-meta time',
                'label' => esc_html__('Time ypography', 'the-pack-addon'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ctxpo',
                'selector' => '{{WRAPPER}} .tp-comment-content',
                'label' => esc_html__('Comment typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'ctx_clr',
            [
                'label' => esc_html__('Comment color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,            
                'selectors' => [
                    '{{WRAPPER}} .tp-comment-content' => 'color:{{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            't4',
            [
                'label' => esc_html__('Form', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'inbg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-control' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'inlr',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-control' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'inbdclr',
            [
                'label' => esc_html__('Border color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .form-control' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'in_pad',
            [
                'label' => esc_html__('Padding', 'the-pack-addon'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'in_bdwd',
            [
                'label' => esc_html__('Border width', 'the-pack-addon' ),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .form-control' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};border-style:solid;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'intyp',
                'label' => esc_html__('Typography', 'the-pack-addon'),
                'selector' => '{{WRAPPER}} .form-control',
            ]
        );

        $this->add_responsive_control(
            'nm_hdz',
            [
                'label' => esc_html__('Submit button', 'the-pack-addon'),
                'type' =>Controls_Manager::HEADING,
                'separator' => 'after',                                                            
            ]
        );

        $this->add_responsive_control(
            'wibt',   
            [
                'label' => esc_html__('Width', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER, 
                'size_units' => ['px', '%'],                   
                'selectors' => [
                    '{{WRAPPER}} .submit' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'brsbt',   
            [
                'label' => esc_html__('Border radius', 'the-pack-addon'), 
                'type' => Controls_Manager::SLIDER, 
                'size_units' => ['px', '%'],                   
                'selectors' => [
                    '{{WRAPPER}} .submit' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tybt',
                'selector' => '{{WRAPPER}} .submit',
                'label' => esc_html__('Typography', 'the-pack-addon'),
            ]
        );

        $this->add_control(
            'tycl',
            [
                'label' => esc_html__('Color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'tycbg',
            [
                'label' => esc_html__('Background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->add_control(
            'tyclh',
            [
                'label' => esc_html__('Hover color', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'tycbgh',
            [
                'label' => esc_html__('Hover background', 'the-pack-addon'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

	}

	public function product_page_script() {
		?>
		<script type="text/javascript">
			jQuery(function($) {
				jQuery('.woocommerce-Reviews').find('#rating')
					.hide()
					.before(
						'<p class="stars">\
								<span>\
									<a class="star-1" href="#">1</a>\
									<a class="star-2" href="#">2</a>\
									<a class="star-3" href="#">3</a>\
									<a class="star-4" href="#">4</a>\
									<a class="star-5" href="#">5</a>\
								</span>\
							</p>'
					);
			});
		</script>
		<?php
	}

    public function the_hooks( $control = null ) {

		if ( ! $control ) {
			return;
		}  
        if ($control['hide_heading']){

            add_filter( 'woocommerce_product_description_heading', '__return_null' );

            add_filter( 'woocommerce_product_additional_information_heading', '__return_null' );

            remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );

        }

        add_filter( 'woocommerce_product_tabs',function( $tabs ) use ( $control ) {
            
                if ( ! $control ) {
                    return;
                }   

                if ($control['des_lbl']){
                    $tabs[ 'description' ][ 'title' ] = $control['des_lbl'];
                } 

                if ($control['adi_lbl']){
                    $tabs[ 'additional_information' ][ 'title' ] = $control['adi_lbl'];
                }

                if ($control['rate_lbl']){
                    $tabs[ 'reviews' ][ 'title' ] = str_replace( 'Reviews', $control['rate_lbl'], $tabs[ 'reviews' ][ 'title' ] );
                }

                $tabs['reviews'][ 'callback' ] = 'tp_woo_comments_template';

                if ($control['hide_desc']){
                    unset( $tabs['description'] );
                }  
                if ($control['hide_info']){
                    unset( $tabs['additional_information'] );
                } 
                if ($control['hide_review']){
                    unset( $tabs['reviews'] );
                }  

                return $tabs;

        } );

	}

    public function footer(){
        $classes[] = 'foog';
        return $classes;
    }
 
	protected function render() {
        $settings = $this->get_settings();
        global $product;
        $this->the_hooks($settings);
        $preview  = isset( $_GET['preview'] ) ? sanitize_text_field(wp_unslash($_GET['preview'])) : '';//phpcs:disable WordPress.Security.NonceVerification.Recommended 
        if (Plugin::instance()->editor->is_edit_mode() | $preview == 'true' ) {
            $this->product_page_script();
            $product = wc_get_product($settings['preview']);
        }
        if (!preg_match("/[^[:alnum:]_\/-]/",$settings['disp'])) {
            include plugin_dir_path(__FILE__) . $settings['disp'] . '.php';
        }        
	} 

}

$widgets_manager->register(new \ThePackAddon\Widgets\Woo_Product_Tabs());
