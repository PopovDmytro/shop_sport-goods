{if $error}
{include file="home/404.tpl"}
{else}
{assign var=product_id value=$product->id}
<ul class="breadcrumbs">
    <li><a href="{url mod=home}">Главная</a></li>
    {strip}
        {foreach from=$navCategoryList key=key item=category name=navBar}
            <li><a href="{url mod=pCatalog act=default task=$category.alias}">{$category.name}</a></li>
        {/foreach}
        {*<li>{$product->name|truncate:40:'...'}</li>*}
    {/strip}
</ul>
<div class="product-page">
    {include file='product/razmer.tpl'}
    <div class="block-background"></div>
    <ul class="product-list">
        <li>
            {assign var=image value=$imageList.0}
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
            <div class="info-product">
                <h1 class="heading-product">{*$pcatalog->name_single*} {$product->name}</h1>
                {*<img id="qr-img" src="{getimg type=page name=QRCode id=$product->id ext=png}" />*}
                <div class="clear"></div>
                <div class="info-box" id="product-page">
                    <div class="container">
                        <div class="parameters">
                            <a class="name-cat" href="{url mod=pCatalog task=$pcatalog->alias brand_alias=$productBrand->alias}">{$productBrand->name}</a>
                            <div class="buy-block">
                                {if $product->sale}
                                    <span class="product-price-sale-full">{$product->price_old} грн</span>
                                    <span class="prise-tosale cost">{$product->price} грн</span>
                                {else}
                                    <span class="prise-tosale cost">{$product->price} грн</span>
                                {/if}
                            </div>
                            {*<a class="setka" href="{url mod=staticPage task='razmernaja-setka'}">Размерная сетка</a>*}
                            <div class="clear"></div>
                            {if $productBrand->id eq 1 or $productBrand->id eq 3 or $productBrand->id eq 21}
                                {if $pcatalog->pid eq 125 or $pcatalog->pid eq 80 or $pcatalog->id eq 1}
                                    <a class="setka obyv" href="javascript:void(0)">Размерная сетка</a>
                                {elseif $pcatalog->id eq 52}
                                    <a class="setka nakolenniki" href="javascript:void(0)">Размерная сетка</a>
                                {elseif $pcatalog->id eq 60}
                                    <a class="setka noski" href="javascript:void(0)">Размерная сетка</a>
                                {elseif $pcatalog->pid eq 81 or $pcatalog->pid eq 54 or $pcatalog->pid eq 55 or $pcatalog->pid eq 92}
                                    <a class="setka odezhda" href="javascript:void(0)">Размерная сетка</a>
                                {elseif $pcatalog->id eq 63 or $pcatalog->id eq 128}
                                    <a class="setka" href="http://{'clear_domain'|config}/razmernaja-setka.html">Все размеры</a>
                                {/if}
                            {/if}

                            <div class="clear"></div>
                            {if $attrForSale}
                                <span>
                                    <span>Артикул:</span>
                                    <strong>
                                        <span id="product-id">{if $default_sku}{$default_sku}{else}{$product->id}{/if}</span>
                                    </strong>
                                </span>
                            {/if}
                            {*if $attribute->name eq 'Пол'}
                            <span><span>Пол:</span>
                                <strong>
                                    <span>
                                        {foreach from=$attributeList key=key item=subAttribute}
                                        {assign var=attr_id value=$subAttribute->id}
                                            {if $attr2prod.$attr_id}
                                                <div class="gender-name">{$subAttribute->name}</div>
                                            {/if}
                                        {/foreach}
                                    </span>
                                </strong>
                            </span>
                            {/if*}

                            {if $product->weight}
                                <span><span>Вес:</span><strong>{$product->weight} гр</strong></span>
                            {/if}
                            {if $sex}
                                 <span><span>Пол:</span><strong>{foreach from=$sex key=key item=item}{$item.name} {/foreach}</strong></span>
                            {/if}

                            {*if $product->unit}
                                <span><span>Единица:</span><strong>{$product->unit}</strong></span>
                            {/if*}
                            {include file="product/skuslist.inc.tpl"}
                        </div>
                        <span class="total-quantity" style="display: none;">
                            {if $totalQuantity eq 1}{$totalQuantity}{else}{$product->quantity}{/if}
                        </span>
                        <div class="product-des-box">
                            <form action="" method="post" id="cartForm" class="product-def-form">
                                <input type="hidden" name="mod" value="cart">
                                <input type="hidden" name="act" value="add">
                                <input type="hidden" name="cart[product_id]" value="{$product->id}">
                                <input type="hidden" name="cart[submit]" value="Купить">
                                <input type="hidden" name="cart[sku]" value="{$skus.0.id}">
                                {if $attrForSale}
                                    <div class="attr-wrapper number">
                                        <label for="number">Количество:</label>
                                        <div class="count-prod">
                                            <div class="count-minus back"></div>
                                            <div id="number" class="count">1</div>
                                            {*<input id="number" type="text" class="count" value="1"/> *}
                                            <div class="count-plus forward"></div>
                                        </div>
                                    </div>
                                {/if}
                                <input type="hidden" name="cart[count]" id="info-product-count" value="1" />
                                <div class="buy-block" style="position: static;">
                                    {if $attrForSale}
                                        <div class="wrapper-buy">
                                            <a href="javascript:void(0);" class="button-cart buy button-cart-product {if !$attrForSale}button-cart-active{/if} {if $isset_cart}cart-added{/if}" {if $attrForSale}style="opacity:0.6;"{/if}>
                                                <span class="a-button bgcolor">{if $isset_cart}В корзине. Перейти?{else}В корзину{/if}</span>
                                            </a>
                                        </div>
                                    {else}
                                        Нет в наличии
                                    {/if}
                                    <a href="javascript:void(0);" pid="{$product->id}" style="margin-top:25px; margin-right: 0; {if $attrForSale}opacity:0.6;{/if}" class="pa_compare compare_{$product->id} l_ic compare {if !$attrForSale}button-cart-active{/if}"></a>
                                </div>
                                <div class="buy-block" style="position: static;">
                                    <div class="wrapper-order order_bell">
                                        Заказать в один клик
                                    </div>
                                </div>
                                <div class="quick_order_form">
                                    <div class="name-bell"><h1>Заказать в один клик</h1></div>
                                    <div class="close-bell"></div>
                                    <div class="quick-order-submit-form">Ваш запрос принят. Наш менеджер свяжется с Вами в ближайшее время!</div>
                                    <div class="quick-order-submit-form-error">Извините! Произошла ошибка отправления формы, перезагрузите страницу и попытайтесь еще раз.</div>
                                    <div class="quick-order-body">
                                        {*<input id="quick-order-count" type="hidden" name="quick_order[count]" value="1">*}
                                        {*<div class="bell-text left-bells-form-text">Не можете связаться с менеджерами? Оставьте свои данные и они сами вам перезвонят.</div>*}
                                        <div class="text-bell" autocomplete="off" id="bell-email">Номер телефона: <span class="star">*</span></div><input class="inpbell titlex" id="quick-order-phone" type="text" name="quick_order[phone]" value="" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__">
                                        <div class="quick-order-phone-error left-quick-order-form-text">Неверный формат номера</div>
                                        <div class="required"><span class="star">*</span> - поля обязательные для заполнения</div>
                                        <input type="button" name="quick_order[submit]" class="quick_order_button"  id="free_button" value="Отправить" />
                                    </div>
                                </div>
                                <div class="bell-background"></div>
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
                            </form>
                        </div>
                    </div>
            </div>
            </div>
            <ul class="photo-gallery">
                        {assign var="img_counter" value="0"}
                        {assign var="iteration" value="0"}
                        {foreach from=$imageList key=image_key item=imgsmall name=images}
                            {if $imgsmall.id neq $image.id}
                                <li style="width: 72px;" class="img-gal-box" {if $imgsmall.attribute_id neq 0}attr_id="{$imgsmall.attribute_id}"{/if}>
                                    <img style="cursor: pointer;" src="{getimg type=icon name=pImage id=$imgsmall.id ext=$imgsmall.image}" onclick="loadImg('{$imgsmall.id}', '{$imgsmall.image}');" />
                                    <a id="{$imgsmall.id}" class="gallery" data-caption="{if $productBrand}{$productBrand->name} {/if}{$product->name}" href="{getimg type=main name=pImage id=$imgsmall.id ext=$imgsmall.image}"></a>
                                    <div style="display:none;">
                                        {getimg type=defmain name=pImage id=$imgsmall.id ext=$imgsmall.image}
                                    </div>
                                </li>
                            {/if}
                        {/foreach}
            </ul>
        </li>
    </ul>
    <div class="info-block">
        <div class="wrapper-info tab_prod">
            <ul class="nav-info">
                <li id="tab1" attr="1"><a onclick="ShowTab(1);"><span><em>доставка и оплата</em></span></a></li>
                <li id="tab2" attr="2"><a onclick="ShowTab(2);"><span><em>отзывы</em></span></a></li>
                <li id="tab3" attr="3"><a onclick="ShowTab(3);"><span><em>Характеристики</em></span></a></li>
                <li id="tab4" attr="4" class="active"><a onclick="ShowTab(4);"><span><em>Все</em></span></a></li>
            </ul>
        </div>
        <div class="product-def-description">
            <div id="product-def-description-1" style="display: none">
                <div class="text-container">
                    <div class="static-delivery">{assign var="content" value=$content}
                        <noindex>{$content}</noindex>
                    </div>
                </div>
            </div>
            <div id="product-def-description-3" style="display: none">
                {*if $attributes}
                    <table class="attributes">
                        <tr>
                            <th class="first">Характеристика</th>
                            <th>Значение</th>
                        </tr>
                    {$attributes}
                    </table>
                {else}
                    <h5>аттрибуты не выбраны</h5>
                {/if*}
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

            <div id="product-def-description-2" style="display: none">
                <div class="block-comments">
                    {strip}
                        {include file="comment/list.tpl"}
                        {$comment_form}
                    {/strip}
                </div>
            </div>
            {*if $attaches}
                {include file="attach/list.tpl"}
            {/if*}
            <div id="product-def-description-4">
                {strip}

                <div class="text-container">
                    <div class="social_likes">
                        <span class="social">
                            <!-- Put this script tag to the <head> of your page -->
                            {*<script type="text/javascript" src="//vk.com/js/api/openapi.js?113"></script>

                            <script type="text/javascript">
                                {literal}
                                VK.init({apiId: 4441584, onlyWidgets: true});
                                {/literal}
                            </script>

                            <!-- Put this div tag to the place, where the Like block will be -->
                            <div id="vk_like"></div>
                            <script type="text/javascript">
                                {literal}
                                    VK.Widgets.Like("vk_like", {type: "button", height: 20});
                                {/literal}
                            </script>*}
                        </span>
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
                    <div class="clear"></div>
                    <div class="product-content">
                        {*if $attributes}
                            <table class="attributes">
                                {$attributes}
                            </table>
                        {*else}
                            <span class="no-select">аттрибуты не выбраны</span>*}
                        {*/if*}
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
                        {*<span class="heading">Наши преимущества</span>*}
                        <div class="heading">Наши преимущества:</div>
                        <ul>
                            <li class="deliver">Доставка по всей Украине</li>
                            <li class="checked">Актуальные размеры</li>
                            <li class="assurance">Гарантия подлинности</li>
                            <li class="return">Гарантия возврата</li>
                            <li class="sale">Дополнительные скидки для постоянных покупателей</li>
                        </ul>
                        {*<span class="warranty">Гарантия 12 месяцев</span>
                        <span>обмен/возврат товара в течение 14 дней</span>*}
                        <a class="more" href="#amply">Подробнее</a>

                        {*<a class="more" id="amply" href="javascript:void(0);">Подробней</a>
                        <div class="amply"><p>Произвольный текст</p></div>*}
                    </div>
                </div>
                    {if $product.youtube}
                        <div style="text-align: center;">
                            <iframe width="100%" height="400" src="{$product.youtube}" frameborder="0" style="border-top: 1px solid #1B6CB3; margin-top: 15px;    padding-top: 10px;" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        </div>
                    {/if}
                <div class="clear"></div>
                <div class="block-comments">
                    {strip}
                        {include file="comment/list.tpl"}
                        {$comment_form}
                    {/strip}
                </div>
                {/strip}
            </div>
        </div>
        <div class="clear"></div>
    </div><!--product-->
    <div class="accordion">
        <div class="tabs">
            <div class="tab1">
                <a onclick="ShowTabAcc(1, this)">Характеристики</a>
                <div id="tab-1" class="tab-box">
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
                    <div class="more-brand">Узнать больше о <b><a href="{url mod=product act=brand brand_a=$productBrand->id}" target="_blank">{$productBrand->name}</a></b></div>
                </div>
            </div>
            <div class="tab2">
                <a onclick="ShowTabAcc(2, this)">Отзывы</a>
                <div id="tab-2" class="tab-box">
                    <div class="block-comments">
                        {strip}
                            {include file="comment/list.tpl"}
                            {$comment_form}
                        {/strip}
                    </div>
                </div>
            </div>
            <div class="tab3">
                <a onclick="ShowTabAcc(3, this);">Доставка и оплата</a>
                <div id="tab-3" class="tab-box">
                    <div class="text-container">
                        <div class="static-delivery">
                            {assign var="content" value=$content}
                            <noindex>{$content}</noindex>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="#x" class="overlay" id="amply"></a>
    <div class="benefits">
        <div style="padding: 15px;">
            {include file='product/benefits.tpl'}
        </div>
        <a class="close"title="Закрыть" href="#close"></a>
    </div>
    {if $productList}
        <div class="heading-block" style="padding-left:20px;">
            <strong class="lider">Сопутствующие товары</strong>
        </div>
        <div id="products-contents">
            {assign var=imageList value=$rimageList}
            {*assign var=categoryList value=$rCategoryList}
            {assign var=brandList value=$rBrandList*}
            {if $modal}
                {include file="pcatalog/product.block.tpl"}
            {else}
                {include file="pcatalog/product.list.tpl"}
            {/if}
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
    {/if}
        {strip}
            {include file="product/last.tpl"}
        {/strip}
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
