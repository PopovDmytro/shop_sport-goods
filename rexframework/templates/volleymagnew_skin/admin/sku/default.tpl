{if !$in_parent}
    <h3 class="head1 ui-tabs-nav ui-helper-reset ui-widget-header ui-corner-all">Добавление артикулов товару № {$product_id}</h3>
    {include file="product/add.header.tpl"}
{/if}

<div class="main1   ui-widget-content ui-corner-all ui-state-default bg-reset">
    <form id="skuForm">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="task" value="{$product_id}">
        <input type="hidden" name="entity[exist_id]" value="{$product_id}">
        {if $attr and $attr eq 'color'}
            <table cellspacing="5" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="frame-t-td-l">
                        Цена:
                    </td>
                    <td class="frame-t-td">
                        <input name="entity[price]" data-autosave="true" data-entity="product" type="text" maxlength="128" value="{if $product_entity->price}{$product_entity->price}{else}0{/if}" style="width: 280px;" >
                    </td>
                </tr>
                <tr>
                    <td class="frame-t-td-l">
                        Цена (закупка):
                    </td>
                    <td class="frame-t-td">
                        <input name="entity[price_opt]" data-autosave="true" data-entity="product" type="text" maxlength="128" value="{if $product_entity->price_opt}{$product_entity->price_opt}{else}0{/if}" style="width: 280px;" >
                    </td>
                </tr>
                <tr>
                    <td class="frame-t-td-l">
                        Акционный:
                    </td>
                    <td class="frame-t-td">
                        <input type="checkbox" name="entity[event]" data-autosave="true" data-entity="product" {if $product_entity->event eq 1}checked="checked"{/if}/>
                    </td>
                </tr>
            </table>
        {/if}
        <div class="datagrid_container">
            {if $attr and $attr eq 'color'}
                <div class="table_wrapper">
                    <table cellspacing="5" cellpadding="0" border="0" width="100%">
                        <tbody>
                        <tr>
                            <th class="ui-accordion-header ui-helper-reset"></th>
                            <th class="ui-accordion-header ui-helper-reset"></th>
                            <th class="ui-accordion-header ui-helper-reset">
                                Общая цена
                            </th>
                            <th class="ui-accordion-header ui-helper-reset">
                                <b>Цена (закупка)</b>
                            </th>
                            <th class="ui-accordion-header ui-helper-reset">
                                <b>Цена (розница)</b>
                            </th>
                            <th class="ui-accordion-header ui-helper-reset">
                                Скидка/Общая скидка
                            </th>
                            <th class="ui-accordion-header ui-helper-reset">
                                Процент
                            </th>
                        </tr>
                        <tr>
                            <td width="61"></td>
                            <td width="222"></td>
                            <td width="220">
                                <input type="checkbox" id="is_common_price" name="entity[is_common_price]" data-autosave="true" data-entity="product" {if $product_entity->is_common_price eq 1}checked="checked"{/if}/>
                                <label for="is_common_price">Общая цена</label>
                            </td>
                            <td width="220">
                                <input type="text" class="sku-opt-common" value="{if $product_entity->price_opt}{$product_entity->price_opt}{else}0{/if}" />
                            </td>
                            <td width="220">
                                <input type="text" class="sku-price-common" value="{if $product_entity->price}{$product_entity->price}{else}0{/if}" />
                            </td>
                            <td width="220">
                                <input type="checkbox" id="is_common_sale" name="entity[is_common_sale]" data-autosave="true" data-entity="product" {if $product_entity->is_common_sale eq 1}checked="checked"{/if}/>
                                <label for="is_common_sale">Общая скидка</label>
                            </td>
                            <td width="220">
                                <input type="text" data-autosave="true" data-entity="product" class="sku-sale-common" name="entity[sale]" value="{if $product_entity->sale}{$product_entity->sale}{else}0{/if}" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            {/if}

            {$dg}
        </div>
    </form>
    {if $in_parent}
        <table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
            <tr valign="">
                <td class="frame-t-td-s" colspan="2">
                    <button id="sku-save" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button"><span class="ui-button-text">Save</span></button>
                </td>
            </tr>
        </table>
    {elseif !$in_parent}
        {include file="product/add.footer.tpl"}
        <script>
            {rexscript_start}
            {literal}

                template.find('#button-step-next').die('click').live('click', function() {
                    template.find('#skuForm').rexSubmit('sku', 'saveForm', {attr: {/literal}{if $attr}'{$attr}'{else}false{/if}{literal}}, function(data){
                        if (data != false) {
                            window.location.href = template.find('#button-step-next').parents('a').attr('href');
                        }
                    });
                    return false;
                });

            {/literal}
            {rexscript_stop}
        </script>
    {/if}
</div>

<script type="text/javascript">
{rexscript_start}
    $.data(template, 'page', {$filters.page});
    $.data(template, 'inpage', {$filters.inpage});
    $.data(template, 'order_by', "{$filters.order_by}");
    $.data(template, 'order_dir', "{$filters.order_dir}");
{literal}
    var productPrice = {/literal}{if $product_entity->price}{$product_entity->price}{else}0{/if}{literal},
        productPriceOpt = {/literal}{if $product_entity->price_opt}{$product_entity->price_opt}{else}0{/if}{literal},
        sale = {/literal}{if $product_entity->sale}{$product_entity->sale}{else}0{/if}{literal};
    changeSale();

    template.find('[name="entity[event]"]').die('change').live('change', function(){
        changeSale();
    });

    function calcSalePrice(price, sale) {
        price = price - (price * (sale / 100));
        return price.toFixed(2);
    }

    function changeSale()
    {
        if (template.find('[name="entity[event]"]').prop('checked')) {
            if (!productPrice) {
                productPrice = $('input[name="entity[price]"]').val() || 0;
            }

            if (!sale) {
                sale = $('body input[name="entity[sale]"]').val() || 0;
            }

            var price = calcSalePrice(productPrice, sale);

            template.find('#is_common_sale').removeAttr('onclick');
            template.find('[name="entity[event]"]').parents('tr').append('<td>Цена со скидкой: <input type="text" disabled="disabled" id="sale_price" value="' + price + '"/></td>');
        } else {
            template.find('input[name="entity[sale]"]').val(0).trigger('input').trigger('blur');
            template.find('#is_common_sale').attr('onclick', 'return false;');
            template.find('#sale_price').parents('td').remove();
        }

        var $event = template.find('[name="entity[event]"]');
        template.find('#datagrid_container table td:nth-child(6) .sku-sale').prop('readonly', !$event.prop('checked') || ($event.prop('checked') && template.find('#is_common_sale').prop('checked')));
        template.find('.sku-sale-common').prop('readonly', !$event.prop('checked') || ($event.prop('checked') && !template.find('#is_common_sale').prop('checked')));
    }

    function changeCommonPrices(type, price) {
        var indexEl = 0;
        if (type == 'opt') {
            indexEl = 4;
        } else if (type == 'price') {
            indexEl = 5;
        }

        if (!indexEl || !$('#is_common_price').prop('checked')) {
            return false;
        }
        var ids = [];
        var priceInputs = $('#datagrid_container table td:nth-child('+indexEl+') .sku-price');
        priceInputs.each(function(i, item) {
            var $item = $(item);
            ids.push($item.data('entity-id'));
            $item.val(price);
        });
        if (indexEl == 5) {
            template.find('.sku-sale-common').trigger('input');
        }

        var data = {
            entity_id: ids,
            field: {
                entity: priceInputs.data('entity'),
                name: priceInputs.data('field'),
                value: price
            }
        };

        $.rex(mod, 'fieldAutoSave', data);
    }

    $(document).on('input', 'input[name="entity[sale]"]', function(){
        var productSale = parseInt($(this).val()),
                salePrice = $('#sale_price'),
                price = parseInt($('input[name="entity[price]"]').val());

        if (!productSale) {
            productSale = 0;
        }

        if (!price) {
            price = productPrice;
        }

        salePrice.val(calcSalePrice(price, productSale));
    }).on('input', 'input[name="entity[price]"]', function() {
        var price = parseInt($(this).val()),
                saleField = $('input[name="entity[sale]"]'),
                salePriceField = $('#sale_price'),
                salePrice = parseInt(saleField.val());

        if (!price) {
            price = productPrice;
        }

        if (!salePrice) {
            salePrice = 0;
        }

        changeCommonPrices('price', price);
        $('input.sku-price-common').val(price);
        salePriceField.val(calcSalePrice(price, salePrice));
    }).on('input', 'input.sku-price-common', function() {
        var price = parseInt($(this).val()),
                saleField = $('input[name="entity[sale]"]'),
                salePriceField = $('#sale_price'),
                salePrice = parseInt(saleField.val());

        if (!price) {
            price = productPrice;
        }

        if (!salePrice) {
            salePrice = 0;
        }

        changeCommonPrices('price', price);
        $('input[name="entity[price]"]').val(price).trigger('blur');
        salePriceField.val(calcSalePrice(price, salePrice));
    }).on('input', 'input[name="entity[price_opt]"', function () {
        var $this = $(this),
            priceOpt = parseInt($this.val());

        if (!priceOpt) {
            priceOpt = productPriceOpt;
        }

        changeCommonPrices('opt', priceOpt);
        $('input.sku-opt-common').val(priceOpt);
    }).on('input', 'input.sku-opt-common', function () {
        var $this = $(this),
            priceOpt = parseInt($this.val());

        if (!priceOpt) {
            priceOpt = productPriceOpt;
        }

        changeCommonPrices('opt', priceOpt);
        $('input[name="entity[price_opt]"').val(priceOpt).trigger('blur');
    }).on('change', '#is_common_price', function() {
        $('#datagrid_container table td:nth-child(4) .sku-price').prop('readonly', $(this).prop('checked'));
        $('#datagrid_container table td:nth-child(5) .sku-price').prop('readonly', $(this).prop('checked'));
        $('input.sku-opt-common').prop('readonly', !$(this).prop('checked')).trigger('input');
        $('input.sku-price-common').prop('readonly', !$(this).prop('checked')).trigger('input');
    }).on('change', '#is_common_sale', function() {
        if (!template.find('[name="entity[event]"]').prop('checked')) {
            return false;
        }

        var $this = $(this);

        $('#datagrid_container table td:nth-child(6) .sku-sale').prop('readonly', $this.prop('checked'));
        template.find('.sku-sale-common').prop('readonly', !$this.prop('checked'));
        if ($this.prop('checked')) {
            template.find('.sku-sale-common').trigger('input');
        }
    }).on('input', '.sku-sale-common', function() {
        var saleField = $(this),
            salePrice = parseInt(saleField.val()),
            salesInputs = $('#datagrid_container table td:nth-child(6) .sku-sale');

        if (!salePrice) {
            salePrice = 0;
        }

        var ids = [];
        salesInputs.each(function(i, item) {
            var $item = $(item);
            ids.push($item.data('entity-id'));
            $item.val(salePrice).trigger('input');
        });

        var data = {
            entity_id: ids,
            field: {
                entity: salesInputs.data('entity'),
                name: salesInputs.data('field'),
                value: salePrice
            }
        };

        $.rex(mod, 'fieldAutoSave', data);
    }).on('input', '.sku-sale', function() {
        var $this = $(this),
            $parent = $this.parents('tr'),
            $price = $parent.find('td:nth-child(5) .sku-price'),
            price = $price.val(),
            salePrice = $this.val();

        if (!price) {
            price = productPrice;
        }

        if (!salePrice) {
            salePrice = 0;
        }

        $parent.find('.sku-sale-price').val(calcSalePrice(price, salePrice));
    }).on('input', 'td:nth-child(5) .sku-price', function() {
        template.find('.sku-sale').trigger('input');
    }).on('change', '.sku-presence', function() {
        var $element = $(this),
            data = {
                product_id: $('[name="entity[exist_id]"]').val(),
                entity_id: $element.data('entity-id'),
                color_id: $element.data('color-id'),
                value: $element.prop('checked') ? 1 : 0,
                attr: {/literal}{if $attr}'{$attr}'{else}false{/if}{literal}
            };

        $.rex(mod, 'colorAutoSave', data, function (response) {});
    });

    template.find('#is_common_sale').trigger('change');

    var getFilters = function() {
        var filters = {};
        template.find('.filter').each(function(){
            var name = $.trim($(this).attr('name'));
            if (name != '') {
                switch (this.tagName.toLowerCase()) {
                    case 'input':
                        if (($(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio') && !$(this).is(':checked')) {
                            break;
                        }
                    case 'select':
                    case 'textarea':
                        filters[name] = $(this).val();
                    break;
                    default:
                        filters[name] = $(this).text();
                }
            }
        });
        if (!filters.order_by) {
            filters['order_by'] = $.data(template, 'order_by');
        }
        if (!filters.order_dir) {
            filters['order_dir'] = $.data(template, 'order_dir');
        }
        if (!filters.page) {
            filters['page'] = $.data(template, 'page');
        }
        if (!filters.inpage) {
            filters['inpage'] = $.data(template, 'inpage');
        }
        return filters;
    }
    $.data(template, 'getFilters', getFilters);

    var clearFilters = function() {
        template.find('.search').val('');
        template.find('.datefrom').val('');
        template.find('.dateto').val('');
        template.find('.check').val([]).trigger("liszt:updated");
        $.data(template, 'updateDatagrid')();
    }
    $.data(template, 'clearFilters', clearFilters);

    var updateDatagrid = function() {
        template.find('.datagrid_container').rex(mod, act, {filters: $.data(template, 'getFilters')(), dg_only: true});
    };
    $.data(template, 'updateDatagrid', updateDatagrid);

    template.find('.searchexec').unbind('click').click(function(){
        $.data(template, 'updateDatagrid')();
    });

    var checkboxes = $('input:checkbox.sku-presence');
    $.each(checkboxes, function(key, value) {
        if ($(this).attr('checked') === undefined) {
            $(this).parent().parent().children('td').css("background-color", "#fb8682");
        }
    });

    template.find('input:checkbox.sku-presence').off().on('change', function(){
        if ($(this).attr('checked') !== undefined) {
            if ($(this).parent().parent().hasClass('Even')) {
                $(this).parent().parent().children('td').css("background-color", "#E5E5E5");
            } else {
                $(this).parent().parent().children('td').css("background-color", "#F5F5F5");
            }
        } else {
            $(this).parent().parent().children('td').css("background-color", "#fb8682");
        }
    });

    template.find('.searchreset').unbind('click').click(function(){
        $.data(template, 'clearFilters')();
    });

    template.find('.sort').die('click').live('click', function(){
        var field = $(this).attr('field');
        if ($.data(template, 'order_by') == field) {
            if ($.data(template, 'order_dir') == 'asc') {
                $.data(template, 'order_dir', 'desc');
            } else {
                $.data(template, 'order_dir', 'asc');
            }
        } else {
            $.data(template, 'order_by', field);
            $.data(template, 'order_dir', 'asc');
        }
        $.data(template, 'updateDatagrid')();
    });

    template.find('.search').unbind('keypress').keypress(function(event){
        if (event.keyCode == 13) {
            var span_clk = $(this).parent().parent().parent().find('td ul#icons li:first a span.ui-icon-search');
            if (span_clk.length > 0) {
                span_clk.parent().click();
            }
        }
    });

    template.find('.datagrid_container .dg_pager').die('click').live('click', function(){
        template.find('#skuForm').rexSubmit('sku', 'saveForm', {attr: {/literal}{if $attr}'{$attr}'{else}false{/if}{literal}});
        $.data(template, 'page', $(this).attr('page'));
        $.data(template, 'updateDatagrid')();
    });

    template.find('#sku-save').die('click').live('click', function(){
        template.find('#skuForm').rexSubmit('sku', 'saveForm', {attr: {/literal}{if $attr}'{$attr}'{else}false{/if}{literal}});
    });

    template.find('li.ui-state-default').die('mouveover').live('mouseover', function(){
        $(this).addClass('ui-state-hover');
    }).die('mouseleave').live('mouseleave', function(){
        $(this).removeClass('ui-state-hover');
    });

    setInterval(colorizeThat, 500);

    function colorizeThat()
    {
        template.find('tr.ui-state-default').each(function(){
            if ($(this).find('.sku-presence').attr('checked') == undefined) {
                $(this).removeClass('sku-green').addClass('sku-red');
            } else {
                $(this).removeClass('sku-red').addClass('sku-green');
            }
        });
    }
{/literal}
{rexscript_stop}
</script>
{include "_block/field_autosave.inc.tpl"}
