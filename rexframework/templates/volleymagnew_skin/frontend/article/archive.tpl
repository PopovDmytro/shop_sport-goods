<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link"><i aria-hidden="true" class="fa fa-home"></i>Главная</a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Статьи</a>
            </li>
        </ul>
    </div>
</div>

{*id="archive_list"*}

{if $article_archive}
    <div class="row">
        <div class="column small-12">
            <h1 class="section-title section-title--blue">Статьи</h1>
        </div>
    </div>

    <section class="row small-up-1 medium-up-2 large-up-3">
        {foreach from=$article_archive key=key item=item }
            <div class="article-container column column-block">
                <article class="news_slide news_flex-article">
                    <div class="img-holder text-center">
                        {if $item.icon neq ''}
                            <img src="{getimg type=main name='article' id=$item.id ext=$item.icon}" class="slide-img" />
                        {/if}
                        <a href="#" class="overlay-link">
                            {img src='main-page/eye_icon.png' class='overlay-link_icon'}
                        </a>
                    </div>
                    <div class="news_content">
                        <p class="news_slide_date">{$item.date|date_format:"%d.%m.%y "}</p>
                        {*<p class="ncontent">{$item.content|strip_tags|truncate:450:"..."}</p>*}
                        <a href="{url mod=article act=default task=$item.alias}" class="news_slide_link">{$item.name}</a>
                    </div>
                    <div class="text-right">
                        <a href="{url mod=article task=$item.alias}" class="cursive-link cursive-link--blue">Читать дальше&nbsp;&nbsp;></a>
                    </div>
                </article>
            </div>
        {/foreach}

        {*TODO need to be changed pagination html*}
        <div class="pagination round" align="center" style="visibility: visible;">
            {if $pager_article && $pager_count neq 1}
                <ul>
                    {foreach from=$pager_article->pages key=key item=item}
                        {if $pager_article->currentPage == $item}
                            <li class="pagination_div active">
                                <b>{$item}</b>
                            </li>
                        {elseif $item eq 1}
                            <li class="pagin-item">
                                <a href="{url mod=article act=archive}">{$item}</a>
                            </li>
                        {else}
                            <li class="pagin-item">
                                <a href="{url mod=article act=archive task=$item}">{$item}</a>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
            {/if}
        </div>
        {*---*}
    </section>
{/if}
