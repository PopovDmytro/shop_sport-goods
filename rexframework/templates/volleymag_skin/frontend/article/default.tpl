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
            {if $article_item->youtube}
            <p style="text-align: center">
                <iframe width="100%" height="400" src="{$article_item->youtube}" frameborder="0"
                        allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </p>
            {/if}
        </div>
    </div>
    {include file='article/articlecomm.tpl'}
{/if}