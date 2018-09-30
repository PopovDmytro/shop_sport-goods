<div class="header_categories_menu categoriesMenuWrapper hide">
    <ul data-accordion-menu data-multi-open="false" class="vertical menu accordion-menu">
        {foreach from=$treeList item=zeroLevel name=catList}
            {if $zeroLevel.level eq 0}
                <li class="header_category-item {if $zeroLevel.cat_list}has-submenu{/if}">
                <a class="header_collapse-anchor" id="menu-n{$zeroLevel.id}" href="{url mod=pCatalog act=default task=$zeroLevel.alias}">
                    {img src="/categories-icons/{$zeroLevel.alias}" class="header_category-icon"}
                    {img src="/categories-icons/{$zeroLevel.alias}" class="header_category-icon header_category-icon--hover"}
                    <span>{$zeroLevel.name}</span>
                    {if $zeroLevel.cat_list}
                        {img src='/categories-icons/arrow-icon.png' class="category_collapse-icon"}
                        {img src='/categories-icons/arrow-icon-hover.png' class="category_collapse-icon category_collapse-icon--hover"}
                    {/if}
                </a>
            {/if}
            {if $zeroLevel.cat_list}
                <ul class="menu vertical nested header_subcategory">
                    {foreach from=$zeroLevel.cat_list item=firstLevel name=foo}
                        <li {if $firstLevel.cat_list.level2} class=""{/if}>
                            {if $firstLevel.cat_list.level2}<span class="toggler"> + </span>{/if}
                            <a id="menu-n{$firstLevel.id}" class="header_inner-anchor" href="{url mod=pCatalog act=default task=$firstLevel.alias}">
                                <span>{$firstLevel.name|upper}</span>
                            </a>
                            {if $firstLevel.cat_list.level2}
                                <ul class="menu vertical nested header_subcategory">
                                    {foreach from=$firstLevel.cat_list.level2 item=secondLevel}
                                        <li>
                                            <a id="menu-n{$secondLevel.id}" class="header_inner-anchor" href="{url mod=pCatalog act=default task=$secondLevel.alias}">
                                                <span>{$secondLevel.name|upper}</span>
                                            </a>
                                        </li>
                                    {/foreach}
                                </ul>
                            {/if}
                        </li>
                    {/foreach}
                </ul>
            {/if}
            {if $zeroLevel.level eq 0}
                </li>
            {/if}
        {/foreach}
    </ul>
</div>
{*<div class="sidebarleft-box-content sidebarleft-menu-wrapper">
    <div class="sublevel-list"></div>
    <div class="sidebarleft-menu-divide"></div>
    <div class="zerolevel-list">
    <div class="dotted"></div>
        {foreach from=$treeList item=zeroLevel name=catList}
            {if $zeroLevel.level eq 0}  
              
                <div class="m_item">
                  <a href="{url mod=pCatalog act=default task=$zeroLevel.alias}">
                        {if $zeroLevel.icon}
                            <img src="{getimg type=list name=pCatalog id=$zeroLevel.id ext=$zeroLevel.icon}" />
                        {/if}
                        <span>                        
                           {$zeroLevel.name}
                        </span>
                        <div class="zerolevel-one-content">
                            {$zeroLevel.content|strip_tags}
                        </div>
                     </a>
                    {if $zeroLevel.cat_list}
                        <div class="sublevel-list-hidden">
                            {foreach from=$zeroLevel.cat_list item=firstLevel}
                                <div class="sublevel-one">
                                     <a href="{url mod=pCatalog act=default task=$firstLevel.alias}">
                                        {if $firstLevel.icon}
                                            <img src="{getimg type=list name=pCatalog id=$firstLevel.id ext=$firstLevel.icon}" />
                                        {/if}
                                        <div class="sublevel-one-name">                        
                                           {$firstLevel.name}
                                        </div>
                                        <div class="sublevel-one-content">
                                            {$firstLevel.content|strip_tags}
                                        </div>
                                    </a>
                                </div>  
                                <div class="clear"></div>  
                            {/foreach}
                        </div>
                    {/if} 
                </div>
               
            {/if}
            <div class="clear"></div>
            {if !$smarty.foreach.catList.last} 
                <div class="dotted"></div>
            {/if}  
        {/foreach}
            <div class="dotted"></div>
                <a href="{url mod=pCatalog act=full}" class="all-cat">Все категории</a>
    </div>
</div>

<script>
    {literal}
    
        $('.m_item').mouseenter(function(){
            if (!$(this).hasClass('m_item_selected')) {
                resetSelectedMenu();
                var topArrow = Math.round(($(this).innerHeight() - 14)/2);
                $(this).addClass('m_item_selected').append('<div class="zerolevel-arrow-right" style="top:'+topArrow+'px;opacity:0;"></div>');
                $('.zerolevel-arrow-right').animate({
                    opacity: 1    
                }, 800);
            }
            $(this).parents('.sidebarleft-menu-wrapper').find('.sublevel-list').html($(this).find('.sublevel-list-hidden').html());
            $(this).parents('.sidebarleft-menu-wrapper').find('.sidebarleft-menu-divide').show();
            if (!$(this).parents('.sidebarleft-menu-wrapper').hasClass('sublevel-list-open')) {
                $(this).parents('.sidebarleft-menu-wrapper').addClass('sublevel-list-open').animate({
                    width: '430px'     
                }, 500);
            }
        });
        
        $('.sidebarleft-menu-wrapper').mouseleave(function(){
            resetSelectedMenu();
            $(this).find('.sidebarleft-menu-divide').hide();
            $(this).removeClass('sublevel-list-open').stop(true).find('.sublevel-list').html('');
            $(this).animate({
                width: '188px'     
            }, 500);
        });
        
        function resetSelectedMenu()
        {
            $('.zerolevel-arrow-right').stop(true).remove();
            $('.m_item_selected').removeClass('m_item_selected');    
        }
    
    {/literal}
</script>*}

{*

    <ul data-accordion-menu data-multi-open="false" class="vertical menu accordion-menu">
        <li class="header_category-item">
            <a href="/" class="header_collapse-anchor">Кроссовки
                для волейбола<img src="assets/img/categories-icons/sneaker_icon.png"
                                  class="header_category-icon"><img
                        src="assets/img/categories-icons/sneaker_icon-hover.png"
                        class="header_category-icon header_category-icon--hover"></a>
        </li>

        <li class="header_category-item"><a href="/" class="header_collapse-anchor">Мужская игровая форма
                <img src="assets/img/categories-icons/man_icon.png" class="header_category-icon">
                <img src="assets/img/categories-icons/man_icon-hover.png" class="header_category-icon header_category-icon--hover">
                <img src="assets/img/categories-icons/arrow-icon.png" class="category_collapse-icon">
                <img src="assets/img/categories-icons/arrow-icon-hover.png" class="category_collapse-icon category_collapse-icon--hover">
            </a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Комплекты</a></li>
                <li><a href="#" class="header_inner-anchor">Футболки</a></li>
                <li><a href="#" class="header_inner-anchor">Шорты</a></li>
            </ul>
        </li>
        <li class="header_category-item"><a href="/" class="header_collapse-anchor">Женская
                игровая форма<img src="assets/img/categories-icons/woman_icon.png"
                                  class="header_category-icon"><img
                        src="assets/img/categories-icons/woman_icon-hover.png"
                        class="header_category-icon header_category-icon--hover"><img
                        src="assets/img/categories-icons/arrow-icon.png"
                        class="category_collapse-icon"><img
                        src="assets/img/categories-icons/arrow-icon-hover.png"
                        class="category_collapse-icon category_collapse-icon--hover"></a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Комплекты</a></li>
                <li><a href="#" class="header_inner-anchor">Футболки</a></li>
                <li><a href="#" class="header_inner-anchor">Шорты</a></li>
            </ul>
        </li>
        <li class="header_category-item">
            <a href="/"
                                            class="header_collapse-anchor">Наколенники<img
                        src="assets/img/categories-icons/knee_icon.png"
                        class="header_category-icon"><img
                        src="assets/img/categories-icons/knee_icon-hover.png"
                        class="header_category-icon header_category-icon--hover"></a>
        </li>
        <li class="header_category-item"><a href="/" class="header_collapse-anchor">Мячи<img
                        src="assets/img/categories-icons/ball.png" class="header_category-icon"><img
                        src="assets/img/categories-icons/ball-hover.png"
                        class="header_category-icon header_category-icon--hover"><img
                        src="assets/img/categories-icons/arrow-icon.png" class="category_collapse-icon"><img
                        src="assets/img/categories-icons/arrow-icon-hover.png"
                        class="category_collapse-icon category_collapse-icon--hover"></a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Волейбольные</a></li>
                <li><a href="#" class="header_inner-anchor">Баскетбольные</a></li>
                <li><a href="#" class="header_inner-anchor">Футбольные</a></li>
                <li><a href="#" class="header_inner-anchor">Разное</a></li>
            </ul>
        </li>
        <li class="header_category-item"><a href="/" class="header_collapse-anchor">Одежда<img
                        src="assets/img/categories-icons/clothes_icon.png" class="header_category-icon"><img
                        src="assets/img/categories-icons/clothes_icon-hover.png"
                        class="header_category-icon header_category-icon--hover"><img
                        src="assets/img/categories-icons/arrow-icon.png" class="category_collapse-icon"><img
                        src="assets/img/categories-icons/arrow-icon-hover.png"
                        class="category_collapse-icon category_collapse-icon--hover"></a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Футболки</a></li>
                <li><a href="#" class="header_inner-anchor">Майки и топы</a></li>
                <li><a href="#" class="header_inner-anchor">Шорты</a></li>
                <li><a href="#" class="header_inner-anchor">Пляжные шорты</a></li>
                <li><a href="#" class="header_inner-anchor">Носки гольфы и гетры</a></li>
                <li><a href="#" class="header_inner-anchor">Спортивные костюмы</a></li>
                <li><a href="#" class="header_inner-anchor">Спотривные кофты и толстовки</a>
                </li>
                <li><a href="#" class="header_inner-anchor">Штаны, Тайтсы</a></li>
                <li><a href="#" class="header_inner-anchor">Термобелье</a></li>
                <li><a href="#" class="header_inner-anchor">Куртки и ветровки</a></li>
                <li><a href="#" class="header_inner-anchor">Кепки и шапки</a></li>
                <li><a href="#" class="header_inner-anchor">Перчатки</a></li>
            </ul>
        </li>
        <li class="header_category-item"><a href="/" class="header_collapse-anchor">Обувь<img
                        src="assets/img/categories-icons/shoes_icon.png"
                        class="header_category-icon"><img
                        src="assets/img/categories-icons/shoes_icon-hover.png"
                        class="header_category-icon header_category-icon--hover"><img
                        src="assets/img/categories-icons/arrow-icon.png" class="category_collapse-icon"><img
                        src="assets/img/categories-icons/arrow-icon-hover.png"
                        class="category_collapse-icon category_collapse-icon--hover"></a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Кроссовки для бега</a></li>
                <li><a href="#" class="header_inner-anchor">Обувь для футбола</a></li>
                <li><a href="#" class="header_inner-anchor">Обувь для тениса</a></li>
                <li><a href="#" class="header_inner-anchor">Спортивный стиль</a></li>
                <li><a href="#" class="header_inner-anchor">Тапочки и вьетнамки</a></li>
                <li><a href="#" class="header_inner-anchor">Борцовки</a></li>
            </ul>
        </li>
        <li class="header_category-item"><a href="/" class="header_collapse-anchor">Сумки,
                рюкзаки<img src="assets/img/categories-icons/bag.png"
                            class="header_category-icon"><img
                        src="assets/img/categories-icons/bag-hover.png"
                        class="header_category-icon header_category-icon--hover"><img
                        src="assets/img/categories-icons/arrow-icon.png"
                        class="category_collapse-icon"><img
                        src="assets/img/categories-icons/arrow-icon-hover.png"
                        class="category_collapse-icon category_collapse-icon--hover"></a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Сумки</a></li>
                <li><a href="#" class="header_inner-anchor">Рюкзаки</a></li>
                <li><a href="#" class="header_inner-anchor">Разное</a></li>
            </ul>
        </li>
        <li class="header_category-item"><a href="/"
                                            class="header_collapse-anchor">Аксессуары<img
                        src="assets/img/categories-icons/cup_icon.png" class="header_category-icon"><img
                        src="assets/img/categories-icons/cup_icon-hover.png"
                        class="header_category-icon header_category-icon--hover"><img
                        src="assets/img/categories-icons/arrow-icon.png" class="category_collapse-icon"><img
                        src="assets/img/categories-icons/arrow-icon-hover.png"
                        class="category_collapse-icon category_collapse-icon--hover"></a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Спортивные аксессуары</a></li>
                <li><a href="#" class="header_inner-anchor">Сувениры</a></li>
                <li><a href="#" class="header_inner-anchor">Сувениры</a></li>
                <li><a href="#" class="header_inner-anchor">Спортивная медицина</a></li>
                <li><a href="#" class="header_inner-anchor">Аксессуары для судей</a></li>
            </ul>
        </li>
        <li class="header_category-item"><a href="/" class="header_collapse-anchor">Спортивное
                питание<img src="assets/img/categories-icons/.png" class="header_category-icon"><img
                        src="assets/img/categories-icons/-hover.png"
                        class="header_category-icon header_category-icon--hover"><img
                        src="assets/img/categories-icons/arrow-icon.png"
                        class="category_collapse-icon"><img
                        src="assets/img/categories-icons/arrow-icon-hover.png"
                        class="category_collapse-icon category_collapse-icon--hover"></a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Одноразове порции</a></li>
                <li><a href="#" class="header_inner-anchor">Аминокислоты</a></li>
                <li><a href="#" class="header_inner-anchor">Витамины и минералы</a></li>
                <li><a href="#" class="header_inner-anchor">Креатин</a></li>
                <li><a href="#" class="header_inner-anchor">Протеины</a></li>
                <li><a href="#" class="header_inner-anchor">Предтренировочные комплексы</a></li>
                <li><a href="#" class="header_inner-anchor">Послетренировочные комплексы</a>
                </li>
                <li><a href="#" class="header_inner-anchor">Гейнеры</a></li>
                <li><a href="#" class="header_inner-anchor">Жиросжигатели</a></li>
                <li><a href="#" class="header_inner-anchor">Тестостероновые бустеры</a></li>
                <li><a href="#" class="header_inner-anchor">Шейкеры</a></li>
            </ul>
        </li>
        <li class="header_category-item">
            <a href="/" class="header_collapse-anchor">Товары для
                детей<img src="assets/img/categories-icons/child_icon.png"
                          class="header_category-icon"><img
                        src="assets/img/categories-icons/child_icon-hover.png"
                        class="header_category-icon header_category-icon--hover"></a>
        </li>
        <li class="header_category-item"><a href="/" class="header_collapse-anchor">Плавание<img
                        src="assets/img/categories-icons/swimming_icon.png"
                        class="header_category-icon"><img
                        src="assets/img/categories-icons/swimming_icon-hover.png"
                        class="header_category-icon header_category-icon--hover"><img
                        src="assets/img/categories-icons/arrow-icon.png" class="category_collapse-icon"><img
                        src="assets/img/categories-icons/arrow-icon-hover.png"
                        class="category_collapse-icon category_collapse-icon--hover"></a>
            <ul class="menu vertical nested header_subcategory">
                <li><a href="#" class="header_inner-anchor">Купальники и плавки</a></li>
                <li><a href="#" class="header_inner-anchor">Экипировка для плавания</a></li>
            </ul>
        </li>
    </ul>
*}
