{if $mod eq 'order' and $act eq 'add'}
    <h3 class="head1 ui-tabs-nav ui-helper-reset  ui-widget-header ui-corner-all" style="margin-bottom:15px">Добавление заказа</h3>
{/if}
<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="pid" value="{$pid}">

        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Статус:
                </td>
                <td class="frame-t-td">
                    <select name="entity[status]">
                    <option value="1" {if $entity->status eq 1}selected{/if}>Новый</option>
                    <option value="4" {if $entity->status eq 4}selected{/if}>Оплачивается</option>
                    <option value="2" {if $entity->status eq 2}selected{/if}>Оплачен</option>
                    <option value="5" {if $entity->status eq 5}selected{/if}>Доставляется</option>
                    <option value="7" {if $entity->status eq 7}selected{/if}>Доставлен</option>
                    <option value="3" {if $entity->status eq 3}selected{/if}>Завершен</option>
                    <option value="6" {if $entity->status eq 6}selected{/if}>Отменен</option>
                    <option value="8" {if $entity->status eq 8}selected{/if}>Обрабатывается</option>
                    <option value="9" {if $entity->status eq 9}selected{/if}>СРОЧНО</option>
                    <option value="10" {if $entity->status eq 10}selected{/if}>Возврат</option>
                    <option value="11" {if $entity->status eq 10}selected{/if}>Обмен</option>
                </select>
                </td>
            </tr>
            {if $mod eq 'order' and $act eq 'edit'}
            <tr>
                <td class="frame-t-td-l">
                    Скидка:
                </td>
                <td class="frame-t-td">
                    <input name="entity[sale]" value="{if $discount}{$discount}{else}0{/if}" style="width:25px; text-align:center;" />%
                </td>
            </tr>
            {/if}
            <tr>
                <td class="frame-t-td-l">
                    Принял:
                </td>
                <td class="frame-t-td">
                <input type="hidden" name="entity[operator]" value="{$user->login}" />{$user->login}
                 {*<select name="entity[operator]">
                         <option value="0">Выберите продавца</option>
                        {foreach from=$role key=key item=item}
                        {if $item.role neq 'user'}<option value="{$item.login}">{$item.login}</option>{/if}
                        {/foreach}
                    </select>*}
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Имя:
                </td>
                <td class="frame-t-td">
                    <input type="text" id="autocomplete_name2" name="entity[name_single]" value="{if $entity->name_single}{$entity->name_single}{elseif $last_name}{$last_name}{else}{$entity->name}{/if}" />
                    {if $mod eq 'order' and $act eq 'add'}<input type="hidden" name="entity[user_id]" id="user_id" value="0">{/if}
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Фамилия:
                </td>
                <td class="frame-t-td">
                    <input type="text" id="autocomplete_name" name="entity[name]" value="{if $single_name}{$single_name}{/if}" />
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Телефон:
                </td>
                <td class="frame-t-td">
                    <input type="text" id="phone" name="entity[phone]" value="{if $entity->phone}{$entity->phone}{/if}" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__" />
                </td>
            </tr>
           <tr>
                <td class="frame-t-td-l">
                   Город:
                </td>
                <td class="frame-t-td">
                    <input type="text" id="autocomplete_city" class="search titlex" name="entity[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$entity->city}{/if}" />
                    <input type="hidden" id="val_city" name="" value="{$city_id}">
                </td>
            </tr>
            {*<tr>
                <td class="frame-t-td-l">
                    Город:
                </td>
                <td class="frame-t-td">
                    <select name="entity[city]" class="select_default titlex">
                        <option value="0">Выберите город</option>
                        {foreach from=$city item=icity}
                            <option value="{$icity.id}" {if $entity.city eq $icity.id}selected{/if}>{$icity.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>*}
            <tr>
                <td class="frame-t-td-l">
                    Филиал:
                </td>
                <td class="frame-t-td">
                    <select name="entity[fillials]" class="select_default titlex" style="width:250px">
                        <option id="Selcity" value="0">Выберите филиал города</option>
                        {foreach from=$fillials item=ifillials}
                            <option value="{$ifillials.name}" cid="{$ifillials.city_id}" {if $entity.fillials eq $ifillials.name}selected{/if}>{$ifillials.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
            <tr>
                <td class="frame-t-td-l">
                    Способ доставки и оплаты:
                </td>
                <td class="frame-t-td">
                    {*<select name="entity[delivery]" class="select_default titlex" style="width:250px">
                        <option value="Выберите способ оплаты и доставки" >Выберите способ оплаты и доставки</option>
                        <option value="Полная предоплата" {if $entity->delivery eq 'Полная предоплата'}selected{/if}>Полная предоплата</option>
                        <option value="Наложенный платеж" {if $entity->delivery eq 'Наложенный платеж'}selected{/if}>Наложенный платеж</option>
                        <option value="Оплата наличными курьеру" {if $entity->delivery eq 'Оплата наличными курьеру'}selected{/if} >Оплата наличными курьеру</option>
                    </select>*}
                    <input type="radio" {if $entity->delivery eq 'Полная предоплата'}checked{/if} name="entity[delivery]" value="Полная предоплата">Полная предоплата
                    <br/>
                    <input type="radio" name="entity[delivery]" {if $entity->delivery eq 'Наложенный платеж'}checked{/if} value="Наложенный платеж"> Наложенный платеж
                    <br/>

                    <input type="radio" name="entity[delivery]" {if $entity->delivery eq 'Безналичный расчет'}checked{/if} value="Безналичный расчет"> Безналичный расчет
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Комментарий модератора:
                </td>
                <td class="frame-t-td">
                    <textarea name="entity[admin_comment]" style="height: 100px; width: 400px;">{if $entity->admin_comment}{$entity->admin_comment}{/if}</textarea>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Комментарий покупателя:
                </td>
                <td class="frame-t-td">
                    <textarea name="entity[comment]" style="height: 100px; width: 400px;">{if $entity->comment}{$entity->comment}{/if}</textarea>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">Товары заказа</td>
            </tr>
            <tr>
                <td colspan="4">
                    <input id="product-search" type="search" value="" placeholder="Введите название товара" autocomplete="off">
                </td>
            </tr>
            <tr>
                <table id="product-list">
                    <thead>
                        <th>ID</th>
                        <th>Артикул</th>
                        <th>Название</th>
                        <th>Количество</th>
                        <th>Цвет</th>
                        <th>Размер</th>
                        {*if $productList*}<th>Скидка</th>{*/if*}
                        <th>Цена</th>
                        <th>Действие</th>
                    </thead>
                    <tbody>
                        {if $productList}
                            {foreach from=$productList item=product}
                                {assign var=productUniqueID value=$product.id|cat:rand(1,10000)}
                                <tr class="order-item-row" data-product-id="{$productUniqueID}">
                                    <td>
                                        <input type="hidden" name="entity[order_items][{$productUniqueID}][product_id]" value="{$product.id}">
                                        <input type="hidden" name="entity[order_items][{$productUniqueID}][sku]" value="{$product.sku_id}" class="product_order_sku">
                                        {$product.id}
                                    </td>
                                    <td class="article">
                                        {$product.sku_article}
                                    </td>
                                    <td>
                                        {$product.name}
                                    </td>
                                    <td>
                                        <input type="number" name="entity[order_items][{$productUniqueID}][count]" value="{$product.count}" min="1" max="500" class="product-order-count">
                                    </td>
                                    <td class="color">
                                        {if $product.attr}
                                            <select name="cart[1][sku]" class="sku-by-color" data-prodid="{$product.id}">
                                                {$ind = 0}
                                                {foreach from=$product.attr item=attr}
                                                    {if $attr.attribute_id == 1}
                                                        <option name="entity[order_items][{$productUniqueID}][color]" value="{$attr.sku_id}" data-article="{$attr.sku_article}" {if $product.sku_article == $attr.sku_article}selected="selected"{/if} data-full-price="{$attr.full_price}" data-price="{$attr.price}">{$attr.name}</option>
                                                        {$ind = 1}
                                                    {/if}
                                                {/foreach}
                                                {if $ind == 0}
                                                    <option value="1">Не выбран</option>
                                                {/if}
                                            </select>
                                        {/if}
                                    </td>
                                    <td class="size">
                                        {if $product.attr}
                                            <select name="cart[2][sku]" class="sku-by-size" data-prodid="{$product.id}">
                                                {$ind = 0}
                                                {foreach from=$product.attr item=attr}
                                                    {if $attr.attribute_id|in_array:[2, 150, 188, 265]}
                                                        <option name="entity[order_items][{$productUniqueID}][size]" value="{$attr.sku_id}" data-article="{$attr.sku_article}" {if $product.sku_id == $attr.sku_id}selected="selected"{/if} data-full-price="{$attr.full_price}" data-price="{$attr.price}">{$attr.name}</option>
                                                        {$ind = 1}
                                                    {/if}
                                                {/foreach}
                                                {if $ind == 0}
                                                    <option value="1">Не выбран</option>
                                                {/if}
                                            </select>
                                        {/if}
                                    </td>
                                    <td>
                                        <input type="hidden" name="entity[order_items][{{$productUniqueID}}][discount]" value="{$product.discount}">
                                        {$product.discount}
                                    </td>
                                    <td>
                                        <input type="hidden" name="entity[order_items][{{$productUniqueID}}][price]" value="{$product.full_price}" class="product-full-price">
                                        <input id="product_order_price" value="{$product.price}" readonly="true">
                                    </td>
                                    <td>
                                        <a class="remove-product" href="#">Удалить</a>
                                    </td>
                                </tr>
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
            </tr>
            {*if $productList*}
        </table>
    </form>
</div>
{if $mod eq 'order' and $act eq 'add'}
<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center" style="margin-left:270px;">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">Add</span></button></td>
    </tr>
</table>
{else}
<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">Update</span></button></td>
    </tr>
</table>
{/if}
{include file="_block/input.phone.mask.tpl"}
<style type="text/css">
{rexstyle_start}
    #cke_contents_DataFCKeditor {
        height: 150px!important;
    }
{rexstyle_stop}
</style>


<script>
{rexscript_start}
{literal}
        function getRandom(min, max) {
           return Math.round(Math.random() * (max - min) + min);
        }

        $(document).ready(function(){
            initPhoneMask();
        });

        template.find('#button').die('click').live('click', function() {
                var hasAllAttrsSelected = true;
                $('table#product-list select').each(function (index, obj) {
                    if ($(this).find('option:selected').val() === '1') {
                        alert('Выберите все атрибуты');
                        hasAllAttrsSelected = false;
                        return false;
                    }
                });

                if (!hasAllAttrsSelected) {
                    return false;
                }

                if ($('#autocomplete_name').val().length == 0) {
                    alert('Введите имя пользователя');
                    return false;
                } else if ($('#phone').val().length == 0) {
                    alert('Введите телефон');
                    return false;
                } else if ($('#val_city').val().length == '') {
                    alert('Введите город назначения');
                    return false;
                } else {
                    var orderID = $('#rex_dc_add_order form input[name="entity[exist_id]"]').val();
                    template.find('.addform').rexSubmit(function(data){
                        var btn_send = $('span.ui-button-text');
                        if (data != false) {
                            if (btn_send.text() == 'Add') {
                                window.location.href = '/admin/?mod=order';
                            } else {
                                if (btn_send.text() == 'Update') {
                                    $('a.ui-dialog-titlebar-close').trigger('click');
                                    $('.button_search').trigger('click');
                                    var orderRow = $('input[type="checkbox"][data-order-id="' + orderID + '"]').parents('tr.ui-state-default');
                                    if (!$('.prod2order').is(':visible')) {
                                        orderRow.trigger('click');
                                    }
                                }
                            }
                        }
                    });
                }

            return false;
        });

        window.selectClone = $('select[name="entity[fillials]"]').clone();

        var checkSelect = function(featured)
        {
            $this = window.selectClone.clone();
            //var featured2 = $('#select option:selected').attr('cid');
            if (featured != 0 && $this.find('option[cid="'+featured+'"]').length > 0) {
                $this.find('option[cid!="'+featured+'"]').remove();
            } else if (featured != 0 && !$this.find('option[cid="'+featured+'"]').length) {
                $this.val(0).find('option[value!="0"]').remove();
            } else {
                $this.val(featured);
            }

            $('select[name="entity[fillials]"]').replaceWith($this);
        };

        if ($('#val_city').val()) {
            checkSelect($('#val_city').val());
        }
        $("#autocomplete_city").autocomplete("/admin/autocompletecityadmin/", {
            selectFirst: false,
            minLength: 2,
            width: 420,
            scrollHeight: 400,
            max: 30,
            formatItem: function(data, i, n, value) {
                city = value.split('=')[0];
                city_id = value.split('=')[1];
                return '<div class="city" cid="'+city_id+'" value="'+city+'">'+city+'</div>'

            }, formatResult: function(data, i, n) {
                    return i.split('=')[0];
            }
        }).result(function(event, item) {
            var id = item[0].split('=')[1];
            $('#val_city').val(id);
            checkSelect(id);
            return false;
        });

       $("#autocomplete_name").autocomplete("/admin/autocompleteuser/", {
            selectFirst: false,
            minLength: 2,
            width: 420,
            scrollHeight: 400,
            max: 30,
            formatItem: function(data, i, n, value) {
                user = value.split('=')[0];
                user_id = value.split('=')[1];
                return '<div class="user" uid="'+user_id+'" value="'+user+'">'+user+'</div>'

            }, formatResult: function(data, i, n) {
                    return i.split('=')[0];
            }
        }).result(function(event, item) {
            var id = item[0].split('=')[3];
            var phone = item[0].split('=')[2];
            var city = item[0].split('=')[5];
            var name = item[0].split('=')[6];
            var fillials = item[0].split('=')[4];
            var user_id = item[0].split('=')[1];
            $('#phone').val(phone);
            $('#autocomplete_name2').val(name);
            $('#val_city').val(id);
            $('#Selcity').text(fillials);
            $('#autocomplete_city').val(city);
            $('#user_id').val(user_id);
            //checkSelect(id);
            ///$('select.select_default option:selected').removeAttr('selected');
            $('select.select_default option[value="'+fillials+'"]').attr('selected', 'selected');
            return false;

        });
        
        $("#autocomplete_name2").autocomplete("/admin/autocompleteuser2/", {
            selectFirst: false,
            minLength: 2,
            width: 420,
            scrollHeight: 400,
            max: 30,
            formatItem: function(data, i, n, value) {
                user = value.split('=')[0];
                user_id = value.split('=')[1];
                return '<div class="user" uid="'+user_id+'" value="'+user+'">'+user+'</div>'

            }, formatResult: function(data, i, n) {
                    return i.split('=')[0];
            }
        }).result(function(event, item) {
            var id = item[0].split('=')[3];
            var phone = item[0].split('=')[2];
            var last_name2 = item[0].split('=')[6];
            var city = item[0].split('=')[5];
            var fillials = item[0].split('=')[4];
            var user_id = item[0].split('=')[1];
            $('#phone').val(phone);
            $('#autocomplete_name').val(last_name2);
            $('#val_city').val(id);
            $('#autocomplete_city').val(city);
            $('#user_id').val(user_id);
            $('#Selcity').text(fillials);
            //checkSelect(id);
            //$('select.select_default option:selected').removeAttr('selected');
            $('select.select_default option[value="'+fillials+'"]').attr('selected', 'selected');
            return false;

        });

        $("#phone").autocomplete("/admin/autocompletephone/", {
            selectFirst: false,
            minLength: 2,
            width: 420,
            scrollHeight: 400,
            max: 30,
            formatItem: function(data, i, n, value) {
                phone = value.split('=')[2];
                user_id = value.split('=')[1];
                return '<div class="user" uid="'+user_id+'" value="'+phone+'">'+phone+'</div>'

            }, formatResult: function(data, i, n) {
                    return i.split('=')[0];
            }
        }).result(function(event, item) {
            var id = item[0].split('=')[3];
            var user = item[0].split('=')[0];
            var user_login = item[0].split('=')[6];
            var phone = item[0].split('=')[2];
            var city = item[0].split('=')[5];
            var fillials = item[0].split('=')[4];
            var user_id = item[0].split('=')[1];
            $('#phone').val(phone);
            $('#val_city').val(id);
            $('#autocomplete_city').val(city);
            if (user) {
                $('#autocomplete_name').val(user);
            } else {
                $('#autocomplete_name').val(user_login);
            }
            $('#user_id').val(user_id);
            checkSelect(id);
            $('select.select_default option:selected').removeAttr('selected');
            $('select.select_default option[value="'+fillials+'"]').attr('selected', 'selected');
            return false;

        });

        $("#product-search").autocomplete("/admin/autocompletprod/", {
            selectFirst: false,
            minLength: 2,
            width: 420,
            scrollHeight: 400,
            max: 30,
            formatItem: function (data, i, n, value) {
                product_id = value.split('=')[0];
                product_name = value.split('=')[1];
                product_image = value.split('=')[2];

                return '<div style="width:80px; padding-right: 10px; float: left; text-align: center;"><img src="' + product_image + '" style="vertical-align: middle;width: 60px;height: 60px;"/></div><div style="padding-top: 13px;color: #089BE3;">' + product_name + '</div>'

            }, formatResult: function (data, i, n) {
                return i.split('=')[1];
            }
        }).result(function (event, item) {
            var productID = item[0].split('='),
                isExistsProduct = $('tr[data-product-id="' + productID[0] + '"]').length,
                productUniqueID = productID[0];
            if (isExistsProduct) {
                productUniqueID = productID[0] + getRandom(1, 10000);
                if ($('tr[data-product-id="' + productUniqueID + '"]').length) {
                    productUniqueID = productUniqueID + getRandom(1, 10000);
                }

            }
            $('#product-list tbody').append(
                    '<tr class="order-item-row" data-product-id="' + productUniqueID + '">' +
                        '<td class="id">' +
                            '<input type="hidden" name="entity[order_items][' + productUniqueID + '][product_id]" value="' + productID[0] + '">' +
                            '<input type="hidden" name="entity[order_items][' + productUniqueID + '][sku]" value="' + productID[7] +'" class="product_order_sku">' +
                            productID[0] +
                        '</td>' +
                        '<td class="article">' +
                            productID[3] +
                        '</td>' +
                        '<td>' +
                            productID[1] +
                        '</td>' +
                        '<td>' +
                            '<input type="number" name="entity[order_items][' + productUniqueID + '][count]" value="1" min="1" max="500" class="product-order-count">' +
                        '</td>' +
                        '<td class="color">' +
                            '' +
                        '</td>' +
                        '<td class="size">' +
                            '' +
                        '</td>' +
                        '<td>' +
                            '<input type="hidden" class="sale-input" name="entity[order_items][' + productUniqueID + '][discount]" value="' + productID[6] + '"><span class="sale">' +
                            productID[6] +
                        '</span></td>' +
                        '<td>' +
                            '<input type="hidden" name="entity[order_items][' + productUniqueID + '][price]" value="' + productID[8] + '" class="product-full-price">' +
                            '<input value="' + productID[4] + '" id="product_order_price" readonly="true">' +
                        '</td>' +
                        '<td>' +
                            '<a class="remove-product" href="#">Удалить</a>' +
                        '</td>' +
                        '</tr>');

            $.rex('order', 'attrList', {product_id: productID[0]}, function(data) {
                var tr = $('tr.order-item-row:last'),
                    colorColumn = tr.find('td.color');
                if (data !== false) {
                    colorColumn.html(data);
                    tr.find('.product_order_sku').val(1);
                    colorColumn.find('option').each(function(index, obj) {
                        var self = $(this),
                            name = self.attr('name');
                        if (name) {
                            self.attr('name', 'entity[order_items][' + tr.data('product-id') + '][color]');
                        }
                    });
                }
            });

            return false;
        });

        $(document).on('change', 'select.sku-by-color', function(e) {
            e.preventDefault();
            var $this = $(this),
                selected = $this.find('option:selected'),
                prod_id = $this.data('prodid'),
                article = selected.data('article'),
                tr = $this.parents('tr.order-item-row'),
                sizeColumn = tr.find('td.size');
            $.rex('order', 'attrList', {product_id: prod_id, sku_id: selected.val()}, function(data) {
                if (data !== false) {
                    sizeColumn.html(data);
                    sizeColumn.find('option').each(function(index, obj) {
                        var self = $(this),
                            name = self.attr('name');
                        if (name) {
                            self.attr('name', 'entity[order_items][' + tr.data('product-id') + '][size]');
                        }
                    });
                    if (data != '&nbsp') {
                        tr.find('.product_order_sku').val(1);
                    } else {
                        tr.find('.product_order_sku').val(selected.val());
                    }
                }
            });
            tr.find('.article').text(article);
        });

        $(document).on('change', 'select.sku-by-size', function () {
            var $this = $(this),
                sku_id = $this.find('option:selected'),
                prod_id = $this.data('prodid'),
                sale = sku_id.data('sale'),
                row = $this.parents('tr.order-item-row');
            row.find('.product_order_sku').val(sku_id.val());
            row.find('#product_order_price').val(sku_id.data('price'));
            row.find('.product-full-price').val(sku_id.data('full-price'));
            row.find('.sale').text(sale);
            row.find('.sale-input').val(sale);
        });

        $(document).on('click', '.remove-product', function(e) {
            e.preventDefault();
            var row = $(this).parents('tr');
            if (row.length) {
                row.remove();
            }
        });

{/literal}
{rexscript_stop}
</script>