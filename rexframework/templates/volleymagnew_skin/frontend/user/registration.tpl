<td valign="top" style="min-width:700px; width:702px;">
	<ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a>
        </li>
        <li>Регистрация</li>
    </ul>
    <div class="product-def">
	    <div class="error-box">
            {page type='getRenderedMessages' section='user'}
            {page type='getRenderedErrors' section='user'}
        </div>
        {if !$okprocess and !$confirm_sms}
            {include file="user/registration.inc.tpl"}
        {elseif $confirm_sms}
            <h1>Регистрация</h1>
            <div class="into-box">
                <form enctype="multipart/form-data" id="registration_form" action="" method="post">
                    <table cellpadding="0" cellspacing="4px" border="0" class="table-reg">
                        <tr>
                            <td class="table-reg-l">Введите код подтверждения</td>
                            <td class="table-reg-r-captcha"><input class="title-captcha" type="text" name="phone[code]" value=""></td>
                        </tr>
                        <tr>
                            <td class="table-reg-l-sub" align="right"><input type="submit" name="phone[submit]" value="Подтвердить" /></td>
                            <td class="table-reg-l-sub" align="right"><input type="submit" name="phone[cancel]" value="Сбросить регистрацию" /></td>
                        </tr>
                    </table>
                </form>
            </div>
        {/if}
	</div>
</td>