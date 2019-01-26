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

    {*<div class="into-box page_text" >
        {$staticpage}
    </div>*}

    <div class="row small-up-12">
        <section class="about_main-content column column-block">
            <h1 class="section-title section-title--blue">О нас</h1>
            <p>Компания volleymag (волеймаг) — онлайн магазин спортивной экипировки и аксессуаров, но прежде всего
                это коллектив профессионалов, по-настоящему любящих спорт. Именно поэтому Вы никогда не увидите в
                нашем магазине подделок или заведомо некачественных товаров. Только оригинальная профессиональная
                экипировка именитых брендов, таких как Mizuno, Asics, Errea, Mikasa, Mueller, Select, Umbro от
                официальных дилеров в Украине дают нам уверенность в том, что Вы останетесь довольны покупками и
                ничто не помешает Вам заниматься любимым спортом. <br> Каждый день мы дарим Вам новые эмоции и новые
                победы!</p>
            <div class="additional-conditions">
                <div class="additional-conditions_item">
                    <div class="conditions-block conditions_title">
                        {img src='main-page/percent_icon--blue.png' class='item_icon'}
                        <h4 class="article-title article-title--blue">Дополнительные скидки для постоянных покупателей</h4>
                    </div>
                    <div class="conditions-block conditions_content"><p>Мы очень ценим доверие наших клиентов и
                            предоставляем скидку в размере 5% на любой товар при оформлении второго и последующего
                            заказа! <br> Для удобства оформления заказов Вы можете один раз зарегистрироваться на нашем
                            сайте и в дальнейшем процесс оформления заказа сведется к одному клику мышкой!</p>
                        <p class="text-italic">Внимание! Если Вы уже оформляли заказ в нашем магазине, но не знаете
                            как зайти в свою учетную запись - свяжитесь с менеджером магазина и Вас включат в
                            систему скидок, как постоянного клиента!</p>
                    </div>
                </div>
                <div class="additional-conditions_item">
                    <div class="conditions-block conditions_title">
                        {img src='main-page/support_icon.png' class='item_icon'}
                        <h4 class="article-title article-title--blue">Специальные условия для команд</h4>
                    </div>
                    <div class="conditions-block conditions_content"><p>Поддержка детского, аматорского и
                            студенческого спорта в Украине является одной из основных задач нашей работы, поэтому при
                            заказе товаров на команду предоставляется дополнительная индивидуальная скидка на заказ.
                            Размер скидки уточняйте у менеджера заказа.</p>
                    </div>
                </div>
            </div>
            <p>Если у Вас возникли какие-либо вопросы, то Вы всегда сможете связаться с нами любым удобным способом.
                Предложения по улучшению нашего магазина будут приняты с благодарностью. Подробности в разделе
                &nbsp;
                <a href="{url mod=home act=contact}">Контакты.</a>
            </p>
            <p>Мы неустанно прикладываем все усилия, чтобы создать самый лучший спортивный интернет-магазин,
                позволяющий сделать процесс покупок максимально приятным для Вас!</p>
            <div class="about_callout text-center">
                <h3 class="article-title article-title--blue medium-size">volleymag - Все для твоих побед!</h3>
                <p class="text-italic">С уважением, коллектив магазина.</p>
            </div>
        </section>
    </div>
    <section class="about_partners">
        <h3 class="section-title">Особая благодарность нашим партнерам:</h3>
        <div class="partners_section column column-block">
            <div class="row align-center">
                <div class="column small-12">
                    <div class="my-slider my-slider_partners">
                        <div class="my-sliders_slide partners_slide">
                            <div class="img-holder">
                                <a href="javascript:void(0);">
                                    <img src="/content/file/-/mizuno_logo.png" class="slide-img">
                                </a>
                            </div>
                        </div>
                        <div class="my-sliders_slide partners_slide">
                            <div class="img-holder">
                                <a href="javascript:void(0);">
                                    <img src="/content/file/-/asics_logo.png" class="slide-img">
                                </a>
                            </div>
                        </div>
                        <div class="my-sliders_slide partners_slide">
                            <div class="img-holder">
                                <a href="javascript:void(0);">
                                    <img src="/content/file/-/mikasa_logo.png" class="slide-img">
                                </a>
                            </div>
                        </div>
                        <div class="my-sliders_slide partners_slide">
                            <div class="img-holder">
                                <a href="javascript:void(0);">
                                    <img src="/content/file/-/errea_logo.png" class="slide-img">
                                </a>
                            </div>
                        </div>
                        <div class="my-sliders_slide partners_slide">
                            <div class="img-holder">
                                <a href="javascript:void(0);">
                                    <img src="/content/file/-/fvho_new_mid.png" class="slide-img">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about_comments-section row">
        {if $comments}
            <div class="comments-wrapper column small-12">
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
            </div>
        {/if}
        {if $user}
            <div class="answer-wrapper column small-12">
                <form action="#about" method="POST" class="commentform">
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
