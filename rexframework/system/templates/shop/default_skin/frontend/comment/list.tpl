<a name="comments"></a>
{if $comments}
    <table cellpadding="0" cellspacing="0" border="0" class="profile-userinfo1 user-comments">
    {foreach from=$comments key=key item=item}
        <tr {if $item.status eq 1}class="tr-red-bg"{/if}>
            <td class="user-comments-avatar" valign="top"> 
                <div style="font-weight: bold;" class="user-comments-avatar-img">
                    <a href="{url mod=user act=default id=$item.id}">{$item.login}</a>
                </div>
                
            </td>
            <td class="user-comments-in" valign="top">
                {$item.content}
            </td>
        </tr>
    {/foreach}
    </table>
    <hr />
{/if}