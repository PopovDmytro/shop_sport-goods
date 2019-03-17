{if $user}
<section class="product-card_feedback">
    <div class="row align-center">
        <div class="columns small-12">
            <h1 class="section-title section-title--blue">Отзывы</h1>
            {if $commententity}
                <div class="comments-list comments-wrapper column small-12">
                    <article class="comment">
                        <div class="comment_header">
                            <div class="comment_date">
                                <span class="date">{$commententity.date_create|date_format:"%d.%m.%Y"}</span>
                            </div>
                            <div class="comment_user-name">
                                <a class="user comment_user-link" href="{url mod=user act=default id=$commententity.user_id}">
                                    {if $commententity.name}{$commententity.name}{else}{$commententity.name_single}{/if}
                                    {*{$user.name}*}
                                </a>
                                {if $commententity.status eq 1}&nbsp;&nbsp;&nbsp;<span class="your_comment">Сообщение на модерации.</span>{/if}
                            </div>
                        </div>
                        <div class="comment_body">
                            <p>{$commententity.content}</p>
                        </div>
                    </article>
                </div>
            {/if}
            <form action="#comments" method="POST" class="commentform">
                <input type="hidden" name=comment[url] value="http://volleymag.com.ua/{$smarty.server.REQUEST_URI}">
                <div class="user-comments">
                    <div class="feedbacks-textarea">
                        <div class="user-comments-in user-comments-intitle" >
                            <div class="massegebox">
                                {page type='getRenderedErrors' section='comment'}
                                {page type='getRenderedMessages' section='comment'}
                            </div>
                            <h3>{'comment.add'|lang}</h3>
                        </div>
                        <div class="user-comments-in" valign="top">
                            <textarea rows="10" onkeyup="javascript:backspacerUPText(this,event);" id="comment_text" class="user-comments-textarea" name="comment[content]"></textarea><br/>
                        </div>
                        <div class="user-comments-in user-comments-inbutton" valign="top" align="left">
                            {if $user->is_registered eq 3}
                                <span class="user-comment-add-submit enter comment-submit-ajax">Комментировать</span>
                            {else}
                                <input type="submit" class="btn btn--blue user-comment-add-submit enter" value="Комментировать" name="comment[commentsubmit]" />{*Комментировать</input>*}
                            {/if}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
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
