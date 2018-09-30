<div class="my-slider my-slider_partners">
    {if $brands}
        {foreach from=$brands key=k item=brand}
            {*{assign var=img_src value={getimg type=list name='brand' id=$k ext='jpeg'}}*}
            {assign var=img_src_main_page value="skin/volleymagnew_skin/frontend/img/main-page/{$brand['alias']}_logo.png"}
            {if $img_src_main_page|file_exists}
            <div class="my-sliders_slide partners_slide">
                <div class="img-holder">
                    <a href="{url mod=pCatalog task='all' brand_alias=$brand['alias']}">
                        {img src="main-page/{$brand['alias']}_logo.png" alt=$brand['name']}
                    </a>
                </div>
            </div>
            {/if}
        {/foreach}
    {/if}
</div>