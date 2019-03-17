{if $categoryBrandList and $pcatalog}
<li data-accordion-item class="accordion-item">
    <a href="javascript:void(0)" class="accordion-title">Производитель</a>
    <div data-tab-content class="accordion-content">
        <ul class="categories_brands-selector no-bullet filter_choose_all_list">
            <li>
                <div class="radio-holder">
                    <label>
                        <input class="filter_choose_all" type="checkbox" id="brand_check_all" checked="checked">
                        <span class="checkbox-trigger checkbox-trigger--grey"></span>
                        <span class="lbl">Выбрать все</span>
                    </label>
                </div>
            </li>
            {$withoutBrand = false}
            {foreach from=$categoryBrandList key=key item=currBrand}
                {if $currBrand.selected}
                    {$withoutBrand = true}
                    {break}
                {/if}
            {/foreach}
            {foreach from=$categoryBrandList key=key item=currBrand}
                <li>
                    <div class="radio-holder">
                        <label>
                            <input type="checkbox" name="filter[brand][]" id="brand_{$currBrand.id}" value="{$currBrand.id}" {if !$withoutBrand or $currBrand.selected}checked="checked"{/if}>
                            <span class="checkbox-trigger checkbox-trigger--grey"></span>
                            <a href="{url mod=pCatalog task=$pcatalog->alias brand_alias=$currBrand.alias}" class="lbl">{$currBrand.name}</a>
                        </label>
                    </div>
                </li>
            {/foreach}
        </ul>
    </div>
</li>
{/if}