<?php
namespace ThePackAddon\Widgets;
use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;

class Tp_Woo_Group_Query extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'tp-woo-query';
	}
	
	protected function init_fields() { 

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'meta', [
                'label' =>   esc_html__( 'Category', 'news-element' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => false,
                'options' =>  thepack_drop_cat(),
            ]
        );
        $repeater->add_control(
            'label', [
                'label' =>   esc_html__( 'Category label', 'news-element' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $cus = new \Elementor\Repeater();

        $cus->add_control(
            'meta', [
                'label' =>   esc_html__( 'Category', 'news-element' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'multiple' => false,
                'options' => [
                    'topsell' => __('Top seller', 'news-element'),
                    'featured' => __('Featured', 'news-element'),
                    'sale' => __('Sale', 'news-element'),
                    'endingsale' => __('Sale ending', 'news-element'),
                    'new' => __('New', 'news-element'),
                ], 
            ]
        ); 

        $cus->add_control(
            'label', [
                'label' =>   esc_html__( 'Category label', 'news-element' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

		$controls = [];

        $controls['query_type'] = [
                'label' => esc_html__('Data source', 'the-pack-addon'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    'category' => __('Taxonomy Filter', 'news-element'),
                    'custom' => __('Custom', 'news-element'),
                    'individual' => __('Individual', 'news-element'),
					'category_term' => __('Taxonomy terms', 'news-element'),
					'related' => __('Related products', 'news-element'),
                ],                
		];

        $controls['num'] = [
                'label' => esc_html__('Posts', 'the-pack-addon'),
                'type' => Controls_Manager::SLIDER,              
		];
        $controls['taxonomy'] = [

			'label' => esc_html__('Taxonomy', 'the-pack-addon'),
			'type' => Controls_Manager::SELECT,
			'label_block' => true,
			'options' => thepaack_drop_taxolist(),    
			'condition' => [
				'query_type' => ['category','category_term'],
				//'arc_query!' => 'yes',
			],              
		];

        $controls['terms'] = [

			'type' => Controls_Manager::REPEATER,
			'prevent_empty' => false,
			'fields' => $repeater->get_controls(),
			'title_field' => '{{{ meta }}}',
			'condition' => [
				'query_type' => ['category','category_term'],
				//'arc_query!' => 'yes',
			],             
		];

        $controls['customs'] = [

			'type' => Controls_Manager::REPEATER,
			'prevent_empty' => false,
			'fields' => $cus->get_controls(),
			'title_field' => '{{{ meta }}}',
			'condition' => [
				'query_type' => 'custom',
				//'arc_query!' => 'yes',
			],              
		];
        $controls['id_query'] = [

			'label' => esc_html__('Posts', 'the-pack-addon'),
			'type' => Controls_Manager::SELECT2,
			'options' => thepack_drop_posts('product'),
			'multiple' => true,
			'label_block' => true,
			'condition' => [
				'query_type' => 'individual',
				//'arc_query!' => 'yes',
			],            
		];

		return $controls;
	} 

	protected function prepare_fields( $fields ) {

		array_walk(
			$fields, function( &$field, $field_name ) {

				if ( in_array( $field_name, [ 'tp-woo-control', 'popover_toggle' ] ) ) {
					return;
				}

				$field['condition']['tp-woo-control'] = 'custom';
			}
		);

		return parent::prepare_fields( $fields );
	}

	protected function get_default_options() {
		return [
			'popover' => [
				'starter_name' 	=> 'tp-woo-control',
				'starter_title' => __( 'Query', 'news-element' ),
			],
		];
	}
}

$controls_manager->add_group_control( 'tp-woo-query', new \ThePackAddon\Widgets\Tp_Woo_Group_Query() );
