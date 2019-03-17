<div class="my-slider my-slider_partners">
    {if $brands}
        {foreach from=$brands key=k item=brand}
            {*{assign var=img_src value={getimg type=list name='brand' id=$k ext='jpeg'}}*}
            {assign var=img_path value="content/images/brand/`$k`/list"}
            {if file_exists("{$img_path}.svg")}
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="{url mod=pCatalog task='all' brand_alias=$brand['alias']}">
                            <img src="/{$img_path}.svg" alt="{$brand['name']}">
                        </a>
                    </div>
                </div>
            {elseif file_exists("{$img_path}.png")}
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="{url mod=pCatalog task='all' brand_alias=$brand['alias']}">
                            <img src="/{$img_path}.png" alt="{$brand['name']}">
                        </a>
                    </div>
                </div>
            {elseif file_exists("{$img_path}.jpeg")}
                <div class="my-sliders_slide partners_slide">
                    <div class="img-holder">
                        <a href="{url mod=pCatalog task='all' brand_alias=$brand['alias']}">
                            <img src="/{$img_path}.jpeg" alt="{$brand['name']}">
                        </a>
                    </div>
                </div>
            {/if}
        {/foreach}
    {/if}
</div>

