{if $attrForSale}
    {foreach from=$attrForSale key=key item=attrFS}
        <div class="attr-wrapper"><b>{$key}: </b>
            {foreach from=$attrFS key=k item=attr}
                <a href="javascript:void(0);" class="attr-default" attr_id="{$attr.attribute_id}" id="{$attr.id}">{if $attr.img_id}<img src="{getimg type=mini name=pImage id=$attr.img_id ext=$attr.img_ext}" title="{$attr.name}">{else}{$attr.name}{/if}</a>
            {/foreach}
        </div>    
    {/foreach}
{/if}

<script>
    {literal}
        $(function(){
            dolar_rate = {/literal}{strip}
                {$dolar_rate}     
            {/strip}{literal};
            
            default_price = {/literal}{strip}
                {$product->price}     
            {/strip}{literal};
            
            default_count = {/literal}{strip}
                {if $totalQuantity}{$totalQuantity}{else}{$product->quantity}{/if}     
            {/strip}{literal};
            
            default_id = {/literal}{strip}
                {$product->id}     
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
            
            if ($('.img-gal-box:not([attr_id]):first').find('img').length) {
                default_picture = $('.img-gal-box:not([attr_id]):first').find('img').attr('src');    
            } else {
                default_picture = $('.product-img').find('img').attr('src');
            }
            
            cart_button = $('a.button-cart');
            cart_label = cart_button.find('div');
            cart_default_text = cart_label.html();
            cart_added_text = 'В корзине. Перейти?';
            cart_top_div = $('.cart-block');
            
            comp_button = $('a.a-button.pa_compare');
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
                        {literal}}{/literal}{if !$smarty.foreach.tr.last},{literal}{{/literal}{/if}
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
                        cart_label.html('Добавление...');
                        $('#cartForm').rexSubmit(function(data){
                            if (data != false) {
                                $.rex('cart', 'cart', {}, function(data){
                                    if (data !== false) {
                                        cart_top_div.html(data);    
                                    }
                                });
                                cart_label.html(cart_added_text);
                                cart_button.addClass('cart-added');
                                skus[current_sku].isset = 1;
                            }
                        });
                    }
                }        
            });
            
            // автовыбор скусов
            checkCount();
            if (skuSelected != 0) {
                for (var i in skuSelected) {
                    $('#'+skuSelected[i]).click();
                }        
            } else {
                $('.attr-default:first').click();
            }
            
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
        $('.attr-default').click(function(){
            if (!$(this).hasClass('attr-inactive')) {
                if ($(this).hasClass('attr-active')) {
//                    $(this).removeClass('attr-active');
                    if (!$('.attr-default.attr-active').not('[attr_id='+$(this).attr('id')+'])').length) {
                        $('.img-gal-box[attr_id]').hide().find('a').removeClass('gallery');
                        $('#imageFull').attr('src', default_picture.replace('icon', 'page'));
                        $('#imgfull').attr('href', default_picture.replace('icon', 'main'));
                    }
                    $('.attr-default:not([attr_id='+$(this).attr('attr_id')+']).attr-inactive').removeClass('attr-inactive');
                    getSkusChange();
                } else {
                    $('.attr-default[attr_id='+$(this).attr('attr_id')+']').removeClass('attr-active');
                    $(this).addClass('attr-active');
                    if ($(this).find('img').length) {
                        $('#imageFull').attr('src', $(this).find('img').attr('src').replace('mini', 'page'));
                        $('#imgfull').attr('href', $(this).find('img').attr('src').replace('mini', 'main'));
                        $('.img-gal-box[attr_id]').not('[attr_id='+$(this).attr('id')+'])').hide().find('a').removeClass('gallery');
                        $('.img-gal-box[attr_id='+$(this).attr('id')+']').show().find('a').addClass('gallery');
                    } else if (!$('.attr-default.attr-active img').length) {
                        $('#imageFull').attr('src', default_picture.replace('icon', 'page'));
                        $('#imgfull').attr('href', default_picture.replace('icon', 'main'));
                        $('.img-gal-box[attr_id]').not('[attr_id='+$(this).attr('id')+'])').hide().find('a').removeClass('gallery');
                        $('.img-gal-box[attr_id='+$(this).attr('id')+']').show().find('a').addClass('gallery');
                    }
                    getSkus();
                }
                generateLightbox();
            }
        });
        
        
        // обработчик клика на минус
        $('.count-minus').die('click').live('click', function() {
            if(!$(this).hasClass('count-minus-disabled')) {
                value_count -= 1;
                $('#info-product-count').val(value_count);
                $('.count').html(value_count);
                checkCount();
            }
        });
        
        // обработчик клика на плюс
        $('.count-plus').die('click').live('click', function() {
            if(!$(this).hasClass('count-plus-disabled')) {
                value_count += 1;
                $('#info-product-count').val(value_count);
                $('.count').html(value_count);
                checkCount();
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
        function checkCount()
        {
            var count = eval($('.total-quantity').html());
            if (eval($('#info-product-count').val()) >= count) {
                $('#info-product-count').val(count);
                $('.count').html(count);
                value_count = eval(count);
                $('.count-plus').addClass('count-plus-disabled');
            } else {
                $('.count-plus').removeClass('count-plus-disabled');
            }
            if (eval($('#info-product-count').val()) < 2) {
                $('.count-minus').addClass('count-minus-disabled');
            } else {
                $('.count-minus').removeClass('count-minus-disabled');
            }
        }
        
        // не трогать, функция для проверки соответствия выбранных аттрибутов и скусов (артикулов)
        function getSkus()
        {
            var attrSkuID = [];
            $('.attr-default.attr-active').each(function(index){
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
                    } else if (count > 0) {
                        for (var z in skus[y].elements) {
                            if (z != matchID) {
                                if (skus[y].quantity == 0) {
                                    $('#'+skus[y].elements[z]).addClass('attr-inactive');    
                                } else {
                                    $('#'+skus[y].elements[z]).removeClass('attr-inactive');
                                }
                            }
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
            
            if (typeof sku != 'undefined') {
                priceChange = sku.price;
                countChange = sku.quantity;
                $('.mystical-button').hide();
                if ($('.attributes-hidden').length) {
                    $('.attributes-hidden').val(sku.id);
                } else {
                    var block = '<input type="hidden" class="attributes-hidden" name="cart[sku]" value="'+sku.id+'">';
                    $('#cartForm').append(block);
                }
                $('#product-id').html(default_id+'-'+sku.id);
                $('a.button-cart').addClass('button-cart-active');
                $('a.pa_compare').addClass('button-cart-active');
                $('.button-cart-active').css('opacity', 1);
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
            } else {
                $('.mystical-button').show();
                $('.attributes-hidden').remove();
                $('#product-id').html(default_id);
                $('a.button-cart').css('opacity', 0.6).removeClass('button-cart-active');
                $('a.pa_compare').css('opacity', 0.6).removeClass('button-cart-active');
            }
            
            var priceGrnChange = Number(priceChange*dolar_rate).toFixed(0);
            $('.product-discription .price').html('$'+priceChange);
            $('.product-discription .product-price-sale-full').html('$'+sku.price_old);
            $('.total-quantity').html(countChange);
            checkCount();
        }
        
        // функция загрузки картинок
        function loadImg (id, ext){
            prev_id = id;
            document.getElementById("imageFull").setAttribute("src",  "/content/images/pimage/"+id+"/page."+ext);
            document.getElementById("imgfull").setAttribute("href", "/content/images/pimage/"+id+"/main."+ext); 
            console.log(id);               
            //$('.a_gallery').lightBox();
            generateLightbox();
        }
    {/literal}
</script>