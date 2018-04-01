	<div class="product-def">
		<div class="nav_category bgcolor round">
            <p class="navigation-p">                                      
            {strip}
                Изменения Пароля
            {/strip}
            </p>
        </div>
		<div class="into-box login-link">
            <h1>Изменения Пароля</h1>
        
        <a href="{url mod=user act=default id=$userentity.id}/">{$userentity.login}</a>
        <br />
        <br />
		{page type='getRenderedMessages' section='user'}
		{page type='getRenderedErrors' section='user'}
		
		<form action="" enctype="multipart/form-data" method="post">
			<input type="hidden" name="mod" value="{$mod}">
			<input type="hidden" name="act" value="{$act}">
			
			<div class="profile-edit-div">Текущий пароль:</div>
			<input type="password" class="profile-edit-input" name="profile[curr_password]" maxlength="128" value=""><br/>
			
			<div class="profile-edit-div">Новый пароль:</div>
			<input type="password" class="profile-edit-input" name="profile[password]" maxlength="128" value=""><br/>
			
			<div class="profile-edit-div">Новый пароль (Еще раз):</div>
			<input type="password" class="profile-edit-input" name="profile[passconfirm]" maxlength="128" value=""><br/>
				
			<div class="profile-edit-div"><input type="submit" class="profile-edit-submit" name="profile[submit]" value="Отправить"></div>
		</form>
		</div>
		<div class="product-def-bottom-bg"></div>
	</div>
