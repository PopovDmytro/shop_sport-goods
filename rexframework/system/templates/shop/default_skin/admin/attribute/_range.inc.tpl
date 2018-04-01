<div class="wrapper-range" id="slide-{$attribute->id}">
    <div style="float: left; width: 90px; overflow: hidden;">
        {$attribute->name}
    </div>
    <div style="float: left;">
        <input type="hidden" value=" {if $range.name}{$range.name}{else}лет{/if}" class="range-name" />
        <input type="hidden" name="attribute[{$range.start_id}]" value="{if $rangefrom}{$rangefrom}{else}{$range.start}{/if}" class="rangefrom">
        <input type="hidden" name="attribute[{$range.end_id}]" value="{if $rangeto}{$rangeto}{else}{$range.end}{/if}" class="rangeto">
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
        rangeName = {/literal}' {if $range.name}{$range.name}{else}лет{/if}'{literal};
        
        $('.wrapper-range#'+rangeAttr+' .slider-range').slider({
            range: true,
            min: {/literal}{$range.start}{literal},
            max: {/literal}{$range.end}{literal},
            values: [ {/literal}{if $rangefrom}{$rangefrom}{else}{$range.start}{/if}, {if $rangeto}{$rangeto}{else}{$range.end}{/if}{literal} ],
            slide: function(event, ui) {
                $(ui.handle).parents('.wrapper-range').find('.amount').text( ui.values[ 0 ] + ' - ' + ui.values[ 1 ] + $(ui.handle).parents('.wrapper-range').find('.range-name').val());
                $(ui.handle).parents('.wrapper-range').find('.rangefrom').val( ui.values[ 0 ]);
                $(ui.handle).parents('.wrapper-range').find('.rangeto').val(ui.values[ 1 ] );
            }
        });
        $('.wrapper-range#'+rangeAttr+' .amount').val(  $('.wrapper-range#'+rangeAttr+' .slider-range').slider('values', 0 ) +
            " - " + $('.wrapper-range#'+rangeAttr+' .slider-range').slider('values', 1 ) + rangeName);
    {/literal}
    
    </script>
</div>