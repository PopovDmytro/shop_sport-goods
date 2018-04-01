<div class="content" id="ulmenu">
    <ul>
        <li style="font-weight: bold; margin-left: -10px"><b>маркет</b></li>
        <li {if $mod eq 'product'}class="selected"{/if}>{a href="{url mod=product}"}Продукция{/a}</li>
        <li {if $mod eq 'pCatalog'}class="selected"{/if}>{a href="{url mod=pCatalog}"}Каталог{/a}</li>
        <li {if $mod eq 'attribute'}class="selected"{/if}>{a href="{url mod=attribute}"}Атрибуты{/a}</li>
        <li {if $mod eq 'brand'}class="selected"{/if}>{a href="{url mod=brand}"}Бренды{/a}</li>
        <li {if $mod eq 'order'}class="selected"{/if}>{a href="{url mod=order}"}Заказы{/a}</li>

        <li style="font-weight: bold; margin-left: -10px"><b>наполнение</b></li>
        <li {if $mod eq 'staticPage'}class="selected"{/if}>{a href="{url mod=staticPage}"}Страницы{/a}</li>
        <li {if $mod eq 'news'}class="selected"{/if}>{a href="{url mod=news}"}Новости{/a}</li>
        <li {if $mod eq 'slider'}class="selected"{/if}>{a href="{url mod=slider}"}Слайдер{/a}</li>
        <li {if $mod eq 'comment'}class="selected"{/if}>{a href="{url mod=comment}"}Комментарии{/a}</li>

        <li style="font-weight: bold; margin-left: -10px"><b>системные</b></li>
        <li {if $mod eq 'user'}class="selected"{/if}>{a href="{url mod=user}"}Пользователи{/a}</li>
        <li {if $mod eq 'settings'}class="selected"{/if}>{a href="{url mod=settings}"}Настройки{/a}</li>
        <li {if $mod eq 'home' and $act eq 'mailing'}class="selected"{/if}>{a href="{url mod=home act=mailing}"}Рассылка{/a}</li>
    </ul>
</div>