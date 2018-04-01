<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="pid" value="{$pid}">
        
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Статус:
                </td>
                <td class="frame-t-td">
                    <select name="entity[status]">
                    <option value="1" {if $entity->status eq 1}selected{/if}>Новый</option>    
                    <option value="2" {if $entity->status eq 2}selected{/if}>Оплачен</option>
                    <option value="3" {if $entity->status eq 3}selected{/if}>Закрыт</option>
                </select>
                </td>
            </tr>
            {if $usaorder eq 1}
            <tr>
                <td class="frame-t-td-l">
                    Цена на сайте, у.е.:
                </td>
                <td class="frame-t-td">
                    <span style="font-size:13px;color:#000;">{if $entity->price_usa}{$entity->price_usa}{else}цена отсутствует{/if}</span>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Цена к оплате, грн:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[price]" value="{if $entity->price}{$entity->price}{/if}" />
                </td>
            </tr>
            {/if}
        
            {if $usaorder eq 2}
            <tr>
                <td class="frame-t-td-l">
                    Цена на сайте, юани:
                </td>
                <td class="frame-t-td">
                    <span style="font-size:13px;color:#000;">{if $entity->price_usa}{$entity->price_usa}{else}цена отсутствует{/if}</span>
                </td>
            </tr>
            
            <tr>
                <td class="frame-t-td-l">
                    Цена к оплате, у.е.:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[price]" value="{if $entity->price}{$entity->price}{/if}" />
                </td>
            </tr>
            {/if}
            <tr>
                <td class="frame-t-td-l">
                    Комментарий:
                </td>
                <td class="frame-t-td">
                    <textarea id="DataFCKeditor" name="entity[comment]" style="height: 100px; width: 400px;">{if $entity->comment}{$entity->comment}{/if}</textarea>
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
<style type="text/css">
{rexstyle_start}
    #cke_contents_DataFCKeditor {
        height: 150px!important;
    }
{rexstyle_stop}
</style>