<div class="main1   ui-widget-content ui-corner-all ui-state-default bg-reset">
    <table class="general_form" cellspacing="0" cellpadding="0" border="0" width="100%">
    {if !isset($in_parent)}
        <tr>
            <td colspan="2">
                <h3 class="head1 ui-tabs-nav ui-helper-reset  ui-widget-header ui-corner-all">
                    {$mod|capitalize}
                </h3>
            </td>
        </tr>
    {/if}
        <tr>
            <td  class="add_cl">
                {include file="$template_filters"}
            </td>
            {if $mod neq 'ticket'}
                <td class="add_cl" align="right" >
                    {include file="$template_buttons"}
                </td>
            {/if}
        </tr>
    </table>
    <table style="width:100%;">
    <tr>
    <td style="width:620px;">
    <div class="datagrid_container_order special-order" style="width:620px; overflow-y: auto !important;webkit-overflow-scrolling: touch;  height:450px;border: 1px solid;display: inline-block;">
        {$dg}
    </div>
    </td>
    <td>
    <div class="right"><div class="prod2order">{$prod2Order}</div></div>
    </td>
    </tr>
    </table>

</div>
<div id="send_sms_block">
    <div class="close">X</div>
    <h3 style="margin-top:0;">Отправка SMS пользователю</h3>
    <p>Шаблон сообщения</p>
    <select id="tamplate_sms_send">
        <option value="">Шаблон не выбран</option>
        <option value="спасибо, Ваш заказ оформлен">спасибо ваш заказ оформлен</option>
        <option value="извините, Ваш заказ задерживается">извините ваш заказ задерживается</option>

        <option value="Сумма Вашего заказа: грн. Карта Приватбанка № 5169 3305 0948 5352, ФОП Умінська С.О., р/с 26002052207599"">реквизиты</option>
    </select>
    <p>Текст сообщения</p>
    <textarea resize="off" id="text_sms_send" name="text" cols="" rows=""></textarea>
    <input type="hidden" order_id="" id="sms_oid">
    <input type="button" class="sendbutton" value="Отправить SMS">
</div>

<script type="text/javascript">
{rexscript_start}
    $.data(template, 'page', {$filters.page});
    $.data(template, 'inpage', {$filters.inpage});
    $.data(template, 'order_by', "{$filters.order_by}");
    $.data(template, 'order_dir', "{$filters.order_dir}");
{literal}
    function floorN(x, n) {
         var mult = Math.pow(10, n);
         return Math.floor(x*mult)/mult;
    }

    var getFilters = function() {
        var filters = {};
        template.find('.filter').each(function() {
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
        $.data(template, 'page', 1);
        $.data(template, 'updateDatagrid')();
    }
    $.data(template, 'clearFilters', clearFilters);

    template.on('change.currChange', '.filters input, .filters select, .filters textarea', function(){
        $.data(template, 'page', 1);
    });

    template.off('change.currChange', '.filters input, .filters select, .filters textarea').on('change.currChange', '.filters input, .filters select, .filters textarea', function(){
        $.data(template, 'page', 1);
    });

    var updateDatagrid = function() {
        template.find('.datagrid_container_order').rex(mod, act, {filters: $.data(template, 'getFilters')(), dg_only: true, task: task});
        orderProd();
        $.rex(mod, 'totalizator', {filters: $.data(template, 'getFilters')()}, function(data){
            //console.log(data);
            if (data) {
                $('#order_amount').html('Заказов: ' + (data.orders ? data.orders : 0));
                $('#order_total').html('Всего на: ' + (data.total ? data.total : 0) + 'грн');
                $('#order_profit').html('Прибыль: ' + ((data.total - data.zakup) ?  floorN((data.total - data.zakup), 2) : 0) + 'грн');
            }
        });
    };

    $.data(template, 'updateDatagrid', updateDatagrid);

    var updateRow = function(element, item_id) {
        element.rex(mod, act, {filters: $.data(template, 'getFilters')(), dg_only: true, item_id: item_id, row_only: true});
    };
    $.data(template, 'updateRow', updateRow);

    template.find('.searchexec').unbind('click').click(function() {
        $.data(template, 'page', 1);
        $.data(template, 'updateDatagrid')();
        orderProd();
    });

    template.find('.searchreset').unbind('click').click(function() {
        $.data(template, 'clearFilters')();
    });

    template.off('click.sortItems', '.sort').on('click.sortItems', '.sort', function() {
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

    template.find('.search').unbind('keypress').keypress(function(event) {
        if (event.keyCode == 13) {
            var span_clk = $(this).parent().parent().parent().find('td ul#icons li:first a span.ui-icon-search');
            if (span_clk.length > 0) {
                span_clk.parent().click();
            }
        }
    });

    template.off('click.changePage', '.datagrid_container_order .dg_pager').on('click.changePage', '.datagrid_container_order .dg_pager', function() {
        $.data(template, 'page', $(this).attr('page'));
        $.data(template, 'updateDatagrid')();
    });

    template.find('.itemadd').unbind('click').click(function() {
        $.showRexDialog(mod, 'add', {}, 'add_'+mod, 'Add ' + mod);
    });

    template.off('click.editItem', '.itemedit').on('click.editItem', '.itemedit', function() {
        var parent = $(this).parents('tr').addClass('update-tr-item');
        $.showRexDialog(mod, 'edit', {task: $(this).attr('item_id')}, 'add_'+mod, 'Edit ' + mod);
    });

    $(document).off('click.addForm', '.item_add').on('click.addForm', '.item_add', function() {
        $.rexDialog('add_'+mod).find('.addform').rexSubmit(function(data) {
            if (data !== false) {
                $.closeRexDialog('add_'+mod);

                if ($.rexDialog('add_'+mod).find('input[name="act"]').val() == 'add') {
                    $.data(template, 'updateDatagrid')();
                } else {
                    $.data(template, 'updateRow')(template.find('tr.update-tr-item'), $.rexDialog('add_'+mod).find('input[name="entity[exist_id]"]').val());
                }
            }
        });
    });

    template.off('click.deleteItem', '.itemdelete').on('click.deleteItem', '.itemdelete', function() {
        if (confirm('Really delete '+mod+'?') && $.rex(mod, 'delete', {task: $(this).attr('item_id')}) !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    template.off('mouseenter.hoverButton', 'li.ui-state-default').on('mouseenter.hoverButton', 'li.ui-state-default', function() {
        $(this).addClass('ui-state-hover');
    }).off('mouseleave.leaveButton', 'li.ui-state-default').on('mouseleave.leaveButton', 'li.ui-state-default', function() {
        $(this).removeClass('ui-state-hover');
    });
    function orderProd(){
        $('.data').off('click', 'tr.ui-state-default').on('click', 'tr.ui-state-default',function(){
            var $hiddenProduct = $(this).find('td.last'),
                $hiddenProductTable = $hiddenProduct.find('table').first(),
                hiddenProductOLID = $hiddenProductTable.attr('id');
            if (hiddenProductOLID == $('.prod2order').data('olid')) {
                $('.prod2order').hide().removeData('olid');
            } else {
                $('.prod2order').data({
                    olid: hiddenProductOLID
                }).html($hiddenProduct.html()).show().find('table').first().fadeIn();
            }
        });
    }

    template.on('click', '.data .ui-state-default',function(){
        var activ = false
        if ($(this).hasClass('active-order')) {
            activ = true;
        }
        if ($('.active-order').length > 0 ) {
            $('.active-order').removeClass('active-order');
            if ($(this).hasClass('active-order')) {
                $('.active-order').removeClass('active-order');
            }
        }
        if (!activ) {
            $(this).addClass('active-order');
        }
    });

    orderProd();
    template.on('click', '.itemsend', function() {
        
        $('.bg-shadow').css('display', 'block');
        $('#send_sms_block').css('display', 'block');
        template.find('#sms_oid').attr('order_id', $(this).attr('item_id'));
    });

    $('.bg-shadow, #send_sms_block .close').click(function(){
        $('.bg-shadow').css('display', 'none');
        $('#send_sms_block').css('display', 'none');
    });

    template.find('#tamplate_sms_send').change(function(){
        template.find('#text_sms_send').text($(this).val());
    });

    template.find('.sendbutton').click(function(){
        var text = template.find('#text_sms_send').val();

        if (!text || text.length < 3) {
            alert('Вы не ввели текст SMS');
            return false;
        }

        var data = $.rex(mod, 'sendsms', {text:text, ord_id:template.find('#sms_oid').attr('order_id')});
        if (data) {
            $('.bg-shadow').css('display', 'none');
            $('#send_sms_block').css('display', 'none');
            alert(data);
        }
    });

    $(document).on('change', '.order-status', function() {
        var $this = $(this);
        $.rex(mod, 'UpdateOrderStatus', {order_id: $this.data('order-id'), status_id: $this.val()}, function(response) {
            if (response.message !== 'success') {
                alert(response.message);
            } else {
                $this.css({
                    'color': $this.find('option:selected').data('color')
                });
                $.data(template, 'page', response.page);
                $.data(template, 'updateDatagrid')();
            }
        });
    });

    $(".order-email-bind").autocomplete("/admin/autocompleteorderemail/", {
        selectFirst: false,
        minLength: 2,
        width: 420,
        scrollHeight: 400,
        max: 30,
        formatItem: function(data, i, n, value) {
            return value.split('=')[1];

        }, formatResult: function(data, i, n) {
            return i.split('=')[1];
        }
    }).result(function(event, item) {
        var $this = $(this),
            userId = item[0].split('=')[0];

        $.rex(mod, 'ChangeOrderBind', {order_id: $this.data('order-id'), user_id: userId, bind_status: 1}, function(response) {
            if (response !== 'ok') {
                alert(response);
            } else {
                $this.prop('disabled', true);
                $this.parent().find('.bing-order-action')
                        .prop('checked', true)
                        .prop('disabled', false)
                        .attr('data-user-id', userId);
            }
        });

        return false;
    });

    $(document).on('change', '.bing-order-action', function(e) {
        e.preventDefault();
        var userID  = $(this).data('user-id') || false,
            orderID = $(this).data('order-id') || false;

        if (!userID || !orderID) {
            return false;
        }

        var $this = $(this);
        $this.attr('data-user-id', 0).prop('disabled', true)
                .parent().find('.order-email-bind').val('').prop('disabled', false);

        $.rex('order', 'changeOrderBind', {user_id: userID, order_id: orderID, bind_status: +this.checked});
    });

{/literal}
{rexscript_stop}
</script>