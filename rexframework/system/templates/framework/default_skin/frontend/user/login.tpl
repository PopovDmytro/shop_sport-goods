<td valign="top" style="min-width:700px; width:702px;">
	<div class="product-def">
	<div class="product-def-top-bg"></div>
	
	<div class="message-box">
	{page type='getRenderedMessages' section='user'}
	{page type='getRenderedErrors' section='user'}
    {if !$confirm_sms}
	    {include file="user/login.inc.tpl"}
    {else}
        <h1>Sms-подтверждение</h1>
        <div class="into-box">
            <form enctype="multipart/form-data" id="registration_form" action="" method="post">
                <table cellpadding="0" cellspacing="4px" border="0" class="table-reg">
                    <tr>
                        <td class="table-reg-l">Введите код подтверждения</td>
                        <td class="table-reg-r-captcha"><input class="title-captcha" type="text" name="user[code]" value=""></td>
                    </tr>
                    <tr>
                        <td class="table-reg-l-sub" align="right"><input type="submit" name="user[submit]" value="Подтвердить" /></td>
                        <td class="table-reg-l-sub" align="right"><input type="submit" name="user[cancel]" value="Сбросить логин" /></td>
                    </tr>
                </table>
            </form>
        </div>
    {/if}
	</div>
	
	<div class="product-def-bottom-bg"></div>
	</div>
</td>