{if $article}
    {foreach from=$article key=key item=item name=article_list}
        <div class="text-wrapper">
            <div class="text-box">
                <span class="date">{$item.date|date_format:"%d.%m.%y "}</span>
                <strong class="heading">{$item.name}</strong>
                <p>{$item.content|strip_tags|truncate:100:"...":true}</p>
                <div class="more">
                    <a href="{url mod=article act=default task=$item.alias}" class="more">Подробнее</a>
                </div>
            </div>
        </div>
    {/foreach}
{/if}