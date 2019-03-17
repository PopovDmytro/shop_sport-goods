<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Сравнение</a>
            </li>
        </ul>
    </div>
</div>

<div class="compare_wrapper into-box">
    {*delete all compare products*}
    {*
    <div class="row align-center">
        <div class="columns small-12 medium-12 large-10">
            <div class="compare_product-block compare_product_btns columns small-12 medium-4 large-3">
                <a id="free_button" class="btn btn--green" href="{url mod=product act=compareclear}" >Oчистить</a>
            </div>
            <br />
        </div>
    </div>
    *}
    {**}
    <div class="row align-center">
        <div class="columns small-12 medium-12 large-10">
            <div class="row compare" id="compare">
                {if $productList}
                    {foreach from=$productList key=key item=product name=prodlist}

                        {assign var=currBrand value=$brandList.$product_id}
                        <div class="compare_product_column columns">
                            <article class="compare_product-block">

                                <div class="compare_product-block_title">
                                    <h3>
                                        <a class="compare_product-block_title" href="{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id}">{$product.name}</a>
                                    </h3>
                                </div>
                                <div class="inner-model-holder compare_product-info">
                                    <div class="compare_product-block_picture">
                                        <div class="product-img">
                                            {strip}
                                                <a href="{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id}">
                                                    {if $product.image_id}
                                                        <img src="{getimg type=compare name=pImage id=$product.image_id ext=$product.image_ext}"/>
                                                    {else}
                                                        {img src="default-icon-120.jpg"}
                                                    {/if}
                                                </a>
                                            {/strip}
                                        </div>
                                        <div class="product-price text-center">
                                            {if $product.price}<p class="price-gr">{if $product.sku_price}{$product.sku_price|round:0}{else}{$product.price|round:0}{/if} грн</p>{/if}
                                        </div>
                                    </div>
                                    <div class="compare_product_btns">
                                        <a class="btn btn--blue" href="{if $product.sku_id}{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id sku=$product.sku_id}{else}{url mod=product act=default cat_alias=$product.pcatalog_alias task=$product.id}{/if}" id="free_button">
                                            Купить
                                        </a>
                                        <a id="free_button" class="btn btn--green" href="{url mod=product act=compareclear}{$product.id}" >Oчистить</a>
                                    </div>
                                </div>

                                {if $product.attributes}
                                    {foreach from=$product.attributes item=attr key=key}
                                        {if $key eq 'Цвет'} {*and $attr[1]}*}
                                            <div class="compare_product-block_colors compare_product-info">
                                                <ul class="product-card_pic_additional compare-mod">
                                                    {foreach from=$attr item=color_attr}
                                                        <li>
                                                            <div class="product-card_pic_thumbnail">
                                                                {if $color_attr.img_id}<img src="{getimg type=mini name=pImage id=$color_attr.img_id ext=$color_attr.img_ext}" >{else}{$color_attr.name}{/if}
                                                            </div>
                                                        </li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        {else}
                                            <div class="compare_product-block_sizes compare_product-info">
                                                {foreach from=$attr item=attr_item}
                                                    {$attr_item.name}&nbsp;
                                                {/foreach}
                                            </div>
                                        {/if}
                                    {/foreach}
                                {/if}

                                {if $product.sex}
                                    <div class="compare_product-block_gender compare_product-info">
                                        {foreach from=$product.sex item=sex}
                                            {$sex.name}&nbsp;
                                        {/foreach}
                                    </div>
                                {/if}

                                <div class="compare_product-block_description compare_product-info">
                                    {$product.content|strip_tags|truncate:200}
                                </div>
                            </article>
                        </div>
                    {/foreach}
                {else}
                    <div class="into-box page_text">
                        <p>Список сравнения пуст, или некорректно добавлены товары.</p>
                    </div>
                {/if}
            </div>
        </div>
    </div>
</div>