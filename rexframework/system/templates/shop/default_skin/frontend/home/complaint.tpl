<td valign="top" style="min-width:700px; width:702px;">
	<div class="product-def">
		<div class="product-def-top-bg"></div>
		<h1>{$staticpage->name}</h1>
		<div class="into-box">
			{$staticpage->content}
		</div>
		<h1>Форма обратной связи</h1>
		<div class="into-box">
			{page type='getRenderedErrors' section='home'}
			{page type='getRenderedMessages' section='home'}
			<div>
				<form action="" method="POST">
					<input type="hidden" name="mod" value="home">
					<input type="hidden" name="act" value="contact">
					<table cellpadding="4" cellspacing="0" border="0" class="formcontact">
						<tr>
							<td valign="middle" class="cf_title">Имя</td>
							<td valign="middle" class="cf_inpt"><input class="inpt-text" type="text" name="contact[name]" value="{if $contact.name}{$contact.name}{/if}" maxlength="64"></td>
						</tr>
						<tr>
							<td valign="middle" class="cf_title">Email</td>
							<td valign="middle" class="cf_inpt"><input class="inpt-text" type="text" name="contact[email]" value="{if $contact.email}{$contact.email}{/if}" maxlength="64"></td>
						</tr>
						<tr>
							<td valign="top" class="cf_title">Сообщение</td>
							<td valign="top" class="cf_inpt"><textarea class="textarea-text" name="contact[text]">{if $contact.text}{$contact.text}{/if}</textarea></td>
						</tr>
						<tr>
							<td valign="middle"><img src="/index.php?mod=home&act=captcha" width="85" height="25"></td>
							<td valign="middle" class="cf_inpt_cpch"><input class="inpt-cpch" type="text" name="contact[code]" value=""></td>
						</tr>
						<tr>
							<td valign="top" colspan="2" class="cf_inpt_bttn"><input type="submit" name="contact[submit]" value="Отправить сообщение" /></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
	<div class="product-def-bottom-bg"></div>
	</div>
</td>