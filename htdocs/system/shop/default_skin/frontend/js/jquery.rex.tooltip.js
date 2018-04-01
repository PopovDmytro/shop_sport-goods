rexTooltipConfig = {
    speed: 'fast',
    predelay: 0,
    delay: 500,
    position: 'top',
    afterBody: false,
    quickShow: false,
    wrapperClass: 'rex-tooltip-wrapper',
    addedClass: '',
    arrowClass: false,
    arrowLeft: 0,
    arrowTop: 0,
    left: 0,
    top: 0,
    zindex: 9999,
    events: ['mouseenter', 'mouseleave'],
    layout: ':next',
    parent: false,
    titleAttr: false
}

numberTooltip = 0;
rexTooltipCfg = new Array();

$.fn.rexTooltip = function(d) {    
    var d = getRexTooltipConfig(d);
    d.numberTooltip = numberTooltip;
    rexTooltipCfg[d.numberTooltip] = d;
    hiding = function(){};
    showing = function(){};
    
    this.die(d.events[0]).live(d.events[0], function(){
        clearTimeout(hiding);
        thisElement = $(this);
        thisElement.attr('numberTooltip', d.numberTooltip);
        if ($('.rex-tooltip-active').length && thisElement.attr('rex-tooltip-visible') != 'yes' && thisElement.attr('numberTooltip') == $('.rex-tooltip-active').attr('numberTooltip')) {
            $.fn.rexTooltip.hide(rexTooltipCfg[$('.rex-tooltip-active').attr('numberTooltip')], 0);
            if (d.quickShow) {
                $.fn.rexTooltip.show(d, 0, 0);    
            } else {
                $.fn.rexTooltip.show(d, d.speed, d.predelay);
            }
        } else if (!$('.rex-tooltip-active').length) {
           $.fn.rexTooltip.show(d, d.speed); 
        } else if ($('.rex-tooltip-active').length && thisElement.attr('rex-tooltip-visible') != 'yes' && thisElement.attr('numberTooltip') != $('.rex-tooltip-active').attr('numberTooltip')) {
            $.fn.rexTooltip.hide(rexTooltipCfg[$('.rex-tooltip-active').attr('numberTooltip')], 0);
            $.fn.rexTooltip.show(d, d.speed, d.predelay);
        }
    });
    
    this.die(d.events[1]).live(d.events[1], function(){
        $.fn.rexTooltip.hide(d, d.speed, d.delay);
    });
    
    if (!d.titleAttr) {
        $('.rex-tooltip-hidden[numberTooltip="'+d.numberTooltip+'"]').die(d.events[0]).live(d.events[0], function(){
            clearTimeout(showing);
            clearTimeout(hiding);
        });
        
        $('.rex-tooltip-hidden[numberTooltip="'+d.numberTooltip+'"]').die(d.events[1]).live(d.events[1], function(){
            $.fn.rexTooltip.hide(d, d.speed, d.delay);
        });
        
        $('.rex-tooltip-hidden[numberTooltip="'+d.numberTooltip+'"]').die('click').live('click', function(){
            $.fn.rexTooltip.hide(d, d.speed, 0);
        });
    }
    
    numberTooltip += 1;
    delete d;
    
};

$.fn.rexTooltip.show = function(d, speed, predelay){
    if (d.overlay) {
        if (!$("body").find("#rex-tooltip-overlay").is("div")) {
            if( !$.browser.msie) {
                $('body').prepend('<div id="rex-tooltip-overlay"></div>');
            } else {
                $('body').prepend('<div id="rex-tooltip-overlay"><iframe style="position: absolute; top: 0; left: 0;width: 100%; height: 100%; filter:alpha(opacity=0)" frameborder="0" scrolling="no"></iframe></div>');
            }
        }
    }
    clearTimeout(showing);
    showing = setTimeout(function(){
        if (!thisElement.length) {
            return;
        }
        var tooltip = thisElement;
        if (!d.titleAttr) {
            if (d.parent && typeof d.parent == 'boolean') {
                tooltip = tooltip.parent();
            } else if (d.parent && typeof d.parent == 'string') {
                tooltip = tooltip.parents(d.parent);
            }
            tooltip = tooltip.find(d.layout);
        }
        if (!$('.rex-tooltip-hidden').length && d.afterBody) {
            $('body').append('<div class="rex-tooltip-hidden"></div>');
        } else if (!d.afterBody) {
            tooltip.addClass('rex-tooltip-hidden');
        }
        s = $('.rex-tooltip-hidden');
        s.addClass(d.wrapperClass+' '+d.addedClass).attr('numberTooltip', d.numberTooltip);
        thisElement.attr('rex-tooltip-visible', 'yes');
        s.addClass('rex-tooltip-active');
        et = thisElement.context;
        y = 0;
        x = 0;
        if (d.afterBody) {
            x = thisElement.offset().left;
            y = thisElement.offset().top;
            if (d.titleAttr) {
                s.html(thisElement.attr('rexTitle'));
            } else {
                s.html(tooltip.html());
            }
        }
        var position_x = 0;
        var position_y = 0;
        s.addClass('rex-tooltip-moved');
        if (d.position == 'top') {
            position_x = (thisElement.innerWidth() - s.innerWidth())/2;
            position_y = -1*s.innerHeight();
        } else if (d.position == 'bottom') {
            position_x = (thisElement.innerWidth() - s.innerWidth())/2;
            position_y = thisElement.innerHeight();
        } else if (d.position == 'left') {
            position_x = -1*s.innerWidth();
            position_y = (thisElement.innerHeight() - s.innerHeight())/2;
        } else if (d.position == 'right') {
            position_x = thisElement.innerWidth();
            position_y = (thisElement.innerHeight() - s.innerHeight())/2;
        }
        if (!d.titleAttr) {
            if (!s.find('.'+d.arrowClass).length) {
                s.prepend('<div class="'+d.arrowClass+'"></div>');
            }
            var arrow = $('.'+d.arrowClass);
            var position_arrow_y = 0;
            var position_arrow_x = 0;
            if (d.position == 'top') {
                position_arrow_y = s.innerHeight() - arrow.innerHeight();
                position_arrow_x = s.innerWidth()/2 - arrow.innerWidth()/2;
            } else if (d.position == 'bottom') {
                position_arrow_y = -1*(arrow.innerHeight());
                position_arrow_x = s.innerWidth()/2 - arrow.innerWidth()/2;
            } else if (d.position == 'left') {
                position_arrow_y = (s.innerHeight() - arrow.innerHeight())/2 - arrow.innerHeight()/2;
                position_arrow_x = s.innerWidth();
            } else if (d.position == 'right') {
                position_arrow_y = (s.innerHeight() - arrow.innerHeight())/2 - arrow.innerHeight()/2;
                position_arrow_x = -1*(arrow.innerWidth());
            }
            arrow.css({top: position_arrow_y+d.arrowTop, left: position_arrow_x+d.arrowLeft});
        }
        s.removeClass('rex-tooltip-moved');   
        s.css({position: 'absolute', /*width: s.innerWidth(), height: s.innerHeight(),*/ left: x+position_x+d.left+'px', top: y+position_y+d.top+'px', 'z-index': d.zindex});
        if (!d.titleAttr) {
            arrow.css('position','absolute');
        }
        if (d.overlay) {
            if($("body").find('#rex-tooltip-overlay')){
                $('#rex-tooltip-overlay').css('z-index', d.zindex-1).show();
            }
            thisElement.attr('rexTooltipZindex', thisElement.css('z-index')).css({'z-index': d.zindex, 'position': 'relative'});
        }
        if (typeof d.beforeShow == 'function') {
            d.beforeShow.apply($('.rex-tooltip-active[numberTooltip="'+d.numberTooltip+'"]'));    
        }
        s.fadeIn(speed);
        if (typeof d.onShow == 'function') {
            d.onShow.apply($('.rex-tooltip-active[numberTooltip="'+d.numberTooltip+'"]'));    
        }
    }, predelay);
}

$.fn.rexTooltip.hide = function(d, speed, delay){
    hiding = setTimeout(function(){
        if (typeof d.beforeHide == 'function') {
            d.beforeHide.apply($('.rex-tooltip-active[numberTooltip="'+d.numberTooltip+'"]'));    
        }
        if (d.overlay) {
            $('[rexTooltipZindex]').each(function(){
                $(this).css('z-index', $(this).attr('rexTooltipZindex'));        
            });
            if($('body').find('#rex-tooltip-overlay')){
                $('#rex-tooltip-overlay').hide();
            }
        }
        $('.rex-tooltip-active').stop().css('opacity', 1);
        $('.rex-tooltip-active').fadeOut(speed, function(){
            $('.rex-tooltip-active').removeClass('rex-tooltip-active '+d.wrapperClass+' '+d.addedClass);    
        });
        if (!d.afterBody) {
            $('.rex-tooltip-hidden').removeClass('rex-tooltip-hidden');
        }
        if (typeof d.onHide == 'function') {
            d.onHide.apply($('.rex-tooltip-active[numberTooltip="'+d.numberTooltip+'"]'));    
        }
        $('[rex-tooltip-visible]').attr('rex-tooltip-visible', 'no');
    }, delay);
}
if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, ''); 
  }
}
if(typeof Function.prototype.bind  !== 'function') {
  Function.prototype.bind = Function.prototype.bind || function(b) {
    if (typeof this !== "function") {
      throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
    }

    var a = Array.prototype.slice;
    var f = a.call(arguments, 1);
    var e = this;
    var c = function() {};
    var d = function() {
      return e.apply(this instanceof c ? this : b || window, f.concat(a.call(arguments)));
    };

    c.prototype = this.prototype;
    d.prototype = new c();

    return d;
};  
}
$.fn.rexTooltip.title = function(){
    $('[title]').filter(function(){
        return !$(this).hasClass('rex-tooltip-disable');    
    }).each(function(){
        if ($(this).attr('title').trim().length) {
            $(this).attr('rexTitle', $(this).attr('title'));
            $(this).removeAttr('title');
        }
    });
    setTimeout($.fn.rexTooltip.title, 1000);
}

getRexTooltipConfig = function(d){
    if (typeof rexTooltipProp == 'undefined') {
        rexTooltipProp = {};
    }
    for (var i in rexTooltipConfig) {
        if (!d[i]) {
            if (!rexTooltipProp[i]) {
                d[i] = rexTooltipConfig[i];    
            } else {
                d[i] = rexTooltipProp[i];    
            }
        }
    }
    if (d.titleAttr) {
        $.fn.rexTooltip.title();                
        d.delay = 0;
        d.predelay = 0;
    }
    if (!d.arrowClass) {
        d.arrowClass = 'rex-tooltip-'+d.position+'-arrow';    
    }
    return d;    
}