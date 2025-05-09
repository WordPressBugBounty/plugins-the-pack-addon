<?php
namespace ThePackKitThemeBuilder\Modules\ThemeBuilder;
use Elementor\Core\Admin\Admin_Notices;
use Elementor\Core\App\App;
use Elementor\Core\Base\Document;
use Elementor\TemplateLibrary\Source_Local;
use ThePackKitThemeBuilder\Modules\ThemeBuilder\Classes;
use ThePackKitThemeBuilder\Modules\ThemeBuilder\Documents\Single;
use ThePackKitThemeBuilder\Modules\ThemeBuilder\Documents\Theme_Document;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends \Elementor\Core\Base\Module {

	const ADMIN_LIBRARY_TAB_GROUP = 'theme';

	public static function is_preview() {
	    return thepack_kit()->elementor()->preview->is_preview_mode() || is_preview();
	}

	public static function get_public_post_types( $args = [] ) {
    
	    $post_types = thepack_addon_kit_helper()->get_post_types();

		// Product form WooCommerce are handled separately.
		if ( class_exists( 'woocommerce' ) ) {
			unset( $post_types['product'] );
		}

		return $post_types;
	}

	public function get_name() {
		return 'theme-builder';
	}

	/**
	 * @return Classes\Conditions_Manager
	 */
	public function get_conditions_manager() {
		return $this->get_component( 'conditions' );
	}

	/**
	 * @return Classes\Locations_Manager
	 */
	public function get_locations_manager() {
		return $this->get_component( 'locations' );
	}

	/**
	 * @return Classes\Preview_Manager
	 */
	public function get_preview_manager() {
		return $this->get_component( 'preview' );
	}

	/**
	 * @return Classes\Templates_Types_Manager
	 */
	public function get_types_manager() {
		return $this->get_component( 'templates_types' );
	}

	/**
	 * @param $post_id
	 *
	 * @return Theme_Document
	 */
	public function get_document( $post_id ) {
		$document = null;

		try {
			$document = thepack_kit()->elementor()->documents->get( $post_id );
		} catch ( \Exception $e ) {
			// Do nothing.
			unset( $e );
		}

		if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
			$document = null;
		}

		return $document;
	}

	public function localize_settings( $settings ) {
		$settings = array_replace_recursive( $settings, [
			'i18n' => [
				'publish_settings' => __( 'Publish Settings', 'the-pack-addon'  ),
				'conditions' => __( 'Conditions', 'the-pack-addon'  ),
				'display_conditions' => __( 'Display Conditions', 'the-pack-addon'  ),
				'choose' => __( 'Choose', 'the-pack-addon'  ),
				'add_condition' => __( 'Add Condition', 'the-pack-addon'  ),
				/* Translators: %s condition title. */
				'conditions_title' => __( 'Where Do You Want to Display Your %s?', 'the-pack-addon'  ),
				/* Translators: %s condition description */
				'conditions_description' => __( 'Set the conditions that determine where your %s is used throughout your site.', 'the-pack-addon'  ) . '<br>' . __( 'For example, choose \'Entire Site\' to display the template across your site.', 'the-pack-addon'  ),
				'conditions_publish_screen_description' => __( 'Apply current template to these pages.', 'the-pack-addon'  ),
				'save_and_close' => __( 'Save & Close', 'the-pack-addon'  ),
				'open_site_editor' => __( 'Open Site Editor', 'the-pack-addon'  ),
				'view_live_site' => __( 'View Live Site', 'the-pack-addon'  ),
			],
            'urls' => [
                'modules' => thepack_kit()->plugin_url('/includes/modules/')
            ]
		] );

		return $settings;
	}

	public function document_config( $config, $post_id ) {
		$document = $this->get_document( $post_id );

		if ( ! $document ) {
			return $config;
		}

		$types_manager = $this->get_types_manager();
		$conditions_manager = $this->get_conditions_manager();
		$template_type = $this->get_template_type( $post_id );

		$config = array_replace_recursive( $config, [
			'theme_builder' => [
				'types' => $types_manager->get_types_config(),
				'conditions' => $conditions_manager->get_conditions_config(),
				'template_conditions' => ( new Classes\Template_Conditions() )->get_config(),
				'is_theme_template' => $this->is_theme_template( $post_id ),
				'settings' => [
					'template_type' => $template_type,
					'location' => $document->get_location(),
					'conditions' => $conditions_manager->get_document_conditions( $document ),
				],
			],
		] );

		return $config;
	}

	public function register_controls() {
		$controls_manager = thepack_kit()->elementor()->controls_manager;

		$controls_manager->register( new Classes\Conditions_Repeater() );
	}

	public function create_new_dialog_types( $types ) {
		/**
		 * @var Theme_Document[] $document_types
		 */
		foreach ( $types as $type => $label ) {
			$document_type = thepack_kit()->elementor()->documents->get_document_type( $type );
			$instance = new $document_type();

			if ( $instance instanceof Theme_Document && 'section' !== $type ) {
				$types[ $type ] .= $instance->get_location_label();
			}

			if ( Single::class === $document_type ) {
				unset( $types[ $type ] );
			}
		}

		return $types;
	}

	public function print_location_field() {
		$locations = $this->get_locations_manager()->get_locations( [
			'public' => true,
		] );

		if ( empty( $locations ) ) {
			return;
		}
		?>
		<div id="elementor-new-template__form__location__wrapper" class="elementor-form-field">
			<label for="elementor-new-template__form__location" class="elementor-form-field__label">
				<?php echo esc_attr( 'Select a Location', 'the-pack-addon'  ); ?> 
			</label>
			<div class="elementor-form-field__select__wrapper">
				<select id="elementor-new-template__form__location" class="elementor-form-field__select" name="meta_location">
					<option value="">
						<?php echo esc_attr( 'Select...', 'the-pack-addon'  ); ?>
					</option>
					<?php

					foreach ( $locations as $location => $settings ) {
						echo sprintf( '<option value="%1$s">%2$s</option>', esc_attr($location), esc_attr($settings['label']) );
					}
					?>
				</select>
			</div>
		</div>
		<?php
	}

	public function print_post_type_field() {
		$post_types = self::get_public_post_types( [
			'exclude_from_search' => false,
		] );

		if ( empty( $post_types ) ) {
			return;
		}
		?>
		<div id="elementor-new-template__form__post-type__wrapper" class="elementor-form-field">
			<label for="elementor-new-template__form__post-type" class="elementor-form-field__label">
				<?php echo esc_attr( 'Select Post Type', 'the-pack-addon'  ); ?>
			</label>
			<div class="elementor-form-field__select__wrapper">
				<select id="elementor-new-template__form__post-type" class="elementor-form-field__select" name="<?php echo esc_attr(Single::REMOTE_CATEGORY_META_KEY); ?>">
					<option value="">
						<?php echo esc_attr( 'Select', 'the-pack-addon'  ); ?>...
					</option>
					<?php

					foreach ( $post_types as $post_type => $label ) {
						$doc_type = thepack_kit()->elementor()->documents->get_document_type( $post_type );
						$doc_class = new $doc_type();

						$is_base_page = $doc_class instanceof \Elementor\Core\DocumentTypes\PageBase;

						if ( $is_base_page ) {
							$post_type_object = get_post_type_object( $post_type );
							echo sprintf( '<option value="%1$s">%2$s</option>', esc_attr($post_type), esc_attr($post_type_object->labels->singular_name) );
						}
					}

					//phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
					echo sprintf( '<option value="%1$s">%2$s</option>', 'not_found404', esc_attr( '404 Page', 'the-pack-addon'  ) );

					?>
				</select>
			</div>
		</div>
		<?php
	}

	public function admin_head() {
		$current_screen = get_current_screen();
		if ( $current_screen && in_array( $current_screen->id, [ 'elementor_library', 'edit-elementor_library' ] ) ) {
			// For column type (Supported/Unsupported) & for `print_location_field`.
			$this->get_locations_manager()->register_locations();
		}
	}

	/**
	 * An hack to hide the app menu on before render without remove the app page from system.
	 *
	 * @param $menu
	 *
	 * @return mixed
	 */
	public function hide_admin_app_submenu( $menu ) {
		remove_submenu_page( Source_Local::ADMIN_MENU_SLUG, App::PAGE_ID );

		return $menu;
	}

	public function admin_columns_content( $column_name, $post_id ) {
		if ( 'elementor_library_type' === $column_name ) {
			/** @var Document $document */
			$document = thepack_kit()->elementor()->documents->get( $post_id );

			if ( $document instanceof Theme_Document ) {
				$location_label = $document->get_location_label();

				if ( $location_label ) {
					echo ' - ' . esc_html( $location_label );
				}
			}
		}
	}

	public function get_template_type( $post_id ) {
		return Source_local::get_template_type( $post_id );
	}

	public function is_theme_template( $post_id ) {
		$document = thepack_kit()->elementor()->documents->get( $post_id );

		return $document instanceof Theme_Document;
	}

	public function on_elementor_editor_init() {
		thepack_kit()->elementor()->common->add_template( __DIR__ . '/views/panel-template.php' );
	}

	public function add_finder_items( array $categories ) {
		$categories['create']['items']['theme-template'] = [
			'title' => __( 'Add New Theme Template', 'the-pack-addon'  ),
			'icon' => 'plus-circle-o',
			'url' => $this->get_admin_templates_url() . '#add_new',
			'keywords' => [ 'template', 'theme', 'new', 'create' ],
		];

		return $categories;
	}

	/**
	 * Add New item to admin menu.
	 *
	 * Fired by `admin_menu` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=elementor_library', '', __( 'Theme Builder', 'the-pack-addon'  ), 'publish_posts', $this->get_admin_templates_url( true ) );
	}

	public function print_new_theme_builder_promotion( $views ) {
		/** @var Source_Local $source */
		$source = thepack_kit()->elementor()->templates_manager->get_source( 'local' );

		$current_tab_group = $source->get_current_tab_group();

		if ( self::ADMIN_LIBRARY_TAB_GROUP === $current_tab_group ) {
			/**
			 * @var Admin_Notices $admin_notices
			 */
			$admin_notices = thepack_kit()->elementor()->admin->get_component( 'admin-notices' );

			$admin_notices->print_admin_notice( [
				'title' => __( 'Meet the New Theme Builder: More Intuitive and Visual Than Ever', 'the-pack-addon'  ),
				'description' => __( 'With the new Theme Builder you can visually manage every part of your site intuitively, making the task of designing a complete website that much easier', 'the-pack-addon'  ),
				'button' => [
					'text' => __( 'Try it Now', 'the-pack-addon'  ),
					'class' => 'elementor-button elementor-button-success',
					'url' => thepack_kit()->elementor()->app->get_settings( 'menu_url' ),
				],
			] );
		}

		return $views;
	}

	private function get_admin_templates_url( $relative = false ) {
		$base_url = Source_Local::ADMIN_MENU_SLUG;

		if ( ! $relative ) {
			$base_url = admin_url( $base_url );
		}

		return add_query_arg( 'tabs_group', self::ADMIN_LIBRARY_TAB_GROUP, $base_url );
	}

    /**
     * Enqueue plugin scripts only with elementor scripts
     *
     * @return void
     */
    public function enqueue_editor_scripts() {

        wp_enqueue_style(
            'thepack-kit-editor-conditions',
            thepack_kit()->plugin_url( 'assets/css/thepack-kit-editor-conditions.css' ),
            [],
            thepack_kit()->get_version()
        );

        wp_enqueue_script(
            'thepack-kit-editor-conditions',
            thepack_kit()->plugin_url( 'assets/js/thepack-kit-editor-conditions.min.js' ),
            [
                'backbone-marionette',
                'elementor-common',
                'elementor-editor-modules',
                'elementor-editor-document',
            ],
            thepack_kit()->get_version(),
            true
        );

        $locale_settings = [];

        $locale_settings = apply_filters('thepackkit/admin/localize_settings', $locale_settings);

        \Elementor\Utils::print_js_config(
            'thepack-kit-editor-conditions',
            'ThePackKitProConfig',
            $locale_settings
        );
    }

	public function __construct() {
		parent::__construct();

		require __DIR__ . '/api.php';

		$this->add_component( 'theme_support', new Classes\Theme_Support() );
		$this->add_component( 'conditions', new Classes\Conditions_Manager() );
		$this->add_component( 'templates_types', new Classes\Templates_Types_Manager() );
		$this->add_component( 'preview', new Classes\Preview_Manager() );
		$this->add_component( 'locations', new Classes\Locations_Manager() );

		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

		// Editor
		add_action( 'elementor/editor/init', [ $this, 'on_elementor_editor_init' ] );
		add_filter( 'thepackkit/admin/localize_settings', [ $this, 'localize_settings' ] );
		add_filter( 'elementor/editor/localize_settings', [ $this, 'localize_settings' ] );
		add_filter( 'elementor/document/config', [ $this, 'document_config' ], 10, 2 );
        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		// Admin
		add_action( 'admin_head', [ $this, 'admin_head' ] );
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 22 /* After core promotion menu */ );
		add_filter( 'add_menu_classes', [ $this, 'hide_admin_app_submenu' ], 9 /* Before core submenu fixes */ );
		add_action( 'manage_' . Source_Local::CPT . '_posts_custom_column', [ $this, 'admin_columns_content' ], 10, 2 );
		add_action( 'elementor/template-library/create_new_dialog_fields', [ $this, 'print_location_field' ] );
		//add_action( 'elementor/template-library/create_new_dialog_fields', [ $this, 'print_post_type_field' ] );
		add_filter( 'elementor/template-library/create_new_dialog_types', [ $this, 'create_new_dialog_types' ] );
		add_filter( 'views_edit-' . Source_Local::CPT, [ $this, 'print_new_theme_builder_promotion' ], 9 );

		// Common
		add_filter( 'elementor/finder/categories', [ $this, 'add_finder_items' ] );
	}
}
