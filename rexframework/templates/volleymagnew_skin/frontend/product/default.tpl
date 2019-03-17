{if $error}
    {include file="home/404.tpl"}
{else}
{assign var=product_id value=$product->id}
<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            {strip}
                {foreach from=$navCategoryList key=key item=category name=navBar}
                    <li class="breadcrumbs_item active">
                        <a href="{url mod=pCatalog act=default task=$category.alias}" class="breadcrumbs_link">{$category.name}</a>
                    </li>
                {/foreach}
            {/strip}
        </ul>
    </div>
</div>
<div class="product-page">
    {include file='product/razmer.tpl'}

    <section class="product-card_main-section">
        <div class="row">
            <div class="column small-12">
                <h1 class="section-title section-title--blue text-left">{$product->name}</h1>
            </div>
        </div>
        <div class="row product-card_card-wrapper">
            <div class="columns large-5 small-12">
                <div  class="product-card_product-pic">
                    {assign var=image value=$imageList.0}
                    <div class="product-card_pic_main">
                        <div id="left-image-block">
                            {if $image and $image.id}
                                <div class="img-box">
                                    <a style="position:relative; display:block;" id="imgfull" class="gallery rex-tooltip-disable" data-caption="{if $productBrand}{$productBrand->name} {/if}{$product->name}" href="{getimg type=main name=pImage id=$image.id ext=$image.image}">
                                        <img id="imageFull" src="{getimg type=defmain name=pImage id=$image.id ext=$image.image}" />
                                    </a>
                                </div>
                            {literal}<script> prev_id = '{/literal}{$image.id}{literal}' </script>{/literal}

                            {else}
                                <div class="img-box">
                                    {img width="400" height="400" src="defmain.jpg" class="t-image"}
                                </div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
            <div class="info-box product-card_info-column columns large-7 small-12" id="product-page">
                <ul class="product-card_pic_additional photo-gallery">
                    {assign var="img_counter" value="0"}
                    {assign var="iteration" value="0"}
                    {foreach from=$imageList key=image_key item=imgsmall name=images}
                        {if $imgsmall.id neq $image.id}
                            <li class="img-gal-box" {if $imgsmall.attribute_id neq 0}attr_id="{$imgsmall.attribute_id}"{/if}>
                                <div class="product-card_pic_thumbnail">
                                    <img style="cursor: pointer;" src="{getimg type=icon name=pImage id=$imgsmall.id ext=$imgsmall.image}" onclick="loadImg('{$imgsmall.id}', '{$imgsmall.image}');" />
                                    <a id="{$imgsmall.id}" class="gallery" data-caption="{if $productBrand}{$productBrand->name} {/if}{$product->name}" href="{getimg type=main name=pImage id=$imgsmall.id ext=$imgsmall.image}"></a>
                                    <div style="display:none;">
                                        {getimg type=defmain name=pImage id=$imgsmall.id ext=$imgsmall.image}
                                    </div>
                                </div>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
                <form action="" method="post" id="cartForm" class="product-def-form product-card_info">
                    <input type="hidden" name="mod" value="cart">
                    <input type="hidden" name="act" value="add">
                    <input type="hidden" name="cart[product_id]" value="{$product->id}">
                    <input type="hidden" name="cart[submit]" value="Купить">
                    <input type="hidden" name="cart[sku]" value="{$skus.0.id}">
                    <input type="hidden" name="cart[count]" id="info-product-count" value="1" />
                    <ul class="product-info_flex-list">
                        <li>
                            <div class="product-card_logo-holder">
                                <a class="name-cat" href="{url mod=pCatalog task=$pcatalog->alias brand_alias=$productBrand->alias}">
                                    {assign var=img_path value="content/images/brand/`$productBrand['id']`/"}
                                    {if file_exists("{$img_path}icon.{$productBrand["icon"]}")}
                                        <img src="/{$img_path}icon.{$productBrand["icon"]}" alt="{$productBrand->name}">
                                    {elseif file_exists("{$img_path}list.{$productBrand["icon"]}")}
                                        <img src="/{$img_path}list.{$productBrand["icon"]}" alt="{$productBrand->name}">
                                    {else}
                                        {$productBrand->name}
                                    {/if}
                                </a>
                            </div>
                        </li>
                        {if $attrForSale}
                            <li class="m0" style="margin-bottom: 0">
                                <dl class="product-card_dl">
                                    <dt>Артикул:</dt>
                                    <dd>
                                        <span id="product-id">{if $default_sku}{$default_sku}{else}{$product->id}{/if}</span>
                                    </dd>
                                </dl>
                            </li>
                        {/if}
                            <li class="m0" style="margin-bottom: 0">
                                {if $sex}
                                <dl class="product-card_dl">
                                    <dt>Пол:</dt>
                                    <dd>{foreach from=$sex key=key item=item}{$item.name} {/foreach}</dd>
                                </dl>
                                {/if}
                                {if $product->weight}
                                <dl class="product-card_dl">
                                    <dt>Вес:</dt>
                                    <dd>{$product->weight} гр</dd>
                                </dl>
                                {/if}
                            </li>
                        {include file="product/skuslist.inc.tpl"}
                        <li class="m0">
                            <div class="buy-block" style="position: static;">
                                <button type="button" class="btn btn--blue wrapper-order order_bell">
                                    Заказать в один клик
                                </button>
                            </div>
                            <div class="quick_order_form popup_box">
                                <div class="popup_header">
                                    <h3>Заказать в один клик</h3>
                                </div>
                                <div class="popup_body">
                                    <div class="close-bell popup_close-btn"></div>
                                    <div class="quick-order-submit-form">Ваш запрос принят. Наш менеджер свяжется с Вами в ближайшее время!</div>
                                    <div class="quick-order-submit-form-error">Извините! Произошла ошибка отправления формы, перезагрузите страницу и попытайтесь еще раз.</div>
                                    <div class="quick-order-body">
                                        {*<input id="quick-order-count" type="hidden" name="quick_order[count]" value="1">*}
                                        {*<div class="bell-text left-bells-form-text">Не можете связаться с менеджерами? Оставьте свои данные и они сами вам перезвонят.</div>*}
                                        <div class="text-bell" autocomplete="off" id="bell-email"></div>
                                        <input class="inpbell titlex" id="quick-order-phone" type="text" name="quick_order[phone]" value="" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__">
                                        <div class="quick-order-phone-error left-quick-order-form-text">Неверный формат номера</div>
                                        {*<div class="required"><span class="star">*</span> - поля обязательные для заполнения</div>*}
                                        <button type="button" name="quick_order[submit]" class="quick_order_button btn btn--blue"  id="free_button" value="Отправить" >Отправить</button>
                                    </div>
                                </div>
                            </div>
                            <div class="bell-background"></div>
                        </li>
                    </ul>
                    <ul class="product-info_flex-list">
                        <li>
                            <div class="product-card_price-block">
                                <div class="label">Цена:</div>
                                <div class="price-holder">
                                    <div class="buy-block">
                                        {if $product->sale}
                                            <div class="current-price cost">{$product->price} грн</div>
                                            <div class="old-price prise-tosale product-price-sale-full">{$product->price_old} грн</div>
                                        {else}
                                            <div class="prise-tosale cost">{$product->price} грн</div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                                <div class="product-card_compare buy-block">
                                    <div class="label">Сравнить:</div>
                                    <a href="javascript:void(0);" pid="{$product->id}" style="{if $attrForSale}opacity:0.6;{/if}" class="product-btn product-btn--compare pa_compare compare_{$product->id} l_ic compare {if !$attrForSale}button-cart-active{/if}">
                                        {img src='main-page/compare_icon.png' itemprop="logo" class="compare-icon"}
                                    </a>
                                </div>
                            </li>
                        <li class="sizes-container">
                                {if $productBrand->id eq 1 or $productBrand->id eq 3 or $productBrand->id eq 21}
                                    {if $pcatalog->pid eq 125 or $pcatalog->pid eq 80 or $pcatalog->id eq 1}
                                        <a class="btn btn--blue sm setka obyv" href="javascript:void(0)">Размерная сетка</a>
                                    {elseif $pcatalog->id eq 52}
                                        <a class="btn btn--blue sm setka nakolenniki" href="javascript:void(0)">Размерная сетка</a>
                                    {elseif $pcatalog->id eq 60}
                                        <a class="btn btn--blue sm setka noski" href="javascript:void(0)">Размерная сетка</a>
                                    {elseif $pcatalog->pid eq 81 or $pcatalog->pid eq 54 or $pcatalog->pid eq 55 or $pcatalog->pid eq 92}
                                        <a class="btn btn--blue sm setka odezhda" href="javascript:void(0)">Размерная сетка</a>
                                    {elseif $pcatalog->id eq 63 or $pcatalog->id eq 128}
                                        <a class="btn btn--blue sm setka" href="http://{'clear_domain'|config}/razmernaja-setka.html">Все размеры</a>
                                    {/if}
                                {/if}
                            </li>
                        <li>
                                <div class="product-card_stock">
                                    {if $attrForSale}
                                        <div class="attr-wrapper number">
                                            <div class="text-center label">Количество:</div>
                                            <div class="checkout_stock count-prod">
                                                <button type="button" class="count-minus back checkout_btn checkout_btn--decrease"></button>
                                                <div id="number" class="count">1</div>
                                                {*<input id="number" type="text" class="count" value="1"/> *}
                                                <button type="button" class="count-plus forward checkout_btn checkout_btn--increase"></button>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                            </li>
                        <li class="m0">
                                <div class="buy-block" style="position: static;">
                                    {if $attrForSale}
                                        <div class="wrapper-buy">
                                            <a href="javascript:void(0);" class="btn btn--green product_checkout-btn button-cart buy button-cart-product {if !$attrForSale}button-cart-active{/if} {if $isset_cart}cart-added{/if}" {if $attrForSale}style="opacity:0.6;"{/if}>
                                                {img src='checkout-icon--white.png' itemprop="logo" class="compare-icon"}
                                                <span class="a-button bgcolor">{if $isset_cart}В корзине. Перейти?{else}В корзину{/if}</span>
                                            </a>
                                        </div>
                                    {else}
                                        Нет в наличии
                                    {/if}
                                </div>
                                {if $attrForSale}
                                    <div class="mystical-tooltip" style="display: none;">
                                        Для заказа товара необходимо выбрать все свойства
                                    </div>
                                    <script>
                                        {literal}
                                        $(document).ready(function(){
                                            $('.attr-default.color-box:first').trigger('click');
                                            $('#product-page .button-cart:not(.button-cart-active)').rexTooltip({
                                                layout: '.mystical-tooltip',
                                                parent: '#cartForm',
                                                afterBody: true,
                                                predelay: 200,
                                                delay: 200,
                                                top: -10,
                                                arrowLeft: 0,
                                                position: 'top'
                                            });
                                            $('#product-page .pa_compare:not(.button-cart-active)').rexTooltip({
                                                layout: '.mystical-tooltip',
                                                parent: '#cartForm',
                                                afterBody: true,
                                                predelay: 200,
                                                delay: 200,
                                                top: -10,
                                                arrowLeft: 0,
                                                position: 'top'
                                            });
                                        });
                                        {/literal}
                                    </script>
                                 {/if}
                                <span class="total-quantity" style="display: none;">
                                    {if $totalQuantity eq 1}{$totalQuantity}{else}{$product->quantity}{/if}
                                </span>
                                <div class="product-des-box"></div>
                            </li>
                    </ul>
                </form>
            </div>
        </div>

        <div class="row info-block">
            <div class="columns small-12">
                <div class="product-card_tabs-box">
                    <ul id="product-card-tabs" data-tabs class="tabs">
                        <li class="tabs-title is-active">
                            <a aria-selected="false" data-tabs-target="panel" href="#panel">Все</a>
                        </li>
                        <li class="tabs-title">
                            <a aria-selected="false" data-tabs-target="pane2" href="#pane2">Характеристики</a>
                        </li>
                        <li class="tabs-title">
                            <a aria-selected="false" data-tabs-target="pane3" href="#pane3">Отзывы</a>
                        </li>
                        <li class="tabs-title">
                            <a aria-selected="false" data-tabs-target="pane4" href="#pane4">Доставка и оплата</a>
                        </li>
                    </ul>
                    <div data-tabs-content="product-card-tabs" class="tabs-content">
                        <div id="panel" class="tabs-panel is-active">
                            {strip}
                                <div class="text-container">
                                    {*<div class="social_likes">
                                        <span class="social"></span>
                                        <span class="social">
                                            <div id="fb-root"></div>
                                            <script>
                                            {literal}
                                            (function(d, s, id) {
                                                var js, fjs = d.getElementsByTagName(s)[0];
                                                if (d.getElementById(id)) return;
                                                js = d.createElement(s); js.id = id;
                                                js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.0";
                                                fjs.parentNode.insertBefore(js, fjs);
                                            }(document, 'script', 'facebook-jssdk'));
                                            {/literal}
                                            </script>
                                            <div class="fb-like" data-href="http://www.volleymag.com.ua{$smarty.server.REQUEST_URI}" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true" data-width="190px"></div>
                                        </span>
                                    </div>

                                    <br>*}

                                    <div class="product-content">
                                        {if $product.content}
                                            {$product.content}
                                        {/if}
                                        {if $technologies}
                                            <br/>
                                            <p class="big-text-product">Технологии <b>{$productBrand->name}</b></p>
                                            <ul class="technologies">
                                                {foreach from=$technologies key=technology_key item=technology name=technology}
                                                    <li class="technology">
                                                        <img src="{getimg type=icon name=technology id=$technology.id ext=$technology.icon}" class="img-technology" title="{$technology.description}" />
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                        <br/>
                                        Узнать больше о <b><a href="{url mod=product act=brand brand_a=$productBrand->id}" target="_blank">{$productBrand->name}</a></b>
                                    </div>
                                    <div class="delivery">
                                        <div class="heading">Наши преимущества:</div>
                                        <ul>
                                            <li class="deliver">  Доставка по всей Украине</li>
                                            <li class="checked">Актуальные размеры</li>
                                            <li class="assurance">Гарантия подлинности</li>
                                            <li class="return">Гарантия возврата</li>
                                            <li class="sale">Дополнительные скидки для постоянных покупателей</li>
                                        </ul>
                                        <a class="more" href="#amply">Подробнее <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            {/strip}
                        </div>

                        <div id="pane2" class="tabs-panel">
                            {if $product.content}
                                <div class="prod_content">{$product.content}</div>
                            {/if}
                            {if $technologies}
                                <table class="table-technologies">
                                    <tr>
                                        <td colspan="2">
                                            <p class="technology-description big-text-product">
                                                Технологии
                                                <b>{$productBrand->name}</b>
                                            </p>
                                        </td>
                                    </tr>
                                    {foreach from=$technologies key=technology_key item=technology name=technology}
                                        <tr>
                                            <td><img src="{getimg type=icon name=technology id=$technology.id ext=$technology.icon}" class="img-technology" title=""/>
                                            </td>
                                            <td class="technology-description">
                                                {$technology.description}
                                            </td>
                                        </tr>
                                    {/foreach}
                                </table>
                            {/if}
                        </div>
                        <div id="pane3" class="tabs-panel">
                            <div class="block-comments">
                                {strip}
                                    {include file="comment/list.tpl"}
                                    {$comment_form}
                                {/strip}
                            </div>
                        </div>
                        <div id="pane4" class="tabs-panel">
                            <div class="text-container">
                                <div class="static-delivery">{assign var="content" value=$content}
                                    <noindex>{$content}</noindex>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <a href="#x" class="overlay popup_overlay" id="amply"></a>
    <div class="benefits">
        <div class="popup_box">
            {include file='product/benefits.tpl'}
        </div>
        <a class="close popup_close-btn" title="Закрыть" href="#close"></a>
    </div>

    {if $productList}
        <section class="product-card_additional-goods">
            <div class="row align-center">
                <div class="columns small-12">
                    <div class="heading-block">
                        <h1 class="section-title section-title--blue">Сопутствующие товары</h1>
                    </div>
                    <input type="hidden" id="count_next" value="{$count_next}">
                    <div class="my-slider my-slider_product" id="products-contents">
                        {assign var=imageList value=$rimageList}
                        {include file="pcatalog/product.block.inc.tpl"}
                    </div>
                </div>
            </div>
            {*<div id="content" class="related">
        {foreach from=$relatedList key=key item=related name=related_list}
            {assign var=related_id value=$related.id}
            {if $rBrandList.$related_id}
                {assign var=rBrand value=$rBrandList.$related_id}
            {/if}
            {assign var=rCategory value=$rCategoryList.$related_id}
            {if $smarty.foreach.related_list.iteration % 3 eq 0}
                <div class="item last">
            {else}
                <div class="item">
            {/if}
                    <div class="item-img">
                        <a href="{url mod=product act=default cat_alias=$rCategory.alias task=$related_id}">
                            {if $rimageList.$related_id}
                                    {assign var=image value=$rimageList.$related_id}
                                    {strip}
                                        <img src="{getimg type=list name=pImage id=$image.id ext=$image.image}"/>
                                    {/strip}
                            {else}
                                {img width="210" src="default-icon-120.jpg" class="t-image"}
                            {/if}
                        </a>
                    </div>
                    <div class="name">
                        <a href="{url mod=product act=default cat_alias=$rCategory.alias task=$related_id}">
                            {$related.name}
                        </a>
                    </div>
                    {if $related.sale}
                        <div class="price-sale">
                            <span class="prise-sale-full">${$related.price}</span>
                            <span class="prise-tosale">${($related.price - $related.price*$related.sale/100)|round:2}</span>
                        </div>
                    {else}
                        <div class="price"> <span>${$related.price}</span></div>
                    {/if}
                </div>
        {/foreach}
        </div>  *}
        </section>
    {/if}
        {*{strip}*}
            {*{include file="product/last.tpl"}*}
        {*{/strip}*}
</div><!-- #prod_page-->

    {include file="_block/input.phone.mask.tpl"}
    <script type="text/javascript">
        var menu_min_category = $('#cat-list').find('#menu-n'+'{$pcatalog->id}'),
                menu_category = $('.sidebar-holder').find('#menu-n'+'{$pcatalog->id}'),
                img_box = $('.img-box'),
                info_product = $('.info-product'),
                marge = 0;
        {literal}
        $(document).ready(function(){
            menu_min_category.addClass('active');
            menu_category.addClass('active');
            menu_category.closest('ul').slideDown();
            initPhoneMask();
           // while (info_product.outerHeight() > img_box.outerHeight() && marge < 100) {
            //    marge += 5;
            //    img_box.css("padding", marge+'px 0');
            //}
        });
        {/literal}
    </script>
{/if}
