var newsSettings = {
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: !1,
        autoplaySpeed: 3e3,
        accessibility: !1,
        draggable: !1,
        responsive: [{breakpoint: 1024, settings: {slidesToShow: 3, slidesToScroll: 1, infinite: !0}}, {
            breakpoint: 600,
            settings: {slidesToShow: 1, slidesToScroll: 1}
        }],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev--hover.png"></button>'
    },

    mainSliderSettings = {
        arrows: !1,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 3e3,
        accessibility: !1,
        draggable: !1
    },

    productsSettings = {
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: !1,
        autoplaySpeed: 3e3,
        accessibility: !1,
        draggable: !1,
        responsive: [{breakpoint: 1200, settings: {slidesToShow: 3, slidesToScroll: 1, infinite: !0}}, {
            breakpoint: 1024,
            settings: {slidesToShow: 2, slidesToScroll: 1, infinite: !0}
        }],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-next--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/arrow-prev--hover.png"></button>'
    },

    partnersSlider = {
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: !0,
        autoplaySpeed: 3e3,
        accessibility: !1,
        draggable: !1,
        responsive: [{breakpoint: 1024, settings: {slidesToShow: 3, slidesToScroll: 1}}],
        nextArrow: '<button type="button" class="slick-next"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/right-arrow--hover.png"></button>',
        prevArrow: '<button type="button" class="slick-prev"><img class="slider_arrow-img" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow.png"><img class="slider_arrow-img slider_arrow-img--hover" src="/skin/volleymagnew_skin/frontend/img/slick/left-arrow--hover.png"></button>'
    };

!function (e) {
    function t() {
        n = e("#sliderOutput1").attr("aria-valuenow"), i = e("#sliderOutput2").attr("aria-valuenow"), o.text(n), s.text(i)
    }

    e(function () {

        var t = function () {
            e("#news-label").addClass("isActive").siblings().removeClass("isActive"),
            e("#publications").hide(),
            e("#news").show(),
            e(".my-slider_news").slick(newsSettings)
        },
        n = function () {

            e("#publications-label").addClass("isActive").siblings().removeClass("isActive"),
            e("#news").hide(),
            e("#publications").show(),
            e(".my-slider_publication").slick(newsSettings)
        };
        t(),
        e("#publications-label").on("click", function () {
            n()
        }),
        e("#news-label").on("click", function () {
            t()
        }),
        e(".my-slider_main").slick(mainSliderSettings),
        e(".my-slider_product").slick(productsSettings),
        e(".my-slider_partners").slick(partnersSlider)
    });
    var n, i,
        o = e("#fstVal"), s = e("#secVal");
        t(),
        e("#sliderOutput1, #sliderOutput2").on("mousedown", function () {
            return e(document).on("mousemove", t).one("mouseup", function () {
                return e(document).off("mousemove", t)
            })
        })
}(jQuery);