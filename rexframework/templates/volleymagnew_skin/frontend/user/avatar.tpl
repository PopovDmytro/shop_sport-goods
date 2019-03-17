<div class="breadcrumbs-block row">
	<div class="columns small-12">
		<ul class="breadcrumbs_list no-bullet">
			<li class="breadcrumbs_item">
				<a href="{url mod=home}" class="breadcrumbs_link">
					<i aria-hidden="true" class="fa fa-home"></i>Главная
				</a>
			</li>
			<li class="breadcrumbs_item">
				<a href="{url mod=user id=$user->id}" class="breadcrumbs_link">Профиль</a>
			</li>
			<li class="breadcrumbs_item">
				Редактирование Фотографии:
				<a href="{url mod=user act=default id=$user->id}" class="breadcrumbs_link">{$user->login}</a>
			</li>
		</ul>
	</div>
</div>

<div class="product-def">
	<div class="into-box login-link">
	    {page type='getRenderedMessages'}
	    {page type='getRenderedErrors'}
	    
	    <form action="{url mod=$mod act=$act}" id="form-avatar" enctype="multipart/form-data" method="post">
	        <input type="hidden" name="mod" value="{$mod}">
	        <input type="hidden" name="act" value="{$act}">
	        <div id="cropped-box">
	        {if $user->avatar}
		        <img src="{getimg type=main name=user id=$user->id ext=$user->avatar}"/>
	        {else}
		        {img class="user_page_ava" src="User.png"}
	        {/if}
            </div>
	        
	        <div class="avatar-download">
                <div id="avatar-show"></div>
		        <input id="cropped" type="file" name="avatar"  accept="image/*" value="">
		        <input type="submit" class="avatar-download-submit" id="free_button" name="profile[submit]" value="Отправить">
	        </div>
	    </form>
    </div>
</div>
<script type="text/javascript">
{literal}
    $('#cropped').die('change').live('change', function(){
         $.rexCrop($(this), $('#cropped-box'), 'cropped', 'width:256px;heigt:256px;');
    });
    $('.avatar-download-submit').click(function(){ 
        $('#form-avatar').attr('action','');      
        $('#form-avatar').attr('target','');      
    });
{/literal}
</script>