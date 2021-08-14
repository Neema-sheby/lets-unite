/* ***************************************************************************************************************************************************
                                        RETRIES PATH AND SENDS THE PATH TO upload/ upload background
***************************************************************************************************************************************************  */

export const sendFilePath = async function (aspectRatio, fileName) {
  const bs_modal = $("#modal");
  const image = document.getElementById("image");

  console.log(image);
  let cropper, reader, file;

  $("body").on("change", ".image", function (e) {
    let files = e.target.files;

    let done = function (url) {
      image.src = url;
      bs_modal.modal("show");
    };

    if (files && files.length > 0) {
      file = files[0];

      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function (e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  });

  bs_modal
    .on("shown.bs.modal", function () {
      cropper = new Cropper(image, {
        aspectRatio: `${aspectRatio}`,
        viewMode: 3,
        preview: ".preview",
      });
    })
    .on("hidden.bs.modal", function () {
      cropper.destroy();
      cropper = null;
    });

  $("#crop").click(function () {
    const canvas = cropper.getCroppedCanvas({
      minWidth: 256,
      minHeight: 256,
      maxWidth: 4096,
      maxHeight: 4096,
      fillColor: "#fff",
      imageSmoothingEnabled: true,
      imageSmoothingQuality: "high",
    });

    canvas.toBlob(function (blob) {
      const url = URL.createObjectURL(blob);
      let reader = new FileReader();
      reader.readAsDataURL(blob);
      reader.onloadend = function () {
        let base64data = reader.result;
        uploadAjaxCall(base64data);
      };
    });
  });

  // AJAX CALL TO UPLOAD IMAGE INTO DATABASE
  const uploadAjaxCall = async function (val) {
    const input = document.querySelector('input[type="file"]');
    const userLoggedIn = document.querySelector(".upload-userLoggedIn").value;
    const formData = new FormData();
    formData.append("file", input.files[0]);
    formData.append("image", val);
    formData.append("userLoggedIn", userLoggedIn);
    //console.log(Object.fromEntries(formData.entries()));

    try {
      const response = await fetch(`${fileName}`, {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new error(
          `Something went wrong while fetching data ,${response.status} `
        );
      }
      const data = await response.text();
      console.log(data);
      bs_modal.modal("hide");
    } catch (err) {
      console.error(err.message);
    }
  };
};
