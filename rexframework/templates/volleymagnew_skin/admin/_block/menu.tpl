<div class="content" id="ulmenu">
    <ul>
        {if $user->role eq 'admin'}
            <li style="font-weight: bold; margin-left: -10px"><b>маркет</b></li>
            <li {if $mod eq 'product'}class="selected"{/if}>{a href="{url mod=product}&page=1"}Продукция{/a}</li>
            <li {if $mod eq 'pCatalog'}class="selected"{/if}>{a href="{url mod=pCatalog}"}Каталог{/a}</li>
            <li {if $mod eq 'attribute'}class="selected"{/if}>{a href="{url mod=attribute}"}Атрибуты{/a}</li>
            <li {if $mod eq 'brand'}class="selected"{/if}>{a href="{url mod=brand}"}Бренды{/a}</li>
            <li {if $mod eq 'technology'}class="selected"{/if}>{a href="{url mod=technology}"}Технологии{/a}</li>
            <li {if $mod eq 'order'}class="selected"{/if}>{a href="{url mod=order order_status=12}"}Заказы{/a}</li>
            <li {if $mod eq 'statistics'}class="selected"{/if}>{a href="{url mod=statistics}"}Статистика{/a}</li>

            <li style="font-weight: bold; margin-left: -10px"><b>наполнение</b></li>
            <li {if $mod eq 'article'}class="selected"{/if}>{a href="{url mod=article}"}Статьи{/a}</li>
            <li {if $mod eq 'news'}class="selected"{/if}>{a href="{url mod=news}"}Новости{/a}</li>
            <li {if $mod eq 'staticPage'}class="selected"{/if}>{a href="{url mod=staticPage}"}Страницы{/a}</li>
            <li {if $mod eq 'slider'}class="selected"{/if}>{a href="{url mod=slider}"}Слайдер{/a}</li>
            <li {if $mod eq 'comment'}class="selected"{/if}>{a href="{url mod=comment}"}Комментарии{/a}</li>

            <li style="font-weight: bold; margin-left: -10px"><b>системные</b></li>
            <li {if $mod eq 'user'}class="selected"{/if}>{a href="{url mod=user}"}Пользователи{/a}</li>
            <li {if $mod eq 'user'}class="selected"{/if}>{a href="{url mod=subscriber}"}Подписчики{/a}</li>
            <li {if $mod eq 'settings'}class="selected"{/if}>{a href="{url mod=settings}"}Настройки{/a}</li>
            <li {if $mod eq 'home' and $act eq 'mailing'}class="selected"{/if}>{a href="{url mod=home act=mailing}"}Рассылка{/a}</li>
        {else}
            <li style="font-weight: bold; margin-left: -10px"><b>маркет</b></li>
            <li {if $mod eq 'product'}class="selected"{/if}>{a href="{url mod=product}"}Продукция{/a}</li>
            <li {if $mod eq 'order'}class="selected"{/if}>{a href="{url mod=order}"}Заказы{/a}</li>

            <li style="font-weight: bold; margin-left: -10px"><b>наполнение</b></li>
            <li {if $mod eq 'article'}class="selected"{/if}>{a href="{url mod=article}"}Статьи{/a}</li>
            <li {if $mod eq 'news'}class="selected"{/if}>{a href="{url mod=news}"}Новости{/a}</li>
            <li {if $mod eq 'slider'}class="selected"{/if}>{a href="{url mod=slider}"}Слайдер{/a}</li>
            
            <li style="font-weight: bold; margin-left: -10px"><b>системные</b></li>
            <li {if $mod eq 'user'}class="selected"{/if}>{a href="{url mod=user}"}Пользователи{/a}</li>
            <li {if $mod eq 'user'}class="selected"{/if}>{a href="{url mod=subscriber}"}Подписчики{/a}</li>
            <li {if $mod eq 'home' and $act eq 'mailing'}class="selected"{/if}>{a href="{url mod=home act=mailing}"}Рассылка{/a}</li>
        {/if}
    </ul>
</div>
<script>
    var title = document.title,
            new_title = '',
            count = 0,
            favicon_href = '';

    var newOrderChecker = setInterval(function () {
        $.rex('order', 'new', {}, function (data) {
            count = parseInt(data);
            if (count == 0) {
                new_title = title;
                favicon_href = '';
            } else {
                if (count == 1) {
                    new_title = count + ' новий заказ';
                } else {
                    if (count > 1 && count < 5) {
                        new_title = count + ' нових заказа';
                    } else {
                        if (count >= 5) {
                            new_title = count + ' нових заказов';
                        }
                    }
                }
            }
        });

        var titleChanger = setInterval(function () {
            if (isNaN(count)) {
                clearInterval(titleChanger);
            }
            document.title = (document.title == new_title ? title : new_title);
            if (count != 0) {
                favicon_href = favicon_href == '' ? '/content/images/favicon.ico' : '';
                chFavicon(favicon_href);
            }
        }, 500);
    }, 30000);

    function chFavicon(iconHref) {
        var icon = $(":[rel='icon']");
        var cache = icon.clone();
        cache.attr("href", iconHref);
        icon.replaceWith(cache);
    }

    var sendInterval = function() {
        var intervalID = setInterval(function () {
            $.rex('home', 'progressbar', {}, function (data) {
                if (data) {
                    if (data == 100) {
                        clearInterval(intervalID);
                        jQuery('#myBar').css('width', '100%');
                        jQuery('#percent-value').html('Рассылка успешно произведена');
                    } else {
                        jQuery('.progressbar-row').show();
                        jQuery('#myBar').css('width', parseInt(data, 10) + '%');
                        jQuery('#percent-value').html(parseInt(data, 10) + '%');
                    }
                }
            });
        }, 30000);
    };

    jQuery(document).ready(function($) {
        $('#mailing-form').submit(function() {
            setTimeout(function() {
                document.location.reload();
            }, 500);
        });

        sendInterval();

        $('#mailing-form select.mailing-role').change(function(){
            if ($(this).val() == 'user') {
                $('.user-order-statuses').show();
            } else {
                $('.user-order-statuses').hide();
            }
        });
    });

</script>