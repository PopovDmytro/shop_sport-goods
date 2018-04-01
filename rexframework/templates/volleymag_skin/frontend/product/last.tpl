{if $lastproduct}
    <div class="heading-block">
        <strong class="lider">Последние просмотренные
        </strong>
    </div>
    {foreach from=$lastproduct key=key item=item}
        <div class="last-product-block">
            <a href="{url mod=product act=default task=$item.id cat_alias=$item.palias}">{$item.name}</a>
            <div class="last-product-block-content">
                <a href="{url mod=product act=default task=$item.id cat_alias=$item.palias}" class="wrapper">
                    {if $item.pimageid}
                        <img width="120" height="120"
                             src="{getimg type=list name=pImage id=$item.pimageid ext=$item.image}"/>
                    {else}
                        {img width="120" height="120" src="list.jpg" class="t-image"}
                    {/if}
                </a>
                {$item.content|truncate:200}
                <p> Цена :
                    {if $item.sale}
                        {assign var=new value=$item.price -$item.price*$item.sale/100}
                       <span class="prise-tosale cost">{$new|floor } грн</span>
                        {* <span class="product-price-sale-full">{$item.price} грн</span>*}
                    {else}
                        <span class="prise-tosale cost">{$item.price} грн</span>
                    {/if}
                </p>
                <p>
                    <span>Артикул:</span>
                    <strong>
                        <span id="product-id">{if $item.sku_article}{$item.sku_article}{else}{$item.id}{/if}</span>
                    </strong>

                </p>
            </div>
        </div>

    {/foreach}

{/if}