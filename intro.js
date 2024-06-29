let slideIndex = 0;
showSlides();

function showSlides() {
  let slides = document.getElementsByClassName("slide");
  let dots = document.getElementsByClassName("dot");
  
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  
  slideIndex++;
  
  if (slideIndex > slides.length) {
    window.location.href = "signin.html"; // Redirect to sign.html after the last slide
    return; // Exit the function to prevent further execution
  }
  
  for (let i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";
  
  setTimeout(showSlides, 4000); // Change slide every 4 seconds
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

document.querySelectorAll('.slide').forEach((slide) => {
  slide.addEventListener('click', () => {
    showSlides(slideIndex += 1);
  });
});
