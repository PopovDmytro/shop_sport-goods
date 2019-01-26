{if $news}
    {foreach from=$news key=key item=item name=news_list}
        <div class="text-wrapper">
            <div class="text-box">
                <span class="date">{$item.date|date_format:"%d.%m.%y "}</span>
                {if $item.icon neq ''}
                    <div class="art_img">
                        <a href="{url mod=news act=default task=$item.alias}" >
                            <img src="{getimg type=main name='news' id=$item.id ext=$item.icon}" />
                        </a>
                    </div>
                {/if}
                <a href="{url mod=news act=default task=$item.alias}" class="news-title"><strong class="heading">{$item.name}</strong></a>
                <p>{$item.content|strip_tags|truncate:100:"...":true}</p>
                <div class="more">
                    <a href="{url mod=news act=default task=$item.alias}" class="more">Подробнее</a>
                </div>
            </div>
        </div>
    {/foreach}
{/if}