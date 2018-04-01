<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link"><i aria-hidden="true" class="fa fa-home"></i>Главная</a>
            </li>
            <li class="breadcrumbs_item active"><a href="javascript:void(0)" class="breadcrumbs_link">Новости</a></li>
        </ul>
    </div>
</div>

{if $news_archive}
    <div class="row">
        <div class="column small-12">
            <h1 class="section-title section-title--blue">Новости</h1>
        </div>
    </div>

    <section class="row small-up-1 medium-up-2 large-up-3">
        {foreach from=$news_archive key=key item=item name=news_list}
            <div class="article-container column column-block">
                <article class="news_slide news_flex-article">
                    <div class="img-holder text-center">
                        {if $item.icon neq ''}
                            <img src="{getimg type=main name='news' id=$item.id ext=$item.icon}" class="slide-img" />
                        {/if}
                        <a href="#" class="overlay-link">
                            {img src='main-page/eye_icon.png' class='overlay-link_icon'}
                        </a>
                    </div>
                    <div class="news_content">
                        <p class="news_slide_date">{$item.date|date_format:"%d.%m.%y "}</p>
                        {*<p class="ncontent">{$item.content|strip_tags|truncate:450:"..."}</p>*}
                        <a href="{url mod=news act=default task=$item.alias}" class="news_slide_link">{$item.name}</a>
                    </div>
                    <div class="text-right">
                        <a href="{url mod=news task=$item.alias}" class="cursive-link cursive-link--blue">Читать дальше&nbsp;&nbsp;></a>
                    </div>
                </article>
            </div>
        {/foreach}

        {*TODO need to be changed pagination html*}
        <div class="pagination round" align="center" style="visibility: visible;">
        {if $pager_news && $pager_count neq 1}
            <ul>
                {foreach from=$pager_news->pages key=key item=item}
                    {if $pager_news->currentPage == $item}
                        <li class="pagination_div active">
                            <b>{$item}</b>
                        </li>
                    {elseif $item eq 1}
                        <li class="pagin-item">
                            <a href="{url mod=news act=archive}">{$item}</a>
                        </li>
                    {else}
                        <li class="pagin-item">
                            <a href="{url mod=news act=archive task=$item}">{$item}</a>
                        </li>
                    {/if}
                {/foreach}
            </ul>
        {/if}
    </div>
        {*---*}
    </section>
{/if}
