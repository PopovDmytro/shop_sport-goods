/*!
 * jquery.lightbox.js
 * https://github.com/duncanmcdougall/Responsive-Lightbox
 * Copyright 2013 Duncan McDougall and other contributors; @license Creative Commons Attribution 2.5
 *
 * Options: 
 * margin - int - default 50. Minimum margin around the image
 * nav - bool - default true. enable navigation
 * blur - bool - default true. Blur other content when open using css filter
 * minSize - int - default 0. Min window width or height to open lightbox. Below threshold will open image in a new tab.
 *
 */
(function ($) {

    'use strict';

    $.fn.lightbox = function (options) {

        var opts = {
            margin: 50,
            nav: true,
            blur: true,
            minSize: 0
        };

        var plugin = {

            items: [],
            lightbox: null,
            image: null,
            current: null,
            locked: false,
            caption: null,

            init: function (items) {
                plugin.items = items;
                plugin.selector = "lightbox-"+Math.random().toString().replace('.','');
                var id = 'lightbox-' + Math.floor((Math.random() * 100000) + 1);
                if (!plugin.lightbox) {
                    $('body').append(
                        '<div id="'+id+'" class="lightbox" style="display:none;">'+
                        '<a href="#" class="lightbox-close lightbox-button"></a>' +
                        '<div class="lightbox-nav">'+
                        '<a href="#" class="lightbox-previous lightbox-button"></a>' +
                        '<a href="#" class="lightbox-next lightbox-button"></a>' +
                        '</div>' +
                        '<div href="#" class="lightbox-caption"><p></p></div>' +
                        '</div>'
                    );

                    plugin.lightbox = $("#"+id);
                    plugin.caption = $('.lightbox-caption', plugin.lightbox);
                }

                if (plugin.items.length > 1 && opts.nav) {
                    $('.lightbox-nav', plugin.lightbox).show();
                } else {
                    $('.lightbox-nav', plugin.lightbox).hide();
                }

                plugin.bindEvents();

            },

            loadImage: function () {
                if(opts.blur) {
                    $("body").addClass("blurred");
                }
                $("img", plugin.lightbox).remove();
                plugin.lightbox.fadeIn('fast').append('<span class="lightbox-loading"></span>');

                var img = $('<img src="' + $(plugin.current).attr('href') + '" draggable="false">');

                $(img).load(function () {
                    $('.lightbox-loading').remove();
                    plugin.lightbox.append(img);
                    plugin.image = $("img", plugin.lightbox).hide();
                    plugin.resizeImage();
                    plugin.setCaption();
                });
            },

            setCaption: function () {
                var caption = $(plugin.current).data('caption');
                if(!!caption && caption.length > 0) {
                    plugin.caption.fadeIn();
                    $('p', plugin.caption).text(caption);
                }else{
                    plugin.caption.hide();
                }
            },

            resizeImage: function () {
                var ratio, wHeight, wWidth, iHeight, iWidth;
                wHeight = $(window).height() - opts.margin;
                wWidth = $(window).outerWidth(true) - opts.margin;
                plugin.image.width('').height('');
                iHeight = plugin.image.height();
                iWidth = plugin.image.width();
                if (iWidth > wWidth) {
                    ratio = wWidth / iWidth;
                    iWidth = wWidth;
                    iHeight = Math.round(iHeight * ratio);
                }
                if (iHeight > wHeight) {
                    ratio = wHeight / iHeight;
                    iHeight = wHeight;
                    iWidth = Math.round(iWidth * ratio);
                }
                plugin.image.width(iWidth).height(iHeight).css({
                    'top': ($(window).height() - plugin.image.outerHeight()) / 2 + 'px',
                    'left': ($(window).width() - plugin.image.outerWidth()) / 2 + 'px'
                }).show();
                plugin.locked = false;
            },

            getCurrentIndex: function () {
                return $.inArray(plugin.current, plugin.items);
            },

            next: function () {
                if (plugin.locked) {
                    return false;
                }
                plugin.locked = true;
                //if (plugin.getCurrentIndex() >= plugin.items.length - 1) {
                //    $(plugin.items[0]).click();
                //} else {
                //    $(plugin.items[plugin.getCurrentIndex() + 1]).click();
                //}
                var curentIndex = plugin.getCurrentIndex();
                if ( curentIndex >= plugin.items.length - 1 ) {
                    if ( plugin.items[0].className.indexOf('gallery') != -1 ) {     // проверка сначала
                        $(plugin.items[0]).click();
                    } else {
                        var i_begin = 0;
                        var ten_begin = -1;
                        while ( ten_begin == -1 && ++i_begin < plugin.items.length - 1 ) {
                            ten_begin = plugin.items[i_begin].indexOf('gallery');
                        }
                        if ( i_begin >= plugin.items.length - 1) {     // возврат в 0
                            $(plugin.items[0]).click();
                        } else {
                            $(plugin.items[i_begin]).click();
                        }
                    }
                } else {
                    if ( plugin.items[curentIndex + 1].className.indexOf('gallery') != -1) {    // с текущего индекса
                        $(plugin.items[curentIndex + 1]).click();
                    } else {
                        var i_next = 0;
                        var ten_next = -1;
                        while ( ten_next == -1 && curentIndex + ++i_next <= plugin.items.length - 1 ) {
                            ten_next = plugin.items[curentIndex + i_next].className.indexOf('gallery');
                        }
                        if ( curentIndex + i_next < plugin.items.length ) {
                            if ( ten_next != -1 ) {
                                $(plugin.items[curentIndex + i_next]).click();
                            }
                        } else {
                            if ( plugin.items[1].className.indexOf('gallery') != -1 ) {     // проверка сначала
                                $(plugin.items[1]).click();
                            } else {
                                var i_begin2 = 1;
                                var ten_begin2 = -1;
                                while ( ten_begin2 == -1 && ++i_begin2 < plugin.items.length - 1 ) {
                                    ten_begin2 = plugin.items[i_begin2].indexOf('gallery');
                                }
                                if ( i_begin2 >= plugin.items.length - 1 ) {     // возврат в 0
                                    $(plugin.items[0]).click();
                                } else {
                                    $(plugin.items[i_begin2]).click();
                                }
                            }
                        }
                    }
                }
            },

            previous: function () {
                if (plugin.locked) {
                    return false;
                }
                plugin.locked = true;
                //if (plugin.getCurrentIndex() <= 0) {
                //    $(plugin.items[plugin.items.length - 1]).click();
                //} else {
                //    $(plugin.items[plugin.getCurrentIndex() - 1]).click();
                //}
                var curentIndex = plugin.getCurrentIndex();
                if (curentIndex <= 1) {
                    if ( plugin.items[plugin.items.length - 1].className.indexOf('gallery') != -1) {    // проверка с конца
                        $(plugin.items[plugin.items.length - 1]).click();
                    } else {
                        var i_end = 0;
                        var ten_end = -1;
                        while ( ten_end == -1 && plugin.items.length >= ++i_end) {
                            ten_end = plugin.items[plugin.items.length - i_end].className.indexOf('gallery');
                        }
                        if ( ten_end != -1 ) {
                            $(plugin.items[plugin.items.length - i_end]).click();
                        }
                    }
                } else {
                    if ( plugin.items[curentIndex - 1].className.indexOf('gallery') != -1) {    // с текущего индекса
                        $(plugin.items[curentIndex - 1]).click();
                    } else {
                        var i_prev = 0;
                        var ten_prev = -1;
                        while ( ten_prev == -1 && ++i_prev <= curentIndex ) {
                            ten_prev = plugin.items[curentIndex - i_prev].className.indexOf('gallery');
                        }
                        if ( i_prev <= curentIndex ) {      // не дойшли к началу
                            if ( ten_prev != -1 ) {
                                $(plugin.items[curentIndex - i_prev]).click();
                            }
                        } else {
                            if ( plugin.items[plugin.items.length - 1].className.indexOf('gallery') != -1) {    // проверка с конца
                                $(plugin.items[plugin.items.length - 1]).click();
                            } else {
                                var i_end2 = 0;
                                var ten_end2 = -1;
                                while ( ten_end2 == -1 && plugin.items.length >= ++i_end2) {
                                    ten_end2 = plugin.items[plugin.items.length - i_end2].className.indexOf('gallery');
                                }
                                if ( ten_end2 != -1 ) {
                                    $(plugin.items[plugin.items.length - i_end2]).click();
                                }
                            }
                        }
                    }
                }
            },

            bindEvents: function () {
                $(plugin.items).click(function (e) {
                    if(!plugin.lightbox.is(":visible") && ($(window).width() < opts.minSize || $(window).height() < opts.minSize)) {
                        $(this).attr("target", "_blank");
                        return;
                    }
                    var self = $(this)[0];
                    e.preventDefault();
                    plugin.current = self;
                    plugin.loadImage();

                    // Bind Keyboard Shortcuts
                    $(document).on('keydown', function (e) {
                        // Close lightbox with ESC
                        if (e.keyCode === 27) {
                            plugin.close();
                        }
                        // Go to next image pressing the right key
                        if (e.keyCode === 39) {
                            plugin.next();
                        }
                        // Go to previous image pressing the left key
                        if (e.keyCode === 37) {
                            plugin.previous();
                        }
                    });
                });

                // Add click state on overlay background only
                plugin.lightbox.on('click', function (e) {
                    if (this === e.target) {
                        plugin.close();
                    }
                });

                // Previous click
                $(plugin.lightbox).on('click', '.lightbox-previous', function () {
                    plugin.previous();
                    return false;
                });

                // Next click
                $(plugin.lightbox).on('click', '.lightbox-next', function () {
                    plugin.next();
                    return false;
                });

                // Close click
                $(plugin.lightbox).on('click', '.lightbox-close', function () {
                    plugin.close();
                    return false;
                });

                $(window).resize(function () {
                    if (!plugin.image) {
                        return;
                    }
                    plugin.resizeImage();
                });
            },

            close: function () {
                $(document).off('keydown'); // Unbind all key events each time the lightbox is closed
                $(plugin.lightbox).fadeOut('fast');
                $('body').removeClass('blurred');
            }
        };

        $.extend(opts, options);

        plugin.init(this);
    };

})(jQuery);