<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="rex_dialog_uin" value="{RexResponse::getDialogUin()}">

        <div class="popup-half-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Название:
                </td>
                <td class="frame-t-td">
                    <input id="technology_name" type="text" name="entity[name]" maxlength="128" value="{if $entity->name}{$entity->name|escape:'html'}{/if}" style="width: 210px;">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Изображение:
                </td>
                <td class="frame-t-td">
                    {if $entity->icon}
                        <img src="{getimg type=list name=technology id=$entity->id ext=$entity->icon}" />
                        {$icon_delete}
                    {else}
                        нет
                    {/if}
                    <br/><input type="file" name="icon" value="" style="width: 210px;">
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
                    <textarea id="DataFCKeditor" name="entity[description]" style="height: 100px; width: 400px;">{if $entity->description}{$entity->description}{/if}</textarea>
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
    template.find('input[type=file]').die('change').live('change', function(){
        $.rexCrop($(this), $(this).parent(), 'entity[cropped]', 'width:100px;heigt:100px;')
    });

{/literal}
{rexscript_stop}
</script>