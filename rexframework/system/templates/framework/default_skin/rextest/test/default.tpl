{css src="stylerextest.css"}

<div class="test_start">
    <div style="display: none;" id="type_view">all</div>
    <div style="display: none;" id="view_hosting"></div>
    
    <form class="testForm" action="{url mod=$mod act=$act}" method="post">
        <input type="radio" name="entity[view]" value="all" checked="checked" id="all" /> <label for="all">all tests</label>
        &nbsp;&nbsp;&nbsp;
        <input type="radio" name="entity[view]" value="errors" id="errors" /> <label for="errors">errors only</label>
        &nbsp;&nbsp;&nbsp;
        <input type="radio" name="entity[view]" value="passed" id="passed" /> <label for="passed">passed only</label>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="checkbox" name="entity[hosting]" value="yes" id="hosting" /> <label for="passed">test hosting</label>
    </form>
</div>
<button type="button" name="entity[submit]" class="button_start">Start</button>
<div id='mainload'> &nbsp; </div>
<br />
<div class="datagrid_container">
    {include file="$template_dg"}
</div>
<script type="text/javascript">
{rexscript_start}
{literal}
    var updateDatagrid = function() {
        template.find('.datagrid_container').rex(mod, false, {type_view: template.find('#type_view').text(), hosting: template.find('#view_hosting').text()});
    };
    
    $.data(template, 'updateDatagrid', updateDatagrid);
    
    template.find('.button_start').die('click').live('click', function() {
        
        start_main_load();
        $(this).hide();
        
        template.find('.datagrid_container').hide();
        var data = $.rex(mod, 'runConsole', {});
        
        template.find('input[type=radio]').each(function () {
            if ($(this).is(':checked')) {
                template.find('#type_view').text($(this).val());
            }
        });
        
        if (template.find('#hosting').is(':checked')) {
            template.find('#view_hosting').text('yes');
        }
        template.find('#check').text('no');
        
        dinamic();
    });
    
    function dinamic()
    {
        var check = template.find('#check').text();
        
        if (check != 'end') {
            setTimeout(checkDB, 5000);
        } else {
            stop_main_load();
            template.find('.button_start').show();
        }
    }
    
    function checkDB()
    {
        var data = $.rex(mod, 'checkDB', {items: template.find('#items').text()});
        
        if (data !== false) {
            template.find('#check').text(data);
        }
        
        if (data != 'no') {
            $.data(template, 'updateDatagrid')();
            template.find('.datagrid_container').show();
        }
        
        dinamic(); 
    }
    
    template.find('input[type=radio]').die('change').live('change', function() {
        template.find('#type_view').text($(this).val());
        $.data(template, 'updateDatagrid')();
    });
    
    template.find('#hosting').die('change').live('change', function() {
        if ($(this).is(':checked')) {
            template.find('#view_hosting').text('yes');
        } else {
            template.find('#view_hosting').text('');
        }
        
        $.data(template, 'updateDatagrid')();
    });
{/literal}
{rexscript_stop}
</script>