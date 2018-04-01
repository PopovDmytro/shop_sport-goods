{if $categoryBrandList and $pcatalog}
    <div class="sideRight-box-title round">
        <div class="lmenu-text-title">Производитель</div>
        <div class="dotted" style="margin-top:5px"></div>
    </div> 
    <div class="sideRight-box-conttent">
    <ul class="newslist-list">
    {foreach from=$categoryBrandList key=key item=currBrand}
        <li class="ntitle"><input type="checkbox" id="brand_{$currBrand.id}" name="filter[brand][]" value="{$currBrand.id}" {if $currBrand.selected}checked{/if}><a href="{url mod=pCatalog task=$pcatalog->alias brand_alias=$currBrand.alias}">{$currBrand.name}</a></li>
    {/foreach}
    <br/>
    </ul>
    </div>
{/if}