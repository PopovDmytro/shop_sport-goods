<div id="pic-crop">
    <form action="" enctype="multipart/form-data" method="post" id="cropForm">
        <input type="hidden" name="x" id="td_x_crop"/>
        <input type="hidden" name="y" id="td_y_crop"/>
        <input type="hidden" name="w" id="td_w_crop"/>
        <input type="hidden" name="h" id="td_h_crop"/>    
        <input type="hidden" name="picture" value="{$picture}"/>    
        <input type="hidden" id="scale-size" name="scale" value=""/>    

        <div>
            <center>
                <img id="simage_crop" src="{$picturl}" />
            </center>
        </div>
    </form>
</div>

<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="simage-crop-savecrop" type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="entity[submit]" ><span class="ui-button-text">Save</span></button></td>
    </tr>
</table>


<script type="text/javascript">
{literal}
    var pic_w = {/literal}{$pic_width}{literal};
    var pic_h = {/literal}{$pic_height}{literal};
    var cropWidth = {/literal}{$crop_width}{literal};
    var cropHeight = {/literal}{$crop_height}{literal};
    var cropAct = {/literal}'{$act}'{literal};
    
    $('#scale-size').val(window.scale);

    $(document).ready(function() {
        var coords = null;
        var saveCoords = function (c)
        {
            $('#td_x_crop').val(c.x);
            $('#td_y_crop').val(c.y);
            $('#td_w_crop').val(c.w);
            $('#td_h_crop').val(c.h);
        };

        $('#simage_crop').Jcrop({
                minSize: [cropWidth/window.scale, cropHeight/window.scale],  
                //setSelect: [0, 0, cropWidth/window.scale, cropHeight/window.scale],   
                setSelect: [0, 0, pic_w, pic_h],
                aspectRatio: cropWidth / cropHeight,
                onSelect: saveCoords,
                onChange: saveCoords        
        });
        
    });
{/literal}
</script>
