let profileUsername = "";

const userLoggedIn = document.querySelector(".navi__username").textContent;
const profileUsernameEle = document.querySelector(".profile__name");

if (profileUsernameEle) {
  profileUsername = profileUsernameEle.textContent;
}

let inProgress = false;

loadPosts(); //Load first posts

$(window).scroll(function () {
  let bottomElement = $(".status_post").last();
  let noMorePosts = $(".post-area").find(".noMorePosts").val();

  // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
  if (isElementInView(bottomElement[0]) && noMorePosts == "false") {
    loadPosts();
  }
});

function loadPosts() {
  if (inProgress) {
    //If it is already in the process of loading some posts, just return
    return;
  }

  inProgress = true;

  $(".spinner-box").show();

  let page = $(".post-area").find(".nextPage").val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'

  $.ajax({
    url: "includes/handlers/ajax_load_posts.php",
    type: "POST",
    data:
      "page=" +
      page +
      "&userLoggedIn=" +
      userLoggedIn +
      "&profileUsername=" +
      profileUsername,
    cache: false,

    success: function (data) {
      $(".post-area").find(".nextPage").remove(); //Removes current .nextpage
      $(".post-area").find(".noMorePosts").remove(); //Removes current .nextpage
      $(".posts-area").find(".noMorePostsText").remove(); //Removes current .nextpage

      $(".spinner-box").hide();
      $(".post-area").append(data);

      inProgress = false;
    },
  });
}

//Check if the element is in view
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
