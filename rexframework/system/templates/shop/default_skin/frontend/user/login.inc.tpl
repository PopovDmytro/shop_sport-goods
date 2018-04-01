{if !$user->id}
        <div class="nav_category bgcolor round">
            <p class="navigation-p">                                      
            {strip}
                Форма авторизации
            {/strip}
            </p>
        </div>  
        <div class="into-box">
            <div class="message-box">
                {page type='getRenderedMessages' section='user'}
                {page type='getRenderedErrors' section='user'}
            </div>
            <form action="{url mod=user act=login}" method="post">
                <input type="hidden" name="user[request]" value="{$CURRENT_PAGE}">
                <table cellpadding="0" cellspacing="4" border="0" class="table-reg">
                    <tr>
                        <td class="table-reg-l">E-mail</td>
                        <td class="table-reg-r"><input id="user_email" type="text" name="user[email]" class="login-input" value=""/></td>
                    </tr>
                    <tr>
                        <td class="table-reg-l">Пароль</td>
                        <td class="table-reg-r"><input id="password" type="password" name="user[password]" class="login-input" value=""/></td>
                    </tr>
                    <tr>
                        <td class="table-reg-l-submit" colspan="2" align="right"><input type="submit" class="login-submit bgcolor round c" name="user[submitlogin]" value="Войти"></td>
                    </tr>
                </table>
            </form>
        </div>
{else}
    {*<script type="text/javascript">document.location.href='http://{$DOMAIN}/profile/{$user->id}/'</script>*}
    <!--script type="text/javascript">document.location.href='{url mod=cart}'</script-->
{/if}