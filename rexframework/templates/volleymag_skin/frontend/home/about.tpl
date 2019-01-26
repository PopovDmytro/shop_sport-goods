    <div class="product-def">
        <div class="breadcrumbs">
                <li><a href="{url mod=home}">Главная</a></li>   
                <li>О Нас</li> 
            
            {*<p class="navigation-p"><a href="{url mod=home}">Главная</a></p> 
            <p class="navigation-p">{$staticpage->name}</p>*}
        </div>
        <div class="into-box page_text" >
            {$staticpage}
        </div>
        
            {if $comments}
            <div class="block-comments">
                 <ul class="comments-list">
                 {foreach from=$comments key=key item=item}
                    <li>
                        <div class="top-block"><a class="user" href="{url mod=user act=default id=$item.user_id}">{$item.name}</a><span class="date">{$item.date_create|date_format:"%d.%m.%Y"}</span></div>
                        <p>{$item.content}</p>
                    </li>
                 {/foreach}
                </ul>
            {/if}
            {if $user}
                <form action="#about" method="POST" class="commentform">
                <table cellpadding="0" cellspacing="0" border="0" class="user-comments">
                    <tr>
                        <td class="user-comments-in user-comments-intitle" valign="top">
                            <div>
                                {page type='getRenderedErrors' section='home'}
                                {page type='getRenderedMessages' section='home'}
                            </div>
                            {'about.add'|lang}
                        </td>
                    </tr>
                    <tr>
                        <td class="user-comments-in" valign="top">    
                            <textarea onkeyup="javascript:backspacerUPText(this,event);"  id="comment_text" class="user-comments-textarea" name="about[content]">{if $commententity.content}Отзыв отправлен на модерацию...{*$commententity.content*}{/if}</textarea><br/>
                        </td>
                    </tr>
                    <tr>
                        <td class="user-comments-in user-comments-inbutton" valign="top" align="left">
                            <input type="submit" class="user-comment-add-submit enter" value="Отправить отзыв" name="about[commentsubmit]">
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
            </div>
            {/if}
        <div class="product-def-bottom-bg"></div>
    </div>
