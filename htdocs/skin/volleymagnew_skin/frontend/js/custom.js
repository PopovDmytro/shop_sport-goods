var newsSettings = {
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 3000,
        accessibility: false,
        draggable: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev--hover.png"></button>'
    },

    mainSliderSettings = {
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        accessibility: false,
        draggable: false
    },

    productsSettings = {
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 3000,
        accessibility: false,
        draggable: false,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true
                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true
                }
            }
        ],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev--hover.png"></button>'
    },

    partnersSlider = {
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        accessibility: false,
        draggable: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            }
        ],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow--hover.png"></button>'
    },

    productColorsSlider = {
        infinite: !1,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: !1,
        accessibility: !1,
        draggable: !1,
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow--sm.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow-hover--sm.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow--sm.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow-hover--sm.png"></button>'
    };

(function ($) {
    $(function () {
        // News slider
        var newsSlider = function () {
            $('#news-label').addClass('isActive').siblings().removeClass('isActive');
            $('#publications').hide();
            $('#news').show();
            $('.my-slider_news').slick(newsSettings);
        };

        var publicationsSlider = function () {
            $('#publications-label').addClass('isActive').siblings().removeClass('isActive');
            $('#news').hide();
            $('#publications').show();
            $('.my-slider_publication').slick(newsSettings);
        };

        newsSlider();

        // Publications
        $('#publications-label').on('click', function () {
            publicationsSlider();
        });

        $('#news-label').on('click', function () {
            newsSlider();
        });


        // Main slider
        $('.my-slider_main').slick(mainSliderSettings);

        // Product slider
        $('.my-slider_product').slick(productsSettings);

        // Partners slider
        $('.my-slider_partners').slick(partnersSlider);

        //product colors slider
        $('.my-slider_product-colors').slick(productColorsSlider);
    });

    var dataFstPrice;
    var dataSecPrice;
    var textFstPrice = $('#fstVal');
    var textSecPrice = $('#secVal');
    function setText() {
        dataFstPrice = ($('#sliderOutput1').attr('aria-valuenow'));
        dataSecPrice = ($('#sliderOutput2').attr('aria-valuenow'));
        textFstPrice.text(dataFstPrice);
        textSecPrice.text(dataSecPrice);
    }
    setText();
    //$('#sliderOutput1, #sliderOutput2').on('mousemove', setText);
    $('#sliderOutput1, #sliderOutput2').on("mousedown", function () {
        return $(document).on("mousemove", setText).one("mouseup", function () { return $(document).off("mousemove", setText); });
    });

    //open / close mobile search block
    document.querySelector('#mobile-search-toggle').addEventListener('click', function (evt) {
        var searchBox = document.querySelector('#search-box');

        if(getComputedStyle(searchBox).display === 'none') {
            searchBox.style.display = 'block';
        } else {
            searchBox.style.display = 'none';
        }

    });

})($j);

(function (jQuery) {

    var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

    !function ($) {

        "use strict";

        var FOUNDATION_VERSION = '6.3.1';

        // Global Foundation object
        // This is attached to the window, or used as a module for AMD/Browserify
        var Foundation = {
            version: FOUNDATION_VERSION,

            /**
             * Stores initialized plugins.
             */
            _plugins: {},

            /**
             * Stores generated unique ids for plugin instances
             */
            _uuids: [],

            /**
             * Returns a boolean for RTL support
             */
            rtl: function rtl() {
                return $('html').attr('dir') === 'rtl';
            },
            /**
             * Defines a Foundation plugin, adding it to the `Foundation` namespace and the list of plugins to initialize when reflowing.
             * @param {Object} plugin - The constructor of the plugin.
             */
            plugin: function plugin(_plugin, name) {
                // Object key to use when adding to global Foundation object
                // Examples: Foundation.Reveal, Foundation.OffCanvas
                var className = name || functionName(_plugin);
                // Object key to use when storing the plugin, also used to create the identifying data attribute for the plugin
                // Examples: data-reveal, data-off-canvas
                var attrName = hyphenate(className);

                // Add to the Foundation object and the plugins list (for reflowing)
                this._plugins[attrName] = this[className] = _plugin;
            },
            /**
             * @function
             * Populates the _uuids array with pointers to each individual plugin instance.
             * Adds the `zfPlugin` data-attribute to programmatically created plugins to allow use of $(selector).foundation(method) calls.
             * Also fires the initialization event for each plugin, consolidating repetitive code.
             * @param {Object} plugin - an instance of a plugin, usually `this` in context.
             * @param {String} name - the name of the plugin, passed as a camelCased string.
             * @fires Plugin#init
             */
            registerPlugin: function registerPlugin(plugin, name) {
                var pluginName = name ? hyphenate(name) : functionName(plugin.constructor).toLowerCase();
                plugin.uuid = this.GetYoDigits(6, pluginName);

                if (!plugin.$element.attr('data-' + pluginName)) {
                    plugin.$element.attr('data-' + pluginName, plugin.uuid);
                }
                if (!plugin.$element.data('zfPlugin')) {
                    plugin.$element.data('zfPlugin', plugin);
                }
                /**
                 * Fires when the plugin has initialized.
                 * @event Plugin#init
                 */
                plugin.$element.trigger('init.zf.' + pluginName);

                this._uuids.push(plugin.uuid);

                return;
            },
            /**
             * @function
             * Removes the plugins uuid from the _uuids array.
             * Removes the zfPlugin data attribute, as well as the data-plugin-name attribute.
             * Also fires the destroyed event for the plugin, consolidating repetitive code.
             * @param {Object} plugin - an instance of a plugin, usually `this` in context.
             * @fires Plugin#destroyed
             */
            unregisterPlugin: function unregisterPlugin(plugin) {
                var pluginName = hyphenate(functionName(plugin.$element.data('zfPlugin').constructor));

                this._uuids.splice(this._uuids.indexOf(plugin.uuid), 1);
                plugin.$element.removeAttr('data-' + pluginName).removeData('zfPlugin')
                /**
                 * Fires when the plugin has been destroyed.
                 * @event Plugin#destroyed
                 */
                    .trigger('destroyed.zf.' + pluginName);
                for (var prop in plugin) {
                    plugin[prop] = null; //clean up script to prep for garbage collection.
                }
                return;
            },

            /**
             * @function
             * Causes one or more active plugins to re-initialize, resetting event listeners, recalculating positions, etc.
             * @param {String} plugins - optional string of an individual plugin key, attained by calling `$(element).data('pluginName')`, or string of a plugin class i.e. `'dropdown'`
             * @default If no argument is passed, reflow all currently active plugins.
             */
            reInit: function reInit(plugins) {
                var isJQ = plugins instanceof $;
                try {
                    if (isJQ) {
                        plugins.each(function () {
                            $(this).data('zfPlugin')._init();
                        });
                    } else {
                        var type = typeof plugins === 'undefined' ? 'undefined' : _typeof(plugins),
                            _this = this,
                            fns = {
                                'object': function object(plgs) {
                                    plgs.forEach(function (p) {
                                        p = hyphenate(p);
                                        $('[data-' + p + ']').foundation('_init');
                                    });
                                },
                                'string': function string() {
                                    plugins = hyphenate(plugins);
                                    $('[data-' + plugins + ']').foundation('_init');
                                },
                                'undefined': function undefined() {
                                    this['object'](Object.keys(_this._plugins));
                                }
                            };
                        fns[type](plugins);
                    }
                } catch (err) {
                    console.error(err);
                } finally {
                    return plugins;
                }
            },

            /**
             * returns a random base-36 uid with namespacing
             * @function
             * @param {Number} length - number of random base-36 digits desired. Increase for more random strings.
             * @param {String} namespace - name of plugin to be incorporated in uid, optional.
             * @default {String} '' - if no plugin name is provided, nothing is appended to the uid.
             * @returns {String} - unique id
             */
            GetYoDigits: function GetYoDigits(length, namespace) {
                length = length || 6;
                return Math.round(Math.pow(36, length + 1) - Math.random() * Math.pow(36, length)).toString(36).slice(1) + (namespace ? '-' + namespace : '');
            },
            /**
             * Initialize plugins on any elements within `elem` (and `elem` itself) that aren't already initialized.
             * @param {Object} elem - jQuery object containing the element to check inside. Also checks the element itself, unless it's the `document` object.
             * @param {String|Array} plugins - A list of plugins to initialize. Leave this out to initialize everything.
             */
            reflow: function reflow(elem, plugins) {

                // If plugins is undefined, just grab everything
                if (typeof plugins === 'undefined') {
                    plugins = Object.keys(this._plugins);
                }
                // If plugins is a string, convert it to an array with one item
                else if (typeof plugins === 'string') {
                    plugins = [plugins];
                }

                var _this = this;

                // Iterate through each plugin
                $.each(plugins, function (i, name) {
                    // Get the current plugin
                    var plugin = _this._plugins[name];

                    // Localize the search to all elements inside elem, as well as elem itself, unless elem === document
                    var $elem = $(elem).find('[data-' + name + ']').addBack('[data-' + name + ']');

                    // For each plugin found, initialize it
                    $elem.each(function () {
                        var $el = $(this),
                            opts = {};
                        // Don't double-dip on plugins
                        if ($el.data('zfPlugin')) {
                            console.warn("Tried to initialize " + name + " on an element that already has a Foundation plugin.");
                            return;
                        }

                        if ($el.attr('data-options')) {
                            var thing = $el.attr('data-options').split(';').forEach(function (e, i) {
                                var opt = e.split(':').map(function (el) {
                                    return el.trim();
                                });
                                if (opt[0]) opts[opt[0]] = parseValue(opt[1]);
                            });
                        }
                        try {
                            $el.data('zfPlugin', new plugin($(this), opts));
                        } catch (er) {
                            console.error(er);
                        } finally {
                            return;
                        }
                    });
                });
            },
            getFnName: functionName,
            transitionend: function transitionend($elem) {
                var transitions = {
                    'transition': 'transitionend',
                    'WebkitTransition': 'webkitTransitionEnd',
                    'MozTransition': 'transitionend',
                    'OTransition': 'otransitionend'
                };
                var elem = document.createElement('div'),
                    end;

                for (var t in transitions) {
                    if (typeof elem.style[t] !== 'undefined') {
                        end = transitions[t];
                    }
                }
                if (end) {
                    return end;
                } else {
                    end = setTimeout(function () {
                        $elem.triggerHandler('transitionend', [$elem]);
                    }, 1);
                    return 'transitionend';
                }
            }
        };

        Foundation.util = {
            /**
             * Function for applying a debounce effect to a function call.
             * @function
             * @param {Function} func - Function to be called at end of timeout.
             * @param {Number} delay - Time in ms to delay the call of `func`.
             * @returns function
             */
            throttle: function throttle(func, delay) {
                var timer = null;

                return function () {
                    var context = this,
                        args = arguments;

                    if (timer === null) {
                        timer = setTimeout(function () {
                            func.apply(context, args);
                            timer = null;
                        }, delay);
                    }
                };
            }
        };

        // TODO: consider not making this a jQuery function
        // TODO: need way to reflow vs. re-initialize
        /**
         * The Foundation jQuery method.
         * @param {String|Array} method - An action to perform on the current jQuery object.
         */
        var foundation = function foundation(method) {
            var type = typeof method === 'undefined' ? 'undefined' : _typeof(method),
                $meta = $('meta.foundation-mq'),
                $noJS = $('.no-js');

            if (!$meta.length) {
                $('<meta class="foundation-mq">').appendTo(document.head);
            }
            if ($noJS.length) {
                $noJS.removeClass('no-js');
            }

            if (type === 'undefined') {
                //needs to initialize the Foundation object, or an individual plugin.
                Foundation.MediaQuery._init();
                Foundation.reflow(this);
            } else if (type === 'string') {
                //an individual method to invoke on a plugin or group of plugins
                var args = Array.prototype.slice.call(arguments, 1); //collect all the arguments, if necessary
                var plugClass = this.data('zfPlugin'); //determine the class of plugin

                if (plugClass !== undefined && plugClass[method] !== undefined) {
                    //make sure both the class and method exist
                    if (this.length === 1) {
                        //if there's only one, call it directly.
                        plugClass[method].apply(plugClass, args);
                    } else {
                        this.each(function (i, el) {
                            //otherwise loop through the jQuery collection and invoke the method on each
                            plugClass[method].apply($(el).data('zfPlugin'), args);
                        });
                    }
                } else {
                    //error for no class or no method
                    throw new ReferenceError("We're sorry, '" + method + "' is not an available method for " + (plugClass ? functionName(plugClass) : 'this element') + '.');
                }
            } else {
                //error for invalid argument type
                throw new TypeError('We\'re sorry, ' + type + ' is not a valid parameter. You must use a string representing the method you wish to invoke.');
            }
            return this;
        };

        window.Foundation = Foundation;
        $.fn.foundation = foundation;

        // Polyfill for requestAnimationFrame
        (function () {
            if (!Date.now || !window.Date.now) window.Date.now = Date.now = function () {
                return new Date().getTime();
            };

            var vendors = ['webkit', 'moz'];
            for (var i = 0; i < vendors.length && !window.requestAnimationFrame; ++i) {
                var vp = vendors[i];
                window.requestAnimationFrame = window[vp + 'RequestAnimationFrame'];
                window.cancelAnimationFrame = window[vp + 'CancelAnimationFrame'] || window[vp + 'CancelRequestAnimationFrame'];
            }
            if (/iP(ad|hone|od).*OS 6/.test(window.navigator.userAgent) || !window.requestAnimationFrame || !window.cancelAnimationFrame) {
                var lastTime = 0;
                window.requestAnimationFrame = function (callback) {
                    var now = Date.now();
                    var nextTime = Math.max(lastTime + 16, now);
                    return setTimeout(function () {
                        callback(lastTime = nextTime);
                    }, nextTime - now);
                };
                window.cancelAnimationFrame = clearTimeout;
            }
            /**
             * Polyfill for performance.now, required by rAF
             */
            if (!window.performance || !window.performance.now) {
                window.performance = {
                    start: Date.now(),
                    now: function now() {
                        return Date.now() - this.start;
                    }
                };
            }
        })();
        if (!Function.prototype.bind) {
            Function.prototype.bind = function (oThis) {
                if (typeof this !== 'function') {
                    // closest thing possible to the ECMAScript 5
                    // internal IsCallable function
                    throw new TypeError('Function.prototype.bind - what is trying to be bound is not callable');
                }

                var aArgs = Array.prototype.slice.call(arguments, 1),
                    fToBind = this,
                    fNOP = function fNOP() {},
                    fBound = function fBound() {
                        return fToBind.apply(this instanceof fNOP ? this : oThis, aArgs.concat(Array.prototype.slice.call(arguments)));
                    };

                if (this.prototype) {
                    // native functions don't have a prototype
                    fNOP.prototype = this.prototype;
                }
                fBound.prototype = new fNOP();

                return fBound;
            };
        }
        // Polyfill to get the name of a function in IE9
        function functionName(fn) {
            if (Function.prototype.name === undefined) {
                var funcNameRegex = /function\s([^(]{1,})\(/;
                var results = funcNameRegex.exec(fn.toString());
                return results && results.length > 1 ? results[1].trim() : "";
            } else if (fn.prototype === undefined) {
                return fn.constructor.name;
            } else {
                return fn.prototype.constructor.name;
            }
        }
        function parseValue(str) {
            if ('true' === str) return true;else if ('false' === str) return false;else if (!isNaN(str * 1)) return parseFloat(str);
            return str;
        }
        // Convert PascalCase to kebab-case
        // Thank you: http://stackoverflow.com/a/8955580
        function hyphenate(str) {
            return str.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
        }
    }(jQuery);
    'use strict';

    !function ($) {

        Foundation.Box = {
            ImNotTouchingYou: ImNotTouchingYou,
            GetDimensions: GetDimensions,
            GetOffsets: GetOffsets

            /**
             * Compares the dimensions of an element to a container and determines collision events with container.
             * @function
             * @param {jQuery} element - jQuery object to test for collisions.
             * @param {jQuery} parent - jQuery object to use as bounding container.
             * @param {Boolean} lrOnly - set to true to check left and right values only.
             * @param {Boolean} tbOnly - set to true to check top and bottom values only.
             * @default if no parent object passed, detects collisions with `window`.
             * @returns {Boolean} - true if collision free, false if a collision in any direction.
             */
        };function ImNotTouchingYou(element, parent, lrOnly, tbOnly) {
            var eleDims = GetDimensions(element),
                top,
                bottom,
                left,
                right;

            if (parent) {
                var parDims = GetDimensions(parent);

                bottom = eleDims.offset.top + eleDims.height <= parDims.height + parDims.offset.top;
                top = eleDims.offset.top >= parDims.offset.top;
                left = eleDims.offset.left >= parDims.offset.left;
                right = eleDims.offset.left + eleDims.width <= parDims.width + parDims.offset.left;
            } else {
                bottom = eleDims.offset.top + eleDims.height <= eleDims.windowDims.height + eleDims.windowDims.offset.top;
                top = eleDims.offset.top >= eleDims.windowDims.offset.top;
                left = eleDims.offset.left >= eleDims.windowDims.offset.left;
                right = eleDims.offset.left + eleDims.width <= eleDims.windowDims.width;
            }

            var allDirs = [bottom, top, left, right];

            if (lrOnly) {
                return left === right === true;
            }

            if (tbOnly) {
                return top === bottom === true;
            }

            return allDirs.indexOf(false) === -1;
        };

        /**
         * Uses native methods to return an object of dimension values.
         * @function
         * @param {jQuery || HTML} element - jQuery object or DOM element for which to get the dimensions. Can be any element other that document or window.
         * @returns {Object} - nested object of integer pixel values
         * TODO - if element is window, return only those values.
         */
        function GetDimensions(elem, test) {
            elem = elem.length ? elem[0] : elem;

            if (elem === window || elem === document) {
                throw new Error("I'm sorry, Dave. I'm afraid I can't do that.");
            }

            var rect = elem.getBoundingClientRect(),
                parRect = elem.parentNode.getBoundingClientRect(),
                winRect = document.body.getBoundingClientRect(),
                winY = window.pageYOffset,
                winX = window.pageXOffset;

            return {
                width: rect.width,
                height: rect.height,
                offset: {
                    top: rect.top + winY,
                    left: rect.left + winX
                },
                parentDims: {
                    width: parRect.width,
                    height: parRect.height,
                    offset: {
                        top: parRect.top + winY,
                        left: parRect.left + winX
                    }
                },
                windowDims: {
                    width: winRect.width,
                    height: winRect.height,
                    offset: {
                        top: winY,
                        left: winX
                    }
                }
            };
        }

        /**
         * Returns an object of top and left integer pixel values for dynamically rendered elements,
         * such as: Tooltip, Reveal, and Dropdown
         * @function
         * @param {jQuery} element - jQuery object for the element being positioned.
         * @param {jQuery} anchor - jQuery object for the element's anchor point.
         * @param {String} position - a string relating to the desired position of the element, relative to it's anchor
         * @param {Number} vOffset - integer pixel value of desired vertical separation between anchor and element.
         * @param {Number} hOffset - integer pixel value of desired horizontal separation between anchor and element.
         * @param {Boolean} isOverflow - if a collision event is detected, sets to true to default the element to full width - any desired offset.
         * TODO alter/rewrite to work with `em` values as well/instead of pixels
         */
        function GetOffsets(element, anchor, position, vOffset, hOffset, isOverflow) {
            var $eleDims = GetDimensions(element),
                $anchorDims = anchor ? GetDimensions(anchor) : null;

            switch (position) {
                case 'top':
                    return {
                        left: Foundation.rtl() ? $anchorDims.offset.left - $eleDims.width + $anchorDims.width : $anchorDims.offset.left,
                        top: $anchorDims.offset.top - ($eleDims.height + vOffset)
                    };
                    break;
                case 'left':
                    return {
                        left: $anchorDims.offset.left - ($eleDims.width + hOffset),
                        top: $anchorDims.offset.top
                    };
                    break;
                case 'right':
                    return {
                        left: $anchorDims.offset.left + $anchorDims.width + hOffset,
                        top: $anchorDims.offset.top
                    };
                    break;
                case 'center top':
                    return {
                        left: $anchorDims.offset.left + $anchorDims.width / 2 - $eleDims.width / 2,
                        top: $anchorDims.offset.top - ($eleDims.height + vOffset)
                    };
                    break;
                case 'center bottom':
                    return {
                        left: isOverflow ? hOffset : $anchorDims.offset.left + $anchorDims.width / 2 - $eleDims.width / 2,
                        top: $anchorDims.offset.top + $anchorDims.height + vOffset
                    };
                    break;
                case 'center left':
                    return {
                        left: $anchorDims.offset.left - ($eleDims.width + hOffset),
                        top: $anchorDims.offset.top + $anchorDims.height / 2 - $eleDims.height / 2
                    };
                    break;
                case 'center right':
                    return {
                        left: $anchorDims.offset.left + $anchorDims.width + hOffset + 1,
                        top: $anchorDims.offset.top + $anchorDims.height / 2 - $eleDims.height / 2
                    };
                    break;
                case 'center':
                    return {
                        left: $eleDims.windowDims.offset.left + $eleDims.windowDims.width / 2 - $eleDims.width / 2,
                        top: $eleDims.windowDims.offset.top + $eleDims.windowDims.height / 2 - $eleDims.height / 2
                    };
                    break;
                case 'reveal':
                    return {
                        left: ($eleDims.windowDims.width - $eleDims.width) / 2,
                        top: $eleDims.windowDims.offset.top + vOffset
                    };
                case 'reveal full':
                    return {
                        left: $eleDims.windowDims.offset.left,
                        top: $eleDims.windowDims.offset.top
                    };
                    break;
                case 'left bottom':
                    return {
                        left: $anchorDims.offset.left,
                        top: $anchorDims.offset.top + $anchorDims.height + vOffset
                    };
                    break;
                case 'right bottom':
                    return {
                        left: $anchorDims.offset.left + $anchorDims.width + hOffset - $eleDims.width,
                        top: $anchorDims.offset.top + $anchorDims.height + vOffset
                    };
                    break;
                default:
                    return {
                        left: Foundation.rtl() ? $anchorDims.offset.left - $eleDims.width + $anchorDims.width : $anchorDims.offset.left + hOffset,
                        top: $anchorDims.offset.top + $anchorDims.height + vOffset
                    };
            }
        }
    }(jQuery);

    'use strict';

    !function ($) {

        var keyCodes = {
            9: 'TAB',
            13: 'ENTER',
            27: 'ESCAPE',
            32: 'SPACE',
            37: 'ARROW_LEFT',
            38: 'ARROW_UP',
            39: 'ARROW_RIGHT',
            40: 'ARROW_DOWN'
        };

        var commands = {};

        var Keyboard = {
            keys: getKeyCodes(keyCodes),

            /**
             * Parses the (keyboard) event and returns a String that represents its key
             * Can be used like Foundation.parseKey(event) === Foundation.keys.SPACE
             * @param {Event} event - the event generated by the event handler
             * @return String key - String that represents the key pressed
             */
            parseKey: function parseKey(event) {
                var key = keyCodes[event.which || event.keyCode] || String.fromCharCode(event.which).toUpperCase();

                // Remove un-printable characters, e.g. for `fromCharCode` calls for CTRL only events
                key = key.replace(/\W+/, '');

                if (event.shiftKey) key = 'SHIFT_' + key;
                if (event.ctrlKey) key = 'CTRL_' + key;
                if (event.altKey) key = 'ALT_' + key;

                // Remove trailing underscore, in case only modifiers were used (e.g. only `CTRL_ALT`)
                key = key.replace(/_$/, '');

                return key;
            },


            /**
             * Handles the given (keyboard) event
             * @param {Event} event - the event generated by the event handler
             * @param {String} component - Foundation component's name, e.g. Slider or Reveal
             * @param {Objects} functions - collection of functions that are to be executed
             */
            handleKey: function handleKey(event, component, functions) {
                var commandList = commands[component],
                    keyCode = this.parseKey(event),
                    cmds,
                    command,
                    fn;

                if (!commandList) return console.warn('Component not defined!');

                if (typeof commandList.ltr === 'undefined') {
                    // this component does not differentiate between ltr and rtl
                    cmds = commandList; // use plain list
                } else {
                    // merge ltr and rtl: if document is rtl, rtl overwrites ltr and vice versa
                    if (Foundation.rtl()) cmds = $.extend({}, commandList.ltr, commandList.rtl);else cmds = $.extend({}, commandList.rtl, commandList.ltr);
                }
                command = cmds[keyCode];

                fn = functions[command];
                if (fn && typeof fn === 'function') {
                    // execute function  if exists
                    var returnValue = fn.apply();
                    if (functions.handled || typeof functions.handled === 'function') {
                        // execute function when event was handled
                        functions.handled(returnValue);
                    }
                } else {
                    if (functions.unhandled || typeof functions.unhandled === 'function') {
                        // execute function when event was not handled
                        functions.unhandled();
                    }
                }
            },


            /**
             * Finds all focusable elements within the given `$element`
             * @param {jQuery} $element - jQuery object to search within
             * @return {jQuery} $focusable - all focusable elements within `$element`
             */
            findFocusable: function findFocusable($element) {
                if (!$element) {
                    return false;
                }
                return $element.find('a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, *[tabindex], *[contenteditable]').filter(function () {
                    if (!$(this).is(':visible') || $(this).attr('tabindex') < 0) {
                        return false;
                    } //only have visible elements and those that have a tabindex greater or equal 0
                    return true;
                });
            },


            /**
             * Returns the component name name
             * @param {Object} component - Foundation component, e.g. Slider or Reveal
             * @return String componentName
             */

            register: function register(componentName, cmds) {
                commands[componentName] = cmds;
            },


            /**
             * Traps the focus in the given element.
             * @param  {jQuery} $element  jQuery object to trap the foucs into.
             */
            trapFocus: function trapFocus($element) {
                var $focusable = Foundation.Keyboard.findFocusable($element),
                    $firstFocusable = $focusable.eq(0),
                    $lastFocusable = $focusable.eq(-1);

                $element.on('keydown.zf.trapfocus', function (event) {
                    if (event.target === $lastFocusable[0] && Foundation.Keyboard.parseKey(event) === 'TAB') {
                        event.preventDefault();
                        $firstFocusable.focus();
                    } else if (event.target === $firstFocusable[0] && Foundation.Keyboard.parseKey(event) === 'SHIFT_TAB') {
                        event.preventDefault();
                        $lastFocusable.focus();
                    }
                });
            },

            /**
             * Releases the trapped focus from the given element.
             * @param  {jQuery} $element  jQuery object to release the focus for.
             */
            releaseFocus: function releaseFocus($element) {
                $element.off('keydown.zf.trapfocus');
            }
        };

        /*
   * Constants for easier comparing.
   * Can be used like Foundation.parseKey(event) === Foundation.keys.SPACE
   */
        function getKeyCodes(kcs) {
            var k = {};
            for (var kc in kcs) {
                k[kcs[kc]] = kcs[kc];
            }return k;
        }

        Foundation.Keyboard = Keyboard;
    }(jQuery);
    'use strict';

    var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

    !function ($) {

        // Default set of media queries
        var defaultQueries = {
            'default': 'only screen',
            landscape: 'only screen and (orientation: landscape)',
            portrait: 'only screen and (orientation: portrait)',
            retina: 'only screen and (-webkit-min-device-pixel-ratio: 2),' + 'only screen and (min--moz-device-pixel-ratio: 2),' + 'only screen and (-o-min-device-pixel-ratio: 2/1),' + 'only screen and (min-device-pixel-ratio: 2),' + 'only screen and (min-resolution: 192dpi),' + 'only screen and (min-resolution: 2dppx)'
        };

        var MediaQuery = {
            queries: [],

            current: '',

            /**
             * Initializes the media query helper, by extracting the breakpoint list from the CSS and activating the breakpoint watcher.
             * @function
             * @private
             */
            _init: function _init() {
                var self = this;
                var extractedStyles = $('.foundation-mq').css('font-family');
                var namedQueries;

                namedQueries = parseStyleToObject(extractedStyles);

                for (var key in namedQueries) {
                    if (namedQueries.hasOwnProperty(key)) {
                        self.queries.push({
                            name: key,
                            value: 'only screen and (min-width: ' + namedQueries[key] + ')'
                        });
                    }
                }

                this.current = this._getCurrentSize();

                this._watcher();
            },


            /**
             * Checks if the screen is at least as wide as a breakpoint.
             * @function
             * @param {String} size - Name of the breakpoint to check.
             * @returns {Boolean} `true` if the breakpoint matches, `false` if it's smaller.
             */
            atLeast: function atLeast(size) {
                var query = this.get(size);

                if (query) {
                    return window.matchMedia(query).matches;
                }

                return false;
            },


            /**
             * Checks if the screen matches to a breakpoint.
             * @function
             * @param {String} size - Name of the breakpoint to check, either 'small only' or 'small'. Omitting 'only' falls back to using atLeast() method.
             * @returns {Boolean} `true` if the breakpoint matches, `false` if it does not.
             */
            is: function is(size) {
                size = size.trim().split(' ');
                if (size.length > 1 && size[1] === 'only') {
                    if (size[0] === this._getCurrentSize()) return true;
                } else {
                    return this.atLeast(size[0]);
                }
                return false;
            },


            /**
             * Gets the media query of a breakpoint.
             * @function
             * @param {String} size - Name of the breakpoint to get.
             * @returns {String|null} - The media query of the breakpoint, or `null` if the breakpoint doesn't exist.
             */
            get: function get(size) {
                for (var i in this.queries) {
                    if (this.queries.hasOwnProperty(i)) {
                        var query = this.queries[i];
                        if (size === query.name) return query.value;
                    }
                }

                return null;
            },


            /**
             * Gets the current breakpoint name by testing every breakpoint and returning the last one to match (the biggest one).
             * @function
             * @private
             * @returns {String} Name of the current breakpoint.
             */
            _getCurrentSize: function _getCurrentSize() {
                var matched;

                for (var i = 0; i < this.queries.length; i++) {
                    var query = this.queries[i];

                    if (window.matchMedia(query.value).matches) {
                        matched = query;
                    }
                }

                if ((typeof matched === 'undefined' ? 'undefined' : _typeof(matched)) === 'object') {
                    return matched.name;
                } else {
                    return matched;
                }
            },


            /**
             * Activates the breakpoint watcher, which fires an event on the window whenever the breakpoint changes.
             * @function
             * @private
             */
            _watcher: function _watcher() {
                var _this = this;

                $(window).on('resize.zf.mediaquery', function () {
                    var newSize = _this._getCurrentSize(),
                        currentSize = _this.current;

                    if (newSize !== currentSize) {
                        // Change the current media query
                        _this.current = newSize;

                        // Broadcast the media query change on the window
                        $(window).trigger('changed.zf.mediaquery', [newSize, currentSize]);
                    }
                });
            }
        };

        Foundation.MediaQuery = MediaQuery;

        // matchMedia() polyfill - Test a CSS media type/query in JS.
        // Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas, David Knight. Dual MIT/BSD license
        window.matchMedia || (window.matchMedia = function () {
            'use strict';

            // For browsers that support matchMedium api such as IE 9 and webkit

            var styleMedia = window.styleMedia || window.media;

            // For those that don't support matchMedium
            if (!styleMedia) {
                var style = document.createElement('style'),
                    script = document.getElementsByTagName('script')[0],
                    info = null;

                style.type = 'text/css';
                style.id = 'matchmediajs-test';

                script && script.parentNode && script.parentNode.insertBefore(style, script);

                // 'style.currentStyle' is used by IE <= 8 and 'window.getComputedStyle' for all other browsers
                info = 'getComputedStyle' in window && window.getComputedStyle(style, null) || style.currentStyle;

                styleMedia = {
                    matchMedium: function matchMedium(media) {
                        var text = '@media ' + media + '{ #matchmediajs-test { width: 1px; } }';

                        // 'style.styleSheet' is used by IE <= 8 and 'style.textContent' for all other browsers
                        if (style.styleSheet) {
                            style.styleSheet.cssText = text;
                        } else {
                            style.textContent = text;
                        }

                        // Test if media query is true or false
                        return info.width === '1px';
                    }
                };
            }

            return function (media) {
                return {
                    matches: styleMedia.matchMedium(media || 'all'),
                    media: media || 'all'
                };
            };
        }());

        // Thank you: https://github.com/sindresorhus/query-string
        function parseStyleToObject(str) {
            var styleObject = {};

            if (typeof str !== 'string') {
                return styleObject;
            }

            str = str.trim().slice(1, -1); // browsers re-quote string style values

            if (!str) {
                return styleObject;
            }

            styleObject = str.split('&').reduce(function (ret, param) {
                var parts = param.replace(/\+/g, ' ').split('=');
                var key = parts[0];
                var val = parts[1];
                key = decodeURIComponent(key);

                // missing `=` should be `null`:
                // http://w3.org/TR/2012/WD-url-20120524/#collect-url-parameters
                val = val === undefined ? null : decodeURIComponent(val);

                if (!ret.hasOwnProperty(key)) {
                    ret[key] = val;
                } else if (Array.isArray(ret[key])) {
                    ret[key].push(val);
                } else {
                    ret[key] = [ret[key], val];
                }
                return ret;
            }, {});

            return styleObject;
        }

        Foundation.MediaQuery = MediaQuery;
    }(jQuery);
    'use strict';

    !function ($) {

        /**
         * Motion module.
         * @module foundation.motion
         */

        var initClasses = ['mui-enter', 'mui-leave'];
        var activeClasses = ['mui-enter-active', 'mui-leave-active'];

        var Motion = {
            animateIn: function animateIn(element, animation, cb) {
                animate(true, element, animation, cb);
            },

            animateOut: function animateOut(element, animation, cb) {
                animate(false, element, animation, cb);
            }
        };

        function Move(duration, elem, fn) {
            var anim,
                prog,
                start = null;
            // console.log('called');

            if (duration === 0) {
                fn.apply(elem);
                elem.trigger('finished.zf.animate', [elem]).triggerHandler('finished.zf.animate', [elem]);
                return;
            }

            function move(ts) {
                if (!start) start = ts;
                // console.log(start, ts);
                prog = ts - start;
                fn.apply(elem);

                if (prog < duration) {
                    anim = window.requestAnimationFrame(move, elem);
                } else {
                    window.cancelAnimationFrame(anim);
                    elem.trigger('finished.zf.animate', [elem]).triggerHandler('finished.zf.animate', [elem]);
                }
            }
            anim = window.requestAnimationFrame(move);
        }

        /**
         * Animates an element in or out using a CSS transition class.
         * @function
         * @private
         * @param {Boolean} isIn - Defines if the animation is in or out.
         * @param {Object} element - jQuery or HTML object to animate.
         * @param {String} animation - CSS class to use.
         * @param {Function} cb - Callback to run when animation is finished.
         */
        function animate(isIn, element, animation, cb) {
            element = $(element).eq(0);

            if (!element.length) return;

            var initClass = isIn ? initClasses[0] : initClasses[1];
            var activeClass = isIn ? activeClasses[0] : activeClasses[1];

            // Set up the animation
            reset();

            element.addClass(animation).css('transition', 'none');

            requestAnimationFrame(function () {
                element.addClass(initClass);
                if (isIn) element.show();
            });

            // Start the animation
            requestAnimationFrame(function () {
                element[0].offsetWidth;
                element.css('transition', '').addClass(activeClass);
            });

            // Clean up the animation when it finishes
            element.one(Foundation.transitionend(element), finish);

            // Hides the element (for out animations), resets the element, and runs a callback
            function finish() {
                if (!isIn) element.hide();
                reset();
                if (cb) cb.apply(element);
            }

            // Resets transitions and removes motion-specific classes
            function reset() {
                element[0].style.transitionDuration = 0;
                element.removeClass(initClass + ' ' + activeClass + ' ' + animation);
            }
        }

        Foundation.Move = Move;
        Foundation.Motion = Motion;
    }(jQuery);
    'use strict';

    !function ($) {

        var Nest = {
            Feather: function Feather(menu) {
                var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'zf';

                menu.attr('role', 'menubar');

                var items = menu.find('li').attr({ 'role': 'menuitem' }),
                    subMenuClass = 'is-' + type + '-submenu',
                    subItemClass = subMenuClass + '-item',
                    hasSubClass = 'is-' + type + '-submenu-parent';

                items.each(function () {
                    var $item = $(this),
                        $sub = $item.children('ul');

                    if ($sub.length) {
                        $item.addClass(hasSubClass).attr({
                            'aria-haspopup': true,
                            'aria-label': $item.children('a:first').text()
                        });
                        // Note:  Drilldowns behave differently in how they hide, and so need
                        // additional attributes.  We should look if this possibly over-generalized
                        // utility (Nest) is appropriate when we rework menus in 6.4
                        if (type === 'drilldown') {
                            $item.attr({ 'aria-expanded': false });
                        }

                        $sub.addClass('submenu ' + subMenuClass).attr({
                            'data-submenu': '',
                            'role': 'menu'
                        });
                        if (type === 'drilldown') {
                            $sub.attr({ 'aria-hidden': true });
                        }
                    }

                    if ($item.parent('[data-submenu]').length) {
                        $item.addClass('is-submenu-item ' + subItemClass);
                    }
                });

                return;
            },
            Burn: function Burn(menu, type) {
                var //items = menu.find('li'),
                    subMenuClass = 'is-' + type + '-submenu',
                    subItemClass = subMenuClass + '-item',
                    hasSubClass = 'is-' + type + '-submenu-parent';

                menu.find('>li, .menu, .menu > li').removeClass(subMenuClass + ' ' + subItemClass + ' ' + hasSubClass + ' is-submenu-item submenu is-active').removeAttr('data-submenu').css('display', '');
            }
        };

        Foundation.Nest = Nest;
    }(jQuery);
    'use strict';

    !function ($) {

        function Timer(elem, options, cb) {
            var _this = this,
                duration = options.duration,
                //options is an object for easily adding features later.
                nameSpace = Object.keys(elem.data())[0] || 'timer',
                remain = -1,
                start,
                timer;

            this.isPaused = false;

            this.restart = function () {
                remain = -1;
                clearTimeout(timer);
                this.start();
            };

            this.start = function () {
                this.isPaused = false;
                // if(!elem.data('paused')){ return false; }//maybe implement this sanity check if used for other things.
                clearTimeout(timer);
                remain = remain <= 0 ? duration : remain;
                elem.data('paused', false);
                start = Date.now();
                timer = setTimeout(function () {
                    if (options.infinite) {
                        _this.restart(); //rerun the timer.
                    }
                    if (cb && typeof cb === 'function') {
                        cb();
                    }
                }, remain);
                elem.trigger('timerstart.zf.' + nameSpace);
            };

            this.pause = function () {
                this.isPaused = true;
                //if(elem.data('paused')){ return false; }//maybe implement this sanity check if used for other things.
                clearTimeout(timer);
                elem.data('paused', true);
                var end = Date.now();
                remain = remain - (end - start);
                elem.trigger('timerpaused.zf.' + nameSpace);
            };
        }

        /**
         * Runs a callback function when images are fully loaded.
         * @param {Object} images - Image(s) to check if loaded.
         * @param {Func} callback - Function to execute when image is fully loaded.
         */
        function onImagesLoaded(images, callback) {
            var self = this,
                unloaded = images.length;

            if (unloaded === 0) {
                callback();
            }

            images.each(function () {
                // Check if image is loaded
                if (this.complete || this.readyState === 4 || this.readyState === 'complete') {
                    singleImageLoaded();
                }
                // Force load the image
                else {
                    // fix for IE. See https://css-tricks.com/snippets/jquery/fixing-load-in-ie-for-cached-images/
                    var src = $(this).attr('src');
                    $(this).attr('src', src + (src.indexOf('?') >= 0 ? '&' : '?') + new Date().getTime());
                    $(this).one('load', function () {
                        singleImageLoaded();
                    });
                }
            });

            function singleImageLoaded() {
                unloaded--;
                if (unloaded === 0) {
                    callback();
                }
            }
        }

        Foundation.Timer = Timer;
        Foundation.onImagesLoaded = onImagesLoaded;
    }(jQuery);
    'use strict';

    (function ($) {

        $.spotSwipe = {
            version: '1.0.0',
            enabled: 'ontouchstart' in document.documentElement,
            preventDefault: false,
            moveThreshold: 75,
            timeThreshold: 200
        };

        var startPosX,
            startPosY,
            startTime,
            elapsedTime,
            isMoving = false;

        function onTouchEnd() {
            //  alert(this);
            this.removeEventListener('touchmove', onTouchMove);
            this.removeEventListener('touchend', onTouchEnd);
            isMoving = false;
        }

        function onTouchMove(e) {
            if ($.spotSwipe.preventDefault) {
                e.preventDefault();
            }
            if (isMoving) {
                var x = e.touches[0].pageX;
                var y = e.touches[0].pageY;
                var dx = startPosX - x;
                var dy = startPosY - y;
                var dir;
                elapsedTime = new Date().getTime() - startTime;
                if (Math.abs(dx) >= $.spotSwipe.moveThreshold && elapsedTime <= $.spotSwipe.timeThreshold) {
                    dir = dx > 0 ? 'left' : 'right';
                }
                // else if(Math.abs(dy) >= $.spotSwipe.moveThreshold && elapsedTime <= $.spotSwipe.timeThreshold) {
                //   dir = dy > 0 ? 'down' : 'up';
                // }
                if (dir) {
                    e.preventDefault();
                    onTouchEnd.call(this);
                    $(this).trigger('swipe', dir).trigger('swipe' + dir);
                }
            }
        }

        function onTouchStart(e) {
            if (e.touches.length == 1) {
                startPosX = e.touches[0].pageX;
                startPosY = e.touches[0].pageY;
                isMoving = true;
                startTime = new Date().getTime();
                this.addEventListener('touchmove', onTouchMove, false);
                this.addEventListener('touchend', onTouchEnd, false);
            }
        }

        function init() {
            this.addEventListener && this.addEventListener('touchstart', onTouchStart, false);
        }

        function teardown() {
            this.removeEventListener('touchstart', onTouchStart);
        }

        $.event.special.swipe = { setup: init };

        $.each(['left', 'up', 'down', 'right'], function () {
            $.event.special['swipe' + this] = { setup: function setup() {
                    $(this).on('swipe', $.noop);
                } };
        });
    })(jQuery);

    !function ($) {
        $.fn.addTouch = function () {
            this.each(function (i, el) {
                $(el).bind('touchstart touchmove touchend touchcancel', function () {
                    //we pass the original event object because the jQuery event
                    //object is normalized to w3c specs and does not provide the TouchList
                    handleTouch(event);
                });
            });

            var handleTouch = function handleTouch(event) {
                var touches = event.changedTouches,
                    first = touches[0],
                    eventTypes = {
                        touchstart: 'mousedown',
                        touchmove: 'mousemove',
                        touchend: 'mouseup'
                    },
                    type = eventTypes[event.type],
                    simulatedEvent;

                if ('MouseEvent' in window && typeof window.MouseEvent === 'function') {
                    simulatedEvent = new window.MouseEvent(type, {
                        'bubbles': true,
                        'cancelable': true,
                        'screenX': first.screenX,
                        'screenY': first.screenY,
                        'clientX': first.clientX,
                        'clientY': first.clientY
                    });
                } else {
                    simulatedEvent = document.createEvent('MouseEvent');
                    simulatedEvent.initMouseEvent(type, true, true, window, 1, first.screenX, first.screenY, first.clientX, first.clientY, false, false, false, false, 0 /*left*/, null);
                }
                first.target.dispatchEvent(simulatedEvent);
            };
        };
    }(jQuery);

    'use strict';

    var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

    !function ($) {

        var MutationObserver = function () {
            var prefixes = ['WebKit', 'Moz', 'O', 'Ms', ''];
            for (var i = 0; i < prefixes.length; i++) {
                if (prefixes[i] + 'MutationObserver' in window) {
                    return window[prefixes[i] + 'MutationObserver'];
                }
            }
            return false;
        }();

        var triggers = function triggers(el, type) {
            el.data(type).split(' ').forEach(function (id) {
                $('#' + id)[type === 'close' ? 'trigger' : 'triggerHandler'](type + '.zf.trigger', [el]);
            });
        };
        // Elements with [data-open] will reveal a plugin that supports it when clicked.
        $(document).on('click.zf.trigger', '[data-open]', function () {
            triggers($(this), 'open');
        });

        // Elements with [data-close] will close a plugin that supports it when clicked.
        // If used without a value on [data-close], the event will bubble, allowing it to close a parent component.
        $(document).on('click.zf.trigger', '[data-close]', function () {
            var id = $(this).data('close');
            if (id) {
                triggers($(this), 'close');
            } else {
                $(this).trigger('close.zf.trigger');
            }
        });

        // Elements with [data-toggle] will toggle a plugin that supports it when clicked.
        $(document).on('click.zf.trigger', '[data-toggle]', function () {
            var id = $(this).data('toggle');
            if (id) {
                triggers($(this), 'toggle');
            } else {
                $(this).trigger('toggle.zf.trigger');
            }
        });

        // Elements with [data-closable] will respond to close.zf.trigger events.
        $(document).on('close.zf.trigger', '[data-closable]', function (e) {
            e.stopPropagation();
            var animation = $(this).data('closable');

            if (animation !== '') {
                Foundation.Motion.animateOut($(this), animation, function () {
                    $(this).trigger('closed.zf');
                });
            } else {
                $(this).fadeOut().trigger('closed.zf');
            }
        });

        $(document).on('focus.zf.trigger blur.zf.trigger', '[data-toggle-focus]', function () {
            var id = $(this).data('toggle-focus');
            $('#' + id).triggerHandler('toggle.zf.trigger', [$(this)]);
        });

        /**
         * Fires once after all other scripts have loaded
         * @function
         * @private
         */
        $(window).on('load', function () {
            checkListeners();
        });

        function checkListeners() {
            eventsListener();
            resizeListener();
            scrollListener();
            closemeListener();
        }

        //******** only fires this function once on load, if there's something to watch ********
        function closemeListener(pluginName) {
            var yetiBoxes = $('[data-yeti-box]'),
                plugNames = ['dropdown', 'tooltip', 'reveal'];

            if (pluginName) {
                if (typeof pluginName === 'string') {
                    plugNames.push(pluginName);
                } else if ((typeof pluginName === 'undefined' ? 'undefined' : _typeof(pluginName)) === 'object' && typeof pluginName[0] === 'string') {
                    plugNames.concat(pluginName);
                } else {
                    console.error('Plugin names must be strings');
                }
            }
            if (yetiBoxes.length) {
                var listeners = plugNames.map(function (name) {
                    return 'closeme.zf.' + name;
                }).join(' ');

                $(window).off(listeners).on(listeners, function (e, pluginId) {
                    var plugin = e.namespace.split('.')[0];
                    var plugins = $('[data-' + plugin + ']').not('[data-yeti-box="' + pluginId + '"]');

                    plugins.each(function () {
                        var _this = $(this);

                        _this.triggerHandler('close.zf.trigger', [_this]);
                    });
                });
            }
        }

        function resizeListener(debounce) {
            var timer = void 0,
                $nodes = $('[data-resize]');
            if ($nodes.length) {
                $(window).off('resize.zf.trigger').on('resize.zf.trigger', function (e) {
                    if (timer) {
                        clearTimeout(timer);
                    }

                    timer = setTimeout(function () {

                        if (!MutationObserver) {
                            //fallback for IE 9
                            $nodes.each(function () {
                                $(this).triggerHandler('resizeme.zf.trigger');
                            });
                        }
                        //trigger all listening elements and signal a resize event
                        $nodes.attr('data-events', "resize");
                    }, debounce || 10); //default time to emit resize event
                });
            }
        }

        function scrollListener(debounce) {
            var timer = void 0,
                $nodes = $('[data-scroll]');
            if ($nodes.length) {
                $(window).off('scroll.zf.trigger').on('scroll.zf.trigger', function (e) {
                    if (timer) {
                        clearTimeout(timer);
                    }

                    timer = setTimeout(function () {

                        if (!MutationObserver) {
                            //fallback for IE 9
                            $nodes.each(function () {
                                $(this).triggerHandler('scrollme.zf.trigger');
                            });
                        }
                        //trigger all listening elements and signal a scroll event
                        $nodes.attr('data-events', "scroll");
                    }, debounce || 10); //default time to emit scroll event
                });
            }
        }

        function eventsListener() {
            if (!MutationObserver) {
                return false;
            }
            var nodes = document.querySelectorAll('[data-resize], [data-scroll], [data-mutate]');

            //element callback
            var listeningElementsMutation = function listeningElementsMutation(mutationRecordsList) {
                var $target = $(mutationRecordsList[0].target);

                //trigger the event handler for the element depending on type
                switch (mutationRecordsList[0].type) {

                    case "attributes":
                        if ($target.attr("data-events") === "scroll" && mutationRecordsList[0].attributeName === "data-events") {
                            $target.triggerHandler('scrollme.zf.trigger', [$target, window.pageYOffset]);
                        }
                        if ($target.attr("data-events") === "resize" && mutationRecordsList[0].attributeName === "data-events") {
                            $target.triggerHandler('resizeme.zf.trigger', [$target]);
                        }
                        if (mutationRecordsList[0].attributeName === "style") {
                            $target.closest("[data-mutate]").attr("data-events", "mutate");
                            $target.closest("[data-mutate]").triggerHandler('mutateme.zf.trigger', [$target.closest("[data-mutate]")]);
                        }
                        break;

                    case "childList":
                        $target.closest("[data-mutate]").attr("data-events", "mutate");
                        $target.closest("[data-mutate]").triggerHandler('mutateme.zf.trigger', [$target.closest("[data-mutate]")]);
                        break;

                    default:
                        return false;
                    //nothing
                }
            };

            if (nodes.length) {
                //for each element that needs to listen for resizing, scrolling, or mutation add a single observer
                for (var i = 0; i <= nodes.length - 1; i++) {
                    var elementObserver = new MutationObserver(listeningElementsMutation);
                    elementObserver.observe(nodes[i], { attributes: true, childList: true, characterData: false, subtree: true, attributeFilter: ["data-events", "style"] });
                }
            }
        }

        // ------------------------------------

        // [PH]
        // Foundation.CheckWatchers = checkWatchers;
        Foundation.IHearYou = checkListeners;
        // Foundation.ISeeYou = scrollListener;
        // Foundation.IFeelYou = closemeListener;
    }(jQuery);
    'use strict';

    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

    !function ($) {

        /**
         * Accordion module.
         * @module foundation.accordion
         * @requires foundation.util.keyboard
         * @requires foundation.util.motion
         */

        var Accordion = function () {
            /**
             * Creates a new instance of an accordion.
             * @class
             * @fires Accordion#init
             * @param {jQuery} element - jQuery object to make into an accordion.
             * @param {Object} options - a plain object with settings to override the default options.
             */
            function Accordion(element, options) {
                _classCallCheck(this, Accordion);

                this.$element = element;
                this.options = $.extend({}, Accordion.defaults, this.$element.data(), options);

                this._init();

                Foundation.registerPlugin(this, 'Accordion');
                Foundation.Keyboard.register('Accordion', {
                    'ENTER': 'toggle',
                    'SPACE': 'toggle',
                    'ARROW_DOWN': 'next',
                    'ARROW_UP': 'previous'
                });
            }

            /**
             * Initializes the accordion by animating the preset active pane(s).
             * @private
             */


            _createClass(Accordion, [{
                key: '_init',
                value: function _init() {
                    var _this2 = this;

                    this.$element.attr('role', 'tablist');
                    this.$tabs = this.$element.children('[data-accordion-item]');

                    this.$tabs.each(function (idx, el) {
                        var $el = $(el),
                            $content = $el.children('[data-tab-content]'),
                            id = $content[0].id || Foundation.GetYoDigits(6, 'accordion'),
                            linkId = el.id || id + '-label';

                        $el.find('a:first').attr({
                            'aria-controls': id,
                            'role': 'tab',
                            'id': linkId,
                            'aria-expanded': false,
                            'aria-selected': false
                        });

                        $content.attr({ 'role': 'tabpanel', 'aria-labelledby': linkId, 'aria-hidden': true, 'id': id });
                    });
                    var $initActive = this.$element.find('.is-active').children('[data-tab-content]');
                    this.firstTimeInit = true;
                    if ($initActive.length) {
                        this.down($initActive, this.firstTimeInit);
                        this.firstTimeInit = false;
                    }

                    this._checkDeepLink = function () {
                        var anchor = window.location.hash;
                        //need a hash and a relevant anchor in this tabset
                        if (anchor.length) {
                            var $link = _this2.$element.find('[href$="' + anchor + '"]'),
                                $anchor = $(anchor);

                            if ($link.length && $anchor) {
                                if (!$link.parent('[data-accordion-item]').hasClass('is-active')) {
                                    _this2.down($anchor, _this2.firstTimeInit);
                                    _this2.firstTimeInit = false;
                                };

                                //roll up a little to show the titles
                                if (_this2.options.deepLinkSmudge) {
                                    var _this = _this2;
                                    $(window).load(function () {
                                        var offset = _this.$element.offset();
                                        $('html, body').animate({ scrollTop: offset.top }, _this.options.deepLinkSmudgeDelay);
                                    });
                                }

                                /**
                                 * Fires when the zplugin has deeplinked at pageload
                                 * @event Accordion#deeplink
                                 */
                                _this2.$element.trigger('deeplink.zf.accordion', [$link, $anchor]);
                            }
                        }
                    };

                    //use browser to open a tab, if it exists in this tabset
                    if (this.options.deepLink) {
                        this._checkDeepLink();
                    }

                    this._events();
                }

                /**
                 * Adds event handlers for items within the accordion.
                 * @private
                 */

            }, {
                key: '_events',
                value: function _events() {
                    var _this = this;

                    this.$tabs.each(function () {
                        var $elem = $(this);
                        var $tabContent = $elem.children('[data-tab-content]');
                        if ($tabContent.length) {
                            $elem.children('a').off('click.zf.accordion keydown.zf.accordion').on('click.zf.accordion', function (e) {
                                e.preventDefault();
                                _this.toggle($tabContent);
                            }).on('keydown.zf.accordion', function (e) {
                                Foundation.Keyboard.handleKey(e, 'Accordion', {
                                    toggle: function toggle() {
                                        _this.toggle($tabContent);
                                    },
                                    next: function next() {
                                        var $a = $elem.next().find('a').focus();
                                        if (!_this.options.multiExpand) {
                                            $a.trigger('click.zf.accordion');
                                        }
                                    },
                                    previous: function previous() {
                                        var $a = $elem.prev().find('a').focus();
                                        if (!_this.options.multiExpand) {
                                            $a.trigger('click.zf.accordion');
                                        }
                                    },
                                    handled: function handled() {
                                        e.preventDefault();
                                        e.stopPropagation();
                                    }
                                });
                            });
                        }
                    });
                    if (this.options.deepLink) {
                        $(window).on('popstate', this._checkDeepLink);
                    }
                }

                /**
                 * Toggles the selected content pane's open/close state.
                 * @param {jQuery} $target - jQuery object of the pane to toggle (`.accordion-content`).
                 * @function
                 */

            }, {
                key: 'toggle',
                value: function toggle($target) {
                    if ($target.parent().hasClass('is-active')) {
                        this.up($target);
                    } else {
                        this.down($target);
                    }
                    //either replace or update browser history
                    if (this.options.deepLink) {
                        var anchor = $target.prev('a').attr('href');

                        if (this.options.updateHistory) {
                            history.pushState({}, '', anchor);
                        } else {
                            history.replaceState({}, '', anchor);
                        }
                    }
                }

                /**
                 * Opens the accordion tab defined by `$target`.
                 * @param {jQuery} $target - Accordion pane to open (`.accordion-content`).
                 * @param {Boolean} firstTime - flag to determine if reflow should happen.
                 * @fires Accordion#down
                 * @function
                 */

            }, {
                key: 'down',
                value: function down($target, firstTime) {
                    var _this3 = this;

                    $target.attr('aria-hidden', false).parent('[data-tab-content]').addBack().parent().addClass('is-active');

                    if (!this.options.multiExpand && !firstTime) {
                        var $currentActive = this.$element.children('.is-active').children('[data-tab-content]');
                        if ($currentActive.length) {
                            this.up($currentActive.not($target));
                        }
                    }

                    $target.slideDown(this.options.slideSpeed, function () {
                        /**
                         * Fires when the tab is done opening.
                         * @event Accordion#down
                         */
                        _this3.$element.trigger('down.zf.accordion', [$target]);
                    });

                    $('#' + $target.attr('aria-labelledby')).attr({
                        'aria-expanded': true,
                        'aria-selected': true
                    });
                }

                /**
                 * Closes the tab defined by `$target`.
                 * @param {jQuery} $target - Accordion tab to close (`.accordion-content`).
                 * @fires Accordion#up
                 * @function
                 */

            }, {
                key: 'up',
                value: function up($target) {
                    var $aunts = $target.parent().siblings(),
                        _this = this;

                    if (!this.options.allowAllClosed && !$aunts.hasClass('is-active') || !$target.parent().hasClass('is-active')) {
                        return;
                    }

                    // Foundation.Move(this.options.slideSpeed, $target, function(){
                    $target.slideUp(_this.options.slideSpeed, function () {
                        /**
                         * Fires when the tab is done collapsing up.
                         * @event Accordion#up
                         */
                        _this.$element.trigger('up.zf.accordion', [$target]);
                    });
                    // });

                    $target.attr('aria-hidden', true).parent().removeClass('is-active');

                    $('#' + $target.attr('aria-labelledby')).attr({
                        'aria-expanded': false,
                        'aria-selected': false
                    });
                }

                /**
                 * Destroys an instance of an accordion.
                 * @fires Accordion#destroyed
                 * @function
                 */

            }, {
                key: 'destroy',
                value: function destroy() {
                    this.$element.find('[data-tab-content]').stop(true).slideUp(0).css('display', '');
                    this.$element.find('a').off('.zf.accordion');
                    if (this.options.deepLink) {
                        $(window).off('popstate', this._checkDeepLink);
                    }

                    Foundation.unregisterPlugin(this);
                }
            }]);

            return Accordion;
        }();

        Accordion.defaults = {
            /**
             * Amount of time to animate the opening of an accordion pane.
             * @option
             * @type {number}
             * @default 250
             */
            slideSpeed: 250,
            /**
             * Allow the accordion to have multiple open panes.
             * @option
             * @type {boolean}
             * @default false
             */
            multiExpand: false,
            /**
             * Allow the accordion to close all panes.
             * @option
             * @type {boolean}
             * @default false
             */
            allowAllClosed: false,
            /**
             * Allows the window to scroll to content of pane specified by hash anchor
             * @option
             * @type {boolean}
             * @default false
             */
            deepLink: false,

            /**
             * Adjust the deep link scroll to make sure the top of the accordion panel is visible
             * @option
             * @type {boolean}
             * @default false
             */
            deepLinkSmudge: false,

            /**
             * Animation time (ms) for the deep link adjustment
             * @option
             * @type {number}
             * @default 300
             */
            deepLinkSmudgeDelay: 300,

            /**
             * Update the browser history with the open accordion
             * @option
             * @type {boolean}
             * @default false
             */
            updateHistory: false
        };

        // Window exports
        Foundation.plugin(Accordion, 'Accordion');
    }(jQuery);
    'use strict';

    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

    !function ($) {

        /**
         * AccordionMenu module.
         * @module foundation.accordionMenu
         * @requires foundation.util.keyboard
         * @requires foundation.util.motion
         * @requires foundation.util.nest
         */

        var AccordionMenu = function () {
            /**
             * Creates a new instance of an accordion menu.
             * @class
             * @fires AccordionMenu#init
             * @param {jQuery} element - jQuery object to make into an accordion menu.
             * @param {Object} options - Overrides to the default plugin settings.
             */
            function AccordionMenu(element, options) {
                _classCallCheck(this, AccordionMenu);

                this.$element = element;
                this.options = $.extend({}, AccordionMenu.defaults, this.$element.data(), options);

                Foundation.Nest.Feather(this.$element, 'accordion');

                this._init();

                Foundation.registerPlugin(this, 'AccordionMenu');
                Foundation.Keyboard.register('AccordionMenu', {
                    'ENTER': 'toggle',
                    'SPACE': 'toggle',
                    'ARROW_RIGHT': 'open',
                    'ARROW_UP': 'up',
                    'ARROW_DOWN': 'down',
                    'ARROW_LEFT': 'close',
                    'ESCAPE': 'closeAll'
                });
            }

            /**
             * Initializes the accordion menu by hiding all nested menus.
             * @private
             */


            _createClass(AccordionMenu, [{
                key: '_init',
                value: function _init() {
                    this.$element.find('[data-submenu]').not('.is-active').slideUp(0); //.find('a').css('padding-left', '1rem');
                    this.$element.attr({
                        'role': 'menu',
                        'aria-multiselectable': this.options.multiOpen
                    });

                    this.$menuLinks = this.$element.find('.is-accordion-submenu-parent');
                    this.$menuLinks.each(function () {
                        var linkId = this.id || Foundation.GetYoDigits(6, 'acc-menu-link'),
                            $elem = $(this),
                            $sub = $elem.children('[data-submenu]'),
                            subId = $sub[0].id || Foundation.GetYoDigits(6, 'acc-menu'),
                            isActive = $sub.hasClass('is-active');
                        $elem.attr({
                            'aria-controls': subId,
                            'aria-expanded': isActive,
                            'role': 'menuitem',
                            'id': linkId
                        });
                        $sub.attr({
                            'aria-labelledby': linkId,
                            'aria-hidden': !isActive,
                            'role': 'menu',
                            'id': subId
                        });
                    });
                    var initPanes = this.$element.find('.is-active');
                    if (initPanes.length) {
                        var _this = this;
                        initPanes.each(function () {
                            _this.down($(this));
                        });
                    }
                    this._events();
                }

                /**
                 * Adds event handlers for items within the menu.
                 * @private
                 */

            }, {
                key: '_events',
                value: function _events() {
                    var _this = this;

                    this.$element.find('li').each(function () {
                        var $submenu = $(this).children('[data-submenu]');

                        if ($submenu.length) {
                            $(this).children('a').off('click.zf.accordionMenu').on('click.zf.accordionMenu', function (e) {
                                e.preventDefault();

                                _this.toggle($submenu);
                            });
                        }
                    }).on('keydown.zf.accordionmenu', function (e) {
                        var $element = $(this),
                            $elements = $element.parent('ul').children('li'),
                            $prevElement,
                            $nextElement,
                            $target = $element.children('[data-submenu]');

                        $elements.each(function (i) {
                            if ($(this).is($element)) {
                                $prevElement = $elements.eq(Math.max(0, i - 1)).find('a').first();
                                $nextElement = $elements.eq(Math.min(i + 1, $elements.length - 1)).find('a').first();

                                if ($(this).children('[data-submenu]:visible').length) {
                                    // has open sub menu
                                    $nextElement = $element.find('li:first-child').find('a').first();
                                }
                                if ($(this).is(':first-child')) {
                                    // is first element of sub menu
                                    $prevElement = $element.parents('li').first().find('a').first();
                                } else if ($prevElement.parents('li').first().children('[data-submenu]:visible').length) {
                                    // if previous element has open sub menu
                                    $prevElement = $prevElement.parents('li').find('li:last-child').find('a').first();
                                }
                                if ($(this).is(':last-child')) {
                                    // is last element of sub menu
                                    $nextElement = $element.parents('li').first().next('li').find('a').first();
                                }

                                return;
                            }
                        });

                        Foundation.Keyboard.handleKey(e, 'AccordionMenu', {
                            open: function open() {
                                if ($target.is(':hidden')) {
                                    _this.down($target);
                                    $target.find('li').first().find('a').first().focus();
                                }
                            },
                            close: function close() {
                                if ($target.length && !$target.is(':hidden')) {
                                    // close active sub of this item
                                    _this.up($target);
                                } else if ($element.parent('[data-submenu]').length) {
                                    // close currently open sub
                                    _this.up($element.parent('[data-submenu]'));
                                    $element.parents('li').first().find('a').first().focus();
                                }
                            },
                            up: function up() {
                                $prevElement.focus();
                                return true;
                            },
                            down: function down() {
                                $nextElement.focus();
                                return true;
                            },
                            toggle: function toggle() {
                                if ($element.children('[data-submenu]').length) {
                                    _this.toggle($element.children('[data-submenu]'));
                                }
                            },
                            closeAll: function closeAll() {
                                _this.hideAll();
                            },
                            handled: function handled(preventDefault) {
                                if (preventDefault) {
                                    e.preventDefault();
                                }
                                e.stopImmediatePropagation();
                            }
                        });
                    }); //.attr('tabindex', 0);
                }

                /**
                 * Closes all panes of the menu.
                 * @function
                 */

            }, {
                key: 'hideAll',
                value: function hideAll() {
                    this.up(this.$element.find('[data-submenu]'));
                }

                /**
                 * Opens all panes of the menu.
                 * @function
                 */

            }, {
                key: 'showAll',
                value: function showAll() {
                    this.down(this.$element.find('[data-submenu]'));
                }

                /**
                 * Toggles the open/close state of a submenu.
                 * @function
                 * @param {jQuery} $target - the submenu to toggle
                 */

            }, {
                key: 'toggle',
                value: function toggle($target) {
                    if (!$target.is(':animated')) {
                        if (!$target.is(':hidden')) {
                            this.up($target);
                        } else {
                            this.down($target);
                        }
                    }
                }

                /**
                 * Opens the sub-menu defined by `$target`.
                 * @param {jQuery} $target - Sub-menu to open.
                 * @fires AccordionMenu#down
                 */

            }, {
                key: 'down',
                value: function down($target) {
                    var _this = this;

                    if (!this.options.multiOpen) {
                        this.up(this.$element.find('.is-active').not($target.parentsUntil(this.$element).add($target)));
                    }

                    $target.addClass('is-active').attr({ 'aria-hidden': false }).parent('.is-accordion-submenu-parent').attr({ 'aria-expanded': true });

                    //Foundation.Move(this.options.slideSpeed, $target, function() {
                    $target.slideDown(_this.options.slideSpeed, function () {
                        /**
                         * Fires when the menu is done opening.
                         * @event AccordionMenu#down
                         */
                        _this.$element.trigger('down.zf.accordionMenu', [$target]);
                    });
                    //});
                }

                /**
                 * Closes the sub-menu defined by `$target`. All sub-menus inside the target will be closed as well.
                 * @param {jQuery} $target - Sub-menu to close.
                 * @fires AccordionMenu#up
                 */

            }, {
                key: 'up',
                value: function up($target) {
                    var _this = this;
                    //Foundation.Move(this.options.slideSpeed, $target, function(){
                    $target.slideUp(_this.options.slideSpeed, function () {
                        /**
                         * Fires when the menu is done collapsing up.
                         * @event AccordionMenu#up
                         */
                        _this.$element.trigger('up.zf.accordionMenu', [$target]);
                    });
                    //});

                    var $menus = $target.find('[data-submenu]').slideUp(0).addBack().attr('aria-hidden', true);

                    $menus.parent('.is-accordion-submenu-parent').attr('aria-expanded', false);
                }

                /**
                 * Destroys an instance of accordion menu.
                 * @fires AccordionMenu#destroyed
                 */

            }, {
                key: 'destroy',
                value: function destroy() {
                    this.$element.find('[data-submenu]').slideDown(0).css('display', '');
                    this.$element.find('a').off('click.zf.accordionMenu');

                    Foundation.Nest.Burn(this.$element, 'accordion');
                    Foundation.unregisterPlugin(this);
                }
            }]);

            return AccordionMenu;
        }();

        AccordionMenu.defaults = {
            /**
             * Amount of time to animate the opening of a submenu in ms.
             * @option
             * @type {number}
             * @default 250
             */
            slideSpeed: 250,
            /**
             * Allow the menu to have multiple open panes.
             * @option
             * @type {boolean}
             * @default true
             */
            multiOpen: true
        };

        // Window exports
        Foundation.plugin(AccordionMenu, 'AccordionMenu');
    }(jQuery);
    'use strict';

    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

    !function ($) {

        /**
         * Slider module.
         * @module foundation.slider
         * @requires foundation.util.motion
         * @requires foundation.util.triggers
         * @requires foundation.util.keyboard
         * @requires foundation.util.touch
         */

        var Slider = function () {
            /**
             * Creates a new instance of a slider control.
             * @class
             * @param {jQuery} element - jQuery object to make into a slider control.
             * @param {Object} options - Overrides to the default plugin settings.
             */
            function Slider(element, options) {
                _classCallCheck(this, Slider);

                this.$element = element;
                this.options = $.extend({}, Slider.defaults, this.$element.data(), options);

                this._init();

                Foundation.registerPlugin(this, 'Slider');
                Foundation.Keyboard.register('Slider', {
                    'ltr': {
                        'ARROW_RIGHT': 'increase',
                        'ARROW_UP': 'increase',
                        'ARROW_DOWN': 'decrease',
                        'ARROW_LEFT': 'decrease',
                        'SHIFT_ARROW_RIGHT': 'increase_fast',
                        'SHIFT_ARROW_UP': 'increase_fast',
                        'SHIFT_ARROW_DOWN': 'decrease_fast',
                        'SHIFT_ARROW_LEFT': 'decrease_fast'
                    },
                    'rtl': {
                        'ARROW_LEFT': 'increase',
                        'ARROW_RIGHT': 'decrease',
                        'SHIFT_ARROW_LEFT': 'increase_fast',
                        'SHIFT_ARROW_RIGHT': 'decrease_fast'
                    }
                });
            }

            /**
             * Initilizes the plugin by reading/setting attributes, creating collections and setting the initial position of the handle(s).
             * @function
             * @private
             */


            _createClass(Slider, [{
                key: '_init',
                value: function _init() {
                    this.inputs = this.$element.find('input');
                    this.handles = this.$element.find('[data-slider-handle]');

                    this.$handle = this.handles.eq(0);
                    this.$input = this.inputs.length ? this.inputs.eq(0) : $('#' + this.$handle.attr('aria-controls'));
                    this.$fill = this.$element.find('[data-slider-fill]').css(this.options.vertical ? 'height' : 'width', 0);

                    var isDbl = false,
                        _this = this;
                    if (this.options.disabled || this.$element.hasClass(this.options.disabledClass)) {
                        this.options.disabled = true;
                        this.$element.addClass(this.options.disabledClass);
                    }
                    if (!this.inputs.length) {
                        this.inputs = $().add(this.$input);
                        this.options.binding = true;
                    }

                    this._setInitAttr(0);

                    if (this.handles[1]) {
                        this.options.doubleSided = true;
                        this.$handle2 = this.handles.eq(1);
                        this.$input2 = this.inputs.length > 1 ? this.inputs.eq(1) : $('#' + this.$handle2.attr('aria-controls'));

                        if (!this.inputs[1]) {
                            this.inputs = this.inputs.add(this.$input2);
                        }
                        isDbl = true;

                        // this.$handle.triggerHandler('click.zf.slider');
                        this._setInitAttr(1);
                    }

                    // Set handle positions
                    this.setHandles();

                    this._events();
                }
            }, {
                key: 'setHandles',
                value: function setHandles() {
                    var _this2 = this;

                    if (this.handles[1]) {
                        this._setHandlePos(this.$handle, this.inputs.eq(0).val(), true, function () {
                            _this2._setHandlePos(_this2.$handle2, _this2.inputs.eq(1).val(), true);
                        });
                    } else {
                        this._setHandlePos(this.$handle, this.inputs.eq(0).val(), true);
                    }
                }
            }, {
                key: '_reflow',
                value: function _reflow() {
                    this.setHandles();
                }
                /**
                 * @function
                 * @private
                 * @param {Number} value - floating point (the value) to be transformed using to a relative position on the slider (the inverse of _value)
                 */

            }, {
                key: '_pctOfBar',
                value: function _pctOfBar(value) {
                    var pctOfBar = percent(value - this.options.start, this.options.end - this.options.start);

                    switch (this.options.positionValueFunction) {
                        case "pow":
                            pctOfBar = this._logTransform(pctOfBar);
                            break;
                        case "log":
                            pctOfBar = this._powTransform(pctOfBar);
                            break;
                    }

                    return pctOfBar.toFixed(2);
                }

                /**
                 * @function
                 * @private
                 * @param {Number} pctOfBar - floating point, the relative position of the slider (typically between 0-1) to be transformed to a value
                 */

            }, {
                key: '_value',
                value: function _value(pctOfBar) {
                    switch (this.options.positionValueFunction) {
                        case "pow":
                            pctOfBar = this._powTransform(pctOfBar);
                            break;
                        case "log":
                            pctOfBar = this._logTransform(pctOfBar);
                            break;
                    }
                    var value = (this.options.end - this.options.start) * pctOfBar + this.options.start;

                    return value;
                }

                /**
                 * @function
                 * @private
                 * @param {Number} value - floating point (typically between 0-1) to be transformed using the log function
                 */

            }, {
                key: '_logTransform',
                value: function _logTransform(value) {
                    return baseLog(this.options.nonLinearBase, value * (this.options.nonLinearBase - 1) + 1);
                }

                /**
                 * @function
                 * @private
                 * @param {Number} value - floating point (typically between 0-1) to be transformed using the power function
                 */

            }, {
                key: '_powTransform',
                value: function _powTransform(value) {
                    return (Math.pow(this.options.nonLinearBase, value) - 1) / (this.options.nonLinearBase - 1);
                }

                /**
                 * Sets the position of the selected handle and fill bar.
                 * @function
                 * @private
                 * @param {jQuery} $hndl - the selected handle to move.
                 * @param {Number} location - floating point between the start and end values of the slider bar.
                 * @param {Function} cb - callback function to fire on completion.
                 * @fires Slider#moved
                 * @fires Slider#changed
                 */

            }, {
                key: '_setHandlePos',
                value: function _setHandlePos($hndl, location, noInvert, cb) {
                    // don't move if the slider has been disabled since its initialization
                    if (this.$element.hasClass(this.options.disabledClass)) {
                        return;
                    }
                    //might need to alter that slightly for bars that will have odd number selections.
                    location = parseFloat(location); //on input change events, convert string to number...grumble.

                    // prevent slider from running out of bounds, if value exceeds the limits set through options, override the value to min/max
                    if (location < this.options.start) {
                        location = this.options.start;
                    } else if (location > this.options.end) {
                        location = this.options.end;
                    }

                    var isDbl = this.options.doubleSided;

                    if (isDbl) {
                        //this block is to prevent 2 handles from crossing eachother. Could/should be improved.
                        if (this.handles.index($hndl) === 0) {
                            var h2Val = parseFloat(this.$handle2.attr('aria-valuenow'));
                            location = location >= h2Val ? h2Val - this.options.step : location;
                        } else {
                            var h1Val = parseFloat(this.$handle.attr('aria-valuenow'));
                            location = location <= h1Val ? h1Val + this.options.step : location;
                        }
                    }

                    //this is for single-handled vertical sliders, it adjusts the value to account for the slider being "upside-down"
                    //for click and drag events, it's weird due to the scale(-1, 1) css property
                    if (this.options.vertical && !noInvert) {
                        location = this.options.end - location;
                    }

                    var _this = this,
                        vert = this.options.vertical,
                        hOrW = vert ? 'height' : 'width',
                        lOrT = vert ? 'top' : 'left',
                        handleDim = $hndl[0].getBoundingClientRect()[hOrW],
                        elemDim = this.$element[0].getBoundingClientRect()[hOrW],

                        //percentage of bar min/max value based on click or drag point
                        pctOfBar = this._pctOfBar(location),

                        //number of actual pixels to shift the handle, based on the percentage obtained above
                        pxToMove = (elemDim - handleDim) * pctOfBar,

                        //percentage of bar to shift the handle
                        movement = (percent(pxToMove, elemDim) * 100).toFixed(this.options.decimal);
                    //fixing the decimal value for the location number, is passed to other methods as a fixed floating-point value
                    location = parseFloat(location.toFixed(this.options.decimal));
                    // declare empty object for css adjustments, only used with 2 handled-sliders
                    var css = {};

                    this._setValues($hndl, location);

                    // TODO update to calculate based on values set to respective inputs??
                    if (isDbl) {
                        var isLeftHndl = this.handles.index($hndl) === 0,

                            //empty variable, will be used for min-height/width for fill bar
                            dim,

                            //percentage w/h of the handle compared to the slider bar
                            handlePct = ~~(percent(handleDim, elemDim) * 100);
                        //if left handle, the math is slightly different than if it's the right handle, and the left/top property needs to be changed for the fill bar
                        if (isLeftHndl) {
                            //left or top percentage value to apply to the fill bar.
                            css[lOrT] = movement + '%';
                            //calculate the new min-height/width for the fill bar.
                            dim = parseFloat(this.$handle2[0].style[lOrT]) - movement + handlePct;
                            //this callback is necessary to prevent errors and allow the proper placement and initialization of a 2-handled slider
                            //plus, it means we don't care if 'dim' isNaN on init, it won't be in the future.
                            if (cb && typeof cb === 'function') {
                                cb();
                            } //this is only needed for the initialization of 2 handled sliders
                        } else {
                            //just caching the value of the left/bottom handle's left/top property
                            var handlePos = parseFloat(this.$handle[0].style[lOrT]);
                            //calculate the new min-height/width for the fill bar. Use isNaN to prevent false positives for numbers <= 0
                            //based on the percentage of movement of the handle being manipulated, less the opposing handle's left/top position, plus the percentage w/h of the handle itself
                            dim = movement - (isNaN(handlePos) ? (this.options.initialStart - this.options.start) / ((this.options.end - this.options.start) / 100) : handlePos) + handlePct;
                        }
                        // assign the min-height/width to our css object
                        css['min-' + hOrW] = dim + '%';
                    }

                    this.$element.one('finished.zf.animate', function () {
                        /**
                         * Fires when the handle is done moving.
                         * @event Slider#moved
                         */
                        _this.$element.trigger('moved.zf.slider', [$hndl]);
                    });

                    //because we don't know exactly how the handle will be moved, check the amount of time it should take to move.
                    var moveTime = this.$element.data('dragging') ? 1000 / 60 : this.options.moveTime;

                    Foundation.Move(moveTime, $hndl, function () {
                        // adjusting the left/top property of the handle, based on the percentage calculated above
                        // if movement isNaN, that is because the slider is hidden and we cannot determine handle width,
                        // fall back to next best guess.
                        if (isNaN(movement)) {
                            $hndl.css(lOrT, pctOfBar * 100 + '%');
                        } else {
                            $hndl.css(lOrT, movement + '%');
                        }

                        if (!_this.options.doubleSided) {
                            //if single-handled, a simple method to expand the fill bar
                            _this.$fill.css(hOrW, pctOfBar * 100 + '%');
                        } else {
                            //otherwise, use the css object we created above
                            _this.$fill.css(css);
                        }
                    });

                    /**
                     * Fires when the value has not been change for a given time.
                     * @event Slider#changed
                     */
                    clearTimeout(_this.timeout);
                    _this.timeout = setTimeout(function () {
                        _this.$element.trigger('changed.zf.slider', [$hndl]);
                    }, _this.options.changedDelay);
                }

                /**
                 * Sets the initial attribute for the slider element.
                 * @function
                 * @private
                 * @param {Number} idx - index of the current handle/input to use.
                 */

            }, {
                key: '_setInitAttr',
                value: function _setInitAttr(idx) {
                    var initVal = idx === 0 ? this.options.initialStart : this.options.initialEnd;
                    var id = this.inputs.eq(idx).attr('id') || Foundation.GetYoDigits(6, 'slider');
                    this.inputs.eq(idx).attr({
                        'id': id,
                        'max': this.options.end,
                        'min': this.options.start,
                        'step': this.options.step
                    });
                    this.inputs.eq(idx).val(initVal);
                    this.handles.eq(idx).attr({
                        'role': 'slider',
                        'aria-controls': id,
                        'aria-valuemax': this.options.end,
                        'aria-valuemin': this.options.start,
                        'aria-valuenow': initVal,
                        'aria-orientation': this.options.vertical ? 'vertical' : 'horizontal',
                        'tabindex': 0
                    });
                }

                /**
                 * Sets the input and `aria-valuenow` values for the slider element.
                 * @function
                 * @private
                 * @param {jQuery} $handle - the currently selected handle.
                 * @param {Number} val - floating point of the new value.
                 */

            }, {
                key: '_setValues',
                value: function _setValues($handle, val) {
                    var idx = this.options.doubleSided ? this.handles.index($handle) : 0;
                    this.inputs.eq(idx).val(val);
                    $handle.attr('aria-valuenow', val);
                }

                /**
                 * Handles events on the slider element.
                 * Calculates the new location of the current handle.
                 * If there are two handles and the bar was clicked, it determines which handle to move.
                 * @function
                 * @private
                 * @param {Object} e - the `event` object passed from the listener.
                 * @param {jQuery} $handle - the current handle to calculate for, if selected.
                 * @param {Number} val - floating point number for the new value of the slider.
                 * TODO clean this up, there's a lot of repeated code between this and the _setHandlePos fn.
                 */

            }, {
                key: '_handleEvent',
                value: function _handleEvent(e, $handle, val) {
                    var value, hasVal;
                    if (!val) {
                        //click or drag events
                        e.preventDefault();
                        var _this = this,
                            vertical = this.options.vertical,
                            param = vertical ? 'height' : 'width',
                            direction = vertical ? 'top' : 'left',
                            eventOffset = vertical ? e.pageY : e.pageX,
                            halfOfHandle = this.$handle[0].getBoundingClientRect()[param] / 2,
                            barDim = this.$element[0].getBoundingClientRect()[param],
                            windowScroll = vertical ? $(window).scrollTop() : $(window).scrollLeft();

                        var elemOffset = this.$element.offset()[direction];

                        // touch events emulated by the touch util give position relative to screen, add window.scroll to event coordinates...
                        // best way to guess this is simulated is if clientY == pageY
                        if (e.clientY === e.pageY) {
                            eventOffset = eventOffset + windowScroll;
                        }
                        var eventFromBar = eventOffset - elemOffset;
                        var barXY;
                        if (eventFromBar < 0) {
                            barXY = 0;
                        } else if (eventFromBar > barDim) {
                            barXY = barDim;
                        } else {
                            barXY = eventFromBar;
                        }
                        var offsetPct = percent(barXY, barDim);

                        value = this._value(offsetPct);

                        // turn everything around for RTL, yay math!
                        if (Foundation.rtl() && !this.options.vertical) {
                            value = this.options.end - value;
                        }

                        value = _this._adjustValue(null, value);
                        //boolean flag for the setHandlePos fn, specifically for vertical sliders
                        hasVal = false;

                        if (!$handle) {
                            //figure out which handle it is, pass it to the next function.
                            var firstHndlPos = absPosition(this.$handle, direction, barXY, param),
                                secndHndlPos = absPosition(this.$handle2, direction, barXY, param);
                            $handle = firstHndlPos <= secndHndlPos ? this.$handle : this.$handle2;
                        }
                    } else {
                        //change event on input
                        value = this._adjustValue(null, val);
                        hasVal = true;
                    }

                    this._setHandlePos($handle, value, hasVal);
                }

                /**
                 * Adjustes value for handle in regard to step value. returns adjusted value
                 * @function
                 * @private
                 * @param {jQuery} $handle - the selected handle.
                 * @param {Number} value - value to adjust. used if $handle is falsy
                 */

            }, {
                key: '_adjustValue',
                value: function _adjustValue($handle, value) {
                    var val,
                        step = this.options.step,
                        div = parseFloat(step / 2),
                        left,
                        prev_val,
                        next_val;
                    if (!!$handle) {
                        val = parseFloat($handle.attr('aria-valuenow'));
                    } else {
                        val = value;
                    }
                    left = val % step;
                    prev_val = val - left;
                    next_val = prev_val + step;
                    if (left === 0) {
                        return val;
                    }
                    val = val >= prev_val + div ? next_val : prev_val;
                    return val;
                }

                /**
                 * Adds event listeners to the slider elements.
                 * @function
                 * @private
                 */

            }, {
                key: '_events',
                value: function _events() {
                    this._eventsForHandle(this.$handle);
                    if (this.handles[1]) {
                        this._eventsForHandle(this.$handle2);
                    }
                }

                /**
                 * Adds event listeners a particular handle
                 * @function
                 * @private
                 * @param {jQuery} $handle - the current handle to apply listeners to.
                 */

            }, {
                key: '_eventsForHandle',
                value: function _eventsForHandle($handle) {
                    var _this = this,
                        curHandle,
                        timer;

                    this.inputs.off('change.zf.slider').on('change.zf.slider', function (e) {
                        var idx = _this.inputs.index($(this));
                        _this._handleEvent(e, _this.handles.eq(idx), $(this).val());
                    });

                    if (this.options.clickSelect) {
                        this.$element.off('click.zf.slider').on('click.zf.slider', function (e) {
                            if (_this.$element.data('dragging')) {
                                return false;
                            }

                            if (!$(e.target).is('[data-slider-handle]')) {
                                if (_this.options.doubleSided) {
                                    _this._handleEvent(e);
                                } else {
                                    _this._handleEvent(e, _this.$handle);
                                }
                            }
                        });
                    }

                    if (this.options.draggable) {
                        this.handles.addTouch();

                        var $body = $('body');
                        $handle.off('mousedown.zf.slider').on('mousedown.zf.slider', function (e) {
                            $handle.addClass('is-dragging');
                            _this.$fill.addClass('is-dragging'); //
                            _this.$element.data('dragging', true);

                            curHandle = $(e.currentTarget);

                            $body.on('mousemove.zf.slider', function (e) {
                                e.preventDefault();
                                _this._handleEvent(e, curHandle);
                            }).on('mouseup.zf.slider', function (e) {
                                _this._handleEvent(e, curHandle);

                                $handle.removeClass('is-dragging');
                                _this.$fill.removeClass('is-dragging');
                                _this.$element.data('dragging', false);

                                $body.off('mousemove.zf.slider mouseup.zf.slider');
                            });
                        })
                        // prevent events triggered by touch
                            .on('selectstart.zf.slider touchmove.zf.slider', function (e) {
                                e.preventDefault();
                            });
                    }

                    $handle.off('keydown.zf.slider').on('keydown.zf.slider', function (e) {
                        var _$handle = $(this),
                            idx = _this.options.doubleSided ? _this.handles.index(_$handle) : 0,
                            oldValue = parseFloat(_this.inputs.eq(idx).val()),
                            newValue;

                        // handle keyboard event with keyboard util
                        Foundation.Keyboard.handleKey(e, 'Slider', {
                            decrease: function decrease() {
                                newValue = oldValue - _this.options.step;
                            },
                            increase: function increase() {
                                newValue = oldValue + _this.options.step;
                            },
                            decrease_fast: function decrease_fast() {
                                newValue = oldValue - _this.options.step * 10;
                            },
                            increase_fast: function increase_fast() {
                                newValue = oldValue + _this.options.step * 10;
                            },
                            handled: function handled() {
                                // only set handle pos when event was handled specially
                                e.preventDefault();
                                _this._setHandlePos(_$handle, newValue, true);
                            }
                        });
                        /*if (newValue) { // if pressed key has special function, update value
            e.preventDefault();
            _this._setHandlePos(_$handle, newValue);
          }*/
                    });
                }

                /**
                 * Destroys the slider plugin.
                 */

            }, {
                key: 'destroy',
                value: function destroy() {
                    this.handles.off('.zf.slider');
                    this.inputs.off('.zf.slider');
                    this.$element.off('.zf.slider');

                    clearTimeout(this.timeout);

                    Foundation.unregisterPlugin(this);
                }
            }]);

            return Slider;
        }();

        Slider.defaults = {
            /**
             * Minimum value for the slider scale.
             * @option
             * @type {number}
             * @default 0
             */
            start: 0,
            /**
             * Maximum value for the slider scale.
             * @option
             * @type {number}
             * @default 100
             */
            end: 100,
            /**
             * Minimum value change per change event.
             * @option
             * @type {number}
             * @default 1
             */
            step: 1,
            /**
             * Value at which the handle/input *(left handle/first input)* should be set to on initialization.
             * @option
             * @type {number}
             * @default 0
             */
            initialStart: 0,
            /**
             * Value at which the right handle/second input should be set to on initialization.
             * @option
             * @type {number}
             * @default 100
             */
            initialEnd: 100,
            /**
             * Allows the input to be located outside the container and visible. Set to by the JS
             * @option
             * @type {boolean}
             * @default false
             */
            binding: false,
            /**
             * Allows the user to click/tap on the slider bar to select a value.
             * @option
             * @type {boolean}
             * @default true
             */
            clickSelect: true,
            /**
             * Set to true and use the `vertical` class to change alignment to vertical.
             * @option
             * @type {boolean}
             * @default false
             */
            vertical: false,
            /**
             * Allows the user to drag the slider handle(s) to select a value.
             * @option
             * @type {boolean}
             * @default true
             */
            draggable: true,
            /**
             * Disables the slider and prevents event listeners from being applied. Double checked by JS with `disabledClass`.
             * @option
             * @type {boolean}
             * @default false
             */
            disabled: false,
            /**
             * Allows the use of two handles. Double checked by the JS. Changes some logic handling.
             * @option
             * @type {boolean}
             * @default false
             */
            doubleSided: false,
            /**
             * Potential future feature.
             */
            // steps: 100,
            /**
             * Number of decimal places the plugin should go to for floating point precision.
             * @option
             * @type {number}
             * @default 2
             */
            decimal: 2,
            /**
             * Time delay for dragged elements.
             */
            // dragDelay: 0,
            /**
             * Time, in ms, to animate the movement of a slider handle if user clicks/taps on the bar. Needs to be manually set if updating the transition time in the Sass settings.
             * @option
             * @type {number}
             * @default 200
             */
            moveTime: 200, //update this if changing the transition time in the sass
            /**
             * Class applied to disabled sliders.
             * @option
             * @type {string}
             * @default 'disabled'
             */
            disabledClass: 'disabled',
            /**
             * Will invert the default layout for a vertical<span data-tooltip title="who would do this???"> </span>slider.
             * @option
             * @type {boolean}
             * @default false
             */
            invertVertical: false,
            /**
             * Milliseconds before the `changed.zf-slider` event is triggered after value change.
             * @option
             * @type {number}
             * @default 500
             */
            changedDelay: 500,
            /**
             * Basevalue for non-linear sliders
             * @option
             * @type {number}
             * @default 5
             */
            nonLinearBase: 5,
            /**
             * Basevalue for non-linear sliders, possible values are: `'linear'`, `'pow'` & `'log'`. Pow and Log use the nonLinearBase setting.
             * @option
             * @type {string}
             * @default 'linear'
             */
            positionValueFunction: 'linear'
        };

        function percent(frac, num) {
            return frac / num;
        }
        function absPosition($handle, dir, clickPos, param) {
            return Math.abs($handle.position()[dir] + $handle[param]() / 2 - clickPos);
        }
        function baseLog(base, value) {
            return Math.log(value) / Math.log(base);
        }

        // Window exports
        Foundation.plugin(Slider, 'Slider');
    }(jQuery);
    'use strict';

    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

    !function ($) {

        /**
         * Sticky module.
         * @module foundation.sticky
         * @requires foundation.util.triggers
         * @requires foundation.util.mediaQuery
         */

        var Sticky = function () {
            /**
             * Creates a new instance of a sticky thing.
             * @class
             * @param {jQuery} element - jQuery object to make sticky.
             * @param {Object} options - options object passed when creating the element programmatically.
             */
            function Sticky(element, options) {
                _classCallCheck(this, Sticky);

                this.$element = element;
                this.options = $.extend({}, Sticky.defaults, this.$element.data(), options);

                this._init();

                Foundation.registerPlugin(this, 'Sticky');
            }

            /**
             * Initializes the sticky element by adding classes, getting/setting dimensions, breakpoints and attributes
             * @function
             * @private
             */


            _createClass(Sticky, [{
                key: '_init',
                value: function _init() {
                    var $parent = this.$element.parent('[data-sticky-container]'),
                        id = this.$element[0].id || Foundation.GetYoDigits(6, 'sticky'),
                        _this = this;

                    if (!$parent.length) {
                        this.wasWrapped = true;
                    }
                    this.$container = $parent.length ? $parent : $(this.options.container).wrapInner(this.$element);
                    this.$container.addClass(this.options.containerClass);

                    this.$element.addClass(this.options.stickyClass).attr({ 'data-resize': id, 'data-mutate': id });
                    if (this.options.anchor !== '') {
                        $('#' + _this.options.anchor).attr({ 'data-mutate': id });
                    }

                    this.scrollCount = this.options.checkEvery;
                    this.isStuck = false;
                    $(window).one('load.zf.sticky', function () {
                        //We calculate the container height to have correct values for anchor points offset calculation.
                        _this.containerHeight = _this.$element.css("display") == "none" ? 0 : _this.$element[0].getBoundingClientRect().height;
                        _this.$container.css('height', _this.containerHeight);
                        _this.elemHeight = _this.containerHeight;
                        if (_this.options.anchor !== '') {
                            _this.$anchor = $('#' + _this.options.anchor);
                        } else {
                            _this._parsePoints();
                        }

                        _this._setSizes(function () {
                            var scroll = window.pageYOffset;
                            _this._calc(false, scroll);
                            //Unstick the element will ensure that proper classes are set.
                            if (!_this.isStuck) {
                                _this._removeSticky(scroll >= _this.topPoint ? false : true);
                            }
                        });
                        _this._events(id.split('-').reverse().join('-'));
                    });
                }

                /**
                 * If using multiple elements as anchors, calculates the top and bottom pixel values the sticky thing should stick and unstick on.
                 * @function
                 * @private
                 */

            }, {
                key: '_parsePoints',
                value: function _parsePoints() {
                    var top = this.options.topAnchor == "" ? 1 : this.options.topAnchor,
                        btm = this.options.btmAnchor == "" ? document.documentElement.scrollHeight : this.options.btmAnchor,
                        pts = [top, btm],
                        breaks = {};
                    for (var i = 0, len = pts.length; i < len && pts[i]; i++) {
                        var pt;
                        if (typeof pts[i] === 'number') {
                            pt = pts[i];
                        } else {
                            var place = pts[i].split(':'),
                                anchor = $('#' + place[0]);

                            pt = anchor.offset().top;
                            if (place[1] && place[1].toLowerCase() === 'bottom') {
                                pt += anchor[0].getBoundingClientRect().height;
                            }
                        }
                        breaks[i] = pt;
                    }

                    this.points = breaks;
                    return;
                }

                /**
                 * Adds event handlers for the scrolling element.
                 * @private
                 * @param {String} id - psuedo-random id for unique scroll event listener.
                 */

            }, {
                key: '_events',
                value: function _events(id) {
                    var _this = this,
                        scrollListener = this.scrollListener = 'scroll.zf.' + id;
                    if (this.isOn) {
                        return;
                    }
                    if (this.canStick) {
                        this.isOn = true;
                        $(window).off(scrollListener).on(scrollListener, function (e) {
                            if (_this.scrollCount === 0) {
                                _this.scrollCount = _this.options.checkEvery;
                                _this._setSizes(function () {
                                    _this._calc(false, window.pageYOffset);
                                });
                            } else {
                                _this.scrollCount--;
                                _this._calc(false, window.pageYOffset);
                            }
                        });
                    }

                    this.$element.off('resizeme.zf.trigger').on('resizeme.zf.trigger', function (e, el) {
                        _this._eventsHandler(id);
                    });

                    this.$element.on('mutateme.zf.trigger', function (e, el) {
                        _this._eventsHandler(id);
                    });

                    if (this.$anchor) {
                        this.$anchor.on('mutateme.zf.trigger', function (e, el) {
                            _this._eventsHandler(id);
                        });
                    }
                }

                /**
                 * Handler for events.
                 * @private
                 * @param {String} id - psuedo-random id for unique scroll event listener.
                 */

            }, {
                key: '_eventsHandler',
                value: function _eventsHandler(id) {
                    var _this = this,
                        scrollListener = this.scrollListener = 'scroll.zf.' + id;

                    _this._setSizes(function () {
                        _this._calc(false);
                        if (_this.canStick) {
                            if (!_this.isOn) {
                                _this._events(id);
                            }
                        } else if (_this.isOn) {
                            _this._pauseListeners(scrollListener);
                        }
                    });
                }

                /**
                 * Removes event handlers for scroll and change events on anchor.
                 * @fires Sticky#pause
                 * @param {String} scrollListener - unique, namespaced scroll listener attached to `window`
                 */

            }, {
                key: '_pauseListeners',
                value: function _pauseListeners(scrollListener) {
                    this.isOn = false;
                    $(window).off(scrollListener);

                    /**
                     * Fires when the plugin is paused due to resize event shrinking the view.
                     * @event Sticky#pause
                     * @private
                     */
                    this.$element.trigger('pause.zf.sticky');
                }

                /**
                 * Called on every `scroll` event and on `_init`
                 * fires functions based on booleans and cached values
                 * @param {Boolean} checkSizes - true if plugin should recalculate sizes and breakpoints.
                 * @param {Number} scroll - current scroll position passed from scroll event cb function. If not passed, defaults to `window.pageYOffset`.
                 */

            }, {
                key: '_calc',
                value: function _calc(checkSizes, scroll) {
                    if (checkSizes) {
                        this._setSizes();
                    }

                    if (!this.canStick) {
                        if (this.isStuck) {
                            this._removeSticky(true);
                        }
                        return false;
                    }

                    if (!scroll) {
                        scroll = window.pageYOffset;
                    }

                    if (scroll >= this.topPoint) {
                        if (scroll <= this.bottomPoint) {
                            if (!this.isStuck) {
                                this._setSticky();
                            }
                        } else {
                            if (this.isStuck) {
                                this._removeSticky(false);
                            }
                        }
                    } else {
                        if (this.isStuck) {
                            this._removeSticky(true);
                        }
                    }
                }

                /**
                 * Causes the $element to become stuck.
                 * Adds `position: fixed;`, and helper classes.
                 * @fires Sticky#stuckto
                 * @function
                 * @private
                 */

            }, {
                key: '_setSticky',
                value: function _setSticky() {
                    var _this = this,
                        stickTo = this.options.stickTo,
                        mrgn = stickTo === 'top' ? 'marginTop' : 'marginBottom',
                        notStuckTo = stickTo === 'top' ? 'bottom' : 'top',
                        css = {};

                    css[mrgn] = this.options[mrgn] + 'em';
                    css[stickTo] = 0;
                    css[notStuckTo] = 'auto';
                    this.isStuck = true;
                    this.$element.removeClass('is-anchored is-at-' + notStuckTo).addClass('is-stuck is-at-' + stickTo).css(css)
                    /**
                     * Fires when the $element has become `position: fixed;`
                     * Namespaced to `top` or `bottom`, e.g. `sticky.zf.stuckto:top`
                     * @event Sticky#stuckto
                     */
                        .trigger('sticky.zf.stuckto:' + stickTo);
                    this.$element.on("transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd", function () {
                        _this._setSizes();
                    });
                }

                /**
                 * Causes the $element to become unstuck.
                 * Removes `position: fixed;`, and helper classes.
                 * Adds other helper classes.
                 * @param {Boolean} isTop - tells the function if the $element should anchor to the top or bottom of its $anchor element.
                 * @fires Sticky#unstuckfrom
                 * @private
                 */

            }, {
                key: '_removeSticky',
                value: function _removeSticky(isTop) {
                    var stickTo = this.options.stickTo,
                        stickToTop = stickTo === 'top',
                        css = {},
                        anchorPt = (this.points ? this.points[1] - this.points[0] : this.anchorHeight) - this.elemHeight,
                        mrgn = stickToTop ? 'marginTop' : 'marginBottom',
                        notStuckTo = stickToTop ? 'bottom' : 'top',
                        topOrBottom = isTop ? 'top' : 'bottom';

                    css[mrgn] = 0;

                    css['bottom'] = 'auto';
                    if (isTop) {
                        css['top'] = 0;
                    } else {
                        css['top'] = anchorPt;
                    }

                    this.isStuck = false;
                    this.$element.removeClass('is-stuck is-at-' + stickTo).addClass('is-anchored is-at-' + topOrBottom).css(css)
                    /**
                     * Fires when the $element has become anchored.
                     * Namespaced to `top` or `bottom`, e.g. `sticky.zf.unstuckfrom:bottom`
                     * @event Sticky#unstuckfrom
                     */
                        .trigger('sticky.zf.unstuckfrom:' + topOrBottom);
                }

                /**
                 * Sets the $element and $container sizes for plugin.
                 * Calls `_setBreakPoints`.
                 * @param {Function} cb - optional callback function to fire on completion of `_setBreakPoints`.
                 * @private
                 */

            }, {
                key: '_setSizes',
                value: function _setSizes(cb) {
                    this.canStick = Foundation.MediaQuery.is(this.options.stickyOn);
                    if (!this.canStick) {
                        if (cb && typeof cb === 'function') {
                            cb();
                        }
                    }
                    var _this = this,
                        newElemWidth = this.$container[0].getBoundingClientRect().width,
                        comp = window.getComputedStyle(this.$container[0]),
                        pdngl = parseInt(comp['padding-left'], 10),
                        pdngr = parseInt(comp['padding-right'], 10);

                    if (this.$anchor && this.$anchor.length) {
                        this.anchorHeight = this.$anchor[0].getBoundingClientRect().height;
                    } else {
                        this._parsePoints();
                    }

                    this.$element.css({
                        'max-width': newElemWidth - pdngl - pdngr + 'px'
                    });

                    var newContainerHeight = this.$element[0].getBoundingClientRect().height || this.containerHeight;
                    if (this.$element.css("display") == "none") {
                        newContainerHeight = 0;
                    }
                    this.containerHeight = newContainerHeight;
                    this.$container.css({
                        height: newContainerHeight
                    });
                    this.elemHeight = newContainerHeight;

                    if (!this.isStuck) {
                        if (this.$element.hasClass('is-at-bottom')) {
                            var anchorPt = (this.points ? this.points[1] - this.$container.offset().top : this.anchorHeight) - this.elemHeight;
                            this.$element.css('top', anchorPt);
                        }
                    }

                    this._setBreakPoints(newContainerHeight, function () {
                        if (cb && typeof cb === 'function') {
                            cb();
                        }
                    });
                }

                /**
                 * Sets the upper and lower breakpoints for the element to become sticky/unsticky.
                 * @param {Number} elemHeight - px value for sticky.$element height, calculated by `_setSizes`.
                 * @param {Function} cb - optional callback function to be called on completion.
                 * @private
                 */

            }, {
                key: '_setBreakPoints',
                value: function _setBreakPoints(elemHeight, cb) {
                    if (!this.canStick) {
                        if (cb && typeof cb === 'function') {
                            cb();
                        } else {
                            return false;
                        }
                    }
                    var mTop = emCalc(this.options.marginTop),
                        mBtm = emCalc(this.options.marginBottom),
                        topPoint = this.points ? this.points[0] : this.$anchor.offset().top,
                        bottomPoint = this.points ? this.points[1] : topPoint + this.anchorHeight,

                        // topPoint = this.$anchor.offset().top || this.points[0],
                        // bottomPoint = topPoint + this.anchorHeight || this.points[1],
                        winHeight = window.innerHeight;

                    if (this.options.stickTo === 'top') {
                        topPoint -= mTop;
                        bottomPoint -= elemHeight + mTop;
                    } else if (this.options.stickTo === 'bottom') {
                        topPoint -= winHeight - (elemHeight + mBtm);
                        bottomPoint -= winHeight - mBtm;
                    } else {
                        //this would be the stickTo: both option... tricky
                    }

                    this.topPoint = topPoint;
                    this.bottomPoint = bottomPoint;

                    if (cb && typeof cb === 'function') {
                        cb();
                    }
                }

                /**
                 * Destroys the current sticky element.
                 * Resets the element to the top position first.
                 * Removes event listeners, JS-added css properties and classes, and unwraps the $element if the JS added the $container.
                 * @function
                 */

            }, {
                key: 'destroy',
                value: function destroy() {
                    this._removeSticky(true);

                    this.$element.removeClass(this.options.stickyClass + ' is-anchored is-at-top').css({
                        height: '',
                        top: '',
                        bottom: '',
                        'max-width': ''
                    }).off('resizeme.zf.trigger').off('mutateme.zf.trigger');
                    if (this.$anchor && this.$anchor.length) {
                        this.$anchor.off('change.zf.sticky');
                    }
                    $(window).off(this.scrollListener);

                    if (this.wasWrapped) {
                        this.$element.unwrap();
                    } else {
                        this.$container.removeClass(this.options.containerClass).css({
                            height: ''
                        });
                    }
                    Foundation.unregisterPlugin(this);
                }
            }]);

            return Sticky;
        }();

        Sticky.defaults = {
            /**
             * Customizable container template. Add your own classes for styling and sizing.
             * @option
             * @type {string}
             * @default '&lt;div data-sticky-container&gt;&lt;/div&gt;'
             */
            container: '<div data-sticky-container></div>',
            /**
             * Location in the view the element sticks to. Can be `'top'` or `'bottom'`.
             * @option
             * @type {string}
             * @default 'top'
             */
            stickTo: 'top',
            /**
             * If anchored to a single element, the id of that element.
             * @option
             * @type {string}
             * @default ''
             */
            anchor: '',
            /**
             * If using more than one element as anchor points, the id of the top anchor.
             * @option
             * @type {string}
             * @default ''
             */
            topAnchor: '',
            /**
             * If using more than one element as anchor points, the id of the bottom anchor.
             * @option
             * @type {string}
             * @default ''
             */
            btmAnchor: '',
            /**
             * Margin, in `em`'s to apply to the top of the element when it becomes sticky.
             * @option
             * @type {number}
             * @default 1
             */
            marginTop: 1,
            /**
             * Margin, in `em`'s to apply to the bottom of the element when it becomes sticky.
             * @option
             * @type {number}
             * @default 1
             */
            marginBottom: 1,
            /**
             * Breakpoint string that is the minimum screen size an element should become sticky.
             * @option
             * @type {string}
             * @default 'medium'
             */
            stickyOn: 'medium',
            /**
             * Class applied to sticky element, and removed on destruction. Foundation defaults to `sticky`.
             * @option
             * @type {string}
             * @default 'sticky'
             */
            stickyClass: 'sticky',
            /**
             * Class applied to sticky container. Foundation defaults to `sticky-container`.
             * @option
             * @type {string}
             * @default 'sticky-container'
             */
            containerClass: 'sticky-container',
            /**
             * Number of scroll events between the plugin's recalculating sticky points. Setting it to `0` will cause it to recalc every scroll event, setting it to `-1` will prevent recalc on scroll.
             * @option
             * @type {number}
             * @default -1
             */
            checkEvery: -1
        };

        /**
         * Helper function to calculate em values
         * @param Number {em} - number of em's to calculate into pixels
         */
        function emCalc(em) {
            return parseInt(window.getComputedStyle(document.body, null).fontSize, 10) * em;
        }

        // Window exports
        Foundation.plugin(Sticky, 'Sticky');
    }(jQuery);
    'use strict';

    var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

    !function ($) {

        /**
         * Tabs module.
         * @module foundation.tabs
         * @requires foundation.util.keyboard
         * @requires foundation.util.timerAndImageLoader if tabs contain images
         */

        var Tabs = function () {
            /**
             * Creates a new instance of tabs.
             * @class
             * @fires Tabs#init
             * @param {jQuery} element - jQuery object to make into tabs.
             * @param {Object} options - Overrides to the default plugin settings.
             */
            function Tabs(element, options) {
                _classCallCheck(this, Tabs);

                this.$element = element;
                this.options = $.extend({}, Tabs.defaults, this.$element.data(), options);

                this._init();
                Foundation.registerPlugin(this, 'Tabs');
                Foundation.Keyboard.register('Tabs', {
                    'ENTER': 'open',
                    'SPACE': 'open',
                    'ARROW_RIGHT': 'next',
                    'ARROW_UP': 'previous',
                    'ARROW_DOWN': 'next',
                    'ARROW_LEFT': 'previous'
                    // 'TAB': 'next',
                    // 'SHIFT_TAB': 'previous'
                });
            }

            /**
             * Initializes the tabs by showing and focusing (if autoFocus=true) the preset active tab.
             * @private
             */


            _createClass(Tabs, [{
                key: '_init',
                value: function _init() {
                    var _this2 = this;

                    var _this = this;

                    this.$element.attr({ 'role': 'tablist' });
                    this.$tabTitles = this.$element.find('.' + this.options.linkClass);
                    this.$tabContent = $('[data-tabs-content="' + this.$element[0].id + '"]');

                    this.$tabTitles.each(function () {
                        var $elem = $(this),
                            $link = $elem.find('a'),
                            isActive = $elem.hasClass('' + _this.options.linkActiveClass),
                            hash = $link[0].hash.slice(1),
                            linkId = $link[0].id ? $link[0].id : hash + '-label',
                            $tabContent = $('#' + hash);

                        $elem.attr({ 'role': 'presentation' });

                        $link.attr({
                            'role': 'tab',
                            'aria-controls': hash,
                            'aria-selected': isActive,
                            'id': linkId
                        });

                        $tabContent.attr({
                            'role': 'tabpanel',
                            'aria-hidden': !isActive,
                            'aria-labelledby': linkId
                        });

                        if (isActive && _this.options.autoFocus) {
                            $(window).load(function () {
                                $('html, body').animate({ scrollTop: $elem.offset().top }, _this.options.deepLinkSmudgeDelay, function () {
                                    $link.focus();
                                });
                            });
                        }
                    });
                    if (this.options.matchHeight) {
                        var $images = this.$tabContent.find('img');

                        if ($images.length) {
                            Foundation.onImagesLoaded($images, this._setHeight.bind(this));
                        } else {
                            this._setHeight();
                        }
                    }

                    //current context-bound function to open tabs on page load or history popstate
                    this._checkDeepLink = function () {
                        var anchor = window.location.hash;
                        //need a hash and a relevant anchor in this tabset
                        if (anchor.length) {
                            var $link = _this2.$element.find('[href$="' + anchor + '"]');
                            if ($link.length) {
                                _this2.selectTab($(anchor), true);

                                //roll up a little to show the titles
                                if (_this2.options.deepLinkSmudge) {
                                    var offset = _this2.$element.offset();
                                    $('html, body').animate({ scrollTop: offset.top }, _this2.options.deepLinkSmudgeDelay);
                                }

                                /**
                                 * Fires when the zplugin has deeplinked at pageload
                                 * @event Tabs#deeplink
                                 */
                                _this2.$element.trigger('deeplink.zf.tabs', [$link, $(anchor)]);
                            }
                        }
                    };

                    //use browser to open a tab, if it exists in this tabset
                    if (this.options.deepLink) {
                        this._checkDeepLink();
                    }

                    this._events();
                }

                /**
                 * Adds event handlers for items within the tabs.
                 * @private
                 */

            }, {
                key: '_events',
                value: function _events() {
                    this._addKeyHandler();
                    this._addClickHandler();
                    this._setHeightMqHandler = null;

                    if (this.options.matchHeight) {
                        this._setHeightMqHandler = this._setHeight.bind(this);

                        $(window).on('changed.zf.mediaquery', this._setHeightMqHandler);
                    }

                    if (this.options.deepLink) {
                        $(window).on('popstate', this._checkDeepLink);
                    }
                }

                /**
                 * Adds click handlers for items within the tabs.
                 * @private
                 */

            }, {
                key: '_addClickHandler',
                value: function _addClickHandler() {
                    var _this = this;

                    this.$element.off('click.zf.tabs').on('click.zf.tabs', '.' + this.options.linkClass, function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        _this._handleTabChange($(this));
                    });
                }

                /**
                 * Adds keyboard event handlers for items within the tabs.
                 * @private
                 */

            }, {
                key: '_addKeyHandler',
                value: function _addKeyHandler() {
                    var _this = this;

                    this.$tabTitles.off('keydown.zf.tabs').on('keydown.zf.tabs', function (e) {
                        if (e.which === 9) return;

                        var $element = $(this),
                            $elements = $element.parent('ul').children('li'),
                            $prevElement,
                            $nextElement;

                        $elements.each(function (i) {
                            if ($(this).is($element)) {
                                if (_this.options.wrapOnKeys) {
                                    $prevElement = i === 0 ? $elements.last() : $elements.eq(i - 1);
                                    $nextElement = i === $elements.length - 1 ? $elements.first() : $elements.eq(i + 1);
                                } else {
                                    $prevElement = $elements.eq(Math.max(0, i - 1));
                                    $nextElement = $elements.eq(Math.min(i + 1, $elements.length - 1));
                                }
                                return;
                            }
                        });

                        // handle keyboard event with keyboard util
                        Foundation.Keyboard.handleKey(e, 'Tabs', {
                            open: function open() {
                                $element.find('[role="tab"]').focus();
                                _this._handleTabChange($element);
                            },
                            previous: function previous() {
                                $prevElement.find('[role="tab"]').focus();
                                _this._handleTabChange($prevElement);
                            },
                            next: function next() {
                                $nextElement.find('[role="tab"]').focus();
                                _this._handleTabChange($nextElement);
                            },
                            handled: function handled() {
                                e.stopPropagation();
                                e.preventDefault();
                            }
                        });
                    });
                }

                /**
                 * Opens the tab `$targetContent` defined by `$target`. Collapses active tab.
                 * @param {jQuery} $target - Tab to open.
                 * @param {boolean} historyHandled - browser has already handled a history update
                 * @fires Tabs#change
                 * @function
                 */

            }, {
                key: '_handleTabChange',
                value: function _handleTabChange($target, historyHandled) {

                    /**
                     * Check for active class on target. Collapse if exists.
                     */
                    if ($target.hasClass('' + this.options.linkActiveClass)) {
                        if (this.options.activeCollapse) {
                            this._collapseTab($target);

                            /**
                             * Fires when the zplugin has successfully collapsed tabs.
                             * @event Tabs#collapse
                             */
                            this.$element.trigger('collapse.zf.tabs', [$target]);
                        }
                        return;
                    }

                    var $oldTab = this.$element.find('.' + this.options.linkClass + '.' + this.options.linkActiveClass),
                        $tabLink = $target.find('[role="tab"]'),
                        hash = $tabLink[0].hash,
                        $targetContent = this.$tabContent.find(hash);

                    //close old tab
                    this._collapseTab($oldTab);

                    //open new tab
                    this._openTab($target);

                    //either replace or update browser history
                    if (this.options.deepLink && !historyHandled) {
                        var anchor = $target.find('a').attr('href');

                        if (this.options.updateHistory) {
                            history.pushState({}, '', anchor);
                        } else {
                            history.replaceState({}, '', anchor);
                        }
                    }

                    /**
                     * Fires when the plugin has successfully changed tabs.
                     * @event Tabs#change
                     */
                    this.$element.trigger('change.zf.tabs', [$target, $targetContent]);

                    //fire to children a mutation event
                    $targetContent.find("[data-mutate]").trigger("mutateme.zf.trigger");
                }

                /**
                 * Opens the tab `$targetContent` defined by `$target`.
                 * @param {jQuery} $target - Tab to Open.
                 * @function
                 */

            }, {
                key: '_openTab',
                value: function _openTab($target) {
                    var $tabLink = $target.find('[role="tab"]'),
                        hash = $tabLink[0].hash,
                        $targetContent = this.$tabContent.find(hash);

                    $target.addClass('' + this.options.linkActiveClass);

                    $tabLink.attr({ 'aria-selected': 'true' });

                    $targetContent.addClass('' + this.options.panelActiveClass).attr({ 'aria-hidden': 'false' });
                }

                /**
                 * Collapses `$targetContent` defined by `$target`.
                 * @param {jQuery} $target - Tab to Open.
                 * @function
                 */

            }, {
                key: '_collapseTab',
                value: function _collapseTab($target) {
                    var $target_anchor = $target.removeClass('' + this.options.linkActiveClass).find('[role="tab"]').attr({ 'aria-selected': 'false' });

                    $('#' + $target_anchor.attr('aria-controls')).removeClass('' + this.options.panelActiveClass).attr({ 'aria-hidden': 'true' });
                }

                /**
                 * Public method for selecting a content pane to display.
                 * @param {jQuery | String} elem - jQuery object or string of the id of the pane to display.
                 * @param {boolean} historyHandled - browser has already handled a history update
                 * @function
                 */

            }, {
                key: 'selectTab',
                value: function selectTab(elem, historyHandled) {
                    var idStr;

                    if ((typeof elem === 'undefined' ? 'undefined' : _typeof(elem)) === 'object') {
                        idStr = elem[0].id;
                    } else {
                        idStr = elem;
                    }

                    if (idStr.indexOf('#') < 0) {
                        idStr = '#' + idStr;
                    }

                    var $target = this.$tabTitles.find('[href$="' + idStr + '"]').parent('.' + this.options.linkClass);

                    this._handleTabChange($target, historyHandled);
                }
            }, {
                key: '_setHeight',

                /**
                 * Sets the height of each panel to the height of the tallest panel.
                 * If enabled in options, gets called on media query change.
                 * If loading content via external source, can be called directly or with _reflow.
                 * If enabled with `data-match-height="true"`, tabs sets to equal height
                 * @function
                 * @private
                 */
                value: function _setHeight() {
                    var max = 0,
                        _this = this; // Lock down the `this` value for the root tabs object

                    this.$tabContent.find('.' + this.options.panelClass).css('height', '').each(function () {

                        var panel = $(this),
                            isActive = panel.hasClass('' + _this.options.panelActiveClass); // get the options from the parent instead of trying to get them from the child

                        if (!isActive) {
                            panel.css({ 'visibility': 'hidden', 'display': 'block' });
                        }

                        var temp = this.getBoundingClientRect().height;

                        if (!isActive) {
                            panel.css({
                                'visibility': '',
                                'display': ''
                            });
                        }

                        max = temp > max ? temp : max;
                    }).css('height', max + 'px');
                }

                /**
                 * Destroys an instance of an tabs.
                 * @fires Tabs#destroyed
                 */

            }, {
                key: 'destroy',
                value: function destroy() {
                    this.$element.find('.' + this.options.linkClass).off('.zf.tabs').hide().end().find('.' + this.options.panelClass).hide();

                    if (this.options.matchHeight) {
                        if (this._setHeightMqHandler != null) {
                            $(window).off('changed.zf.mediaquery', this._setHeightMqHandler);
                        }
                    }

                    if (this.options.deepLink) {
                        $(window).off('popstate', this._checkDeepLink);
                    }

                    Foundation.unregisterPlugin(this);
                }
            }]);

            return Tabs;
        }();

        Tabs.defaults = {
            /**
             * Allows the window to scroll to content of pane specified by hash anchor
             * @option
             * @type {boolean}
             * @default false
             */
            deepLink: false,

            /**
             * Adjust the deep link scroll to make sure the top of the tab panel is visible
             * @option
             * @type {boolean}
             * @default false
             */
            deepLinkSmudge: false,

            /**
             * Animation time (ms) for the deep link adjustment
             * @option
             * @type {number}
             * @default 300
             */
            deepLinkSmudgeDelay: 300,

            /**
             * Update the browser history with the open tab
             * @option
             * @type {boolean}
             * @default false
             */
            updateHistory: false,

            /**
             * Allows the window to scroll to content of active pane on load if set to true.
             * Not recommended if more than one tab panel per page.
             * @option
             * @type {boolean}
             * @default false
             */
            autoFocus: false,

            /**
             * Allows keyboard input to 'wrap' around the tab links.
             * @option
             * @type {boolean}
             * @default true
             */
            wrapOnKeys: true,

            /**
             * Allows the tab content panes to match heights if set to true.
             * @option
             * @type {boolean}
             * @default false
             */
            matchHeight: false,

            /**
             * Allows active tabs to collapse when clicked.
             * @option
             * @type {boolean}
             * @default false
             */
            activeCollapse: false,

            /**
             * Class applied to `li`'s in tab link list.
             * @option
             * @type {string}
             * @default 'tabs-title'
             */
            linkClass: 'tabs-title',

            /**
             * Class applied to the active `li` in tab link list.
             * @option
             * @type {string}
             * @default 'is-active'
             */
            linkActiveClass: 'is-active',

            /**
             * Class applied to the content containers.
             * @option
             * @type {string}
             * @default 'tabs-panel'
             */
            panelClass: 'tabs-panel',

            /**
             * Class applied to the active content container.
             * @option
             * @type {string}
             * @default 'is-active'
             */
            panelActiveClass: 'is-active'
        };

        // Window exports
        Foundation.plugin(Tabs, 'Tabs');
    }(jQuery);
    'use strict';

    var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

    function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

    !function ($) {

        /**
         * Toggler module.
         * @module foundation.toggler
         * @requires foundation.util.motion
         * @requires foundation.util.triggers
         */

        var Toggler = function () {
            /**
             * Creates a new instance of Toggler.
             * @class
             * @fires Toggler#init
             * @param {Object} element - jQuery object to add the trigger to.
             * @param {Object} options - Overrides to the default plugin settings.
             */
            function Toggler(element, options) {
                _classCallCheck(this, Toggler);

                this.$element = element;
                this.options = $.extend({}, Toggler.defaults, element.data(), options);
                this.className = '';

                this._init();
                this._events();

                Foundation.registerPlugin(this, 'Toggler');
            }

            /**
             * Initializes the Toggler plugin by parsing the toggle class from data-toggler, or animation classes from data-animate.
             * @function
             * @private
             */


            _createClass(Toggler, [{
                key: '_init',
                value: function _init() {
                    var input;
                    // Parse animation classes if they were set
                    if (this.options.animate) {
                        input = this.options.animate.split(' ');

                        this.animationIn = input[0];
                        this.animationOut = input[1] || null;
                    }
                    // Otherwise, parse toggle class
                    else {
                        input = this.$element.data('toggler');
                        // Allow for a . at the beginning of the string
                        this.className = input[0] === '.' ? input.slice(1) : input;
                    }

                    // Add ARIA attributes to triggers
                    var id = this.$element[0].id;
                    $('[data-open="' + id + '"], [data-close="' + id + '"], [data-toggle="' + id + '"]').attr('aria-controls', id);
                    // If the target is hidden, add aria-hidden
                    this.$element.attr('aria-expanded', this.$element.is(':hidden') ? false : true);
                }

                /**
                 * Initializes events for the toggle trigger.
                 * @function
                 * @private
                 */

            }, {
                key: '_events',
                value: function _events() {
                    this.$element.off('toggle.zf.trigger').on('toggle.zf.trigger', this.toggle.bind(this));
                }

                /**
                 * Toggles the target class on the target element. An event is fired from the original trigger depending on if the resultant state was "on" or "off".
                 * @function
                 * @fires Toggler#on
                 * @fires Toggler#off
                 */

            }, {
                key: 'toggle',
                value: function toggle() {
                    this[this.options.animate ? '_toggleAnimate' : '_toggleClass']();
                }
            }, {
                key: '_toggleClass',
                value: function _toggleClass() {
                    this.$element.toggleClass(this.className);

                    var isOn = this.$element.hasClass(this.className);
                    if (isOn) {
                        /**
                         * Fires if the target element has the class after a toggle.
                         * @event Toggler#on
                         */
                        this.$element.trigger('on.zf.toggler');
                    } else {
                        /**
                         * Fires if the target element does not have the class after a toggle.
                         * @event Toggler#off
                         */
                        this.$element.trigger('off.zf.toggler');
                    }

                    this._updateARIA(isOn);
                    this.$element.find('[data-mutate]').trigger('mutateme.zf.trigger');
                }
            }, {
                key: '_toggleAnimate',
                value: function _toggleAnimate() {
                    var _this = this;

                    if (this.$element.is(':hidden')) {
                        Foundation.Motion.animateIn(this.$element, this.animationIn, function () {
                            _this._updateARIA(true);
                            this.trigger('on.zf.toggler');
                            this.find('[data-mutate]').trigger('mutateme.zf.trigger');
                        });
                    } else {
                        Foundation.Motion.animateOut(this.$element, this.animationOut, function () {
                            _this._updateARIA(false);
                            this.trigger('off.zf.toggler');
                            this.find('[data-mutate]').trigger('mutateme.zf.trigger');
                        });
                    }
                }
            }, {
                key: '_updateARIA',
                value: function _updateARIA(isOn) {
                    this.$element.attr('aria-expanded', isOn ? true : false);
                }

                /**
                 * Destroys the instance of Toggler on the element.
                 * @function
                 */

            }, {
                key: 'destroy',
                value: function destroy() {
                    this.$element.off('.zf.toggler');
                    Foundation.unregisterPlugin(this);
                }
            }]);

            return Toggler;
        }();

        Toggler.defaults = {
            /**
             * Tells the plugin if the element should animated when toggled.
             * @option
             * @type {boolean}
             * @default false
             */
            animate: false
        };

        // Window exports
        Foundation.plugin(Toggler, 'Toggler');
    }(jQuery);
    "use strict";

    $j(document).foundation();

})($j);

(function () {
    function AddListeners(openBtn, closeBtn, popupElement) {
        var openBtnRef = document.querySelectorAll("." + openBtn);
        var closeBtnRef = document.querySelector("." + closeBtn);
        var popupElementRef = document.querySelector("." + popupElement);

        if (!popupElementRef || !openBtnRef) return;

        openBtnRef.forEach(function (item) {
            item.addEventListener('click', toggler);
        });

        closeBtnRef.addEventListener('click', toggler);

        function toggler() {
            popupElementRef.classList.toggle('hide');
        }
    }

    var callbackPopup = new AddListeners('callback-btn', 'closeCallPopup', 'callbackPopupWrapper');
    var feedbackPopup = new AddListeners('feedBack', 'closeFeedback', 'feedbackPopupWrapper');
    var categoriesMenu = new AddListeners('categories-btn', 'categories-btn', 'categoriesMenuWrapper');

    //brand choice all
    // var brand_check_all = document.getElementById('brand_check_all');

})();

