<!DOCTYPE html>
<html class="no-js" xmlns:og="http://ogp.me/ns#">
<head {*if $act eq 'default' && $mod eq 'product'}prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#"{/if*}>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    {*META*}
    <title>{page type='title'}</title>
    <meta name="description" content="{page type='description'}">
    <meta name="keywords" content="{page type='keywords'}">
    <link id="page_favicon" href="/content/images/favicon.ico" rel="icon" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
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
    {css src="all.css"}
    {css src="form.css"}

    {css src="slider.css"}
    {*css src="jquery.lightbox-0.5.css"*}
    {css src="jquery.lightbox.css"}
    {css src="jquery.Jcrop.css"}
    {css src="jquery.autocomplete.css"}
    {css src="jquery.carousel.css"}
    {css src="superfish_new.css"}
    {css src="jquery.ui.all.css"}
    {css src="jquery.rex.tooltip.css"}
    {if $mod eq 'user' and $act eq 'avatar'}
    {css src="rex-ui-style.css"}
    {/if}

    {*JS*}
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

    {js src="main.js"}
    {*js src="jquery-1.7.1.min.js"*}
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

    <!-- Yandex.Metrika counter --><script type="text/javascript">{literal} (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter34147670 = new Ya.Metrika({ id:34147670, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");{/literal}</script><noscript><div><img src="https://mc.yandex.ru/watch/34147670" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->

</head>
<body>
</script>
    <div id="wrapper">
        <div class="wrapper-box">
            <div class="wrapper-container">
                <div class="wrapper-holder">
                    {strip}
                    <div id="header">
                        <div class="header-top">
                            <div itemtype="http://schema.org/Organization" itemscope="" class="logo">
                                 <a href="{url mod=home}" itemprop="url">
                                      {img src='logo.png' itemprop="logo"}
                                 </a>
                            </div>
                            <div class="contact">
                                <div class="tel">
                                    <span class="first-string">099 923 81 89</span>
                                    <span class="second-string">097 948 50 39</span>
                                </div>
                                <div class="button_bell">
                                    Обратный звонок
                                </div>
                            </div>
                            <div class="header-box">
                                {*<div class="button_bell">
                                    Обратный звонок
                                </div> *}
                                <div class="login-block">
                                    {if $user->id}
                                        <span class="login"><a href="{url mod=order act=default}">Личный кабинет</a>  </span>
                                        <a href="{url mod=user act=logout}">Выход</a>
                                    {else}
                                        <span class="login"><a href="{url mod=user act=login}">Вход</a></span>
                                        <span class="registration"><a href="{url mod=user act=registration}">Регистрация</a></span>
                                    {/if}
                                </div>
                                <a href="{url mod=cart}" class="basket-href">
                                <div class="basket">
                                    {include file="cart/cart.header.tpl"}
                                </div>
                                </a>
                            </div>
                        </div>
                        <div class="nav-block">
                            <div class="nav-holder">
                                <div class="nav-wrapper">
                                    <ul id="nav">
                                        <li {if $mod eq "home" && $act eq "default"} class="active"{/if}><a href="{url mod=home act=default}"><span>Главная</span></a></li>
                                        <li {if $mod eq "home" and $act eq "about"} class="active"{/if}><a href="{url mod=home act=about}"><span>О нас</span></a></li>
                                        <li {if $mod eq "news" and $act eq "archive"} class="active"{/if}><a href="{url mod=news act=archive}"><span>Новости и акции</span></a></li>
                                        <li {if $mod eq "article" and $act eq "archive"} class="active"{/if}><a href="{url mod=article act=archive}"><span>Статьи</span></a></li>
                                        {*<li><a href="{url mod=staticPage act=default task=about}"><span>О нас</span></a></li>*}
                                        <li {if $mod eq "staticPage" and $task eq "delivery"} class="active"{/if}><a href="{url mod=staticPage act=default task='delivery'}"><span>Оплата и доставка</span></a></li>
                                        {*<li {if $mod eq "staticPage" and $task eq "faq"} class="active"{/if}><a href="{url mod=staticPage act=default task=faq}"><span>FAQ</span></a></li>*}
                                        <li {if $mod eq "staticPage" and $task eq "razmernaja-setka"} class="active"{/if}><a href="{url mod=staticPage act=default task='razmernaja-setka'}"><span>Размеры</span></a></li>
                                        <li {if $mod eq "home" and $task eq "contact"} class="active"{/if}><a href="{url mod=home act=contact}"><span>Контакты</span></a></li>
                                    </ul>
                                    <form action="{url mod=pCatalog act=search}" id="search" method="post">
                                        <div class="search-input"><input name="q" id="search_inp" onblur="{literal}javascript: if (this.value=='') {this.value='Поиск';}" onfocus="javascript: if (this.value=='' || this.value=='Поиск') {this.value='';}{/literal}" value="{if $q}{$q}{else}Поиск{/if}" />   </div>
                                        <input type="submit" name="search-submit" class="search-submit btn-search" value=""/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="adaptive-head">
                            <div class="cat-list" id="cat-list">{include file="pcatalog/menu.inc.tpl"}</div>
                            <ul class="mobile-menu">
                                <li><a href="{url mod=home}" class="mini-logo"></a></li>
                                <li class="search">
                                    <span class="icon-search"></span>
                                </li>
                                <li><a href="{url mod=cart}" class="cart-adaptive"></a></li>
                                <li class="contact-adaptive">
                                    <span class="mobile-contact"></span>
                                    <span>Контакты</span>
                                    <div id="allconact">
                                        <div class="item_cont">
                                            <span class="phones">
                                                <span id="mts"> 099-923-81-89<br /></span>
                                                <span id="kievstar"> 097-948-50-39</span>
                                            </span>
                                            <span id="email-contact"> {'contact_email'|settings}</span>
                                        </div>
                                        <a class="all_contacts_a" href="{url mod=home act=contact}">Все контакты</a>
                                    </div>
                                </li>
                                <li class="menu-adaptive">
                                    <span class="open-menu"><span class="mobile-category"></span>Категории</span>
                                </li>
                            </ul>
                            <div class="before-menu">
                                <a href="{url mod=home act=about}"><span>О нас</span></a>
                                <a href="{url mod=news act=archive}"><span>Новости</span></a>
                                <a href="{url mod=article act=archive}"><span>Статьи</span></a>
                                <a href="{url mod=staticPage act=default task='razmernaja-setka'}"><span>Размеры</span></a>
                            </div>
                            <script type="text/javascript">
                            {literal}
                                $(document).ready(function(){
                                    $('li.menu-adaptive').on('click', function(e){
                                         e.stopPropagation();
                                         $(this).toggleClass('active');
                                         if ($(this).hasClass('active')) {
                                            $('#cat-list').css('width', '80%'); 
                                         } else {
                                             $('#cat-list').css('width', '0');
                                         }
                                    });
                                    $('.toggler').on('click', function(e){
                                          $(this).siblings('ul').slideToggle();
                                    });
                                    $('.sublevel-one-content  .toggler').toggle(function() {
                                         $(this).text('-');
                                        }, function() {
                                            $(this).text('+');
                                    });
                                    $('html').on('click', function(event){
                                         if (!$(event.target).hasClass('toggler')){
                                            $('#cat-list').css('width', '0');
                                            $('li.menu-adaptive').removeClass('active');  
                                         }
                                        
                                         
                                         if ($(event.target).parents('#search-box').size() == 0 && event.target.id != 'search-box') {
                                            $('.search-box').hide(); 
                                         }
                                         $('.adaptive-head li.contact-adaptive #allconact').hide();
                                         if (event.target.id != 'allcontact' && event.target.id != 'allconact_block' && $(event.target).parents('#allconact_block').size() == 0) {
                                            $('#allconact_block').hide();    
                                         }
                                    });
                                    $('.adaptive-head li.search').on('click', function(e){
                                         e.stopPropagation();
                                         $('.search-box').toggle();
                                         $('#allconact').hide(); 
                                    });
                                    $('.adaptive-head li.contact-adaptive').on('click', function(e){
                                         e.stopPropagation();
                                         $(this).find('#allconact').toggle();
                                         $('.search-box').hide();
                                    });
                                })
                            {/literal}
                            </script>
                            <div class="search-box" id="search-box">
                                <form action="{url mod=pCatalog act=search}" id="search-ad" method="post">
                                    <div class="search-input"><input name="q" id="search_inp" onblur="{literal}javascript: if (this.value=='') {this.value='Поиск';}" onfocus="javascript: if (this.value=='' || this.value=='Поиск') {this.value='';}{/literal}" value="{if $q}{$q}{else}Поиск{/if}" />   </div>
                                    <input type="submit" name="search-submit" class="search-submit btn-search" value=""/>
                                </form>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="contact_athers">
                        <a href="javascript:void(0);" id="allcontact" class="all">Еще контакты</a>
                        <div id="allconact_block">
                              <div class="item_cont">
                                   {*<!--<span class="addr"><strong>Адресс:</strong>Харьков, ул. Механизаторская, д.2</span>--> *}
                                   {*<span class="graff"><strong>График работы:</strong> пн-пт: 10.00-18.00</span> *}
                                   <span class="phones">
                                   <span id="mts"> 099-923-81-89<br /></span>
                                   <span id="kievstar"> 097-948-50-39</span>
                                   </span>
                                   <span id="email-contact"> {'contact_email'|settings}</span>
                              </div>
                              <a class="all_contacts_a" href="{url mod=home act=contact}">Все контакты</a>
                              {*<!--<span class="hr_dot"></span>
                              <div class="item_cont">
                                   <span class="addr"><strong>Адресс:</strong>Харьков, ул. Механизаторская, д.2</span>
                                   <span class="graff"><strong>График работы:</strong> пн-пт: 07.00-18.00<br />сб: 10.00-18.00</span>
                                   <span class="phones"><strong>Телефон:</strong>MTC:0994833433<br /> Life: 0932345765</span>
                              </div>-->*}
                        </div>
                    </div>
                    <div class="bells_form">
                        <div class="name-bell"><h1>Заказать обратный звонок</h1></div>
                        <div class="close-bell"></div>
                        <div class="submit-form">Ваш запрос принят. Наш менеджер свяжется с Вами в ближайшее время!</div>
                        <div class="submit-form-error">Извините! Произошла ошибка отправления формы, перезагрузите страницу и попытайтесь еще раз.</div>
                        <div class="bell-body">
                        <div class="bell-text left-bells-form-text">Не можете связаться с менеджерами? Оставьте свои данные и они сами вам перезвонят.</div>
                        <div class="text-bell" autocomplete="off" id="bell-name">Ваше имя? <span class="star">*</span></div><input class="inpbell titlex"  type="text" name="bell[name]" value="" maxlength="128">
                        <div class="bell-name-error left-bells-form-text">Имя не может быть меньше 3х символов</div>
                        <div class="text-bell" autocomplete="off" id="bell-email">Номер телефона: <span class="star">*</span></div><input class="inpbell titlex" type="text" name="bell[phone]" value="" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__">
                        <div class="bell-phone-error left-bells-form-text">Неверный формат номера</div>
                        <div class="required"><span class="star">*</span> - поля обязательные для заполнения</div>
                        <input type="button" name="bell[submit]" class="bell_button"  id="free_button" value="Отправить" />
                        </div>
                    </div>
                    <div class="bell-background"></div>
                    {/strip}
                    {strip}
                        <div id="main">
                            <div class="main-holder">
                                <div class="main-wrapper">
                                    <div class="main-container">
                                        <div id="sidebar">
                                             {workspace section=menu}
                                        </div>

                                        <div id="content">
                                        <div id="content-border"></div>
                                             {workspace}
                                        </div>
                                    </div>
                                    <div class="subscribe-block" >
                                        <form id="subscribe-form" method="post" >
                                            <div class="block-content" >
                                                <label for="subscribe-email" ><h1>Подписаться на рассылку: </h1></label>
                                                <input type="email" name="subscribe_email" required id="subscribe-email" placeholder="Ваш e-mail..." >
                                                <button type="submit" id="button-submit" >Отправить</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="bottom-block">
                                        <div class="slogan-box">
                                            <span class="logo">VolleyMAG</span>
                                            <h1>- Интернет-магазин спортивной обуви и одежды</h1>
                                        </div>
                                        <div class="text-container">
                                            <div class="wrapper">
                                                <div class="text-box">
                                                    <p>Вместе с нами купить спортивную одежду и обувь гораздо проще! Мы постоянно работаем над тем, чтобы покупки становились ещё выгоднее. Купить со скидкой в 5% качественную спортивную одежду и экипировку можно начиная уже со второй покупки!</p>
                                                    <p>Оформлять заказы станет ещё удобнее, если вы зарегистрируетесь в «Личном кабинете». Тогда вам будет доступен просмотр истории заказов, просмотр полученных скидок и не только.</p> 
                                                    <p>Одна из задач нашего магазина – поддержка детского, студенческого и аматорского спорта. При заказе товаров для команд вы обязательно получите скидку!</p>
                                                    <p>Виртуальные витрины Волеймаг постоянно пополняются новыми коллекциями спортивной экипировки! Делайте покупки оптом в и розницу на наших бесконечных полках!</p>
                                                    {*<div style="height: 146px;"></div>*}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="scroller"></div>
                        <div id="footer">
                            <div class="footer-container">
                                <div class="footer-box">
                                    <div class="container">
                                        <a href="http://illusix.com/" class="developer">illusix</a>
                                        <ul class="footer-nav">
                                            <li><a href="{url mod=home act=default}">Главная</a></li>
                                            <li><a href="{url mod=home act=about}">О нас</a></li>
                                            <li><a href="{url mod=news act=archive}">Новости и акции</a></li>
                                            <li><a href="{url mod=article act=archive}">Статьи</a></li>
                                            <li><a href="{url mod=staticPage act=default task='delivery'}">Оплата и доставка</a></li>
                                            <li><a href="{url mod=staticPage act=default task='razmernaja-setka'}">Размеры</a></li>
                                            <li><a href="{url mod=home act=contact}">Контакты</a></li>
                                            {*<li><a href="{url mod=staticPage act=default task=faq}">FAQ</a></li> *}

                                            {*<li><a href="{url mod=home act=contact}">Контакты</a></li>*}
                                        </ul>
                                        <div class="copyright">
                                            <span>«VolleyMAG» 2012-{$smarty.now|date_format:"%Y"}.</span>
                                            <span>All rigth recerved.</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{url mod=home act=default}" class="footer-link"></a>
                            </div>
                             <div class="messagebox"></div>
                        </div>
                    {/strip}
                </div>
            </div>
        </div>
    </div>
<div class="basket-right basket-cart-block">
<a href="https://facebook.com/volleymag" class="social_links fb_link" target="_blank"></a>
{*<a href="http://vk.com/volleymag" class="social_links vk_link" target="_blank"></a>*}
<a href="{url mod=cart}" class="basket-href">
    <div class="basket-right">
        {include file="cart/cart.header.tpl"}
    </div>
</a>
</div>
{*/if*}
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
{literal}
    <script>
        (function(){
            var widget_id = 855570;
            _shcp =[{widget_id : widget_id}];
            var lang =(navigator.language || navigator.systemLanguage
            || navigator.userLanguage ||"en")
                    .substr(0,2).toLowerCase();
            var url ="widget.siteheart.com/widget/sh/"+ widget_id +"/"+ lang +"/widget.js";
            var hcc = document.createElement("script");
            hcc.type ="text/javascript";
            hcc.async =true;
            hcc.src =("https:"== document.location.protocol ?"https":"http")
                    +"://"+ url;
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hcc, s.nextSibling);
        })();
    </script>
{/literal}
</body>
</html>