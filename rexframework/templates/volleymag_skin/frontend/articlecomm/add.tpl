{if $user}
    <form action="#comments" method="POST" class="commentform">
    <table cellpadding="0" cellspacing="0" border="0" class="user-comments">
        {if $commententity}
        <tr>
            <td>
            <ul class="comments-list">
                <li>
                    <div class="top-block"><a class="user" href="{url mod=user act=default id=$commententity.user_id}">{$user.login}</a>{if $commententity.status eq 1}<span class="your_comment">Сообщение на модерации.</span>{/if}<span class="date">{$commententity.date_create|date_format:"%d.%m.%Y"}</span></div>
                    <p>{$commententity.content}</p>
               </li>
            </ul>
            </td>
        </tr> 
        {/if}
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
                <textarea onkeyup="javascript:backspacerUPText(this,event);" id="comment_text" class="user-comments-textarea" name="comment[content]"></textarea><br/>
            </td>
        </tr>
        <tr>
            <td class="user-comments-in user-comments-inbutton" valign="top" align="left">
                <input type="submit" class="user-comment-add-submit enter" value="Комментировать" name="comment[commentsubmit]">
            </td>
        </tr>
    </table>
    </form>
{else}
    <div class="authorization-block">
        <span>Для того чтобы оставить отзыв нужно</span>
        <a href="{url mod=user act=login}" class="enter"><span class="wrapper"><span>Войти</span></span></a>
        <span>или</span>
        <a href="{url mod=user act=registration}" class="enter"><span class="wrapper"><span>Зарегистрироваться</span></span></a>
    </div>
{/if}