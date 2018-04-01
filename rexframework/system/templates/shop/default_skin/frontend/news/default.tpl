{if $news_item}
    <div class="nav_category bgcolor round">
            <p class="navigation-p">                                      
            {strip}
                Новости
            {/strip}
            </p>
    </div> 
    <div class="product-def">
        <div class="product-def-top-bg"></div>
        <div class="into-box">
            <h1>{$news_item->name}</h1>
            <p>{$news_item->date|date_format:"%d/%m/%Y"}</p>
            <p>{$news_item->content}</p>
        </div>
        <div class="product-def-bottom-bg"></div>
    </div>
{/if}