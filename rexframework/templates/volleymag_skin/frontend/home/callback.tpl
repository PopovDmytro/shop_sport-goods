<td valign="top" style="min-width:700px; width:702px;">
	<div class="product-def">
		<div class="product-def-top-bg"></div>
		<h1>{$staticpage->name}</h1>
		<div class="into-box">
			{$staticpage->content}
			
			{page type='getRenderedErrors' section='home'}
			{page type='getRenderedMessages' section='home'}
			<div>
				<form action="" method="POST">
					<input type="hidden" name="mod" value="home">
					<input type="hidden" name="act" value="callback">
					<table cellpadding="4" cellspacing="0" border="0" class="formcontact">
						<tr>
							<td valign="middle" class="cf_title">Имя</td>
							<td valign="middle" class="cf_inpt"><input class="inpt-text" type="text" name="contact[name]" value="{if $contact.name}{$contact.name}{/if}" maxlength="64"></td>
						</tr>
						<tr>
							<td valign="middle" class="cf_title">Телефон</td>
							<td valign="middle" class="cf_inpt"><input class="inpt-text" type="text" name="contact[phone]" value="{if $contact.phone}{$contact.phone}{/if}" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"></td>
						</tr>
						<tr>
							<td valign="middle"><img src="/captcha/" width="85" height="25"></td>
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
{include file="_block/input.phone.mask.tpl"}
<script type="text/javascript">
	$(document).ready(function(){
		initPhoneMask();
	});
</script>