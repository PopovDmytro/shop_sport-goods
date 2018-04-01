<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="pid" value="{$pid}">
        
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Родительский атрибут:
                </td>
                <td class="frame-t-td">
                    <select name="entity[pid]" style="width: 188px;">                
                        <option value="0">Атрибут не выбран</option>
                    {foreach from=$attributeList key=key item=item}
                        {if $item.id neq $entity->id}
                        <option value="{$item.id}" {if $entity->pid eq $item.id or $pid eq $item.id}selected{/if}>{if $item.level > 0}{section name=level loop=$item.level}-{/section}{/if}{$item.name}</option>
                        {/if}
                    {/foreach}                
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Тип аттрибута:
                </td>
                <td class="frame-t-td">
                    <select name="entity[type_id]">
                    {foreach from=$attributeTypeList key=key item=item}
                        <option value="{$key}" {if $entity->type_id eq $key}selected{/if}>{$item.name}</option>
                    {/foreach}                
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Название:
                </td>
                <td class="frame-t-td">
                    <input id="attr_name" type="text" name="entity[name]" maxlength="128" value="{if $entity->name}{$entity->name|escape:'html'}{/if}">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Алиас:
                </td>
                <td class="frame-t-td">
                    <input id="attr_alias" type="text" name="entity[alias]" maxlength="128" value="{if $entity->alias}{$entity->alias|escape:'html'}{/if}">
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
                    <input type="text" name="entity[title]" maxlength="128" value="{if $entity->title}{$entity->title}{/if}">
                </td>
            </tr>
            
            <tr>
                <td class="frame-t-td-l">
                    Description:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[description]" maxlength="255" value="{if $entity->description}{$entity->description}{/if}">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Keywords:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[keywords]" maxlength="255" value="{if $entity->keywords}{$entity->keywords}{/if}">
                </td>
            </tr>
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
            <tr id="is-picture-tr">
                <td class="frame-t-td-l">
                    Загрузка картинок:
                </td>
                <td class="frame-t-td">
                    <select name="entity[is_picture]">
                        <option value="0" {if $entity->is_picture eq 0}selected{/if}>Нет</option>
                        <option value="1" {if $entity->is_picture eq 1}selected{/if}>Да</option>
                    </select>        
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
                    <textarea id="DataFCKeditor" name="entity[content]" style="height: 200px; width: 400px;">{if $entity->content}{$entity->content}{/if}</textarea>
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Участвует в фильтрации:
                </td>
                <td class="frame-t-td">
                    <select name="entity[filtered]">
                        <option value="1" {if $entity->filtered eq 1}selected{/if}>Да</option>
                        <option value="0" {if $entity->filtered neq 1}selected{/if}>НЕТ</option>
                    </select>
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Показан на форме фильтрации:
                </td>
                <td class="frame-t-td" align="left">
                    <select name="entity[filtered_form]">
                        <option value="1" {if $entity->filtered_form eq 1}selected{/if}>Да</option>
                        <option value="0" {if $entity->filtered_form neq 1}selected{/if}>НЕТ</option>
                    </select>
                </td>
            </tr>
        </table>
        </div>
    </form>
</div>

<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">{if $act eq 'add'}Add{elseif $act eq 'edit'}Update{/if}</span></button></td>
    </tr>
</table>
<style type="text/css">
{rexstyle_start}
    #cke_contents_DataFCKeditor {
        height: 200px!important;
    }
{rexstyle_stop}
</style>
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find("#attr_name").keyup(function(e){
        var translate = getUrl($(this).val());
        
        template.find('#attr_alias').val(translate);
    });
    
    template.find('select.[name="entity[pid]"]').change(function(){
        getTypeForSelect();            
    });
    
    template.find('select.[name="entity[type_id]"]').change(function(){
        if ($(this).val() == 7) {
            template.find('#is-picture-tr').show();
        } else {
            template.find('#is-picture-tr').hide();
        }
    });
    
    $(document).ready(function(){
        if (template.find('select.[name="entity[pid]"]').val() != 0) {
            getTypeForSelect();    
        }
        if (template.find('select.[name="entity[type_id]"]').val() == 7) {
            template.find('#is-picture-tr').show();
        } else {
            template.find('#is-picture-tr').hide();
        }    
    });
    
    function getTypeForSelect()
    {
        $.rex('attribute', 'typesForParent', {parentID: template.find('select.[name="entity[pid]"]').val()}, function(data){
            if (data !== false) {
                if (typeof data == 'string' && data == 'not') {
                    template.find('select.[name="entity[type_id]"]>option').show();    
                } else {
                    template.find('select.[name="entity[type_id]"]>option').hide().each(function(index){
                        for (var i in data) {
                            if ($(this).val() === i) {
                                $(this).show();
                            }   
                        }
                    });
                    template.find('select.[name="entity[type_id]"]>option').filter(':visible').first().attr('selected', 'selected');
                }
            }    
        });
    }
{/literal}
{rexscript_stop}
</script>