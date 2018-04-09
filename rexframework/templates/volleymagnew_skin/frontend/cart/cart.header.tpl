<div class="basket-holder">
    {img src='footer/cart_icon.png' class='header_cart-icon'}
    <div class="header_cart-dl-holder">
        <dl class="header_cart-dl">
            <dt>{if $cart_cnt eq 1}Товар{elseif $cart_cnt > 1 && $cart_cnt < 5}Товара{else}Товаров{/if}</dt>
            <dd>{$cart_cnt}</dd>
        </dl>
        <dl class="header_cart-dl">
            <dt>Сумма:</dt>
            <dd>{$cart_sum} грн.</dd>
        </dl>
    </div>
</div>