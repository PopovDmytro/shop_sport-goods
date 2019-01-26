<div class="product-customize" style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" id="productForm" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="rex_dialog_uin" value="{RexResponse::getDialogUin()}">
        <div>
            <div class="popup-half-path">
            <table cellspacing="5" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="frame-t-td-l">
                        Заголовок:
                    </td>
                    <td class="frame-t-td">
                        <input name="entity[name]" data-autosave="true" type="text" maxlength="128" value="{if $entity->name}{$entity->name|escape}{/if}" style="width: 280px;" >
                    </td>
                </tr>
                {*<tr>
                    <td class="frame-t-td-l">
                        Количество:
                    </td>
                    <td class="frame-t-td">*}
                        <input name="entity[quantity]" data-autosave="true" type="hidden" maxlength="128" value="{if $entity->quantity}{$entity->quantity}{else}40{/if}" style="width: 280px;" >
                   {* </td>
                </tr>*}
            </table>
            </div>
            <div class="popup-half-path">
            <table cellspacing="5" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="frame-t-td-l">
                        Title:
                    </td>
                    <td class="frame-t-td">
                        <input type="text" name="entity[title]" data-autosave="true" maxlength="128" value="{if $entity->title}{$entity->title}{/if}" style="width: 280px;">
                    </td>
                </tr>

                <tr>
                    <td class="frame-t-td-l">
                        Description:
                    </td>
                    <td class="frame-t-td">
                        <input type="text" name="entity[description]" data-autosave="true" maxlength="255" value="{if $entity->description}{$entity->description}{/if}" style="width: 280px;">
                    </td>
                </tr>
                <tr>
                    <td class="frame-t-td-l">
                        Keywords:
                    </td>
                    <td class="frame-t-td">
                        <input type="text" name="entity[keywords]" data-autosave="true" maxlength="255" value="{if $entity->keywords}{$entity->keywords}{/if}" style="width: 280px;">
                    </td>
                </tr>
                <tr>
                    <td class="frame-t-td-l">

                    </td>
                    <td class="frame-t-td">
                        <div style="height:20px;"></div>
                    </td>
                </tr>
            </table>
            </div>
         </div>
         <div>   
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Описание:
                </td>
                <td class="frame-t-td">
                        <textarea id="DataFCKeditor" name="entity[content]" data-autosave="true"
                              style="height:120px !important; width: 400px;">{if $entity->content}{$entity->content}{/if}</textarea>
                </td>
            </tr>
                {if $entity->id}
                    <td class="frame-t-td-l">
                        Дата обновления:
                    </td>
                    <td>
                    
                        <input type="text" name="entity[date_update]" maxlength="255" value="{if $entity->date_update}{$entity->date_update}{/if}" data-autosave="true" style="width: 280px;">
                    </td>
                {/if}
            <tr>
                <td class="frame-t-td-l">
                   Youtube:
                </td>
                <td>

                    <input type="text" name="entity[youtube]" value="{if $entity->date_update}{$entity->youtube}{/if}" data-autosave="true" style="width: 280px;">
                </td>
            </tr>
        </table>
            <table cellspacing="5" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="frame-t-td-l">
                        Категория:
                    </td>
                    <td class="frame-t-td" align="left">
                        <select name="entity[category_id]" id="cat" data-autosave="true">
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
            </table>
            <div class="technologies" style="float:left;margin-top: 12px;"></div>
            <table cellspacing="5" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="frame-t-td-l">
                        Выгружать в каталог?:
                    </td>
                    <td class="frame-t-td">
                        <select name="entity[yml]" data-autosave="true">
                            <option value="1" {if $entity->yml eq 1}selected{/if}>Да</option>
                            <option value="0" {if !$entity->yml}selected{/if}>Нет</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l" style="width: 18%;">
                    Активность:
                </td>
                <td class="frame-t-td">
                    <input type="checkbox" name="entity[active]" data-autosave="true" data-checked="1" data-unchecked="2" {if not $entity->id or $entity->active eq 1}checked="checked"{/if}/>
                    {*<span id="admin-add-prodact-status">{if not $entity->id or $entity->active eq 1}Активен{else}Не активен{/if}</span>*}
                </td>
                <td style="width: 90px;">
                </td>
            </tr>

            <tr>
                <td class="frame-t-td-l">
                    Лидер продаж:
                </td>
                <td class="frame-t-td">
                    <select name="entity[bestseller]" data-autosave="true">
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
                    <select name="entity[new]" data-autosave="true">
                        <option value="1" {if $entity->new eq 1}selected{/if}>Да</option>
                        <option value="0" {if !$entity->new}selected{/if}>Нет</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Вес:
                </td>
                <td class="frame-t-td" style="width: 150px;">
                      <input type="text" name="entity[weight]" data-autosave="true" maxlength="255" value="{if $entity->weight}{$entity->weight}{/if}" style="width: 80px;"><span>гр</span>
                </td>
            </tr>
            <tr>    
                <td class="frame-t-td-l">
                    Единица:
                </td>
                <td class="frame-t-td">
                      <select name="entity[unit]" tyle="width: 180px;" data-autosave="true">
                        <option value="шт" {if $entity->unit eq 'шт'}selected{/if}>шт</option>
                        <option value="пара" {if $entity->unit eq 'пара'}selected{/if}>пара</option>
                    </select>
                </td>
            </tr>
            </table>
            </div>
         </div>
        <div class="more-categories" style="margin-top: 12px;"></div>
         <div>
            <div class="popup-half-path">
                <div style="float: left; width: 100px; overflow: hidden;">
                        <div style="margin: 34px 5px;">Сопутствующие товары:</div>
                </div>
                <div style="float: left;">
                    <select style="width: 200px;" size="8" class="multiselect" multiple="multiple" name="related[]" data-related-field="related_id" data-entity="related" id="prod_check">
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
            </div>
         </div>
    </form>
</div>
<style type="text/css">
{rexstyle_start}
    #cke_contents_DataFCKeditor {
        height: 200px!important;
    }

   .ui-multiselect {
       height: 140px!important;
   }

   .technologies .ui-multiselect {
       height: 280px!important;
   }
   .ui-multiselect div.selected {
       height: 140px!important;
   }
   .ui-multiselect div.selected div.ui-widget-header {
       height: 32px!important;
   }
   .ui-multiselect div.selected ul.selected, .ui-multiselect div.available ul.available {
       height: 107px!important;
   }
   .technologies .ui-multiselect div.selected ul.selected {
       height: 247px !important;
   }
   div.technologies div.ui-multiselect div.available ul.available {
       height: 247px!important;
   }
   .technologies .ui-multiselect div.available {
       height: 280px!important;
   }
   
   .ui-multiselect div.available {
       height: 140px!important;
   }
{rexstyle_stop}
</style>
<script type="text/javascript">
{rexscript_start}
{literal}
    $(document).ready(function() {
        template.find('.multiselect').multiselect({
            selected: function(event, ui) {
                sendRelatedAutoSave($(ui.option), $('[name="entity[exist_id]"]'));
            },
            deselected: function(event, ui) {
                sendRelatedAutoSave($(ui.option), $('[name="entity[exist_id]"]'), 'remove');
            }
        });
    });

    buildCKEditor(template);

    CKEDITOR.instances.DataFCKeditor.on('blur', function(event) {
        sendAutoSaveRequest($('#DataFCKeditor'), event.editor.getData());
    });

    var cat_select = template.find('#cat');
    var active_brand = {/literal}{if $brand_select}{$brand_select}{else}0{/if}{literal};

    function load_brands() {
        $.rex('brand', 'loadByCat', {category_id: cat_select.val()}, function(data) {
            if (false !== data) {
                var html = '';
                if (data == false) {
                    html = 'Нет бренда';
                } else {
                    html = '<select id="brnd" name="entity[brand_id]" data-autosave="true">';
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

                $('select#brnd option:first').attr('selected');
            }
            load_technologies();

        });
    }

    function load_technologies() {
        var product_id = $('[name="entity[exist_id]"]').val();
         var technologies = $.rex('technology', 'TechnologiesByBrand', {task: product_id, brand_id: $('select#brnd').val() });
            if (technologies != 'false') {
                $('.technologies').html(technologies);
            }  else {
                $('.technologies').html('');
            }
    }

    load_brands();

    cat_select.change(function(){
        load_brands();
    });

     $('#productForm').on('change', '#brnd', function(){
         load_technologies();
     });
{/literal}
{rexscript_stop}
</script>