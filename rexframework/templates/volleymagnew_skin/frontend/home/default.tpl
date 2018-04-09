{if $mod eq 'home' && $act eq 'default'}
    <section class="main-slider-section">
        {if $sliderHome}
            <div class="my-slider my-slider_main my-slider--no-paddings">
                {foreach from=$sliderHome key=key item=slide}
                    <div class="my-sliders_slide">
                        <a href="{$slide.url}" class="block-link slide_image-holder">
                            <img src="{getimg type=main name=slider id=$slide.id ext=$slide.banner}" class="slide-img">
                            <div class="main-slider_text-holder">
                                <p class="main-slider_text">{$slide.text}</p>
                            </div>
                        </a>
                    </div>
                {/foreach}
            </div>
        {/if}
    </section>
{/if}
<section class="products-section">
    <div class="row align-center">
        <div class="column small-12">
            <h1 class="section-title section-title--blue">Новинки</h1>
            <div class="my-slider my-slider_product">
                {include file="pcatalog/product.block.inc.tpl" productList=$new_productList imageList=$new_imageList categoryList=$new_categoryList prodColor=$new_color}
            </div>
            <div class="link-btn-holder text-center">
                <a href="{url mod=product act=archive feature=new}" class="common_link-btn btn btn--blue">Смотреть все</a>
            </div>
        </div>
    </div>
</section>
<section class="products-section">
    <div class="row align-center">
        <div class="column small-12">
            <h1 class="section-title section-title--blue">Лидеры продаж</h1>
            <div class="my-slider my-slider_product">
                {include file="pcatalog/product.block.inc.tpl" productList=$bestseller_productList imageList=$bestseller_imageList categoryList=$bestseller_categoryList prodColor=$bestseller_color}
            </div>
            <div class="link-btn-holder text-center">
                <a href="{url mod=product act=archive feature=bestseller}" class="common_link-btn btn btn--blue">Смотреть все</a>
            </div>
        </div>
    </div>
</section>
<section class="features_section">
    <div class="row align-center">
        <div class="column small-10 collapse">
            <ul class="features_list ul ul--untitl ul--inline">
                <li>
                    <div class="img-holder">
                        {img src='main-page/ukraine_icon.png' class='feature-icon'}
                    </div>
                    <h3>Доставка по всей Украине</h3>
                </li>
                <li>
                    <div class="img-holder">
                        {img src='main-page/t-shirt_icon.png' class='feature-icon'}
                    </div>
                    <h3>Актуальные размеры</h3>
                </li>
                <li>
                    <div class="img-holder">
                        {img src='main-page/guaranty_icon.png' class='feature-icon'}
                    </div>
                    <h3>Гарантия подлинности</h3>
                </li>
                <li>
                    <div class="img-holder">
                        {img src='main-page/package_icon.png' class='feature-icon'}
                    </div>
                    <h3>Гарантия возврата</h3>
                </li>
                <li>
                    <div class="img-holder">
                        {img src='main-page/percent-grey_icon.png' class='feature-icon'}
                    </div>
                    <h3> Скидки для постоянных покупателей</h3>
                </li>
            </ul>
            {*<p>Вместе с нами купить спортивную одежду и обувь гораздо проще! Мы постоянно работаем над тем,
                чтобы покупки становились ещё выгоднее. Купить со скидкой в 5% качественную спортивную одежду и
                экипировку можно начиная уже со второй покупки!<br>
                Оформлять заказы станет ещё удобнее, если вы зарегистрируетесь в «Личном кабинете». Тогда вам
                будет доступен просмотр истории заказов, просмотр полученных скидок и не только.<br>
                Одна из задач нашего магазина – поддержка детского, студенческого и аматорского спорта. При
                заказе товаров для команд вы обязательно получите скидку!</p>
            <p>Виртуальные витрины Волеймаг постоянно пополняются новыми коллекциями спортивной экипировки!
                Делайте покупки оптом в и розницу на наших бесконечных полках!</p>*}
            <div class="home-content">{$content}</div>
        </div>
    </div>
</section>
{*TODO have to atach to brands from db*}
<section class="partners_section">
    <div class="row align-center">
        <div class="column small-12"><h1 class="section-title">Наши бренды</h1>
            <div class="my-slider my-slider_partners">
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="#">
                            {img src='main-page/mizuno_logo.png' class='slide-img'}
                        </a>
                    </div>
                </div>
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="#">
                            {img src='main-page/asics_logo.png' class='slide-img'}
                        </a>
                    </div>
                </div>
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="#">
                            {img src='main-page/mikasa_logo.png' class='slide-img'}
                        </a>
                    </div>
                </div>
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="#">
                            {img src='main-page/errea_logo.png' class='slide-img'}
                        </a>
                    </div>
                </div>
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="#">
                            {img src='main-page/mizuno_logo.png' class='slide-img'}
                        </a>
                    </div>
                </div>
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="#">
                            {img src='main-page/asics_logo.png' class='slide-img'}
                        </a>
                    </div>
                </div>
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="#">
                            {img src='main-page/mikasa_logo.png' class='slide-img'}
                        </a>
                    </div>
                </div>
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="#">
                            {img src='main-page/errea_logo.png' class='slide-img'}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{**}
<section class="media_section">
    <div class="row align-center">
        <div class="column small-12">
            <div class="media_tabs-holder text-center">
                <button type="button" id="news-label" class="media_tab-btn">Новости</button>
                <button type="button" id="publications-label" class="media_tab-btn">Статьи</button>
            </div>
            {*news*}
            <div id="news" class="media_slider-holder">
                <div class="my-slider my-slider_news">
                    {include file="news/list.tpl"}
                </div>
                <div class="link-btn-holder text-center">
                    <a href="{url mod=news act=archive}" class="common_link-btn btn btn--blue">Все новости</a>
                </div>
            </div>
            {*end news*}
            {*TODO fix articles view*}
            {*articles*}
            <div id="publications" class="media_slider-holder">
                <div class="my-slider my-slider_publication">
                    {*<div class="my-sliders_slide news_slide">
                        <div class="img-holder text-center">
                            {img src='main-page/news-slide-1.jpg' class='slide-img'}
                            <a href="#" class="overlay-link">
                                {img src='main-page/eye_icon.png' class='overlay-link_icon'}
                            </a>
                        </div>
                        <p class="news_slide_date">05.07.2016 г.</p>
                        <a href="#" class="news_slide_link">Как выбрать кроссовки?</a>
                    </div>*}
                    {include file="article/list.tpl"}
                </div>
                <div class="link-btn-holder text-center">
                    <a href="{url mod=article act=archive}" class="common_link-btn btn btn--blue">Все публикации</a>
                </div>
            </div>
            {*end articles*}
        </div>
    </div>
</section>