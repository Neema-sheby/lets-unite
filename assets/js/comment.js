//SHOW OR HIDE COMMENT BOX

const postArea = document.querySelector(".post-area");

// DISPLAY SINGLE POST (FROM BELL ICON)
const singlePostArea = document.querySelector(".singlepost-area");

const postContainerID = function (id) {
  const postID = document.getElementById("post_id");
  postID.value = id;
  console.log(postID.value);
};

if (postArea) {
  postArea.addEventListener("click", function (e) {
    const btnComment = document.querySelector(".post-comment");

    const postComment = e.target.parentElement.parentElement.parentElement;

    if (!btnComment || !e.target.classList.contains("post-comment")) return;
    else {
      console.log(e.target);
      // console.log(postComment);
      postComment.querySelector(".comment__load").classList.toggle("hidden");
    }
  });
}

//WHEN A NOTIFICATION FROM BELL ICON IS CLICKED.
if (singlePostArea) {
  singlePostArea.addEventListener("click", function (e) {
    const btnComment = document.querySelector(".post-comment");

    const postComment = e.target.parentElement.parentElement.parentElement;

    console.log(postComment);

    if (!btnComment || !e.target.classList.contains("post-comment")) return;
    else {
      postComment.querySelector(".comment__load").classList.toggle("hidden");
    }
  });
}
