<div style="float: left; width: 90px; overflow: hidden;">
	{$attribute->name}
</div>

<div style="float: left;">
	<input type="radio" name="attribute[{$attribute->id}]" value="1" {if $attr2prod->value}checked{/if}>Да<br/>
	<input type="radio" name="attribute[{$attribute->id}]" value="0" {if !$attr2prod->value}checked{/if}>Нет
</div>
<div class="clear5"></div>