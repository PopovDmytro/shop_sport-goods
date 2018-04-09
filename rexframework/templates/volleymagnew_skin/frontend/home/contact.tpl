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

{* admin static page
{if $staticpage->content}
    <div class="contact_page_content">{$staticpage->content}</div>
{/if}
*}

<div class="row contacts-wrapper">
    <div class="column small-12">
        <h1 class="section-title section-title--blue">Контакты</h1>
        <p>Получить консультацию в выборе товара, узнать наличие интересующих Вас<br>моделей и условий доставки
            Вы можете, связавшись с менеджером любым удобным Вам способом:</p>
        <ul class="no-bullet contacts_list">
            <li class="contacts_list_item">
                <div class="icon-holder">
                    <div class="inner-wrapper">
                        {img src="contacts/tel_icon--blue.png"}
                    </div>
                </div>
                <div class="text-holder">
                    <a href="tel:097-948-50-39" class="regular-txt">097 948 50 39</a>
                    <a href="tel:099-923-81-89" class="regular-txt">099 923 81 89</a>
                </div>
            </li>
            <li class="contacts_list_item">
                <div class="icon-holder">
                    <div class="inner-wrapper">
                        {img src="contacts/mail_icon--blue.png"}
                    </div>
                </div>
                <div class="text-holder">
                    <a href="mailto:zakaz@volleymag.com.ua" class="regular-txt">zakaz@volleymag.com.ua</a>
                </div>
            </li>
            <li class="contacts_list_item">
                <div class="icon-holder">
                    <div class="inner-wrapper">
                        {img src="contacts/skype_icon--blue.png"}
                    </div>
                </div>
                <div class="text-holder">
                    <span class="regular-txt">loginskype</span>
                </div>
            </li>
            <li class="contacts_list_item">
                <div class="icon-holder">
                    <div class="inner-wrapper">
                        {img src="contacts/scheduller_icon--blue.png"}
                    </div>
                </div>
                <div class="text-holder">
                    <span class="regular-txt">Пн - Пт: 10:00 - 18:00</span>
                    <span class="regular-txt">Сб - Вс: выходной</span>
                </div>
            </li>
        </ul>
        <p>Для предложений сотрудничества, рекламы, а также по любым спорным вопросам Вы можете написать
            руководству магазина. <br>
            Предложения по улучшению нашего сервиса будут приняты с благодарностью.
        </p>
        <div class="contacts_mailing-holder text-center">
            <button type="button" class="btn btn--blue     button_contact">Написать руководству</button>
        </div>
    </div>
</div>

<div class="content into-box">
    <div>
        {*page type='getRenderedErrors' section='home'}
        {page type='getRenderedMessages' section='home'*}
    </div>
    <div class="contact_form">
        {*<form action="" method="POST">
            <input type="hidden" name="mod" value="home">
            <input type="hidden" name="act" value="contact">  *}
            <div class="write"><h1>Написать руководству</h1></div>
            <div class="submit-form">Ваше предложение было успешно отправлено администрации магазина!</div>
            <div class="close-contact"></div>
            <div class="form-body">
            <div class="text-contact" autocomplete="off">Ваше Имя: <span class="star">*</span></div>
                <input type="text" name="contact[name]" class="inpcontact titlex" id="contact-name" value="{if $contact.name}{$contact.name}{/if}" maxlength="64">
                <div class="contact-name-error contact-error">Имя должно быть не меньше 3х символов</div>
            <div class="text-contact" autocomplete="off">Email: <span class="star">*</span></div>
                <input type="text" name="contact[email]" class="inpcontact titlex" id="contact-email" value="{if $contact.email}{$contact.email}{/if}" maxlength="64">
                <div class="contact-email-error contact-error">Некоректный email</div>
            <div class="text-contact" autocomplete="off">Cообщение: <span class="star">*</span></div>
                <textarea name="contact[text]" class="inpcontact titlex"  id="contact-text" style="max-width:445px; resize: none;">{if $contact.text}{$contact.text}{/if}</textarea>
                <div class="contact-text-error contact-error">Сообщение не может быть меньше 2х символов</div>
            <div class="text-contact" autocomplete="off" id="contact-code">
                <img src="/index.php?mod=home&act=captcha" width="85" height="25">
            </div>
            <input type="text" class="inpcontact titlex captcha-text" name="contact[code]" value="" maxlength="2">
            <div class="captcha-form-error contact-error">Неправильный captcha код</div>
            <div class="required"><span class="star">*</span> - поля обязательные для заполнения</div>
            <input type="submit" name="contact[submit]" class="contact_button" id="free_button" value="Отправить" />
            </div>
        {*</form> *}
    </div>
    {*<div class="messagebox"></div>*}
    <div class="contact-background"></div>
</div>
