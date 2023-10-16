var slideIndex = 0;
var autoInc = true;
$(document).ready(function () {
    showSlides()
})

function plusSlides(n) {
    autoInc=false;
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    autoInc=false;
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {
        slideIndex = 1
    }
    if (n < 1) {
        slideIndex = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    if (autoInc) {
        slideIndex++;
    }
    if (slideIndex > slides.length) {
        slideIndex = 1
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active_dot", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active_dot";
    setTimeout(showSlides, 3000);
}
