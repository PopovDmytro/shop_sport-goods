{if $slider_list}
	<ul class="newslist-list" style="background-color: #E2F7CF;"> 
		{foreach from=$slider_list key=key item=item}
			<li class="ntitle"><a href="{url mod=slider act=default task=$item.alias}">{$item.name}</a></li>
		{/foreach}
		<li class="news-archive-link"><a href="{url mod=slider act=archive}">Все aкции</a></li>
        <br/>
	</ul>
{/if}