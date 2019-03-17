<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">{'404.page_not_found'|lang}</a>
            </li>
        </ul>
    </div>
</div>

{*<p >{'404.content'|lang}</p>*}

<style>
    .main-content-wrapper {
        position: relative;
    }
</style>

<div class="error-background" style="background-image: url(/skin/volleymagnew_skin/frontend/img/404.jpg)"></div>

<div class="row small-up-12 main-content_404">
    <section class="column column-block">

        <div class="error-head">
            <div class="error-icon">
                <span class="number">404</span>
                <span>ошибка</span>
            </div>
            <p>Запрашиваемая страница была удалена, либо перемещена :(</p>
        </div>

        <div class="back-to-home_block">
            <span>Предлагаем начать новый поиск</span>
            {img src='slick/arrow-next.png' itemprop="arrow" class="arrow-img"}
            <a class="common_link-btn btn btn--green" href="/">Перейти на главную</a>
        </div>

    </section>
</div>