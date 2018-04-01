{if !$in_parent}
    <h3 class="head1 ui-tabs-nav ui-helper-reset ui-widget-header ui-corner-all">Добавление картинок товару № {$product_id}</h3>
    {include file="product/add.header.tpl"}
{/if}
<div class="main1   ui-widget-content ui-corner-all ui-state-default bg-reset">
    <table class="general_form" cellspacing="0" cellpadding="0" border="0" width="100%">
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

    <div class="datagrid_container">
        {$dg}
    </div>
    {if !$in_parent}
        {include file="product/add.footer.tpl"}
    {/if}
</div>
<script type="text/javascript">
{rexscript_start}
    $.data(template, 'page', {$filters.page});
    $.data(template, 'inpage', {$filters.inpage});
    $.data(template, 'order_by', "{$filters.order_by}");
    $.data(template, 'order_dir', "{$filters.order_dir}");
{literal}
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
        $.data(template, 'page', 1);
        $.data(template, 'updateDatagrid')();
    }
    $.data(template, 'clearFilters', clearFilters);

    var updateDatagrid = function() {
        template.find('.datagrid_container').rex(mod, act, {filters: $.data(template, 'getFilters')(), dg_only: true, task: task});
    };
    $.data(template, 'updateDatagrid', updateDatagrid);

    template.find('.searchexec').unbind('click').click(function(){
        $.data(template, 'page', 1);
        $.data(template, 'updateDatagrid')();
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
        $.data(template, 'page', $(this).attr('page'));
        $.data(template, 'updateDatagrid')();
    });
    
    template.find('.itemadd').unbind('click').click(function(){
        $.showRexDialog(mod, 'add', {}, 'add_'+mod, 'Add ' + mod);
    });

    template.find('.itemedit').die('click').live('click', function(){
        $.showRexDialog(mod, 'edit', {task: $(this).attr('item_id')}, 'add_'+mod, 'Edit ' + mod);
    });

    $.rexDialog('add_'+mod).find('.item_add').die('click').live('click', function(){
        $.rexDialog('add_'+mod).find('.addform').rexSubmit(function(data){
            if (data !== false) {
                $.closeRexDialog('add_'+mod);
                $.data(template, 'updateDatagrid')();
            }
        });
    });

    template.find('.itemdelete').die('click').live('click', function(){
        if (confirm('Really delete '+mod+'?') && $.rex(mod, 'delete', {task: $(this).attr('item_id')}) !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    template.find('li.ui-state-default').die('mouveover').live('mouseover', function(){
        $(this).addClass('ui-state-hover');
    }).die('mouseleave').live('mouseleave', function(){
        $(this).removeClass('ui-state-hover');
    });
    
    template.find('.itemadd').unbind('click').click(function(){
        $.showRexDialog(mod, 'add', {product_id: template.find('#product_id').val()}, 'add_'+mod, 'Add ' + mod);
    });
    
    template.find('.order_up').die('click').live('click', function(){
        var data = $.rex('pImage', 'order', {task: $(this).attr('id'), value: 'up', attribute_id: template.find('[name="attribute_id"]').val()}, function () {});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
    
    template.find('.order_down').die('click').live('click', function(){
        var data = $.rex('pImage', 'order', {task: $(this).attr('id'), value: 'down', attribute_id: template.find('[name="attribute_id"]').val()}, function () {});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    template.find('.itemsetmain').die('click').live('click', function () {
        var data = $.rex(mod, 'mainPhoto', {id: $(this).attr('item_id')});

         if (data !== false) {
            $.data(template, 'updateDatagrid')();
         }
    });

    template.find('.show_color_order').unbind('click').click(function(){
        $.showRexDialog('ProdColorOrder', 'default', {product_id: template.find('#product_id').val()}, 'show_color_order_'+mod, 'Сортировка цветов товара ' + mod);
    });
{/literal}
{rexscript_stop}
</script>