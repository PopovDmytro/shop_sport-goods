<!--<div class="into-box">
{if !$okprocess}
    {if !$user->id}
        <form enctype="multipart/form-data" id="registration_form" action="" method="post">
            <table cellpadding="0" cellspacing="4px" border="0" class="table-reg">
                <tr>
                    <td class="table-reg-l">E-Mail</td>
                    <td class="table-reg-r"><input class="title" value="{if $registration.email}{$registration.email}{/if}" id="email" name="registration[email]" type="text"/></td>
                </tr>
                <tr>
                    <td class="table-reg-l">Логин</td>
                    <td class="table-reg-r"><input class="title" value="{if $registration.login}{$registration.login}{/if}" id="login" name="registration[login]" type="text"/></td>
                </tr>
                <tr>
                    <td class="table-reg-l">Пароль</td>
                    <td class="table-reg-r"><input class="title" value="{if $registration.clear_password}{$registration.clear_password}{/if}" type="password" name="registration[clear_password]" id="password"/></td>
                </tr>
                <tr>
                    <td class="table-reg-l">Пароль (Ещё раз)</td>
                    <td class="table-reg-r"><input class="title" value="{if $registration.passconfirm}{$registration.passconfirm}{/if}" name="registration[passconfirm]" type="password" id="pass"/></td>
                </tr>
                <tr>
                    <td class="table-reg-l">Телефон(380990987654)</td>
                    <td class="table-reg-r"><input class="title" value="{if $registration.phone}{$registration.phone}{/if}" id="phone" name="registration[phone]" type="text"/></td>
                </tr>
                <tr>
                    <td class="table-reg-l"><img src="{url mod=home act=captcha}" width="85" height="25" /></td>
                    <td class="table-reg-r-captcha"><input class="title-captcha" type="text" name="registration[code]" value="" maxlength="2"></td>
                </tr>
                <tr>
                    <td class="table-reg-l-sub" colspan="2" align="right"><input type="submit" name="registration[submit]" value="Зарегистрироваться" class="reg-button bgcolor round" /></td>
                </tr>
            </table>
        </form>
    {/if}
{/if}
</div>   -->
<div class="into-box">
{if !$okprocess}
    {if !$user->id}
        <form enctype="multipart/form-data" id="user-registration" action="" method="post">
            <table cellpadding="0" cellspacing="4px" border="0" class="table-reg">
                <tr>
                    <td class="table-reg-ag-l">E-Mail <span class="required_fields">*</span> :</td>
                    <td class="table-reg-ag-r"><input class="titlex" value="{if $registration.email}{$registration.email}{/if}" id="email" name="registration[email]" type="text"/></td>
                </tr>
                {*<tr>
                    <td class="table-reg-ag-l">Логин <span class="required_fields">*</span> :</td>
                    <td class="table-reg-ag-r"><input class="titlex" value="{if $registration.login}{$registration.login}{/if}" id="login" name="registration[login]" type="text"/></td>
                </tr>*}
                <tr>
                    <td class="table-reg-ag-l">Пароль <span class="required_fields">*</span> :</td>
                    <td class="table-reg-ag-r"><input class="titlex" value="{if $registration.clear_password}{$registration.clear_password}{/if}" type="password" name="registration[clear_password]" id="password"/></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Пароль (Ещё раз) <span class="required_fields">*</span> :</td>
                    <td class="table-reg-ag-r"><input class="titlex" value="{if $registration.passconfirm}{$registration.passconfirm}{/if}" name="registration[passconfirm]" type="password" id="pass"/></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Фамилия :</td>
                    <td class="table-reg-ag-r"><input class="titlex" value="{if $registration.lastname}{$registration.lastname}{/if}" id="phone" name="registration[lastname]" type="text"/></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Имя :</td>
                    <td class="table-reg-ag-r"><input class="titlex" value="{if $registration.name}{$registration.name}{/if}" id="phone" name="registration[name]" type="text"/></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Телефон(380990987654) <span class="required_fields">*</span> :</td>
                    <td class="table-reg-ag-r"><input class="titlex" value="{if $registration.phone}{$registration.phone}{/if}" id="phone" name="registration[phone]" type="text" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"/></td>
                </tr>
                <tr>
                    <td class="table-reg-ag-l">Город</td>
                    <td class="table-reg-ag-r">
                        <input id="search_city" class="search titlex" name="registration[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}Введите ваш город{/if}" />
                        <input type="hidden" id="registr-city-val" name="" value="{$userentity.city}">
                    </td>
                </tr>
                {*<tr>
                    <td class="table-reg-ag-l">Город</td>
                    <td class="table-reg-ag-r">
                        <select name="registration[city]" class="select_default titlex">
                            <option value="0">Выберите город</option>
                            {foreach from=$city item=icity}
                                <option value="{$icity.id}" {if $userentity.city eq $icity.id}selected{/if}>{$icity.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>*}
                <tr class="fillials_rt"></tr>
                {*<tr class="fillials_rt">
                    <td class="table-reg-ag-l">Филлиал</td>
                    <td class="table-reg-ag-r">
                        <select name="registration[fillials]" class="select_default titlex">
                            <option id="Selcity" value="0">Выберите филлиал города</option>
                            {foreach from=$fillials item=ifillials}
                                <option value="{$ifillials.id}" cid="{$ifillials.city_id}" {if $userentity.fillials eq $ifillials.id}selected{/if}>{$ifillials.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>*}
                <tr>
                    <td class="table-reg-ag-l"><img src="{url mod=home act=captcha}" width="85" height="25" /> <span class="required_fields" id="required_captcha">*</span></td>
                    <td class="table-reg-ag-r"><input class="titlex" type="text" name="registration[code]" value="" maxlength="2"></td>
                </tr>
                <tr>
                <td><span class="required_fields"><p>* Все поля обязательны для заполнения</p></span></td>
                </tr>
                <tr>
                    <td class="table-reg-l-sub"  align="right">
                        <a href="{url mod=user act=forgot}" class="free_button">Восстановление пароля</a>
                    </td>
                    <td class="table-reg-l-sub"  align="right"><input id="free_button" type="submit" name="registration[submit]" value="Зарегистрироваться" class="reg-button bgcolor round" /></td>

                </tr>
            </table>
        </form>
    {/if}
{/if}
</div>
{include file="_block/input.phone.mask.tpl"}
<script language="javascript">
{literal}
    $(document).ready(function(){
        initPhoneMask();
    });
    function load_fillials(cid) {
        var fillials = $.rex('user', 'FillialsByCityId', {task: cid, template: 'registration'});
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