{if $news_item}
    <ul class="breadcrumbs">
        <li><a href="{url mod=home}">Главная</a></li>
        <li><a href="{url mod=news act=archive}">Новости</a></li>
        <li>{$news_item->name}</li>
    </ul>
    <div class="product-def">
        <div class="into-box page_text">
            <h1>{$news_item->name}</h1>
            <p>{$news_item->date|date_format:"%d/%m/%Y"}</p>
            <p>{$news_item->content}</p>
        </div>
    </div>
    {include file='news/newscomm.tpl'}
{/if}