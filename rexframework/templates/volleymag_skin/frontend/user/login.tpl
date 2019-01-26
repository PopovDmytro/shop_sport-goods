
    <div class="product-def">
    <div class="product-def-top-bg"></div>

        {if !$okprocess and !$confirm_sms}
            {include file="user/login.inc.tpl"}
        {elseif $confirm_sms}
            <h1>Вход</h1>
            <div class="into-box">
                <form enctype="multipart/form-data" id="" action="{url mod=user act=login}" method="post">
                    <table cellpadding="0" cellspacing="4px" border="0" class="table-reg">
                        <tr>
                            <td class="table-reg-l">Введите код подтверждения</td>
                            <td class="table-reg-r-captcha"><input class="title-captcha" type="text" name="user[code]" value=""></td>
                        </tr>
                        <tr>
                            <td class="table-reg-l-sub" align="right"><input type="submit" name="user[submit]" value="Подтвердить" /></td>
                            <td class="table-reg-l-sub" align="right"><input type="submit" name="user[cancel]" value="Отменить вход" /></td>
                        </tr>
                    </table>
                </form>
            </div>
        {/if}
    
    <div class="product-def-bottom-bg"></div>
    </div>
