{assign var=product_id value=$product->id}
    {literal}
        <script type="text/javascript">
            $(document).ready(function() {
                ShowSubmenu('{/literal}{$podcat_v}{literal}'); 
            });   
        </script>
    {/literal} 
{if $navCategoryList}
    <div class="nav_category round">
        <p class="navigation-p">
            {strip}
                {foreach from=$navCategoryList key=key item=category name=navBar}
                    <a href="{url mod=pCatalog act=default task=$category.alias}">{$category.name}</a>
                    {if !$smarty.foreach.navBar.last}&nbsp;->&nbsp;{/if}
                {/foreach}
                &nbsp;->&nbsp;{$product->name}
            {/strip}
        </p>
    </div> 
{/if}
<div id="content" class="product-id">
<div class="product">
<h1>{$pcatalog->name_single} {if $productBrand}<a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?filter[brand][]={$productBrand->id}">{$productBrand->name}</a> {/if}{$product->name}</h1>
    {assign var=image value=$imageList.0}
    {if $image and $image.id}
        {if $product->bestseller > 0}
            <span class="label100 bestseller70"></span>
        {elseif $product->event > 0}
        <span class="label100 event70"></span>
        {elseif $product->new > 0}
            <span class="label100 new70"></span>
        {/if}
        <div class="product-img">
            <a style="position:relative; display:block;" id="imgfull" class="gallery" title="{if $productBrand}{$productBrand->name} {/if}{$product->name}" href="{getimg type=main name=pImage id=$image.id ext=$image.image}">
                <span class="prodlabel {if $product->bestseller eq 1}topprod{elseif $product->event eq 1}actprod{elseif $product->new eq 1}newprod{/if}"></span>
                <img id="imageFull" src="{getimg type=page name=pImage id=$image.id ext=$image.image}" />
            </a>
            {assign var="iteration" value="0"}
            <div class="product-def-gallery">
                {assign var="img_counter" value="0"}
                {foreach from=$imageList key=image_key item=image name=images}    
                    <div style="width: 70px;" class="img-gal-box" {if $image.attribute_id neq 0}attr_id="{$image.attribute_id}"{/if}>
                        <img style="cursor: pointer;" src="{getimg type=icon name=pImage id=$image.id ext=$image.image}" onclick="loadImg('{$image.id}', '{$image.image}');" />
                        <a id="{$image.id}" class="gallery" href="{getimg type=main name=pImage id=$image.id ext=$image.image}"></a>
                        <div style="display:none;">
                            {getimg type=page name=pImage id=$image.id ext=$image.image}
                        </div>
                    </div>
                    {*if $smarty.foreach.images.iteration % 4 eq 0}
                        <div class="clear"></div>
                    {/if*}
                {/foreach}
            </div>
        </div>
        
        {literal}<script> prev_id = '{/literal}{$image.id}{literal}' </script>{/literal}
    {else}
        {if $product->bestseller > 0}
            <span class="label100 bestseller70"></span>
        {elseif $product->event > 0}
            <span class="label100 event70"></span>
        {elseif $product->new > 0}
            <span class="label100 new70"></span>
        {/if}
        <div class="product-img">
            {img src="default-icon-240.jpg"}
        </div>
    {/if}
    
    <div class="product-discription">
        <img id="qr-img" src="{getimg type=page name=QRCode id=$product->id ext=png}" />
        {assign var=dolar_rate value='dolar_rate'|settings}
        <p class="product-def-title">Код: <span id="product-id">{$product->id}</span></p>
        {*<div class="name">{$pcatalog->name}</div>*}
        <div class="clear"></div>
        {if $product->sale}
            <div class="product-price-sale-full">${$product->price_old} </div>
            <div class="price">${$product->price}</div>
        {else}
            <div class="price">${$product->price}</div>
        {/if}
        
        <div class="product-des-box">
            <div class="attr-wrapper">
                <b>В наличии: </b>
                <span class="total-quantity">
                    {if $totalQuantity}{$totalQuantity}{else}{$product->quantity}{/if}
                </span>
            </div>
            {include file="product/skuslist.inc.tpl"}
            
            <form action="" method="post" id="cartForm" class="product-def-form">
                <input type="hidden" name="mod" value="cart">
                <input type="hidden" name="act" value="add">
                <input type="hidden" name="cart[product_id]" value="{$product->id}">    
                <input type="hidden" name="cart[submit]" value="Купить">  
                <div class="attr-wrapper">  
                    <b>Выберите количество:</b>
                    <div class="count-prod">
                        <div class="count-minus">-</div>
                        <div class="count">1</div>
                        <div class="count-plus">+</div>
                    </div>
                </div>
                <input type="hidden" name="cart[count]" id="info-product-count" value="1" />
                <a href="javascript:void(0);" class="button-cart {if !$attrForSale}button-cart-active{/if} {if $isset_cart}cart-added{/if}" {if $attrForSale}style="opacity:0.6;"{/if}>
                    <div class="a-button bgcolor roundp">{if $isset_cart}В корзине. Перейти?{else}Купить!{/if}</div>
                </a>
                <a href="javascript:void(0);" pid="{$product->id}" style="margin-top:5px; {if $attrForSale}opacity:0.6;{/if}" class="pa_compare compare_{$product->id} l_ic a-button bgcolor roundp {if !$attrForSale}button-cart-active{/if} {if $isset_compare}comp-added">Просмотр{else}">Cравнить{/if}</a>
                {if $attrForSale}
                    <div class="mystical-tooltip">
                        Для заказа товара необходимо выбрать все свойства    
                    </div>
                    
                    <script>
                        {literal}
                            $(document).ready(function(){
                                $('.button-cart:not(.button-cart-active)').rexTooltip({
                                   layout: '.mystical-tooltip',
                                   parent: '#cartForm',
                                   afterBody: true,
                                   predelay: 200,
                                   delay: 200,
                                   top: -10,
                                   arrowLeft: 0,
                                   position: 'top'
                                });
                                $('.pa_compare:not(.button-cart-active)').rexTooltip({
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
    <div class="clear"> </div>
    <div class="tab_prod">
        <table class="tab-prod">
            <tr class="tr-tab-prod">
                <td  onclick="ShowTab(1);">
                    <div id="tab1" class="act-tab bgcolor">
                        <div id="ltab1" class="act-tab-l">
                            <div id="rtab1" class="act-tab-r">
                                    <div id="ttab1" class="act-tab-title">Описание</div>
                            </div>
                        </div>
                    </div> 
                </td>
                <td  onclick="ShowTab(2);">
                    <div id="tab2" class="tab">
                        <div id="ltab2" class="tab-l">
                            <div id="rtab2" class="tab-r">
                                    <div id="ttab2" class="tab-title">Характеристики</div>
                            </div>
                        </div>
                    </div>
                </td>
                <td  onclick="ShowTab(3);" style="border-right:0px">
                    <div id="tab3" class="tab">
                        <div id="ltab3" class="tab-l">
                            <div id="rtab3" class="tab-r">
                                    <div id="ttab3" class="tab-title">Отзывы</div>
                            </div>
                        </div>
                    </div>
                </td>
                {if $attaches}
                    <td width="134" onclick="ShowTab(4);">
                        <div id="tab4" class="tab">
                            <div id="ltab4" class="tab-l">
                                <div id="rtab4" class="tab-r">
                                        <div id="ttab4" class="tab-title">Файлы</div>
                                </div>
                            </div>
                        </div>
                    </td>
                {/if}
            </tr>
        </table>
    </div>
    <div class="product-def-description">
        <div id="product-def-description-1">
            {if $product->content}
                {$product->content}
            {else}
                нет описания
            {/if}
        </div>
        <div id="product-def-description-2" style="display: none">
            {if $attributes}
                <table class="attributes round">
                    <tr>
                        <th class="first">Характеристика</th>
                        <th>Значение</th>
                    </tr>
                {$attributes}
                </table>
            {else}
             аттрибуты не выбраны
            {/if}
        </div>
        <div id="product-def-description-3" style="display: none">
            {strip}
            {include file="comment/list.tpl"}
            {$comment_form}
            {/strip}
        </div>
        {if $attaches}
            {include file="attach/list.tpl"}    
        {/if}
    </div>
    <div class="clear"></div>
    </div><!--product-->    
 </div><!-- #content-->  
{if $relatedList}
    <div class="nav_category bgcolor round" >
        <p class="navigation-p">
            <a href="#">Сопутствующие товары</a>
        </p>
    </div>
    <div id="content" class="related">
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
    </div>
{/if}
