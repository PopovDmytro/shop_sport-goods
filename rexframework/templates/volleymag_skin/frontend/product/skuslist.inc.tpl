{if $attrForSale}
    {foreach from=$attrForSale key=key item=attrFS}
        {if $key eq 'Цвет'}
            <span class="choice-color"> {if $navCategoryList.0.alias eq 'sportivnoe-pitanie'}Выберите вкус:{else}Выберите цвет:{/if}</span>
            <span class="attr-wrapper slider4">
                {foreach from=$attrFS key=k item=attr}
                    <div class="slide-attr" title="{$attr.name}">
                        <a href="javascript:void(0);" class="attr-default color-box" data-sku-article="{$attr.sku_article}" attr_id="{$attr.attribute_id}" id="{$attr.id}">{if $attr.img_id}<img src="{getimg type=mini name=pImage id=$attr.img_id ext=$attr.img_ext}" title="{$attr.name}">{else}{$attr.name}{/if}</a>
                    </div>
                {/foreach}
            </span>
        {else}
            <span class="attr-wrapper size"><span>{$key}:</span>
                <select class="titlex" title="{$key}">
                    {foreach from=$attrFS key=k item=attr name=size}
                        <option class="attr-default {*{if $smarty.foreach.size.first}attr-active{/if}*}" attr_id="{$attr.attribute_id}" id="{$attr.id}">{if $attr.img_id}<img src="{getimg type=mini name=pImage id=$attr.img_id ext=$attr.img_ext}" title="{$attr.name}">{else}{$attr.name}{/if}
                        </option>
                    {/foreach}
                </select>
            </span>
        {/if}
    {/foreach}
{/if}

<script>
    {literal}
    $(document).ready(function (){
        $('.slider4').bxSlider({
            slideWidth: 70,
            slideMargin: 5,
            minSlides: 3,
            maxSlides: 4,
            startSlide: 0,
            auto: false,
            infiniteLoop: false,
            pager: false,
            prevText: '',
            nextText: '',
        });

        var element = $('.attr-wrapper.size').find('span')[0];
        if (typeof(element) != 'undefined' && element.clientHeight < 16) {
            element.style.cssText = "margin-top: 4%";
        }
    });

    function ShowTabAcc(id, tab) {
        if ($(tab).hasClass('active')) {
            $(tab).removeClass('active');
            $('#tab-'+id).slideUp();
        } else {
            $(tab).addClass('active');
            $('#tab-'+id).slideDown();
        }
        $('div.tab-box:not(#tab-'+id+')').slideUp();
        $('div.tabs > div:not(.tab'+id+') > a').removeClass('active');
    }


    $(function(){
        dolar_rate = {/literal}{strip}
        {$dolar_rate}
        {/strip}{literal};

        default_price = {/literal}{strip}
        {$product->price_old}
        {/strip}{literal};

        crutch_price = {/literal}{strip}
        {$product->price}
        {/strip}{literal};

        default_count = {/literal}{strip}
        {if $totalQuantity}{$totalQuantity}{else}{$product->quantity}{/if}
        {/strip}{literal};

        default_id = {/literal}{strip}
        {$product->id}
        {/strip}{literal};

        default_sale = {/literal}{strip}
        {$product->sale}
        {/strip}{literal};

        current_sku = 0;
        value_count = 1;

        skuSelected = {/literal}{strip}
        {if $skuSelected}
                [{foreach from=$skuSelected key=key item=skuElem name=skuEl}
                    '{$skuElem.attr2prod_id}'
                    {if !$smarty.foreach.skuEl.last},{/if}
                    {/foreach}]
        {else}0{/if}
        {/strip}{literal};

        default_picture = '';

        if ($('.photo-gallery li:not([attr_id]):first').find('img').length) {
            default_picture = $('.photo-gallery li:not([attr_id]):first').find('img').attr('src');
        } else {
            default_picture = $('#imgfull').find('img').attr('src');
        }

        cart_button = $('#product-page a.button-cart');
        cart_label = cart_button.find('span');
        cart_default_text = cart_label.html();
        cart_added_text = 'В корзине!';

        comp_button = $('#product-page a.a-button.pa_compare');
        comp_label = comp_button;
        comp_default_text = comp_label.html();
        comp_added_text = 'Просмотр';

        $('.img-gal-box[attr_id]').hide().find('a').removeClass('gallery');
        generateLightbox();
        // заполнение массива артикулов
        skus = new Array({{/literal}{strip}
            {if $attrForSale}
            {foreach from=$skus key=k item=sku name=tr}
            {foreach from=$sku key=key item=sku_elem name=tr_sku}
            {if $key eq "skus_elem"}
            elements:{literal}[{/literal}
                {foreach from=$sku_elem key=k_sku item=sku_one name=tr_sku_one}
                {$sku_one.attr2prod_id}
                {if !$smarty.foreach.tr_sku_one.last},{/if}
                {/foreach}
                {literal}]{/literal}
        {else}
        {$key}:"{$sku_elem}"
        {/if}
        {if !$smarty.foreach.tr_sku.last},{/if}
        {/foreach}
        {literal}}
        {/literal}{if !$smarty.foreach.tr.last},{literal}{{/literal}{/if}
            {/foreach}
            {else}
        }
        {/if}
        {/strip}{literal});

        // обработчик клика по кнопке корзины
        cart_button.die('click').live('click', function(){
            if ($(this).hasClass('button-cart-active')) {
                if (cart_button.hasClass('cart-added')) {
                    window.location.href = '/cart/';
                } else {
                    cart_label.html('Добавление..');
                    $('#cartForm').rexSubmit(function(data){
                        if (data != false) {
                            $.rex('cart', 'cart', {}, function(data){
                                if (data !== false) {
                                    $('.basket-holder').each(function(){
                                        $(this).replaceWith(data)
                                    });
                                }
                            });
                            cart_label.html(cart_added_text);
                            cart_button.addClass('cart-added');
                            skus[current_sku].isset = 1;
                            $('.basket-href .basket-right').addClass('adding');
                            setTimeout(function () {
                                $('.basket-href .basket-right').removeClass('adding')
                            }, 1500);
                        }
                    });
                }
            } else {
                return false;
            }
        });

        // автовыбор скусов
        //checkCount();
        /*if (skuSelected != 0) {
         for (var i in skuSelected) {
         $('#'+skuSelected[i]).click();
         }
         } else {
         $('.attr-default:first').click();
         $('[attr_id=1]:first').trigger('click').trigger('click');
         }*/

        // обработчик клика по кнопке сравнения
        comp_button.die('click').live('click', function(){
            if ($(this).hasClass('button-cart-active')) {
                if (!$(this).hasClass('comp-added')) {
                    var result = compareProduct($(this).attr('pid'));
                    if (result === true) {
                        comp_label.html(comp_added_text);
                        comp_button.addClass('comp-added');
                        skus[current_sku].comp_isset = 1;
                    }
                } else {
                    window.location.href = '/compare/';
                }
            }
        });
    });

    // обработчик клика по артикулу
    if ($.browser.webkit) {
        $('.titlex').change(function () {
            var cur_value = $('option:selected',this);
            if (!cur_value.hasClass('attr-inactive')) {
                if (cur_value.hasClass('attr-active')) {
//                        cur_value.removeClass('attr-active');
                    getSkusChange();
                } else {
                    $('.attr-default[attr_id='+cur_value.attr('attr_id')+']').removeClass('attr-active');
                    cur_value.addClass('attr-active');
                    getSkus();
                }
                generateLightbox();
            } else if (cur_value.hasClass('attr-inactive') && cur_value.attr('attr_id') == 1) {
                $('.attr-default.attr-active').each(function(){
                    cur_value.trigger('click');
                });
                cur_value.trigger('click');
            }
        });
    }

    function writeSkuIDElement(skuID)
    {
        if (skuID) {
            if ($('.attributes-hidden').length) {
                $('.attributes-hidden').val(skuID);
            } else {
                var block = '<input type="hidden" class="attributes-hidden" name="cart[sku]" value="' + skuID + '">';
                $('#product-page #cartForm').append(block);
            }
        }
    }

    function getSkuIDByElement(skuElementID, skuArticle)
    {
        var skuID = false;
        if (!skus) {
            return false;
        }

        for (var y in skus) {
            var skuEl = skus[y];
            if (($.inArray(skuElementID, skuEl.elements) !== -1) && parseInt(skuEl.quantity) > 0 && skuEl.sku_article === skuArticle) {
                skuID = skuEl.id;
                break;
            }
        }

        return skuID;
    }

    function writeSkuIDByOption(option)
    {
        if (!option.length) {
            return false;
        }

        var skuID       = false,
                skuArticle  = $('.attr-default.color-box.attr-active').data('sku-article'),
                skuElemID   = option.attr('id');


        if (!skus || !skuArticle) {
            return false;
        }

        skuElemID = parseInt(skuElemID);
        skuID = getSkuIDByElement(skuElemID, skuArticle);
        writeSkuIDElement(skuID);
    }

    $('.attr-default').click(function() {
        var sizeSelect = $('.attr-wrapper.size select');
        if (!$(this).hasClass('attr-inactive')) {
            if ($(this).hasClass('attr-active')) {
                //$(this).removeClass('attr-active');
                if (!$('.attr-default.attr-active').not('[attr_id='+$(this).attr('id')+'])').length) {
                    $('.img-gal-box[attr_id]').hide().find('a').removeClass('gallery');
                    $('#imageFull').attr('src', default_picture.replace('icon', 'defmain'));
                    $('#imgfull').attr('href', default_picture.replace('icon', 'main'));
                }
                $('.attr-default:not([attr_id='+$(this).attr('attr_id')+']).attr-inactive').removeClass('attr-inactive');
                getSkusChange();
            } else {
                $('.attr-default[attr_id='+$(this).attr('attr_id')+']').removeClass('attr-active');
                $(this).addClass('attr-active');
                if ($(this).find('img').length) {
                    $('#imageFull').attr('src', $(this).find('img').attr('src').replace('mini', 'defmain'));
                    $('#imgfull').attr('href', $(this).find('img').attr('src').replace('mini', 'main'));
                    $('.img-gal-box[attr_id]').not('[attr_id='+$(this).attr('id')+'])').hide().find('a').removeClass('gallery');
                    $('.img-gal-box[attr_id='+$(this).attr('id')+']').show().find('a').addClass('gallery');
                } else if (!$('.attr-default.attr-active img').length) {
                    $('#imageFull').attr('src', "/skin/volleymag_skin/frontend/img/defmain.jpg");
                    $('#imgfull').attr('href', default_picture.replace('icon', 'main'));
                    $('.img-gal-box[attr_id]').not('[attr_id='+$(this).attr('id')+'])').hide().find('a').removeClass('gallery');
                    $('.img-gal-box[attr_id='+$(this).attr('id')+']').show().find('a').addClass('gallery');
                }
                getSkus();
            }
            generateLightbox();
        } else if ($(this).hasClass('attr-inactive') && $(this).attr('attr_id') == 1) {
            $('.attr-default.attr-active').each(function(){
                $(this).trigger('click');
            });
            sizeSelect.find('option').removeClass('attr-active');
            $(this).trigger('click');
        }

        var notActiveOption = sizeSelect.find('option:not(.attr-inactive):first');
        sizeSelect.val(notActiveOption.val());

        writeSkuIDByOption(notActiveOption);
    });

    $(document).on('change', '.attr-wrapper.size select', function() {
        writeSkuIDByOption($(this).find('option:selected'));
    });

    // обработчик клика на минус
    $('.count-minus').die('click').live('click', function() {
        if(!$(this).hasClass('count-minus-disabled')) {
            value_count -= 1;
            if (value_count < 1) {
                value_count = 1;
            }
            $('#info-product-count').val(value_count);
            $('.count').html(value_count);
            //checkCount();
        }
    });

    // обработчик клика на плюс
    $('.count-plus').die('click').live('click', function() {
        if(!$(this).hasClass('count-plus-disabled')) {
            value_count += 1;
            $('#info-product-count').val(value_count);
            $('.count').html(value_count);
            //checkCount();
        }
    });

    // функция для сравнения
    function compareProduct(product_id){
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

    // функция для изменения количества по клику на +/-
    /*function checkCount()
     {
     var count = eval($('.total-quantity').html());
     if (eval($('#info-product-count').val()) >= count) {
     $('#info-product-count').val(count);
     $('#product-page .count').html(count);
     value_count = eval(count);
     $('#product-page .count-plus').addClass('count-plus-disabled');
     } else {
     $('#product-page .count-plus').removeClass('count-plus-disabled');
     }
     if (eval($('#info-product-count').val()) < 2) {
     $('#product-page .count-minus').addClass('count-minus-disabled');
     } else {
     $('#product-page .count-minus').removeClass('count-minus-disabled');
     }
     }*/

    // не трогать, функция для проверки соответствия выбранных аттрибутов и скусов (артикулов)
    function getSkus()
    {
        var skuIsFind = false;
        var attrSkuID = [];
        $('#product-page .attr-default.attr-active').each(function(index){
            attrSkuID[index] = $(this).attr('id');
        });
        /*if (attrSkuID.length == skus[0].elements.length) {*/

        for (var y in skus) {
            var count = 0;
            var matchID = 0;
            for (var z in skus[y].elements) {
                for (var i in attrSkuID) {
                    if (parseInt(attrSkuID[i]) == parseInt(skus[y].elements[z])) {
                        count += 1;
                        matchID = z;
                    }
                }
            }
            if (count == skus[y].elements.length) {
                current_sku = y;
                getSkusChange(skus[y]);
                skuIsFind = true;
            } else if (count > 0) {
                for (var z in skus[y].elements) {
                    if (z != matchID) {
                        var el = $('#'+skus[y].elements[z]);
                        if (skus[y].quantity == 0) {
                            el.addClass('attr-inactive');
                        } else {
                            el.removeClass('attr-inactive');
                        }
                    }
                }
                if (!skuIsFind) {
                    getSkusChange(skus[y]);
                }
            }
        }
        /*}*/
    }


    // трогать, функция для всех изменений в html коде после нахождения соответствий атрибутов и скусов (артикулов)
    // если sku не был передан, то возвращаем дефолт
    function getSkusChange(sku)
    {
        var priceChange = default_price;
        var countChange = default_count;
        if (typeof sku != 'undefined' && sku.quantity != 0 ) {
            priceChange = sku.price;
            countChange = sku.quantity;
            $('#product-page .mystical-button').hide();
            writeSkuIDByOption($('.attr-wrapper.size select').find('option:not(.attr-inactive):first'));
            if ($('.attributes-hidden').length) {
                 $('.attributes-hidden').val(sku.id);
            } else {
                 var block = '<input type="hidden" class="attributes-hidden" name="cart[sku]" value="'+sku.id+'">';
                 $('#product-page #cartForm').append(block);
            }

            //$('#product-id').html(default_id+'-'+sku.sku_article);
            $('#product-page #product-id').html(sku.sku_article);
            $('#product-page a.button-cart').addClass('button-cart-active');
            $('#product-page a.pa_compare').addClass('button-cart-active');
            $('#product-page .button-cart-active').css('opacity', 1);
            if (typeof sku.comp_isset != 'undefined') {
                comp_label.html(comp_added_text);
                comp_button.addClass('comp-added');
            } else {
                comp_label.html(comp_default_text);
                comp_button.removeClass('comp-added');
            }
            if (typeof sku.isset != 'undefined') {
                cart_label.html(cart_added_text);
                cart_button.addClass('cart-added');
            } else {
                cart_label.html(cart_default_text);
                cart_button.removeClass('cart-added');
            }
        } else if ($('#product-page .attr-default.attr-active').length && $('#product-page .attr-default.attr-active').attr('attr_id') == 1) {
            return;
        } else {
            $('#product-page .mystical-button').show();
            $('#product-page .attributes-hidden').remove();
            //$('#product-id').html(default_id);
            $('#product-page a.button-cart').css('opacity', 0.6).removeClass('button-cart-active');
            $('#product-page a.pa_compare').css('opacity', 0.6).removeClass('button-cart-active');
        }

        if(sku.sale){
            $('#product-page .buy-block .cost').html(Math.floor(priceChange - (priceChange * sku.sale / 100)) + ' грн');
            $('#product-page .buy-block  .product-price-sale-full').html(priceChange + ' грн');
        }else{
            if (!priceChange) {
                priceChange = crutch_price;
            }
            $('#product-page .buy-block .cost').html(priceChange+' грн');
        }
        $('.total-quantity').html(countChange);
        //checkCount();
    }

    // функция загрузки картинок
    function loadImg (id, ext){
        prev_id = id;
        document.getElementById("imageFull").setAttribute("src",  "/content/images/pimage/"+id+"/defmain."+ext);
        document.getElementById("imgfull").setAttribute("href", "/content/images/pimage/"+id+"/main."+ext);
        //$('.a_gallery').lightBox();
        generateLightbox();
    }
    {/literal}
</script>