<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="addform{if $isOnlyView} user-add-form{/if}">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="add">
        <input type="hidden" name="entity[exist_id]" value="{$entity->id}">

        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            {*<tr>
                <td class="frame-t-td-l">
                    Login:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[login]" maxlength="128" value="{if $entity->login}{$entity->login}{/if}">
                </td>
            </tr> *}
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
                    Фамилия:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[lastname]" maxlength="128" value="{if $entity->lastname}{$entity->lastname}{/if}">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Роль:
                </td>
                <td class="frame-t-td">
                    <select name="entity[role]">
                      <option value="user"{if $entity->role eq 'user'}selected{/if}>User</option>
                      <option value="operator"{if $entity->role eq 'operator'}selected{/if}>Operator</option>
                      <option value="admin"{if $entity->role eq 'admin'}selected{/if}>Admin</option>
                   </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Телефон:
                </td>
                <td class="frame-t-td">
                    <input type="text" name="entity[phone]" value="{if $entity->phone}{$entity->phone}{/if}" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                   Город:
                </td>
                <td class="frame-t-td">
                    <input type="text" id="autocomplete_city" class="search titlex" name="q" onblur="{literal}javascript: if (this.value=='') {this.value='Введите ваш город';}" onfocus="javascript: if (this.value=='' || this.value=='Введите ваш город') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$entity->city}{/if}" />
                    <input type="hidden" id="val_city" name="entity[city]" data-id="{$city_id}" value="{$entity->city}">
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Филлиал:
                </td>
                <td class="frame-t-td">
                    <select name="entity[fillials]" class="select_default city_filials titlex" style="width:250px">
                        <option id="Selcity" value="0">Выберите филлиал города</option>
                        {foreach from=$fillials item=ifillials}
                            <option value="{$ifillials.name}" cid="{$ifillials.city_id}" {if $entity->fillials eq $ifillials.name}selected{/if}>{$ifillials.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="frame-t-td-l">
                    Участие в e-mail рассылке:
                </td>
                <td class="frame-t-td">
                    <select name="entity[delivery]" class="select_default titlex" style="width:250px">
                        <option value="0" {if $entity->delivery eq 0}selected{/if}>Нет</option>
                        <option value="1" {if $entity->delivery eq 1}selected{/if}>Да</option>
                    </select>
                </td>
            </tr>
        </table>
    </form>
    {if $entity->delivery eq 0}
        <div class="change-email">* Для участия в рассылке укажите обязательно действующий E-mail</div>
    {/if}
</div>
<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        {if !$isOnlyView}
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">{if $act eq 'add'}Add{elseif $act eq 'edit'}Update{/if}</span></button></td>
        {/if}
    </tr>
</table>
{include file="order/user_order_list.inc.tpl"}
{include file="_block/input.phone.mask.tpl"}
<script type="text/javascript">
{rexscript_start}
var filials = '{$entity->fillials}';

{literal}
    $(document).ready(function(){
        initPhoneMask();
        $('.user-add-form input, .user-add-form select').attr('disabled', true);
    });
    template.find('form:not(.user-add-form) #password').generatePassword();
    window.selectClone = $('select[name="entity[fillials]"]').clone();
    
        var checkSelect = function(featured)
        {
            
            $this = window.selectClone.clone();
            //var featured2 = $('#select option:selected').attr('cid');
            if (featured != 0 && $this.find('option[cid="'+featured+'"]').length > 0) {
                $this.find('option[cid!="'+featured+'"]').remove();
            } else if (featured != 0 && !$this.find('option[cid="'+featured+'"]').length) {
                $this.val(0).find('option[value!="0"]').remove();
            } else {
                $this.val(featured);
            }

            $('select[name="entity[fillials]"]').replaceWith($this);          
        };
        
        if ($('#val_city').val() && !filials && filials.length == 0) {
            checkSelect($('#val_city').attr('data-id'));    
        }
        $("#autocomplete_city").autocomplete("/admin/autocompletecityadmin/", {
            selectFirst: false,
            minLength: 2,
            width: 420,
            scrollHeight: 400,
            max: 30,
            formatItem: function(data, i, n, value) {
                city = value.split('=')[0];
                city_id = value.split('=')[1];
                return '<div class="city" cid="'+city_id+'" value="'+city+'">'+city+'</div>'

            }, formatResult: function(data, i, n) {
                    return i.split('=')[0];
            }
        }).result(function(event, item) {
            var id = item[0].split('=')[1];
            var city = item[0].split('=')[0];
            $('#val_city').val(city);
            $('#val_city').attr('data-id', id );
            checkSelect(id);
            return false;
        });
{/literal}
{rexscript_stop}
</script>