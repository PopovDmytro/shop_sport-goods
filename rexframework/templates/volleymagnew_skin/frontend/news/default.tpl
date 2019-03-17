{if $news_item}
    <div class="breadcrumbs-block row">
        <div class="columns small-12">
            <ul class="breadcrumbs_list no-bullet">
                <li class="breadcrumbs_item">
                    <a href="{url mod=home}" class="breadcrumbs_link"><i aria-hidden="true" class="fa fa-home"></i>Главная</a>
                </li>
                <li class="breadcrumbs_item"><a href="{url mod=news act=archive}" class="breadcrumbs_link">Новости</a></li>
                <li class="breadcrumbs_item active"><a href="javascript:void(0)" class="breadcrumbs_link">{$news_item->name}</a></li>
            </ul>
        </div>
    </div>
    <div class="product-def row small-up-12">
        <section class="about_main-content column column-block">
            <div class="into-box page_text">
                <h1 class="section-title section-title--blue">{$news_item->name}</h1>
                <p>{$news_item->date|date_format:"%d/%m/%Y"}</p>
                <p>{$news_item->content}</p>
            </div>
            {include file='news/newscomm.tpl'}
        </section>
    </div>
{/if}


