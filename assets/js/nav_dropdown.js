// DROPDOWN MENU FOR THE MESSAGE ICON
const body = document.querySelector("body");
const userLoggedInDrop = document.getElementById("user_dropdown").value;
const msgIcon = document.getElementById("msg-icon");
const bellIcon = document.getElementById("bell-icon");
const dropDown = document.querySelector(".navi__dropdown-window");
const noMoreDropDownText = document.querySelector(".noMoreDropdownText");
const hiddenInputDropdown = document.getElementById("dropdown_data_type");
const unreadMsgs = document.getElementById("unread-message");
const unreadNotifications = document.getElementById("unread-notification");

//////////////////////////////////////////

const dropDownForm = document.querySelector(".navi__dropdown-form");
const formData = new FormData(dropDownForm);

///////////////////// WHEN BODY IS CLICKED

body.addEventListener("click", function (e) {
  //  console.log(e.target);
  if (e.target === dropDown) {
    return;
  }
  dropDown.style.height = "0rem";
  dropDown.innerHTML = "";
});

//////////////////////////////WHEN MESSAGE ICON IS CLICKED

msgIcon.addEventListener("click", function () {
  hiddenInputDropdown.value = "message";
  $("body").removeClass("stop-scrolling");
  dropDownData(userLoggedInDrop, "message");
});

//////////////////////////////WHEN BELL ICON IS CLICKED

bellIcon.addEventListener("click", function () {
  hiddenInputDropdown.value = "notification";
  $("body").removeClass("stop-scrolling");
  dropDownData(userLoggedInDrop, "notification");
});

////////////////////////////////////////////////////////

const dropDownData = function (user, type) {
  if (dropDown.style.height === "0rem") {
    let pageName;
    if (type === "notification") {
      pageName = "ajax_load_notifications.php";
      if (unreadNotifications) {
        unreadNotifications.remove();
      }
    } else if (type === "message") {
      pageName = "ajax_load_messages.php";
      if (unreadMsgs) {
        unreadMsgs.remove();
      }
    }
    formData.append("pageDropdown", 1);
    formData.append("userDropdown", user);
    ajaxRequest(pageName, type, formData);
  } else {
    dropDown.innerHTML = "";
    dropDown.style.height = "0rem";
  }
};

const ajaxRequest = async function (pageName, type, fmData) {
  try {
    const response = await fetch(`includes/handlers/${pageName}`, {
      method: "POST",
      body: fmData,
    });
    if (!response.ok) {
      throw new error(
        "Something wrong with fetching delete_post.php",
        $response.status
      );
    }
    const data = await response.text();
    dropDown.innerHTML = data;
    dropDown.style.height = "28rem";
    hiddenInputDropdown.value = type;
    console.log(data);
  } catch (err) {
    console.error(err.message);
  }
};
