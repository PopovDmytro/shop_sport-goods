{include file="parent/default.tpl"}

<script type="text/javascript">
{rexscript_start}
{literal}
    $(document).ready(function(){
        retable();
    });
    
    $(window).resize(function(){
        retable();

    });
    
    function retable(){
        var datatab = template.find('table.data');
        var pdata = datatab.parent();
        var wdatatab = datatab.width();
        var wpdata = pdata.width();
        if (wdatatab != wpdata) {
            var thisw = 0;
            var thistab = [];
            datatab.find('th').each(function(){
                if ($(this).attr('ilattr') != 'inline-cel') {
                    thisw += $(this).width() + 10;
                } else {
                    thistab = $(this);
                }
            })
            template.find('.inline-cell').css('max-width',wpdata-thisw-30);
        } 
    }
{/literal}
{rexscript_stop}
</script>