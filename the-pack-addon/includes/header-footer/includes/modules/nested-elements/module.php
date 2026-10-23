<?php
namespace ThePackKitThemeBuilder\Modules\NestedElements;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Module extends \Elementor\Core\Base\Module {

    public static function is_active() {
        return thepack_kit()->elementor()->experiments->is_feature_active('container');
    }

    public function get_name() {
        return 'nested-elements';
    }

    public function before_enqueue_scripts(){

        wp_enqueue_script( 'tp-nested-carousel-editor', thepack_kit()->plugin_url('includes/modules/nested-elements/assets/js/nested-carousel-editor.min.js'), [
            $this->get_name()
        ], ELEMENTOR_VERSION, true );

    }

    public function frontend_enqueue(){
            wp_enqueue_script( 'lastudio-kit-w__ncarousel', thepack_kit()->plugin_url( 'includes/modules/nested-elements/assets/js/n-carousel.js' ), ['thepack-kit-base'], thepack_kit()->get_version(), true );
            wp_enqueue_style(
                'lastudio-kit-base',
                thepack_kit()->plugin_url('includes/modules/nested-elements/assets/lastudio-kit-base.css'),
                [],
                thepack_kit()->get_version()
            );
    }

    public function __construct()
    {
        parent::__construct();

        add_action( 'elementor/controls/register', function ( $controls_manager ) {
            $controls_manager->register( new Controls\Control_Nested_Repeater() );
        } );

        add_action( 'elementor/widgets/register', function ($widgets_manager){

            $widgets_manager->register( new Widgets\Nested_Carousel() );
        } );

        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'before_enqueue_scripts' ] );

        add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'frontend_enqueue' ), 10 );
    }
}