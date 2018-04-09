<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Сравнение</a>
            </li>
        </ul>
    </div>
</div>

<div class="into-box">
    {if $productList}
        <a id="free_button" href="{url mod=product act=compareclear}" style="float:left">Oчистить</a>
        <div class="clear"></div>
        <table class="compare" id="compare">
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
                                    {if $product.price}<p class="price-gr">{if $product.sku_price}{$product.sku_price|round:0}{else}{$product.price|round:0}{/if} грн</p>{/if}
                                </td>
                            </tr>
                            <tr>
                                <td class="tocart">
                                    <a href="{if $product.sku_id}{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id sku=$product.sku_id}{else}{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id}{/if}" id="free_button">
                                        Купить!
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                {/foreach}
            </tr>
            {$attributes}
            <tr>
                <td class="first">Описание</td>
                {foreach from=$productList key=key item=product name=prodlist}
                    <td>
                        {$product.content|strip_tags|truncate:200}
                    </td>
                {/foreach}
            </tr>
        </table>
    {else}
        <div class="into-box page_text" style="margin-right:10px;">
            <p>Список сравнения пуст, или некорректно добавлены товары.</p>
        </div>
    {/if}
</div>