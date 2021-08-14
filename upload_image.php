<?php
 include('includes/header.php');

 $url = $_SERVER['REQUEST_URI'];
 if(strpos($url, 'upload_image.php') == true){

  echo "
   <div class='upload profile_pic'>
   <h2 class='heading-2 text-align'>UPLOAD PROFILE  PICTURE </h2>
   <form action='' method='post'>
    <input type='file' name='image' class='btn-file image'>
    <input type='hidden' name='upload-userLoggedIn' class='upload-userLoggedIn' value = '$userLoggedIn' >
  </form>
        <!-- IMAGE CROP MODAL -->
        <!-- <input type='button' class='profile__post-msg' name='post_message' value='Post Message' data-bs-toggle='modal' data-bs-target='#post_message'> -->

<!-- OPEN MODAl WHEN IMAGE TO UPLOAD IS SELECTED-->
    <div class='modal fade' id='modal' tabindex='-1' aria-labelledby='modal-title' aria-hidden='true'>
      <div class='modal-dialog modal-dialog-centered modal-lg'>
        <div class='modal-content'>
        <div class = modal__container>
          <div class='modal-header'>
            <h2 class='modal-title' id='modal-title'>Post Something!</h2>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class='modal-body text-sm-start'> 

            <!-- //IMAGE CROP -->
            <div class='img-container'>
                <div class='row'>
                    <div class='col-md-8'>  
                       <img id='image'>
                    </div>
                    <div class='col-md-4'>
                        <div class='preview'></div>
                    </div>
                </div>
            </div>

          <div class='modal-footer'>
            <button type='button' class='btn__submit-green-large' data-bs-dismiss='modal'>Cancel</button>
            <button type='button' class='btn__submit-green-large' id='crop'>Crop</button>
          </div>
        </div>
      </div>
    </div> 
    </div>
</div>";
 }

?>



