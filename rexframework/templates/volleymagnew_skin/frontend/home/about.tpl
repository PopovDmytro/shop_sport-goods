<div class="about-static-page">
    <div class="breadcrumbs-block row">
        <div class="columns small-12">
            <ul class="breadcrumbs_list no-bullet">
                <li class="breadcrumbs_item">
                    <a href="{url mod=home}" class="breadcrumbs_link">
                        <i aria-hidden="true" class="fa fa-home"></i>Главная
                    </a>
                </li>
                <li class="breadcrumbs_item active">
                    <a href="javascript:void(0)" class="breadcrumbs_link">О Нас</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row small-up-12">
        <section class="about_main-content column column-block">
            {$staticpage}
        </section>
    </div>

    <section class="about_comments-section row">
        <div class="comments-wrapper column small-12">
            {if $comments}
                {foreach from=$comments key=key item=item}
                <article class="comment">
                    <div class="comment_header">
                        <div class="comment_date"><span>{$item.date_create|date_format:"%d.%m.%Y"} г.</span></div>
                        <div class="comment_user-name">
                            <a href="{url mod=user act=default id=$item.user_id}" class="comment_user-link">{$item.name}</a>
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
                        {page type='getRenderedErrors' section='home'}
                        {page type='getRenderedMessages' section='home'}
                    </div>
                    <h3>{'about.add'|lang}</h3>
                    <label for="comment_text"></label>
                    <textarea rows="4" onkeyup="javascript:backspacerUPText(this,event);"  id="comment_text" class="user-comments-textarea" name="about[content]">{if $commententity.content}Отзыв отправлен на модерацию...{*$commententity.content*}{/if}</textarea>
                    <br/>
                    <input type="submit" value="Отправить отзыв" name="about[commentsubmit]" class="btn btn--blue">
                </form>
            </div>
        {else}
            <div class="answer-wrapper column small-12">
                <h3>Для того чтобы оставить отзыв нужно</h3>
                <a href="{url mod=user act=login}" class="common_link-btn btn btn--blue">Войти</a>
                <span>или</span>
                <a href="{url mod=user act=registration}" class="common_link-btn btn btn--blue">Зарегистрироваться</a>
            </div>
        {/if}
    </section>
</div>
