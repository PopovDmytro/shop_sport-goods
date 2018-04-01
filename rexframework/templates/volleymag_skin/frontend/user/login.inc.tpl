{if !$user->id} 
        <ul class="breadcrumbs">
            <li>
                <a href="/">Главная</a>
            </li>
            <li>Форма авторизации</li>
        </ul>
        <div class="message-box error-box">
                {page type='getRenderedMessages' section='user'}
                {page type='getRenderedErrors' section='user'}
        </div>
        <div class="into-box login-box" style="margin-top:10px;">
            <form action="{url mod=user act=login}" method="post">
                <input type="hidden" name="user[request]" value="{$CURRENT_PAGE}">
                <table cellpadding="0" cellspacing="4" border="0" class="table-reg">
                    <tr>
                        {*<td class="table-reg-ag-l">E-mail</td>*}
                        <td class="table-reg-r" colspan="2"><div class="login-in">E-mail</div><input id="user_email" type="text" name="user[email]" class="login-input titlex" value="{if $oemail}{$oemail}{/if}"/></td>
                    </tr>
                    <tr>
                        {*<td class="table-reg-ag-l">Пароль</td>*}
                        <td class="table-reg-r" colspan="2"><div class="login-in">Пароль</div><input id="password" type="password" name="user[password]" class="login-input titlex" value=""/></td>
                    </tr>
                    <tr>
                    <td class="table-reg-l-sub"  align="right"><a href="{url mod=user act=forgot}" id="free_button" class="free_button">Восстановление пароля</a></td>
                        <td class="table-reg-l-submit"  align="right">
                            <input type="submit" id="free_button" class="login-submit bgcolor round c" name="user[submitlogin]" value="Войти">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        {include file='user/sociallogin.tpl'}
{else}
    {*<script type="text/javascript">document.location.href='http://{$DOMAIN}/profile/{$user->id}/'</script>*}
    <!--script type="text/javascript">document.location.href='{url mod=cart}'</script-->
{/if}
