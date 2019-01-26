<div>
	<div style="padding-left: 10px;padding-right:10px;">
		<div class="product-def">
			<div class="product-def-top-bg"></div>
			<h1>{$staticpage->name}</h1>
			<div class="into-box">
				{$staticpage->content}
			</div>
            {if $staticpage->youtube}
				<p style="text-align: center">
					<iframe width="100%" height="400" src="{$staticpage->youtube}" frameborder="0"
							allow="autoplay; encrypted-media" allowfullscreen></iframe>
				</p>
            {/if}
			<div class="product-def-bottom-bg"></div>
		</div>
	</div>
</div>