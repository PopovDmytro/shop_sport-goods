<!DOCTYPE html>
<html>
<head>
    <title>RexDebugger</title>
    <script type="text/javascript" src="/system/framework/default_skin/admin/js/jquery-1.7.1.min.js"></script>
</head>
<body>
    <style type="text/css">
        body {
            width: 100%;
            height: 100%;
            position: absolute;
            margin: 0px;
        }
        iframe, input {
            display: block;
            width: 100%;
            border: none;
            margin: 0px;
            padding: 0px;
        }
        #site_location{
            background: #CCC;
        }
        #debug {
            height: 350px;
            background: #CCC;
        }
    </style>
    <input id="site_location" />
    <iframe id="site" name="site" src="/"></iframe>
    <iframe id="debug" name="debug" src="/rexdebug/engine/"></iframe>
    
<script type="text/javascript">

function process_cookie(window) {
    var split = window.document.cookie.split(';');
    var result = {};
    for (var i in split) {
        var kv_split = split[i].split('=');
        result[$.trim(kv_split[0])] = $.trim(kv_split[1]);
    }
    return result;
}
function set_cookie(window, key, value) {
    window.document.cookie = (key + '=' + value + ';path=/');
}

$(function(){
    var site_location = $('#site_location');
    var site = $('#site');
    var debug = $('#debug');
    
    var site_window = window.frames.site;
    var debug_window = window.frames.debug;
    
    var exist_cookie = process_cookie(site_window);
    var uin = exist_cookie.rexdebug_uin ? exist_cookie.rexdebug_uin : Date.now()+'_'+Math.ceil(Math.random() * 100000);
    $(site_window.document).ready(function(){
        //set_cookie(site_window, 'rexdebug', 'on');
        set_cookie(site_window, 'rexdebug_uin', uin);
        debug_window.rexdebug_uin = uin;
    })

    site.height(window.innerHeight - site_location.height() - debug.height());
    $(window).resize(function(event){
        site.height(window.innerHeight - site_location.outerHeight(true) - debug.outerHeight(true));
    });
    
    setInterval(function(){
        if (!site_location.is(':focus')) {
            site_location.val(site_window.location.toString());
        }
    }, 200);
    
    setInterval(function(){
        site_window.document.cookie = 'rexdebug_session = '+Date.now()+'_'+Math.ceil(Math.random() * 100000);
    }, 10);
    
    site_location.keydown(function(event){
        if (event.keyCode == 13) {
            site_window.location = $(this).val();
        }
    });
    
});
</script>
</body>
</html>