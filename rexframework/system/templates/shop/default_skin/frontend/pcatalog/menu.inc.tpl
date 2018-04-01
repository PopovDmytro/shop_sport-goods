<div class="sidebarleft-box-content sidebarleft-menu-wrapper">
    
    <div class="sublevel-list"></div>
    <div class="sidebarleft-menu-divide"></div>
    <div class="zerolevel-list">
    <div class="dotted"></div>
        {foreach from=$treeList item=zeroLevel name=catList}
            {if $zeroLevel.level eq 0} {*top level*}  
              
                <div class="m_item">
                  <a href="{url mod=pCatalog act=default task=$zeroLevel.alias}">
                        {if $zeroLevel.icon}
                            <img src="{getimg type=list name=pCatalog id=$zeroLevel.id ext=$zeroLevel.icon}" />
                        {/if}
                        <span>                        
                           {$zeroLevel.name}
                        </span>
                        <div class="zerolevel-one-content">
                            {$zeroLevel.content|strip_tags}
                        </div>
                     </a>
                    {if $zeroLevel.cat_list}
                        <div class="sublevel-list-hidden">
                            {foreach from=$zeroLevel.cat_list item=firstLevel}
                                <div class="sublevel-one">
                                     <a href="{url mod=pCatalog act=default task=$firstLevel.alias}">
                                        {if $firstLevel.icon}
                                            <img src="{getimg type=list name=pCatalog id=$firstLevel.id ext=$firstLevel.icon}" />
                                        {/if}
                                        <div class="sublevel-one-name">                        
                                           {$firstLevel.name}
                                        </div>
                                        <div class="sublevel-one-content">
                                            {$firstLevel.content|strip_tags}
                                        </div>
                                    </a>
                                </div>  
                                <div class="clear"></div>  
                            {/foreach}
                        </div>
                    {/if} 
                </div>
               
            {/if}
            <div class="clear"></div>
            {if !$smarty.foreach.catList.last} 
                <div class="dotted"></div>
            {/if}  
        {/foreach}
            <div class="dotted"></div>
                <a href="{url mod=pCatalog act=full}" class="all-cat">Все категории</a>
    </div>
</div>

<script>
    {literal}
    
        $('.m_item').mouseenter(function(){
            if (!$(this).hasClass('m_item_selected')) {
                resetSelectedMenu();
                var topArrow = Math.round(($(this).innerHeight() - 14)/2);
                $(this).addClass('m_item_selected').append('<div class="zerolevel-arrow-right" style="top:'+topArrow+'px;opacity:0;"></div>');
                $('.zerolevel-arrow-right').animate({
                    opacity: 1    
                }, 800);
            }
            $(this).parents('.sidebarleft-menu-wrapper').find('.sublevel-list').html($(this).find('.sublevel-list-hidden').html());
            $(this).parents('.sidebarleft-menu-wrapper').find('.sidebarleft-menu-divide').show();
            if (!$(this).parents('.sidebarleft-menu-wrapper').hasClass('sublevel-list-open')) {
                $(this).parents('.sidebarleft-menu-wrapper').addClass('sublevel-list-open').animate({
                    width: '430px'     
                }, 500);
            }
        });
        
        $('.sidebarleft-menu-wrapper').mouseleave(function(){
            resetSelectedMenu();
            $(this).find('.sidebarleft-menu-divide').hide();
            $(this).removeClass('sublevel-list-open').stop(true).find('.sublevel-list').html('');
            $(this).animate({
                width: '188px'     
            }, 500);
        });
        
        function resetSelectedMenu()
        {
            $('.zerolevel-arrow-right').stop(true).remove();
            $('.m_item_selected').removeClass('m_item_selected');    
        }
    
    {/literal}
</script>