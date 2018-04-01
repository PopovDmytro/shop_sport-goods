<div style="overflow-y: auto">
    <form method="post" id="cartForm" enctype="multipart/form-data" action="" class="addform">
       <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="task" value="{if is_object($entity)}{$entity->id}{/if}">
        <input type="hidden" name="entity[order_id]" value="{if is_object($entity)}{$entity->order_id}{else}{$porder_id}{/if}">
        <input type="hidden" name="entity[exist_id]" value="{if is_object($entity)}{$entity->id}{/if}">
        <input type="hidden" name="rex_dialog_uin" value="{RexResponse::getDialogUin()}">
        <input type="hidden" name="entity[product_id]" value="{if is_object($entity)}{$entity->product_id}{/if}">
        
        <div class="popup-all-path">
        <table cellspacing="5" id="attr-table" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Название товара:
                </td>
                <td class="frame-t-td">
                     {if $act eq 'add'}
                        <input id="product-autocomplite" type="text" maxlength="128" value="" style="width: 412px;" >
                     {else}
                         {if isset($product)}{$product->name}{/if}
                     {/if}   
                </td>
            </tr>
            <tr class="attr-wrapper">
                <td class="frame-t-td-l">
                    Количество:
                </td>
                <td class="frame-t-td">
                    <input name="entity[count]" type="text" maxlength="128" value="{if is_object($entity)}{$entity->count}{/if}" style="width: 280px;" >
                </td>
            </tr>
            {if isset($arrtprod)}
                {foreach from=$arrtprod key=key item=attrFS}
                    <tr class="attr-wrapper">
                        <td class="first">{$key}:</td>                                                                                       
                        <td class="contentsattr">                                          
                            {foreach from=$attrFS key=k item=attr}                                                                                                                                                                       
                                <a href="javascript:void(0);"  class="attr-default" attr_id="{$attr.attribute_id}" id="{$attr.id}">{if $attr.img_url}<img src="{getimg type=mini name=pImage id=$attr.img_id ext=$attr.img_ext}" title="{$attr.name}">{else}{$attr.name}{/if}</a>                              
                            {/foreach}
                        </td>
                    </tr>    
                {/foreach}
            {/if}
        </table>
    </form>
</div>
<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">{if $act eq 'add'}Add{elseif $act eq 'edit'}Update{/if}</span></button></td>
    </tr>
</table>

<script type="text/javascript">
var skusing = false;
var skusing2 = false;
var skus = false;
{if isset($selectsku)}
    skusing = {if $selectsku[1].attr2prod_id}{$selectsku[1].attr2prod_id}{else}0{/if};
    skusing2 = {if $selectsku[0].attr2prod_id}{$selectsku[0].attr2prod_id}{else}0{/if};
{/if}
{if isset($skus)}
    {literal} 
    var skus = new Array({{/literal}{strip}
         {foreach from=$skus key=k item=sku name=tr}
            {foreach from=$sku key=key item=sku_elem name=tr_sku}
                {if $key eq "skus_elem"}
                    elements:{literal}[{/literal}
                    {foreach from=$sku_elem key=k_sku item=sku_one name=tr_sku_one}
                        {$sku_one.attr2prod_id}
                        {if !$smarty.foreach.tr_sku_one.last},{/if}
                    {/foreach}
                    {literal}]{/literal}
                {else}
                    {$key}:"{$sku_elem}"
                {/if}
                {if !$smarty.foreach.tr_sku.last},{/if}
            {/foreach}
            {literal}}{/literal}{if !$smarty.foreach.tr.last},{literal}{{/literal}{/if}
         {/foreach} 
    {/strip}{literal});
    {/literal} 
{/if}
{literal} 
 
    var main_color_id = 75;

    $(document).ready(function(){  
        $("#product-autocomplite").autocomplete("/admin/autocompleteadmin/", {
            selectFirst: false,
            minLength: 2,
            width: 421,
            scrollHeight: 300,
            max: 30,
            formatItem: function(data, i, n, value) {                
                imageid = value.split('=')[0];
                imageext = value.split('=')[1];
                product = value.split('=')[2];
                product_id = value.split('=')[4];
                name = value.split('=')[3];
                //onclick=\'document.location.href="/product/'+product+'"\'
                {/literal}
                    return '<div product_id="'+product_id+'" style="width:80px; padding-right: 10px; float: left; text-align: center;"><img src="/content/images/pimage/'+imageid+'/mini.'+imageext+'" style="vertical-align: middle;"/></div><div style="padding-top: 13px;">'+name+'</div>';
                    //return '<a href="javascript: void();" ><div style="width:80px; padding-right: 10px; float: left; text-align: center;"><img src="'+image+'" style="vertical-align: middle;width: 60px;height: 60px;"/></div><div style="padding-top: 13px;">'+name+'</div></a>';
                   // console.log("/content/images/pimage/"+imageid+'/mini.'+imageext);
                {literal}
            }, formatResult: function(data, i, n) {
                    return i.split('=')[3];
            }
        }).result(function(event, item) {
            var id_item = item[0].split('=')[4];
            $('input[name="entity[product_id]"]').val(id_item);
            sku = $.rex('prod2Order', 'skuproduct', {task: id_item});
            if (sku) {
                var attr = sku.attr;
                skus = sku.skus;
                for (var i in attr) {
                    var sku1 = $('.addform #attr-table')
                        .append('<tr class="attr-wrapper"><td class="first">'+ i +':</td><td class="contentsattr"></td></tr>')
                        .show();
                        
                    for (var k in attr[i]) {
                        if (attr[i][k].img_url) {
                            var name = '<img src="/content/images/pimage/'+attr[i][k].img_id+'/mini.'+attr[i][k].img_ext+'">'
                            //var name = '<img src="'+imglink+'/pimage/small_'+attr[i][k].img_url+'">'
                        } else { 
                            var name = attr[i][k].name;
                        }
                        var sku2 = $('.attr-wrapper:last .contentsattr')
                            .append('<a href="javascript:void(0);"  class="attr-default" attr_id="'+attr[i][k].attribute_id+'" id="'+attr[i][k].id+'">'+name+'</a>')
                            .show();
                    }     
                }
                $('.item_add').css('visibility','hidden');
            }
            
            return false;
        }); 
        
        $('.attr-default').die('click').live('click',function(){
            if (!$(this).hasClass('attr-inactive')) {
                if ($(this).hasClass('active_sky')) {
                    $(this).removeClass('active_sky');
                    if (!$('.attr-default.active_sky').not('[attr_id='+$(this).attr('id')+'])').length) {
                        $('.attr-default:not([attr_id='+$(this).attr('attr_id')+']).attr-inactive').removeClass('attr-inactive');
                    }
                    getSkusChange();
                } else {
                    $('.attr-default[attr_id='+$(this).attr('attr_id')+']').removeClass('active_sky');
                    $(this).addClass('active_sky');
                    var attr_id = $(this).attr('attr_id');
                    $('input.sku[attr_id="'+attr_id+'"]').val($(this).attr('id'));
                    if (!$('.attr-default.active_sky img').length) {
 
                    }
                    getSkus();
                }
            }           
        });
        
        function getSkusChange(sku)
        {
            //var priceChange = default_price;
            if (typeof sku != 'undefined') {
                //priceChange = sku.price;
                $('.item_add').css('visibility','visible');
                var block = '<input type="hidden" class="attributes-hidden" name="entity[sku]" value="'+sku.id+'">';
                var price = '<input type="hidden" class="attributes-hidden" name="entity[price]" value="'+sku.price+'">';
                $('#cartForm').append(block,price);
               // $('#product-id').html(default_id+'-'+sku.id);
            } else {
                $('.item_add').css('visibility','hidden');
                $('.attributes-hidden').remove();
            }
            
            $('.attr-default').each(function(){
                if ($(this).find('img').length > 0) {
                     $(this).removeClass('attr-inactive');
                }
            });
            
            /*var priceGrnChange = Number(priceChange*dolar_rate).toFixed(0);            
            $('.price-us').html('$'+priceChange);
            $('.price-gr').html(priceGrnChange+' ГРН.');*/            
        }
        
        function getSkus()
        {
            var attrSkuID = [];
            $('.attr-default.active_sky').each(function(index){
                attrSkuID[index] = $(this).attr('id');
            });
            
           
            /*if (attrSkuID.length == skus[0].elements.length) {*/
                for (var y in skus) {
                    var count = 0;
                    var matchID = 0;
                    for (var z in skus[y].elements) {
                        for (var i in attrSkuID) {
                            if (parseInt(attrSkuID[i]) == parseInt(skus[y].elements[z])) {
                                count += 1;
                                matchID = z; 
                            }        
                        }
                    }
                    if (count == skus[y].elements.length) {
                        if (skus[y].quantity > 0) {
                            getSkusChange(skus[y]);        
                        } else {
                            for (var z in skus[y].elements) {
                                $('#'+skus[y].elements[z]+':not([attr_id='+main_color_id+'])').click().addClass('attr-inactive');    
                            }    
                        }
                    } else if (count > 0) {
                        for (var z in skus[y].elements) {
                            if (z != matchID) {
                                if (skus[y].quantity == 0) {
                                    $('#'+skus[y].elements[z]+':not([attr_id='+main_color_id+'])').addClass('attr-inactive');
                                } else {
                                    $('#'+skus[y].elements[z]).removeClass('attr-inactive');
                                }
                            }
                        }
                    }
                } 
                
                $('.attr-default').each(function(){
                    if ($(this).find('img').length > 0) {
                         $(this).removeClass('attr-inactive');
                    }
                });   
            /*}*/
        }
        
        if(act = 'edit'){
            $('.attr-default[id="'+skusing+'"]').click();
            $('.attr-default[id="'+skusing2+'"]').click(); 
        }
    });
{/literal} 
</script>