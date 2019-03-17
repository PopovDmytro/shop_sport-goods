<div class="breadcrumbs-block row">
	<div class="columns small-12">
		<ul class="breadcrumbs_list no-bullet">
			<li class="breadcrumbs_item">
				<a href="{url mod=home}" class="breadcrumbs_link">
					<i aria-hidden="true" class="fa fa-home"></i>Главная
				</a>
			</li>
			<li class="breadcrumbs_item active">
				<a href="javascript:void(0)" class="breadcrumbs_link">Профиль</a>
			</li>
			<li class="breadcrumbs_item active">
				<a href="javascript:void(0)" class="breadcrumbs_link">Изменения Пароля:&nbsp;{$userentity.email}</a>
			</li>
		</ul>
	</div>
</div>

<section class="profile-tabs">
	<div class="row align-center">
		<div class="columns small-12 large-8">
			<div class="profile_tabs-container product-def">
				{include file="user/intobox.text.tpl"}
				<div class="into-box login-link">
					{page type='getRenderedMessages' section='user'}
					{page type='getRenderedErrors' section='user'}
					<br />

					<form action="" enctype="multipart/form-data" method="post">
						<input type="hidden" name="mod" value="{$mod}">
						<input type="hidden" name="act" value="{$act}">

						<div class="row small-up-1 py-1">
							<div class="input-holder column">
								<input placeholder="Текущий пароль" type="password" class="profile-edit-input titlex" name="profile[curr_password]" maxlength="128" value="">
							</div>

							<div class="input-holder column">
								<input placeholder="Новый пароль" type="password" class="profile-edit-input titlex" name="profile[password]" maxlength="128" value="">
							</div>

							<div class="input-holder column">
								<input placeholder="Новый пароль (Еще раз)" type="password" class="profile-edit-input titlex" name="profile[passconfirm]" maxlength="128" value="">
							</div>

							<div class="profile-edit-div column">
								<button type="submit" id="free_button"class="profile-edit-submit btn btn--green" name="profile[submit]" value="Отправить">Отправить</button>
							</div>
						</div>
					</form>
				</div>
				<div class="product-def-bottom-bg"></div>
			</div>
		</div>
	</div>
</section>