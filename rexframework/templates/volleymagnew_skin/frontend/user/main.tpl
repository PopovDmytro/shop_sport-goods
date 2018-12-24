<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                {*<a href="{url mod=user id=$user->id}" class="breadcrumbs_link">Профиль</a>*}
                <a href="javascript:void(0)" class="breadcrumbs_link">Профиль</a>
            </li>
            <li class="breadcrumbs_item active">
                {*<a href="{url mod=user act=default id=$userentity.id}" class="breadcrumbs_link">&nbsp;{$userentity.login}</a>*}
                <a href="javascript:void(0)" class="breadcrumbs_link">Редактирование настроек:&nbsp;{$userentity.login}</a>
            </li>
        </ul>
    </div>
</div>


<section class="profile-tabs">
    <div class="row align-center">
        <div class="columns small-12 large-8">
            <div class="profile_tabs-container product-def">
                {include file="user/intobox.text.tpl"}
                <div class="into-box">
                    {page type='getRenderedMessages'}
                    {page type='getRenderedErrors'}

                    <form action="" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="mod" value="{$mod}">
                        <input type="hidden" name="act" value="{$act}">

                        <div class="row small-up-1 py-1 table-reg">
                            <div class="input-holder column">
                                {*Фамилия:*}
                                <input type="text" class="profile-edit-input titlex" name="profile[lastname]" maxlength="128" value="{$userentity.lastname}">
                            </div>
                            <div class="input-holder column">
                                {*Имя:*}
                                <input type="text" class="profile-edit-input titlex" name="profile[name]" maxlength="128" value="{$userentity.name}">
                            </div>
                            <div class="input-holder column">
                                {*Адрес:*}
                                <input type="text" class="profile-edit-input titlex" name="profile[address]" maxlength="128" value="{$userentity.address}">
                            </div>
                            <div class="input-holder column">
                                {*E-Mail:*}
                                <input type="text" class="profile-edit-input titlex" name="profile[email]" maxlength="128" value="{$userentity.email}">
                            </div>
                            <div class="input-holder column">
                                {*Телефон(380990987654):*}
                                <input class="profile-edit-input titlex" value="{if $userentity.phone}{$userentity.phone}{/if}" name="profile[phone]" type="text" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"/>
                            </div>
                            <div class="input-holder column">
                                {*Город:*}
                                {*<input id="search_city" class="search titlex" name="q" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$usercity}{/if}" />
                                <input type="hidden" id="registr-city-val" name="profile[city]" value="{$userentity.city}">*}
                                <input id="search_city" class="search titlex" name="profile[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$userentity.city}{/if}" />
                                <input type="hidden" id="registr-city-val" name="" value="{$usercity_id}">
                            </div>
                            <div class="fillials_rt" ></div>

                            <div class="input-holder column">
                                <button type="submit" id="free_button" class="btn btn--green profile-edit-submit" name="profile[submit]" value="Отправить">Отправить</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="product-def-bottom-bg"></div>

            </div>
        </div>
    </div>
</section>

{include file="_block/input.phone.mask.tpl"}
<script language="javascript">
{literal}
    $(document).ready(function(){
        initPhoneMask();
    });
    function load_fillials(cid) {
        var fillials = $.rex('user', 'FillialsByCityId', {task: cid, template: 'user'});
            if (fillials != 'false') {
                $('.fillials_rt').replaceWith(fillials);
            }  else {
                $('.fillials_rt').html('');
            }
    }

    if ($('#registr-city-val').val()) {
        load_fillials($('#registr-city-val').val());
    }

    $('#registr-city-val').on('change', function(){
         load_fillials($('#registr-city-val').val());
    });

{/literal}
</script>