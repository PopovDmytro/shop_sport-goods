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
        </tr>
    </table>

    <div class="datagrid_container">
        {$dg}
    </div>
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

    template.off('click.changePage', '.datagrid_container .dg_pager').on('click.changePage', '.datagrid_container .dg_pager', function() {
        $.data(template, 'page', $(this).attr('page'));
        $.data(template, 'updateDatagrid')();
    });

    template.find('.itemadd').unbind('click').click(function() {
        $.showRexDialog(mod, 'add', {}, 'add_'+mod, 'Add ' + mod);
    });

    template.off('click.editItem', '.itemedit').on('click.editItem', '.itemedit', function() {
        var parent = $(this).parents('tr').addClass('update-tr-item');
        $.showRexDialog(mod, 'edit', {task: $(this).attr('item_id')}, 'add_'+mod, 'Edit ' + mod);
        $.data(template, 'updateDatagrid')();
    });

    $(document).off('click.addForm', '.item_add').on('click.addForm', '.item_add', function() {
        $.rexDialog('add_'+mod).find('.addform').rexSubmit(function(data) {
            if (data !== false) {
                $.closeRexDialog('add_'+mod);
                if ($.rexDialog('add_'+mod).find('input[name="act"]').val() == 'add') {
                    $.data(template, 'updateDatagrid')();
//                    alert(entity[phone]);

                } else {
                    $.data(template, 'updateRow')(template.find('tr.update-tr-item'), $.rexDialog('add_'+mod).find('input[name="entity[exist_id]"]').val());
//                    alert(entity[phone]);

                }
            }
//            alert(entity[phone]);
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
    {/literal}
    {rexscript_stop}
</script>