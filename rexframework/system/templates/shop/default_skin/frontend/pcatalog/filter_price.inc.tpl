<div class="sidebar-center">
<div class="dotted"></div>
    {if $pcatalog}
        {assign var=url value="`$pcatalog->alias`/"}
    {else}
        {assign var=url value=""}
    {/if}    

    <div class="demo" >
        <p>
            {if $valuta eq 'грн'}
                <a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?{if $parseUrl.query}{$parseUrl.query}&{/if}filter[rangefrom]=1&filter[rangeto]=100">До 100 грн</a>
                <a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?{if $parseUrl.query}{$parseUrl.query}&{/if}filter[rangefrom]=1&filter[rangeto]=1000">До 1000 грн</a>
            {elseif $valuta eq '$'}
                <a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?{if $parseUrl.query}{$parseUrl.query}&{/if}filter[rangefrom]=1&filter[rangeto]=10">До $10</a>
                <a href="{url mod=pCatalog act=default task=$pcatalog->alias}filter/?{if $parseUrl.query}{$parseUrl.query}&{/if}filter[rangefrom]=1&filter[rangeto]=100">До $100</a>
            {/if}
            <div id="amount" type="text"  name="pcatalog[range]" style="border:0; color:#f6931f; font-weight:bold; text-align:center;">
                {if $valuta eq 'грн'}
                    {if $pricerange.pricefrom}{$pricerange.pricefrom|round:0} грн{else}0 грн{/if} - {if $pricerange.priceto}{$pricerange.priceto|round:0} грн{else}100000 грн{/if}
                {elseif $valuta eq '$'}
                    {if $pricerange.pricefrom}${$pricerange.pricefrom|round:0}{else}$0{/if} - {if $pricerange.priceto}${$pricerange.priceto|round:0}{else}$10000{/if}
                {/if}
            </div> 
           
        </p>
        <div id="slider-range" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
            <div class="ui-slider-range bgcolor">
            </div>
                <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 23.6%;"></a>
                <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 50%;"></a>
        </div>
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
    $( "#slider-range" ).slider({
        range: true,
        step: step,
        min: min,
        max: max,
        values: [ {/literal}{if $pricerange.pricefrom}{$pricerange.pricefrom|round:0}{else}0{/if}, {if $pricerange.priceto}{$pricerange.priceto|round:0}{else}0{/if}{literal} ],
        slide: function (event, ui) {
            if (typeof window.sliderTimeout != 'undefined') {
                clearTimeout(window.sliderTimeout);    
            }
            if (valuta == 'грн')
                $("#amount").text(ui.values[ 0 ] + " грн" + " - " + ui.values[ 1 ] + " грн");
            else
                $("#amount").text("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
            
            if(!$("#rangefrom").length) {
                $('<input type="hidden" name="filter[rangefrom]" value="" id="rangefrom">')
                    .appendTo($('#filter-form'));
            }
            
            if(!$("#rangeto").length) {
                $('<input type="hidden" name="filter[rangeto]" value="" id="rangeto">')
                    .appendTo($('#filter-form'));
            }
            
            $("#rangefrom").val(ui.values[ 0 ]);
            $("#rangeto").val(ui.values[ 1 ]);
        },
        stop: function (event, ui) {
            window.sliderTimeout = setTimeout(function(){
                {/literal}{if $instant_filter}$('#filter-form').submit();{/if}{literal}    
            }, 2000);
        }
    });
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
    " - $" + $( "#slider-range" ).slider( "values", 1 ) );
});
{/literal}
</script>