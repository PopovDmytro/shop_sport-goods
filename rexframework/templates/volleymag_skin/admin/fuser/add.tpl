{strip}
<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform" id="addPost">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="entity[active]" value="0">
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            {*<tr>
                <td colspan="2">
                    {page type='getRenderedErrors' section='fUser'}
                </td>
            </tr>*}
            <tr>
                <td class="frame-t-td-l">
                    Active:
                </td>
                <td class="frame-t-td">
                    <input type="checkbox" name="entity[active]" {if $entity->active}checked="checked"{/if} value="1" />
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    First Name:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[fname]" maxlength="100" value="{$entity->fname}" style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Last Name:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[lname]" maxlength="100" value="{$entity->lname}" style="width: 100%;" />
                </td>
            </tr>
            {if $role.view_useremail or $act eq 'add'}
            <tr>
                <td class="frame-t-td-l">
                    Email:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[email]" maxlength="80" value="{$entity->email}" style="width: 100%;" />
                </td>
            </tr>
            {/if}
            <tr>
                <td class="frame-t-td-l">
                    Points:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[points]" maxlength="10" value="{$entity->points}" style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Sum Points:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[points_sum]" maxlength="10" value="{$entity->points_sum}" style="width: 100%;" />
                </td>
            </tr>
             <tr>
                <td class="frame-t-td-l">
                    Avatar:
                </td>
                <td class="frame-t-td" id="img-src-td-slider">
                    {if $entity->icon}
                        <img src="{getimg type=main name=fUser id=$entity->id ext=$entity->icon}" />
                    {else}
                        none
                    {/if}
                    <br/>
                    {if $entity->icon}
                        <ul id="icons" class="add_btn ui-widget" style="float: left;">
                            <li class="ui-state-default ui-corner-all">
                                <a class="clear_avatar" href="javascript: void(0);" title="Clear avatar">
                                    <span class="ui-icon ui-icon-trash"></span>
                                </a>
                            </li>
                        </ul>
                    {/if}<input type="file" id="input-file-slider" name="icon" value="">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-s" colspan="3"><button style="margin-left: auto;margin-right: auto;width: 100px;float: none;display: block;" id="button" type="submit" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">{if $act eq 'add'}{'a.badword.add.add'|lang}{elseif $act eq 'edit'}{'a.badword.add.update'|lang}{/if}</span></button></td>
            </tr>
        </table>
    </form>
</div>
<script>
    {literal}
        $('#input-file-slider').die('change').live('change', function(){
             $.rexCrop($(this), $('#img-src-td-slider'), 'entity[cropped]', 'width:150px;height:150px;')
        });
        $('.clear_avatar').on('click', function(){
            if (confirm('Really clear avatar?')) {
                var result = $.rex('fUser', 'clearAvatar', {id: $('input[name="entity[exist_id]"]').val()});
                
                if (result == 'true') {
                    $('#img-src-td-slider img, #icons').remove();    
                }
            }    
        });
    {/literal}
</script>
{/strip}