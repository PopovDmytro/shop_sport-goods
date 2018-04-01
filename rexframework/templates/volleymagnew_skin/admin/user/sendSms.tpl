<div style="overflow-y: auto">
    <table cellspacing="5" cellpadding="0" border="0" width="100%">
        <tr>
            <td class="frame-t-td-l">
                User phone number:
            </td>
            <td class="frame-t-td">
                {if $phone}{$phone}{else}<input type="text" id="sms-send-phone" />{/if}
            </td>
        </tr>
        <tr>
            <td class="frame-t-td-l">
                Text:
            </td>
            <td class="frame-t-td">
                <textarea id="send-sms-text" style="width:282px;height:80px;"></textarea>
            </td>
        </tr>
    </table>
</div>

<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="send-sms-button" user_id="{$entity->id}" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">Send</span></button></td>
    </tr>
</table>

<script type="text/javascript">
{rexscript_start}
{literal}
    template.on('click', '#send-sms-button', function() {
        if ($('#sms-send-phone').length && $('#sms-send-phone').val().length < 8) {
            window.alert('Please enter phone number');
            return;
        }
        
        var phone = '';
        if ($('#sms-send-phone').length) {
            phone = $('#sms-send-phone').val();
        }
        
        var data = $.rex('user', 'sendSms', {task: $(this).attr('user_id'), text: $('#send-sms-text').val(), phone: phone});
        if (data !== false) {
            $.closeRexDialog('sendsms');
        }
        
        return false;
    });
{/literal}
{rexscript_stop}
</script>
