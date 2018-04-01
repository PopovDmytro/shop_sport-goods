<div>
<div class="dotted"></div>
    {if $pcatalog}
        {assign var=url value="`$pcatalog->alias`/"}
    {else}
        {assign var=url value=""}
    {/if}    

    <div class="demo" >
        <p>
            {if $valuta eq 'грн'}
                <a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?{if $parseUrl.query}{$parseUrl.query}&{/if}filter[rangefrom]=0&filter[rangeto]={$pricerange.pricefrom|floor}">До {$pricerange.pricefrom|floor} грн</a>
                <a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?{if $parseUrl.query}{$parseUrl.query}&{/if}filter[rangefrom]=0&filter[rangeto]={$pricerange.priceto|floor}">До {$pricerange.priceto|floor} грн</a>
            {elseif $valuta eq '$'}
                <a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?{if $parseUrl.query}{$parseUrl.query}&{/if}filter[rangefrom]=0&filter[rangeto]={$pricerange.pricefrom|floor}">До ${$pricerange.pricefrom|floor}</a>
                <a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?{if $parseUrl.query}{$parseUrl.query}&{/if}filter[rangefrom]=0&filter[rangeto]={$pricerange.priceto|floor}">До ${$pricerange.priceto|floor}</a>
            {/if}
        </p>
            <div id="amount" type="text"  name="pcatalog[range]" style="color:#f6931f; font-weight:bold; text-align:center;">
                {if $valuta eq 'грн'}
                    {if $pricerange.pricefrom}
                        <input class="priceFrom" type='text' value="{$pricerange.pricefrom|floor}">
                    {else}
                        <input class="priceFrom" type='text' value="0">
                    {/if} грн
                    <div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                        <div class="ui-slider-range bgcolor">
                        </div>
                        <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 23.6%;"></a>
                        <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%;"></a>
                    </div>
                    {if $pricerange.priceto}
                        <input class="priceTo" type="text" value="{$pricerange.priceto|floor}">
                    {else}
                        <input class="priceTo" type="text" value="100000">
                    {/if} грн
                {elseif $valuta eq '$'}
                    ${if $pricerange.pricefrom}
                        <input class="priceFrom" type='text' value="{$pricerange.pricefrom|floor}">
                    {else}
                        <input class="priceFrom" type='text' value="0">
                    {/if}
                    <div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                        <div class="ui-slider-range bgcolor">
                        </div>
                        <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 23.6%;"></a>
                        <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%;"></a>
                    </div>
                    ${if $pricerange.priceto}
                        <input class="priceTo" type="text" value="{$pricerange.priceto|floor}">
                    {else}
                        <input class="priceTo" type="text" value="10000">
                    {/if}
                {/if}
                <select name="filter[price_order]">
                    <option value="DESC" {if $price_order eq 'DESC'}selected{/if}>По убыванию</option>
                    <option value="ASC"  {if $price_order eq 'ASC'}selected{/if}>По возрастанию</option>
                </select>
            </div>


        {*<div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
            <div class="ui-slider-range bgcolor">
            </div>
                <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 23.6%;"></a>
                <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%;"></a>
        </div> *}
    </div>

</div>
<style>
    #demo-frame > div.demo { padding: 10px !important; };
</style>

<script type="text/javascript">
{literal}
var valuta = '{/literal}{$valuta}{literal}';
var min = {/literal}{if $pricerange.rangefrom}{$pricerange.rangefrom|round:0}{else}0{/if}{literal};
var max = {/literal}{if $pricerange.rangeto}{$pricerange.rangeto|round:0}{else}0{/if}{literal};
var step = 1;
var difference = max - min;
if (difference > 100 && difference < 1000) {
    step = 10;
} else if (difference >= 1000) {
    step = 100;
}
$(document).ready(function() {
    var pFrom = $('#amount input.priceFrom'),
        pTo = $('#amount input.priceTo');

    if(!$("#rangefrom").length) {
        $('<input type="hidden" name="filter[rangefrom]" value="'+pFrom.val()+'" id="rangefrom">')
            .appendTo($('#filter-form'));
    }

    if(!$("#rangeto").length) {
        $('<input type="hidden" name="filter[rangeto]" value="'+pTo.val()+'" id="rangeto">')
            .appendTo($('#filter-form'));
    }

    $( "#slider-range" ).slider({
        range: true,
        step: 1,
        min: min,
        max: max,
        values: [ {/literal}{if $pricerange.pricefrom}{$pricerange.pricefrom|round:0}{else}0{/if}, {if $pricerange.priceto}{$pricerange.priceto|round:0}{else}0{/if}{literal} ],
        slide: function (event, ui) {
            if (typeof window.sliderTimeout != 'undefined') {
                clearTimeout(window.sliderTimeout);    
            }
            var pFrom = $('#amount input.priceFrom'),
                    pTo = $('#amount input.priceTo');
            pFrom.val(ui.values[0]);
            pTo.val(ui.values[1]);
            $("#rangefrom").val(ui.values[0]);
            $("#rangeto").val(ui.values[1]);
        }
    });
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
    " - $" + $( "#slider-range" ).slider( "values", 1 ) );

    $(document).on('change input paste', 'input.priceFrom', function(e) {
        e.preventDefault();
        var $this = $(this);
        $this.val(checkMinMax($this.val(), true));
        $('#slider-range').slider('values', 0, $this.val());
        $("#rangefrom").val($this.val());
    });

    $(document).on('change input paste', 'input.priceTo', function(e) {
        e.preventDefault();
        var $this = $(this);
        $this.val(checkMinMax($this.val(), false));
        $('#slider-range').slider('values', 1, $this.val());
        $("#rangeto").val($this.val());
    });

    function checkMinMax(value, left) {
        var slider_range = $('#slider-range');
        if (left === undefined) {
            left = true;
        }

        if (value < 0) {
            value = min;
        }

        if (value >= max) {
            value = max;
        }

        /*if (!left && value <= slider_range.slider('values', 0)) {
            value = slider_range.slider('values', 0);
        } else {
            if (left && value >= slider_range.slider('values', 1)) {
                value = slider_range.slider('values', 1);
            }
        }*/

        return value
    }
});
{/literal}
</script>