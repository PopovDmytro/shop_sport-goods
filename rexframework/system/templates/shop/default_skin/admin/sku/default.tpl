{if !$in_parent}
    <h3 class="head1 ui-tabs-nav ui-helper-reset ui-widget-header ui-corner-all">Добавление артикулов товару № {$product_id}</h3>
    {include file="product/add.header.tpl"}
{/if}
<div class="main1   ui-widget-content ui-corner-all ui-state-default bg-reset">
    <table class="general_form" cellspacing="0" cellpadding="0" border="0" width="100%">
         <tr>
            <td  class="add_cl">
                {include file="$template_filters"}
            </td>
        </tr>
    </table>
    <form id="skuForm">
        <div class="datagrid_container">
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
                    template.find('#skuForm').rexSubmit('sku', 'saveForm', function(data){
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
        template.find('#skuForm').rexSubmit('sku', 'saveForm');
        $.data(template, 'page', $(this).attr('page'));
        $.data(template, 'updateDatagrid')();
    });
    
    template.find('#sku-save').die('click').live('click', function(){
        template.find('#skuForm').rexSubmit('sku', 'saveForm');    
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
            if ($(this).find('.sku-quantity').val() == 0) {
                $(this).removeClass('sku-green').addClass('sku-red');
            } else if ($(this).find('.sku-quantity').val() > 0) {
                $(this).removeClass('sku-red').addClass('sku-green');
            }   
        });        
    }
{/literal}
{rexscript_stop}
</script>