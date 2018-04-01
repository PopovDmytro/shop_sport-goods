<div style="text-align: center">
	<form action="" enctype="multipart/form-data" method="post">
	<input type="hidden" name="mod" value="{$mod}">
	<input type="hidden" name="act" value="{$act}">
	<input type="hidden" name="task" value="{$task}">

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table-add">

		<tr>		
			<td valign="top">
				<table cellpadding="0" cellspacing="0" border="0" class="table-add-second">
					<tr>
						<td class="inf-title" valign="middle">Текст рассылки</td>
						<td class="inf-info" valign="middle"> </td>
					</tr>	
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<textarea id="DataFCKeditor" name="mailing[content]">sds</textarea>
			</td>
		</tr>
        <tr>
            <td class="sms-send-check">
                <input type="checkbox" id="sms-send" name="mailing[sms_send]"><label for="sms-send">Включить SMS рассылку</label>
            </td>
        </tr>
        <tr class="sms-mailing">        
            <td valign="top">
                <table cellpadding="0" cellspacing="0" border="0" class="table-add-second">
                    <tr>
                        <td class="inf-title" valign="middle">Текст SMS рассылки</td>
                        <td class="inf-info" valign="middle"> </td>
                    </tr>    
                </table>
            </td>
        </tr>
        <tr class="sms-mailing">
            <td>
                <textarea  id="DataFCKeditor" name="mailing[sms_content]" ></textarea>
            </td>
        </tr>
        <tr class="sms-mailing">
            <td>
                <div>
                    <input type="button" id="sms-sending-button" class="button" value="Отправить тестовое смс"> на номер <input type="text" id="sms-phone" value="{$userphone}">
                </div>
            </td>
        </tr>
		
		<tr>
			<td><input type="submit" class="button" name="mailing[submit]" value="Отправить"></td>
		</tr>
	</table>
	</form>
</div>
<script>
{literal}
    $('#sms-send').change(function(){
        if ($(this).is(':checked')) {
            $('.sms-mailing').show();    
        } else {
            $('.sms-mailing').hide();
        }
    });
    
    $('#sms-sending-button').die('click').live('click', function(){
        $.rex(mod, 'testSms', {text: $('.sms-mailing textarea').val(), phone: $('#sms-phone').val()});
    }); 
    buildCKEditor();   
{/literal}
</script>