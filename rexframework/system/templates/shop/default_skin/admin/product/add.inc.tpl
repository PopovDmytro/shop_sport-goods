<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" id="productForm" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="rex_dialog_uin" value="{RexResponse::getDialogUin()}">
        
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Заголовок:
                </td>
                <td class="frame-t-td">
                    <input name="entity[name]" type="text" maxlength="128" value="{if $entity->name}{$entity->name}{/if}" style="width: 280px;" >
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Цена:
                </td>
                <td class="frame-t-td">
                    <input name="entity[price]" type="text" maxlength="128" value="{if $entity->price}{$entity->price}{/if}" style="width: 280px;" >
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Количество:
                </td>
                <td class="frame-t-td">
                    <input name="entity[quantity]" type="text" maxlength="128" value="{if $entity->quantity}{$entity->quantity}{/if}" style="width: 280px;" >
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Title:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[title]" maxlength="128" value="{if $entity->title}{$entity->title}{/if}" style="width: 280px;">
                </td>
            </tr>
            
            <tr>
                <td class="frame-t-td-l">
                    Description:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[description]" maxlength="255" value="{if $entity->description}{$entity->description}{/if}" style="width: 280px;">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Keywords:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[keywords]" maxlength="255" value="{if $entity->keywords}{$entity->keywords}{/if}" style="width: 280px;">
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-all-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Описание:
                </td>
                <td class="frame-t-td">
                    <textarea id="DataFCKeditor" name="entity[content]" style="height:250px !important; width: 400px;">{if $entity->content}{$entity->content}{/if}</textarea>
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Статус:
                </td>
                <td class="frame-t-td">
                    <select name="entity[active]">
                        <option value="1" {if $entity->active eq 1}selected{/if}>Активен</option>
                        <option value="2" {if $entity->active eq 2}selected{/if}>НЕ Активен</option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td class="frame-t-td-l">
                    Лидер продаж:
                </td>
                <td class="frame-t-td">
                    <select name="entity[bestseller]">
                        <option value="1" {if $entity->bestseller eq 1}selected{/if}>Да</option>
                        <option value="0" {if !$entity->bestseller}selected{/if}>Нет</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Новинка:
                </td>
                <td class="frame-t-td">
                    <select name="entity[new]">
                        <option value="1" {if $entity->new eq 1}selected{/if}>Да</option>
                        <option value="0" {if !$entity->new}selected{/if}>Нет</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Акционный:
                </td>
                <td class="frame-t-td">
                    <select name="entity[event]">
                        <option value="1" {if $entity->event eq 1}selected{/if}>Да</option>
                        <option value="0" {if !$entity->event}selected{/if}>Нет</option>
                    </select>
                </td>
            </tr>
            </table>
            </div>
            <div class="popup-half-path">
            <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Категория:
                </td>
                <td class="frame-t-td" align="left">
                    <select name="entity[category_id]" id="cat">
                        {foreach from=$pcatalogList key=key item=category}
                            <option value="{$category.id}" {if $category.level eq 0 and $category.count gt 0}disabled{elseif $entity->category_id eq $category.id}selected{/if}>{if $category.level > 0}{section name=level loop=$category.level}-{/section}{/if}{$category.name}</option>
                        {/foreach}                
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Бренд:
                </td>
                <td class="frame-t-td" align="left">
                    <div id="brand"></div>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Выгружать в каталог?:
                </td>
                <td class="frame-t-td">
                    <select name="entity[yml]">
                        <option value="1" {if $entity->yml eq 1}selected{/if}>Да</option>
                        <option value="0" {if !$entity->yml}selected{/if}>Нет</option>
                    </select>
                </td>
            </tr>
            </table>
            </div>
            <div class="popup-all-path">
                <div style="float: left; width: 100px; overflow: hidden;">
                        <div style="margin: 34px 5px;">Сопутствующие товары:</div>
                </div>
                <div style="float: left;">
                    <select style="width: 200px;" size="8" class="multiselect" multiple="multiple" name="related[]" id="prod_check">
                        {foreach from=$productList key=key item=prod}
                            {assign var=prod_id value=$prod.id}
                            {if $relatedList.$prod_id}
                                <option title="{$prod.name}" value="{$prod.id}" selected>{$prod.name|truncate:20:"..."}</option>
                            {else}
                                <option title="{$prod.name}" value="{$prod.id}">{$prod.name|truncate:20:"..."}</option>
                            {/if}
                            
                        {/foreach}                
                    </select>
                </div>
                <div class="more-categories" style="float:left;"></div>
            </div>
    </form>
</div>
<style type="text/css">
{rexstyle_start}
    #cke_contents_DataFCKeditor {
        height: 200px!important;
    }
    
   .ui-multiselect {
       height: 180px!important;
   }
   .ui-multiselect div.selected {
       height: 180px!important;
   }
   .ui-multiselect div.selected div.ui-widget-header {
       height: 32px!important;
   }
   .ui-multiselect div.selected ul.selected, .ui-multiselect div.available ul.available {
       height: 147px!important;
   }
   .ui-multiselect div.available {
       height: 180px!important;
   }
{rexstyle_stop}
</style>
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.multiselect').multiselect();
    
    var cat_select = template.find('#cat');
    var active_brand = {/literal}{if $brand_select}{$brand_select}{else}0{/if}{literal};
    
    template.find('#brnd').live('change', function(){
        active_brand = $(this).val();
    });
    
    function load_brands() {
        $.rex('brand', 'loadByCat', {category_id: cat_select.val()}, function(data) {
            if (false !== data) {
                var html = '';
                if (data == false) {
                    html = 'Нет бренда';    
                } else {
                    html = '<select id="brnd" name="entity[brand_id]">';
                    for (var i in data) {
                        if (i) {
                            html = html + '<option value="'+i+'" '+(i == active_brand ? 'selected="selected" ' : '')+'>'+data[i]+'</option>';
                        }
                    }
                    html += '</select>';
                    var product_id = $('[name="entity[exist_id]"]').val();
                    var categories = $.rex('pCatalog', 'allCategoriesExcept', {task: cat_select.val(), product_id: product_id});
                    $('div.more-categories').html(categories);    
                }
                template.find('#brand').html(html);
            }
        });
    }
    
    load_brands();
    cat_select.change(function(){
        load_brands();
    });
{/literal}
{rexscript_stop}
</script>