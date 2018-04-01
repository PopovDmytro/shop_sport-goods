<td valign="top" style="min-width:700px; width:702px;">
	<div class="product-def">
		<div class="product-def-top-bg"></div>
		{if $staticpage}
			<div class="contentcurrent">
				<h1>{$staticpage->name}</h1>
				<p>{$staticpage->content}</p>
			</div>
		{/if}
		<h1>Акции</h1>
		<div class="into-box">
			{if $slider_archive}
				<ul class="newslist">
					{foreach from=$slider_archive key=key item=item}
						<li class="eventslist-li-top"></li>
						<li class="eventslist-li">
							<ul>
								<h2><a href="{url mod=slider act=default task=$item.alias}">{$item.name}</a></h2>
								<li class="ncontent">{$item.content|truncate:300:"..."}</li>
								<li class="nmore"><a href="{url mod=slider act=default task=$item.alias}">Подробнее...</a></li>
							</ul>
							<br/>
						</li>
						<li class="eventslist-li-bottom" ></li>
					{/foreach}
				</ul>
			
				<div class="clear"></div>
		        <div align="center" class="pagination">
		            {if $pager_slider}
						{foreach from=$pager_slider->pages key=key item=item}
							{if $pager_slider->currentPage == $item}
								<b>[{$item}]</b>
							{elseif $item eq 1}
								<a href="{url mod=slider act=archive}">{$item}</a>
							{else}
								<a href="{url mod=slider act=archive task=$item}">{$item}</a>
							{/if}			
						{/foreach}
					{/if}
		        </div>  
		        
			{/if}
		</div>
		<div class="product-def-bottom-bg"></div>
	</div>
</td>
