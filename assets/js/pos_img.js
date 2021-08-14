const postContainer = document.querySelector(".post-area");
const popUp = document.querySelector(".popup");
const postBody = document.querySelector("body");
const popupClose = document.querySelector(".popup__close");

//console.log(userPage);
if (postContainer) {
  postContainer.addEventListener("click", function (e) {
    if (e.target.classList.contains("user__img-post")) {
      const src = e.target.src;
      const html = `
      <div class='popup__box'>
       <img src='${src}' class='popup__img' alt ='posted image'>
      </div>
      <div class="popup__close">
          <svg class="icon-cross  icon-circle-with-cross" id="btn_cancel-img">
              <use xlink:href="assets/images/sprite.svg#icon-circle-with-cross">
              </use>
          </svg>
      </div>
      `;
      popUp.classList.toggle("hidden");
      popUp.insertAdjacentHTML("afterbegin", html);
    }
    //
  });
}
if (popUp) {
  popUp.addEventListener("click", function (e) {
    e.preventDefault();
    if (
      (e.target.classList.contains("popup") &&
        !e.target.classList.contains("user__img-post")) ||
      e.target.classList.contains("popup__close")
    ) {
      console.log("hello");
      popUp.classList.toggle("hidden");
      document.querySelector(".popup__box").remove();
      document.querySelector(".popup__close").remove();
    }
  });
}
