/***************************************************************************************************************************************************
                                                          UPLOADS BACKGROUND IMAGE IN MAIN PAGE
***************************************************************************************************************************************************/
import * as bgImage from "./function_calls/send_file-path.js";

const backgroundPic = document.querySelector(".background");
const myModalEl = document.getElementById("modal");

const closeBgImg = document.getElementById("close_bg");

if (backgroundPic) {
  bgImage.sendFilePath(16 / 9, "upload_bg.php");
}

if (myModalEl) {
  myModalEl.addEventListener("hidden.bs.modal", function (event) {
    location.reload();
  });
}
