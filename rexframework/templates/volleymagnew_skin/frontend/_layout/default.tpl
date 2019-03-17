<!DOCTYPE html>
<html class="no-js" xmlns:og="http://ogp.me/ns#">
<head {*if $act eq 'default' && $mod eq 'product'}prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#"{/if*}>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    {*META*}
    <title>{page type='title'}</title>
    <meta name="description" content="{page type='description'}">
    <meta name="keywords" content="{page type='keywords'}">
    <link id="page_favicon" href="/content/images/favicon.ico" rel="icon" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {if $smarty.cookies.unitooltip }
        <script type='text/javascript' src='http://kir.rexframework.ru/assets/unitoolbar.js'></script>
    {/if}

    {if $act eq 'default' && $mod eq 'product'}
        {assign var=image value=$imageList.0}
        <link rel="image_src" href="http://{'clear_domain'|config}{getimg type=defmain name=pImage id=$image.id ext=$image.image}" />
        <meta property="og:image" content="http://volleymag.com.ua{getimg type=defmain name=pImage id=$image.id ext=$image.image}" />
        {*<meta property="og:type" content="article" />*}
    {/if}

    {*CSS*}
    {*{css src="all.css"}*}
    {*{css src="form.css"}*}

    {*{css src="slider.css"}*}
    {*css src="jquery.lightbox-0.5.css"*}
    {css src="jquery.lightbox.css"}
    {css src="jquery.Jcrop.css"}
    {css src="jquery.autocomplete.css"}
    {css src="jquery.carousel.css"}
    {css src="superfish_new.css"}
    {*{css src="jquery.ui.all.css"}*}
    {css src="jquery.rex.tooltip.css"}
    {if $mod eq 'user' and $act eq 'avatar'}
        {css src="rex-ui-style.css"}
    {/if}

    {*JS*}
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

    {*new assets*}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800&amp;amp;subset=cyrillic,cyrillic-ext,latin-ext"
          rel="stylesheet">
    <script src="https://use.fontawesome.com/c8751efcd6.js"></script>

    <!-- css --->
    {css src="app.css"}
    <!-- / --->
    {*new assets end*}

    {js src="main.js"}
    {js src="jquery.autocomplete.js"}
    {*js src="jquery.lightbox-0.5.pack.js"*}
    {js src="jquery.lightbox.js"}
    {js src="jquery.superfish.js"}
    {js src="jquery.rex-0.0.9.js"}
    {js src="jquery.Jcrop.min.js"}
    {js src="jquery.rex.tooltip.js"}
    {if $mod eq 'user' and $act eq 'avatar'}
        {js src="jquery-ui-1.8.17.custom.min.js"}
    {/if}

    {js src="jquery.ui.core.js"}
    {js src="jquery.ui.widget.js"}
    {js src="jquery.ui.mouse.js"}
    {js src="jquery.ui.slider.js"}
    {js src="jquery.bxslider.js"}
    {js src="mainvolley.js"}

    <script type="text/javascript">
        {literal}
        var ajax_paging = {/literal}{if "ajax_paging"|settings == "true"}1{else}0{/if}{literal};
        var task = {/literal}'{$task}'{literal};
        var this_mod = {/literal}'{$mod}'{literal};
        var this_act = {/literal}'{$act}'{literal};
        var feature = {/literal}'{$feature}'{literal};

        if (window.location.hash == '#comments') {
            $(document).ready(function(){
                ShowTab(2);
            });
        }

        {/literal}
    </script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <meta name="google-site-verification" content="Qag2BpE9I2tqev5d6Wt34reQPwZyXN0eGkmEwFyyStw" />
    <!--Google Аналитика-->
    <script type="text/javascript">
        {literal}
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-16062923-33']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
        {/literal}
    </script>



</head>
<body>
<div class="wrapper">
    {*header*}
    <header class="main-header">
        <div class="header_content-wrapper">
            <section class="header_content">
                <div class="header_temp-wrapper">
                    <div class="header_logo-holder">
                        <a href="{url mod=home}" itemprop="url">
                            {img src='logos/logo_header.png' itemprop="logo"}
                            {img src='logos/logo_header-mob-sticky.png' itemprop="logo" class="logo-mobile_sticky"}
                        </a>
                    </div>
                    <div class="header_stream-block">
                        <ul class="header_actions-holder hide-for-large">
                            <li>
                                <button type="button" id="mobile-search-toggle">
                                    {img src='footer/search_icon_grey.png' class='search-icon action-icon'}
                                </button>
                            </li>
                            <li>
                                <a href="{url mod=cart}" class="mobile-basket">
                                    {img src='footer/cart_icon.png' class='header_cart-icon action-icon'}
                                    {include file="cart/cart.header.tpl"}
                                </a>
                            </li>
                        </ul>
                        <section class="header_menu row small-collapse">
                            <nav class="header_nav large-9 small-12 columns">
                                <ul class="no-bullet">
                                    {*<li {if $mod eq "home" && $act eq "default"} class="active"{/if}><a href="{url mod=home act=default}"><span>Главная</span></a></li>*}
                                    <li><a href="{url mod=home act=about}" {if $mod eq "home" and $act eq "about"} class="active"{/if}><span>О нас</span></a></li>
                                    <li><a href="{url mod=news act=archive}" {if $mod eq "news" and $act eq "archive"} class="active"{/if}><span>Новости</span></a></li>
                                    <li><a href="{url mod=article act=archive}" {if $mod eq "article" and $act eq "archive"} class="active"{/if}><span>Статьи</span></a></li>
                                    {*<li><a href="{url mod=staticPage act=default task=about}"><span>О нас</span></a></li>*}
                                    <li><a href="{url mod=staticPage act=default task='delivery'}" {if $mod eq "staticPage" and $task eq "delivery"} class="active"{/if}><span>Оплата и доставка</span></a></li>
                                    {*<li {if $mod eq "staticPage" and $task eq "faq"} class="active"{/if}><a href="{url mod=staticPage act=default task=faq}"><span>FAQ</span></a></li>*}
                                    <li><a href="{url mod=staticPage act=default task='razmernaja-setka'}" {if $mod eq "staticPage" and $task eq "razmernaja-setka"} class="active"{/if}><span>Размеры</span></a></li>
                                    <li><a href="{url mod=home act=contact}" {if $mod eq "home" and $task eq "contact"} class="active"{/if}><span>Контакты</span></a></li>
                                </ul>
                            </nav>
                            <div class="header_login-block column end shrink">
                                <i aria-hidden="true" class="fa fa-user"></i>
                                {if $user->id}
                                    <a href="{url mod=order act=default}" class="header_login header_login--sign">Личный кабинет&nbsp;</a>/&nbsp;
                                    <a href="{url mod=user act=logout}" class="header_login header_login--reg">Выход</a>
                                {else}
                                    <a href="{url mod=user act=login}" class="header_login header_login--sign">Вход&nbsp;</a>/&nbsp;
                                    <a href="{url mod=user act=registration}" class="header_login header_login--reg">Регистрация</a>
                                {/if}
                            </div>
                        </section>
                    </div>
                    <div class="header_tel-holder">
                        <div class="tel-holder_tel-icon show-for-medium">
                            {img src='footer/mobile_icon_blue.png'}
                        </div>
                        <div class="tel-holder_tel-numbers">
                            <a href="tel:+380979485039">097 948 50 39</a>
                            <a href="tel:+380999238189">099 923 81 89</a>
                        </div>
                    </div>
                    <div class="header_btn-holder">
                        <button type="button" class="btn btn--blue button_bell callback-btn">Обратный звонок</button>
                    </div>
                </div>
                <div class="header_sec-row {if $user->id}user-in{/if}">
                    <div class="header_categories">
                        <button type="button" class="btn btn--green categories-btn">
                            {img src='footer/burger_icon.png' class='burger-icon'}
                            Категории
                        </button>
                        {include file="pcatalog/menu.inc.tpl"}
                    </div>
                    <div class="header_search">
                        <form action="{url mod=pCatalog act=search}" id="search" method="post">
                            <input name="q" id="search_inp" onblur="{literal}javascript: if (this.value=='') {this.value='Я ищу...';}" onfocus="javascript: if (this.value=='' || this.value=='Я ищу...') {this.value='';}{/literal}" value="{if $q}{$q}{else}Я ищу...{/if}">
                            <button type="submit" name="search-submit" class="search-btn" value="">
                                {img src='footer/search_icon.png' class='search-icon'}
                            </button>
                        </form>
                    </div>
                    <div class="header_cart show-for-large">
                        <a href="{url mod=cart}"></a>
                        {include file="cart/cart.header.tpl"}
                    </div>
                    <div class="header_mobile-menu hide-for-large">
                        <button type="button" class="btn btn--grey header_nav-btn">
                            <i aria-hidden="true" class="fa fa-angle-down toggle-arrow"></i>
                            Меню
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </header>
    {*end header*}

    {*callback form popoup*}
    <div class="bells_form-wrapper ">
        <div class="bells_form">
            <div class="popup_header">
                <h3>Заказать обратный звонок</h3>
                <div class="bell-text left-bells-form-text">Мы обязательно позвоним и ответим на интересующие Вас вопросы!</div>
                <div class="submit-form">Ваш запрос принят. Наш менеджер свяжется с Вами в ближайшее время!</div>
                <div class="submit-form-error">Извините! Произошла ошибка отправления формы, перезагрузите страницу и попытайтесь еще раз.</div>
            </div>
            <div class="close-bell popup_close-btn"></div>
            <div class="bell-body">
                <div class="text-bell" autocomplete="off" id="bell-name"></div>
                <input placeholder="Ваше имя?" class="inpbell titlex"  type="text" name="bell[name]" value="" maxlength="128">
                <div class="bell-name-error left-bells-form-text">Имя не может быть меньше 3х символов</div>
                <div class="text-bell" autocomplete="off" id="bell-email"></div>
                <input class="inpbell titlex" type="text" name="bell[phone]" value="" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__" >
                <div class="bell-phone-error left-bells-form-text">Неверный формат номера</div>
                {*<div class="required">* - поля обязательные для заполнения</div>*}
                <input type="button" name="bell[submit]" class="btn btn--blue bell_button"  id="free_button" value="Отправить" />
            </div>
        </div>
    </div>
    <div class="bell-background"></div>
    {*end callback form popoup*}

    {*main*}
    <main class="main-content-wrapper">
        {workspace}
    </main>
    {*end main*}

    {strip}
        {include file="product/last.tpl"}
    {/strip}

    {*footer*}
    <footer class="main-footer">
        <section class="footer_main-section">
            <div class="row align-middle">
                <div class="column large-3 medium-6 small-12">
                    <div class="footer_logo">
                        <div class="img-holder">
                            {*{img src='logos/logo_footer.png' class="default-logo"}*}
                            <a href="{url mod=home}" itemprop="url">
                                {img src='logos/logo_header.png' itemprop="logo"}
                            </a>
                            <h1 class="footer_logo_heading">интернет-магазин <br>спортивной&nbsp;экипировки <br>и аксессуаров</h1>
                        </div>
                    </div>
                </div>

                {*TODO have to be checked !!!*}
                <div class="column large-9 medium-6 small-12">
                    <form id="subscribe-form" method="post" >
                        <div class="row footer_mailing-form">
                            <div class="column large-expand small-12">
                                <label for="subscribe-email"></label>
                                <input type="email" name="subscribe_email" required id="subscribe-email" class="footer_input">
                            </div>
                            <div class="column large-expand small-12 footer_btn-holder align-self-stretch col-collapse col-collapse--large">
                                <button type="submit" id="button-submit" class="btn btn--grey">Подписаться</button>
                            </div>
                        </div>
                    </form>
                </div>
                {*TODO have to be checked !!!*}

                <div class="column large-2 small-6 medium-3 large-offset-3 footer_links align-self-top">
                    <nav class="footer_nav">
                        <ul class="ul ul--untitl">
                            <li><a href="{url mod=home act=default}">Главная</a></li>
                            <li><a href="{url mod=home act=about}">О нас</a></li>
                            <li><a href="{url mod=news act=archive}">Новости</a></li>
                            <li><a href="{url mod=article act=archive}">Статьи</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="column large-3 small-6 medium-4 footer_links align-self-top">
                    <nav class="footer_nav">
                        <ul class="ul ul--untitl">
                            <li><a href="{url mod=staticPage act=default task='delivery'}">Оплата</a></li>
                            <li><a href="{url mod=staticPage act=default task='razmernaja-setka'}">Размеры</a></li>
                            <li><a href="{url mod=home act=contact}">Контакты</a></li>
                            <li><a href="{url mod=staticPage act=default task='garantija'}">Гарантия</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="column large-4 medium-5 footer_links footer_contacts align-self-top col-collapse col-collapse--large">
                    <h3>Контакты</h3>
                    <div class="footer_contacts_tel-holder">
                        {img src='footer/mobile_icon.png'}
                        <a href="tel:0979485039">097 948 50 39</a><a href="tel:0999238189">099 923 81 89</a>
                    </div>
                    <button type="button" class="btn btn--blue button_bell callback-btn">Обратный звонок</button>
                    <div class="flex-holder">
                        <a href="mailto:zakaz@volleymag.com.ua" class="footer_email">
                            {img src='footer/email_icon.png'}
                            zakaz@volleymag.com.ua
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="footer_social-section">
            <div class="row align-middle">
                <div class="column xlarge-8 large-7 medium-6 small-12 copyright-block">
                    <h4>volleymag 2012-{$smarty.now|date_format:"%Y"}. All rigth recerved.</h4>
                </div>
                <div class="column medium-6 small-12 soc-block">
                    <h5>Наши сообщества в:</h5>
                    <ul class="ul ul--untitl">
                        <li>
                            <a href="https://facebook.com/volleymag" target="_blank"> {img src='footer/facebook_icon.png'} </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/volley_mag" target="_blank"> {img src='footer/instagram_icon.png'} </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
    </footer>
    {*end footer*}

    {*button to top*}
    <div class="btn-to-top" tabindex="0">
        {img src='slick/arrow-next.png' class=""}
    </div>
    {*end button to top*}
</div>
{include file="_block/input.phone.mask.tpl"}
{literal}
    <script type="text/javascript">
        $(document).ready(function(){
            initPhoneMask();
            if(navigator.userAgent.match(/Trident\/7\./)) {
                $('body').on("mousewheel", function () {
                    event.preventDefault();

                    var wheelDelta = event.wheelDelta;
                    var currentScrollPosition = window.pageYOffset;
                    window.scrollTo(0, currentScrollPosition - wheelDelta);
                });
            }
        });
    </script>
{/literal}

{*new js*}
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>var $j = jQuery.noConflict(true);</script>
{js src="slick.js"}
{js src="custom.js"}
{**}

</body>
</html>