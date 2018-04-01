{if $featured_productList}
	<h3 style="padding: 10px;">Лидеры продаж</h3>
	
	{foreach from=$featured_productList key=featured_key item=featured}
	{assign var=featured_id value=$featured.id}
	{if $featured_brandList.$featured_id}
		{assign var=featuredBrand value=$featured_brandList.$featured_id}
	{/if}
	{assign var=featuredCategory value=$featured_categoryList.$featured_id}

	
	<div class="featured">
			<p class="product-name"><a href="{url mod=product act=default cat_alias=$featuredCategory.alias task=$featured_id}">{if $featuredBrand}{$featuredBrand.name} {/if}{$featured.name}</a></p>
			<div class="cat-name">{$featuredCategory.name}</div>
			{if $featured_imageList.$featured_id}
				{assign var=image value=$featured_imageList.$featured_id}
				{strip}
				<a href="{url mod=product act=default cat_alias=$featuredCategory.alias task=$featured_id}">					
				<div class="img-box">					
					{if $featured.bestseller > 0}
						<span class="label70 bestseller70"></span>
					{elseif $featured.event > 0}
					<span class="label70 event70"></span>
					{elseif $featured.new > 0}
						<span class="label70 new70"></span>
					{/if}
					<img src="{getimg type=list name=pImage id=$image.id ext=$image.image}"/>
				</div></a>
				{/strip}
			{else}
				{strip}
				<a href="{url mod=product act=default cat_alias=$featuredCategory.alias task=$featured_id}">
				<div class="img-box">
					{if $featured.bestseller > 0}
						<span class="label70 bestseller70"></span>
					{elseif $featured.event > 0}
					<span class="label70 event70"></span>
					{elseif $featured.new > 0}
						<span class="label70 new70"></span>
					{/if}
                    {img src="default-icon-60.jpg"}
				</div></a>
				{/strip}
			{/if}

			{if $featured.price}<span class="new-price">{$featured.price} ГРН.</span>{/if}
			{if $featured.price < $featured.price_old}&nbsp;<span class="old-price">{$featured.price_old} ГРН.</span>{/if}
	</div>
	{/foreach}
{/if}