{if $article_item}
    <div class="breadcrumbs-block row">
        <div class="columns small-12">
            <ul class="breadcrumbs_list no-bullet">
                <li class="breadcrumbs_item">
                    <a href="{url mod=home}" class="breadcrumbs_link"><i aria-hidden="true" class="fa fa-home"></i>Главная</a>
                </li>
                <li class="breadcrumbs_item"><a href="{url mod=article act=archive}" class="breadcrumbs_link">Статьи</a></li>
                <li class="breadcrumbs_item active"><a href="javascript:void(0)" class="breadcrumbs_link">{$article_item->name}</a></li>
            </ul>
        </div>
    </div>

    <div class="product-def">
        <div class="into-box page_text">
            <h1>{$article_item->name}</h1>
            <p>{$article_item->date|date_format:"%d/%m/%Y"}</p>
            <p>{$article_item->content}</p>
        </div>
    </div>
    {include file='article/articlecomm.tpl'}
{/if}