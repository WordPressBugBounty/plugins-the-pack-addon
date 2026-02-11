(function ($) {
	"use strict";

	function addCustomCss(css, context) {
		if (!context) {
			return;
		}
		var model = context.model,
			customCSS = model.get('settings').get('tp_expand_custom_css');
		var selector = '.elementor-element.elementor-element-' + model.get('id');

		if ('document' === model.get('elType')) {
		  selector = elementor.config.document.settings.cssWrapperSelector;
		}

		if (customCSS) {
		  css += customCSS.replace(/selector/g, selector);
		}

		return css;
	  }

	elementor.hooks.addFilter('editor/style/styleText', addCustomCss);


  function thepack_section_start_render() {
    var _elementor = typeof elementor !== "undefined" ? elementor : elementorFrontend;

    if (!_elementor || !_elementor.hooks) {
      console.warn("Elementor hooks not available");
      return;
    }

    _elementor.hooks.addFilter(
      "thepack_element_container/before-render",
      function (html, settings, el) {

        if (
          typeof settings.tp_dot_container_pos !== "undefined" &&
          Array.isArray(settings.tp_dot_container_pos) &&
          settings.tp_dot_container_pos.length > 0
        ) {
          var items = settings.tp_dot_container_pos;
          items.forEach(function (item) {
            html += '<span class="tp-dot tp-dot__' + item + '"></span>';
          });
        }

        return html;
      }
    );

  }


	function addPageCustomCss() {

		var customCSS = elementor.settings.page.model.get('tp_expand_custom_css');
		if (customCSS) {
			customCSS = customCSS.replace(/selector/g, elementor.config.settings.page.cssWrapperSelector);
			elementor.settings.page.getControlsCSS().elements.$stylesheetElement.append(customCSS);
		}
		
    try {
      if (typeof thepack_section_start_render === "function") {
        thepack_section_start_render();
      }
    } catch (error) {
      console.error("Error initializing custom functions:", error);
    }

	}
	// elementor.settings.page.model.on('change', addPageCustomCss);
	elementor.on('preview:loaded', addPageCustomCss);

})(jQuery);