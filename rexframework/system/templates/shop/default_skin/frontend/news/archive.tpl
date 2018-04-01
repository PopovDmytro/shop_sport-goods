	<div class="nav_category round">
        <p class="navigation-p"> 
            Новости
        </p>
     </div>
    <div class="product-def">
		<div class="into-box">
			{if $news_archive}
				<ul class="newslist">
					{foreach from=$news_archive key=key item=item}
						<li class="newslist-li-top"></li>
						<li class="newslist-li">
							<ul>
								<h2><a href="{url mod=news act=default task=$item.alias}">{$item.name}</a></h2>
								<li class="ncontent">{$item.content|strip_tags|truncate:450:"..."}</li>
								<li class="nmore">
                                    <a href="{url mod=news task=$item.alias}" class="read-moor">Подробнее...</a>
                                </li>
							</ul>
							<br/>
						</li>
						<li class="newslist-li-bottom"></li>
					{/foreach}
				</ul>
			
				<div class="clear"></div>
		        <div class="pagination round" align="center" style="visibility: visible;">
		            {if $pager_news}
						{foreach from=$pager_news->pages key=key item=item}
							{if $pager_news->currentPage == $item}
								<div class="pagination_div">
                                    <b>{$item}</b>
                                </div>
							{elseif $item eq 1}
								<a href="{url mod=news act=archive}">{$item}</a>
							{else}
								<a href="{url mod=news act=archive task=$item}">{$item}</a>
							{/if}			
						{/foreach}
					{/if}
		        </div>  
				
			{/if}
		</div>
		<div class="product-def-bottom-bg"></div>
	</div>