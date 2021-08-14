let userSendMsg = "";
const userLoggedInDropdown = document.getElementById("user_dropdown").value;
const userSendMsgEle = document.querySelector(".message__user-name");
const dropDownWindow = document.querySelector(".navi__dropdown-window");

if (userSendMsgEle) {
  userSendMsg = userSendMsgEle.textContent;
  console.log(userSendMsg);
}

let dropdownInProgress = false;

$(".navi__dropdown-window").scroll(function () {
  $("body").addClass("stop-scrolling");
  let bottomElement = $(".navi__dropdown-window").last();
  let noMoreDropdownData = $(".navi__dropdown-window")
    .find(".noMoreDropdownData")
    .val();

  if (isElementInView(bottomElement[0]) && noMoreDropdownData === "false") {
    console.log("hello");
    loadDropdownPosts();
  }
});

function loadDropdownPosts() {
  if (dropdownInProgress) {
    //If it is already in the process of loading some posts, just return
    return;
  }

  dropdownInProgress = true;

  let page =
    $(".navi__dropdown-window").find(".nextPageDropdownData").val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'

  let pageName; //Holds name of page to send ajax request to
  let type = $("#dropdown_data_type").val();

  console.log(type);

  if (type == "notification") pageName = "ajax_load_notifications.php";
  else if (type == "message") pageName = "ajax_load_messages.php";

  $.ajax({
    url: "includes/handlers/" + pageName,
    type: "POST",
    data: "pageDropdown=" + page + "&userDropdown=" + userLoggedInDropdown,
    cache: false,

    success: function (response) {
      $(".navi__dropdown-window").find(".nextPageDropdownData").remove(); //Removes current .nextpage
      $(".navi__dropdown-window").find(".noMoreDropdownData").remove();
      $(".navi__dropdown-window").append(response);
      dropdownInProgress = false;
    },
  });
}

function isElementInView(el) {
  if (el == null) {
    return;
  }

  let rect = el.getBoundingClientRect();

  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <=
      (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
    rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
  );
}
