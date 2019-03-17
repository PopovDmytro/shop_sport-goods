<div class="header_categories_menu categoriesMenuWrapper">
    <ul class="vertical menu accordion-menu">
        {foreach from=$treeList item=zeroLevel name=catList}
            {if $zeroLevel.level eq 0}
                <li class="header_category-item {if $zeroLevel.cat_list}has-submenu{/if}">
                <a class="header_collapse-anchor" id="menu-n{$zeroLevel.id}" href="{url mod=pCatalog act=default task=$zeroLevel.alias}">
                    {img src="/categories-icons/{$zeroLevel.alias}.png" class="header_category-icon"}
                    {img src="/categories-icons/{$zeroLevel.alias}-hover.png" class="header_category-icon header_category-icon--hover"}
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