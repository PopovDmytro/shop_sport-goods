<div class="product-def">
    <div class="product-def-top-bg"></div>
    {if !$okprocess and !$confirm_sms}
        {include file="user/login.inc.tpl"}
    {elseif $confirm_sms}
        <div class="row">
            <div class="column small-12">
                <h1 class="section-title section-title--blue">Вход</h1>
            </div>
        </div>
        <div class="into-box">
            <form enctype="multipart/form-data" id="" action="{url mod=user act=login}" method="post">
                <div class="checkout_form-wrapper table-reg">
                    <div class="row">
                        <div class="input-holder column small-12 large-6">
                            <input placeholder="Введите код подтверждения" class="title-captcha" type="text" name="user[code]" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-holder column small-12 large-3">
                            <input class="btn btn--blue" type="submit" name="user[submit]" value="Подтвердить" />
                        </div>
                        <div class="input-holder column small-12 large-3">
                            <input class="btn btn--blue" type="submit" name="user[cancel]" value="Отменить вход" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    {/if}
    <div class="product-def-bottom-bg"></div>
</div>