function start_main_load(){ 
        $('#mainload').show();
}  
function stop_main_load(){ 
        $('#mainload').hide();
}
function newPopup(url) {
    popupWindow = window.open(
        url,'popUpWindow','height=350,width=550,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
}

function confirmDelete(action, id, name){
    if (confirm('Вы действительно хотите удалить '+name+'?') == true) {
        document.location.href = 'index.php?mod='+action+'&act=delete&task=' + id;
    }
}

function confirmDeleteImage(action, id, name){
    if (confirm('Вы действительно хотите удалить '+name+'?') == true) {
        document.location.href = 'index.php?mod='+action+'&act=deleteImage&task=' + id;
    }
}

function confirmDeleteParams(action, id, param_name, param_id, name){
    if (confirm('Вы действительно хотите удалить '+name+'?') == true) {
        document.location.href = 'index.php?mod='+action+'&act=delete&'+param_name+'='+param_id+'&task=' + id;
    }
}

function confirmUnlink(action, id, name, site_id){
    if (confirm('Вы действительно хотите удалить '+name+'?') == true) {
        document.location.href = 'index.php?mod='+action+'&act=delete&id=' + id +'&site_id=' + site_id;
    }
}

function confirmDeleteLink(action, id, name, site_id, rubric_id, page){
    if (confirm('Вы действительно хотите удалить '+name+'?') == true) {
        document.location.href = 'index.php?mod='+action+'&act=delete&id=' + id +'&site_id=' + site_id +'&rubric_id=' + rubric_id +'&page=' + page;
    }
}

function checkAll(t){
    for(i=1; i<t.length; i++) {
         t[i].checked=t[0].checked;
     } 
}

function addKeyword(aKeyId){
    var test=document.getElementById('keywords').value;
    if (test.indexOf(','+aKeyId+',')!=-1){

        document.getElementById('keywords').value=test.substring(0,test.indexOf(','+aKeyId+','))+test.substring(test.indexOf(','+aKeyId+',')+aKeyId.length+1,test.length);
    } else {
        if (test.indexOf(aKeyId+',')==0){

        document.getElementById('keywords').value=test.substring(0,test.indexOf(aKeyId+','))+test.substring(test.indexOf(aKeyId+',')+aKeyId.length+1,test.length);
    } else {
        var lngt=test.length;
    
        if (test.charAt(lngt-1)!=',' && lngt!=0) {
            document.getElementById('keywords').value=document.getElementById('keywords').value+',';
        }
    document.getElementById('keywords').value+=aKeyId+",";
    }
    }
}

function showKeywords() {
    if (document.getElementById('keyword_form').style.display=='block') {
        document.getElementById('keyword_form').style.display='none';
    } else {
        document.getElementById('keyword_form').style.display='block';
    }
}

function resetRadio() {
    for (var i=0; i<document.forms.res_radio.elements.length; i++){
    document.forms.res_radio.elements[i].checked=false;
  }
    
}

function selectAllEdit(form) {
    if (form['link\[edit\]\[all\]'].checked == true) {
        check = true;
    } else {
        check = false;
    }
    for (var i = 0; i < form.length; i++) {
        var checkbox = form.elements[i];
        if (checkbox.type == 'checkbox' && !checkbox.name.indexOf('link[edit]') )
        checkbox.checked = check;
    }
}
function selectAllHide(form) {
    if (form['link\[hide\]\[all\]'].checked == true) {
        check = true;
    } else {
        check = false;
    }
    for (var i = 0; i < form.length; i++) {
        var checkbox = form.elements[i];
        if (checkbox.type == 'checkbox' && !checkbox.name.indexOf('link[hide]') )
        checkbox.checked = check;
    }
}

function selectAllDelete(form) {
    if (form['link\[delete\]\[all\]'].checked == true) {
        check = true;
    } else {
        check = false;
    }
    for (var i = 0; i < form.length; i++) {
        var checkbox = form.elements[i];
        if (checkbox.type == 'checkbox' && !checkbox.name.indexOf('link[delete]') )
        checkbox.checked = check;
    }
}

function removeCKEditor(dialog) {
    var editor = $(dialog).data('ckeditor');
    if (editor) {
        editor.destroy();
    }
}

function buildCKEditor(dialog){ 
    CKEDITOR.config.toolbarStartupExpanded = false;
    if(dialog){
        var textarea = dialog.find('#DataFCKeditor');
    } else {
        var textarea = $('#DataFCKeditor');
    }

    if (textarea.length) {
        var editor = CKEDITOR.replace(textarea[0], {toolbar: [
            {name: 'basic', items: [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ]},
            {name: 'document', items : [ 'Source', '-' , 'Preview' ]},
            {name: 'clipboard', items : [ 'PasteFromWord' ] },
            {name: 'colors', items : [ 'TextColor','BGColor' ]},
            {name: 'styles', items : [ 'Styles','Format','Font','FontSize' ]},
            {name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
                '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ]},
            {name: 'links', items : [ 'Link','Unlink','Anchor','Maximize','Scayt' ]},
            {name: 'insert', items : [ 'Image','youtube','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ]}
        ],
        on :
        {
            instanceReady : function( ev )
            {
                var options = {
                                indent : false,
                                breakBeforeOpen : true,
                                breakAfterOpen : false,
                                breakBeforeClose : false,
                                breakAfterClose : true
                              };
                // Output paragraphs as <p>Text</p>.
                this.dataProcessor.writer.setRules( 'p', options);
                this.dataProcessor.writer.setRules( 'td', options);
                this.dataProcessor.writer.setRules( 'li', options);
                this.dataProcessor.writer.setRules( 'strong', options);
                this.dataProcessor.writer.setRules( 'i', options);
                this.dataProcessor.writer.setRules( 'u', options);
            }
        },
            extraPlugins: 'justify'
        });
        DjenxExplorer.init({
            returnTo: editor
        });
        $(dialog).data('ckeditor', editor);
    } else {
        $(dialog).data('ckeditor', false);
    }
}

jQuery.rex.process_error = function(data) {
    var error_show = '';
    var task = 0;
    for(var i = 0; i < data.length; i++) {
        if (typeof(data[i]) == 'string')
            error_show = error_show + data[i] + '\n';
        
        if (typeof(data[i]) == 'object') {
            if (data[i].error_no == 'undefind' || data[i].message == 'undefind' || data[i].message == '') {
                return error_show + 'Undefind error-no or message';
                console.log(data);
                return false;
            }
            
            error_show = error_show + data[i].message + '\n';
            
            if (data[i].error_no == 1) {
                if (data[i].dialog_uin == 'undefind' || !data[i].dialog_uin) {
                    return error_show + 'Undefind dialog_uin';
                    console.log(data);
                    return false;
                }
                
                $.closeRexDialog(data[i].dialog_uin);
                $('.datagrid_container').trigger('updateDatagrid');
                
                if (data[i].task != 'undefind')
                    task = data[i].task;
                    
                if (data[i].mod != 'undefind')
                    mod = data[i].mod;
            }
        }
    }
    
    if (task > 0)
        $.showRexDialog(mod, 'edit', {task: task}, 'add_'+mod, 'Edit ' + mod);
    
    return error_show;
}

function getUrl(name)
{
    var translit = {
        'ї' : 'i', 'є' : 'e', 'і' : 'i',
            
        'Ї' : 'i', 'Є' : 'e', 'І' : 'i',
            
        'й' : 'j', 'ц' : 'ts', 'у' : 'y', 'к' : 'k', 'е' : 'e', 'н' : 'n', 
        'г' : 'g', 'ш' : 'sh', 'щ' : 'sch', 'з' : 'z', 'х' : 'h', 'ъ' : '',

        'ф' : 'f', 'ы' : 'i', 'в' : 'v', 'а' : 'a', 'п' : 'p', 'р' : 'r',
        'о' : 'o', 'л' : 'l', 'д' : 'd', 'ж' : 'zh', 'э' : 'e',

        'я' : 'ja', 'ч' : 'ch', 'с' : 's', 'м' : 'm', 'и' : 'i', 'т' : 't', 
        'ь' : '', 'б' : 'b', 'ю' : 'ju',

        'Й' : 'j', 'Ц' : 'ts', 'У' : 'y', 'К' : 'k', 'Е' : 'e', 'Н' : 'n', 
        'Г' : 'g', 'Ш' : 'sh', 'Щ' : 'sch', 'З' : 'z', 'Х' : 'h', 'Ъ' : '',

        'Ф' : 'f', 'Ы' : 'i', 'В' : 'v', 'А' : 'a', 'П' : 'p', 'Р' : 'r',
        'О' : 'o', 'Л' : 'l', 'Д' : 'd', 'Ж' : 'zh', 'Э' : 'e',

        'Я' : 'ja', 'Ч' : 'ch', 'С' : 's', 'М' : 'm', 'И' : 'i', 'Т' : 't', 
        'Ь' : '', 'Б' : 'b', 'Ю' : 'ju',

        'q' : 'q', 'w' : 'w', 'e' : 'e', 'r' : 'r', 't' : 't', 'y' : 'y', 
        'u' : 'u', 'i' : 'i', 'o' : 'o', 'p' : 'p',

        'a' : 'a', 's' : 's', 'd' : 'd', 'f' : 'f', 'g' : 'g', 'h' : 'h', 
        'j' : 'j', 'k' : 'k', 'l' : 'l',

        'z' : 'z', 'x' : 'x', 'c' : 'c', 'v' : 'v', 'b' : 'b', 'n' : 'n', 'm' : 'm',

        'Q' : 'q', 'W' : 'w', 'E' : 'e', 'R' : 'r', 'T' : 't', 'Y' : 'y', 
        'U' : 'u', 'I' : 'i', 'O' : 'o', 'P' : 'p',

        'A' : 'a', 'S' : 's', 'D' : 'd', 'F' : 'f', 'G' : 'g', 'H' : 'h', 
        'J' : 'j', 'K' : 'k', 'L' : 'l',

        'Z' : 'z', 'X' : 'x', 'C' : 'c', 'V' : 'v', 'B' : 'b', 'N' : 'n', 'M' : 'm',

        '1' : '1', '2' : '2', '3' : '3', '4' : '4', '5' : '5', '6' : '6', '7' : '7', 
        '8' : '8', '9' : '9', '0' : '0', '-' : '-'
    };

    var result = '';
    name = name.split(' ').join('-');
    
    while (strpos(name, '--', 0)) {
        name = name.split('--').join('-');
    }
    
    var strl = name.length;
    
    for (var i = 0; i < strl; ++i) {
        var ch = name[i] + '';
       
        if (translit[ch] !== undefined) {
            result = result + translit[ch];
        } else if (i < strl - 1) {
            var ii = i + 1;
            ch = ch + name[ii];
            
            if (translit[ch] !== undefined) {
                result = result + translit[ch];
                ++i;
            }
        }
    }
    
    return result;
}

function strpos( haystack, needle, offset){
    var i = haystack.indexOf( needle, offset ); 
    return i >= 0 ? i : false;
}

var maxlength = 50;

function backspacerUPText(object,e) {
  if(e){
    e = e
  } else {
    e = window.event
  }
  if(e.which){
    var keycode = e.which
  } else {
    var keycode = e.keyCode
  }

  if(keycode >= 48){
    ValidateText(object)
  }
}

/*----------*/
function explode( delimiter, string ) {    // Split a string by string
    // 
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: kenneth
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

    var emptyArray = { 0: '' };

    if ( arguments.length != 2
        || typeof arguments[0] == 'undefined'
        || typeof arguments[1] == 'undefined' )
    {
        return null;
    }

    if ( delimiter === ''
        || delimiter === false
        || delimiter === null )
    {
        return false;
    }

    if ( typeof delimiter == 'function'
        || typeof delimiter == 'object'
        || typeof string == 'function'
        || typeof string == 'object' )
    {
        return emptyArray;
    }

    if ( delimiter === true ) {
        delimiter = '1';
    }

    return string.toString().split ( delimiter.toString() );
}

function TrimStr(s) {
  s = s.replace( /^\s+/g, '');
  return s.replace( /\s+$/g, '');
}

function ValidateText(object){

  var p = object.value;

  p = p.replace(/[\s]{2,}/gi," ")
  
  var arr = explode(' ', p);
  //console.log(arr);
  var res = '';
  for (var i in arr) {
      if (arr[i].length > maxlength)
        pp = arr[i].substring(0, maxlength);
      else 
        pp = arr[i];
        
      res = res + ' ' + pp;
  }
  
  object.value = TrimStr(res);
}