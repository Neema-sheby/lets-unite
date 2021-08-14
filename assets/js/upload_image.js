/***************************************************************************************************************************************************
                                                          UPLOADS IMAGE IN MAIN PAGE
***************************************************************************************************************************************************/
import * as image from "./function_calls/send_file-path.js";

const profilePic = document.querySelector(".profile_pic");
const myModalEl2 = document.getElementById("modal");

if (profilePic) {
  console.log("hello");
  image.sendFilePath(1, "upload.php");
}

if (myModalEl2) {
  myModalEl2.addEventListener("hidden.bs.modal", function (event) {
    location.reload();
  });
}
