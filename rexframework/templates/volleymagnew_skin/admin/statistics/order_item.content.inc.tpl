<div class="order-box">
    <div class="order-content">
        <table>
            <thead>
            <tr>
                <td style="min-width: 30px;max-width: 30px">№ заказа</td>
                <td style="min-width: 300px;max-width: 300px">Товар</td>
                <td style="min-width: 70px;max-width: 70px">Артикул товара</td>
                <td style="min-width: 30px;">Количество</td>
                <td style="min-width: 30px;">Закупка ед.</td>
                <td style="min-width: 30px;">Продажа за ед.</td>
                <td style="min-width: 30px;">Закупка</td>
                <td style="min-width: 30px;">Продажа</td>
                <td style="min-width: 70px;max-width: 70px">Продажа с учётом скидки на заказ</td>
                <td style="min-width: 30px;">Прибыль</td>
                <td style="min-width: 70px;max-width: 70px">Прибыль с учётом скидки на заказ</td>
            </tr>
            </thead>
            <tbody>
            {foreach from=$order.products key=product_key item=product }
                <tr>
                    {if $product_key eq 0 }
                        <td rowspan="{count($order.products)}">№ {$order.id}</td>
                    {/if}
                    <td>{$product.name}</td>
                    <td>{$product.product_article}</td>
                    <td>{$product.count}</td>
                    <td>{$product.price_opt}</td>
                    <td>{$product.price}</td>
                    <td>{$product.price_opt * $product.count}</td>
                    <td>{$product.price * $product.count}</td>
                    <td>{(($product.price * $product.count) * ( (100 - $order.sale)/100 ))|round}</td>
                    <td>{($product.price - $product.price_opt) * $product.count}</td>
                    <td>{((($product.price * ( (100 - $order.sale)/100 )) - $product.price_opt) * $product.count)|round}</td>
                </tr>
            {/foreach}
            <tr><td colspan="11" style="height: 15px" ></td></tr>
            <tr>
                <td>ФИО заказчика:</td>
                <td>{$order.user_name}</td>
                <td></td>
                <td colspan="2" ><b>Итоги по заказу:</b></td>
                <td>№ {$order.id}</td>
                <td><b>{$order.total_price_opt}</b></td>
                <td><b>{$order.total_price}</b></td>
                <td></td>
                <td><b>{$order.total_price - $order.total_price_opt}</b></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2" >Скидка на заказ, %</td>
                <td>{$order.sale}</td>
                <td></td>
                <td><b class="red" >{$order.total_sale_price}</b></td>
                <td></td>
                <td><b class="red" >{$order.total_sale_price - $order.total_price_opt}</b></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>