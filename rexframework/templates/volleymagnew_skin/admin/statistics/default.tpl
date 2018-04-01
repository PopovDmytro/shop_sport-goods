<label for="date-from">Дата с:</label>
<input name="date-from" id="date-from" class="date-picker" value="{if $currDateRange.date_from}{$currDateRange.date_from}{/if}"/>
<label for="date-to">По :</label>
<input name="date-to" id="date-to" class="date-picker" value="{if $currDateRange.date_to}{$currDateRange.date_to}{/if}"/>
<div id="statistics_chart_first"></div>
<div id="statistics_chart_second"></div>
<div id="statistics_chart_fourth"></div>
<div id="statistics_chart_fourth_totals"></div>
<div id="statistics_chart_thrid"></div>
{literal}
<script>
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function() {
        $('#date-from').trigger('change');
    });

    function drawChart(data, title, elementID, chartType) {
        if (!data || !elementID) {
            return false;
        }

        var data = google.visualization.arrayToDataTable(data);

        var options = {
            title: title,
            is3D: true,
            tooltip: {
                trigger: 'selection'
            },
            bar: {groupWidth: "20"}
        };

        var chart = new google.visualization[chartType](document.getElementById(elementID));
        chart.draw(data, options);
    }

    function drawMaterialColumns(data, title, elementID) {
        var data = google.visualization.arrayToDataTable(data);

        var options = {
          chart: {
            title: title,
            subtitle: ''
          },
          bars: 'vertical',
          vAxis: {format: 'decimal'},
          height: 400,
          colors: ['#1b9e77', '#d95f02'],
            bar: { groupWidth: "20%" }
        };

        var chart = new google.charts.Bar(document.getElementById(elementID));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }

    $(document).ready(function() {
        var dateFormat = 'yy-mm-dd',
                from = $('#date-from').datepicker({
                    defaultDate: '+1w',
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: dateFormat
                }).on('change', function () {
                    to.datepicker('option', 'minDate', getDate(this));
                }),
                to = $('#date-to').datepicker({
                    defaultDate: '+1w',
                    changeMonth: true,
                    numberOfMonths: 1,
                    dateFormat: dateFormat
                }).on('change', function () {
                    from.datepicker('option', 'maxDate', getDate(this));
                });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }

            return date;
        }

        $(document).on('change input paste', '#date-from', function () {
            var $this = $(this),
                    dateFrom = $this.val(),
                    dateTo = $('#date-to').val();

            if (!dateFrom || !dateTo) {
                return false;
            }

            $.rex('statistics', '', {date_from: dateFrom, date_to: dateTo}, function (stats) {
                if (!stats) {
                    alert('No data');
                } else {
                    stats = JSON.parse(stats);
                    for (chartIndex in stats) {
                        var chartData = stats[chartIndex];
                        if (chartData.chart_type === 'MaterialColumn') {
                            drawMaterialColumns(chartData.data, chartData.title, 'statistics_chart_' + chartIndex);
                        } else {
                            drawChart(chartData.data, chartData.title, 'statistics_chart_' + chartIndex, chartData.chart_type);
                        }
                    }
                    getOrdersSection(1);
                }
            });
        }).on('change input paste', '#date-to', function () {
            $('#date-from').trigger('change');
        });

        function getOrdersSection( page ) {
            var dateFrom = $('#date-from').val(),
                dateTo   = $('#date-to').val();

            if (!dateFrom || !dateTo) {
                return false;
            }

            var filters = {inpage: 20, page:page};

            $.rex('statistics', 'periodorders', {date_from: dateFrom, date_to: dateTo, filters: filters}, function (response) {
                if (response) {
                    console.log(response.dg);

                    $('#statistics_chart_fourth').html(response.dg);
                    $('#statistics_chart_fourth_totals').html(response.totals);


                    $('.dg_pager_container .dg_pager').die('click').live( 'click', function(){
                        var newPage = Number($(this).attr('page'));
                        getOrdersSection( newPage );
                    });
                }
            });
        }
    });
</script>
{/literal}
