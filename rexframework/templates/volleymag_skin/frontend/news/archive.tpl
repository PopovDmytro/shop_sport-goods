    <ul class="breadcrumbs">
        <li><a href="{url mod=home}">Главная</a></li>
        <li>Новости</li>
    </ul>
    <div class="product-def">
        <div class="into-box" id="archive_list">
            {if $news_archive}
            <h1>Новости</h1>
                <ul class="newslist">
                    {foreach from=$news_archive key=key item=item}
                        <li class="newslist-li">
                            <ul>
                                <div class="art_img">
                                    {if $item.icon neq ''}
                                        <img src="{getimg type=main name='news' id=$item.id ext=$item.icon}" />
                                    {/if}
                                </div>
                                <h2><a href="{url mod=news act=default task=$item.alias}">{$item.name}</a></h2>
                                <li class="ncontent">{$item.content|strip_tags|truncate:450:"..."}</li>
                                <li class="more">
                                    <a href="{url mod=news task=$item.alias}" class="read-more">Подробнее...</a>
                                </li>
                            </ul>
                        </li>
                    {/foreach}
                </ul>
            
                <div class="clear"></div>
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
                
            {/if}
        </div>
    </div>