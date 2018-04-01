{if $slider}
<td valign="top" style="min-width:700px; width:702px;">
	<div class="product-def">
		<div class="product-def-top-bg"></div>
		<h1>{$slider->name}</h1>
		<div class="into-box">
			<p>{$slider->date|date_format:"%d/%m/%Y"}</p>
			<p>{$slider->content}</p>
		</div>
		<div class="product-def-bottom-bg"></div>
	</div>
</td>
{/if}