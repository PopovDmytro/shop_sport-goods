 {if $mod eq 'home' && $act eq 'default'}
    <div class="slider">
        {if $sliderHome}
            <section id="slider-box">
                    <div id="slider-wrapper">
                        {foreach from=$sliderHome key=key item=slide}  
                            <div class="slaid">
                                <a href="{$slide.url}">
                                    <img src="{getimg type=main name=slider id=$slide.id ext=$slide.banner}">
                                    <div class="slider-heading">
                                        <p>{$slide.text}</p>
                                    </div>
                                </a>
                            </div>
                        {/foreach}
                    </div>       
            </section>
        {/if}
    </div>
{/if}

<div class="heading-block">
    <strong class="lider">Новинки</strong>
    <a href="{url mod=product act=archive feature=new}" class="all-view">Смотреть все</a>
</div>
 {*{include file="home/def_bestseller.inc.tpl"}*}
 <div class="product-plate">
     {include file="pcatalog/product.block.inc.tpl" productList=$new_productList imageList=$new_imageList categoryList=$new_categoryList prodColor=$new_color}
 </div>

 <div class="heading-block">
     <strong class="lider">Лидеры продаж</strong>
     <a href="{url mod=product act=archive feature=bestseller}" class="all-view">Смотреть все</a>
</div>
{*{include file="home/def_new.inc.tpl"}*}
 <div class="product-plate">
     {include file="pcatalog/product.block.inc.tpl" productList=$bestseller_productList imageList=$bestseller_imageList categoryList=$bestseller_categoryList prodColor=$bestseller_color}
 </div>

<div class="home-content">{$content}</div>


