<ul class="product-list">
    {foreach from=$treeList item=zeroLevel name=catList}
        {if $zeroLevel.cat_list}
            {$level0 = false}
            {foreach from=$zeroLevel.cat_list item=level1}
                {if $pcatalog->id|in_array:$level1}
                    {$level0 = true}
                {/if}
            {/foreach}
            {foreach from=$zeroLevel.cat_list item=firstLevel name=foo}
                {if $pcatalog->level == 0 && $level0}
                    <li class="parent-list">
                        <div class="img-box">
                            <a href="{url mod=pCatalog act=default task=$firstLevel.alias}" class="wrapper">
                                <div class="wrapper">
                                    <img class="one{$imageColor}" src="/content/images/pimage/1369/list_gray.jpg"/>
                                </div>
                            </a>
                        </div>
                        <div class="info-product">
                            <a href="{url mod=pCatalog act=default task=$firstLevel.alias}"
                               class="heading-product">{$firstLevel.name|upper}</a>
                        </div>
                    </li>
                {/if}
                {if $firstLevel.cat_list.level2 && $pcatalog->level >= 1}
                    {$leve1 = false}
                    {foreach from=$firstLevel.cat_list.level2 item=level2}
                        {if $pcatalog->id|in_array:$level2}
                            {$leve1 = true}
                        {/if}
                    {/foreach}
                    {foreach from=$firstLevel.cat_list.level2 item=secondLevel}
                        {if $secondLevel.id == $pcatalog->id || $leve1}
                            <li class="parent-list">
                                <div class="img-box">
                                    <a href="{url mod=pCatalog act=default task=$secondLevel.alias}" class="wrapper">
                                        <img class="one{$imageColor}" src="/content/images/pimage/1369/list_gray.jpg"/>
                                    </a>
                                </div>
                                <div class="info-product">
                                    <a href="{url mod=pCatalog act=default task=$secondLevel.alias}"
                                       class="heading-product">{$secondLevel.name|upper}</a>
                            </li>
                        {/if}
                    {/foreach}
                {/if}
            {/foreach}
        {/if}
    {/foreach}
</ul>