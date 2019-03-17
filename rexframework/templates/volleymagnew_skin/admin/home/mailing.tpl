<div style="text-align: center">
	<form action="" enctype="multipart/form-data" method="post" id="mailing-form">
	<input type="hidden" name="mod" value="{$mod}">
	<input type="hidden" name="act" value="sendMail">
	<input type="hidden" name="task" value="{$task}">

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table-add">
        <tr class="progressbar-row">
            <td>
                <div class="admin-progressbar-container" style="height:30px;">
                    <div id="myBar" class="admin-progressbar admin-green" style="width: 100%;">
                        <div class="admin-center admin-text-white" id="percent-value" style="line-height:30px;">0%</div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>        
            <td valign="top">
                <span>Модуль рассылки:</span>
                <select name="mailing[role]" class="mailing-role">
                     <option value="">Выберите роль</option>
                     <option value="admin">Администраторам</option>
                     <option value="operator">Операторам</option>
                     <option value="user">Пользователям</option>
                     <option value="subscriber">Подписчикам</option>
                </select>
            </td>
        </tr>
        <tr class="user-order-statuses">
            <td valign="top">
                <span>Статус заказов:</span>
                <select name="mailing[status]">
                    <option value="">Все</option>
                    <option value="1">Новый</option>
                    <option value="9">СРОЧНО</option>
                    <option value="3">Завершен</option>
                </select>
            </td>
        </tr>
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
				<textarea id="DataFCKeditor" name="mailing[content]"></textarea>
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
                <textarea name="mailing[sms_content]" ></textarea>
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
			<td><input type="button" class="button send-mail-button" name="mailing[submit]" value="Отправить"></td>
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
        var res = $.rex(mod, 'testSms', {text: $('.sms-mailing textarea').val(), phone: $('#sms-phone').val()});
        if (res == 'ok') {
            alert('Тестовая смс успешно отправлена');
        } else {
            alert('Произошла ошибка');
        }
    });

    $('.send-mail-button').on('click', function(){
        var form = $(this).parents('form');
        form.find('#DataFCKeditor').text(CKEDITOR.instances.DataFCKeditor.getData());
        $.rex('home', 'sendMail', form.serialize(), function (response){
            if (response.status === 'success') {
                $('.progressbar-row').show().find('#percent-value').text(response.progress);
            }
        });
    });

    buildCKEditor();   
{/literal}
</script>