<div class="sidebar-holder">
    <div class="sidebar-box category">
        <div class="wrapper">
            <div class="holder">
                <h2>Категории</h2>
                {include file="pcatalog/menu.inc.tpl"}
                <div class="compare-wrapper">
                    <a href="{url mod=product act=compare}" class="compare">Сравнение товаров</a>
                </div>
            </div>
        </div>
    </div>

    {*<form id="filter-form" action="{url route=shop_fsearch task=$pcatalog->alias uri=''}" name="filter-form" method="get">*}
        

            <div class="sidebar-box news">
                <div class="wrapper">
                    <div class="holder">
                        <h2><a href="{url mod=news act=archive}"> Новости </a></h2>

                        {include file="news/list.tpl"}
         
                        <a href="{url mod=news act=archive}" class="all-news">Читать все новости</a>
                    </div>
                </div>
            </div>

    {*</form>*}
    <div class="fb_social">
        <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fvolleymag&amp;width=175&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true&amp;appId=576153869165908" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:175px; height:290px;" allowTransparency="true"></iframe>
    </div>
    <div class="clear"></div>
    {*<script type="text/javascript" src="//vk.com/js/api/openapi.js?113"></script>
    <!-- VK Widget -->
    <div id="vk_groups"></div>
    <script type="text/javascript">
        {literal}
        VK.Widgets.Group("vk_groups", {mode: 0, width: "175", height: "300", color1: 'f3f3f3', color2: '2B587A', color3: '5B7FA6'}, 51264173);
        {/literal}
    </script>
    *}
</div> 
