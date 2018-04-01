{if $article_item}
    <ul class="breadcrumbs">
        <li><a href="{url mod=home}">Главная</a></li>
        <li><a href="{url mod=article act=archive}">Статьи</a></li>
        <li>{$article_item->name}</li>
    </ul>
    <div class="product-def">
        <div class="into-box page_text">
            <h1>{$article_item->name}</h1>
            <p>{$article_item->date|date_format:"%d/%m/%Y"}</p>
            <p>{$article_item->content}</p>
        </div>
    </div>
    {include file='article/articlecomm.tpl'}
{/if}