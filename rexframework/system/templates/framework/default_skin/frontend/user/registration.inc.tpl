{if !$user->id}
<h1>Регистрация</h1>
<div class="into-box">
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
                <td class="table-reg-r"><input class="title" value="{if $registration.password}{$registration.password}{/if}" type="password" name="registration[clear_password]" id="password"/></td>
            </tr>
            <tr>
                <td class="table-reg-l">Пароль (Ещё раз)</td>
                <td class="table-reg-r"><input class="title" value="{if $registration.passconfirm}{$registration.passconfirm}{/if}" name="registration[passconfirm]" type="password" id="pass"/></td>
            </tr>
            <tr>
                <td class="table-reg-l">Телефон</td>
                <td class="table-reg-r"><input class="title" value="{if $registration.phone}{$registration.phone}{/if}" id="phone" name="registration[phone]" type="text" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"/></td>
            </tr>
            <tr>
                <td class="table-reg-l"><img src="{url mod=home act=captcha}" width="85" height="25" /></td>
                <td class="table-reg-r-captcha"><input class="title-captcha" type="text" name="registration[code]" value="" maxlength="2"></td>
            </tr>
            <tr>
                <td class="table-reg-l-sub" colspan="2" align="right"><input type="submit" name="registration[submit]" value="Зарегистрироваться" class="reg-button" /></td>
            </tr>
        </table>
    </form>
</div>
{/if}
{include file="_block/input.phone.mask.tpl"}
<script type="text/javascript">
    $(document).ready(function(){
        initPhoneMask();
    });
</script>