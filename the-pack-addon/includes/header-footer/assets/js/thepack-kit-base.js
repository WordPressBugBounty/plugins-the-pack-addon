
(function ($) {
    "use strict";

    $(document).on('thepack-kit/LazyloadSequenceEffects', '.col-row, .swiper-container', function (e){
        var $this = $(this);
        if( $this.hasClass('swiper-container') ){
            LaStudioKits.LazyLoad( $this, {rootMargin: '0px'} ).observe();
        }
        else{
            LaStudioKits.LazyLoad( $('>*', $this), {rootMargin: '0px'} ).observe();
        }
    });


    var LaStudioKits = {

        carouselAsFor: [],

        initCarousel: function ($scope) {

            var $carousel = $scope.find('.lakit-carousel').first();

            if ($carousel.length == 0) {
                return;
            }

            if ($carousel.hasClass('inited')) {
                return;
            }

            $carousel.addClass('inited');

            var elementSettings = $carousel.data('slider_options'),
                slidesToShow = parseInt(elementSettings.slidesToShow.desktop) || 1,
                elementorBreakpoints = elementorFrontend.config.responsive.activeBreakpoints,
                carousel_id = elementSettings.uniqueID;

            var swiperOptions = {
                slidesPerView: slidesToShow,
                loop: elementSettings.infinite,
                speed: elementSettings.speed,
                handleElementorBreakpoints: true,
                slidesPerColumn: elementSettings.rows.desktop,
                slidesPerGroup: elementSettings.slidesToScroll.desktop || 1,
                loopAdditionalSlides: 1
            }

            swiperOptions.breakpoints = {};

            var lastBreakpointSlidesToShowValue = 1;
            var defaultLGDevicesSlidesCount = 1;
            Object.keys(elementorBreakpoints).reverse().forEach(function (breakpointName) {
                // Tablet has a specific default `slides_to_show`.
                var defaultSlidesToShow = 'tablet' === breakpointName ? defaultLGDevicesSlidesCount : lastBreakpointSlidesToShowValue;
                swiperOptions.breakpoints[elementorBreakpoints[breakpointName].value] = {
                    slidesPerView: +elementSettings.slidesToShow[breakpointName] || defaultSlidesToShow,
                    slidesPerGroup: +elementSettings.slidesToScroll[breakpointName] || 1,
                    slidesPerColumn: +elementSettings.rows[breakpointName] || 1,
                };
                lastBreakpointSlidesToShowValue = +elementSettings.slidesToShow[breakpointName] || defaultSlidesToShow;
            });

            if (elementSettings.autoplay) {
                swiperOptions.autoplay = {
                    delay: (elementSettings.effect == 'slide' && elementSettings.infiniteEffect ? 10 : elementSettings.autoplaySpeed),
                    disableOnInteraction: elementSettings.pauseOnInteraction,
                    pauseOnMouseEnter: elementSettings.pauseOnHover,
                    reverseDirection: elementSettings.reverseDirection || false,
                };
                if(elementSettings.effect == 'slide' && elementSettings.infiniteEffect){
                    $carousel.addClass('lakit--linear-effect');
                    if(swiperOptions.slidesPerView == 1){
                        swiperOptions.slidesPerView = 'auto';
                        swiperOptions.breakpoints = {};
                        $carousel.addClass('lakit--linear-effect-auto');
                    }
                }
            }
            if (elementSettings.centerMode) {
                swiperOptions.centerInsufficientSlides = true;
                swiperOptions.centeredSlides = true;
                swiperOptions.centeredSlidesBounds = false;
            }

            switch (elementSettings.effect) {
                case 'fade':
                    if (slidesToShow == 1) {
                        swiperOptions.effect = elementSettings.effect;
                        swiperOptions.fadeEffect = {
                            crossFade: true
                        };
                    }
                    break;

                case 'coverflow':
                    swiperOptions.effect = 'coverflow';
                    swiperOptions.grabCursor = true;
                    swiperOptions.centeredSlides = true;
                    swiperOptions.slidesPerView = 'auto';
                    swiperOptions.coverflowEffect = {
                        rotate: 50,
                        stretch: 0,
                        depth: 100,
                        modifier: 1,
                        slideShadows: true
                    };
                    swiperOptions.coverflowEffect = $.extend( {}, {
                        rotate: 0,
                        stretch: 100,
                        depth: 100,
                        modifier: 2.6,
                        slideShadows : true
                    }, elementSettings.coverflowEffect )
                    break;

                case 'cube':
                    swiperOptions.effect = 'cube';
                    swiperOptions.grabCursor = true;
                    swiperOptions.cubeEffect = {
                        shadow: true,
                        slideShadows: true,
                        shadowOffset: 20,
                        shadowScale: 0.94,
                    }
                    swiperOptions.slidesPerView = 1;
                    swiperOptions.slidesPerGroup = 1;
                    break;

                case 'flip':
                    swiperOptions.effect = 'flip';
                    swiperOptions.grabCursor = true;
                    swiperOptions.slidesPerView = 1;
                    swiperOptions.slidesPerGroup = 1;
                    break;

                case 'slide':
                    swiperOptions.effect = 'slide';
                    swiperOptions.grabCursor = true;
                    break;
            }

            if (elementSettings.arrows) {
                swiperOptions.navigation = {
                    prevEl: elementSettings.prevArrow,
                    nextEl: elementSettings.nextArrow
                };
            }
            if (elementSettings.dots) {

                var  _dotType = elementSettings.dotType || 'bullets';
                swiperOptions.pagination = {
                    el: elementSettings.dotsElm || '.lakit-carousel__dots',
                    clickable: true
                };
                if(_dotType != 'custom'){
                    swiperOptions.pagination.type = _dotType
                }
                if (_dotType == 'bullets') {
                    swiperOptions.pagination.dynamicBullets = true;
                    swiperOptions.pagination.renderBullet = function (index, className) {
                        return '<span class="' + className + '">' + (index + 1) + "</span>";
                    }
                }
                if (_dotType == 'custom') {
                    swiperOptions.pagination.modifierClass = 'lakit-swiper-pagination-'
                    swiperOptions.pagination.renderBullet = function (t, e) {
                        return '<span class="' + e + '"><svg width="65px" height="65px" viewBox="0 0 72 72" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><circle class="time" stroke-width="5" fill="none" stroke-linecap="round" cx="33" cy="33" r="28"></circle></svg></span>'
                    }
                }
            }

            var enableScrollbar = elementSettings.scrollbar || false;

            if (!enableScrollbar) {
                swiperOptions.scrollbar = false;
            }
            else {
                swiperOptions.scrollbar = {
                    el: '.lakit-carousel__scrollbar',
                    draggable: true
                }
            }

            var _has_slidechange_effect = false,
                _slide_change_effect_in = elementSettings.content_effect_in || 'fadeInUp',
                _slide_change_effect_out = elementSettings.content_effect_out || 'fadeOutDown';

            if (elementSettings.content_selector !== undefined && $carousel.find(elementSettings.content_selector).length > 0) {
                _has_slidechange_effect = true;
            }

            if ($carousel.closest('.no-slide-animation').length || $carousel.closest('.slide-no-animation').length) {
                _has_slidechange_effect = false;
            }

            if (elementSettings.direction) {
                swiperOptions.direction = elementSettings.direction;
            }
            if (elementSettings.autoHeight) {
                swiperOptions.autoHeight = elementSettings.autoHeight
            }
            swiperOptions.watchSlidesProgress = true;
            swiperOptions.watchSlidesVisibility = true;
            swiperOptions.parallax = true;

            function findAsForObj(value, key) {
                var _found = [];
                for (var i = 0; i < LaStudioKits.carouselAsFor.length; i++) {
                    if (LaStudioKits.carouselAsFor[i][key] == value) {
                        LaStudioKits.carouselAsFor[i]['index'] = i;
                        _found.push(LaStudioKits.carouselAsFor[i]);
                        break;
                    }
                }
                return _found;
            }

            function makeLoadedAsFor(value, key) {
                var obj = findAsForObj(value, key);
                if (obj.length) {
                    obj[0][key + '_init'] = true;
                }
                return obj;
            }

            if (typeof elementSettings.asFor !== "undefined" && elementSettings.asFor != '' && elementSettings.asFor != '#' && $('#' + elementSettings.asFor).length) {
                var _thumb_swiper = $('#' + elementSettings.asFor).data('swiper');
                if (null === _thumb_swiper || "undefined" === _thumb_swiper) {
                    swiperOptions.thumbs = {
                        swiper: _thumb_swiper,
                    }
                }
                else {
                    LaStudioKits.carouselAsFor.push({
                        main: carousel_id,
                        thumb: elementSettings.asFor,
                        main_init: false,
                        thumb_init: false
                    });
                }
                swiperOptions.slideToClickedSlide = true;
            }

            var $swiperContainer = $scope.find('.swiper-container');

            var Swiper = elementorFrontend.utils.swiper;

            function initSlideContentAnimation( needwaiting ){
                var $sliderContents = $carousel.find('.swiper-slide-active .lakit-template-wrapper .elementor-invisible.lakit-has-entrance-animation');
                $sliderContents.each(function () {
                    var _settings = $(this).data('settings'),
                        animation = elementorFrontend.getCurrentDeviceSetting(_settings, '_animation') || elementorFrontend.getCurrentDeviceSetting(_settings, 'animation'),
                        animationDelay = elementorFrontend.getCurrentDeviceSetting(_settings, '_animation_delay') || elementorFrontend.getCurrentDeviceSetting(_settings, 'animation_delay'),
                        $element = $(this);

                    if(!LaStudioKits.isInViewport(this)){
                        return;
                    }

                    if ('none' === animation) {
                        $element.removeClass('elementor-invisible');
                    }
                    else {
                        setTimeout(function () {
                            $element.removeClass('elementor-invisible').addClass('animated ' + animation);
                        }, animationDelay);
                    }
                });
                if (_has_slidechange_effect) {
                    $carousel.find('.swiper-slide:not(.swiper-slide-visible) ' + elementSettings.content_selector).addClass('no-effect-class').removeClass(_slide_change_effect_in).addClass(_slide_change_effect_out);
                    $carousel.find('.swiper-slide-visible ' + elementSettings.content_selector).removeClass('no-effect-class').removeClass(_slide_change_effect_out).addClass(_slide_change_effect_in);
                }
                const _w_callback = () => {
                    var $InActiveSliderContents = $carousel.find('.swiper-slide:not(.swiper-slide-visible) .lakit-template-wrapper .lakit-has-entrance-animation');
                    $InActiveSliderContents.each(function () {
                        var _settings = $(this).data('settings'),
                            animation = elementorFrontend.getCurrentDeviceSetting(_settings, '_animation') || elementorFrontend.getCurrentDeviceSetting(_settings, 'animation');
                        if ('none' === animation) {
                            $(this).removeClass('animated').addClass('elementor-invisible');
                        }
                        else {
                            $(this).removeClass('animated ' + animation).addClass('elementor-invisible');
                        }
                    });
                }
                if(needwaiting){
                    setTimeout(_w_callback, 1000);
                }
                else{
                    _w_callback();
                }
            }

            const totalSlides = $carousel.find('.swiper-wrapper').first().find('.swiper-slide').length;

            $swiperContainer.trigger('thepack-kit/LazyloadSequenceEffects');

            new Swiper($swiperContainer, swiperOptions).then(function (SwiperInstance) {

                if(totalSlides < 5 && SwiperInstance.pagination.$el){
                    SwiperInstance.pagination.$el.addClass('no-bullets-dynamic');
                }

                if(elementSettings.autoplay && elementSettings.pauseOnHover && typeof SwiperInstance.autoplay !== "undefined" && typeof SwiperInstance.autoplay.onMouseEnter === "undefined"){
                    $swiperContainer.on('mouseenter', function (){
                        SwiperInstance.autoplay.stop();
                    }).on('mouseleave', function (){
                        SwiperInstance.autoplay.start();
                    });
                }

                if(elementSettings.autoHeight){
                    SwiperInstance.wrapperEl.style.height = 'auto';
                }

                $carousel.css('--data-autoplay-speed', SwiperInstance.params.autoplay.delay + 'ms');

                $swiperContainer.data('swiper', SwiperInstance);

                $swiperContainer.find('.elementor-top-section').trigger('thepack-kit/section/calculate-container-width');

                initSlideContentAnimation(true);

                var ob1 = makeLoadedAsFor(carousel_id, 'thumb');
                var ob2 = makeLoadedAsFor(carousel_id, 'main');

                if (ob1.length && ob1[0].main_init && ob1[0].thumb_init) {
                    var _main_swiper = $('#' + ob1[0].main).data('swiper');
                    _main_swiper.thumbs.swiper = $('#' + ob1[0].thumb).data('swiper');
                    _main_swiper.thumbs.init();
                }
                if (ob2.length && ob2[0].main_init && ob2[0].thumb_init) {
                    var _main_swiper = $('#' + ob2[0].main).data('swiper');
                    _main_swiper.thumbs.swiper = $('#' + ob2[0].thumb).data('swiper');
                    _main_swiper.thumbs.init();
                }

                if (_has_slidechange_effect) {
                    $carousel.find(elementSettings.content_selector).addClass('animated no-effect-class');
                    $carousel.find('.swiper-slide-visible ' + elementSettings.content_selector).removeClass('no-effect-class').addClass(_slide_change_effect_in);
                }

                SwiperInstance.on('slideChange', function () {
                    if ($swiperContainer.hasClass(this.params.thumbs.thumbsContainerClass)) {
                        this.clickedIndex = this.activeIndex;
                        this.clickedSlide = this.slides[this.clickedIndex];
                        this.emit('tap');
                    }
                });

                SwiperInstance.on('slideChangeTransitionEnd', function (){
                    initSlideContentAnimation(false);
                });

                $('.elementor-motion-effects-element', $scope).trigger('resize');

                $(document).trigger('thepack-kit/carousel/init_success', { swiperContainer: $swiperContainer, SwiperInstance: SwiperInstance, parentContainer: $scope });
            });

        },

        isInViewport: function (element) {
            var bounding = element.getBoundingClientRect();
            return (bounding.top >= -element.offsetHeight
                && bounding.left >= -element.offsetWidth
                && bounding.right <= (window.innerWidth || document.documentElement.clientWidth) + element.offsetWidth
                && bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight) + element.offsetHeight
            )
        },
        LazyLoad: function (){
            var selector = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
            var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
            var _defaultConfig$option = $.extend({}, {
                    rootMargin: '50px',
                    threshold: 0,
                    load: function load(element) {
                        var base_src = element.getAttribute('data-src') || element.getAttribute('data-lazy') || element.getAttribute('data-lazy-src') || element.getAttribute('data-lazy-original'),
                            base_srcset = element.getAttribute('data-src') || element.getAttribute('data-lazy-srcset'),
                            base_sizes = element.getAttribute('data-sizes') || element.getAttribute('data-lazy-sizes');
                        if (base_src) {
                            element.src = base_src;
                        }
                        if (base_srcset) {
                            element.srcset = base_srcset;
                        }
                        if (base_sizes) {
                            element.sizes = base_sizes;
                        }
                        if (element.getAttribute('data-background-image')) {
                            element.style.backgroundImage = 'url("' + element.getAttribute('data-background-image') + '")';
                        }
                        element.setAttribute('data-element-loaded', true);
                        if (element.classList.contains('jetpack-lazy-image')) {
                            element.classList.add('jetpack-lazy-image--handled');
                        }
                    },
                    complete: function (elm) {}
                }, options),
                rootMargin = _defaultConfig$option.rootMargin,
                threshold = _defaultConfig$option.threshold,
                load = _defaultConfig$option.load,
                complete = _defaultConfig$option.complete; // // If initialized, then disconnect the observer

            var _target_cache = false,
                _counter = 0;

            function onIntersection(load) {
                return function (entries, observer) {
                    entries.forEach(function (entry) {
                        if(entry.isIntersecting){
                            if(_counter > 7){
                                _counter = 0;
                            }
                            if(_target_cache !== entry.target.offsetTop){
                                _counter = 0;
                                _target_cache = entry.target.offsetTop;
                            }
                            else{
                                _counter++;
                            }
                            observer.unobserve(entry.target);
                            entry.target.style.setProperty('--effect-delay', _counter);
                            load(entry.target);
                        }
                    });
                };
            }

            var observer = void 0;

            if ("IntersectionObserver" in window) {
                observer = new IntersectionObserver(onIntersection(load), {
                    rootMargin: rootMargin,
                    threshold: threshold
                });
            }
            return {
                observe: function observe() {
                    if(!selector){
                        return;
                    }
                    for (var i = 0; i < selector.length; i++) {
                        if(selector[i].getAttribute('data-element-loaded') === 'true'){
                            continue;
                        }
                        if (observer) {
                            observer.observe(selector[i]);
                            continue;
                        }
                        load(selector[i]);
                    }
                    complete(selector);
                }
            };
        }
    }

    function initFrontEndFunction(){

        window.elementorFrontend.hooks.addAction('frontend/element_ready/lakit-advanced-carousel.default', function ($scope) {
            LaStudioKits.initCarousel($scope);
        });
    }

    $(window).on('elementor/frontend/init', initFrontEndFunction);

    window.LaStudioKits = LaStudioKits;

}(jQuery));




