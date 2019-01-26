<div>
    <ul class="breadcrumbs">
        <li>
            <a href="/">Главная</a>
        </li>
        <li>Контакты</li>
    </ul>
     
    <div class="content into-box">
        {if $staticpage->content}
             <div class="contact_page_content">{$staticpage->content}</div>
        {/if}
        {if $staticpage->youtube}
            <p style="text-align: center">
                <iframe width="100%" height="400" src="{$staticpage->youtube}" frameborder="0"
                        allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </p>
        {/if}
        <div class="button_contact">
            Написать руководству
        </div>
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
        {*<div class="contact_form_block">
            
            {page type='getRenderedErrors' section='home'}
            {page type='getRenderedMessages' section='home'}
            <h3>Написать руководству</h3>
           <form action="" method="POST">
                <input type="hidden" name="mod" value="home">
                <input type="hidden" name="act" value="contact">
                <table cellpadding="4" cellspacing="0" border="0" class="formcontact">
                    <tr>
                        <td valign="middle" class="cf_title">Имя:</td>
                        <td valign="middle" class="cf_inpt"><input type="text" name="contact[name]" class="titlex" value="{if $contact.name}{$contact.name}{/if}" maxlength="64"></td>
                    </tr>
                    <tr>
                        <td valign="middle" class="cf_title">Email:</td>
                        <td valign="middle" class="cf_inpt"><input type="text" class="titlex" name="contact[email]" value="{if $contact.email}{$contact.email}{/if}" maxlength="64"></td>
                    </tr>
                    <tr>
                        <td valign="top" class="cf_title">Сообщение:</td>
                        <td valign="top" class="cf_inpt"><textarea name="contact[text]" class="titlex" style="max-width:445px">{if $contact.text}{$contact.text}{/if}</textarea></td>
                    </tr>
                    <tr>
                        <td valign="middle"><img src="/index.php?mod=home&act=captcha" width="85" height="25"></td>
                        <td valign="middle" class="cf_inpt_cpch"><input type="text" class="titlex" name="contact[code]" value="" maxlength="2"></td>
                    </tr>
                    <tr>
                        <td valign="top" colspan="2" class="cf_inpt_bttn"><input type="submit" name="contact[submit]" id="free_button" value="Отправить сообщение" /></td>
                    </tr>
                </table>
            </form>
        </div>*}
    </div>
</div>
