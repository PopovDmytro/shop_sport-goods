<!DOCTYPE html>
<html>
<head>
    <title>RexDebugger</title>
    <script type="text/javascript" src="/system/framework/default_skin/admin/js/jquery-1.7.1.min.js"></script>

    <link rel="stylesheet" href="/system/framework/default_skin/admin/js/CodeMirror-2.33/lib/codemirror.css">
    <script src="/system/framework/default_skin/admin/js/CodeMirror-2.33/lib/codemirror.js"></script>
    <script src="/system/framework/default_skin/admin/js/CodeMirror-2.33/mode/php/php.js"></script>
    <script src="/system/framework/default_skin/admin/js/CodeMirror-2.33/mode/css/css.js"></script>
    <script src="/system/framework/default_skin/admin/js/CodeMirror-2.33/mode/javascript/javascript.js"></script>
    <script src="/system/framework/default_skin/admin/js/CodeMirror-2.33/mode/xml/xml.js"></script>
    <script src="/system/framework/default_skin/admin/js/CodeMirror-2.33/mode/clike/clike.js"></script>

</head>
<body>
    <style type="text/css">
        html, body{
            margin: 0px;
            padding: 0px;
        }
        .b0{
            height: 350px;
            overflow: hidden;
        }
        .b1{
            height: 330px;
            overflow: hidden;
        }
        .b2{
            height: 310px;
            overflow: hidden;
        }
        .b3{
            height: 290px;
            overflow: hidden;
        }
        .caption{
            height: 19px;
            overflow: hidden;
            border-width: 0px 0px 1px 0px;
            border-style: solid;
            border-color: black;
        }
        .right{
            width: 30%;
            float: right;
        }
        .hidden{
            display: none;
        }
        .scrollable{
            overflow: auto !important;
        }
        .CodeMirror {
            background-color: #FFFFFF;
        }
        .CodeMirror-gutter {
            min-width: 4.2em;
        }
        .CodeMirror-gutter-text pre.valid {
            color: #26A83E;
        }
        .CodeMirror pre.code_bpoint {
            background-color: #D8FFEF
        }
        .CodeMirror pre.code_curline {
            background-color: #FF7A7A !important;
        }
        .CodeMirror pre.code_bpoint.active {
            background-color: #7AFFCC
        }
        #command_buttons a{
            margin-right: 5px;
            font-size: 12px;
        }
        .tree {
            line-height: 11px;
        }
        .tree span.line{
            display: inline-block;
            width: 12px;
            height: 9px;
            background: url('/system/framework/default_skin/admin/img/rexdebug/line-hor.gif') no-repeat -5px 0px;
        }
        .tree div.object span.line{
            width: 17px;
            background: url('/system/framework/default_skin/admin/img/rexdebug/line-hor.gif') no-repeat 0px 0px;
        }
        .tree, .tree * {
            font-family: monospace;
            font-size: 11px;
        }
        .tree div.object{
            padding-left: 6px;
            background: url('/system/framework/default_skin/admin/img/rexdebug/line-up.gif') repeat-y 0px 0px;
        }
        .tree div.object div.object{
            margin-left: 5px;
        }
        .tree div.noobject{
            padding-left: 8px;
        }
        .tree input {
            display: none;
        }
        .tree label{
            padding-left: 12px;
            background: url('/system/framework/default_skin/admin/img/rexdebug/pm.gif') no-repeat -5px -18px;
        }
        .tree div.object label{
            padding-left: 17px;
            background-position: 0px -18px;
        }
        .tree input:checked + label {
            background-position: -5px 2px;
        }
        .tree div.object input:checked + label {
            background-position: 0px 2px;
        }
        .tree input:checked + label + div.object {
            height: 0px;
            overflow: hidden;
        }
        a.tabs_control{
            color: gray;
            font-size: 12px;
            margin-left: 5px;
        }
        a.tabs_control.active, a.tabs_control:hover{
            color: blue;
        }
        .red{
            color: red;
            float: right;
            margin-right: 5px;
            font-size: 12px;
        }
        #src_tree{
            overflow: auto;
        }
        #src_container{
            float: left;
            width: 70%;
        }
        span[path]{
            cursor: pointer;
        }
        span[path]:hover{
            color: red;            
        }
        .clicked{
            color: blue;
        }
    </style>

<?php
function tree($arr, $level = 0)
{
    static $cnt = 0;
    $html = '';
    foreach ($arr as $item => $value) {
        if (is_array($value)) {
            $html .= '<input type="checkbox" checked="checked" id="checktree_'.(++$cnt).'" /><label for="checktree_'.$cnt.'">'.$item.'</label>';
            $html .= tree($value, $level + 1);
        } else {
            $html .= '<span class="line"></span><span path="'.$value.'">'.$item.'</span><br />';
        }
    }
    if ($level && $html != '') {
        $html = '<div class="object">'.$html.'</div>';
    }
    return $html;
}
?>    

<div id="wrapper" class="b0">
    <div id="wrapper_tabs_control" class="caption">
        <a class="tabs_control active" tab="wrap_debug" href="javascript: void(0);">Script</a>
        <a class="tabs_control" tab="wrap_tree" href="javascript: void(0);">Tree</a>
    </div>
    <div id="wrapper_tabs" class="b1">
        <div id="wrap_debug" class="tabs">
            <div id="nodebug">
                No debug now
                <a class="debugon red" href="javascript: void(0);">Debug ON</a>
                <a class="debugoff red hidden" href="javascript: void(0);">Debug OFF</a>
            </div>
            <div id="command" class="caption">
                <div id="command_buttons" class="right">
                    <a id="run" href="javascript: void(0);">Run</a>
                    <a id="stepinto" href="javascript: void(0);">Step Into</a>
                    <a id="stepover" href="javascript: void(0);">Step Over</a>
                    <a id="stepout" href="javascript: void(0);">Step Out</a>
                    <a class="debugoff red hidden" href="javascript: void(0);">Debug OFF</a>
                    <a id="stop" class="red" href="javascript: void(0);">Stop</a>
                </div>
                <div id="code_info"></div>
            </div>
            <div id="info" class="b2 right">
                <div id="info_commands" class="caption">
                    <a class="tabs_control active" tab="info_vars" href="javascript: void(0);">Var</a>
                    <a class="tabs_control" tab="info_constants" href="javascript: void(0);">Constants</a>
                </div>
                <div class="tabs tree" id="info_vars" class="b3 scrollable"></div>
                <div class="tabs tree" id="info_constants" class="b3 scrollable hidden"></div>
            </div>
            <div id="code" class="b2">
                <div id="code_file"></div>
            </div>
        </div>
        <div id="wrap_tree" class="tabs" style="display: none;">
            <div id="src_container"></div>
            <div id="src_tree" class="tree right b2">
                <?php echo tree($tree); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    var currentFile = false;
    var currentLine = 0;
    var status = 'off';
    var rexdebug_session = 'no_session';
    
    var command = $('#command');
    var nodebug = $('#nodebug');
    var info = $('#info');
    var info_vars = $('#info_vars');
    var file_tree = $('#file_tree');
    var code = $('#code');
    var code_info = $('#code_info');
    var code_insert = $('#code_file');
    var code_mirror = false;
    
    var updateStateInterval = setInterval(updateState, 1000);

    var updateStatus = function(new_status) {
        if (status != new_status) {
            if (new_status == 'on') {
                nodebug.hide();
                command.show();
                code.show();
                info.show();
                //clearInterval(updateStateInterval);
            } else {
                code.hide();
                info.hide();
                command.hide();
                nodebug.show();
                //updateStateInterval = setInterval(updateState, 1000);
            }
            status = new_status;
        }
    }
    
    var DbgSourceEditor = function(options){
        this._construct(options);
    }
    DbgSourceEditor.prototype = {
        code_mirror: false,
        currentLine: 0,
        breakpoints: false,
        allow_break: false,
        file: false,
        _construct: function(options){
            var obj = this;
            this.code_mirror = CodeMirror(options.parent, {
                value: options.text,
                mode: 'php',
                fixedGutter: true,
                gutter: true,
                readOnly: true,
                lineNumbers: true,
                onGutterClick: function(cm, n){
                    obj.toggleBreakpoint(n + 1);
                }
            });
            this._updateLines(options.allow_break);
            this.file = options.file;
        },
        _updateLines: function(allow_break){
            this.allow_break = allow_break;
            for (var i in this.allow_break) {
                if (this.allow_break[i]) {
                    this.code_mirror.setMarker(parseInt(i) - 1, false, 'valid');
                }
            }
            this.breakpoints = [];
        },
        _sendBreakpoint: function(row, action) {
            var command = action == 'set' ? 'setBreakpoint' : 'removeBreakpoint';
            var data = $.ajax({
                url: '/rexdebug/engine/' + command + '/',
                type: 'POST',
                async: false,
                data: {
                    rexdebug_file: this.file,
                    rexdebug_bprow: row
                }
            }).responseText;
            data = $.parseJSON(data);
            if (data.content != 'ok') {
                alert('Put command error');
            }
            return data.content == 'ok';
        },
        setValue: function(options){
            this.clear();
            this.code_mirror.setValue(options.text);
            this._updateLines(options.allow_break);
            this.file = options.file;
        },
        toggleBreakpoint: function(line_no){
            if (typeof this.breakpoints[line_no] == 'object') {
                this.code_mirror.setMarker(line_no - 1, '', 'valid');
                this.code_mirror.setLineClass(line_no - 1, '', this.currentLine == line_no ? 'code_curline' : '');
                if (this._sendBreakpoint(line_no, 'unset')) {
                    delete this.breakpoints[line_no];
                }
            } else if (this.allow_break[line_no]) {
                this.code_mirror.setMarker(line_no - 1, "<span style=\"color: #900\">●</span> %N%", 'valid');
                this.code_mirror.setLineClass(line_no - 1, '',
                    'code_bpoint active ' + (this.currentLine == line_no ? 'code_curline' : ''));
                if (this._sendBreakpoint(line_no, 'set')) {
                    this.breakpoints[line_no] = { active: true };
                }                    
            }
        },
        setLine: function(line_no) {
            if (this.currentLine) {
                var info = this.code_mirror.lineInfo(this.currentLine - 1);
                if (info) {
                    this.code_mirror.setLineClass(this.currentLine - 1, '',
                        info.bgClass ? info.bgClass.replace('code_curline', '') : '');
                }
            }
            this.currentLine = line_no;
            var info = this.code_mirror.lineInfo(this.currentLine - 1);
            this.code_mirror.setLineClass(this.currentLine - 1, '',
                info.bgClass ? info.bgClass + ' ' + 'code_curline' : 'code_curline');
            var scroll_pos = this.code_mirror.charCoords({
                line: this.currentLine > 5 ? this.currentLine - 6 : 0,
                ch: 0
            }, 'local');
            this.code_mirror.scrollTo(scroll_pos.x, scroll_pos.y);
        },
        clear: function(){
            this.code_mirror.setValue('');
            this.breakpoints = false;
            this.allow_break = false;
        }
    }

    var updateCode = function(filename, file, parent, editor) {
        var allow_break = [];
        for (var i in file.allow_break) {
            allow_break[file.allow_break[i]] = true;
        }
        if (!editor) {
            editor = new DbgSourceEditor({
                parent: parent,
                text: file.content,
                allow_break: allow_break,
                file: filename
            });
        } else {
            editor.setValue({
                text: file.content,
                allow_break: allow_break,
                file: filename
            });
        }
        for (var i in file.breakpoints) {
            editor.toggleBreakpoint(i);
        }
        return editor;
    }
    
    var updateVars = function(vars) {
        info_vars.html(print_r(vars));
    }    
    
    function updateState() {
        $.ajax({
            url: '/rexdebug/engine/getState/',
            type: 'POST',
            async: true,
            data: {
                rexdebug_uin: window.rexdebug_uin,
                rexdebug_session: rexdebug_session,
                rexdebug_file: currentFile
            },
            success: function(data){
                data = $.parseJSON(data);
                if (data.content !== false && !data.error) {
                    if (data.content.session) {
                        rexdebug_session = data.content.session;
                    }
                    updateStatus(data.status);
                    if (data.content.file != currentFile && data.file) {
                        code_mirror = updateCode(data.content.file, data.file, code_insert.get(0), code_mirror);
                        code_info.html(data.content.file);
                        currentFile = data.content.file;
                        code_mirror.setLine(data.content.line);
                        updateVars(data.content.vars);
                    } else if (data.content.line != currentLine && data.content.line) {
                        code_mirror.setLine(data.content.line);
                        updateVars(data.content.vars);
                    }
                } else {
                    alert('Put command error');
                }
            }
        });
    }
    
    var sendCommand = function(command) {
        var data = $.ajax({
            url: '/rexdebug/engine/putCommand/',
            type: 'POST',
            async: false,
            data: {
                rexdebug_uin: window.rexdebug_uin,
                rexdebug_session: rexdebug_session,
                command: command
            }
        }).responseText;
        data = $.parseJSON(data);
        if (data.content !== false && !data.error) {
            updateState();
        } else {
            alert('Put command error');
        }
    }
    
    var cnt = 1;
    function print_r(arr, level) {
        var html = '';
        if (!level) {
            level = 0;
        }
        if (typeof(arr) == 'object') {
            for (var item in arr) {
                var value = arr[item];
                if (typeof(value) == 'object') {
                    var inner_html = print_r(value, level+1);
                    if (inner_html == '') {
                        if ($.isArray(value)) {
                            html += '<div><span></span>' + item + ' :[]</div>';
                        } else {
                            html += '<div><span></span>' + item + ' :{}</div>';
                        }
                    } else {
                        html += '<input type="checkbox" checked="checked" id="check_'+(++cnt)+'" /><label for="check_'+cnt+'">'+item+'</label> :';
                        html += inner_html;
                    }
                } else {
                    if (typeof(value) == 'string') {
                        value = value.replace('<', '&ls;').replace('>', '&gt;');
                    }
                    html += '<div><span class="line"></span>' + item + ' => "' + value + '"</div>';
                }
            }
            if (level && html != '') {
                html = '<div class="object">' + html + '</div>';
            }
        } else {
            html = '===>'+arr+'<===('+typeof(arr)+')';
        }
        return html;
    }

    var srceditor = false;
    var openSource = function(path){
        var data = $.ajax({
            url: '/rexdebug/engine/getSrc/',
            type: 'POST',
            async: false,
            data: {
                rexdebug_file: path
            }
        }).responseText;
        data = $.parseJSON(data);
        if (data.content !== false && !data.error) {
            if (data.content.session) {
                rexdebug_session = data.content.session;
            }
            srceditor = updateCode(path, data, $('#src_container').get(0), srceditor);
        } else {
            alert('Put command error');
        } 
    }
    
    updateState();
    
    var clicked = false;
    $('span[path]').click(function(event){
        if (clicked) {
            clicked.removeClass('clicked');
        }
        clicked = $(this);
        clicked.addClass('clicked');
        openSource(clicked.attr('path'));
    });
    
    $('#stepinto').click(function(event){
        sendCommand('stepin');
    });
    
    $('#stepover').click(function(event){
        sendCommand('stepover');
    });
    
    $('#stepout').click(function(event){
        sendCommand('stepout');
    });
    
    $('#run').click(function(event){
        sendCommand('run');
    });
    
    $('#stop').click(function(event){
        sendCommand('exit');
    });
    
    $('.debugoff').click(function(event){
        parent.set_cookie(parent.frames.site, 'rexdebug', 'off');
        sendCommand('run');
    });
    
    $('.debugon').click(function(event){
        parent.set_cookie(parent.frames.site, 'rexdebug', 'on');
        parent.frames.site.location.reload();
    });
    
    var debug = false;
    var check_debug_process = function(){
        var process_cookie = parent.process_cookie(parent.frames.site);
        if (process_cookie.rexdebug !== 'on') {
            if (debug) {
                $('.debugon').show();
                $('.debugoff').hide();
                debug = false;
            }
        } else {
            if (!debug) {
                $('.debugon').hide();
                $('.debugoff').show();
                debug = true;
            }
        }
    };
    window.setInterval(check_debug_process, 2000);
    check_debug_process();
    
    $('.tabs_control').click(function(event){
        $(this).parent().find('.tabs_control').removeClass('active');
        var tab_id = $(this).attr('tab');
        $(this).addClass('active');
        var tab = $('#'+tab_id);
        tab.parent().find('.tabs').hide();
        tab.show();
    });
});
</script>
</body>
</html>