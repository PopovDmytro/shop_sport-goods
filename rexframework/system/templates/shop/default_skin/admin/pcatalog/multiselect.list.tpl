{if $categories}
    <div class="popup-all-path">
        <div style="float: left; width: 100px; overflow: hidden;">
                <div style="margin: 34px 5px;">Добавить этот товар в категории:</div>
        </div>
        <div style="float: left;">
            <select style="width: 200px;" size="8" class="multiselect" multiple="multiple" name="categories[]" data-related-field="category_id" data-entity="prod2Cat">
                {foreach from=$categories key=key item=category}
                    <option title="{$category.name}" value="{$category.id}" {if $category.exist eq 1}selected{/if}>{if $category.pname}{$category.pname|truncate:20:"..."} -> {/if}{$category.name|truncate:20:"..."}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <script type="text/javascript">
    {literal}
        $('.multiselect').multiselect({
            selected: function(event, ui) {
                sendRelatedAutoSave($(ui.option), $('[name="entity[exist_id]"]'));
            },
            deselected: function(event, ui) {
                sendRelatedAutoSave($(ui.option), $('[name="entity[exist_id]"]'), 'remove');
            }
        });
    {/literal}
    </script>
{/if}