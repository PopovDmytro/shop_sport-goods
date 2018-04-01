{if $categoryBrandList and $pcatalog}
    {*<div class="sidebar-box">*}
        {*<div class="wrapper">*}
            <div class="holder one-fourth">
                <h2 class="toggle-btn">Производитель</h2>
                <ul class="brand-list filter_choose_all_list">
                    <li class="checker-all-item">
                        <label>
                            <input type="checkbox" id="brand_check_all" class="filter_choose_all" checked="checked">
                            Выбрать все
                        </label>
                    </li>
                    {$withoutBrand = false}
                    {foreach from=$categoryBrandList key=key item=currBrand}
                        {if $currBrand.selected}
                            {$withoutBrand = true}
                            {break}
                        {/if}
                    {/foreach}
                    {foreach from=$categoryBrandList key=key item=currBrand}
                        <li class="ntitle"><input type="checkbox" id="brand_{$currBrand.id}" name="filter[brand][]" value="{$currBrand.id}" {if !$withoutBrand or $currBrand.selected}checked{/if}><a href="{url mod=pCatalog task=$pcatalog->alias brand_alias=$currBrand.alias}">{$currBrand.name}</a></li>
                    {/foreach}
                </ul>
            </div>
        {*</div>*}
    {*</div>*}
{/if}