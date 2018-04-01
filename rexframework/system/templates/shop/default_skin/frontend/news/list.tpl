{if $news}
    <ul class="submenu">
        {foreach from=$news key=key item=item name=news_list}
            <li class="lmenu">
                {*<a class="news-name" href="{url mod=news act=default task=$item.alias}"> {$item.name} </a> *}
                <div class="date">{$item.date|date_format:"%d.%m.%y "}</div>
                <p>{$item.content|strip_tags|truncate:60:"...":true}</p>
                <a class="read-moor" href="{url mod=news act=default task=$item.alias}">Подробнее</a>
            </li>
            <div class="dotted"></div>
        {/foreach}
        <a class="view-all" href="{url mod=news act=archive}">Все новости</a>
    </ul>
{/if}