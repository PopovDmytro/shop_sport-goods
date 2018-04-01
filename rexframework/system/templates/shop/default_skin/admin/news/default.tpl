{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.datagrid_container').bind('updateDatagrid', function () {
        $.data(template, 'updateDatagrid')();
    });
{/literal}
{rexscript_stop}
</script>