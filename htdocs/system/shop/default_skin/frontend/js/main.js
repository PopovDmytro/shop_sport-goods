function clearCache(){
    var path = window.location.pathname;
    if ( !document.cookie.match('cache_is_clear') || (document.cookie.match('cache_is_clear') && !document.cookie.match(path)) ) {
        if ( !document.cookie.match('cache_is_clear') ){
            document.cookie = "cache_is_clear=" + path;
        } else {
            var cookie = document.cookie.match(/cache_is_clear=[^;]+;/)[0];
            delete_cookie('cache_is_clear');
            document.cookie = cookie.replace(';', path + ';');
        }

        window.location.reload(true);
    }
}

function delete_cookie ( cookie_name )
{
    var cookie_date = new Date ( );
    cookie_date.setTime ( cookie_date.getTime() - 1 );
    document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}

clearCache();

function ShowTab(id) {
    var active = $('.nav-info .active');
    $('#product-def-description-'+active.attr('attr')).css('display', 'none');
    active.removeClass('active');

    $('#tab'+id).addClass('active');
    $('#product-def-description-'+id).css('display', 'block');
}

function ShowSubmenu(id) {
    var subm = document.getElementById('sub-m'+id);
    var m = document.getElementById('m'+id);
    if (subm && m) {
	    if (subm.style.display == 'none'){
		    subm.style.display = 'block';
		    m.style.margin = '0 6px';
	    } else {
		    subm.style.display = 'none';
		    m.style.margin = '0 6px 6px';
	    }
    }
}

function showSubMenuByClass(id) {
    if(typeof $('.sidebar .sub-m'+id) != 'undefined') {
        if($('.sidebar .sub-m'+id).is(":visible")) {
            $('.sidebar .sub-m'+id).hide();
            $('.sidebar .sub-m'+id).css({'margin':'0 6px'});
        } else {
            $('.sidebar .sub-m'+id).show();
            $('.sidebar .sub-m'+id).css({'margin':'0 6px 6px'});
        }
    }
}

var maxinputlength = 4;

function backspacerUP(object,e) {
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
    ValidateInput(object)
  }
}

function ValidateInput(object){

  var p = object.value;

  p = p.replace(/[^\d]*/gi,"");

  if (p.length <= 4) {
    object.value = p
  } else if(p.length > 4){
    object.value = p.substring(0, maxinputlength);
  }
}

/*-----*/
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

$(document).ready(function(){
    var delayedRedirect =  document.body.dataset.delayedRedirect;
    if (delayedRedirect) {
        setTimeout(function () {
            window.location.href= delayedRedirect;
        }, 5000);
    }
});
