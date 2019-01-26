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
                    Заголовок:
                </td>
                <td class="frame-t-td">
                    <input id="news_name" name="entity[name]" type="text" maxlength="128" value="{if $entity->name}{$entity->name}{/if}" style="width: 210px;" >
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Алиас:
                </td>
                <td class="frame-t-td">
                    <input id="news_alias" name="entity[alias]" type="text" maxlength="128" value="{if $entity->alias}{$entity->alias}{/if}" style="width: 210px;" >
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Изображение:
                </td>
                <td class="frame-t-td">
                    {if $entity->icon}
                        <img src="{getimg type=main name=$mod id=$entity->id ext=$entity->icon}" />
                        {$icon_delete}
                    {else}
                        нет
                    {/if}
                    <br/><input type="file" name="icon" value="" style="width: 210px;">
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
        </table>
        </div>
        <div class="popup-all-path">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Текст новости:
                </td>
                <td class="frame-t-td">
                    <textarea id="DataFCKeditor" name="entity[content]" style="height:200px !important; width: 400px;">{if $entity->content}{$entity->content}{/if}</textarea>
                </td>
            </tr>
        </table>
            <table cellspacing="5" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="frame-t-td-l">
                        Youtube:
                    </td>
                    <td class="frame-t-td">
                        <input name="entity[youtube]" style="width: 400px;" value="{if $entity->youtube}{$entity->youtube}{/if}"></input>
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
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('input[type=file]').die('change').live('change', function(){
        $.rexCrop($(this), $(this).parent(), 'entity[cropped]', 'width:100px;heigt:100px;')
    });
    
    template.find("#news_name").keyup(function(e){
        var translate = getUrl($(this).val());
        
        template.find('#news_alias').val(translate);
    });
{/literal}
{rexscript_stop}
</script>