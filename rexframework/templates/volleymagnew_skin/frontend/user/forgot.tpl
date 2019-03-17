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


<section class="profile-tabs">
    <div class="row align-center">
        <div class="columns small-12 large-8">
            <div class="profile_tabs-container product-def">

                <div class="product-def-top-bg"></div>
                <div class="into-box" style="margin-top:10px;">
                    {page type='getRenderedMessages' section='user'}
                    {page type='getRenderedErrors' section='user'}
                    {if !$okprocess and !$user->id}

                        <form enctype="multipart/form-data" action="" method="post">
                            <input type="hidden" name="forgot[submit]" value="Forgot">

                            <div class="row small-up-1 py-1">
                                <table cellpadding="0" cellspacing="4" border="0" class="table-reg">
                                    <div class="input-holder column">
                                        <input placeholder="E-Mail" class="login-input titlex" value="{if $forgot.email}{$forgot.email}{/if}" id="user_email" name="forgot[email]" type="text"/>
                                    </div>
                                    <div class="input-holder column small-3">
                                        <img src="{url mod=home act=captcha}" width="85" height="25" />
                                        <input class="login-input titlex" type="text" name="forgot[code]" value="" maxlength="10">
                                    </div>
                                    <div class="input-holder column">
                                        <button type="submit" id="free_button" name="forgot[submit]" value="Выслать пароль" class="forgot-send-submit btn btn--green" >Выслать пароль</button>
                                    </div>
                                </table>
                            </div>
                        </form>
                    {/if}
                </div>
                <div class="product-def-bottom-bg"></div>

            </div>
        </div>
    </div>
</section>