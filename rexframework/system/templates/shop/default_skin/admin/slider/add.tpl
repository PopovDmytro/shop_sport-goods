<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="entity[sorder]" value="{if $entity->sorder}{$entity->sorder}{else}0{/if}">
        <input type="hidden" name="rex_dialog_uin" value="{RexResponse::getDialogUin()}">

        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Показывать в слайдере:
                </td>
                <td class="frame-t-td">
                    <select name="entity[showbanner]">
                        <option value="on" {if $entity->showbanner eq 1}selected{/if}>Да</option>
                        <option value="off" {if $entity->showbanner eq 0}selected{/if}>Нет</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Баннер:
                </td>
                <td class="frame-t-td">
                    {if $entity->banner}
                        {assign var=x value= 1|rand:100}
                        <img src="{getimg type=mini name=slider id=$entity->id ext=$entity->banner}?{$x}" />
                        {$icon_delete}
                    {else}
                        нет
                    {/if}
                    <br/><input type="file" name="banner" accept="image/*" value="">
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Заголовок:
                </td>
                <td class="frame-t-td">
                    <input name="entity[name]" type="text" maxlength="128" value="{if $entity->name}{$entity->name}{/if}" style="width: 220px;" >
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Url:
                </td>
                <td class="frame-t-td">
                    <input name="entity[url]" type="text" maxlength="128" value="{if $entity->url}{$entity->url}{/if}" style="width: 220px;" >
                </td>
            </tr>
        </table>
        </div>
        <div class="popup-all-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Текст:
                </td>
                <td class="frame-t-td">
                    <textarea name="entity[text]" style="height:100px !important; width: 522px;">{if $entity->text}{$entity->text}{/if}</textarea>
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

<script>
    {literal}

        $('input[type=file]').die('change').live('change', function(){
             $.rexCrop($(this), $(this).parents('td'), 'entity[cropped]', 'width:317px;height:157px;')
        });

    {/literal}
</script>