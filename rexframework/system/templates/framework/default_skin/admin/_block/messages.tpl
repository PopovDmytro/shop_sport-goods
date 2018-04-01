{if $arrMessages}
	<div id="doc_messages">
	{foreach from = $arrMessages item = message}
		<b style="color:#43AA0B">{$message}</b><br/>
	{/foreach}
	</div>
{/if}