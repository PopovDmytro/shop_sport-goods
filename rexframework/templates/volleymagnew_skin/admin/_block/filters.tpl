{if isset($dates)}
    <td valign="top" style="padding-right: 10px;">
        <select class="filter" name="date_type" id="date_type">                
            {foreach from=$dates item=item key=key}
                <option value="{$key}">{$item}</option>
            {/foreach}
        </select>
    </td>
{/if}
<td valign="top" style="padding-right: 10px; height: 80px;">
    <input type="radio" id="radio_range" name="date_radio" value="range" method="#range|enable|change;#date_from|disable;#date_to|disable" />
    <select id="range" style="margin-bottom: 3px;">                
        <option value="today">Сегодня</option>
        <option value="yesterday">Вчера</option>
        {*<option value="last7">Последние 7 дней</option>
        <option value="last14">Последние 14 дней</option>*}
        <option value="this_week">Эта неделя</option>
        {*<option value="last_week">Прошлая неделя</option>*}
        <option selected="selected" value="this_month">Этот месяц</option>
        <option value="this_year">Этот год</option>               
    </select> <br />
    
    <input type="radio" name="date_radio" value="date" method="#range|disable;#date_from|enable;#date_to|enable" />
    <input id="date_from" name="date_from" class="datepicker filter" value="{$filters.date_from}" disabled="disabled" style="width: 65px;" />
    -
    <input id="date_to" name="date_to" class="datepicker filter" value="{$filters.date_to}" disabled="disabled" style="width: 65px;" /><br />
    <input id="default_date_range" type="radio" checked="checked" name="date_radio" class="filter" value="all" method="#range|disable;#date_from|disable;#date_to|disable" />&nbsp;&nbsp;Без учета даты
</td>
{if isset($users)}
    <td valign="top" style="padding-right: 10px;">
        <div style="margin: 14px 0px 0px 5px;">Пользователь:</div>
        <input id="user_list" class="ui-state-default" type="text" name="entity[user_name]" maxlength="64" value="" style="width: 200px; height: 20px; background-color: #FFFFFF !important;" />
        <input type="hidden" class="filter" name="user_id" value="" id="user_id">
    </td>
{/if} 

<script type="text/javascript">
{rexscript_start}
{literal}
    
    var datepicker_option = {
        closeText: 'Закрыть',
        prevText: '&#x3c;Пред',
        nextText: 'След&#x3e;',
        currentText: 'Сегодня',
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
        'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
        'Июл','Авг','Сен','Окт','Ноя','Дек'],
        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        weekHeader: 'Нед',
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '',
        hideIfNoPrevNext: true
        /*maxDate: '+0d' */
    };
    $('.datepicker').datepicker(datepicker_option);
    
    $('input[type=radio][method]').change(function(){
        var method = $.trim($(this).attr('method'));
        if (method != '') {
            exec_command(method);
        }
    });

    function exec_command(command) {
        command = command.split(';');
        for (var i in command) {
            var splits = command[i].split('|');
            if (splits.length > 1) {
                var element = $($.trim(splits[0]));
                for (var j = 1; j < splits.length; ++j) {
                    var action = $.trim(splits[j]);
                    switch (action) {
                        case 'enable':
                            element.removeAttr('disabled');
                            break;
                        case 'disable':
                            element.attr('disabled', 'disabled');
                            break;
                        default:
                            if (action.indexOf('(') < 0) {
                                //console.log('element.'+action+'();')
                                eval('element.'+action+'();');
                            } else {
                                //console.log('element.'+action+';');
                                eval('element.'+action+';');
                            }
                            break;
                    }
                }
            }
        }
    }
    
    $('#range').live('change', function(){
        var val = $(this).val();
        var curr_date = new Date();
        //curr_date.setMonth(5);
        //curr_date.setDate(16);
        var date_from = '';
        var date_to = '';
        if (val == 'today') {
            date_from = getDateString(curr_date);
            date_to = subDate(0, -1, curr_date);
        } else if (val == 'yesterday') {
            date_from = subDate(0, 1, curr_date);
            date_to = date_from;
        } /*else if (val == 'last7') {
            date_to = getDateString(curr_date);
            date_from = subDate(0, 7, curr_date);
        } else if (val == 'last14') {
            date_to = getDateString(curr_date);
            date_from = subDate(0, 14, curr_date);
        }*/ else if (val == 'this_week') {
            date_to = getDateString(curr_date);
            if (curr_date.getDay() > 0) {
                date_from = subDate(0, curr_date.getDay() - 1, curr_date);
            } else {
                date_from = date_to;
            }
        } /*else if (val == 'last_week') {
            if (curr_date.getDay() > 0) {
                subDate(0, curr_date.getDay(), curr_date);
            }
            
            //date_to = subDate(0, 1, curr_date);
            date_to = getDateString(curr_date);
            date_from = subDate(0, 7, curr_date);
        }*/ else if (val == 'this_month') {
            //curr_date.setDate(curr_date.getDate() - 1);
            date_to = getDateString(curr_date);
            curr_date.setDate(1);
            date_from = getDateString(curr_date);
        } /*else if (val == 'last_month') {
            curr_date.setDate(1);
            date_to = subDate(0, 2, curr_date);
            curr_date.setDate(1);
            date_from = getDateString(curr_date);
        }*/else if (val == 'this_year') {
            date_to = getDateString(curr_date);
            curr_date.setMonth(0, 1);
            curr_date.setDate(1);
            date_from = getDateString(curr_date);
        }
        
        $('input#date_from').val(date_from);
        $('input#date_to').val(date_to);
    });
    
    function subDate(months, days, curr_date) {
        if (curr_date.getDate() - days < 1) {
            var sub_days = curr_date.getDate() - days;
            
            if (curr_date.getMonth() - 1 < 1) {
                curr_date.setFullYear(curr_date.getFullYear() - 1);
                curr_date.setMonth(11);
                curr_date.setDate(getDays(curr_date.getMonth(), curr_date.getYear()) + 1 - Math.abs(sub_days));
            } else {
                curr_date.setMonth(curr_date.getMonth() - 1);
                curr_date.setDate(getDays(curr_date.getMonth(), curr_date.getYear()) + curr_date.getDate() - days);
            }
        } else {
            curr_date.setDate(curr_date.getDate() - days);
        }

        if (curr_date.getMonth() - months < 1) {
            curr_date.setFullYear(curr_date.getFullYear() - 1);
            curr_date.setMonth(12 + curr_date.getMonth() - months);
        } else {
            curr_date.setMonth(curr_date.getMonth() - months);
        }
        return getDateString(curr_date);
    }
    
    function getDateString(date) {
        return nulableString(date.getDate()) + '.' +
                nulableString(date.getMonth() + 1) + '.' +
                date.getFullYear();
    }
    
    function isLeapYear(year) {
         if (year % 4 == 0) return true;
         return false;
    }
    
    function nulableString(str) {
        str = str + '';
        if (str.length < 2) {
            str = '0' + str;
        }
        return str;
    }

    function getDays(month, year) {

        var ar = new Array(12);
        ar[0] = 31; // Январь
        ar[1] = (isLeapYear(year)) ? 29 : 28; // Февраль
        ar[2] = 31; // Март
        ar[3] = 30; // Апрель
        ar[4] = 31; // Май
        ar[5] = 30; // Июнь
        ar[6] = 31; // Июль
        ar[7] = 31; // Август
        ar[8] = 30; // Сентябрь
        ar[9] = 31; // Остябрь
        ar[10] = 30; // Ноябрь
        ar[11] = 31; // Декабрь
        return ar[month];
    }
    
    window.exec_command = exec_command;
    
    $(document).ready(function() {
        template.find('input#default_date_range').change();
        
        template.find("#user_list").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/admin/autocomplete/",
                    dataType: "json",
                    data: {
                        q: request.term,
                        limit: 20
                    },
                    success: function(data) {
                        response($.map(data.result, function(item) {
                            return {
                                label: item.name + ' ' + item.lname + " (" + item.email + ")",
                                value: item.name + ' ' + item.lname,
                                id: item.id
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                template.find('#user_id').val(ui.item.id);
            },
            open: function() {
                $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
            },
            close: function() {
                $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
            }
        });
    });
    

{/literal}
{rexscript_stop}
</script>