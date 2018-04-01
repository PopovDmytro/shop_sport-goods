<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        <input type="hidden" name="pid" value="{$pid}">
        
        <table class="form_font_size" cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td align="center" colspan="2">
                    <table cellspacing="2" cellpadding="0" border="0" width="100%">
                        <tr>
                            <td align="center">
                                <div id="attributes-list">
                                    {foreach from=$attributeList key=key item=item}
                                        {if $item.id neq $attribute->id}                       
                                            {if $item.level > 0}{section name=level loop=$item.level}&nbsp;&nbsp;{/section}{/if}
                                            <div class="item-attribute not_select" theid="{$item.id}">{$item.name}</div>  
                                        {/if}
                                    {/foreach}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
</div>

<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add_attr ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">{if $act eq 'add'}Add{elseif $act eq 'edit'}Update{/if}</span></button></td>
    </tr>
</table>

<script type="text/javascript">
{rexscript_start}
{literal}
    if(template.find('#attributes-list .not_select').size() + template.find('#attributes-list .select').size() > 6) {
        template.find('#attributes-list').css({
            'overflow-y' : 'scroll'
        });
    }
    
    template.find('.item-attribute').die('click').live('click', function() {
        var th = $(this);

        if(th.hasClass('not_select')) {
            th.removeClass('not_select');
            th.addClass('select');
        } else if(th.hasClass('select')) {
            th.removeClass('select');
            th.addClass('not_select');
        }
    });
{/literal}
{rexscript_stop}
</script>