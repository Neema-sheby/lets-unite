
<?php
require 'includes/header.php';

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['profile_username'])){
  $username = $_GET['profile_username'];
  //echo "<script>console.log('$profile_name')</script>";
  $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
  $row = mysqli_fetch_array($user_details_query);
  $profile_pic = $row['profile_pic'];
  $num_posts = $row['num_posts'];
  $num_likes = $row['num_likes'];
  $user_to_name =$row['first_name']." ".$row['last_name'];

  // GETS NUMBER OF FRIENDS FROM ARRAY
  $num_friends = (substr_count($row['friend_array'], ","))-1;
}

//IF REMOVE BUTTON IS CLICKED
if (isset($_POST['remove_friend'])){
  $user = new User($con,$userLoggedIn);
  $user->removeFriend($username);
  header('Location: index.php');
}
//IF ADD BUTTON IS CLICKED
if (isset($_POST['add_friend'])){
  $user = new User($con,$userLoggedIn);
  $user->sendRequest($username);
}
//IF RESPOND REQUEST BUTTON IS CLICKED
if (isset($_POST['respond_request'])){
  header('Location: requests.php');
}

//IF MESSAGE SEND BUTTON IS CLICKED
if(isset($_POST['msg_post'])){
  if(isset($_POST['msg_body'])){

    $msg= mysqli_real_escape_string($con,$_POST['msg_body']);
    $date = date("Y-m-d H:i:s");
    $message_obj->sendMessage($username, $msg, $date);
  }

  $link = '#myTab a[href="#messages"]';
  echo "
  <script>
   const ele -
  </script>
";



}


?>
<div class="profile">
  <div class="profile__sidebar">
    <div class="profile__background-img">
        <img class="profile__background" style = "background-image: url('assets/images/profile_pics/defaults/main.jpg')">
    </div>
    <div class="profile__img-box">
        <img src="<?php echo $profile_pic;?>" alt="<?php echo $username;?>" class="profile__img">
    </div>
    <p class="profile__name"><?php echo $username;?></p>
    <div class="profile__details">
      <p class="profile__num-posts">Posts:&nbsp<?php echo $num_posts;?></p>
      <p class="profile__num-likes">Likes:&nbsp<?php echo $num_likes;?></p>
      <p class="profile__num-friends">Friends:&nbsp<?php echo $num_friends;?></p>
    </div>
    <form action="<?php $username;?>" class="profile__form" method="post">
    
    <?php

     // CHECK IF USER IS CLOSED
     $new_friend = new User($con,$username);
  
     if($new_friend->isClosed()){
       header('Location: user_closed.php');
     }
     // CHECK IF USER IS CLOSED
     $user_logged_in = new User($con,$userLoggedIn);
     
     if($userLoggedIn != $username){
       
      // COMMON FRIENDS 
        echo "<p class='profile__common-friends'>";
        echo $user_logged_in->getCommonFriends($username);
        echo "</p>";

      if($user_logged_in->checkIfFriends($username)){
        echo "<input type='submit' class='profile__remove-friend' name='remove_friend' value='Remove Friend'>";
      }
      else if($user_logged_in->receivedFriendRequest($username)){
        echo "<input type='submit' class='profile__respond-request' name='respond_request' value='Respond to request'>";
      }
      else if($user_logged_in->sendFriendRequest($username)){
        echo "<input type='text' class='profile__send-request' name='send_request' value='Request sent' readonly>";
      }else{
       echo "<input type='submit' class='profile__add-friend' name='add_friend' value='Add Friend'>";
      }
     }
    ?>
    </form>
      <!-- POST MESSAGE BUTTON -->
      <input type="button" class="profile__post-msg" name="post_message" value="Post Message" data-bs-toggle="modal" data-bs-target="#post_message">
 
  </div>
  <div class="profile__main">

    <!---------- PROFILE PAGE TAB FOR POSTS AND MESSAGES ---------->
    <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link fs-4 fw-bold active" id="posts-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="true">Posts</a>
      </li>
      <li class="nav-item">
        <a class="nav-link fs-4 fw-bold" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="true">Messages</a>
      </li>
    </ul>

    <div class="tab-content mt-5 mb-5 ">
      <div class="tab-pane active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
          <div class="post-area">
            <input type ='hidden' id ='post_id' name = 'post_id'>
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
      <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
          <div class="message__tab"> 
            <?php
            include("includes/handlers/profile_page_msg.php");
            ?>
          </div> 
      </div>
    </div> 
  </div>  


<!---------- MODAl WHEN post message BUTTON IS CLICKED----------->

  <div class="modal fade" id="post_message" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
      <div class = modal__container>
        <div class="modal-header">
          <h2 class="modal-title" id="exampleModalLabel">Post Something!</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-sm-start"> 
          <p class="modal__text">This message will appear in user's profile and their newsfeed for your friends to see !</p>
          <form action="" method="post" class="modal__form" id="modal_form">
            <label for="text_msg" class="modal__label">Message</label>
            <textarea class="modal__text-area" name="text_msg" placeholder="Enter Your Message here"></textarea>
            <input type="hidden" name="user_to" value="<?php echo $username ?>">
            <input type="hidden" name="user_from" value="<?php echo $userLoggedIn?>">
          </form>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn__submit-green-large" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn__submit-green-large" id="profile_post_something">Post Message</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    // CHANGE TABS- post and messages OF PROFILE PAGE
    const triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'));
     triggerTabList.forEach(function (triggerEl) {
    const tabTrigger = new bootstrap.Tab(triggerEl);

    triggerEl.addEventListener('click', function (e) {
      e.preventDefault()
      tabTrigger.show()
      });
    });

  // STORE TAB INTO LOCAL
  const btnSendMsg = document.querySelector('.message__submit');
  btnSendMsg.addEventListener('click', function (e) {
    localStorage.setItem('myTab','m')
  });

   // STAY IN THE MESSAGE TAB AFTER SUBMITTING MESSAGE
  const msgTab = localStorage.getItem('myTab');

  if(msgTab){
    const messagesEl = document.querySelector('#myTab a[href="#messages"]');
    const tab = new bootstrap.Tab(messagesEl);
    tab.show();
    localStorage.removeItem('myTab');

    // SCROLL TO LATEST MESSAGE
    const scrollToMsg = document.getElementById('scroll_to_new_msg');
    if(scrollToMsg){
    scrollToMsg.scrollTop = scrollToMsg.scrollHeight;
    }
  }
  
  </script> 

</body>
</html>




