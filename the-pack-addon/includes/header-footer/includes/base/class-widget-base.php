<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

abstract class ThePacK_Kit_Base extends Widget_Base
{

    public $_context = 'render';
    public $_processed_item = false;
    public $_processed_index = 0;
    public $_load_level = 100;
    public $_include_controls = [];
    public $_exclude_controls = [];
    public $_new_icon_prefix = 'selected_';

    public $_paged_key = '';

    /**
     * [__construct description]
     * @param array $data [description]
     * @param [type] $args [description]
     */
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        $this->_load_level = 100;

        $widget_name = $this->get_name();

        $this->_include_controls = apply_filters("lastudio-kit/editor/{$widget_name}/include-controls", [], $widget_name, $this);

        $this->_exclude_controls = apply_filters("lastudio-kit/editor/{$widget_name}/exclude-controls", [], $widget_name, $this);

        $this->enqueue_addon_resources();
    }

    protected function enqueue_addon_resources() { }

    public function has_widget_inner_wrapper(): bool {
      return false;
    }
    protected function get_html_wrapper_class()
    {
        return 'lastudio-kit elementor-' . $this->get_name();
    }

    protected function get_widget_title()
    {
        return '';
    }

    protected function get_lakit_name(){
        return str_replace('lakit-', '', $this->get_name());
    }

    public function get_title()
    {
        return 'LaStudioKit ' . $this->get_widget_title();
    }

    public function get_categories()
    {
        return ['lastudiokit'];
    }


    /**
     * Get globaly affected template
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function _get_global_template($name = null)
    {

	    $name = $this->sanitize_file_name($name);

        $widget_name = str_replace(['lakit-', 'lastudio-kit-'], '', $this->get_name());

        $template = call_user_func(array($this, sprintf('_get_%s_template', $this->_context)), $name);

        if (!$template) {
            $template = $this->get_template_output($widget_name . '/global/' . $name . '.php');
        }

        return $template;
    }

    /**
     * Get front-end template
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function _get_render_template($name = null)
    {
	    $widget_name = str_replace(['lakit-', 'lastudio-kit-'], '', $this->get_name());
        return $this->get_template_output($widget_name . '/render/' . $name . '.php');
    }

    /**
     * Get editor template
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function _get_edit_template($name = null)
    {
	    $widget_name = str_replace(['lakit-', 'lastudio-kit-'], '', $this->get_name());
        return $this->get_template_output($widget_name . '/edit/' . $name . '.php');
    }

    /**
     * Load template directly
     * @param $template
     * @access protected
     */
    protected function _load_template($template) {
        if(file_exists($template)){
            include $template;
        }
    }

    /**
     * Get global looped template for settings
     * Required only to process repeater settings.
     *
     * @param string $name Base template name.
     * @param string $setting Repeater setting that provide data for template.
     * @param string $callback Callback for preparing a loop array
     * @return void
     */
    public function _get_global_looped_template($name = null, $setting = null, $callback = null)
    {

        $templates = array(
            'start' => $this->_get_global_template($name . '-loop-start'),
            'loop' => $this->_get_global_template($name . '-loop-item'),
            'end' => $this->_get_global_template($name . '-loop-end'),
        );

        call_user_func(
            array($this, sprintf('_get_%s_looped_template', $this->_context)), $templates, $setting, $callback
        );

    }

    /**
     * Get render mode looped template
     *
     * @param array $templates [description]
     * @param string $setting [description]
     * @param string $callback Callback for preparing a loop array
     * @return void
     */
    public function _get_render_looped_template($templates = array(), $setting = null, $callback = null)
    {

        $loop = $this->get_settings_for_display($setting);
        $loop = apply_filters('lastudio-kit/widget/loop-items', $loop, $setting, $this);

        if (empty($loop)) {
            return;
        }

        if ($callback && is_callable($callback)) {
            $loop = call_user_func($callback, $loop);
        }

        if (!empty($templates['start'])) {
            include $templates['start'];
        }

        foreach ($loop as $item) {

            $this->_processed_item = $item;

            if (!empty($templates['loop'])) {
                include $templates['loop'];
            }
            $this->_processed_index++;
        }

        $this->_processed_item = false;
        $this->_processed_index = 0;

        if (!empty($templates['end'])) {
            include $templates['end'];
        }

    }

    /**
     * Get edit mode looped template
     *
     * @param array $templates [description]
     * @param  [type] $setting   [description]
     * @return [type]            [description]
     */
    public function _get_edit_looped_template($templates = array(), $setting = null)
    {
        ?>
      <# if ( settings.<?php echo esc_attr($setting); ?> ) { #>
        <?php
        if (!empty($templates['start'])) {
            include $templates['start'];
        }
        ?>
      <# _.each( settings.<?php echo esc_attr($setting); ?>, function( item ) { #>
        <?php
        if (!empty($templates['loop'])) {
            include $templates['loop'];
        }
        ?>
      <# } ); #>
        <?php
        if (!empty($templates['end'])) {
            include $templates['end'];
        }
        ?>
      <# } #>
        <?php
    }

    /**
     * Get current looped item dependends from context.
     *
     * @param string $key Key to get from processed item
     * @return mixed
     */
    public function _loop_item($keys = array(), $format = '%s')
    {

        return call_user_func(array($this, sprintf('_%s_loop_item', $this->_context)), $keys, $format);

    }

    /**
     * Loop edit item
     *
     * @param  [type]  $keys       [description]
     * @param string $format [description]
     * @param boolean $nested_key [description]
     * @return [type]              [description]
     */
    public function _edit_loop_item($keys = array(), $format = '%s')
    {

        $settings = $keys[0];

        if (isset($keys[1])) {
            $settings .= '.' . $keys[1];
        }

        ob_start();

        echo '<# if ( item.' . esc_attr($settings) . ' ) { #>';
        printf($format, '{{{ item.' . esc_attr($settings) . ' }}}'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<# } #>';

        return ob_get_clean();
    }

    /**
     * Loop render item
     *
     * @param string $format [description]
     * @param  [type]  $key        [description]
     * @param boolean $nested_key [description]
     * @return [type]              [description]
     */
    public function _render_loop_item($keys = array(), $format = '%s')
    {

        $item = $this->_processed_item;

        $key = $keys[0];
        $nested_key = $keys[1] ?? false;

        if (empty($item) || !isset($item[$key])) {
            return false;
        }

        if (false === $nested_key || !is_array($item[$key])) {
            $value = $item[$key];
        } else {
            $value = $item[$key][$nested_key] ?? false;
        }

        if (!empty($value)) {
            return sprintf($format, $value);
        }
        return false;
    }

    /**
     * Include global template if any of passed settings is defined
     *
     * @param  [type] $name     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function _glob_inc_if($name = null, $settings = array())
    {

        $template = $this->_get_global_template($name);

        call_user_func(array($this, sprintf('_%s_inc_if', $this->_context)), $template, $settings);

    }

    /**
     * Include render template if any of passed setting is not empty
     *
     * @param  [type] $file     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function _render_inc_if($file = null, $settings = array())
    {

        foreach ($settings as $setting) {
            $val = $this->get_settings_for_display($setting);

            if (!empty($val)) {
                include $file;
                return;
            }

        }

    }

    /**
     * Include render template if any of passed setting is not empty
     *
     * @param  [type] $file     [description]
     * @param  [type] $settings [description]
     * @return [type]           [description]
     */
    public function _edit_inc_if($file = null, $settings = array())
    {

        $condition = null;
        $sep = null;

        foreach ($settings as $setting) {
            $condition .= $sep . 'settings.' . $setting;
            $sep = ' || ';
        }

        ?>

      <# if ( <?php echo esc_attr($condition); ?> ) { #>

        <?php include $file; ?>

      <# } #>

        <?php
    }

    /**
     * Open standard wrapper
     *
     * @return void
     */
    public function _open_wrap()
    {

    }

    /**
     * Close standard wrapper
     *
     * @return void
     */
    public function _close_wrap()
    {

    }

    /**
     * Print HTML markup if passed setting not empty.
     *
     * @param string $setting Passed setting.
     * @param string $format Required markup.
     * @param array $args Additional variables to pass into format string.
     * @param bool $echo Echo or return.
     * @return string|void
     */
    public function _html($setting = null, $format = '%s')
    {

        call_user_func(array($this, sprintf('_%s_html', $this->_context)), $setting, $format);

    }

    /**
     * Returns HTML markup if passed setting not empty.
     *
     * @param string $setting Passed setting.
     * @param string $format Required markup.
     * @param array $args Additional variables to pass into format string.
     * @param bool $echo Echo or return.
     * @return string|void
     */
    public function _get_html($setting = null, $format = '%s')
    {

        ob_start();
        $this->_html($setting, $format);
        return ob_get_clean();

    }

    /**
     * Print HTML template
     *
     * @param  [type] $setting [description]
     * @param  [type] $format  [description]
     * @return [type]          [description]
     */
    public function _render_html($setting = null, $format = '%s')
    {

        if (is_array($setting)) {
            $key = $setting[1];
            $setting = $setting[0];
        }

        $val = $this->get_settings_for_display($setting);

        if (!is_array($val) && '0' === $val) {
            printf($format, $val); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        if (is_array($val) && empty($val[$key])) {
            return '';
        }

        if (!is_array($val) && empty($val)) {
            return '';
        }

        if (is_array($val)) {
            printf($format, $val[$key]); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        else {
            printf($format, $val); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

    }

    /**
     * Print underscore template
     *
     * @param  [type] $setting [description]
     * @param  [type] $format  [description]
     * @return [type]          [description]
     */
    public function _edit_html($setting = null, $format = '%s')
    {

        if (is_array($setting)) {
            $setting = $setting[0] . '.' . $setting[1];
        }

        echo '<# if ( settings.' . esc_attr($setting) . ' ) { #>';
        printf($format, '{{{ settings.' . esc_attr($setting) . ' }}}'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<# } #>';
    }

    /**
     * Add icon control
     *
     * @param string $id
     * @param array $args
     * @param object $instance
     */
    public function _add_advanced_icon_control($id, array $args = array(), $instance = null)
    {

        if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '2.6.0', '>=')) {

            $_id = $id; // old control id
            $id = $this->_new_icon_prefix . $id;

            $args['type'] = Controls_Manager::ICONS;
            $args['fa4compatibility'] = $_id;

            unset($args['file']);
            unset($args['default']);

            if (isset($args['fa5_default'])) {
                $args['default'] = $args['fa5_default'];

                unset($args['fa5_default']);
            }
        } else {
            $args['type'] = Controls_Manager::ICON;
            unset($args['fa5_default']);
        }

        if (null !== $instance) {
            $instance->add_control($id, $args);
        } else {
            $this->add_control($id, $args);
        }
    }

    /**
     * Prepare icon control ID for condition.
     *
     * @param string $id Old icon control ID.
     * @return string
     */
    public function _prepare_icon_id_for_condition($id)
    {

        if (defined('ELEMENTOR_VERSION') && version_compare(ELEMENTOR_VERSION, '2.6.0', '>=')) {
            return $this->_new_icon_prefix . $id . '[value]';
        }

        return $id;
    }

    /**
     * Print HTML icon markup
     *
     * @param array $setting
     * @param string $format
     * @param string $icon_class
     * @return void
     */
    public function _icon($setting = null, $format = '%s', $icon_class = '')
    {
        call_user_func(array($this, sprintf('_%s_icon', $this->_context)), $setting, $format, $icon_class);
    }

    /**
     * Returns HTML icon markup
     *
     * @param array $setting
     * @param string $format
     * @param string $icon_class
     * @return string
     */
    public function _get_icon($setting = null, $format = '%s', $icon_class = '')
    {
        return $this->_render_icon($setting, $format, $icon_class, false);
    }

    /**
     * Print HTML icon template
     *
     * @param array $setting
     * @param string $format
     * @param string $icon_class
     * @param bool $echo
     *
     * @return void|string
     */
    public function _render_icon($setting = null, $format = '%s', $icon_class = '', $echo = true)
    {

        if (false === $this->_processed_item) {
            $settings = $this->get_settings_for_display();
        } else {
            $settings = $this->_processed_item;
        }

        $new_setting = $this->_new_icon_prefix . $setting;

        $migrated = isset($settings['__fa4_migrated'][$new_setting]);
        $is_new = empty($settings[$setting]) && Icons_Manager::is_migration_allowed();

        $icon_html = '';

        if ($is_new || $migrated) {

            $attr = array('aria-hidden' => 'true');

            if (!empty($icon_class)) {
                $attr['class'] = $icon_class;
            }

            if (isset($settings[$new_setting])) {
                $icon_html = self::try_get_icon_html($settings[$new_setting], $attr);
            }

        } else if (!empty($settings[$setting])) {

            if (empty($icon_class)) {
                $icon_class = $settings[$setting];
            } else {
                $icon_class .= ' ' . $settings[$setting];
            }

            $icon_html = sprintf('<i class="%s" aria-hidden="true"></i>', $icon_class);
        }

        if (empty($icon_html)) {
            return;
        }

        if (!$echo) {
            return sprintf($format, $icon_html);
        }

        printf($format, $icon_html); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * [__add_control description]
     * @param boolean $control_id [description]
     * @param array $control_args [description]
     * @param integer $load_level [description]
     * @return [type]                [description]
     */
    public function _add_control($control_id = false, $control_args = [], $load_level = 100)
    {

        if (
            ($this->_load_level < $load_level
                || 0 === $this->_load_level
                || in_array($control_id, $this->_exclude_controls)
            ) && !in_array($control_id, $this->_include_controls)
        ) {
            return false;
        }

        $this->add_control($control_id, $control_args);
    }

    /**
     * [__add_responsive_control description]
     * @param boolean $control_id [description]
     * @param array $control_args [description]
     * @param integer $load_level [description]
     * @return [type]                [description]
     */
    public function _add_responsive_control($control_id = false, $control_args = [], $load_level = 100)
    {

        if (
            ($this->_load_level < $load_level
                || 0 === $this->_load_level
                || in_array($control_id, $this->_exclude_controls)
            ) && !in_array($control_id, $this->_include_controls)
        ) {
            return false;
        }

        $this->add_responsive_control($control_id, $control_args);
    }

    /**
     * [__add_group_control description]
     * @param boolean $group_control_type [description]
     * @param array $group_control_args [description]
     * @param integer $load_level [description]
     * @return [type]                      [description]
     */
    public function _add_group_control($group_control_type = false, $group_control_args = [], $load_level = 100)
    {

        if (
            ($this->_load_level < $load_level
                || 0 === $this->_load_level
                || in_array($group_control_args['name'], $this->_exclude_controls)
            ) && !in_array($group_control_args['name'], $this->_include_controls)
        ) {
            return false;
        }

        $this->add_group_control($group_control_type, $group_control_args);
    }

    /**
     * [__add_icon_control description]
     * @param  [type] $id   [description]
     * @param array $args [description]
     * @return [type]       [description]
     */
    public function _add_icon_control($id, array $args = array(), $load_level = 100)
    {

        if (
            ($this->_load_level < $load_level
                || 0 === $this->_load_level
                || in_array($id, $this->_exclude_controls)
            ) && !in_array($id, $this->_include_controls)
        ) {
            return false;
        }

        $this->_add_advanced_icon_control($id, $args);
    }

    /**
     * [__start_controls_section description]
     * @param boolean $controls_section_id [description]
     * @param array $controls_section_args [description]
     * @param integer $load_level [description]
     * @return [type]                         [description]
     */
    public function _start_controls_section($controls_section_id = false, $controls_section_args = [], $load_level = 25)
    {
        if (!$controls_section_id || $this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->start_controls_section($controls_section_id, $controls_section_args);
    }

    /**
     * [__end_controls_section description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _end_controls_section($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->end_controls_section();
    }

    /**
     * [__start_controls_tabs description]
     * @param boolean $tabs_id [description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _start_controls_tabs($tabs_id = false, $tab_args = [], $load_level = 25)
    {

        if (!$tabs_id || $this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->start_controls_tabs($tabs_id, $tab_args);
    }

    /**
     * [__end_controls_tabs description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _end_controls_tabs($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->end_controls_tabs();
    }

    /**
     * [__start_controls_tab description]
     * @param boolean $tab_id [description]
     * @param array $tab_args [description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _start_controls_tab($tab_id = false, $tab_args = [], $load_level = 25)
    {

        if (!$tab_id || $this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->start_controls_tab($tab_id, $tab_args);
    }

    /**
     * [__end_controls_tab description]
     * @param integer $load_level [description]
     * @return [type]              [description]
     */
    public function _end_controls_tab($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->end_controls_tab();
    }

    /**
     * Start popover
     *
     * @param int $load_level
     * @return void|bool
     */
    public function _start_popover($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->start_popover();
    }

    /**
     * End popover
     *
     * @param int $load_level
     * @return void|bool
     */
    public function _end_popover($load_level = 25)
    {

        if ($this->_load_level < $load_level || 0 === $this->_load_level) {
            return false;
        }

        $this->end_popover();
    }

    public function _get_icon_setting($setting = null, $format = '%s', $icon_class = '', $echo = false)
    {
        $icon_html = '';

        $attr = array('aria-hidden' => 'true');

        if (!empty($icon_class)) {
            $attr['class'] = $icon_class;
        }

        if (!empty($setting)) {
            $icon_html = self::try_get_icon_html($setting, $attr);
        }

        if (empty($icon_html)) {
            return '';
        }

        if (!$echo) {
            return sprintf($format, $icon_html);
        }

        printf($format, $icon_html); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

    public function _add_link_attributes($element, array $url_control, $overwrite = false)
    {
        if (method_exists($this, 'add_link_attributes')) {
            return $this->add_link_attributes($element, $url_control, $overwrite);
        }

        $attributes = array();

        if (!empty($url_control['url'])) {
            $attributes['href'] = esc_url($url_control['url']);
        }

        if (!empty($url_control['is_external'])) {
            $attributes['target'] = '_blank';
        }

        if (!empty($url_control['nofollow'])) {
            $attributes['rel'] = 'nofollow';
        }
        if ( ! empty( $url_control['custom_attributes'] ) ) {
            // Custom URL attributes should come as a string of comma-delimited key|value pairs
            $attributes = array_merge( $attributes, Utils::parse_custom_attributes( $url_control['custom_attributes'] ) );
        }

        if ($attributes) {
            $this->add_render_attribute($element, $attributes, $overwrite);
        }

        return $this;
    }

    protected function register_carousel_section($carousel_condition = [], $carousel_columns = false, $enable_carousel = true)
    {
        $this->_start_controls_section(
            'carousel_section',
            array(
                'label' => esc_html__('Carousel Settings', 'lastudio-kit'),
                'condition' => $carousel_condition
            )
        );

        if ($enable_carousel) {
            $this->_add_control(
                'enable_carousel',
                array(
                    'label' => esc_html__('Enable Carousel', 'lastudio-kit'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'lastudio-kit'),
                    'label_off' => esc_html__('No', 'lastudio-kit'),
                    'return_value' => 'yes',
                    'default' => '',
                )
            );
        }
        else{
            $this->_add_control(
                'enable_carousel',
                [
                    'type' => Controls_Manager::HIDDEN,
                    'default' => 'yes',
                ]
            );
        }

        $this->_add_responsive_control(
            'carousel_direction',
            array(
                'label' => esc_html__('Carousel Type', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'lastudio-kit'),
                    'vertical' => esc_html__('Vertical', 'lastudio-kit'),
                ],
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

        $this->_add_responsive_control(
            'carousel_height',
            array(
                'label' => esc_html__('Carousel Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('%', 'px', 'em', 'vw', 'vh'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .swiper-vertical' => 'height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'carousel_direction' => 'vertical',
                    'enable_carousel' => 'yes'
                ),
            )
        );

        if ($carousel_columns === false) {
            $column_dependency = 'carousel_columns!';
            $this->_add_responsive_control( 
                'carousel_columns',
                array(
                    'label' => esc_html__('Slides to Show', 'lastudio-kit'),
                    'type' => Controls_Manager::SELECT,    
                    'default' => '1',
                    'options' => thepack_addon_kit_helper()->get_select_range(10),
                    'condition' => [
                        'enable_carousel' => 'yes'
                    ]
                )
            );
        } else {
            $column_dependency = "{$carousel_columns}!";
        }

        $this->_add_control(
            'enable_swiper_item_auto_width',
            array(
                'label' => esc_html__('Auto item width?', 'lastudio-kit'),
                'description' => esc_html__('This option may not work in some cases', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );
        $this->_add_responsive_control('swiper_item_align_self', [
            'label' => esc_html__('Align Items', 'elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'elementor'),
                    'icon' => 'eicon-flex eicon-align-start-v',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'elementor'),
                    'icon' => 'eicon-flex eicon-align-center-v',
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'elementor'),
                    'icon' => 'eicon-flex eicon-align-end-v',
                ],
                'stretch' => [
                    'title' => esc_html__('Stretch', 'elementor'),
                    'icon' => 'eicon-flex eicon-align-stretch-v',
                ],
            ],
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .lakit-carousel .swiper-slide' => 'align-self: {{VALUE}};',
            ],
            'condition' => [
                'enable_carousel' => 'yes'
            ]
        ]);
        $this->_add_responsive_control(
            'swiper_item__normal_width',
            array(
                'label' => esc_html__('Item Normal Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'custom'),
                'condition' => array(
                    'enable_swiper_item_auto_width' => 'yes',
                    'enable_carousel' => 'yes'
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-carousel-item-normal-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'swiper_item__active_width',
            array(
                'label' => esc_html__('Item Active Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'custom'),
                'condition' => array(
                    'enable_swiper_item_auto_width' => 'yes',
                    'enable_carousel' => 'yes'
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-carousel-item-active-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_to_scroll',
            array(
                'label' => esc_html__('Slides to Scroll', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => thepack_addon_kit_helper()->get_select_range(20),
                'condition' => array(
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_rows',
            array(
                'label' => esc_html__('Rows', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => thepack_addon_kit_helper()->get_select_range(6),
                'condition' => array(
                    'carousel_direction' => 'horizontal',
                    'enable_carousel' => 'yes'
                ),
            )
        );

	    $this->_add_control(
		    'carousel_overflow',
		    array(
			    'label' => esc_html__('Show Hidden Items', 'lastudio-kit'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__('Yes', 'lastudio-kit'),
			    'label_off' => esc_html__('No', 'lastudio-kit'),
			    'return_value' => 'yes',
			    'default' => '',
                'prefix_class' => 'carousel-overflow-',
			    'selectors' => array(
				    '{{WRAPPER}} .swiper-container' => 'overflow: inherit',
			    ),
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
		    )
	    );

        $this->_add_responsive_control(
		    'carousel_overflow_pos',
		    array(
			    'label' => esc_html__('Visible', 'lastudio-kit'),
			    'type' => Controls_Manager::SELECT,
                'options' => [
                    'both' => esc_html__('Both', 'lastudio-kit'),
                    'left' => esc_html__('Left', 'lastudio-kit'),
                    'right' => esc_html__('Right', 'lastudio-kit'),
                    'none' => esc_html__('None', 'lastudio-kit'),
                ],
                'condition' => [
                    'enable_carousel' => 'yes',
                    'carousel_overflow' => 'yes',
                    'carousel_direction' => 'horizontal'
                ],
                'selectors' => [
                    '{{WRAPPER}} .lakit-carousel-inner.sw--horizontal' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'both'  => 'clip-path: inset(-50% -200% -50% -200%)',
                    'left'  => is_rtl() ? 'clip-path: inset(-50% -200% -50% calc(-1 * var(--lakit-carousel-item-right-space, 0px)))' : 'clip-path: inset(-50% calc(-1 * var(--lakit-carousel-item-right-space, 0px)) -50% -200%)',
                    'right' => is_rtl() ? 'clip-path: inset(-50% calc(-1 * var(--lakit-carousel-item-left-space, 0px)) -50% -200%)' : 'clip-path: inset(-50% -200% -50% calc(-1 * var(--lakit-carousel-item-left-space, 0px)))',
                    'none'  => 'clip-path: inset(-50% calc(-1 * var(--lakit-carousel-item-right-space, 0px)) -50% calc(-1 * var(--lakit-carousel-item-left-space, 0px)))',
                ]
		    )
	    );

        $this->_add_control(
            'carousel_arrows',
            array(
                'label' => esc_html__('Show Arrows Navigation', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

        $this->_add_icon_control(
            'carousel_prev_arrow',
            array(
                'label' => esc_html__('Prev Arrow Icon', 'lastudio-kit'),
                'label_block' => true,
                'default' => 'lastudioicon-arrow-left',
                'fa5_default' => array(
                    'value' => 'lastudioicon-arrow-left',
                    'library' => 'lastudioicon',
                ),
                'condition' => array(
                    'carousel_arrows' => 'true',
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_icon_control(
            'carousel_next_arrow',
            array(
                'label' => esc_html__('Next Arrow Icon', 'lastudio-kit'),
                'label_block' => true,
                'default' => 'lastudioicon-arrow-right',
                'fa5_default' => array(
                    'value' => 'lastudioicon-arrow-right',
                    'library' => 'lastudioicon'
                ),
                'condition' => array(
                    'carousel_arrows' => 'true',
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_dots',
            array(
                'label' => esc_html__('Show Dots Pagination', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'carousel_dot_type',
            array(
                'label' => esc_html__('Dots Pagination Type', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bullets',
                'options' => [
                    'bullets' => esc_html__('Bullets', 'lastudio-kit'),
                    'fraction' => esc_html__('Fraction', 'lastudio-kit'),
                    'progressbar' => esc_html__('Progressbar', 'lastudio-kit'),
                    'custom' => esc_html__('Custom', 'lastudio-kit'),
                ],
                'condition' => array(
                    'carousel_dots' => 'true',
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_autoplay',
            array(
                'label' => esc_html__('Autoplay', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'carousel_scrollbar',
            array(
                'label' => esc_html__('Scrollbar', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'carousel_pause_on_hover',
            array(
                'label' => esc_html__('Pause on Hover', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
                'condition' => array(
                    'carousel_autoplay' => 'true',
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_pause_on_interaction',
            array(
                'label' => esc_html__('Disable on Interaction', 'lastudio-kit'),
                'description' => esc_html__('Set to no and autoplay will not be disabled after user interactions (swipes), it will be restarted every time after interaction', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
                'condition' => array(
                    'carousel_autoplay' => 'true',
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_reverse_direction',
            array(
                'label' => esc_html__('Reverse Direction', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => '',
                'condition' => array(
                    'carousel_autoplay' => 'true',
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_autoplay_speed',
            array(
                'label' => esc_html__('Autoplay Speed', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => array(
                    'carousel_autoplay' => 'true',
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_loop',
            array(
                'label' => esc_html__('Infinite Loop', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => 'true',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );


        $this->_add_control(
            'carousel_autoheight',
            array(
                'label' => esc_html__('Item AutoHeight', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'true',
                'default' => 'false',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'carousel_equality',
            array(
                'label' => esc_html__('Item Equality', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'lakit-carousel-equalheight',
                'default' => '',
                'prefix_class' => '',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

        $this->_add_control(
            'carousel_effect',
            array(
                'label' => esc_html__('Effect', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__('The `Fade` option does not work if `slides to show` option is not `1`', 'lastudio-kit'),
                'default' => 'slide',
                'options' => array(
                    'slide' => esc_html__('Slide', 'lastudio-kit'),
                    'fade' => esc_html__('Fade', 'lastudio-kit'),
                    'cube' => esc_html__('Cube', 'lastudio-kit'),
                    'coverflow' => esc_html__('Coverflow', 'lastudio-kit'),
                    'flip' => esc_html__('Flip', 'lastudio-kit'),
                    'cards' => esc_html__('Cards', 'lastudio-kit'),
                    'creative' => esc_html__('Creative', 'lastudio-kit'),
                ),
//                'prefix_class' => 'lakit-carousel-item-effect-',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

        $coverflow_conditions = [
            'carousel_effect' => 'coverflow',
            'enable_carousel' => 'yes'
        ];
        if ($enable_carousel) {
            $coverflow_conditions['enable_carousel'] = 'yes';
        }

        $this->_add_control(
            'carousel_coverflow__depth',
            array(
                'label' => esc_html__('Coverflow Deep', 'lastudio-kit'),
                'description' => esc_html__('Depth offset in px (slides translate in Z axis)', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'before',
                'min' => -1000,
                'max' => 500,
                'step' => 1,
                'default' => 100,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_coverflow__modifier',
            array(
                'label' => esc_html__('Coverflow Modifier', 'lastudio-kit'),
                'description' => esc_html__('Effect multiplier', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 10,
                'step' => 0.1,
                'default' => 1,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_coverflow__stretch',
            array(
                'label' => esc_html__('Coverflow Stretch', 'lastudio-kit'),
                'description' => esc_html__('Stretch space between slides (in px)', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 500,
                'step' => 1,
                'default' => 100,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_coverflow__rotate',
            array(
                'label' => esc_html__('Coverflow Rotate', 'lastudio-kit'),
                'description' => esc_html__('Slide rotate in degrees', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'min' => -360,
                'max' => 360,
                'step' => 1,
                'default' => 0,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_coverflow__scale',
            array(
                'label' => esc_html__('Coverflow Scale', 'lastudio-kit'),
                'description' => esc_html__('Slide scale effect', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'after',
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'default' => 1,
                'condition' => $coverflow_conditions,
            )
        );

        $this->_add_control(
            'carousel_creative_translate',
            array(
                'label' => esc_html__('Creative Effect Transform', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 200,
                'step' => 1,
                'default' => 100,
                'condition' => [
                    'carousel_effect' => 'creative',
                ],
            )
        );

        $this->_add_control(
            'carousel_disable_content_effect',
            array(
                'label' => esc_html__('Disable Content Effect', 'lastudio-kit'),
                'description' => esc_html__('If available', 'lastudio-kit'),
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'slide-no-animation',
                'prefix_class' => '',
                'condition' => [
                    'enable_carousel' => 'yes'
                ]
            )
        );

	    $this->_add_control(
		    'carousel_enable_linear_effect',
		    array(
			    'label' => esc_html__('Enable Linear Effect', 'lastudio-kit'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__('Yes', 'lastudio-kit'),
			    'label_off' => esc_html__('No', 'lastudio-kit'),
			    'return_value' => 'true',
			    'default' => '',
			    'condition' => array(
				    'carousel_autoplay' => 'true',
				    'carousel_effect' => 'slide',
                    'enable_carousel' => 'yes'
			    ),
		    )
	    );

        $this->_add_responsive_control(
            'carousel_item_width',
            array(
                'label' => esc_html__('Custom Item Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 100,
                    'unit' => '%',
                ),
                'size_units' => ['px', 'em', 'vw', 'vh', '%'],
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'carousel_effect' => ['flip', 'cube', 'coverflow'],
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_speed',
            array(
                'label' => esc_html__('Animation Speed', 'lastudio-kit'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'condition' => array(
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_center_mode',
            array(
                'label' => esc_html__('Center Mode', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => array(
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_padding_left',
            array(
                'label' => esc_html__('Padding Left', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('%', 'px', 'em', 'vw', 'vh', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}}' => '--lakit-carousel-padding-left: {{SIZE}}{{UNIT}};',
                    'body.rtl {{WRAPPER}}' => '--lakit-carousel-padding-right: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_padding_right',
            array(
                'label' => esc_html__('Padding Right', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('%', 'px', 'em', 'vw', 'vh', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}}' => '--lakit-carousel-padding-right: {{SIZE}}{{UNIT}}',
                    'body.rtl {{WRAPPER}}' => '--lakit-carousel-padding-left: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_add_control(
            'carousel_id',
            array(
                'label' => esc_html__('Carousel UniqueID', 'lastudio-kit'),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'label_block' => true,
                'condition' => array(
                    'enable_carousel' => 'yes'
                ),
            )
        );
	    $this->_add_control(
		    'carousel_as_for',
		    array(
			    'label' => esc_html__('Carousel Controller', 'lastudio-kit'),
			    'label_block' => true,
			    'description' => sprintf('%1$s <a href="%3$s" target="_blank">%2$s</a>', esc_html__('Enter Carousel ID.', 'lastudio-kit'), esc_html__('Learn more.', 'lastudio-kit'), 'https://swiperjs.com/swiper-api#controller'),
			    'type' => Controls_Manager::TEXT,
                'condition' => array(
                    'enable_carousel' => 'yes'
                ),
		    )
	    );
        $this->_add_control(
            'carousel_thumbs',
            array(
                'label' => esc_html__('Carousel Thumbs', 'lastudio-kit'),
                'label_block' => true,
                'description' => sprintf('%1$s <a href="%3$s" target="_blank">%2$s</a>', esc_html__('Enter Carousel ID.', 'lastudio-kit'), esc_html__('Learn more.', 'lastudio-kit'), 'https://swiperjs.com/swiper-api#thumbs'),
                'type' => Controls_Manager::TEXT,
                'condition' => array(
                    'enable_carousel' => 'yes'
                ),
            )
        );

        $this->_end_controls_section();
    }

    protected function register_carousel_arrows_style_section($carousel_condition = []){
        /**
         * Arrow Sections
         */
        $this->_start_controls_section(
            'carousel_arrow_style_section',
            array(
                'label' => esc_html__('Carousel Arrows', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => $carousel_condition
            )
        );

        $this->add_responsive_control(
            'carousel_arrow_display',
            [
                'label' => esc_html__( 'Display', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'yes' => [
                        'title' => esc_html__( 'Yes', 'lastudio-kit' ),
                        'icon' => 'eicon-check',
                    ],
                    'no' => [
                        'title' => esc_html__( 'No', 'lastudio-kit' ),
                        'icon' => 'eicon-ban',
                    ],
                ],
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'yes'       => '--lakit-carousel-arrow-o: 1; --lakit-carousel-arrow-v: inherit; --lakit-carousel-arrow-d: flex',
                    'no'        => '--lakit-carousel-arrow-o: 0; --lakit-carousel-arrow-v: hidden; --lakit-carousel-arrow-d: none',
                ]
            ]
        );

        $this->_add_control(
            'carousel_arrow_show_on_hover',
            array(
                'label' => esc_html__('Display on hover', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'yes',
                'default' => 'no',
                'prefix_class' => 'lakit-arrow--showonhover-',
            )
        );

        $this->_start_controls_tabs('carousel_arrow_tabs');

        $this->_start_controls_tab(
            'carousel_tab_arrow_normal',
            array(
                'label' => esc_html__('Normal', 'lastudio-kit'),
            )
        );

        // $this->_add_group_control(
        //     Group_Control_Box_Style::get_type(),
        //     array(
        //         'name' => 'carousel_arrow_normal_style',
        //         'label' => esc_html__('Arrows Style', 'lastudio-kit'),
        //         'selector' => '{{WRAPPER}} .lakit-carousel .lakit-arrow'
        //     )
        // );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'carousel_tab_arrow_hover',
            array(
                'label' => esc_html__('Hover', 'lastudio-kit'),
            )
        );

        // $this->_add_group_control(
        //     Group_Control_Box_Style::get_type(),
        //     array(
        //         'name' => 'carousel_arrow_hover_style',
        //         'label' => esc_html__('Arrows Style', 'lastudio-kit'),
        //         'selector' => '{{WRAPPER}} .lakit-carousel .lakit-arrow:hover'
        //     )
        // );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'carousel_prev_arrow_position',
            array(
                'label' => esc_html__('Prev Arrow Position', 'lastudio-kit'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->_add_control(
            'carousel_prev_arrow_v_position_by',
            array(
                'label' => esc_html__('Vertical Position by', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top' => esc_html__('Top', 'lastudio-kit'),
                    'bottom' => esc_html__('Bottom', 'lastudio-kit'),
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_prev_arrow_top_position',
            array(
                'label' => esc_html__('Top Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_prev_arrow_v_position_by' => 'top',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_prev_arrow_bottom_position',
            array(
                'label' => esc_html__('Bottom Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_prev_arrow_v_position_by' => 'bottom',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->_add_control(
            'carousel_prev_arrow_h_position_by',
            array(
                'label' => esc_html__('Horizontal Position by', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left' => esc_html__('Left', 'lastudio-kit'),
                    'right' => esc_html__('Right', 'lastudio-kit'),
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_prev_arrow_left_position',
            array(
                'label' => esc_html__('Left Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_prev_arrow_h_position_by' => 'left',
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                    'body.rtl {{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_prev_arrow_right_position',
            array(
                'label' => esc_html__('Right Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_prev_arrow_h_position_by' => 'right',
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                    'body.rtl {{WRAPPER}} .lakit-carousel .lakit-arrow.prev-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->_add_control(
            'carousel_next_arrow_position',
            array(
                'label' => esc_html__('Next Arrow Position', 'lastudio-kit'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->_add_control(
            'carousel_next_arrow_v_position_by',
            array(
                'label' => esc_html__('Vertical Position by', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top',
                'options' => array(
                    'top' => esc_html__('Top', 'lastudio-kit'),
                    'bottom' => esc_html__('Bottom', 'lastudio-kit'),
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_next_arrow_top_position',
            array(
                'label' => esc_html__('Top Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_next_arrow_v_position_by' => 'top',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'top: {{SIZE}}{{UNIT}}; bottom: auto;',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_next_arrow_bottom_position',
            array(
                'label' => esc_html__('Bottom Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_next_arrow_v_position_by' => 'bottom',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'bottom: {{SIZE}}{{UNIT}}; top: auto;',
                ),
            )
        );

        $this->_add_control(
            'carousel_next_arrow_h_position_by',
            array(
                'label' => esc_html__('Horizontal Position by', 'lastudio-kit'),
                'type' => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => array(
                    'left' => esc_html__('Left', 'lastudio-kit'),
                    'right' => esc_html__('Right', 'lastudio-kit'),
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_next_arrow_left_position',
            array(
                'label' => esc_html__('Left Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_next_arrow_h_position_by' => 'left',
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                    'body.rtl {{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                ),
            )
        );

        $this->_add_responsive_control(
            'carousel_next_arrow_right_position',
            array(
                'label' => esc_html__('Right Indent', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px', '%', 'em', 'custom'),
                'range' => array(
                    'px' => array(
                        'min' => -400,
                        'max' => 400,
                    ),
                    '%' => array(
                        'min' => -100,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => -50,
                        'max' => 50,
                    ),
                ),
                'condition' => array(
                    'carousel_next_arrow_h_position_by' => 'right',
                ),
                'selectors' => array(
                    'body:not(.rtl) {{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
                    'body.rtl {{WRAPPER}} .lakit-carousel .lakit-arrow.next-arrow' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
                ),
            )
        );

        $this->_end_controls_section();
    }

    protected function register_carousel_dots_style_section($carousel_condition = []){
        $this->_start_controls_section(
            'carousel_dot_style_section',
            array(
                'label' => esc_html__('Carousel Dots', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => $carousel_condition
            )
        );

        $this->_start_controls_tabs('carousel_dot_tabs');

        $this->_start_controls_tab(
            'carousel_tab_dot_normal',
            array(
                'label' => esc_html__('Normal', 'lastudio-kit'),
            )
        );

        // $this->_add_group_control(
        //     Group_Control_Box_Style::get_type(),
        //     array(
        //         'name' => 'carousel_dot_normal_style',
        //         'label' => esc_html__('Dots Style', 'lastudio-kit'),
        //         'selector' => '{{WRAPPER}} .lakit-carousel .swiper-pagination-bullet',
        //         'condition' => [
        //             'carousel_dot_type!' => ['progressbar', 'custom', 'fraction']
        //         ]
        //     )
        // );
        $this->_add_control(
            '_carousel_dot__color1',
            array(
                'label' => esc_html__('Color Normal', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__dots' => '--lakit-carousel__dots-color-1: {{VALUE}}',
                ),
                'condition' => [
                    'carousel_dot_type' => ['progressbar', 'custom', 'fraction']
                ]
            )
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'carousel_tab_dot_hover',
            array(
                'label' => esc_html__('Active', 'lastudio-kit'),
            )
        );

        // $this->_add_group_control(
        //     Group_Control_Box_Style::get_type(),
        //     array(
        //         'name' => 'carousel_dot_hover_style',
        //         'label' => esc_html__('Dots Style', 'lastudio-kit'),
        //         'selector' => '{{WRAPPER}} .lakit-carousel .swiper-pagination-bullet-active,{{WRAPPER}} .lakit-carousel .swiper-pagination-bullet:hover',
        //         'condition' => [
        //             'carousel_dot_type!' => ['progressbar', 'custom', 'fraction']
        //         ]
        //     )
        // );

        $this->_add_control(
            '_carousel_dot__color2',
            array(
                'label' => esc_html__('Color Active', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__dots' => '--lakit-carousel__dots-color-2: {{VALUE}}',
                ),
                'condition' => [
                    'carousel_dot_type' => ['progressbar', 'custom', 'fraction']
                ]
            )
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_control(
            'carousel_dot_vertical',
            array(
                'label' => esc_html__('Enable Vertical Dots', 'lastudio-kit'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'lastudio-kit'),
                'label_off' => esc_html__('No', 'lastudio-kit'),
                'return_value' => 'yes',
                'default' => 'no',
                'prefix_class' => 'lakit-dots--vertical-',
                'condition' => [
                    'carousel_dot_type!' => 'progressbar'
                ],
                'separator' => 'before',
            )
        );

        $this->_add_responsive_control(
            'carousel_dots_gap',
            array(
                'label' => esc_html__('Gap', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'default' => array(
                    'size' => 5,
                    'unit' => 'px',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--lakit-carousel-dot-item-space: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
                'condition' => [
                    'carousel_dot_type!' => ['progressbar', 'custom', 'fraction']
                ]
            )
        );

        $this->_add_responsive_control(
            'carousel_dots_margin',
            array(
                'label' => esc_html__('Dots Box Margin', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel .lakit-carousel__dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->_add_control(
            'carousel_dots_h_alignment',
            array(
                'label' => esc_html__('Horizontal Position', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__('Left', 'lastudio-kit'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__('Center', 'lastudio-kit'),
                        'icon' => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__('Right', 'lastudio-kit'),
                        'icon' => 'eicon-h-align-right',
                    )
                ),
                'prefix_class' => 'lakit-dots-h-align-',
            )
        );

        $this->_add_control(
            'carousel_dots_v_alignment',
            array(
                'label' => esc_html__('Vertical Position', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'top' => array(
                        'title' => esc_html__('Top', 'lastudio-kit'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'middle' => array(
                        'title' => esc_html__('Middle', 'lastudio-kit'),
                        'icon' => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => esc_html__('Bottom', 'lastudio-kit'),
                        'icon' => 'eicon-v-align-bottom',
                    )
                ),
                'prefix_class' => 'lakit-dots-v-align-',
            )
        );

        $this->_add_control(
            'carousel_dots_alignment',
            array(
                'label' => esc_html__('Text Alignment', 'lastudio-kit'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => esc_html__('Left', 'lastudio-kit'),
                        'icon' => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__('Center', 'lastudio-kit'),
                        'icon' => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__('Right', 'lastudio-kit'),
                        'icon' => 'eicon-text-align-right',
                    )
                ),
                'prefix_class' => 'lakit-dots-text-',
            )
        );

        $this->_end_controls_section();

        $bar_conditions = $carousel_condition;
        $bar_conditions['carousel_scrollbar'] = 'true';

        $this->_start_controls_section(
            'carousel_scrollbar_style_section',
            array(
                'label' => esc_html__('Carousel Scrollbar', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => $bar_conditions
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__width',
            array(
                'label' => esc_html__('Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__height',
            array(
                'label' => esc_html__('Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            '_carousel_scrollbar_drag__width',
            array(
                'label' => esc_html__('Draggable Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-drag-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar_drag__height',
            array(
                'label' => esc_html__('Draggable Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-drag-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_control(
            '_carousel_scrollbar__color1',
            array(
                'label' => esc_html__('Color Normal', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar' => 'background: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            '_carousel_scrollbar__color2',
            array(
                'label' => esc_html__('Color Active', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .swiper-scrollbar-drag' => 'background: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__margin',
            array(
                'label' => __('Margin', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%', 'em', 'vw', 'vh', 'custom'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__radius',
            array(
                'label' => __('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar, {{WRAPPER}} .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_section();
    }

    protected function register_carousel_arrows_dots_style_section($carousel_condition = [])
    {
        $this->register_carousel_arrows_style_section($carousel_condition);
        $this->register_carousel_dots_style_section($carousel_condition);
    }

    public function get_lakit_id(){
        return esc_attr($this->get_id());
    }

    public function get_advanced_carousel_options($carousel_columns = false, $widget_id = '', $settings = null)
    {
        if(empty($settings)){
            $settings = $this->get_settings();
        }
        if(empty($widget_id)){
            $widget_id = $this->get_lakit_id();
        }

        $rows = thepack_addon_kit_helper()->get_attribute_with_all_breakpoints('carousel_rows', $settings);

        $carousel_direction_resp = thepack_addon_kit_helper()->get_attribute_with_all_breakpoints('carousel_direction', $settings);

        $carousel_direction = (!empty($settings['carousel_direction']) && $settings['carousel_direction'] == 'vertical') ? 'vertical' : 'horizontal';
        if ($carousel_direction == 'vertical') {
            $rows = thepack_addon_kit_helper()->get_attribute_with_all_breakpoints('carousel_rows', []);
        }

        $carousel_id = $this->get_settings_for_display('carousel_id');
        if (empty($carousel_id)) {
            $carousel_id = 'lakit_carousel_' . $widget_id;
        }

        $carousel_autoheight = isset($settings['carousel_autoheight']) ? $settings['carousel_autoheight'] : false;

        $carousel_creative_translate = $this->get_settings_for_display('carousel_creative_translate');
        if(is_null($carousel_creative_translate)){
            $carousel_creative_translate = 100;
        }

        $options = array(
            'slidesToScroll' => thepack_addon_kit_helper()->get_attribute_with_all_breakpoints('carousel_to_scroll', $settings),
            'rows' => $rows,
            'autoplaySpeed' => absint($settings['carousel_autoplay_speed']),
            'autoplay' => filter_var($settings['carousel_autoplay'], FILTER_VALIDATE_BOOLEAN),
            'infinite' => filter_var($settings['carousel_loop'], FILTER_VALIDATE_BOOLEAN),
            'centerMode' => filter_var($settings['carousel_center_mode'], FILTER_VALIDATE_BOOLEAN),
            'pauseOnHover' => filter_var($settings['carousel_pause_on_hover'], FILTER_VALIDATE_BOOLEAN),
            'pauseOnInteraction' => filter_var($settings['carousel_pause_on_interaction'], FILTER_VALIDATE_BOOLEAN),
            'reverseDirection' => filter_var($settings['carousel_reverse_direction'], FILTER_VALIDATE_BOOLEAN),
            'infiniteEffect' => filter_var($settings['carousel_enable_linear_effect'], FILTER_VALIDATE_BOOLEAN),
            'speed' => absint($settings['carousel_speed']),
            'arrows' => filter_var($settings['carousel_arrows'], FILTER_VALIDATE_BOOLEAN),
            'dots' => filter_var($settings['carousel_dots'], FILTER_VALIDATE_BOOLEAN),
            'variableWidth' => filter_var($settings['enable_swiper_item_auto_width'], FILTER_VALIDATE_BOOLEAN),
            'prevArrow' => '.lakit-carousel__prev-arrow-' . $widget_id,
            'nextArrow' => '.lakit-carousel__next-arrow-' . $widget_id,
            'dotsElm' => '.lakit-carousel__dots_' . $widget_id,
            'rtl' => is_rtl(),
            'effect' => $settings['carousel_effect'],
            'coverflowEffect' => [
                'rotate' => $this->get_settings_for_display('carousel_coverflow__rotate'),
                'stretch' => $this->get_settings_for_display('carousel_coverflow__stretch'),
                'depth' => $this->get_settings_for_display('carousel_coverflow__depth'),
                'modifier' => $this->get_settings_for_display('carousel_coverflow__modifier'),
                'scale' => $this->get_settings_for_display('carousel_coverflow__scale'),
            ],
            'creativeEffect' => [
                'translateX' => absint($carousel_creative_translate)
            ],
            'dotType' => $settings['carousel_dot_type'],
            'direction' => $carousel_direction,
            'uniqueID' => $carousel_id,
            'asFor' => $settings['carousel_as_for'],
            'thumbs' => $settings['carousel_thumbs'] ?? '',
            'autoHeight' => filter_var($carousel_autoheight, FILTER_VALIDATE_BOOLEAN),
            'scrollbar' => [
                'enabled' => filter_var($settings['carousel_scrollbar'], FILTER_VALIDATE_BOOLEAN),
                'el'    => '.lakit-carousel__scrollbar_' . $widget_id
            ],
            'directionbkp' => $carousel_direction_resp,
        );
        if ($carousel_columns === false) {
            $options['slidesToShow'] = thepack_addon_kit_helper()->get_attribute_with_all_breakpoints('carousel_columns', $settings);
        } else {
            $options['slidesToShow'] = thepack_addon_kit_helper()->get_attribute_with_all_breakpoints($carousel_columns, $settings);
        }
        if(filter_var($settings['enable_swiper_item_auto_width'], FILTER_VALIDATE_BOOLEAN)){
          $this->add_render_attribute('main-container', 'class', 'e-swiper--variablewidth');
        }
        return $options;
    }

    /**
     * @param $icon
     * @param $attributes
     * @param $tag
     * @return bool|mixed|string
     */
    public static function try_get_icon_html( $icon, $attributes = [], $tag = 'i' ) {
        $output = '';
        if ( empty( $icon['library'] ) ) {
            return $output;
        }
        if ( 'svg' === $icon['library'] ) {
            $output = Icons_Manager::render_uploaded_svg_icon( $icon['value'] );
        } else {
            $output = Icons_Manager::render_font_icon( $icon, $attributes, $tag );
        }
        return $output;
    }

    protected function dotv2_register_pagination_controls( $conditions = [] ){
        $start_logical = is_rtl() ? 'right' : 'left';
        $end_logical = is_rtl() ? 'left' : 'right';

        $this->_start_controls_section(
            'section_carousel_pagination_style',
            array(
                'label'      => esc_html__( 'Carousel Pagination', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => $conditions
            )
        );

        $this->add_responsive_control(
            'dots_direction',
            [
                'label' => esc_html__( 'Direction', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__( 'Row', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-right',
                    ],
                    'column' => [
                        'title' => esc_html__( 'Column', 'lastudio-kit' ),
                        'icon' => 'eicon-arrow-down',
                    ],
                    'none' => [
                        'title' => esc_html__( 'None', 'lastudio-kit' ),
                        'icon' => 'eicon-ban',
                    ],
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'row' => '--e-dot-direction: row;--e-dot-display: flex',
                    'column' => '--e-dot-direction: column;--e-dot-display: flex',
                    'none' => '--e-dot-display: none',
                ]
            ]
        );

        $this->add_control(
            'heading_dots_pagination_horizontal',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Horizontal', 'lastudio-kit' ),
            ]
        );

        $this->add_responsive_control(
            'dots_h_orientation',
            [
                'label' => esc_html__( 'Orientation', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-' . $start_logical,
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'lastudio-kit' ),
                        'icon' => 'eicon-h-align-' . $end_logical,
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'start' => '--e-dot-h-position:0px;--e-dot-h-transform:0%;',
                    'center' => '--e-dot-h-position:50%;--e-dot-h-transform:-50%;',
                    'end' => '--e-dot-h-position:100%;--e-dot-h-transform:-100%;',
                ]
            ]
        );

        $this->add_responsive_control(
            'dots_h_offset',
            array_merge( $this->dotv2_get_position_slider_initial_configuration(), [
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-h-offset: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'size' => 0,
                ],
            ] )
        );

        $this->add_control(
            'heading_dots_pagination_vertical',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Vertical', 'lastudio-kit' ),
            ]
        );

        $this->add_responsive_control(
            'dots_v_orientation',
            [
                'label' => esc_html__( 'Orientation', 'lastudio-kit' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__( 'Start', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'end' => [
                        'title' => esc_html__( 'End', 'lastudio-kit' ),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'end',
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'start' => '--e-dot-v-position: 0%;--e-dot-v-transform: 0%;',
                    'center' => '--e-dot-v-position: 50%;--e-dot-v-transform: -50%;',
                    'end' => '--e-dot-v-position: 100%;--e-dot-v-transform: -100%;',
                ]
            ]
        );

        $this->add_responsive_control(
            'dots_v_offset',
            array_merge( $this->dotv2_get_position_slider_initial_configuration(), [
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-v-offset: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'size' => 0,
                ],
                'separator' => 'after',
            ] )
        );


        $this->add_control(
            'heading_dots_pagination_label',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Dot Item', 'lastudio-kit' ),
            ]
        );

        $this->add_responsive_control(
            'dot_space',
            [
                'label' => esc_html__( 'Item space', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'pg_tabs' );
        $this->start_controls_tab( 'pg_tab_normal', [
            'label' => esc_html__( 'Normal', 'lastudio-kit' ),
        ] );
        $this->add_control(
            'dot_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'dot_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_height',
            [
                'label' => esc_html__( 'Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_radius',
            [
                'label' => esc_html__( 'Radius', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_scale',
            [
                'label' => esc_html__( 'Scale', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'pg_tab_hover', [
            'label' => esc_html__( 'Active', 'lastudio-kit' ),
        ] );
        $this->add_control(
            'dot_active_color',
            [
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-active-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_control(
            'dot_active_border_color',
            [
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-active-border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_active_width',
            [
                'label' => esc_html__( 'Width', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-active-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_active_height',
            [
                'label' => esc_html__( 'Height', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-active-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'dot_active_scale',
            [
                'label' => esc_html__( 'Scale', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 3,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => '--e-dot-active-scale: {{SIZE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->_end_controls_section();

        $bar_conditions = [
            'carousel_scrollbar!' => '',
            'enable_carousel!' => '',
        ];

        $this->_start_controls_section(
            'carousel_scrollbar_style_section',
            array(
                'label' => esc_html__('Carousel Scrollbar', 'lastudio-kit'),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => $bar_conditions
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__width',
            array(
                'label' => esc_html__('Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__height',
            array(
                'label' => esc_html__('Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_responsive_control(
            '_carousel_scrollbar_drag__width',
            array(
                'label' => esc_html__('Draggable Width', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-drag-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar_drag__height',
            array(
                'label' => esc_html__('Draggable Height', 'lastudio-kit'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}}' => '--swiper-scrollbar-drag-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->_add_control(
            '_carousel_scrollbar__color1',
            array(
                'label' => esc_html__('Color Normal', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar' => 'background: {{VALUE}}',
                ),
            )
        );
        $this->_add_control(
            '_carousel_scrollbar__color2',
            array(
                'label' => esc_html__('Color Active', 'lastudio-kit'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .swiper-scrollbar-drag' => 'background: {{VALUE}}',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__margin',
            array(
                'label' => __('Margin', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%', 'em', 'vw', 'vh', 'custom'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_add_responsive_control(
            '_carousel_scrollbar__radius',
            array(
                'label' => __('Border Radius', 'lastudio-kit'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-carousel__scrollbar, {{WRAPPER}} .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );
        $this->_end_controls_section();
    }

    protected function dotv2_get_position_slider_initial_configuration(){
        return [
            'label' => esc_html__( 'Position', 'lastudio-kit' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
            'range' => [
                'px' => [
                    'min' => -1000,
                    'max' => 1000,
                ],
                '%' => [
                    'min' => -200,
                    'max' => 200,
                ],
                'em' => [
                    'min' => -50,
                    'max' => 50,
                ],
                'rem' => [
                    'min' => -50,
                    'max' => 50,
                ],
                'vw' => [
                    'min' => -200,
                    'max' => 200,
                ],
                'vh' => [
                    'min' => -200,
                    'max' => 200,
                ],
            ],
        ];
    }
}
