import slick from 'slick-carousel';

const btnDisplay = document.getElementById("display-media");

btnDisplay.addEventListener("click", function () {
    $("#slider").show();
});

$(".slider").slick({
    centerMode: true,
    centerPadding: "60px",
    slidesToShow: 3,
    speed: 150,
    variableWidth: true,
    prevArrow: '<div class="slick-prev"><i class="bi-arrow-right" aria-hidden="true"></i></div>',
    nextArrow: '<div class="slick-next"><i class="bi-arrow-left" aria-hidden="true"></i></div>',
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            }
        }
    ]
});
