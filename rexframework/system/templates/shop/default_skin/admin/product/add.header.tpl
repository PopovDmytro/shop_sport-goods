<div class="product-add-header-step">
{if $act eq 'add'}
        <div class="{if $mod eq 'product'}product-add-step-active{else}product-add-step-done{/if}">
            <span>Данные</span> <br />
            Шаг 1
        </div>
        <div class="{if $mod eq 'attr2Prod'}product-add-step-active{elseif $mod eq 'product'}product-add-step-not-active{else}product-add-step-done{/if}">
            <span>Аттрибуты</span><br />
            Шаг 2
        </div>
        <div class="{if $mod eq 'sku' and $attr eq 'color'}product-add-step-active{elseif ($mod eq 'sku' and (!$attr || $attr eq 'sizes') or $mod eq 'pImage')}product-add-step-done{else}product-add-step-not-active{/if}">
           <span>Цвета</span><br />
            Шаг 3
        </div>
        <div class="{if $mod eq 'sku' and (!$attr || $attr eq 'sizes')}product-add-step-active{elseif $mod eq 'pImage'}product-add-step-done{else}product-add-step-not-active{/if}">
            <span>Размеры</span><br />
            Шаг 4
        </div>
        <div class="{if $mod eq 'pImage'}product-add-step-active{else}product-add-step-not-active{/if}">
            <span>Изображения</span><br />
            Шаг 5
        </div>
{else}
    {if $mod eq 'product'} {assign id_prod $task} 
    {elseif $mod eq 'attr2Prod'}{assign id_prod $product->id} 
    {else}{assign id_prod $product_id}{/if}
    <a href="/admin/?mod=product&act=edit&task={$id_prod}">
        <div class="{if $mod eq 'product'}product-add-step-active{else}product-add-step-done{/if}">
            <span>Данные</span> <br />
            Шаг 1
        </div>
    </a>
    <a href="/admin/?mod=attr2Prod&product_id={$id_prod}">
        <div class="{if $mod eq 'attr2Prod'}product-add-step-active{elseif $mod eq 'product'}product-add-step-not-active{else}product-add-step-done{/if}">
            <span>Аттрибуты</span><br />
            Шаг 2
        </div>
    </a>
    <a href="/admin/?mod=sku&product_id={$id_prod}&attr=color">
        <div class="{if $mod eq 'sku' and $attr eq 'color'}product-add-step-active{elseif ($mod eq 'sku' and (!$attr || $attr eq 'sizes') or $mod eq 'pImage')}product-add-step-done{else}product-add-step-not-active{/if}">
           <span>Цвета</span><br />
            Шаг 3
        </div>
    </a>
    <a href="/admin/?mod=sku&product_id={$id_prod}">
        <div class="{if $mod eq 'sku' and (!$attr || $attr eq 'sizes')}product-add-step-active{elseif $mod eq 'pImage'}product-add-step-done{else}product-add-step-not-active{/if}">
            <span>Размеры</span><br />
            Шаг 4
        </div>
    </a>
    <a href="/admin/?mod=pImage&product_id={$id_prod}">
        <div class="{if $mod eq 'pImage'}product-add-step-active{else}product-add-step-not-active{/if}">
            <span>Изображения</span><br />
            Шаг 5
        </div>
    </a>
{/if}   
</div>
<hr />