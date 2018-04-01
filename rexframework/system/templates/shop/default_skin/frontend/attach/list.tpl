{strip}
<div id="product-def-description-4" style="display: none">
    {foreach from=$attaches item=attach}
        <div class="attach-wrapper">
            <div class="attach-image">
                {if $attach.image}
                    {img src="box-download.png"}
                {else}
                    {img src="box-download.png"}
                {/if}
            </div>
            <a class="download-href" href="{url mod=attach act=download task=$attach.id}">{$attach.filename}</a>
            <div>Добавлен: {$attach.date_create}</div>
            <div>Кол-во скачиваний: <span class="download-count">{$attach.download_count}</span></div>
        </div>
    {/foreach}
</div>

<script>
    {literal}

        $('.download-href').die('click').live('click', function(){
            var countDownload = parseInt($(this).parent().find('.download-count').html());
            $(this).parent().find('.download-count').html(countDownload+1);
        });
    
    {/literal}
</script>
{/strip}