{if $user}

<!--<div class="clear"></div> -->


<form action="#comments" method="POST" class="commentform">
<table cellpadding="0" cellspacing="0" border="0" class="user-comments">
    <tr>
        <td class="user-comments-in user-comments-intitle" valign="top">
            <div>
                {page type='getRenderedErrors' section='comment'}
                {page type='getRenderedMessages' section='comment'}
            </div>
            {'comment.add'|lang}
        </td>
    </tr>
    <tr>
        <td class="user-comments-in" valign="top">    
            <textarea onkeyup="javascript:backspacerUPText(this,event);" id="comment_text" class="user-comments-textarea" name="comment[content]">{if $commententity.content}{$commententity.content}{/if}</textarea><br/>
        </td>
    </tr>
    <tr>
        <td class="user-comments-in user-comments-inbutton" valign="top" align="left">
            <input type="submit" class="user-comment-add-submit" value="Комментировать" name="comment[commentsubmit]">
        </td>
    </tr>
</table>
</form>
{else}
<br/>
<a href="{url mod=user act=login}" class="a-button bgcolor roundp" style="float:none; display:inline;">{'comment.enter_link'|lang}</a> {'comment.or'|lang} <a href="{url mod=user act=registration}" class="a-button bgcolor roundp" style="float:none; display:inline;">{'comment.registration_link'|lang}</a> {'comment.leave_reviews'|lang}

{/if}