<?php

namespace ThePackKitExtensions\Elementor\Controls;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Utils;

class Group_Control_Related extends Group_Control_Query {

	public static function get_type() {
		return 'thepackkit-related-query';
	}

	/**
	 * Build the group-controls array
	 * @param string $name
	 *
	 * @return array
	 */
	protected function init_fields_by_name( $name ) {
		$fields = parent::init_fields_by_name( $name );

		$tabs_wrapper = $name . '_query_args';
		$include_wrapper = $name . '_query_include';

		$fields['post_type']['options']['related'] = __( 'Related', 'the-pack-addon'  );
		$fields['include_term_ids']['condition']['post_type!'][] = 'related';
		$fields['related_taxonomies']['condition']['post_type'][] = 'related';
		$fields['include_authors']['condition']['post_type!'][] = 'related';
		$fields['exclude_authors']['condition']['post_type!'][] = 'related';
		$fields['avoid_duplicates']['condition']['post_type!'][] = 'related';
		$fields['offset']['condition']['post_type!'][] = 'related';

		$related_taxonomies = [
			'label' => __( 'Term', 'the-pack-addon'  ),
			'type' => Controls_Manager::SELECT2,
			'options' => $this->get_supported_taxonomies(),
			'label_block' => true,
			'multiple' => true,
			'condition' => [
				'include' => 'terms',
				'post_type' => [
					'related',
				],
			],
			'tabs_wrapper' => $tabs_wrapper,
			'inner_tab' => $include_wrapper,
		];

		$related_fallback = [
			'label' => __( 'Fallback', 'the-pack-addon'  ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'fallback_none' => __( 'None', 'the-pack-addon'  ),
				'fallback_by_id' => __( 'Manual Selection', 'the-pack-addon'  ),
				'fallback_recent' => __( 'Recent Posts', 'the-pack-addon'  ),
			],
			'default' => 'fallback_none',
			'description' => __( 'Displayed if no relevant results are found. Manual selection display order is random', 'the-pack-addon'  ),
			'condition' => [
				'post_type' => 'related',
			],
			'separator' => 'before',
		];

		$fallback_ids = [
			'label' => __( 'Search & Select', 'the-pack-addon'  ),
			'type' => 'thepackkit-query',
			'post_type' => '',
			'options' => [],
			'label_block' => true,
			'multiple' => true,
			'filter_type' => 'by_id',
			'condition' => [
				'post_type' => 'related',
				'related_fallback' => 'fallback_by_id',
			],
			'export' => false,
		];

		$fields = Utils::array_inject( $fields, 'include_term_ids', [ 'related_taxonomies' => $related_taxonomies ] );
		$fields = Utils::array_inject( $fields, 'offset', [ 'related_fallback' => $related_fallback ] );
		$fields = Utils::array_inject( $fields, 'related_fallback', [ 'fallback_ids' => $fallback_ids ] );

		return $fields;
	}

	protected function get_supported_taxonomies() {
		$supported_taxonomies = [];

		$public_types = thepack_addon_kit_helper()->get_post_types();

		foreach ( $public_types as $type => $title ) {
			$taxonomies = get_object_taxonomies( $type, 'objects' );
			foreach ( $taxonomies as $key => $tax ) {
				if ( ! array_key_exists( $key, $supported_taxonomies ) ) {
					$label = $tax->label;
					if ( in_array( $tax->label, $supported_taxonomies ) ) {
						$label = $tax->label . ' (' . $tax->name . ')';
					}
					$supported_taxonomies[ $key ] = $label;
				}
			}
		}

		return $supported_taxonomies;
	}

	protected static function init_presets() {
		parent::init_presets();
		static::$presets['related'] = [
			'related_fallback',
			'fallback_ids',
		];
	}
}
