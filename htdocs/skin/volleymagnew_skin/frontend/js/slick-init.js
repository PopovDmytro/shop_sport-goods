var newsSettings = {
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 3000,
        accessibility: false,
        draggable: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev--hover.png"></button>'
    },

    mainSliderSettings = {
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        accessibility: false,
        draggable: false
    },

    productsSettings = {
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 3000,
        accessibility: false,
        draggable: false,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true
                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true
                }
            }
        ],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev--hover.png"></button>'
    },

    partnersSlider = {
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        accessibility: false,
        draggable: false,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            }
        ],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow--hover.png"></button>'
    };

(function ($) {
    $(function () {
        // News slider
        var newsSlider = function () {
            $('#news-label').addClass('isActive').siblings().removeClass('isActive');
            $('#publications').hide();
            $('#news').show();
            $('.my-slider_news').slick(newsSettings);
        };

        var publicationsSlider = function () {
            $('#publications-label').addClass('isActive').siblings().removeClass('isActive');
            $('#news').hide();
            $('#publications').show();
            $('.my-slider_publication').slick(newsSettings);
        };

        newsSlider();

        // Publications
        $('#publications-label').on('click', function () {
            publicationsSlider();
        });

        $('#news-label').on('click', function () {
            newsSlider();
        });


        // Main slider
        $('.my-slider_main').slick(mainSliderSettings);

        // Product slider
        $('.my-slider_product').slick(productsSettings);

        // Partners slider
        $('.my-slider_partners').slick(partnersSlider);
    });

    var dataFstPrice;
    var dataSecPrice;
    var textFstPrice = $('#fstVal');
    var textSecPrice = $('#secVal');
    function setText() {
        dataFstPrice = ($('#sliderOutput1').attr('aria-valuenow'));
        dataSecPrice = ($('#sliderOutput2').attr('aria-valuenow'));
        textFstPrice.text(dataFstPrice);
        textSecPrice.text(dataSecPrice);
    }
    setText();
    //$('#sliderOutput1, #sliderOutput2').on('mousemove', setText);

    $('#sliderOutput1, #sliderOutput2').on("mousedown", () =>
    $(document)
        .on("mousemove", setText)
        .one("mouseup", () => $(document).off("mousemove", setText))
)

})($j);

