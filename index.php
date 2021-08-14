
<?php 
require 'includes/header.php';

$msg_post = "Click to enter Your message of the day";

if(isset($_POST['post'])){

  //////////////////////////////////POSTING IMAGE
  $uploadOk = 1;
  $imageName = $_FILES['fileToUpload']['name'];


  $error_message = "";

  if($imageName != ""){
    $dir = "assets/images/posts/";
    $imageName = $dir.uniqid().basename($imageName);
    $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

    if($_FILES['fileToUpload']['size'] > 1000000000){
      $error_message = "Sorry, your file is too large";
      $uploadOk = 0;
    }

    if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg" ){

      $error_message = "Sorry, only jpeg, jpg and png files are allowed";
      $uploadOk = 0;
    }
    if($uploadOk){
      if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)){
        //IMAGE UPLOADED
      }
      else{
        $uploadOk = 0;
      }
    }
  }

  if($uploadOk){
    $post = new Post($con,$userLoggedIn);
    $post->submitPost($_POST['post_text'],'none', $imageName);
    header("Location:index.php");//this is added to prevent the page from submitting a previous form
  }

  else{
    echo "<div class='alert-message'>$error_message</div>";
  }

////////////////////////////////////////////////////////

}

//////////////////////////////////POST TODAY'S MESSAGE

if(isset($_POST['post_title-msg'])){

  if(isset($_POST['text_title-msg'])){
    $msg = $_POST['text_title-msg'];

   $query = mysqli_query($con, "UPDATE users SET msg_today ='$msg' WHERE username ='$userLoggedIn'");
  }

}

?>
<div class="main-image" style = "background-image: linear-gradient(to right,
      rgba(255,255,255, 1),
      rgba(255,255,255, 0.5)
    ),
    url(<?php echo $user['background_image']?>)">
  <img src="<?php echo $user['background_image']?>" alt="background-image" class="main-image__bg">
</div>

  <div class="user">
    <div class="user__details margin-top-x-small">

     <a href="<?php echo $userLoggedIn ?>" class="user__image">
        <img src="<?php echo $user['profile_pic']?>" class="user__image--1">
     </a> 
      
     <a href="<?php echo $userLoggedIn ?>" class="user__name"><?php echo $user['first_name']." ".$user['last_name']?></a> 

     <p class="user__num-post"> Posts: <?php echo $user['num_posts']?></p>
     <p class="user__likes"> Likes: <?php  echo $user['num_likes']?></p> 
    </div>

    <div class="user__title-box">

      <div  class="user__display-btn" id="btn_display-msg">
      <?php
      $msg_query = mysqli_query($con,"SELECT msg_today FROM users WHERE username ='$userLoggedIn'");

      $row = mysqli_fetch_array($msg_query);
      $msg_post = $row['msg_today'];

      if($msg_post == ""){
        $msg_post = "Click to enter your message of the day!";
        echo $msg_post;
      }
      else{
        echo $msg_post;
      }
      ?>
      
      </div>
      <form action="" method="post" class="user__form-title hidden" id="form-title">
        <textarea type="text" class="user__input-title" placeholder="Enter your message" maxlength ="50" id="title-msg" name="text_title-msg"></textarea>
        <div class="user__btns-msg">
            <label for="post_title-msg" class="user__label-title" >
                <svg class="icon-small  icon-folder-download">
                  <use xlink:href="assets/images/sprite.svg#icon-folder-download">
                  </use>
                </svg>
            </label>
            <input type="submit" class="btn-hide" name="post_title-msg" id="post_title-msg"  >
            <div class="user__cancel-title">
              <svg class="icon-small  icon-circle-with-cross" id="btn_cancel-title">
                  <use xlink:href="assets/images/sprite.svg#icon-circle-with-cross">
                  </use>
              </svg>
            </div>
        </div>

      </form>
    </div>
               
    <div class="user__posts">
      <form action="index.php"method="post" class="user__send-posts" enctype="multipart/form-data">
        <label for="fileToUpload" class="user__label" id="btn-pic">
          <svg class="icon-animate-black  icon-images">
            <use xlink:href="assets/images/sprite.svg#icon-images">
            </use>
          </svg>
        </label>
        <div class="user__cancel-box hidden">
          <svg class="icon-cross  icon-circle-with-cross" id="btn_cancel-img">
              <use xlink:href="assets/images/sprite.svg#icon-circle-with-cross">
              </use>
          </svg>
        </div>
        <input type="file" class="btn-hide" name="fileToUpload" id="fileToUpload">
        <div class="user__file-box hidden">
            <img src="" class = "user__img-file" id = "image_load" alt="Image preview">
        </div>

        <script>
                      
           const inputPic = document.getElementById("fileToUpload");
           const imgBox = document.querySelector(".user__file-box");

           inputPic.addEventListener('change', function(e){
            
            previewFile();
             
           });
           

           function previewFile() {
              const preview = document.getElementById('image_load');
              const file = document.getElementById('fileToUpload').files[0];
              const reader = new FileReader();

              reader.addEventListener("load", function () {
                preview.src = reader.result;
                console.log(preview.src);
              }, false);

              if (file) {
                reader.readAsDataURL(file);
                imgBox.classList.remove('hidden');
              }
            }
            
        </script>
        <img src="" alt="" class="">

        <textarea name="post_text"id="post_text" class="textarea-80" placeholder="Empty your mind"></textarea>
        <input type="submit" name="post" id="post_btn" value="post" class="btn__submit-green-medium margin-left-small">
      </form>
      <div class="post-area width-100">
        <input type ='hidden' id ='post_id' name = 'post_id' >
      </div>
        <div class="spinner-box">
          <div class="spinner-grow text-black-50 mr-3" role="status"></div>
          <div class="spinner-grow text-black-50 mr-3" role="status"></div>
          <div class="spinner-grow text-black-50 mr-3" role="status"></div>
          <div class="spinner-grow text-black-50 mr-3" role="status"></div>
          <div class="spinner-grow text-black-50 mr-3" role="status"></div>
          <div class="spinner-grow text-black-50" role="status"></div>
        </div>
      <div class="status_post"></div>
    </div>
    <div class="user__popular">
      <h2 class="heading-2">Popular</h2>
      <div class="user__popular-box">
      <?php
      $trend_query = mysqli_query($con,'SELECT * FROM trends ORDER BY hits DESC LIMIT 9');

      foreach($trend_query as $row){
        $word = $row['title'];
        $word_dot= strlen($word)> 14? "...":"";
        $split_word = str_split($word,14);
        $word = $split_word[0].$word_dot;

        echo "
          <div class='user__popular-text'>".$word."</div>
          ";
      }
      ?>   
      </div>
    </div>    
  </div>
  <div class="popup hidden" id="popup">
  </div>
  </body>
</html>

