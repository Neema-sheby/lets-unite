//STORE value and userin the html (SHORT CUT AJAX CALL)
const getUser = function (value, user) {
  $.post(
    "includes/handlers/ajax_friend-search.php",
    {
      value: value,
      userLoggedIn: user,
    },
    function (data) {
      $(".message__result-box").html(data);
    }
  );
};

// EVENT LISTENER 'keyup'
const friendSearchInput = document.querySelector(".message__friend-search");

if (friendSearchInput) {
  const userLoggedIn = document.querySelector(".friend-hidden").value;

  friendSearchInput.addEventListener("keyup", function () {
    getUser(friendSearchInput.value, userLoggedIn);
  });
}
