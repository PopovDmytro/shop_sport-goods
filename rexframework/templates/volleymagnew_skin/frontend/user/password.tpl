<div class="breadcrumbs-block row">
	<div class="columns small-12">
		<ul class="breadcrumbs_list no-bullet">
			<li class="breadcrumbs_item">
				<a href="{url mod=home}" class="breadcrumbs_link">
					<i aria-hidden="true" class="fa fa-home"></i>Главная
				</a>
			</li>
			<li class="breadcrumbs_item active">
				<a href="{url mod=user id=$user->id}" class="breadcrumbs_link">Профиль</a>
			</li>
			<li class="breadcrumbs_item active">
				Изменения Пароля:
				<a href="{url mod=user act=default id=$userentity.id}" class="breadcrumbs_link">{$userentity.login}</a>
			</li>
		</ul>
	</div>
</div>

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
