<div class="block-comments">
        <ul class="comments-list">
        {if $comments}
            {foreach from=$comments key=key item=item}
                <li>
                    <div class="top-block"><a class="user" href="{url mod=user act=default id=$item.user_id}">{$item.login}</a>{if $item.status eq 1}<span class="your_comment">Сообщение на модерации.</span>{/if}<span class="date">{$item.date_create|date_format:"%d.%m.%Y"}</span></div>
                    <p>{$item.content}</p>
                </li>
            {/foreach}
        {/if}
        {foreach from=$userCom key=key item=item}
             <li>
                <div class="top-block"><a class="user" href="{url mod=user act=default id=$item.user_id}">{$item.login}</a>{if $item.status eq 0}<span class="your_comment">Сообщение на модерации.</span>{/if}<span class="date">{$item.date_create|date_format:"%d.%m.%Y"}</span></div>
                <p>{$item.content}</p>
             </li>
        {/foreach}
        </ul>
    
    {*if $commententity}
        <ul class="comments-list">
            <li>
                <div class="top-block"><a class="user" href="{url mod=user act=default id=$commententity.user_id}">{$user.login}</a>{if $commententity.status eq 0}<span class="your_comment">Сообщение на модерации.</span>{/if}<span class="date">{$commententity.date_create|date_format:"%d.%m.%Y"}</span></div>
                <p>{$commententity.content}</p>
           </li>
        </ul>
    {/if*}
    {if $user}
        <form action="" method="POST" class="commentform">
        <table cellpadding="0" cellspacing="0" border="0" class="user-comments">
            <tr>
                <td class="user-comments-in user-comments-intitle" valign="top">
                    <div>
                        {page type='getRenderedErrors' section='article'}
                        {page type='getRenderedMessages' section='article'}
                    </div>
                    {'about.add'|lang}
                </td>
            </tr>
            <tr>
                <td class="user-comments-in" valign="top">    
                    <textarea onkeyup="javascript:backspacerUPText(this,event);"  id="comment_text" class="user-comments-textarea" name="addcom[content]"></textarea><br/>
                </td>
            </tr>
            <tr>
                <td class="user-comments-in user-comments-inbutton" valign="top" align="left">
                    <input type="submit" class="user-comment-add-submit enter" value="Отправить отзыв" name="addcom[commentsubmit]">
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
</div>