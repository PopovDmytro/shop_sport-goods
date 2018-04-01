{if $comments}
	{foreach from=$comments key=key item=item}
		<li><a href="{url mod=product act=default cat_alias=$item task=product_id}{*http://{$DOMAIN}/product/{$item.product_id}.html*}#comments">{$item.login}</a><br/>{$item.content|truncate:80}</li>
	{/foreach}
{/if}