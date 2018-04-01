<div class="basket-holder">
    {*<span>В<a href="{url mod=cart}">корзине</a></span>*}
    <span style="display: block">В корзине</span>
    <span style="display: block">{$cart_cnt} {if $cart_cnt eq 1}товар{elseif $cart_cnt > 1 && $cart_cnt < 5}товара{else}товаров{/if}</span>
    <span style="display: block">на {$cart_sum} грн</span>
    {if $cart_cnt}
        <div class="checkout">Оформить</div>
    {/if}
</div>
