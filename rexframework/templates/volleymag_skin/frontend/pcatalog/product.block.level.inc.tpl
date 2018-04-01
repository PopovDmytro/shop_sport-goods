<ul class="product-plate">
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
                    <li class="parent-list level-block">
                        <a href="{url mod=pCatalog act=default task=$firstLevel.alias}">
                            <div class="img-box">
                                <div class="wrapper">
                                    <img src="/content/images/pimage/1369/list_gray.jpg" style="max-width: 100%;"/>
                                </div>
                            </div>
                            <div class="info-block">
                                <span class="info-product">{$firstLevel.name|upper}</span>
                            </div>
                        </a>
                    </li>
                {/if}
                {if $firstLevel.cat_list.level2 && $pcatalog->level == 1}
                    {$level1 = false}
                    {foreach from=$firstLevel.cat_list.level2 item=level2}
                        {if $pcatalog->id|in_array:$level2}
                            {$level1 = true}
                        {/if}
                    {/foreach}
                    {foreach from=$firstLevel.cat_list.level2 item=secondLevel}
                        {if $secondLevel.id == $pcatalog->id || $level1}
                            <li class="parent-list level-block">
                                <a href="{url mod=pCatalog act=default task=$secondLevel.alias}">
                                    <div class="img-box">
                                        <div class="wrapper">
                                            <img src="/content/images/pimage/1369/list_gray.jpg"
                                                 style="max-width: 100%;"/>
                                        </div>
                                    </div>
                                    <div class="info-block">
                                        <span class="info-product">{$secondLevel.name|upper}</span>
                                    </div>
                                </a>
                            </li>
                        {/if}
                    {/foreach}
                {/if}
            {/foreach}
        {/if}
    {/foreach}
</ul>