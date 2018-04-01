<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    {*META*}
    <title>{page type='title'}</title>
    <meta name="description" content="{page type='description'}">

    {*CSS*}
    {css src="styles.css"}
    {css src="styles_free.css"}
    {css src="jquery.lightbox-0.5.css"}
    {css src="jquery.carousel.css" }
    {css src="jquery.autocomplete.css" }   
    {css src="superfish_new.css" }
    {css src="jquery.ui.all.css" }
    {css src="jquery.rex.tooltip.css" }
    
    {*JS*}
    {js src="main.js"}
    {js src="jquery-1.7.1.min.js"} 
    {js src="jquery.jcarousel.min.js"}
    {js src="jquery.autocomplete.js"}
    {js src="jquery.lightbox-0.5.pack.js"}
    {js src="jquery.superfish.js"}
    {js src="jquery.rex-0.0.9.js"}
    {js src="jquery.rex.tooltip.js"}

    {js src="jquery.ui.core.js"}
    {js src="jquery.ui.widget.js"}
    {js src="jquery.ui.mouse.js"}
    {js src="jquery.ui.slider.js"}
    {js src="jquery.bxSlider.min.js"}

    

    <script type="text/javascript">
    {literal}
    
        var ajax_paging = {/literal}{if "ajax_paging"|settings == "true"}1{else}0{/if}{literal};
    
        $(document).ready(function (){
            smartyresize(); //после загрузки страницы
            SetHeight();
            sideRightMargin();
            productPage();
            /* блок слайдера */
            var bxSliderElem = $('#slider-wrapper').bxSlider({
                    displaySlideQty: 1,
                    moveSlideQty: 1,
                    auto: true,
                    pager: false,
                    pause: 8000
            });
            $('a.arrow_next').click(function(event){
                bxSliderElem.stopShow();
                $('a.bx-next').click();
                bxSliderElem.startShow();
                event.preventDefault();
            });
            $('a.arrow_prev').click(function(event){
                bxSliderElem.stopShow();
                $('a.bx-prev').click();
                bxSliderElem.startShow();
                event.preventDefault(); 
            });
            $('.slider-block-inner').mouseenter(function(){
                bxSliderElem.stopShow();
                $(this).addClass('slider-active');
                var pObject = $(this).find('p'); 
                if (!pObject.is(':visible')) {
                    pObject.show();
                    $(this).stop(true).animate({
                        height: $(this).find('h3').outerHeight(true)+pObject.outerHeight(true)    
                    }, 500);
                }    
            }).mouseleave(function(){
                if ($(this).hasClass('slider-active')) {
                    $(this).removeClass('slider-active');
                    var pObject = $(this).find('p'); 
                    if (pObject.is(':visible')) {
                        $(this).stop(true).animate({
                            height: $(this).find('h3').outerHeight(true)    
                        }, 500, function(){
                            pObject.hide();
                            bxSliderElem.stopShow();
                            bxSliderElem.startShow();
                        });
                    }    
                }
            });
            /* конец блока слайдера */
            generateLightbox(); // генерация лайтбокса
            /* блок автокопмлита */
            $("#search").autocomplete("/autocomplete/", {
                selectFirst: false,
                minLength: 2,
                width: 357,
                scrollHeight: 400,
                max: 30,
                formatItem: function(data, i, n, value) {                
                    image = value.split('=')[0];
                    product = value.split('=')[1];
                    name = value.split('=')[2];
                    //onclick=\'document.location.href="/product/'+product+'"\'
                    {/literal}
                        return '<a href="javascript: void();" ><div style="width:80px; padding-right: 10px; float: left; text-align: center;"><img src="'+image+'" style="vertical-align: middle;width: 60px;height: 60px;"/></div><div style="padding-top: 13px;">'+name+'</div></a>';
                    {literal}
                }, formatResult: function(data, i, n) {
                        return i.split('=')[2];
                }
            }).result(function(event, item) {
                location.href = '/product/'+item[0].split('=')[1];
                return false;
            });
            /* конец блока автокомплита */
            /* блок для мини-слайдера */
            $("#slider-range").slider({
                range: true,
                min: 0,
                max: 500,
                values: [ 75, 300 ],
                slide: function( event, ui ) {
                    $( "#amount" ).val( "грн" + ui.values[ 0 ] + " - грн" + ui.values[ 1 ] ); 
                }
            });
            $("#amount").val("грн" + $( "#slider-range" ).slider("values", 0 ) +
            " - грн" + $( "#slider-range" ).slider( "values", 1 ) );
            /* конец блока для мини-слайдера */
            
            $('[rexTitle]').rexTooltip({
                position: 'top',
                titleAttr: true,
                top: -5,
                addedClass: 'rex-title'    
            });
        });
        
        $.rex.base_path = '/';
        
        rexTooltipProp = {
            predelay: 500,
            speed: 'fast',
            afterBody: true,
            quickShow: true
        };
        
        searchField();
        
        var tocompare_img = '{/literal}{img src="kn3.jpg"}{literal}';
        var tocompare_anc = '{/literal}{url mod=product act=compare}{literal}';
        
        $(window).resize(function () { //после каждого изменения размера экрана
           smartyresize();
           productPage();
           sideRightMargin();
           searchField();
        });
        
        $('#mycarousel').jcarousel({
             wrap: 'circular',
             auto: 1,
             scroll: 1,
             initCallback: mycarousel_initCallback,
             buttonNextHTML: null,
             buttonPrevHTML: null
         });

         $('ul.sf-menu').superfish({
             hoverClass: 'sfHover',
             pathClass: 'overideThisToUse',
             pathLevels: 2,
             delay: 100,
             animation: {
                 opacity: 'show'
             },
             speed: 'normal',
             autoArrows: true,
             dropShadows: true,
             disableHI: false,
             onInit: function () {},
             onBeforeShow: function () {},
             onShow: function () {},
             onHide: function () {}
         });


         $('.attributes tbody tr').each(function (n) {
             if (n % 2 == 0) {
                 $(this).addClass('odd');
             }
         });

         $('.compare tbody tr').each(function (n) {
             if (n % 2 == 1) {
                 $(this).addClass('odd');
             }
         });
         
        function smartyresize() {
            var width_list = $("#container").width(); 
            var count_all=$("#content > *").length;
            if ((count_all > 0) && (width_list > 176)) {
                var width_item=$(".item").width();
                var margin_r=parseInt($(".item").css('marginRight'));
                var count = Math.floor(width_list/(width_item+margin_r*2));
                var count_event=$(".event > *").length;
                var count_item = count_all - count_event;
            }  
        }
        function sideRightMargin(){
            var width = $("#wrapper").width(); 
            if(width < 887){
                var height = $("#sideLeft").height();
                $("#sideRight").css('margin-top', height+10);
            } else {
                 $("#sideRight").css('margin-top', 0);
            } 
        }     
        function SetHeight(){
             var height = $(".item").height();
             $(".item").each(function(){
                 var height2 = $(".item").height();
                 if(height2 > height){
                    height=height2;
                 }
             });
             $(".item").each(function(){
                 $(this).css('min-height', height);
             });
        } 
        function showSubMenuByClass(id) {
            if(typeof $('.sidebar .sub-m'+id) != 'undefined') {
                if($('.sidebar .sub-m'+id).is(":visible")) {
                $('.sidebar .sub-m'+id).hide();
                $('.sidebar .sub-m'+id).css({'padding':'6px 6px 6px 15px'});
                } else {
                $('.sidebar .sub-m'+id).show();
                $('.sidebar .sub-m'+id).css({'padding':'6px 6px 6px 15px'});
                }
            }
            sideRightMargin();
        }  
        function productPage(){
             var product = $.find(".product").length; 
             var category = $.find(".category").length; 
             var wrap = $("#wrapper").width(); 
             if({/literal} "{$mod}" {literal} =='home' && {/literal} "{$act}" {literal} == 'default'){
                 //$('#middle').css('border-right','none');
                 if(wrap <335){
                    $('#sideLeft').css('display','block'); 
                    $(' #container').css('display','none');             
                 }else{
                    $('#container').css('display','block');  
                 }
             }  
        }
        function mycarousel_initCallback(carousel) {
             $('.jcarousel-control a').bind('click', function () {
                 carousel.scroll($.jcarousel.intval($(this).text()));
                 return false;
             });

             carousel.clip.hover(function () {
                 carousel.stopAuto();
             }, function () {
                 carousel.startAuto();
             });

         };
         function generateLightbox()
         {
             var uniqGallery = [];
             $('a.gallery').each(function(index){
                 var notUnique = 0;
                 for (var i in uniqGallery) {
                     if (uniqGallery[i] == $(this).attr('href')) {
                        $(this).addClass('not-unique');
                        notUnique = 1;    
                     }
                 }
                 if (notUnique == 0) {
                     $(this).removeClass('not-unique');
                     uniqGallery[index] = $(this).attr('href');
                 }
             });
             $('a.gallery:not(.not-unique)').lightBox({
                imageLoading: '/system/shop/default_skin/frontend/img/lightbox-ico-loading.gif',
                imageBtnClose: '/system/shop/default_skin/frontend/img/lightbox-btn-close.gif',
                imageBtnPrev: '/system/shop/default_skin/frontend/img/lightbox-btn-prev.gif',
                imageBtnNext: '/system/shop/default_skin/frontend/img/lightbox-btn-next.gif'
            });    
         }
         function searchField() {
            var search = $('#search');
            /*search.width(235 + $('#header_center').width());  */      
         }
         
         $(function (){
             $('.select-custom').bind('click',function(){
                 if (name.length > 6) {
                    $(this).text(name);
                 }
                 $('.cat-search').css('display', 'inline-block');
                 heightul = $('.cat-search').height();
                 $('.cat-search').css('height', '1px');
                 widthli = $('.cat-search li').width()+20;
                 $(this).animate({width: widthli});
                 $('.cat-search').animate({height: heightul});
             });
              $('.cat-search li').bind('click',function(){  
                 search = $('.search');  
                 search.find('.cat-search').css('display', 'none');
                 texts = '';   
                 name = $(this).text();
                 if (name.length > 7) {
                     texts = name.slice(0,7)+'...';
                 } else {
                     texts = name;
                 }                  
                 search.find('.select-custom').text(texts);
                 search.find('#cat_id').val($(this).val());
                 search.find('.select-custom').animate({width: '80px'});
             });
             $('.cat-search').bind('mouseleave', function() {
                 search = $('.search');
                 search.find('.select-custom').animate({width: '80px'});
                 search.find('.cat-search').css('display', 'none'); 
                 if (name.length > 7) {
                     texts = name.slice(0,7)+'...';
                 } else {
                     texts = name;
                 }
                 search.find('.select-custom').text(texts);
                 search.find('.cat-search').css('display', 'none');  
             });

             if (parseInt(ajax_paging) == 1) {
                 $(document).on('click.getAjaxPaging', 'div.ajax-paging', function(){
                    var $link = $('div.pagination div.pagination_div').next('.pagin-item');
                    var number_page = $link.find('a').html();
                    $.post($link.find('a').attr('href'), {rex_request: 1}, function(data){
                        var json_response = $.parseJSON(data);
                        if (json_response !== false) {
                            $('div.item:last').after(json_response.content.content);
                            $link.removeClass('pagin-item').addClass('pagination_div').html('<b>'+number_page+'</b>');
                            var countNext = parseInt(json_response.content.count_next);
                            hideShowNext(countNext);
                        }   
                    }); 
                 });
                 
                 var hideShowNext = function(countNext){
                    var $next = $('div.pagination div.pagination_div').next('.pagin-item');
                    if (!$next.length) {
                        return $('div.ajax-paging').remove();    
                    }
                    var stringNext = '';
                    if (countNext == 1) {
                        stringNext = 'Загрузить ещё 1 товар';   
                    } else if (countNext  < 5) {
                        stringNext = 'Загрузить ещё '+countNext+' товара';
                    } else {
                        stringNext = 'Загрузить ещё '+countNext+' товаров';
                    }
                    $('div.ajax-paging').html(stringNext);  
                 };
                 
                 hideShowNext(parseInt({/literal}{$count_next}{literal}));
             }
         });
         
    {/literal}
    </script>

</head>

<body> 
    <div class="conteiner-box">
        <div class="header">
            <div class="header-block">
                <div class="logo-box"> <a href="{url mod=home act=default}"><div class="logo-title">Illusix</div><div class="logo-text">интернет магазин</div> </a></div>
                <div class="top-menu">
                    <ul>
                        <li><div class="top-menu-logo"></div></li>
                        {if $user->id}
                            <li><a href="{url mod=user id=$user->id}">Профиль [{$user->login}]</a></li>
                            <li><a href="{url mod=user act=logout}">Выход</a></li>    
                        {else}
                            <li><a href="{url mod=user act=login}">Вход</a></li>
                            <li><a href="{url mod=user act=registration}">Регистрация</a></li>
                        {/if}
                    </ul>
                </div>
                <div class="search">
                        <form method="post" action="{url mod=pCatalog act=search}">
                            {if $mainCategoryList}
                                <div class="select-custom">
                                      Все
                                     
                                 </div>
                                  
                                 <div class="clear"></div>
                                 <input type="hidden" id="cat_id" name="category" value="0" />
                                 <ul class="cat-search"> {*class="cat-search"*}
                                        <li value="0">Все</li>
                                        {foreach from=$mainCategoryList item=mainCategory}
                                            <li value="{$mainCategory.id}" {if isset($smarty.session.search_category) and $smarty.session.search_category eq $mainCategory.id}{/if}>{$mainCategory.name}</li>
                                        {/foreach}
                                    </ul>
                                 
                            {/if}
                            <input id="search" name="q" onblur="{literal}javascript: if (this.value=='') {this.value='ПОИСК';}" onfocus="javascript: if (this.value=='' || this.value=='ПОИСК') {this.value='';}{/literal}" value="{if $q}{$q}{else}ПОИСК{/if}" />   
                            <input name="search-submit" class="search-submit" type="submit" value="" />
                        </form>
                </div> 
                <div class="menu heders-menu">
                    <a href="{url mod=home act=default}" {if $mod eq "home" && $act eq "default"}class="active"{/if}>Главная</a>
                    <a href="{url mod=staticPage act=default task=about}" {if $mod eq "staticPage" and $task eq "about"}class="active"{/if}>О нас</a>
                    <a href="{url mod=product act=compare}" {if $mod eq "product" and $act eq "compare"}class="active"{/if}>Сравнение{if $compare_count}<div class="compare-count">{$compare_count}</div>{/if}</a>
                    <a href="{url mod=home act=contact}" {if $mod eq "home" and $task eq "contact"}class="active"{/if}>Контакты</a>
                </div> 
                <div class="cart-block">
                    {include file="cart/cart.header.tpl"} 
                </div>
             </div>   
        </div><!--header-end-->
        {if $mod eq 'home' && $act eq 'default'}
            {if $sliderHome}
                <section id="slider-box">
                    <div class="slider">
                        <a class="arrow_prev" href="javascript:void(0);"></a>
                        <div id="slider-wrapper">
                            {foreach from=$sliderHome key=key item=slide}  
                                <div class="slaid{$key}">
                                    <a href="{$slide.url}">
                                        <img src="{getimg type=main name=slider id=$slide.id ext=$slide.banner}">
                                        <div class="slider-block-inner">
                                            <h3>{$slide.name}</h3>
                                            <p>{$slide.text}</p>
                                        </div>
                                    </a>
                                </div>
                            {/foreach}
                        </div>       
                        <a class="arrow_next" href="javascript:void(0);"></a>
                    </div>
                </section>
            {/if}
        {/if}
        <div id="wrapper">
            <section id="middle">
                <div id="container">
                            {workspace}
                </div><!-- #container-->
                <aside id="sideLeft">
                    <div class="sidebar">
                        <div class="sidebar-box">
                            {workspace section=menu}
                        </div>
                    </div>
                </aside>
            </section><!-- #middle-->
               <div class="bottom-text-section">
                      {$staticpage->content}
               </div>
        </div> <!--#wrapper-->
    </div>
     <div class="footer">
        <div class="footer-box"    
            <div class="copyright">
                &copy; Copyright 2005-{$smarty.now|date_format:"%Y"} <a href="http://www.illusix.com/">Illusix</a>. Все права защищены.
            </div>         
        </div>    
     </div> <!--footer-->
</body>
</html>