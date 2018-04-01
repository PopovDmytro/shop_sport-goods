<li class="wrapper-range" id="slide-{$attribute->id}">
    {if $rangefrom and $rangeto}
        <input type="hidden" name="filter[attribute][{$attribute->id}][{$range.start_id}]" value="{$rangefrom}" class="rangefrom">
        <input type="hidden" name="filter[attribute][{$attribute->id}][{$range.end_id}]" value="{$rangeto}" class="rangeto">
    {/if}
    <strong>
        {$attribute->name}
    </strong>
    <div>
        <input type="hidden" value=" {if $range.name}{$range.name}{else}лет{/if}" class="range-name" />
        <input type="hidden" value="{$range.start_id}" class="range-start" />
        <input type="hidden" value="{$range.end_id}" class="range-end" />
        <p>
            <div class="amount" type="text"  name="filter[range]" style="border:0; color:#f6931f; font-weight:bold; text-align:center;">
                {if $rangefrom}{$rangefrom}{else}{$range.start}{/if} - {if $rangeto}{$rangeto}{else}{$range.end}{/if} {if $range.name}{$range.name}{else}лет{/if}
            </div>
        </p>
        <div class="slider-range ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" style="margin-bottom: 10px;">
            <div class="ui-slider-range ui-widget-header"></div>
            <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;"></a>
            <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 100%;"></a>
        </div> 
    </div>
    <div class="clear5"></div>
    <script type="text/javascript">
    
    {literal}
        rangeAttr = {/literal}'slide-{$attribute->id}'{literal};
        instantFilter = {/literal}{$instant_filter}{literal};
        rangeName = {/literal}' {if $range.name}{$range.name}{else}лет{/if}'{literal};
        
        $('.wrapper-range#'+rangeAttr+' .slider-range').slider({
            range: true,
            min: {/literal}{$range.start}{literal},
            max: {/literal}{$range.end}{literal},
            values: [ {/literal}{if $rangefrom}{$rangefrom}{else}{$range.start}{/if}, {if $rangeto}{$rangeto}{else}{$range.end}{/if}{literal} ],
            slide: function(event, ui) {
                if (typeof window.sliderTimeout != 'undefined') {
                    clearTimeout(window.sliderTimeout);    
                }
                
                if(!$(ui.handle).parents('.wrapper-range').find('.rangeto').length) {
                    $('<input type="hidden" name="filter[attribute]['+$(ui.handle).parents('.wrapper-range').attr('id').replace('slide-', '')+']['+$(ui.handle).parents('.wrapper-range').find('.range-end').val()+']" value="" class="rangeto">')
                        .appendTo($(ui.handle).parents('.wrapper-range'));
                }
                
                if(!$(ui.handle).parents('.wrapper-range').find('.rangefrom').length) {
                    $('<input type="hidden" name="filter[attribute]['+$(ui.handle).parents('.wrapper-range').attr('id').replace('slide-', '')+']['+$(ui.handle).parents('.wrapper-range').find('.range-start').val()+']" value="" class="rangefrom">')
                        .appendTo($(ui.handle).parents('.wrapper-range'));
                }
                
                $(ui.handle).parents('.wrapper-range').find('.amount').text( ui.values[ 0 ] + ' - ' + ui.values[ 1 ] + $(ui.handle).parents('.wrapper-range').find('.range-name').val());
                $(ui.handle).parents('.wrapper-range').find('.rangefrom').val( ui.values[ 0 ]);
                $(ui.handle).parents('.wrapper-range').find('.rangeto').val(ui.values[ 1 ] );
            },
            stop: function (event, ui) {
                if (instantFilter) {
                    window.sliderTimeout = setTimeout(function(){
                        $('#filter-form').submit();
                    }, 1000);
                }
            }
        });
        $('.wrapper-range#'+rangeAttr+' .amount').val(  $('.wrapper-range#'+rangeAttr+' .slider-range').slider('values', 0 ) +
            " - " + $('.wrapper-range#'+rangeAttr+' .slider-range').slider('values', 1 ) + rangeName);
    {/literal}
    
    </script>
</li>