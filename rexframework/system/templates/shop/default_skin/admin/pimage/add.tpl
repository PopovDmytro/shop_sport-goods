<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform" id="addPicture">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="product_id" value="{$product_id}">
        
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Товар:
                </td>
                <td class="frame-t-td">
                    <select name="entity[product_id]">
                    {foreach from=$productList key=key item=item}
                        <option value="{$item.id}" {if $entity->product_id eq $item.id or $product_id eq $item.id}selected{/if}>{$item.name}</option>                        
                    {/foreach}                
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Изображение:
                </td>
                <td class="frame-t-td" id="img-src-td">
                    {if $entity->image}
                        <img src="{getimg type=icon name=pImage id=$entity->id ext=$entity->image}" />
                    {else}
                        нет
                    {/if}
                    <br/>
                    <input type="file" id="input-file" name="image" value="">
                </td>
            </tr>
            {if $colorAttr}
                <tr>
                    <td class="frame-t-td-l">
                        Картинка для цвета:
                    </td>
                    <td class="frame-t-td">
                        <select class="filter-attr-add" name="entity[attribute_id]">
                            <option value="0">Нет</option>
                            {foreach from=$colorAttr key=key item=attribute}
                                <option value="{$attribute.id}" {if $parent_attr eq $attribute.id}selected{/if}>{$attribute.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
            {/if}
            <tr>
                <td class="frame-t-td-l">
                    Название:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[name]" maxlength="128" value="{if $entity->name}{$entity->name|escape:'html'}{/if}">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Описание:
                </td>
                <td class="frame-t-td">
                    <textarea name="entity[description]" style="height: 200px; width: 400px;">{if $entity->description}{$entity->description}{/if}</textarea>
                </td>
            </tr>
        </table>
    </form>
</div>

<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">{if $act eq 'add'}Add{elseif $act eq 'edit'}Update{/if}</span></button></td>
    </tr>
</table>

<script>
    {literal}
        
        $('#input-file').die('change').live('change', function(){
             $.rexCrop($(this), $('#img-src-td'), 'entity[cropped]', 'width:60px;heigt:60px;')
        });
        
        $('.filter-attr-add').change(function(){
            getFilterColor();
        });
        
        if ($('.filter-attr-add').length) {
            getFilterColor();            
        } 
        
        function getFilterColor()
        {
            $.rex('attr2Prod', 'filterAttribute', {attr_id: $('.filter-attr-add').val(), product_id: {/literal}{$product_id}{literal}}, function(data){
                $('#ajax-select-attribute-add').remove();
                if (data != false) {
                    var block = '<tr id="ajax-select-attribute-add">';
                    block += '<td class="frame-t-td-l">Значения :</td>';
                    block += '<td class="frame-t-td"><select class="filter" name="entity[attribute_id]">';
                    block += '<option value="0">Нет</option>';
                    for (var i in data) {
                        block += '<option value="'+data[i].id+'"';
                        var attr_id = {/literal}{if $entity->attribute_id}{$entity->attribute_id}{else}0{/if}{literal};
                        if (attr_id == data[i].id) {
                            block += ' selected';
                        }
                        block += '>'+data[i].child_name+'</option>';    
                    }
                    block += '</select>';
                    block += '</td></tr>';
                    $('.filter-attr-add').parents('tr:first').after(block);
                }
            });
        }    

    {/literal}
</script>