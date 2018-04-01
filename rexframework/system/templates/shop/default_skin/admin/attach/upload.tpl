<div style="overflow-y: auto">
    <form method="post" enctype="multipart/form-data" action="" class="attach_upload_form">
        <input type="hidden" name="mod" value="{$mod}">
        <input type="hidden" name="act" value="upload">
        <input type="hidden" name="product_id" value="{$product_id}">
        <input type="hidden" name="name" value="attach_file">
        
        <table cellspacing="5" cellpadding="0" border="0" width="100%">
            <tr>
                <td class="frame-t-td-l">
                    Selected file:
                </td>
                <td class="frame-t-td">
                    <img id="myImage" src="" style="display:none;"><br>
                    <input type="file" id="myFile" name="attach_file" />
                </td>
            </tr>
        </table>
    </form>
</div>

<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="save_upload" type="button" class="item_add ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"><span class="ui-button-text">Attach</span></button></td>
    </tr>
</table>

<script>
    {literal}
        // биндим проверку размера на аплоаде
        $('#myFile').die('change').live('change', function() {
            var fileObj, size;
            fileObj = this.files[0];
            size = fileObj.size;
            if (size > 5 * 1024 * 1024) { // проверяем на максимальный размер (тут 5 метров)
                alert('Файл не может быть более 5 Мб');
                this.parentNode.innerHTML = this.parentNode.innerHTML; // очищаем поле с файлом
            }
        });
            
    {/literal}
</script>