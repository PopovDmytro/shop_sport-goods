	<div class="product-def">
        <div class="nav_category bgcolor round">
            <p class="navigation-p">                                      
            {strip}
                Профиль пользователя
            {/strip}
            </p>
        </div>
        <div class="into-box">
            <h2><a href="{url mod=user act=default id=$user->id}">{$user->login}</a>{if $user->name} ({$user->name}){/if}</h2>
            <br/>
                              
            {if $who_i->id eq $user->id}
                {if $user->avatar neq 'default'}
                    <img src="{getimg type=avatar name=user id=$user->id ext=$user->avatar}"/>
                {/if}
                <br/>
                <a href="{url mod=order act=default}">Заказы</a><br/>
                <a href="{url mod=user act=main}">Редактировать настройки</a><br/>
                <a href="{url mod=user act=password}">Изменить пароль</a><br/>
                <a href="{url mod=user act=avatar}">Аватар</a><br/>
            {else}
            <br/>
                {if $user->avatar neq 'default'}
                    <img src="{getimg type=avatar name=user id=$user->id ext=$user->avatar}" alt="{$user->login}" />
                {else}
                    <img src="{'RexPath.image.link'|config}avatar/default.jpg" alt="{$user->login}" />
                {/if}
            {/if}
        </div>
        <div class="product-def-bottom-bg"></div>
    </div>