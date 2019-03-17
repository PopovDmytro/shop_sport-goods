{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    var clearFilters = function() {
        template.find('#pcatalog').val('');
        template.find('#filter').val('');
        template.find('#product_list').val('');
        template.find('#product_id').val('');
        template.find('.search').val('');
        template.find('.datefrom').val('');
        template.find('.dateto').val('');
        template.find('.check').val([]).trigger("liszt:updated");
        template.find('#pcatalog_filter option').each(function() {
            $(this).removeAttr('selected');
        });
        template.find('#pcatalog_filter option:first').attr('selected', 'selected');
        $.data(template, 'updateDatagrid')();
    };

    var sortTableRows = function () {
        $('table.data tbody').sortable({
            items: 'tr',
            axis: 'y',
            create: function (event, ui) {
                if (!template.find('#pcatalog_filter').val().length) {
                    $(this).sortable('destroy');
                }
            },
            stop: function (event, ui) {
                var item             = ui.item,
                    currentProductID = item.find('td:first').text(),
                    nextProductID    = item.next().find('td:first').text(),
                    prevProductID    = item.prev().find('td:first').text();

                if (!currentProductID) {
                    return false;
                }

                $.rex(mod, 'ReplaceSorder', {curr: currentProductID, next: nextProductID, prev: prevProductID}, function (response) {
                    if (!response) {
                        $(this).sortable('cancel');
                        alert('Не удалось переместить продукт!');
                    } else {
                        $.data(template, 'updateDatagrid')();
                        sortTableRows();
                    }
                });
            }
        }).disableSelection();
    };

    $.data(template, 'clearFilters', clearFilters);

    template.find('.datagrid_container').bind('updateDatagrid', function () {
        $.data(template, 'updateDatagrid')();
    });

    template.off('click.changePage', '.datagrid_container .dg_pager').on('click.changePage', '.datagrid_container .dg_pager', function (e) {
        e.preventDefault();
        $.data(template, 'page', $(this).attr('page'));
        $.data(template, 'updateDatagrid')();
        sortTableRows();
    });

    template.find('.filters').die('click').live('click', function() {
        var fl = $(this).attr('fl');
        template.find('.filters').removeClass('active');
        $(this).addClass('active');
        template.find('#filter').val(fl);
        $.data(template, 'updateDatagrid')();
    });

    template.find('.filters.active').trigger('click');
    
    template.on('change', '#pcatalog_filter', function() {
        var $this = $(this),
            value = $this.val();

        template.find('#pcatalog').val(value);
        $.data(template, 'updateDatagrid')();
        sortTableRows();
    });
    
    template.find('.images').die('click').live('click', function() {
        var product_id = $(this).attr('product_id');
        $.showRexDialog('pImage', false, {product_id: product_id, in_parent: true}, 'images', 'Images');
    });
    
    template.find('.attributes').die('click').live('click', function() {
        var product_id = $(this).attr('product_id');
        $.showRexDialog('attr2Prod', false, {product_id: product_id, in_parent: true}, 'attributes', 'Attributes');
    });
    
    template.find('.skus').die('click').live('click', function() {
        var product_id = $(this).attr('product_id');
        $.showRexDialog('sku', false, {product_id: product_id, in_parent: true}, 'skus', 'Skus');
    });
    
    template.find('.attach').die('click').live('click', function() {
        var product_id = $(this).attr('product_id');
        $.showRexDialog('attach', false, {product_id: product_id, in_parent: true}, 'attach', 'Attaches for product '+product_id);
    });
     
    $.rexDialog('attributes').find('.item_save').die('click').live('click', function(){
        $.rexDialog('attributes').find('.addform').rexSubmit(function(data){
            if (data !== false) {
                $.closeRexDialog('attributes');
            }
        });
    });
    
    generateLightbox();
    
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
     
     $("#product_list").autocomplete("/admin/autocompletprod/", {
        selectFirst: false,
        minLength: 2,
        width: 420,
        scrollHeight: 400,
        max: 30,
        formatItem: function(data, i, n, value) {
            product_id = value.split('=')[0];
            product_name = value.split('=')[1];
            product_image = value.split('=')[2];
            //return '<div class="product" pid="'+product_id+'" value="'+product_name+'">'+product_name+'</div>'
             return '<div style="width:80px; padding-right: 10px; float: left; text-align: center;"><img src="'+product_image+'" style="vertical-align: middle;width: 60px;height: 60px;"/></div><div style="padding-top: 13px;color: #089BE3;">'+product_name+'</div>'

        }, formatResult: function(data, i, n) {
                return i.split('=')[1];
        }
    }).result(function(event, item) {
        $('#product_list').val(item[0].split('=')[1]);
        template.find('#product_id').val(item[0].split('=')[0]);
        $.data(template, 'updateDatagrid')();
        return false;
    });
    /*select: function( event, ui ) {
        template.find('#user_id').val(ui.item.id);
        $.data(template, 'updateDatagrid')();
    },
    open: function() {
        $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
    },
    close: function() {
        $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
    }  */

    template.find('.itemeactive').die('click').live('click', function () {
        var data = $.rex(mod, 'activate', {task: 1, id: $(this).attr('item_id')});

        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    template.find('.itemdeactive').die('click').live('click', function () {
        var data = $.rex(mod, 'activate', {task: 2, id: $(this).attr('item_id')});

        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
{/literal}
{rexscript_stop}
</script>