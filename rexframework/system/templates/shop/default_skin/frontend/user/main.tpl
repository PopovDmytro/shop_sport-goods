<div style="padding-left: 10px;padding-right:10px;">
	<div class="product-def">
        <div class="into-box">
		<h1>Редактирование настроек</h1>
        
        <a href="{url mod=user act=default id=$userentity.id}">{$userentity.login}</a>
		
			{page type='getRenderedMessages'}
			{page type='getRenderedErrors'}
			
			<form action="" enctype="multipart/form-data" method="post">
				<input type="hidden" name="mod" value="{$mod}">
				<input type="hidden" name="act" value="{$act}">
				
				
				<br/><strong>Общие сведения</strong><br/>
				
				<div class="profile-edit-div">Фамилия:</div>
				<input type="text" class="profile-edit-input" name="profile[lastname]" maxlength="128" value="{$userentity.lastname}"><br/>	
				<div class="profile-edit-div">Имя:</div>
				<input type="text" class="profile-edit-input" name="profile[name]" maxlength="128" value="{$userentity.name}"><br/>
				<div class="profile-edit-div">Отчество:</div>
				<input type="text" class="profile-edit-input" name="profile[middlename]" maxlength="128" value="{$userentity.middlename}"><br/>	
				
				<br/><strong>Контактная информация</strong><br/>
				
				<div class="profile-edit-div">Город:</div>
				<input type="text" class="profile-edit-input" name="profile[city]" maxlength="128" value="{$userentity.city}"><br/>	
				<div class="profile-edit-div">Адрес:</div>
				<input type="text" class="profile-edit-input" name="profile[address]" maxlength="128" value="{$userentity.address}"><br/>	
				<div class="profile-edit-div">Индекс:</div>
				<input type="text" class="profile-edit-input" name="profile[zip]" maxlength="128" value="{$userentity.zip}"><br/>	
				<div class="profile-edit-div">Контактный телефон</div>
				<input class="profile-edit-input" value="{if $userentity.phone}{$userentity.phone}{/if}" name="profile[phone]" type="text" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"/><br/>
					
				<br/><strong>Предпочитаемый вид доставки</strong><br/>
				
				<div class="profile-edit-div">Получатель</div>
				<input class="profile-edit-input" value="{if $userentity.receiver}{$userentity.receiver}{/if}" name="profile[receiver]" type="text"/><br/>
				<div class="profile-edit-div">Грузоперевозчик</div>
				<select name="profile[delivery]" class="profile-edit-select">
					<option value="1" {if $userentity.delivery eq 1}selected{/if}>Гюнсел</option>
					<option value="2" {if $userentity.delivery eq 2}selected{/if}>Автолюкс</option>
					<option value="3" {if $userentity.delivery eq 3}selected{/if}>Нова Пошта</option>
					<option value="4" {if $userentity.delivery eq 4}selected{/if}>Другой (укажите в примечании)</option>
				</select><br/><br/>
				<div class="profile-edit-div">Примечания</div>
				<textarea name="profile[notice]" class="profile-edit-textarea">{if $userentity.notice}{$userentity.notice}{/if}</textarea><br/>
				
				<div class="profile-edit-div"><input type="submit" class="profile-edit-submit" name="profile[submit]" value="Отправить"></div>
			</form>
		</div>
		<div class="product-def-bottom-bg"></div>
	</div>
</div>
{include file="_block/input.phone.mask.tpl"}
<script type="text/javascript">
	$(document).ready(function(){
		initPhoneMask();
	});
</script>