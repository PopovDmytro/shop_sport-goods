{if $user}
    {if $commententity}
        <ul class="comments-list">
            <li>
                <div class="top-block"><a class="user" href="{url mod=user act=default id=$commententity.user_id}">{if $commententity.name}{$commententity.name}{else}{$commententity.name_single}{/if}</a>{if $commententity.status eq 1}<span class="your_comment">Сообщение на модерации.</span>{/if}<span class="date">{$commententity.date_create|date_format:"%d.%m.%Y"}</span></div>
                <p>{$commententity.content}</p>
           </li>
        </ul>
    {/if}
    <form action="#comments" method="POST" class="commentform">
        <input type="hidden" name=comment[url] value="http://volleymag.com.ua/{$smarty.server.REQUEST_URI}">
    <table cellpadding="0" cellspacing="0" border="0" class="user-comments">
        <tr>
            <td class="user-comments-in user-comments-intitle" valign="top">
            <div class="massegebox">
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
                {if $user->is_registered eq 3}
                    <span class="user-comment-add-submit enter comment-submit-ajax">Комментировать</span>
                {else}
                    <input type="submit" class="user-comment-add-submit enter" value="Комментировать" name="comment[commentsubmit]">
                {/if}
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
{*<script src="http://vk.com/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
<script type="text/javascript">
var productId = '{$task}';
{literal}
    $('.comment-submit-ajax:visible').on('click', function(){
        var comment = {};
        comment.url = $('input[name="comment[url]"]').val();
        comment.content = $('.user-comments-textarea:visible').val();
        comment.product_id = productId;
        if (comment.content.length > 2) {
            $.rex('comment', 'ajaxComment', {comment:comment}, function(data){
                if (data) {
                    console.log(123);
                    VK.init({
                    apiId: 4405142
                    });
                    VK.api("wall.post", { "message": comment.content, "attachments": comment.url}, onCompletePostWall, onError);
                }
            });  
        } else {
            alert('Комментарий не должен быть и содержать 3 и более символов');
        }
    });
    function onCompletePostWall(){
        var url = $('input[name="comment[url]"]').val(); 
        window.location.href = url;
    }
    function onError(){
        console.log('error');
    }
{/literal}
</script>*}
<script type="text/javascript">
{rexscript_start}
{literal}
    /*template.find('.commentform input.user-comment-add-submit.enter').click(function(event){
        if (!template.find('#comment_text').text() || template.find('#comment_text').text().length() < 3){
            template.find('.massegebox').html('<b style="color:#FF0000">Комментарий не может быть пустым</b>');
            event.stopPropagation();
            return false; 
        }
        console.log(template.find('#comment_text').val()); 
        
    }); */
{/literal}
{rexscript_stop}
</script>
