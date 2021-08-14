if (postArea) {
  postArea.addEventListener("click", function (e) {
    if (e.target.closest(".delete-btn")) {
      bootbox.confirm({
        message: "Are you sure",
        centerVertical: true,
        callback: function (result) {
          if (result) {
            const post_id = e.target.parentElement.parentElement.id;
            bootboxResult(post_id);
            location.reload();
          }
        },
      });
    }
  });
}

const bootboxResult = async function (id) {
  try {
    const response = await fetch(
      `includes/form_handler/delete_post.php?id=${id}`
    );
    if (!response.ok) {
      throw new error("Something wrong with fetching data", response.status);
    }
    const data = await response.text();
    console.log(data);
  } catch (err) {
    console.error(err.message);
  }
};
