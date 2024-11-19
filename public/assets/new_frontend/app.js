
function toggleDrawer() {
  const drawer = document.getElementById("drawer");
  const blurOverlay = document.getElementById("blurOverlay");
  const hamburger = document.querySelector(".hamburger");

  drawer.classList.toggle("active");
  blurOverlay.classList.toggle("active");
  hamburger.classList.toggle("active");
}

document.addEventListener("DOMContentLoaded", function() {
  const faqs = document.querySelectorAll(".faq");

  faqs.forEach(faq => {
    faq.querySelector(".faq-question").addEventListener("click", function() {

      faqs.forEach(item => {
        if (item !== faq) {
          item.classList.remove("open");
        }
      });

      faq.classList.toggle("open");
    });
  });
});

