{if !$user->id && !$who_i->id}
	<form action="{url mod=user act=login}" method="post">
		<input type="hidden" name="user[request]" value="{$CURRENT_PAGE}">
		{literal}<input class="top-input" id="user_email" type="text" name="user[email]" class="text" onblur="javascript: if (this.value=='') {this.value='E-mail';}" onfocus="javascript: if (this.value=='' || this.value=='E-mail') {this.value='';}" value="E-mail"/>{/literal}
		{literal}<input id="password" type="password" name="user[password]" class="top-input" onblur="javascript: if (this.value=='') {this.value='Password';}" onfocus="javascript: if (this.value=='' || this.value=='Password') {this.value='';}"  value="Password"/>{/literal}
		<input type="submit" class="enter" name="user[submitlogin]" value="Войти">
		<a href="{url mod=user act=registration}" class="menub">Регистрация</a>
		<a href="{url mod=user act=forgot}" class="menub">Забыли пароль?</a>
	</form>
{else}	
	<table width="200px" cellpadding="0" cellspacing="4">
		<tr align="center">
            <td colspan="2" align="center"><strong><a class="menub" href="{url mod=user act=default id=$who_i->id}">{$who_i->login}</a></strong></td>
         </tr>
         <tr align="center">   
			<td><a class="menub" href="{url mod=order act=default}"><b>Заказы</b></a></td>
			<td><a class="menub" href="{url mod=user act=logout}"><b>Выход</b></a></td>
		</tr>
	</table>
{/if}
