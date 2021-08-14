// VARIABLES
const container = document.querySelector(".header-container");
const formBox = document.querySelector(".form-box");
const inputBox = document.querySelectorAll(".input-box");
const registerForm = document.querySelector(".register");
const loginForm = document.querySelector(".login");
const join = document.querySelector(".join");
const signIn = document.querySelector(".signin");
const loginEmail = document.getElementById("login-email");
const loginPassword = document.getElementById("login-password");
const loginBtn = loginForm.querySelector(".btn-login");

// ERROR VARIABLES
const firstname = document.getElementById("firstname");
const lastname = document.getElementById("lastname");
const email = document.getElementById("email");
const email1 = document.getElementById("email1");
const password = document.getElementById("password");
const password1 = document.getElementById("password1");

//--------------------------------------------------------------

/////////////////// SLIDING FUNCTION OF FORMS///////////////////

// CLICK SIGN IN
signIn.addEventListener("click", function (e) {
  e.preventDefault();
  successMsgTimeSetter();
  removeRegErr();
  formBox.style.overflow = "hidden";
  registerForm.classList.toggle("slideUp");
  loginForm.classList.toggle("slideDown");
});

// CLICK REGISTER
join.addEventListener("click", function (e) {
  e.preventDefault();
  if (container.querySelector(".err-msg")) {
    registerForm.classList.remove("hidden");
    loginForm.classList.add("slideUp");
    setTimeout(function () {
      loginForm.style.top = "-70rem";
      container.querySelector(".err-msg").classList.add("error-hidden");
      loginForm.classList.remove("slideDown");
      registerForm.classList.toggle("slideUp");
    }, 600);
  } else {
    successMsgTimeSetter();
    removeLoginErr();
    formBox.style.overflow = "hidden";
    registerForm.classList.toggle("slideUp");
    loginForm.classList.toggle("slideDown");
  }
});

////////////////////////--LOGIN error--//////////////////////

// STAY ON LOGIN PAGE IF ERROR----------------------------------

const StayOnLoginPage = function () {
  if (container.querySelector(".err-msg")) {
    container.querySelector(".err-msg").classList.remove("error-hidden");
    registerForm.classList.add("hidden");
    registerForm.classList.toggle("slideUp");
    loginForm.style.top = 0;
    console.log("hello");
  }
};
StayOnLoginPage();

//REMOVE LOGIN ERROR WHEN INPUT FIELD CLCKED------------------------------------
const removeLoginErr = function () {
  if (container.querySelector(".err-msg")) {
    container.querySelector(".err-msg").classList.add("error-hidden");
  }
};
loginEmail.addEventListener("click", removeLoginErr);
loginPassword.addEventListener("click", removeLoginErr);

////////////////////////--REGISTER error--//////////////////////

// REMOVE REGISTER ERROR MESSSAGE CLICKING ON SCREEN OR INPUT FIELD--------------------------------------------------------

const removeRegErr = function () {
  const errEle = container.querySelectorAll(".error-show");
  errEle.forEach((ele) => {
    ele.classList.add("error-hidden");
    ele.classList.remove("error-show");
  });
};

//SCREEN
container.addEventListener("click", function (e) {
  if (!e.target.classList.contains("header-container")) return;
  removeRegErr();
});

//INPUT FIELDS
firstname.addEventListener("click", removeRegErr);
lastname.addEventListener("click", removeRegErr);
email.addEventListener("click", removeRegErr);
email1.addEventListener("click", removeRegErr);
password.addEventListener("click", removeRegErr);
password1.addEventListener("click", removeRegErr);

//FUNCTION TO DISPLAY REGISTER ERROR MESSAGE--------------------

let element;
let direction;

const displayRegErrMessage = function (ele, dir, form) {
  ele.addEventListener("click", function (e) {
    const iconErr = this.querySelector(".icon-error");
    const inputField = this.querySelector(`.${form}__details`);

    if (!iconErr || inputField != e.target) return;
    formBox.style.overflow = "visible";
    ele.querySelector("svg").classList.add("error-hidden");
    console.log(ele);

    if (element) {
      const errEle = container.querySelectorAll(".error-show");

      errEle.forEach((ele) => {
        ele.classList.add("error-hidden");
        console.log(ele);
        ele.classList.remove("error-show");
      });
    }

    this.querySelector(`.c-box__${dir}`).classList.remove("error-hidden");

    this.querySelector(`.c-box__${dir}`).classList.add("error-show");
    element = ele;
    direction = dir;
  });
};

// DISPLAY REGISTER ERROR MESSAGE--------------------------------------

displayRegErrMessage(firstname, "left", "register-form");
displayRegErrMessage(lastname, "right", "register-form");
displayRegErrMessage(email, "left", "register-form");
displayRegErrMessage(password, "right", "register-form");

// DISPLAY SUCCESS MESSAGE--------------------------------------

const successMsgTimeSetter = function () {
  if (container.querySelector(".success-msg")) {
    setTimeout(function () {
      container.querySelector(".success-msg").classList.add("success-hidden");
    }, 2000);
  }
};
successMsgTimeSetter();

if (window.history.replaceState) {
  window.history.replaceState(null, null, window.location.href);
}
