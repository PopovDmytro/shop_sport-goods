<!DOCTYPE html>
<html>
    <head>
        <title>{page type='title'}</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link id="page_favicon" href="" rel="icon" type="image/x-icon">

        {css src="styleadmin.css"}
        {css src="shopadmin.css"}
        {*{css src="redmond/jquery-ui-1.8.17.custom.css"}*}
        {css src="smoothness/jquery-ui-1.8.17.custom.css"}
        {css src="jquery.Jcrop.css"}
        {css src="jquery.lightbox-0.5.css"}
        {css src="rex-ui-style.css"}
        {css src="ui.multiselect.css"}
        {css src="jquery.autocomplete.css"}

        {js src="jquery-1.7.1.min.js"}
        {js src="jquery-ui-1.8.17.custom.min.js"}
        {js src="ui.multiselect.js"}

        {js src="plugins/tmpl/jquery.tmpl.1.1.1.js"}
        {js src="plugins/blockUI/jquery.blockUI.js"}

        {js src="jquery.rex-0.0.9.js"}
        {js src="jquery.Jcrop.min.js"}
        {js src="jquery.lightbox-0.5.pack.js"}
        {include file="_block/init.js.tpl"}
        {js src="jquery.ajax-location.js"}
        {js src="admin.js"}
        {js src="password.js"}
        {js src="jquery.autocomplete.js"}
       

        {js src="ckeditor/ckeditor.js"}
        {js src="djenx.explorer/djenx-explorer.js"}

        {if $mod eq 'statistics'}
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        {/if}

        <script type="text/javascript">
            $.rex.base_path = '/admin/';

            function supportDialog() {
                $.showRexDialog('home', 'support', {}, 'supportdlg', 'Вопрос к разработчикам');
                var dialog = $.rexDialog('supportdlg');
                dialog.find('#savef').die('click').live('click', function(){
                    dialog.find('#supportf').rexSubmit(function(data){
                        $.rexDialog('supportdlg').find('#save_text').text('');
                        if(data !== false ) {
                              $.rexDialog('supportdlg').find('#save_text').text(data);
                        }
                    });
                })
            }
        </script>
    </head>
    <body {if $onLoad}{$onLoad}{/if}>

        <div class="support" id="support" style="display: none;">
            <iframe name="support" id="supportf" width="640" height="320"></iframe>
        </div>
        <div id="container">
        <div id="header">
            <div class="firm">
                {img src="admin/firm.png" width="182" height="77" usemap="#spage"}
                <map id="spage" name="spage">
                    <area shape="circle" coords="85, 22, 23" href="javascript:supportDialog()" title="Задать вопрос разработчикам" />
                    <area shape="circle" coords="122, 24, 23" href="javascript:supportDialog()" title="Задать вопрос разработчикам" />
                    <area shape="circle" coords="91, 54, 23" href="javascript:supportDialog()" title="Задать вопрос разработчикам" />
                </map>
            </div>
            <br/>
            <a href="{'Environment.frontend.link'|config}">Главная страница сайта {'name'|config}</a>&nbsp;|&nbsp;
            <a href="{$DOMAIN}/content/attach/REXShop.doc">Документация</a>&nbsp;|&nbsp;
            {if $user->id}
            <a href="{url mod=user act=logout}" class="about">[{$user->login}] Выход</a>
            {/if}
        </div>

        <div id="content">

            {if $user->id}
            <div id="left">
                <div class="menublock">
                    <div class="title">
                        <div><h1>Меню {a href="{url mod=home}"}инфо{/a}</h1></div>
                    </div>
                    {workspace section=menu}
                    <div class="bottom"><div></div></div>
                </div>
            </div>
            {/if}

            <div id="right">
                <div class="rightcontent">
                    <div class="filters">
                        {page type='getRenderedMessages'}
                        {page type='getRenderedErrors'}

                        {workspace}
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-shadow"></div>
        <div class="clr"></div>
        <div class="footer"><div>Copyright <a href="http://www.rexengine.ru/">RexEngine</a> 2005-{$smarty.now|date_format:"%Y "}</div></div>
    </body>
</html>