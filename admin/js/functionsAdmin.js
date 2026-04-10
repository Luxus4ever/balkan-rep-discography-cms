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