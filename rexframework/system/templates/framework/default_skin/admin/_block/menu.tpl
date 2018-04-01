<div class="content" id="ulmenu">
    <ul>
        <li style="font-weight: bold; margin-left: -10px"><b>системные</b></li>
        <li {if $mod eq 'staticPage'}class="selected"{/if}>{a href="{url mod=staticPage}"}Страницы{/a}</li>
        <li {if $mod eq 'user'}class="selected"{/if}>{a href="{url mod=user}"}Пользователи{/a}</li>
        <li {if $mod eq 'settings'}class="selected"{/if}>{a href="{url mod=settings}"}Настройки{/a}</li>
        <li {if $mod eq 'home' and $act eq 'mailing'}class="selected"{/if}>{a href="{url mod=home act=mailing}"}Рассылка{/a}</li>
    </ul>
</div>