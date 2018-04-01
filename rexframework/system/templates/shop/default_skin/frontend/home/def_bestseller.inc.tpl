    {assign var=iteration value=0}
    {foreach from=$bestseller_productList key=featured_key item=featured name=product_list}
        {assign var=featured_id value=$featured.id}
        {if $bestseller_brandList.$featured_id}
            {assign var=featuredBrand value=$bestseller_brandList.$featured_id}
        {/if}
        {assign var=featuredCategory value=$bestseller_categoryList.$featured_id}
        {if $smarty.foreach.product_list.iteration % 3 eq 0} 
            <div class="item last">
        {else}
            <div class="item">
        {/if}    
            <div class="item-img">
                {if $bestseller_imageList.$featured_id}
                    {assign var=image value=$bestseller_imageList.$featured_id}    
                    <a href="{url mod=product act=default cat_alias=$featuredCategory->alias task=$featured_id}">
                        <img src="{getimg type=list name=pImage id=$image.id ext=$image.image}"/>
                    </a>
                {else}
                    <a href="{url mod=product act=default cat_alias=$featuredCategory->alias task=$featured_id}">
                        {img width="210" height="210" src="default-icon-240.jpg" class="t-image"}
                    </a>
                {/if}
            </div>
            <div class="name"><a href="{url mod=product act=default cat_alias=$featuredCategory->alias task=$featured_id}">{$featured.name}</a></div>
            {if $featured.sale}
                <div class="price-sale"> 
                    {if $filter_kurs eq 'грн'}
                        <span class="prise-sale-full">{($featured.price*$dolar_rate)|round:0}{$filter_kurs}</span>
                        <span class="prise-tosale">{($featured.price*$dolar_rate - $featured.price*$featured.sale/100)|round:0}{$filter_kurs}</span>
                    {else}
                        <span class="prise-sale-full">${$featured.price}</span>
                        <span class="prise-tosale">${($featured.price - $featured.price*$featured.sale/100)|round:2}</span>
                    {/if}
                </div>
            {else} 
                {if $filter_kurs eq 'грн'}
                   <div class="price"><span>{($featured.price*$dolar_rate)|round:0}{$filter_kurs}</span></div>
                {else}
                    <div class="price"><span>${$featured.price}</span></div>
                {/if}
            {/if}
        </div>
    {/foreach}