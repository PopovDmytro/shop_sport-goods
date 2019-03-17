{if $comments}
     <div class="comments-list comments-wrapper column small-12">
         {foreach from=$comments key=key item=item}
             <article class="comment">
                 <div class="comment_header">
                     <div class="comment_date">
                         <span class="date">{$item.date_create|date_format:"%d.%m.%Y"}</span>
                     </div>
                     <div class="comment_user-name">
                         <a class="user comment_user-link" href="{url mod=user act=default id=$item.id}">
                             {$item.name}
                         </a>
                         {if $item.status eq 1}&nbsp;&nbsp;&nbsp;<span class="your_comment">Сообщение на модерации.</span>{/if}
                     </div>
                 </div>
                 <div class="comment_body">
                     <p>{$item.content}</p>
                 </div>
             </article>
         {/foreach}
     </div>
{/if}