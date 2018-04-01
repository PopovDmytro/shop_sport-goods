<ul class="breadcrumbs">
    <li><a href="{url mod=home}">Главная</a></li>
    <li><a href="{url mod=user id=$user->id}">Профиль</a></li>
    <li>Изменения Пароля: <a href="{url mod=user act=default id=$userentity.id}">{$userentity.login}</a></li>
</ul>
<div class="product-def">
	{include file="user/intobox.text.tpl"}
	<div class="into-box login-link">
	{page type='getRenderedMessages' section='user'}
	{page type='getRenderedErrors' section='user'}
	
	<form action="" enctype="multipart/form-data" method="post">
		<input type="hidden" name="mod" value="{$mod}">
		<input type="hidden" name="act" value="{$act}">
		
		<div class="profile-edit-div">Текущий пароль:</div>
		<input type="password" class="profile-edit-input titlex" name="profile[curr_password]" maxlength="128" value=""><br/>
		
		<div class="profile-edit-div">Новый пароль:</div>
		<input type="password" class="profile-edit-input titlex" name="profile[password]" maxlength="128" value=""><br/>
		
		<div class="profile-edit-div">Новый пароль (Еще раз):</div>
		<input type="password" class="profile-edit-input titlex" name="profile[passconfirm]" maxlength="128" value=""><br/>
			
		<div class="profile-edit-div"><input type="submit" id="free_button"class="profile-edit-submit" name="profile[submit]" value="Отправить"></div>
	</form>
	</div>
	<div class="product-def-bottom-bg"></div>
</div>
