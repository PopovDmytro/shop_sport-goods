$(document).ready(function () {
    var comp_button = $('a.pa_compare');
    /* блок слайдера */
    /*var bxSliderElem = $('#slider-wrapper').bxSlider({
            displaySlideQty: 1,
            moveSlideQty: 1,
            auto: true,
            pager: true,
            pause: 5000
    });*/

    // sliders();

    comp_button.off('click').on('click', function () {
        if ($(this).hasClass('button-cart-active')) {
            if (!$(this).hasClass('comp-added')) {
                $(this)
                var result = compareProduct($(this).attr('pid'));
                if (result === true) {
                    window.location.href = '/compare/';
                }
            } else {
                window.location.href = '/compare/';
            }
        }
    });

    /* конец блока слайдера */
    generateLightbox(); // генерация лайтбокса
    /* блок автокопмлита */

    $('a.gallery').lightbox();

    $("#search_inp").autocomplete("/autocomplete/", {
        selectFirst: false,
        minLength: 2,
        width: 420,
        scrollHeight: 600,
        max: 30,
        formatItem: function (data, i, n, value) {
            image = value.split('=')[0];
            product = value.split('=')[1];
            name = value.split('=')[2];

            if (image == 'lastitems') {
                return '<a class="all_items" style="position:relative; bottom:0; " href="javascript: void();">' + name + '</a>';
            }

            price = value.split('=')[3];
            fullprice = value.split('=')[4];
            return '<a href="javascript: void();" ><div style="width:80px; padding-right: 10px; float: left; "><img src="' + image + '" style="vertical-align: middle;width: 60px;height: 60px;"/></div><div class="a_descr" style="padding-top: 13px;padding-right: 75px;word-wrap : break-word;">' + name + '</div><div class="price_field" style="float: right;padding-bottom: 16px;position: absolute;top: 12px;margin-left: 328px;font-weight: bold;color: #EA4556;"><div class="a_fullprice">' + fullprice + '</div><div class="real_price">' + price + '</div><div class="a_valu">грн</div></div></a>';

        }, formatResult: function (data, i, n) {
            return i.split('=')[2];
        }
    }).result(function (event, item) {
        if (item[0].split('=')[0] == 'lastitems') {
            location.href = item[0].split('=')[1];
        } else {
            location.href = '/product/' + item[0].split('=')[1];
        }


        return false;
    });
    /* конец блока автокомплита */


    $("#search_city").autocomplete("/autocompletecity/", {
        selectFirst: false,
        minLength: 2,
        width: 420,
        scrollHeight: 400,
        max: 30,
        formatItem: function (data, i, n, value) {
            city = value.split('=')[0];
            city_id = value.split('=')[1];
            //return '<option value="'+id+'">'+city+'</option>';
            return '<div class="city" cid="' + city_id + '" value="' + city + '">' + city + '</div>'

        },
        formatResult: function (data, i, n) {
            return i.split('=')[0];
        }
    }).result(function (event, item) {
        var id = item[0].split('=')[1];
        $('#registr-city-val').val(id).trigger('change');
        return false;
    });

    $("#search_city").on('change', function () {
        $(this).next().val(0).trigger('change');
    });


    /* блок для мини-слайдера */
    /*$("#slider-range").slider({
        range: true,
        min: 0,
        max: 500,
        values: [ 75, 300 ],
        slide: function( event, ui ) {
            $( "#amount" ).val( "грн" + ui.values[ 0 ] + " - грн" + ui.values[ 1 ] );
        }
    });*/
    /*$("#amount").val("грн" + $( "#slider-range" ).slider("values", 0 ) +
    " - грн" + $( "#slider-range" ).slider( "values", 1 ) );*/
    /* конец блока для мини-слайдера */

    $('[rexTitle]').rexTooltip({
        position: 'top',
        titleAttr: true,
        top: -5,
        addedClass: 'rex-title'
    });

    $('#allcontact').click(function () {
        $('#allconact_block').toggle();
    });

    Views();
    var winheight = $(window).height();

    function scroller() {
        if ($(this).scrollTop() > winheight) {
            $('.scroller').css('display', 'block');
        } else {
            $('.scroller').css('display', 'none');
        }
    }

    scroller();

    $(document).scroll(function () {
        scroller();
    });

    $('.scroller').click(function () {
        $('html,body').animate({scrollTop: 0}, 1100);
    });

    $('.button_bell').click(function () {
        var contact = $('.bells_form');
        if (contact.css('display') == 'none') {
            $('.bell-background').show();
            var top = ($(window).height() - contact.height()) / 2;
            contact.css('top', top + 'px');
            contact.css('opacity', 1);
            contact.css('transform', 'scale(1.3)');
            contact.show();
            /*setTimeout(function(){
                $('#cantant_form input:first').trigger('focus');
            }, 200);   */
        }
    });

    $('.login_bell').click(function () {
        var contact = $('.login_form');
        if (contact.css('display') == 'none') {
            $('.bell-background').show();
            var top = ($(window).height() - contact.height()) / 2;
            contact.css('top', top + 'px');
            contact.css('opacity', 1);
            contact.css('transform', 'scale(1.3)');
            contact.show();
        }
    });

    $('.order_bell').click(function () {
        var contact = $('.quick_order_form');
        if (contact.css('display') == 'none') {
            $('.bell-background').show();
            var top = ($(window).height() - contact.height()) / 2;
            contact.css('top', top + 'px');
            contact.css('opacity', 1);
            contact.css('transform', 'scale(1.3)');
            contact.show();
        }
    });

    $(document).keydown(function (e) {
        var code = e.keyCode || e.which;
        if (code == 27 && $('.bell-background').is(':visible')) {
            $('.close-bell').triggerHandler('click');
        }
    });

    $('.bell-background, .close-bell').click(function () {
        var contact = $('.bells_form');
        var login = $('.login_form');
        var quick_order = $('.quick_order_form');
        if (contact.css('display') == 'block') {
            contact.hide();
            contact.css('transform', 'scale(1)');
            contact.css('opacity', 0);
            $('.bell-background').hide();
        }
        if (login.css('display') == 'block') {
            login.hide();
            login.css('transform', 'scale(1)');
            login.css('opacity', 0);
            $('.bell-background').hide();
        }
        if (quick_order.css('display') == 'block') {
            quick_order.hide();
            quick_order.css('transform', 'scale(1)');
            quick_order.css('opacity', 0);
            $('.bell-background').hide();
        }
    });


    $('.user_page_menu a').click(function () {
        var tab_id = $(this).attr('data-tab');

        $('.user_page_menu a').removeClass('active-tab');
        $('.tabs-holder div').removeClass('current');

        $(this).addClass('active-tab');
        $("#" + tab_id).addClass('current');
    })
    /*$('.toggle-btn').click(function(){
      $(this).next( 'ul' ).toggle(); 
    }); */
    $('.toggle-btn').click(function (e) {

        e.preventDefault();
        // hide all span
        var $this = $(this).parent().find('ul');
        $(".toggle-btn + ul").not($this).hide();

        // here is what I want to do
        $this.toggle();

    });

    $('.bells_form .bell_button').click(function () {
        var bels = {};
        bels.name = $('input[name="bell[name]"]').val();
        var len = false
        if (bels.name.length < 3) {
            $('input[name="bell[name]"]').css('border-color', 'red');
            $('.bell-name-error').css('opacity', '1');
            len = true;
        } else {
            $('input[name="bell[name]"]').css('border-color', '#098AC9');
            $('.bell-name-error').css('opacity', '0');
        }
        bels.phone = $('input[name="bell[phone]"]').val();
        if (bels.phone.length < 2) {
            $('input[name="bell[phone]"]').css('border-color', 'red');
            $('.bell-phone-error').css('opacity', '1');
            len = true;
        } else {
            $('input[name="bell[phone]"]').css('border-color', '#098AC9');
            $('.bell-phone-error').css('opacity', '0');
        }

        if (len == true) {
            //messages('Вы не ввели все данные!');
            return false;
        }
        bels.submit = $('input[name="bell[submit]"]').val();
        var data = $.rex('home', 'bels', {bels: bels});
        // console.log(data);
        if (data !== 'false') {
            /*messages(data);
            $('.bells_form').hide();
            $('.bell-background').hide();*/
            $('.bells_form').css('height', '100px');
            $('.bell-body').css('display', 'none');
            $('.submit-form').css('display', 'block');
        } else {
            $('.submit-form-error').css('display', 'block');
            //messages('Произошла ошибка отправки сообщения!');
        }
    });

    $('.quick_order_form .quick_order_button').click(function () {
        var quick_orders = {};
        quick_orders.phone = $('input[name="quick_order[phone]"]').val();
        if (quick_orders.phone.length < 12) {
            $('input[name="quick_order[phone]"]').css('border-color', 'red');
            $('.quick-order-phone-error').css('opacity', '1');
            return false;
        } else {
            $('input[name="quick_order[phone]"]').css('border-color', '#098AC9');
            $('.quick-order-phone-error').css('opacity', '0');
        }

        $('#cartForm').rexSubmit('order', 'quickOrder');
        $('.quick_order_form').css('height', '100px');
        $('.quick-order-body').css('display', 'none');
        $('.quick-order-submit-form').css('display', 'block');
    });
    //contact form
    $('.button_contact').click(function () {
        var contact = $('.contact_form');
        if (contact.css('display') == 'none') {
            $('.contact-background').show();
            var top = ($(window).height() - contact.height()) / 2;
            contact.css('top', top + 'px');
            contact.css('opacity', 1);
            contact.css('transform', 'scale(1.3)');
            contact.show();
            /*setTimeout(function(){
                $('#cantant_form input:first').trigger('focus');
            }, 200);   */
        }
    });

    $('.contact-background, .close-contact').click(function () {
        var contact = $('.contact_form');
        if (contact.css('display') == 'block') {
            contact.hide();
            contact.css('transform', 'scale(1)');
            contact.css('opacity', 0);
            $('.contact-background').hide();
        }
    });

    $('.contact_form .contact_button').on('click', function () {
        var contact = {};
        contact.name = $('#contact-name').val();
        //console.log(contact_name.length);
        var len = false
        //console.log(contact_name.length);
        if (contact.name.length < 3) {
            $('input[name="contact[name]"]').css('border-color', 'red');
            $('.contact-name-error').css('opacity', '1');
            len = true;
        } else {
            $('input[name="contact[name]"]').css('border-color', '#098AC9');
            $('.contact-name-error').css('opacity', '0');
        }
        var regExpression = /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
        contact.email = $('#contact-email').val();
        //console.log(cont_email);
        if (!regExpression.test(contact.email)) {
            $('input[name="contact[email]"]').css('border-color', 'red');
            $('.contact-email-error').css('opacity', '1');
            len = true;
        } else {
            $('input[name="contact[email]"]').css('border-color', '#098AC9');
            $('.contact-email-error').css('opacity', '0');
        }
        contact.text = $('#contact-text').val();
        contact.code = $('.captcha-text').val();
        //console.log(cont_text);
        if (contact.text.length < 2) {
            $('textarea[name="contact[text]"]').css('border-color', 'red');
            $('.contact-text-error').css('opacity', '1');
            len = true;
        } else {
            $('textarea[name="contact[text]"]').css('border-color', '#098AC9');
            $('.contact-text-error').css('opacity', '0');
        }
        /*contact_code = $('#contact-code').val();
        //console.log(cont_code);
        if (contact_code.length < 1) {
            $('input[name="contact[code]"]').css('border-color', 'red');
            len = true;
        }*/

        if (len == true) {
            //messages('Вы не ввели все данные!');
            return false;
        }
        contact.submit = $('input[name="contact[submit]"]').val();
        var data = $.rex('home', 'contact', {contact: contact});
        if (data == 'ok') {
            $('.contact_form').css('height', '100px');
            $('.form-body').css('display', 'none');
            $('.submit-form').css('display', 'block');
            window.location.href = '/contact/';
            //messages('Сообщение успешно отправлено!');
        } else {
            //messages('Произошла ошибка отправки сообщения!');
            $('.captcha-form-error').css('opacity', '1');
            $('input[name="contact[code]"]').css('border-color', '#098AC9');
        }
        //return false;
    });


    //////////////////////////////////////////////////// VIREDS ////////////////////////////////
    /* main page sku start */
    $(document).on('click.changeSku', 'ul.photo-list li.slide a[attr_id]', function () {
        var $productBox = $(this).closest('.info-product'),
            $lastPriceContainer = $productBox.find('.buy-block span.prise-tosale'),
            $fullPriceContainer = $productBox.find('.buy-block span.prise-sale-full'),
            stringPrice = ' грн',
            classActive = 'attr-active',
            classInCart = 'attr--incart';

        if ($(this).hasClass(classActive)) {
            return false;
        } else {
            if ($(this).hasClass(classInCart)) {
                $productBox.off('click.onButtonCart', '.button-cart').on('click.onButtonCart', '.button-cart', function () {
                    window.location.href = '/cart/';
                }).find('.button-cart').text('В корзине!').addClass('button-cart--inactive').removeClass('button-cart--active');
            } else {
                $productBox.off('click.onButtonCart', '.button-cart').on('click.onButtonCart', '.button-cart', function () {
                    addProdByRexSubmit($(this));
                }).find('.button-cart').text('В корзину').removeClass('button-cart--inactive').addClass('button-cart--active');
            }

            $(this).closest('li.slide').siblings().find('a[attr_id]').removeClass(classActive);
            $lastPriceContainer.text($(this).data('lastprice').toString() + stringPrice);
            $fullPriceContainer.text($(this).data('fullprice').toString() + stringPrice);
            $productBox.find('strong.sku-article').text($(this).data('sku_article'));
            $productBox.find('form input[name="cart[1]"]').val($(this).data('attr_value'));
            //$productBox.find('form input[name="cart[sku]"]').val(parseInt($(this).attr('id')));
            $productBox.find('form input[name="cart[sku]"]').val($(this).data('skus_id'));
            $(this).addClass(classActive);
        }
    });


    $(document).on('click.changeSkuAlt', 'ul.list-photo li:not(.slide) a[attr_id]', function () {
        var $productBox = $(this).closest('.parent-list'),
            $priceContainer = $productBox.find('.info-block span.cost'),
            stringPrice = ' грн',
            classActive = 'attr-active',
            classInCart = 'attr--incart';

        if ($(this).hasClass(classActive)) {
            return false;
        } else {
            if ($(this).hasClass(classInCart)) {
                $productBox.off('click.onButtonCart', '.button-cart').on('click.onButtonCart', '.button-cart', function () {
                    window.location.href = '/cart/';
                }).find('.button-cart').text('В корзине!').addClass('button-cart--inactive').removeClass('button-cart--active');
            } else {
                $productBox.off('click.onButtonCart', '.button-cart').on('click.onButtonCart', '.button-cart', function () {
                    //$(this).closest('form').submit();
                    addProdByRexSubmit($(this));
                }).find('.button-cart').text('В корзину').removeClass('button-cart--inactive').addClass('button-cart--active');
            }
            $(this).closest('li').siblings().find('a[attr_id]').removeClass(classActive);
            $priceContainer.text($(this).data('price').toString() + stringPrice);
            $productBox.find('strong.sku-article').text($(this).data('sku_article'));
            $productBox.find('form input[name="cart[1]"]').val($(this).data('attr_value'));
            $productBox.find('form input[name="cart[sku]"]').val($(this).data('skus_id'));
            $(this).addClass(classActive);
        }
    });

    /* main page sku end */

    /* cart sku */
    $(document).on('change.checkSize', 'select.sku-by-color', function () {
        var $button = $('#cartForm input.cart-create'),
            unchecked = false;

        if (parseInt($(this).val()) === 1) {
            $button.addClass('cart-create--inactive');
        } else {
            $('select.sku-by-color').each(function () {
                if (parseInt($(this).val()) === 1) {
                    unchecked = true;
                }
            });

            if (!unchecked) {
                $button.removeClass('cart-create--inactive');
            }
        }
    });

    $('select.sku-by-color').each(function () {
        if (parseInt($(this).val()) === 1) {
            if (!$('#cartForm input.cart-create').hasClass('cart-create--inactive')) {
                $('#cartForm input.cart-create').addClass('cart-create--inactive');
            }
        }
    });


    changeSku();
    /* cart sku end */
    //////////////////////////////////////////////////// VIREDS ////////////////////////////////

    $(document).click(function (event) {
        var toggle_btn = $('.toggle-btn'),
            brand_list = toggle_btn.siblings('ul');
        if ($(event.target).closest(brand_list).length || $(event.target).closest(toggle_btn).length) {
            return;
        }
        if (brand_list.is(':visible')) {
            brand_list.hide();
        }
        event.stopPropagation();
    });

    /* checker for check all inputs in list */
    //////////////////////////////////////////////////// Amadey ////////////////////////////////

    $(document).on('change', '.filter_choose_all', function () {
        var $this = $(this),
            $parent = $this.closest('ul'),
            $checkboxes = $parent.find('input[type="checkbox"]:not(.filter_choose_all)');

        $checkboxes.each(function (i, item) {
            var $item = $(item);

            $item.prop('checked', $this.prop('checked'));
        });
    }).on('change', '.filter_choose_all_list input[type="checkbox"]:not(.filter_choose_all)', function () {
        var $this = $(this),
            $parent = $this.closest('ul'),
            $mainChecker = $parent.find('.filter_choose_all'),
            $checkboxes = $parent.find('input[type="checkbox"]:not(.filter_choose_all)'),
            allChecked = true;

        $checkboxes.each(function (i, item) {
            var $item = $(item);

            allChecked = allChecked && $item.prop('checked');
        });

        $mainChecker.prop('checked', allChecked);
    });
});

function changeSku() {
    if ($('ul.photo-list li.slide a[attr_id]').length) {
        $('ul.photo-list').each(function () {
            $(this).find('li.slide a[attr_id]:first').trigger('click.changeSku');
        });
    } else if ($('ul.list-photo li:not(.slide) a[attr_id]').length) {
        $('ul.list-photo').each(function () {
            $(this).find('li:not(.slide) a[attr_id]:first').trigger('click.changeSkuAlt');
        });
    }
    $('.button-cart:not(.button-cart--inactive):not(.button-cart--active):not(.button-cart-product)').on('click.onButtonCart', function () {
        //$(this).closest('form').submit();
        addProdByRexSubmit($(this));
    });
}

function addProdByRexSubmit(divWithForm) {
    var product_form = divWithForm.closest('form'),
        cart_button = product_form.find('a.button-cart'),
        basket_right = $(document).find('.basket-href .basket-right');
    if (!cart_button.hasClass('cart-added')) {
        cart_button.html('Добавление..');
        product_form.rexSubmit(function (data) {
            if (data != false) {
                $.rex('cart', 'cart', {}, function (data) {
                    if (data !== false) {
                        $('.basket-holder').each(function () {
                            $(this).replaceWith(data);

                            console.log(data);
                        });
                        cart_button.html('В корзине!').attr('href', '/cart/').addClass('cart-added');
                        basket_right.addClass('adding');
                        setTimeout(function () {
                            basket_right.removeClass('adding');
                        }, 1500);
                    }
                });
            }
        });
    }
}

function messages(mes) {
    var box = $('.messagebox');
    box.html(mes);
    box.css('opacity', '1');
    setTimeout(function () {
        box.css('opacity', '0')
    }, 2000);
}

function compareProduct(product_id) {
    var sku = 0;
    if ($('.attributes-hidden').length) {
        sku = $('.attributes-hidden').val();
    }
    var data = $.rex('product', 'ajaxCompare', {product_id: product_id, sku: sku});
    if (data !== false) {
        return true;
    }
    return false;
}

$.rex.base_path = '/';

rexTooltipProp = {
    predelay: 500,
    speed: 'fast',
    afterBody: true,
    quickShow: true
};

$('ul.sf-menu').superfish({
    hoverClass: 'sfHover',
    pathClass: 'overideThisToUse',
    pathLevels: 2,
    delay: 100,
    animation: {
        opacity: 'show'
    },
    speed: 'normal',
    autoArrows: true,
    dropShadows: true,
    disableHI: false,
    onInit: function () {
    },
    onBeforeShow: function () {
    },
    onShow: function () {
    },
    onHide: function () {
    }
});

function mycarousel_initCallback(carousel) {
    $('.jcarousel-control a').bind('click', function () {
        carousel.scroll($.jcarousel.intval($(this).text()));
        return false;
    });

    carousel.clip.hover(function () {
        carousel.stopAuto();
    }, function () {
        carousel.startAuto();
    });

};

function generateLightbox() {
    var uniqGallery = [];
    $('a.gallery').each(function (index) {
        var notUnique = 0;
        for (var i in uniqGallery) {
            if (uniqGallery[i] == $(this).attr('href')) {
                $(this).addClass('not-unique');
                notUnique = 1;
            }
        }
        if (notUnique == 0) {
            $(this).removeClass('not-unique');
            uniqGallery[index] = $(this).attr('href');
        }
    });
    /*$('a.gallery:not(.not-unique)').lightBox({
       imageLoading: '/system/shop/default_skin/frontend/img/lightbox-ico-loading.gif',
       imageBtnClose: '/system/shop/default_skin/frontend/img/lightbox-btn-close.gif',
       imageBtnPrev: '/system/shop/default_skin/frontend/img/lightbox-btn-prev.gif',
       imageBtnNext: '/system/shop/default_skin/frontend/img/lightbox-btn-next.gif'
   });*/
}

/*function sliders(){
    $('.slider6').bxSlider({
       slideWidth: 70,
       slideMargin: 10,
       minSlides: 1,
       maxSlides: 1,
       moveSlides: 2,
       infiniteLoop: false,
       startSlide: 0,
       auto: false,
       pager: false,
       prevText: '',
       nextText: ''
   });

   $('.photo-list li a, .list-photo li a').off('click').on('click', function(){
       var src = $(this).attr('attr_src');
       // console.log($('#models').hasClass('view-list'));
       if (src && src.length > 0) {
           $(this).parents('.parent-list').find('.img-box img').attr('src', src);
       } else {
           if ($('#models').hasClass('view-list active')){
              $(this).parents('.parent-list').find('.img-box img').attr('src', "/skin/volleymag_skin/frontend/img/list.jpg");
           } else {
              $(this).parents('.parent-list').find('.img-box img').attr('src', "/skin/volleymag_skin/frontend/img/page.jpg");
           }
       }
   });
}*/

$(function () {
    if (parseInt(ajax_paging) == 1) {
        $(document).on('click.getAjaxPaging', '.ajax-paging', function () {
            var $link = $('div.pagination li.pagination_div').next('.pagin-item');
            var number_page = $link.find('a').html();
            $.post($link.find('a').attr('href'), {rex_request: 1}, function (data) {
                var json_response = $.parseJSON(data);
                if (json_response !== false) {
                    $('#products-contents .product_slide:last').after(json_response.content.content);
                    $link.removeClass('pagin-item').addClass('pagination_div active').html('<b>' + number_page + '</b>');
                    var countNext = parseInt(json_response.content.count_next);
                    hideShowNext(countNext);
                    // sliders();
                    changeSku();
                }
            });
        });

        var hideShowNext = function (countNext) {
            var $next = $('div.pagination li.pagination_div').next('.pagin-item');
            if (!$next.length) {
                return $('div.ajax-paging').remove();
            }
            var stringNext = '';
            if (countNext == 1) {
                stringNext = 'Показать еще 1';
            } else if (countNext < 5) {
                stringNext = 'Показать еще ' + countNext;
            } else {
                stringNext = 'Показать еще ' + countNext;
            }
            $('div.ajax-paging').html(stringNext);
        };
        hideShowNext(parseInt($('#count_next').val()));
    }

    $('.pagination .back, .pagination .forward').click(function () {
        var clicks = 0;
        var active = $(this).parent().find('ul li.active');
        if ($(this).hasClass('back')) {
            clicks = active.prev();
        } else {
            clicks = active.next();
        }
        if (clicks.length > 0) {
            window.location.href = clicks.find('a').attr('href');
        }
    });
});

function Views() {
    $('#models.view-list, #models.view-plate').click(function () {
        if ($(this).hasClass('active')) {
            return false;
        }
        var modal = 'block';
        if ($(this).hasClass('view-list')) {
            modal = 'list';
            $('.view-list').addClass("active");
            $('.view-plate').removeClass("active");
        } else {
            $('.view-plate').addClass("active");
            $('.view-list').removeClass("active");
        }
        var page = $('.pagination_div.active b').length ? $('.pagination_div.active b').html() : 1;
        var pagelast = $('.pagination_div.active:last b').html();
        //console.log($('.pagination_div.active:last b').html());
        if (this_act == 'search') {
            var q = '';
            var dataPost = {q: q, skinDefault: modal, page: page};
        } else if (this_act == 'archive') {
            var dataPost = {feature: feature, skinDefault: modal, page: page, pagelast: (pagelast) ? pagelast : 0};
        } else {
            var dataPost = {skinDefault: modal, page: page, pagelast: (pagelast) ? pagelast : 0};
        }
        $.ajax({
            url: document.URL,
            async: false,
            type: 'POST',
            data: $.extend(dataPost, {rex_request: 1}),
            success: function (data) {
                var result = JSON.parse(data);
                $('#products-contents').html(result.content.content);
                $('#levels-contents').html(result.content.level);
                changeSku();
            }
        });
        // sliders();
    });
}

$('.obyv').off('click').on('click', function () {
    var block = $('.obyv-block');
    if (block.css('display', 'none')) {
        block.css('display', 'block');
        $('.block-background').css('display', 'block');
    }
});

$('.obyv-close').off('click').on('click', function () {
    var block = $('.obyv-block');
    if (block.css('display', 'block')) {
        block.css('display', 'none');
        $('.block-background').css('display', 'none');
    }
});

$('.odezhda').off('click').on('click', function () {
    var block = $('.odezhda-block');
    if (block.css('display', 'none')) {
        block.css('display', 'block');
        $('.block-background').css('display', 'block');
    }
});

$('.odezhda-close').off('click').on('click', function () {
    var block = $('.odezhda-block');
    if (block.css('display', 'block')) {
        block.css('display', 'none');
        $('.block-background').css('display', 'none');
    }
});

$('.nakolenniki').off('click').on('click', function () {
    var block = $('.nakolenniki-block');
    if (block.css('display', 'none')) {
        block.css('display', 'block');
        $('.block-background').css('display', 'block');
    }
});

$('.nakolenniki-close').off('click').on('click', function () {
    var block = $('.nakolenniki-block');
    if (block.css('display', 'block')) {
        block.css('display', 'none');
        $('.block-background').css('display', 'none');
    }
});

$('.noski').off('click').on('click', function () {
    var block = $('.noski-block');
    if (block.css('display', 'none')) {
        block.css('display', 'block');
        $('.block-background').css('display', 'block');
    }
});

$('.noski-close').off('click').on('click', function () {
    var block = $('.noski-block');
    if (block.css('display', 'block')) {
        block.css('display', 'none');
        $('.block-background').css('display', 'none');
    }
});

$('.block-background').off('click').on('click', function () {
    $('.obyv-block').css('display', 'none');
    $('.odezhda-block').css('display', 'none');
    $('.nakolenniki-block').css('display', 'none');
    $('.noski-block').css('display', 'none');
    $('.block-background').css('display', 'none');
});

$('#subscribe-form').on('submit', function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    data = data + '&mod=user&act=addsubscriber';

    $.post('/index.php', data, function () {
        $('.subscribe-block').html('<h1>Поздравляем! Вы подписались на рассылку!</h1>');
    });
});
