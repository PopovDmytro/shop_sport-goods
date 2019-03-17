<section class="block-comments about_comments-section row">
    <div class="comments-wrapper column small-12">
        {if $comments}
            {foreach from=$comments key=key item=item}
                <article class="comment">
                    <div class="comment_header">
                        <div class="comment_date date">{$item.date_create|date_format:"%d.%m.%Y"}</div>
                        <div class="comment_user-name">
                            <a class="user comment_user-link" href="{url mod=user act=default id=$item.user_id}">{$item.name}</a>
                            {if $item.status eq 0}&nbsp;&nbsp;&nbsp;<span class="your_comment">Сообщение на модерации.</span>{/if}
                        </div>
                    </div>
                    <div class="comment_body">
                        <p>{$item.content}</p>
                    </div>
                </article>
            {/foreach}
        {/if}
        {foreach from=$userCom key=key item=item}
            <article class="comment">
                <div class="comment_header">
                    <div class="comment_date date">{$item.date_create|date_format:"%d.%m.%Y"}</div>
                    <div class="comment_user-name">
                        <a class="user comment_user-link" href="{url mod=user act=default id=$item.user_id}">{$item.name}</a>
                        {if $item.status eq 0}&nbsp;&nbsp;&nbsp;<span class="your_comment">Сообщение на модерации.</span>{/if}
                    </div>
                </div>
                <div class="comment_body">
                    <p>{$item.content}</p>
                </div>
            </article>
        {/foreach}
    </div>

    {if $user}
        <div class="answer-wrapper column small-12">
            <form action="" method="POST" class="commentform">
                <div>
                    {page type='getRenderedErrors' section='article'}
                    {page type='getRenderedMessages' section='article'}
                </div>
                <h3>{'about.add'|lang}</h3>
                <label for="comment_text"></label>
                <textarea row="4" onkeyup="javascript:backspacerUPText(this,event);"  id="comment_text" class="user-comments-textarea" name="addcom[content]"></textarea>
                <br/>
                <input type="submit" class="user-comment-add-submit enter btn btn--blue" value="Отправить отзыв" name="addcom[commentsubmit]">
            </form>
        </div>
    {else}
        <div class="authorization-block row">
            <div class="answer-wrapper column small-12">
                <h3>Для того чтобы оставить отзыв нужно</h3>
                <a href="{url mod=user act=login}" class="common_link-btn btn btn--blue">Войти</a>
                <span>или</span>
                <a href="{url mod=user act=registration}" class="common_link-btn btn btn--blue">Зарегистрироваться</a>
            </div>
        </div>
    {/if}
</section>