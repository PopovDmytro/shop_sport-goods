<div class="main1   ui-widget-content ui-corner-all ui-state-default bg-reset {if $mod eq 'user'}user-def{/if}">
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

    <div class="datagrid_container color-sortable">
        {$dg}
    </div>
</div>

<script type="text/javascript">
    {rexscript_start}
        $.data(template, 'page', {$filters.page});
        $.data(template, 'inpage', {$filters.inpage});
        $.data(template, 'order_by', "{$filters.order_by}");
        $.data(template, 'order_dir', "{$filters.order_dir}");
        $.data(template, 'product_id', "{$filters.product_id}");
    {literal}
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
        if (!filters.product_id) {
            filters['product_id'] = $.data(template, 'product_id');
        }
        console.log(filters);
        return filters;
    }
    $.data(template, 'getFilters', getFilters);

    var clearFilters = function() {
        template.find('.search').val('');
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
        template.find('.datagrid_container').rex(mod, act, {filters: $.data(template, 'getFilters')(), dg_only: true, task: task});
    };
    $.data(template, 'updateDatagrid', updateDatagrid);

    var updateRow = function(element, item_id) {
        element.rex(mod, act, {filters: $.data(template, 'getFilters')(), dg_only: true, item_id: item_id, row_only: true});
    };
    $.data(template, 'updateRow', updateRow);

    template.find('.searchexec').unbind('click').click(function() {
        $.data(template, 'page', 1);
        $.data(template, 'updateDatagrid')();
    });

    template.find('.searchreset').unbind('click').click(function() {
        $.data(template, 'clearFilters')();
    });

    template.find('.search').unbind('keypress').keypress(function(event) {
        if (event.keyCode == 13) {
            var span_clk = $(this).parent().parent().parent().find('td ul#icons li:first a span.ui-icon-search');
            if (span_clk.length > 0) {
                span_clk.parent().click();
            }
        }
    });

    template.off('click.changePage', '.datagrid_container .dg_pager').on('click.changePage', '.datagrid_container .dg_pager', function() {
        $.data(template, 'page', $(this).attr('page'));
        $.data(template, 'updateDatagrid')();
    });

    template.find('.order_up').die('click').live('click', function(){
        var data = $.rex('ProdColorOrder', 'order', {task: $(this).attr('id'), value: 'up'}, function () {});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    template.find('.order_down').die('click').live('click', function(){
        var data = $.rex('ProdColorOrder', 'order', {task: $(this).attr('id'), value: 'down'}, function () {});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    {/literal}
    {rexscript_stop}
</script>