{foreach from=$main_categoryList key=key item=mainCat name=categoryList}
    {if $smarty.foreach.categoryList.iteration % 3 eq 0} 
        <div class="item last">
    {else}
        <div class="item">
    {/if}
            <div class="item-img">  
                {if $mainCat.icon}
                    <a href="{url mod=pCatalog act=default task=$mainCat.alias}">
                        <img src="{getimg type=main name=pCatalog id=$mainCat.id ext=$mainCat.icon}"/>
                    </a>
                {else}
                    <a href="{url mod=pCatalog act=default task=$mainCat.alias}">
                        {img width="210" height="210" src="default-icon-240.jpg" class="t-image"}
                    </a>
                {/if}
            </div>
            <div class="name">
                <a href="{url mod=pCatalog act=default task=$mainCat.alias}">
                    {$mainCat.name}
                </a>
            </div>
        </div>    
{/foreach}