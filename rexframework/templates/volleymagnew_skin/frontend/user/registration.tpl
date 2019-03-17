<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Регистрация</a>
            </li>
        </ul>
    </div>
</div>


<td valign="top">
    <div class="product-def">
	    <div class="error-box row">
            <div class="column small-12">
                {page type='getRenderedMessages' section='user'}
                {page type='getRenderedErrors' section='user'}
            </div>
        </div>
        <br />
        {if !$okprocess and !$confirm_sms}
            {include file="user/registration.inc.tpl"}
        {elseif $confirm_sms}
            <div class="row">
                <div class="column small-12">
                    <h1 class="section-title section-title--blue">Регистрация</h1>
                </div>
            </div>
            <div class="into-box">
                <form enctype="multipart/form-data" id="registration_form" action="" method="post">
                    <div class="checkout_form-wrapper table-reg">
                        <div class="row">
                            <div class="input-holder column small-12 large-6">
                                <input placeholder="Введите код подтверждения" class="title-captcha" type="text" name="phone[code]" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-holder column small-12 large-3">
                                <input class="btn btn--blue" type="submit" name="phone[submit]" value="Подтвердить" />
                            </div>
                            <div class="input-holder column small-12 large-3">
                                <input class="btn btn--blue"  type="submit" name="phone[cancel]" value="Сбросить регистрацию" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        {/if}
	</div>
</td>
