<div class="menucontent" id="ulmenu">
    <ul>
        <li style="font-weight: bold; margin-left: -10px"><b>Меню</b></li>
        <li {if $mod eq 'home'}class="selected"{/if}>{a href="{url mod=home}"}Home{/a}</li>
        <li {if $mod eq 'home' and $act eq 'contact'}class="selected"{/if}>{a href="{url mod=home act=contact}"}Contact{/a}</li>
        <li {if $mod eq 'staticPage' and $act eq 'about'}class="selected"{/if}>{a href="{url mod=staticPage task=about}"}About{/a}</li>
    </ul>
</div>