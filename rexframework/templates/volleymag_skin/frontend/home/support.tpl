{$staticpage->content}

{page type='getRenderedErrors' section='home'}
{page type='getRenderedMessages' section='home'}
<div>
    <form action="" method="POST">
        <input type="hidden" name="mod" value="home">
        <input type="hidden" name="act" value="support">
        <table cellpadding="4" cellspacing="0" border="0" class="formcontact">
            <tr>
                <td valign="middle" colspan="2" align="center">
                <textarea name="contact[content]" style="width: 600px; height: 200px">{if $contact.content}{$contact.content}{/if}</textarea>
            </tr>
            <tr>
                <td valign="top" colspan="2" class="cf_inpt_bttn" align="center"><input type="submit" name="contact[submit]" value="Отправить сообщение" /></td>
            </tr>
        </table>
    </form>
</div>