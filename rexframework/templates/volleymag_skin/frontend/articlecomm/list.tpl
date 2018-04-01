{if $comments}
     <ul class="comments-list">
     {foreach from=$comments key=key item=item}
        <li>
            <div class="top-block"><a class="user" href="{url mod=user act=default id=$item.id}">{$item.login}</a>{if $item.status eq 1}<span class="your_comment">Сообщение на модерации.</span>{/if}<span class="date">{$item.date_create|date_format:"%d.%m.%Y"}</span></div>
            <p>{$item.content}</p>
        </li>
     {/foreach}
    </ul>
{/if}