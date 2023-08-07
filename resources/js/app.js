import './bootstrap';

function calculatePerView() {
    const screenWidth = window.innerWidth;
    let perView = 4; // Default value

    // Adjust perView based on screen width
    if (screenWidth < 576) {
      perView = 1;
    } else if (screenWidth < 900) {
        perView = 2;
    } else if (screenWidth < 1300) {
      perView = 3;
    } // You can add more conditions as needed for different screen sizes

    return perView;
}
document.addEventListener("DOMContentLoaded", function () {
    const glide1 = new Glide(".glide-1 .glide", {
      type: "carousel",
      startAt: 0,

      perView: calculatePerView(),
      direction: document.dir,
      // More options here...
    });

    glide1.on('mount.after', function() {
      // Get the total number of slides
      const totalSlides = glide1.settings.slideCount;

      // Disable the next button when the last slide is reached
      const nextButton = document.querySelector('.glide-1 .glide__arrow--next');
      nextButton.disabled = glide1.index === totalSlides - 1;
    });

    glide1.mount();

    // Get the navigation buttons
    const prevButton = document.querySelector('.glide-1 .glide__arrow--prev');
    const nextButton = document.querySelector('.glide-1 .glide__arrow--next');

    // Add click event listeners to the buttons
    prevButton.addEventListener('click', function() {
      glide1.go('<');
    });

    nextButton.addEventListener('click', function() {
      glide1.go('>');
    });

    // Glide 2
    const glide2 = new Glide(".glide-2 .glide", {
      type: "carousel",
      startAt: 0,
      perView: calculatePerView(),
        direction: document.dir,
      // More options here...
    });

    glide2.on('mount.after', function() {
      // Get the total number of slides
      const totalSlides = glide2.settings.slideCount;

      // Disable the next button when the last slide is reached
      const nextButton = document.querySelector('.glide-2 .glide__arrow--next');
      nextButton.disabled = glide2.index === totalSlides - 1;
    });

    glide2.mount();

    // Get the navigation buttons
    const prevButton2 = document.querySelector('.glide-2 .glide__arrow--prev');
    const nextButton2 = document.querySelector('.glide-2 .glide__arrow--next');

    // Add click event listeners to the buttons
    prevButton2.addEventListener('click', function() {
      glide2.go('<');
    });

    nextButton2.addEventListener('click', function() {
      glide2.go('>');
    });

});

document.querySelectorAll('.search i').forEach(ele=>{
    ele.addEventListener('click', function() {
        console.log(ele)
        document.querySelector(".all_hamburger").style.display = "none";
        document.querySelector(".searching .all_search").style.display = "block"
    })
})

document.addEventListener('click', function(e) {
    if(!e.target.classList.contains("all_chats") && !e.target.classList.contains('propagated') && !e.target.classList.contains('add_chat') && !e.target.classList.contains('fa-comment-alt') && document.querySelector("div.chat .all_chats").classList.contains('active')) {
        document.querySelector("div.chat .all_chats").classList.remove('active')
    }

    if(!e.target.classList.contains("all_notifications") && !e.target.classList.contains('propagated_notifications') && !e.target.classList.contains('fa-bell') && document.querySelector("div.notifications .all_notifications").classList.contains('active')) {
        document.querySelector("div.notifications .all_notifications").classList.remove('active')
    }
})



document.querySelector('.hamburger .fa-bars').addEventListener('click', function() {
    document.querySelector(".all_hamburger").style.display = "block";
    document.body.style.overflow = "hidden";
})
document.querySelector('.all_hamburger .fa-times').addEventListener('click', function() {
    document.querySelector(".all_hamburger").style.display = "none";
    document.body.style.overflow = "auto";
})

const imageUpload = document.getElementById("imageUpload");
const imagePreview = document.getElementById("imagePreview");
const imageCircle = document.querySelector(".profile-image-circle");

imageUpload.addEventListener("change", function (e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function () {
      const imageURL = reader.result;
      imagePreview.innerHTML = `<img src="${imageURL}" alt="Profile Image" />`;
      imagePreview.style.display = "flex";
      imageCircle.style.display = "none";
    };
    reader.readAsDataURL(file);
  }
});

// Clear image preview if the user clicks on the preview image
imagePreview.addEventListener("click", function () {
  imageUpload.value = "";
  imagePreview.style.display = "none";
  imageCircle.style.display = "flex";
});


