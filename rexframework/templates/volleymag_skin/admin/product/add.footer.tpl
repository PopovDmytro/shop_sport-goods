<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2">
            {if $mod eq 'product'}
                <button id="button-step-next" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    <span class="ui-button-text">Далее ></span>
                </button>
                <button id="button-step-done" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    <span class="ui-button-text">Готово</span>
                </button>
            {elseif $mod eq 'attr2Prod'}
                <a href="/admin/?mod=product&act=edit&task={$product->id}">
                    <button id="button-step-prev" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <span class="ui-button-text">< Назад</span>
                    </button>
                </a>
                <a href="/admin/?mod=sku&product_id={$product->id}&attr=color">
                    <button id="button-step-next" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <span class="ui-button-text">Далее ></span>
                    </button>
                </a>
            {elseif $mod eq 'sku' and $attr eq 'color'}
                <a href="/admin/?mod=attr2Prod&product_id={$product_id}">
                    <button id="button-step-prev" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <span class="ui-button-text">< Назад</span>
                    </button>
                </a>
                <a href="/admin/?mod=sku&product_id={$product_id}&attr=sizes">
                    <button id="button-step-next" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <span class="ui-button-text">Далее ></span>
                    </button>
                </a>
            {elseif $mod eq 'sku' and $attr neq 'color'}
                <a href="/admin/?mod=sku&product_id={$product_id}&attr=color">
                    <button id="button-step-prev" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <span class="ui-button-text">< Назад</span>
                    </button>
                </a>
                <a href="/admin/?mod=pImage&product_id={$product_id}">
                    <button id="button-step-next" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <span class="ui-button-text">Далее ></span>
                    </button>
                </a>
            {elseif $mod eq 'pImage'}
                <a href="/admin/?mod=sku&product_id={$product_id}&attr=sizes">
                    <button id="button-step-prev" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <span class="ui-button-text">< Назад</span>
                    </button>
                </a>
                <a href="/admin/?mod=product">
                    <button id="button-step-next" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <span class="ui-button-text">Готово</span>
                    </button>
                </a>
            {/if}
        </td>
    </tr>
</table>