<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">
        
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Login:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[login]" maxlength="128" value="{if $entity->login}{$entity->login}{/if}">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    E-mail:
                </td>
                <td class="frame-t-td">
                    <input name="entity[email]" type="text" maxlength="128" value="{if $entity->email}{$entity->email}{/if}" >
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Пароль:
                </td>
                <td class="frame-t-td">
                    <input id="password" name="entity[clear_password]" type="text" maxlength="32" value="" >
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Имя:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[name]" maxlength="128" value="{if $entity->name}{$entity->name}{/if}">
                </td>
            </tr>
            
            <tr>
                <td class="frame-t-td-l">
                    Роль:
                </td>
                <td class="frame-t-td">
                    <select name="entity[role]">
                      <option value="user"{if $entity->role eq 'user'}selected{/if}>User</option>
                      <option value="admin"{if $entity->role eq 'admin'}selected{/if}>Admin</option>
                   </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Телефон:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[phone]" maxlength="255" value="{if $entity->phone}{$entity->phone}{/if}" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__">
                </td>
            </tr>
        </table>
    </form>
</div>

<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">{if $act eq 'add'}Add{elseif $act eq 'edit'}Update{/if}</span></button></td>
    </tr>
</table>

{include file="_block/input.phone.mask.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('#password').generatePassword();

    $(document).ready(function(){
        initPhoneMask();
    });
{/literal}
{rexscript_stop}
</script>