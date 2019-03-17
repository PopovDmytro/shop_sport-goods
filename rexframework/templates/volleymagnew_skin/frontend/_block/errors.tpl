{if $arrErrors}
	<div id="doc_errors">
	{foreach from = $arrErrors item = error}
		<b style="color:#FF0000">{$error}</b><br/>
	{/foreach}
	</div>
{/if}