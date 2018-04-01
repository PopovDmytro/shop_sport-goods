<div class="into-box page_text">
    {*if $user->id && $userEntity->id}
        {if $user->avatar}
            <img class="user_page_ava" src="{getimg type=main name=user id=$user->id ext=$user->avatar}"/>
        {else}
            {img class="user_page_ava" src="User.png"}
        {/if}
    {else}
        {if $user->avatar}
            <img src="{getimg type=main name=user id=$user->id ext=$user->avatar}" alt="{$user->login}" />
        {elseif $userEntity->avatar}
             <img src="{getimg type=main name=user id=$userEntity->id ext=$userEntity->avatar}" alt="{$userEntity->login}" />
        {else}
            <img src="{'RexPath.image.link'|config}avatar/default.jpg" alt="{$user->login}" />
        {/if}
    {/if*}
    {if $mod == 'order'}
        {$order_def = 'active-'}
    {elseif $mod == 'user'}
        {if $act == 'password'}
            {$user_pass = 'active-'}
        {elseif $act == 'main'}
            {$user_main = 'active-'}
        {else}
            {$order_def = ''}
            {$user_main = ''}
            {$user_pass = ''}
        {/if}
    {/if}
    <div class="user_page_menu">
        <a href="{url mod=order act=default}" class="{$order_def}tab" data-tab="{$order_def}tab-1"><span>Заказы</span></a>
        <a href="{url mod=user act=main}" class="{$user_main}tab" data-tab="tab-2"><span>Редактировать настройки</span></a>
        <a href="{url mod=user act=password}" class="{$user_pass}tab" data-tab="{$user_pass}tab-3"><span>Изменить пароль</span></a>
        {*<a href="{url mod=user act=avatar}" id="free_button">Аватар</a>*}
    </div>
</div>