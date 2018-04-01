jQuery.rex = function(controller, action, data, callback) {
    if (!controller) {
        controller = 'home';
    }
    if (!action) {
        action = 'default';
    }
    if (!data) {
        data = [];
    }
    var result = false;

    jQuery.rex.start_load();
    if (typeof(callback) == 'function') {
        jQuery.post(jQuery.rex.base_path+'index.php?mod='+controller+'&act='+action+'&rex_request=1',
            data,
            function (data) {
                jQuery.rex.stop_load();
                result = jQuery.rexTestData(data);
                callback(result);
            }
        );
    } else {
        jQuery.ajax({
            url: jQuery.rex.base_path+'index.php?mod='+controller+'&act='+action+'&rex_request=1',
            type: 'POST',
            async: false,
            data: data,
            success: function(data, textStatus, jqXHR) {
                jQuery.rex.stop_load();
                result = jQuery.rexTestData(data);
            }
        });

        return result;
    }
};
jQuery.rexLocation = function(url, callback) {
    var result = false;
    jQuery.rex.start_load();
    if (typeof(callback) == 'function') {
        jQuery.post(url,
            {rex_location: 1},
            function (data) {
                jQuery.rex.stop_load();
                result = jQuery.rexTestData(data);
                callback(result);
            }
        );
    } else {
        jQuery.ajax({
            url: url,
            type: 'POST',
            async: false,
            data: {rex_location: 1},
            success: function(data, textStatus, jqXHR) {
                jQuery.rex.stop_load();
                result = jQuery.rexTestData(data);
            }
        });

        return result;
    }
};
if (!jQuery.rex.base_path) {
    jQuery.rex.base_path = typeof(window.base_path) == 'string' ? window.base_path : '/';
}
if (!jQuery.rex.start_load) {
    jQuery.rex.start_load = function() {
        $(document.body).addClass('rex_loading');
    }
}
if (!jQuery.rex.stop_load) {
    jQuery.rex.stop_load = function() {
        $(document.body).removeClass('rex_loading');
    }
}
if (!jQuery.rex.show_error) {
    jQuery.rex.show_error = function(message) {
        alert(message);
    }
}
if (!jQuery.rex.process_error) {
    jQuery.rex.process_error = function(data) {
        var error_show = '';
        for(var i = 0; i < data.length; i++) {
            if (typeof(data[i]) == 'string')
                error_show = error_show + data[i] + '\n';
            
            if (typeof(data[i]) == 'object') {
                if (data[i].message == 'undefind' || data[i].message == '') {
                    return error_show + 'Undefind error-no or message';
                    console.log(data);
                    return false;
                }
                
                error_show = error_show + data.error[i].message + '\n';
            }
        }
        
        return error_show;
    }
}
jQuery.showRexError = function(message) {
    jQuery.rex.show_error(message);
}
jQuery.rexDialog = function(uin) {
    return jQuery('#rex_dc_'+uin);
}
jQuery.rexTestData = function(data) {
    if (!data) {
        jQuery.rex.show_error('Unknown error');
        return false;
    }
    try {
        data = jQuery.parseJSON(data);
    } catch (e) {
        var regV = /<!DOCTYPE html>/;
        console.log(e, data);
        return false;
        /*if (!regV.test(data)) {
            jQuery.rex.show_error('Wrong data format');
            console.log(data);
            return false;
        }
        
        window.location = '/admin/';
        alert('redirect...');
        return;*/
    }
    if (typeof(data) != 'object') {
        jQuery.rex.show_error('Unknown error');
        console.log(data);
        return false;
    }
    if (data.error) {
        var error_show = jQuery.rex.process_error(data.error);
        jQuery.rex.show_error(error_show);
        return false;
    }
    if (!data.content && data.content !== 0) {
        jQuery.rex.show_error('Error: no data');
        return false;
    }
    return data.content;
};
jQuery.fn.rex = function(controller, action, data) {
    var html = jQuery.rex(controller, action, data);
    if (html !== false)  {
        this.html(html);
        /*html = $(html);
        this.html('');
        var block = this;
        html.each(function(){
            if (this.nodeName.toLowerCase() == 'script') {
                jQuery.globalEval(this.innerHTML);
            } else {
                block.append($(this));
            }
        });*/
    }
    return this;
};
jQuery.fn.rexSubmit = function(controller_or_callback, action, parametrs_or_callback, callback) {
    if (!this.length || this.get(0).tagName.toLowerCase() != 'form') {
        console.error('Only form allow sending!');
        return;
    }
    var parametrs = {};
    var controller = controller_or_callback;
    if (typeof(controller_or_callback) == 'function') {
        callback = controller_or_callback;
        controller = '';
    }
    if (typeof(parametrs_or_callback) == 'function') {
        callback = parametrs_or_callback;
    } else if (typeof(parametrs_or_callback) == 'object') {
        parametrs = parametrs_or_callback;
    }
    if (typeof(callback) == 'function') {
        jQuery.rexSubmitCallback = callback;
    }
    var iframe = $('iframe#rex_upload');
    if (!iframe.length) {
        $('body').append('<iframe style="display: none;" onLoad="jQuery.rexSubmitResponce();" id="rex_upload" name="rex_upload"></iframe>');
        iframe = $('iframe#rex_upload');
    }

    if (!action) {
        action = 'default';
    }
    var rex_request = this.find('input[name=rex_request]');
    if (!rex_request.length) {
        this.append('<input name="rex_request" type="hidden" value="1" />');
    } else {
        rex_request.val(1);
    }
    var rex_request_form = this.find('input[name=rex_request_form]');
    if (!rex_request_form.length) {
        this.append('<input name="rex_request_form" type="hidden" value="1" />');
    } else {
        rex_request_form.val(1);
    }
    if (controller) {
        var mod = this.find('input[name=mod]');
        if (!mod.length) {
            this.append('<input name="mod" type="hidden" value="'+controller+'" />');
        } else {
            mod.val(controller);
        }
        var act = this.find('input[name=act]');
        if (!act.length) {
            this.append('<input name="act" type="hidden" value="'+action+'" />');
        } else {
            act.val(action);
        }
        if (parametrs) {
            for (var i in parametrs) {
                this.append('<input class="rex-submit-params" name="'+i+'" type="hidden" value="'+parametrs[i]+'" />');    
            }
        }
    }
    this.attr('method', 'post');
    this.attr('enctype', 'multipart/form-data');
    this.attr('target', 'rex_upload');
    this.attr('action', jQuery.rex.base_path+'index.php');
    jQuery.rex.start_load();
    this.submit();
    jQuery('.rex-submit-params').remove();
};
jQuery.rexSubmitResponce = function() {
    jQuery.rex.stop_load();
    if (typeof(jQuery.rexSubmitCallback) == 'function') {
        var data = jQuery(window.frames.rex_upload.document).find('html').text();
        jQuery.rexSubmitCallback(jQuery.rexTestData(data));
    }
};

/*jQuery.loadInnerDG = function($element, controller, action, data) {
    jQuery.rex(controller, action, data, function(response) {
        if (response) {
            $element.html(response);
        }
    })
};*/

jQuery.showRexDialog = function(controller, action, data, uin, caption) {
    if (!uin) {
        jQuery.rex.show_error('Enter UIN for dialog with controller "'+controller+'" and act "'+action+'"!');
        return;
    }
    if (!caption) {
        jQuery.rex.show_error('Enter Caption for dialog with controller "'+controller+'" and act "'+action+'"!');
        return;
    }
    
    var block = jQuery('#rexDialog_'+uin);
    if (block.length) {
        block.remove();
    }
    block = jQuery('<div id="rexDialog_'+uin+'" style="display: none;"></div>').appendTo('body');
    
    block.html('');
    block.dialog({
       autoOpen: false,
       modal: true,
       resizable: false
    });

    if (!data) {
        data = {rex_dailog_uin: uin};
    } else if (typeof(data) == 'object') {
        data['rex_dialog_uin'] = uin;
    } else {
        data += '&rex_dialog_uin=' + uin;
    }

    var dialog_content = jQuery.rex(controller, action, data);
    if (dialog_content === false) {
        return false;
    }
    if (typeof(dialog_content) != 'object' || !dialog_content.template || !dialog_content.width || !dialog_content.height) {
        jQuery.rex.show_error('Use function responseDialog for send dialog template');
        return false;
    }
    block.html('<div id="rex_dc_'+uin+'">' + dialog_content.template + '</div>');
   
    block.dialog('option', 'title', caption);
    block.dialog('option', 'width', parseInt(dialog_content.width));
    block.dialog('option', 'height', parseInt(dialog_content.height));
    block.dialog('option', 'position', 'center');
    block.dialog('option', 'beforeClose', function(event, ui){
        if (typeof removeCKEditor == 'function') { removeCKEditor(block); }
    });
    block.dialog('option', 'open', function(event, ui){
         if (typeof buildCKEditor == 'function') { buildCKEditor(block); }
    });
    block.dialog('open');
    //scroll fix for webkit
    window.setTimeout(function() {
        jQuery(document).unbind('mousedown.dialog-overlay')
                        .unbind('mouseup.dialog-overlay');
    }, 100);
    if (typeof rebuildSelect == 'function') {
        rebuildSelect();
    }
    return true;
};
jQuery.closeRexDialog = function(uin) {
    var block = jQuery('#rexDialog_'+uin);
    if (block.length) {
        block.dialog('close');
    }
};
jQuery.rexGo = function(controller, action, data) {
    if (!controller) {
        controller = 'home';
    }
    if (!action) {
        action = 'default';
    }
    if (!data) {
        data = false;
    }
    var url = jQuery.rex.base_path+'index.php?mod='+controller+'&act='+action;
    if (data) {
        url += '&'+jQuery.param(data);
    }
    window.location = url;
};
jQuery.rexCrop = function(inputObject, outputObject, outputName, outputStyle) {
    if (inputObject.val().split(".")[1].toUpperCase() == 'BMP') {
        inputObject.val('').replaceWith(inputObject.clone(true));
        alert('BMP files are not allowed');
        return;
    }
    if (typeof(outputStyle) == 'undefined') {
        outputStyle = '';   
    }
    if (typeof(outputName) == 'undefined') {
        outputName = 'entity[cropped]';   
    }
    var formMod = '';
    var formAct = '';
    var imageName = inputObject.attr('name');
    if (inputObject.parents('form').find('input[name=mod]').length) {
        formMod = inputObject.parents('form').find('input[name=mod]').val();
    }
    if (inputObject.parents('form').find('input[name=act]').length) {
        formAct = inputObject.parents('form').find('input[name=act]').val();
    }     
    inputObject.parents('form').rexSubmit('image', 'upload', {this_mod: formMod, image_name: imageName}, function(data) {
        inputObject.parents('form').find('input[name=mod]').val(formMod);
        inputObject.parents('form').find('input[name=act]').val(formAct);
        if (data != false) {    
            window.scale = data.scale;
            var dialogResult = jQuery.showRexDialog('image', 'crop', {picture: data.picture, this_mod: formMod, image_name: imageName}, 'imagecrop', 'Crop Image');
            if (dialogResult === false) {
                inputObject.val('').replaceWith(inputObject.clone(true));
                return false;    
            } else {
                jQuery('#pic-crop').parents('.ui-dialog').css({'top': (jQuery(window).height()-pic_h)/2});
                jQuery('#simage-crop-savecrop').die('click').live('click', function(){
                    jQuery('#cropForm').rexSubmit('image', 'crop', {this_mod: formMod}, function(data){
                        if (data != false) {
                            jQuery.closeRexDialog('imagecrop');
                            outputObject.html('<img style="'+outputStyle+'" src="'+data.link+'" /><input type="hidden" name="'+outputName+'" value="'+data.cropped+'" />');
                        } else {
                            inputObject.val('').replaceWith(inputObject.clone(true)); 
                        }
                    });
                });
            }    
        }    
    });
        
};
jQuery.fn.rexClearIntervals = function() {
    var id = this.attr('id');
    var uin = id.indexOf('rex_dc_') != -1 ? id.replace('rex_dc_', '') : 
        (id.indexOf('active_template_content_') != -1 ? id.replace('active_template_content_', '') : id);
    if (window.rexinvl && window.rexinvl[uin] && window.rexinvl[uin].length) {
        for (var i in window.rexinvl[uin]) {
            clearInterval(window.rexinvl[uin][i]);
        }
        delete window.rexinvl[uin];
    }
    if (window.rextout && window.rextout[uin] && window.rextout[uin].length) {
        for (var i in window.rextout[uin]) {
            clearTimeout(window.rextout[uin][i]);
        }
        delete window.rextout[uin];
    }
    return this;
};
jQuery.fn.rexInterval = function(callback, ms) {
    var id = this.attr('id');
    var uin = id.indexOf('rex_dc_') != -1 ? id.replace('rex_dc_', '') : 
        (id.indexOf('active_template_content_') != -1 ? id.replace('active_template_content_', '') : id);
    uin = uin ? uin : 'default';
    if (!window.rexinvl) {
        window.rexinvl = {};
    }
    if (!window.rexinvl[uin]) {
        window.rexinvl[uin] = [];
    }
    window.rexinvl[uin].push(setInterval(callback, ms));
    return this;
};
jQuery.fn.rexTimeout = function(callback, ms) {
    var id = this.attr('id');
    var uin = id.indexOf('rex_dc_') != -1 ? id.replace('rex_dc_', '') : 
        (id.indexOf('active_template_content_') != -1 ? id.replace('active_template_content_', '') : id);
    uin = uin ? uin : 'default';
    if (!window.rextout) {
        window.rextout = {};
    }
    if (!window.rextout[uin]) {
        window.rextout[uin] = [];
    }
    window.rextout[uin].push(setTimeout(callback, ms));
    return this;
};
jQuery(document).ready(function(){
    $('body').append('<iframe style="display: none;" onLoad="jQuery.rexSubmitResponce();" id="rex_upload" name="rex_upload"></iframe>');
});