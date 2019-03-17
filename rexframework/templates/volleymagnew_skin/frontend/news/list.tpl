{if $news}
    {foreach from=$news key=key item=item name=news_list}
        <div class="my-sliders_slide news_slide">
            <div class="img-holder text-center">
                {if $item.icon neq ''}
                    <img src="{getimg type=main name='news' id=$item.id ext=$item.icon}" class='slide-img' />
                    <a href="{url mod=news act=default task=$item.alias}" class="overlay-link">
                        {img src='main-page/eye_icon.png' class='overlay-link_icon'}
                    </a>
                {/if}
            </div>
            <p class="news_slide_date">{$item.date|date_format:"%d.%m.%y "}</p>
            {*<p>{$item.content|strip_tags|truncate:100:"...":true}</p>*}
            <a href="{url mod=news act=default task=$item.alias}" class="news_slide_link">{$item.name}</a>
        </div>
    {/foreach}
{/if}