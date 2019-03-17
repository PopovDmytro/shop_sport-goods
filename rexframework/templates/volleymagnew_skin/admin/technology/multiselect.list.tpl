{if $technologies}
    <div style="float: left; width: 100px; overflow: hidden;">
            <div style="margin: 34px 5px;">Технологии:</div>
    </div>
    <div class="technologies" style="float: left;">
        <select style="width: 200px;" size="8" class="multiselect" multiple="multiple" name="technologies[]" data-related-field="technology_id" data-entity="prod2Tech">
            {foreach from=$technologies key=key item=technology}
                {assign var=technology_id value=$technology.id}
                <option title="{$technology.name}" value="{$technology_id}"
                {foreach from=$prod2Tech key=key2 item=technology2}
                    {foreach from=$technology2 key=key3 item=technology3}
                        {if $technology3 == $technology_id}selected="selected"{/if}
                    {/foreach}
                {/foreach}
                >{$technology.name|truncate:20:"..."}</option>
            {/foreach}
        </select>
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