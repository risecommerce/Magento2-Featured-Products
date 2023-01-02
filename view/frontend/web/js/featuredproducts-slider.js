require(['jquery', 'slick'], function ($) {
    $('.risecommerce_featured_products_product_slider').slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        dots: false,
        autoplay: false,
        arrows: true,
        responsive: [
            {
                breakpoint: 900,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    arrows: true
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    arrows: false
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    centerMode: true,
                    slidesToScroll: 1,
                    adaptiveHeight: true,
                    arrows: false,
                    centerPadding: '0px'
                }
            },
            {
                breakpoint: 350,
                settings: {
                    slidesToShow: 1,
                    centerMode: true,
                    slidesToScroll: 1,
                    adaptiveHeight: true,
                    arrows: false,
                    centerPadding: '30px'
                }
            }
        ]
    });
});
