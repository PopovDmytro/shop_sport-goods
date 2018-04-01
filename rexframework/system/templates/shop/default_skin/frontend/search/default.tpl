<div class="block">
	{if $staticpage}
		<div class="contentcurrent">
			<h2>{$staticpage->name}</h2>
			<p>{$staticpage->content}</p>
		</div>
	{/if}

	<ul class="newslist">
	{if $search}
		{foreach from=$search key=key item=item}
			{if $item.search_type eq 'staticpage'}
				{if $item.search_alias eq 'contact'}
					<li>
						<ul>
							<li class="ntitle"><a href="http://{$DOMAIN}/{$item.search_alias}/">{$item.search_name}</a></li>
							<li class="ncontent">{$item.search_content_prev}</li>
							<li class="nmore"><a href="http://{$DOMAIN}/{$item.search_alias}/">Подробнее...</a></li>
						</ul>
					</li>
				{else}
					<li>
						<ul>
							<li class="ntitle"><a href="http://{$DOMAIN}/{$item.search_alias}.html">{$item.search_name}</a></li>
							<li class="ncontent">{$item.search_content_prev}</li>
							<li class="nmore"><a href="http://{$DOMAIN}/{$item.search_alias}.html">Подробнее...</a></li>
						</ul>
					</li>
				{/if}
			{elseif $item.search_type eq 'portfolio'}
					<li>
						<ul>
							<li class="ntitle"><a href="http://{$DOMAIN}/portfolio/{$item.search_alias}.htm">{$item.search_name}</a></li>
							<li class="ncontent">{$item.search_content_prev}</li>
							<li class="nmore"><a href="http://{$DOMAIN}/portfolio/{$item.search_alias}.htm">Подробнее...</a></li>
						</ul>
					</li>
			{/if}
		{/foreach}
		
		<div align="center">
        {if $pager_search}
            {foreach from=$pager_search->pages key=key item=item}
            {if $pager_search->currentPage == $item}
                [{$item}]
            {elseif $item eq 1}
                <a href="{url mod=pCatalog act=search}">{$item}</a>
            {else}
                <a href="{url mod=pCatalog act=search}/">{$item}</a>
            {/if}
            {/foreach}
        {/if}
        </div>
	{/if}
	</ul>
	
</div>