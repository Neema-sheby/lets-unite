const box = document.querySelector(".user__title-box");
const iconPic = document.getElementById("btn-pic");
const cancelImg = document.querySelector(".user__cancel-box");
const cancelTitle = document.querySelector(".user__cancel-title");
const btnMsg = document.getElementById("btn_display-msg");
const imgBox2 = document.querySelector(".user__file-box");
const img = document.getElementById("image_load");
const msgForm = document.getElementById("form-title");
const inputMsg = document.getElementById("title-msg");
const postMsg = document.querySelector("user__lable-title");

let storedMsg = "";

// WHEN THE PIC ICON IS CLICKED, DISPLAY CANCEL
if (iconPic) {
  iconPic.addEventListener("click", function () {
    iconPic.classList.toggle("hidden");
    cancelImg.classList.toggle("hidden");
  });
}

// WHEN THE LARGE TITLE BUTTON IS CLICKED, DISPLAY TEXT AREA FIELD
if (btnMsg) {
  btnMsg.addEventListener("click", function () {
    btnMsg.classList.toggle("hidden");
    msgForm.classList.toggle("hidden");
  });
}

//WHEN POST TITLE MSG IS CLICKED
if (postMsg) {
  postMsg.addEventListener("click", function () {
    btnMsg.classList.toggle("hidden");
    msgForm.classList.toggle("hidden");
    // inputMsg.value = "";
  });
}

///////////////////////// CANCEL ///////////////////////////

// CANCEL IMAGE TO UPLOAD
if (cancelImg) {
  cancelImg.addEventListener("click", function () {
    iconPic.classList.toggle("hidden");
    cancelImg.classList.toggle("hidden");
    img.src = "";
    imgBox2.classList.add("hidden");
  });
}

//CANCEL TITLE MESSAGE BY CLICKING ON BUTTON
if (cancelTitle) {
  cancelTitle.addEventListener("click", function () {
    // btnMsg.textContent = storedMsg;
    btnMsg.classList.toggle("hidden");
    msgForm.classList.toggle("hidden");
    inputMsg.value = "";
  });
}

////////////////////////////////////////////////////////////

// STORE PREVIOUS MESSAGE
const prevMsg = function (msg) {
  storedMsg = msg;
};
