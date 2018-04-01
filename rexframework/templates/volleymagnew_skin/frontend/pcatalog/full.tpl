<div class="nav_category round">
        <p class="navigation-p">                                      
            Все категории
        </p>
        </div>
<div class="catalog-full-list-wrapper">
    <ul class="full-ul">
        {foreach from=$catalogList key=key item=catalog name=fullcat}
            {if isset($catalog.level) and $catalog.level eq 0}
                {if $smarty.foreach.fullcat.iteration % 3 eq 0} 
                    <li class="catalog-full-list-zeroname-li">
                {else}
                    <li class="catalog-full-list-zeroname-li mr14">
                {/if}
                    <a href="{url mod=pCatalog act=default task=$catalog.alias}" class="catalog-full-list-zeroname">{$catalog.name}</a>
                    {if isset($catalogList.pid[$catalog.id])}
                        <ul>
                        {foreach from=$catalogList.pid[$catalog.id] key=k item=catalogOne}
                            <li>
                                <a href="{url mod=pCatalog act=default task=$catalogOne.alias}" class="catalog-full-list-firstname">{$catalogOne.name}</a>
                                {if isset($catalogList.pid[$catalogOne.id])}
                                    <ul>
                                    {foreach from=$catalogList.pid[$catalogOne.id] key=k item=catalogTwo}
                                        <li>
                                            <a href="{url mod=pCatalog act=default task=$catalogTwo.alias}" class="catalog-full-list-secondname">{$catalogTwo.name}</a>    
                                        </li>    
                                    {/foreach}
                                    </ul>
                                {/if}    
                            </li>    
                        {/foreach}
                        </ul>
                    {/if}
                </li>
            {/if}
        {/foreach}
    </ul>
</div>