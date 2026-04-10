let slideIndex = 0;
let dotNumber= [];
const slides = document.querySelectorAll('.slide');
const dotContainer = document.querySelector('.dots');
let slideSpeed;
const btnRight= document.querySelector('.slider__btn--right');
const btnLeft = document.querySelector('.slider__btn--left');

let curSlide = 0;
const maxSlide = slides.length;
showSlides();

/*----------------------------------------------------------------------------------------------------------------------*/
//start function ShowSlides()
function showSlides(clicked="") {
let i;
let slides = document.getElementsByClassName("okvir");

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  

        //Creating dots
        let createDot= document.createElement("button");
        createDot.classList.add("dots__dot");
        createDot.setAttribute("id", `${[i+1]}`);
        createDot.setAttribute("data-slide", `${[i+1]}`);
        dotNumber.push(createDot);
        let temp= slides[i];

        dotContainer.appendChild(dotNumber[i]);
    }
    slideIndex++;

    /*----------------------------------------------------------------------------------------------------------------------*/
    //When finished, return the slide to the first image
    if (slideIndex > slides.length) {slideIndex = 1}    

    let startPosition= slideIndex - 1;
    let clickedPosition = clicked;

    /*----------------------------------------------------------------------------------------------------------------------*/
    //Start show slider from this position
    if(clickedPosition !== ""){
        slideIndex= clickedPosition;
        slideIndex++;
        slides[clickedPosition].style.display = "flex";
    }else{
        slides[startPosition].style.display = "flex";
    }

    dotActive(slideIndex);
    slideSpeed= setTimeout(showSlides, 2000); // Change image every 2 seconds
}//end showSlides()


/*----------------------------------------------------------------------------------------------------------------------*/
//Function to see/change which is active dot
function dotActive(slideIndex){
    document.querySelectorAll(".dots__dot").forEach(dot => dot.classList.remove('dots__dot--active'));

    document.querySelector(`.dots__dot[data-slide="${slideIndex}"]`).classList.add('dots__dot--active');  
}

/*----------------------------------------------------------------------------------------------------------------------*/
function goToSlide(slideNumber){
    clearTimeout(slideSpeed);
    showSlides(slideNumber);
}

/*----------------------------------------------------------------------------------------------------------------------*/
//Next slide
function nextSlide() {
    if (slideIndex === maxSlide) {
    slideIndex= 0;
    }
    goToSlide(slideIndex);
    dotActive(slideIndex);
};

/*----------------------------------------------------------------------------------------------------------------------*/
//Previous slide
function prevSlide() {
    if (slideIndex === 1) {
    slideIndex= maxSlide;
    } else {
    slideIndex--;
    }
    slideIndex--;
    goToSlide(slideIndex);
    dotActive(slideIndex);
};

/*----------------------------------------------------------------------------------------------------------------------*/
// Event handlers 1
//Click on Arrows
btnRight.addEventListener('click', nextSlide);
btnLeft.addEventListener('click', prevSlide);

/*----------------------------------------------------------------------------------------------------------------------*/
//Event handlers 2
//Key on keyboard moves 
document.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft') prevSlide();
    e.key === 'ArrowRight' && nextSlide();
});

/*----------------------------------------------------------------------------------------------------------------------*/
//Event handlers 3
//Click on dots
dotContainer.addEventListener('click', function (e) {
    if (e.target.classList.contains('dots__dot')) {
    const {slide } = e.target.dataset;
    let realSlide = slide - 1;
    goToSlide(realSlide);
    dotActive(slide);

    const element= document.getElementsByClassName("dots__dot")[realSlide];
    let attr= element.getAttribute("id");

    }
});

/*----------------------------------------------------------------------------------------------------------------------*/