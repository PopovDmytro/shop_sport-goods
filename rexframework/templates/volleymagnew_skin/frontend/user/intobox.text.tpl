<div class="into-box page_text">
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

    <section>
        <div class="row">
            <div class="column small-12">
                <h1 class="section-title section-title--blue">Личный кабинет</h1>
            </div>
        </div>
    </section>

    <ul id="profile-tabs" class="tabs">
        <li class="tabs-title">
            <a href="{url mod=order act=default}" class="{$order_def}tab" data-tab="{$order_def}tab-1"><span>Заказы</span></a>
        </li>
        <li class="tabs-title">
            <a href="{url mod=user act=main}" class="{$user_main}tab" data-tab="tab-2"><span>Редактировать настройки</span></a>
        </li>
        <li class="tabs-title">
            <a href="{url mod=user act=password}" class="{$user_pass}tab" data-tab="{$user_pass}tab-3"><span>Изменить пароль</span></a>
        </li>
    </ul>
    {*<a href="{url mod=user act=avatar}" id="free_button">Аватар</a>*}
</div>