<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="/" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Контакты</a>
            </li>
        </ul>
    </div>
</div>

<div class="row contacts-wrapper">
    <div class="column small-12">
        {*from admin static page*}
        {if $staticpage->content}
            <div class="contact_page_content">{$staticpage->content}</div>
        {/if}

        <div class="contacts_mailing-holder text-center">
            <button type="button" class="feedBack btn btn--blue">Написать руководству</button>
        </div>
    </div>
</div>

<div class="content into-box feedbackPopupWrapper hide">
    <div>
        {*page type='getRenderedErrors' section='home'}
        {page type='getRenderedMessages' section='home'*}
    </div>
    <div class="contact_form popup_overlay">
        <div class="popup_box">
        {*<form action="" method="POST">
            <input type="hidden" name="mod" value="home">
            <input type="hidden" name="act" value="contact">*}
            <button type="button" class="closeFeedback || '' popup_close-btn"></button>
            <div class="popup_header"><h3>Написать руководству</h3></div>
            <div class="submit-form">Ваше предложение было успешно отправлено администрации магазина!</div>
            <div class="form-body popup_body">
                <div class="text-contact" autocomplete="off">
                    <input placeholder="Ваше Имя: *" type="text" name="contact[name]" class="inpcontact titlex" id="contact-name" value="{if $contact.name}{$contact.name}{/if}" maxlength="64">
                    <div class="contact-name-error contact-error">Имя должно быть не меньше 3х символов</div>
                </div>
                <div class="text-contact" autocomplete="off">
                    <input placeholder="Email: *" type="text" name="contact[email]" class="inpcontact titlex" id="contact-email" value="{if $contact.email}{$contact.email}{/if}" maxlength="64">
                    <div class="contact-email-error contact-error">Некоректный email</div>
                </div>
                <div class="text-contact" autocomplete="off">
                    <textarea placeholder="Cообщение: *" name="contact[text]" class="inpcontact titlex"  id="contact-text" style="max-width:445px; resize: none;">{if $contact.text}{$contact.text}{/if}</textarea>
                    <div class="contact-text-error contact-error">Сообщение не может быть меньше 2х символов</div>
                </div>
                <div class="text-contact" autocomplete="off" id="contact-code">
                    <img src="/index.php?mod=home&act=captcha" width="85" height="25">
                </div>
                <div class="text-contact" >
                    <input type="text" class="inpcontact titlex captcha-text" name="contact[code]" value="" maxlength="2">
                    <div class="captcha-form-error contact-error">Неправильный captcha код</div>
                </div>
                <div class="required"><span class="star">*</span> - поля обязательные для заполнения</div>
                <input type="submit" name="contact[submit]" class="contact_button popup_submit-button btn btn--blue" id="free_button" value="Отправить" />
            </div>
        {*</form> *}
        </div>
    </div>
    <div class="contact-background"></div>
</div>