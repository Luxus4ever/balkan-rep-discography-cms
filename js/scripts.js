
(function($) {
    "use strict"; 




    /* Text Slider - Swiper */
	var textSlider = new Swiper('.text-slider', {
        autoplay: {
            delay: 6000,
            disableOnInteraction: false
		},
        loop: true,
        navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev'
        },
        spaceBetween: 70,
        slidesPerView: 2,
		breakpoints: {
            // when window is <= 1199px
            1199: {
                slidesPerView: 1,
            },
        }
    });
    
	/* Removes Long Focus On Buttons */
	$(".button, a, button").mouseup(function() {
		$(this).blur();
	});

})(jQuery);

//--------------------------------------------------------------------------------------------

/* Hamburger meni */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.hamburger[data-target]').forEach(function(btn){
        btn.addEventListener('click', function(){
            var selector = btn.getAttribute('data-target');
            var target = document.querySelector(selector);
            if(!target) return;

            target.classList.toggle('is-open');

            var isOpen = target.classList.contains('is-open');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });
});

//--------------------------------------------------------------------------------------------