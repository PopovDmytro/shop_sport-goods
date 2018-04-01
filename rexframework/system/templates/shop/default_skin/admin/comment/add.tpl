<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Комментарий:
                </td>
                <td class="frame-t-td">
                    <textarea onkeyup="javascript:backspacerUPText(this,event);" name="entity[content]" style="height:200px !important; width: 400px;">{if $entity->content}{$entity->content}{/if}</textarea>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Статус:
                </td>
                <td class="frame-t-td">
                    <select name="entity[status]">
                        <option value="1" {if $entity->status eq 1}selected{/if}>НЕ Заапрувлен</option>
                        <option value="2" {if $entity->status eq 2}selected{/if}>Заапрувлен</option>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</div>

<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">Update</span></button></td>
    </tr>
</table>