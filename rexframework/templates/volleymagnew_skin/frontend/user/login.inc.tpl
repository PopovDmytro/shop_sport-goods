{if !$user->id}
    <div class="breadcrumbs-block row">
        <div class="columns small-12">
            <ul class="breadcrumbs_list no-bullet">
                <li class="breadcrumbs_item">
                    <a href="{url mod=home}" class="breadcrumbs_link">
                        <i aria-hidden="true" class="fa fa-home"></i>
                        Главная
                    </a>
                </li>
                <li class="breadcrumbs_item active">
                    <a href="javascript:void(0)" class="breadcrumbs_link">Форма авторизации</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="checkout_form into-box login-box">
        <div class="row collapse">
            <div class="columns small-12">

                <div class="checkout_form-wrapper row table-reg">
                    <div class="columns small-12">
                        <div class="message-box error-box">
                            {page type='getRenderedMessages' section='user'}
                            {page type='getRenderedErrors' section='user'}
                        </div>
                    </div>
                    <br />
                    <br />

                    <div class="columns large-5">
                        <form action="{url mod=user act=login}" method="post">
                            <input type="hidden" name="user[request]" value="{$CURRENT_PAGE}">
                            <div class="row small-up-1">
                                <div class="input-holder column">
                                    <input placeholder="E-mail" id="user_email" type="text" name="user[email]" class="login-input titlex" value="{if $oemail}{$oemail}{/if}"/>
                                </div>
                                <div class="input-holder column">
                                    <input placeholder="Пароль" id="password" type="password" name="user[password]" class="login-input titlex" value=""/>
                                    <br />
                                    <a href="{url mod=user act=forgot}" id="free_button" class="free_button">Восстановление пароля</a>
                                </div>

                                <div class="input-holder column">
                                    <input type="submit" id="free_button" class="btn btn--blue" name="user[submitlogin]" value="Войти">
                                </div>
                            </div>
                        </form>
                    </div>
                    <br />

                    <div class="columns small-12">
                        {include file='user/sociallogin.tpl'}
                    </div>
                </div>
            </div>
        </div>
    </div>
{else}
    {*<script type="text/javascript">document.location.href='http://{$DOMAIN}/profile/{$user->id}/'</script>*}
    <!--script type="text/javascript">document.location.href='{url mod=cart}'</script-->
{/if}