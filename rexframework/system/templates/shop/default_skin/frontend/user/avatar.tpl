<div class="product-def">
    <div class="nav_category bgcolor round">
            <p class="navigation-p">                                      
            {strip}
                Редактирование Фотографии
            {/strip}
            </p>
        </div>
	<div class="into-box login-link">
    <h1>Редактирование Фотографии</h1>
    <a href="{url mod=user act=default id=$user->id}">{$user->login}</a>
    <br />
	<br />
	{page type='getRenderedMessages'}
	{page type='getRenderedErrors'}
	
	<form action="" enctype="multipart/form-data" method="post">
	<input type="hidden" name="mod" value="{$mod}">
	<input type="hidden" name="act" value="{$act}">
	
	{if $user->avatar neq 'default'}
		<img src="{getimg type=main name=user id=$user->id ext=$user->avatar}"/>
	{else}
		<img src="{'RexPath.image.link'|config}avatar/default.jpg" />
	{/if}
	
	<div class="avatar-download">
        <div id="avatar-show"></div>
			<input type="file" name="avatar" accept="image/*" value="">
		<input type="submit" class="avatar-download-submit" name="profile[submit]" value="Отправить">
	</div>
	
	</form>
</div>

<div class="product-def-bottom-bg"></div>
</div>
