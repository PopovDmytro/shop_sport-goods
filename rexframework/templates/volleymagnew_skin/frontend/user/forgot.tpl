<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Восстановление пароля </a>
            </li>
        </ul>
    </div>
</div>

<div class="product-def">
        <div class="product-def-top-bg"></div>
        <div class="into-box" style="margin-top:10px;">
            {page type='getRenderedMessages' section='user'}
            {page type='getRenderedErrors' section='user'}
            {if !$okprocess and !$user->id}
            
                <form enctype="multipart/form-data" action="" method="post">
                    <input type="hidden" name="forgot[submit]" value="Forgot">
                    <table cellpadding="0" cellspacing="4" border="0" class="table-reg">
                        <tr>
                            <td class="table-reg-ag-l">E-Mail</td>
                            <td class="table-reg-r"><input class="login-input titlex" value="{if $forgot.email}{$forgot.email}{/if}" id="user_email" name="forgot[email]" type="text"/></td>
                        </tr>
                        <tr>
                            <td class="table-reg-ag-l"><img src="{url mod=home act=captcha}" width="85" height="25" /></td>
                            <td class="table-reg-r-captcha"><input class="login-input titlex" type="text" name="forgot[code]" value="" maxlength="10"></td>
                        </tr>
                        <tr>
                            <td class="table-reg-l-sub" colspan="2" align="right"><input type="submit" id="free_button" name="forgot[submit]" value="Выслать пароль" class="forgot-send-submit" />
                            </td>
                        </tr>
                    </table>
                </form>
            {/if}
        </div>
        <div class="product-def-bottom-bg"></div>
    </div>