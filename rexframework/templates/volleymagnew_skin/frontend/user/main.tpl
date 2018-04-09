<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item">
                <a href="{url mod=user id=$user->id}" class="breadcrumbs_link">Профиль</a>
            </li>
            <li class="breadcrumbs_item">
                Редактирование настроек:
                <a href="{url mod=user act=default id=$userentity.id}" class="breadcrumbs_link">&nbsp;{$userentity.login}</a>
            </li>
        </ul>
    </div>
</div>


<div class="product-def">
    {include file="user/intobox.text.tpl"}
    <div class="into-box">
		{page type='getRenderedMessages'}
		{page type='getRenderedErrors'}

		<form action="" enctype="multipart/form-data" method="post">
			<input type="hidden" name="mod" value="{$mod}">
			<input type="hidden" name="act" value="{$act}">

			<table cellpadding="0" cellspacing="4px" border="0" class="table-reg">
                <tr>
                    <td class="table-reg-ag-l">Фамилия:</td>
                    <td class="table-reg-ag-r"><input type="text" class="profile-edit-input titlex" name="profile[lastname]" maxlength="128" value="{$userentity.lastname}"></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Имя:</td>
                    <td class="table-reg-ag-r"><input type="text" class="profile-edit-input titlex" name="profile[name]" maxlength="128" value="{$userentity.name}"></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Адрес:</td>
                    <td class="table-reg-ag-r"><input type="text" class="profile-edit-input titlex" name="profile[address]" maxlength="128" value="{$userentity.address}"></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">E-Mail:</td>
                    <td class="table-reg-ag-r"><input type="text" class="profile-edit-input titlex" name="profile[email]" maxlength="128" value="{$userentity.email}"></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Телефон(380990987654):</td>
                    <td class="table-reg-ag-r"><input class="profile-edit-input titlex" value="{if $userentity.phone}{$userentity.phone}{/if}" name="profile[phone]" type="text" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"/></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Город:</td>
                    <td class="table-reg-ag-r">
                       {*<input id="search_city" class="search titlex" name="q" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$usercity}{/if}" />
                       <input type="hidden" id="registr-city-val" name="profile[city]" value="{$userentity.city}">*}
                       <input id="search_city" class="search titlex" name="profile[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$userentity.city}{/if}" />
                       <input type="hidden" id="registr-city-val" name="" value="{$usercity_id}">
                    </td>
                </tr>
                <tr class="fillials_rt" style="float:left;"></tr>
                
                <tr>
                    <td colspan="2" class="table-reg-l-sub"  align="right"><input type="submit" id="free_button" class="profile-edit-submit" name="profile[submit]" value="Отправить"></td>
                </tr>
            </table>
			{*<br/><strong>Общие сведения</strong><br/>

			<input type="text" class="profile-edit-input titlex" name="profile[lastname]" maxlength="128" value="{$userentity.lastname}"><br/>
			<div class="profile-edit-div">ФИО:</div>
			<input type="text" class="profile-edit-input titlex" name="profile[name]" maxlength="128" value="{$userentity.name}"><br/>
			<br/><strong>Контактная информация</strong><br/>

			<input type="text" class="profile-edit-input titlex" name="profile[city]" maxlength="128" value="{$userentity.city}"><br/>
			<div class="profile-edit-div">Адрес:</div>
			<input type="text" class="profile-edit-input titlex" name="profile[address]" maxlength="128" value="{$userentity.address}"><br/>
			<div class="profile-edit-div">E-mail:</div>
			<input type="text" class="profile-edit-input titlex" name="profile[email]" maxlength="128" value="{$userentity.email}"><br/>
			<div class="profile-edit-div">Контактный телефон</div>
			<input class="profile-edit-input titlex" value="{if $userentity.phone}{$userentity.phone}{/if}" name="profile[phone]" type="text"/><br/>
            <div class="profile-edit-div">Город</div>
                <input id="search_city" class="search titlex" name="q" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$usercity}{/if}" />
                <input type="hidden" id="registr-city-val" name="profile[city]" value="{$userentity.city}">
            <br/>
			<div class="profile-edit-div">Филлиал</div>
            <select name="profile[fillials]" style="width: 250px" class="select_default titlex">
                <option id="Selcity" value="0">Выберите филлиал города</option>
                {foreach from=$fillials item=ifillials}
                    <option value="{$ifillials.id}" cid="{$ifillials.city_id}" {if $userentity.fillials eq $ifillials.id}selected{/if}>{$ifillials.name}</option>
                {/foreach}
            </select>
            <br/><br/>
			<br/><strong>Предпочитаемый вид доставки</strong><br/>

			<div class="profile-edit-div">Получатель</div>
			<input class="profile-edit-input titlex" value="{if $userentity.receiver}{$userentity.receiver}{/if}" name="profile[receiver]" type="text"/><br/>
			<div class="profile-edit-div">Грузоперевозчик</div>
			<select name="profile[delivery]" class="profile-edit-select">
				<option value="1" {if $userentity.delivery eq 1}selected{/if}>Гюнсел</option>
				<option value="2" {if $userentity.delivery eq 2}selected{/if}>Автолюкс</option>
				<option value="3" {if $userentity.delivery eq 3}selected{/if}>Нова Пошта</option>
				<option value="4" {if $userentity.delivery eq 4}selected{/if}>Другой (укажите в примечании)</option>
			</select><br/><br/>*}

			{*<div class="profile-edit-div">Примечания</div>
			<textarea name="profile[notice]" id="comment_text" class="profile-edit-textarea">{if $userentity.notice}{$userentity.notice}{/if}</textarea><br/>

			<div class="profile-edit-div"><input type="submit" id="free_button" class="profile-edit-submit" name="profile[submit]" value="Отправить"></div>*}
		</form>
	</div>
	<div class="product-def-bottom-bg"></div>
</div>
{include file="_block/input.phone.mask.tpl"}
<script language="javascript">
{literal}
    $(document).ready(function(){
        initPhoneMask();
    });
    function load_fillials(cid) {
        var fillials = $.rex('user', 'FillialsByCityId', {task: cid, template: 'user'});
            if (fillials != 'false') {
                $('.fillials_rt').replaceWith(fillials);
            }  else {
                $('.fillials_rt').html('');
            }
    }

    if ($('#registr-city-val').val()) {
        load_fillials($('#registr-city-val').val());
    }

    $('#registr-city-val').on('change', function(){
         load_fillials($('#registr-city-val').val());
    });

{/literal}
</script>
