<div class="into-box checkout_form">
    <div class=" collapse row">
        <div class="columns small-12">
        {if !$okprocess}
            {if !$user->id}
                <form enctype="multipart/form-data" id="user-registration" action="" method="post">
                    <div class="table-reg checkout_form-wrapper">
                        <div class="columns large-5">
                            <div class="row small-up-1">
                                <div class="input-holder column">
                                    <input placeholder="E-Mail *" class="titlex" value="{if $registration.email}{$registration.email}{/if}" id="email" name="registration[email]" type="text"/>
                                </div>
                                <div class="input-holder column">
                                    <input placeholder="Пароль *" class="titlex" value="{if $registration.clear_password}{$registration.clear_password}{/if}" type="password" name="registration[clear_password]" id="password"/>
                                </div>
                                <div class="input-holder column">
                                    <input placeholder="Пароль (Ещё раз) *" class="titlex" value="{if $registration.passconfirm}{$registration.passconfirm}{/if}" name="registration[passconfirm]" type="password" id="pass"/>
                                </div>
                                <div class="input-holder column">
                                    <input placeholder="Фамилия" class="titlex" value="{if $registration.lastname}{$registration.lastname}{/if}" id="lastname" name="registration[lastname]" type="text"/>
                                </div>
                                <div class="input-holder column">
                                    <input placeholder="Имя" class="titlex" value="{if $registration.name}{$registration.name}{/if}" id="name" name="registration[name]" type="text"/>
                                </div>
                                <div class="input-holder column">
                                    <input class="titlex" value="{if $registration.phone}{$registration.phone}{/if}" id="phone" name="registration[phone]" type="text" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"/>
                                </div>
                                <div class="input-holder column searchcity">
                                    <input placeholder="Город" id="search_city" class="search titlex" name="registration[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}Введите ваш город{/if}" />
                                    <input type="hidden" id="registr-city-val" name="" value="{$userentity.city}">
                                </div>
                                <div class="fillials_rt"></div>
                                <div class="input-holder column">
                                    <img src="{url mod=home act=captcha}" width="85" height="25" /> <span class="required_fields" id="required_captcha">*</span>
                                    <input class="titlex" type="text" name="registration[code]" value="" maxlength="2">
                                </div>
                                <div class="input-holder column">
                                    <span class="required_fields"><p>* Все поля обязательны для заполнения</p></span>
                                </div>
                                <div class="input-holder column">
                                    <a href="{url mod=user act=forgot}" class="free_button">Восстановление пароля</a>
                                    <br />
                                    <br />
                                    <input id="free_button" type="submit" name="registration[submit]" value="Зарегистрироваться" class="reg-button bgcolor round btn btn--blue" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            {/if}
        {/if}
        </div>
    </div>
</div>
{include file="_block/input.phone.mask.tpl"}
<script language="javascript">
{literal}
    $(document).ready(function(){
        initPhoneMask();
    });
    function load_fillials(cid) {
        var fillials = $.rex('user', 'FillialsByCityId', {task: cid, template: 'registration'});
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