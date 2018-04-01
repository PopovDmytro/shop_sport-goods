 {literal}<script type="text/javascript">
 $(document).ready(function() {
        ShowSubmenu('{/literal}{$pcatalog->pid}{literal}'); 
    });   
</script> {/literal}  

<div class="nav_category round">
    <p class="navigation-p">
        {strip}
            Поиск
        {/strip}
    </p>
</div>

<div id="content">
    {assign var=iteration value=0}
    {foreach from=$productList key=key item=featured name=product_list}
        {assign var=product_id value=$featured.id}
        {assign var=product_name value=$featured.name}
        {assign var=currBrand value=$brandList.$product_id}
        {assign var=featuredCategory value=$categoryList.$product_id}
        {if $smarty.foreach.product_list.iteration % 3 eq 0} 
            <div class="item last">
        {else}
            <div class="item">
        {/if}
                <div class="item-img">
                <a href="{url mod=product act=default task=$product_id cat_alias=$featuredCategory.alias}">  
                    {if $imageList.$product_id}
                        {assign var=image value=$imageList.$product_id} 
                        <img src="{getimg type=list name=pImage id=$image.id ext=$image.image}"/>    
                    {else}                                                                                                    
                        {img width="210" src="default-icon-120.jpg" class="t-image"}
                    {/if} 
                </a>
                </div>
                <div class="name"><a href="{url mod=product act=default task=$product_id cat_alias=$featuredCategory.alias}">{$product_name}</a></div>
                <div class="price"> <span>${$featured.price}</span></div> 
            </div>  
    {/foreach}
      </div> 

    <div class="clear"></div>
    
   
    {if $pager}
        <div class="pagination round" align="center" style="visibility: visible;">

            {foreach from=$pager->pages key=key item=item}
                {if $pager->currentPage eq $item}
                <div class="pagination_div">
                    <b>{$item}</b>
                </div>
                {elseif $item eq 1}
                    <div class="pagin-item"><a href="{url route=shop_search_one q=$q}">{$item}</a></div>
                {else}
                    <div class="pagin-item"><a href="{url mod=pCatalog act=search q=$q page=$item}">{$item}</a></div>
                {/if}            
            {/foreach}

        </div>   
    {/if}
     
