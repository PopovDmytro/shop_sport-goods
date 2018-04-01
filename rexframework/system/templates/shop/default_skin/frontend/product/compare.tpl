<div class="nav_category bgcolor round">
    <p class="navigation-p">                                      
        {strip}
            Сравнение
        {/strip}
    </p>
</div>
<div class="into-box">
    {if $productList}
        <a class="a-button bgcolor roundp" href="{url mod=product act=compareclear}" style="float:left">Oчистить</a>
        <div class="clear"></div>
        <table class="compare roundp">
            <tr>
                <td class="first">Модель</td>
                {foreach from=$productList key=key item=product name=prodlist}
                    {assign var=currBrand value=$brandList.$product_id}
                    <td width="215px" valign="top">
                        <table class="compare-prod-info">
                            <tr>
                                <td class="name">
                                    <p class="product-name"><a href="{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id}">{$product.name}</a></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                {strip}
                                    <a href="{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id}">                    
                                        <div class="img-box">
                                            {if $product.bestseller > 0}
                                                <span class="label35 bestseller35"></span>
                                            {elseif $product.event > 0}
                                                <span class="label35 event35"></span>
                                            {elseif $product.new > 0}
                                                <span class="label35 new35"></span>
                                            {/if}
                                            {if $product.image_id}    
                                                <img src="{getimg type=compare name=pImage id=$product.image_id ext=$product.image_ext}"/>
                                            {else}
                                                {img src="default-icon-120.jpg"}
                                            {/if}                   
                                        </div>
                                    </a>
                                {/strip}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {if $product.price}<p class="price-gr">${if $product.sku_price}{$product.sku_price}{else}{$product.price}{/if}</p>{/if}
                                </td>
                            </tr>
                            <tr>
                                <td class="tocart">
                                    <a href="{if $product.sku_id}{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id sku=$product.sku_id}{else}{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id}{/if}" class="a-button bgcolor roundp">
                                        Купить!
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                {/foreach}
            </tr>
            {$attributes}
        </table>
    {else}
        Список сравнения пуст, или некорректно добавлены товары.
    {/if}
</div>