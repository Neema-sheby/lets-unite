//GET THE POST MESSAGE DETAILS AND SEND IT

const postBtn = document.getElementById("profile_post_something");

if (postBtn) {
  postBtn.addEventListener("click", function () {
    const form = document.getElementById("modal_form");
    const formData = new FormData(form);
    getPostMessage(formData);
  });
}

//GET FORM DATA AND POST TO THE PHP URL TO SUBMIT POST

const getPostMessage = async function (fmData) {
  try {
    const response = await fetch(
      "includes/handlers/ajax_submit_profile_post.php",
      {
        method: "POST",
        body: fmData,
      }
    );

    if (!response.ok) {
      throw new error(
        `Something went wrong while fetching data ,${response.status} `
      );
    }
    let data = await response.text();
    document.querySelector(".modal").classList.add("hidden");
    location.reload();
  } catch (err) {
    console.error(err.message);
  }
};

/* ACCESS FORM DATA AND SAVE IT IN THE URL SO THE PHP CAN ACCESS DATA TO SUBMIT POST FROM THE URL USING GET METHOD 
const postBtn = document.getElementById("profile_post_something");

if (postBtn) {
  postBtn.addEventListener("click", function () {
    const form = document.getElementById("modal_form");
    const formData = new FormData(form);
    const plainFormData = Object.fromEntries(formData.entries());
    const { text_msg, user_to, user_from } = plainFormData;
    getPostMessage(text_msg, user_to, user_from);
  });
}

const getPostMessage = async function (text_msg, user_to, user_from) {
  try {
    const response = await fetch(
      `includes/handlers/ajax_submit_profile_post.php?text_msg=${text_msg}&user_to=${user_to}&user_from=${user_from}`
    );

    if (!response.ok) {
      throw new error(
        `Something went wrong while fetching data ,${response.status} `
      );
    }
    const data = await response.text();
    console.log(data);
    return data;
  } catch (err) {
    console.error(err.message);
  }
  //
};
 */
