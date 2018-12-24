<div class="product-def">
    <div class="breadcrumbs-block row">
        <div class="columns small-12">
            <ul class="breadcrumbs_list no-bullet">
                <li class="breadcrumbs_item">
                    <a href="/" class="breadcrumbs_link">
                        <i aria-hidden="true" class="fa fa-home"></i>Главная
                    </a>
                </li>
                <li class="breadcrumbs_item active">
                    <a href="javascript:void(0)" class="breadcrumbs_link">
                        {strip}
                            Заказ
                        {/strip}
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row align-center">
        <div class="column small-12">
            <div class="into-box">
                {page type='getRenderedMessages'}
                {page type='getRenderedErrors'}
            </div>
            <div class="product-def-bottom-bg"></div>
        </div>
    </div>
</div>

