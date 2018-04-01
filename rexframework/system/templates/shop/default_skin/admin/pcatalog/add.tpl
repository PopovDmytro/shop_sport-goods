<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="pid" value="{$pid}">
        <input type="hidden" name="rex_dialog_uin" value="{RexResponse::getDialogUin()}">

        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Родительская категория:
                </td>
                <td class="frame-t-td">
                    <select name="entity[pid]">
                        <option value="0">Категория не выбрана</option>
                        {foreach from=$pcatalogList key=key item=item}
                            {if $item.id neq $entity->id}
                            <option value="{$item.id}" {if $entity->pid eq $item.id or $pid eq $item.id}selected{/if}>{if $item.level > 0}{section name=level loop=$item.level}-{/section}{/if}{$item.name}</option>
                            {/if}
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Название:
                </td>
                <td class="frame-t-td">
                    <input id="catalog_name" type="text" name="entity[name]" maxlength="128" value="{if $entity->name}{$entity->name|escape:'html'}{/if}" style="width: 210px;">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Алиас:
                </td>
                <td class="frame-t-td">
                    <input id="catalog_alias" type="text" name="entity[alias]" maxlength="128" value="{if $entity->alias}{$entity->alias|escape:'html'}{/if}" style="width: 210px;">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Название в единственном числе:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[name_single]" maxlength="128" value="{if $entity->name_single}{$entity->name_single|escape:'html'}{/if}" style="width: 210px;">
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
                    <input type="text" name="entity[title]" maxlength="128" value="{if $entity->title}{$entity->title}{/if}" style="width: 220px;">
                </td>
            </tr>

            <tr>
                <td class="frame-t-td-l">
                    Description:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[description]" maxlength="255" value="{if $entity->description}{$entity->description}{/if}" style="width: 220px;">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Keywords:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[keywords]" maxlength="255" value="{if $entity->keywords}{$entity->keywords}{/if}" style="width: 220px;">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Иконка:
                </td>
                <td class="frame-t-td">
                    {if $entity->icon}<img src="{getimg type=list name=$mod id=$entity->id ext=$entity->icon}" />{else}нет{/if}<br/><input type="file" name="icon" value="">
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
                    <textarea id="DataFCKeditor" name="entity[content]" style="height: 100px; width: 400px;">{if $entity->content}{$entity->content}{/if}</textarea>
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Активна ли категория:
                </td>
                <td class="frame-t-td">
                    <select name="entity[active]">
                        <option value="1" {if $entity->active eq 1}selected{/if}>Активна</option>
                        <option value="2" {if $entity->active eq 2}selected{/if}>НЕ Активна</option>
                    </select>
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
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
                <div style="margin: 41px 5px;">Бренды:</div>
            </div>
            <div style="float: left;">
                <select style="width: 200px;" size="4" class="multiselect" multiple="multiple" name="brands[]" id="brands">
                {*<select style="height: 80px; width: 400px;" class="multiselect" multiple="multiple" name="brands[]" id="brands">*}

                {foreach from=$brandList key=key item=brand}
                    {assign var=brand_id value=$brand->id}
                    <option title="{$brand->name}" value="{$brand_id}"
                    {foreach from=$brand2Cat key=key2 item=brand2}
                    {foreach from=$brand2 key=key3 item=brand3}
                    {if $brand3 == $brand_id}selected="selected"{/if}
                    {/foreach}
                    {/foreach}
                    >{$brand->name|truncate:20:"..."}</option>
                {/foreach}
                </select>
            </div>
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

    .ui-multiselect {
       height: 96px!important;
   }
   .ui-multiselect div.selected {
       height: 95px!important;
   }
   .ui-multiselect div.selected div.ui-widget-header {
       height: 32px!important;
   }
   .ui-multiselect div.selected ul.selected, .ui-multiselect div.available ul.available {
       height: 63px!important;
   }
   .ui-multiselect div.available {
       height: 96px!important;
   }
{rexstyle_stop}
</style>
<script type="text/javascript">
{rexscript_start}
{literal}

    template.find('input[type=file]').die('change').live('change', function(){
        $.rexCrop($(this), $(this).parent(), 'entity[cropped]', 'width:100px;heigt:100px;')
    });

    template.find('.multiselect').multiselect();

    template.find("#catalog_name").keyup(function(e){
        var translate = getUrl($(this).val());

        template.find('#catalog_alias').val(translate);
    });
{/literal}
{rexscript_stop}
</script>