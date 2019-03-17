<li data-accordion-item class="accordion-item is-active">
    <a href="javascript:void(0)" class="accordion-title">Цена</a>
    <div data-tab-content class="accordion-content is-active">
        {if $pcatalog}
            {assign var=url value="`$pcatalog->alias`/"}
        {else}
            {assign var=url value=""}
        {/if}
        <div class="categories_price-holder">

            {*slider price*}
            <div class="dotted"></div>
            <div class="categories_range-slider demo" >
                <div id="amount" type="text" name="pcatalog[range]">
                    {if $valuta eq 'грн'}
                        <div class="range-inputs flex-container">
                            <div class="">
                                {if $pricerange.pricefrom}
                                    <input class="priceFrom" type='text' value="{$pricerange.pricefrom - 1|floor}">
                                {else}
                                    <input class="priceFrom" type='text' value="0">
                                {/if} <span>грн</span>
                            </div>
                            <span>|</span>
                            <div class="">
                                {if $pricerange.priceto}
                                    <input class="priceTo" type="text" value="{$pricerange.priceto + 1|floor}">
                                {else}
                                    <input class="priceTo" type="text" value="100000">
                                {/if} <span>грн</span>
                            </div>
                        </div>
                        <div id="slider-range" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                            <div class="slider-fill ui-slider-range"></div>
                            <a class="slider-handle ui-slider-handle ui-state-default ui-corner-all" href="#" ></a>
                            <a class="slider-handle ui-slider-handle ui-state-default ui-corner-all" href="#" ></a>
                        </div>
                    {elseif $valuta eq '$'}
                        ${if $pricerange.pricefrom}
                        <input class="priceFrom" type='text' value="{$pricerange.pricefrom - 1|floor}">
                    {else}
                        <input class="priceFrom" type='text' value="0">
                    {/if}
                        <div id="slider-range" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                            <div class="slider-fill ui-slider-range"></div>
                            <a class="slider-handle ui-slider-handle ui-state-default ui-corner-all" href="#" ></a>
                            <a class="slider-handle ui-slider-handle ui-state-default ui-corner-all" href="#" ></a>
                        </div>
                        ${if $pricerange.priceto}
                        <input class="priceTo" type="text" value="{$pricerange.priceto + 1|floor}">
                    {else}
                        <input class="priceTo" type="text" value="10000">
                    {/if}
                    {/if}

                    <select name="filter[price_order]">
                        <option value="DESC" {if $price_order eq 'DESC'}selected{/if}>По убыванию</option>
                        <option value="ASC"  {if $price_order eq 'ASC'}selected{/if}>По возрастанию</option>
                    </select>
                </div>

            </div>
            <script type="text/javascript">
                {literal}
                var valuta = '{/literal}{$valuta}{literal}';
                var min = {/literal}{if $pricerange.rangefrom}{$pricerange.rangefrom - 1|round:0}{else}0{/if}{literal};
                var max = {/literal}{if $pricerange.rangeto}{$pricerange.rangeto + 1|round:0}{else}0{/if}{literal};
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
                        values: [ {/literal}{if $pricerange.pricefrom}{$pricerange.pricefrom - 1|round:0}{else}0{/if}, {if $pricerange.priceto}{$pricerange.priceto + 1|round:0}{else}0{/if}{literal} ],
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

                        return value
                    }
                });
                {/literal}
            </script>
            {**}
        </div>
    </div>
</li>