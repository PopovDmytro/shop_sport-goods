{if $show_form}
	<form action="" method="POST" enctype="multipart/form-data">
		Выберите прайс:<br />
		<input type="file" name="price" />
		<br /><br />
		<input type="submit" value="Загрузить" />
	</form>
{else}
{/if}