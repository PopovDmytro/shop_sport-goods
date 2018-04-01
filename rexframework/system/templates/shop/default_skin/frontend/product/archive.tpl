   <div class="category">
     {if $navCategoryList}
     <div class="nav_category round">
        <p class="navigation-p">                                      
        {strip}  
            {foreach from=$navCategoryList key=key item=category name=navBar}
                <a href="{url mod=pCatalog act=default task=$category.alias}">{$category.name}</a>
                {if !$smarty.foreach.navBar.last}&nbsp;->&nbsp;{/if}
            {/foreach}  
            {$type}
        {/strip}
        </p>
        </div>
    {/if}
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
      </div> 

    <div class="clear"></div>
    
   
        {if $pager}
         <div class="pagination round" align="center" style="visibility: visible;">
        
            {foreach from=$pager->pages key=key item=item}
                {if $pager->currentPage == $item}
                <div class="pagination_div">
                    <b>{$item}</b>
                </div>
                {elseif $item eq 1}
                    <a href="{url mod=pCatalog act=default task=$pcatalog->alias}{*http://{$DOMAIN}/catalog/{$pcatalog->alias}/*}">{$item}</a>
                {else}
                    <a href="{url mod=pCatalog act=default task=$pcatalog->alias page=$item}{*http://{$DOMAIN}/catalog/{$pcatalog->alias}/{$item}/*}">{$item}</a>
                {/if}            
            {/foreach}
           
           </div>   
        {/if}
     
</div>