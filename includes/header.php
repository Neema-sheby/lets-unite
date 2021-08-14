<?php
require "includes/scssphp.php";
require 'config/config.php';
require 'includes/get_userLoggedIn.php';
include('includes/get_time_frame.php');
include('includes/classes/User.php');
include('includes/classes/Post.php');
include("includes/classes/message.php");
include("includes/classes/notification.php")
?>


<!DOCTYPE html>
<html>
  <head>
    <title>Lets unite</title>

    <!--********************************************************************************************************
                                               - CSS LINKS -
    **********************************************************************************************************-->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> 

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

    <!--cropper.css-->
    <link rel="stylesheet" href="assets/css/cropper.min.css" type="text/css">

    <link rel="stylesheet" href="assets/css/style.css" type="text/css">


    <!--********************************************************************************************************
                                                - SCRIPTS -
    **********************************************************************************************************-->

    <script defer src="https://kit.fontawesome.com/9609d07ac8.js" crossorigin="anonymous"></script>

    <!-- jquery -->    
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

    <!--cropper.js-->
    <script type="text/javascript" defer src="assets/js/cropper.min.js"></script>

     <!-- bootbox code -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

     <!--  scripts  -->
    <script type="text/javascript" defer src="assets/js/comment.js"></script>
    <script type="text/javascript" defer src="assets/js/ajax_call.js"></script>
    <script type="text/javascript" defer src="assets/js/profile_post_message.js"></script>
    <script type="text/javascript" defer src="assets/js/delete_post.js"></script>
    <script type="module" defer src="assets/js/upload_image.js"></script>
    <script type="module" defer src="assets/js/upload_bg-image.js"></script>
    <script type="module" defer src="assets/js/ajax_friends-search.js"></script>
    <script type="module" defer src="assets/js/nav_dropdown.js"></script>
    <script type="module" defer src="assets/js/infiniteScroll _dropdown.js"></script>
    <script type="module" defer src="assets/js/navi_search_bar.js"></script>
    <script type="module" defer src="assets/js/title_message.js"></script>
    <script type="module" defer src="assets/js/pos_img.js"></script>
    
     
  </head>
  <body>
    <?php
    // GET UNREAD MESSAGES
    $message = new Message($con,$userLoggedIn);
    $unreadMsgs = $message->getUnreadNumber();
  
    // GET UNREAD NOTIFICATIONS
    $notification = new Notification($con, $userLoggedIn);
    $unreadNotifications = $notification->getUnreadNumber();

    // GET NUMBER OF FRIEND REQUESTS
    $userLoggedInObj = new User($con,$userLoggedIn);
    $numFriendRequests = $userLoggedInObj->getNumberOfFriendRequests();
    ?>

    <nav class="navi">
      <div class="navi__title-box">
        <a href="register.php" class="navi__title heading-1">Lets unite!</a>
      </div>
      <div class="navi__search-box">
        <form action="search.php" method="GET" class = "navi__search"name="search_form">
          <input type="text" class="navi__search-input" name="q" placeholder="Search" autocomplete="off" id="search_input">
        </form>
        <div class ="navi__search-icon-box" id="search-btn">
          <svg class="icon-search">
            <use xlink:href="assets/images/sprite.svg#icon-magnifying-glass"></use>
          </svg>
        </div>
        <div class="search__results"></div>
        <div class="search__see-all-results"></div>
      </div>

      <ul class="navi__icon-box">
      <li class ="navi__username-box"><a class ="navi__link"  href="<?php echo $userLoggedIn; ?>">
        <?php
        echo "<p class='navi__username'>".$user['username']."</p>" ;
        ?>
        </a></li>
        
        <li class ="icon"><a class ="navi__link"  href="index.php">
        <svg class="icon-small">
          <use xlink:href="assets/images/sprite.svg#icon-home"></use>
        </svg>
        </a></li>
        
        <li class ="icon navi__mail">
          <a class ="navi__link "  href="javascript:void(0)" id="msg-icon">
            <svg class="icon-small">
              <use xlink:href="assets/images/sprite.svg#icon-mail"></use>
            </svg>
        </a>
        <?php
            if($unreadMsgs > 0){
              echo "
              <span class='navi__unreadMsgs' id='unread-message'>$unreadMsgs</span>
              ";
            }
            ?>
      </li>

        <li class ="icon navi__bell">
        <a class ="navi__link"  href="javascript:void(0)" id='bell-icon'>
          <svg class="icon-small">
            <use xlink:href="assets/images/sprite.svg#icon-bell"></use>
          </svg>
        </a>
        <?php
            if($unreadNotifications > 0){
              echo "
              <span class='navi__unreadNotifications' id='unread-notification'>$unreadNotifications</span>
              ";
            }
            ?>
      
      </li>

        <li class ="icon  navi__users">
            <a class ="navi__link"  href="requests.php">
              <svg class="icon-small">
                <use xlink:href="assets/images/sprite.svg#icon-users"></use>
              </svg>
            </a>
            <?php
                if($numFriendRequests > 0){
                  echo "
                  <span class='navi__numFriendRequests' id='unread-FriendRequests'>$numFriendRequests</span>
                  ";
                }
            ?>
        </li>
        
        <li class ="icon"><a class ="navi__link"  href="settings.php">
        <svg class="icon-small">
          <use xlink:href="assets/images/sprite.svg#icon-cog"></use>
        </svg>
        </a></li>
        <li class ="icon"><a class ="navi__link"  href="includes/handlers/logout.php">
        <svg class="icon-small">
          <use xlink:href="assets/images/sprite.svg#icon-log-out"></use>
        </svg>
        </a></li>
      </ul>
    </nav>
    <div class="navi__dropdown-window" style="height: 0rem;"></div>
    <div class="dropdown-bottom"></div>
    <form method="post" class="navi__dropdown-form"></form>
    <input type=hidden id="user_dropdown" name="user_dropdown" value="<?php echo $userLoggedIn; ?>">
    <input type=hidden id="dropdown_data_type" name="dropdown_data_type" value="">
    
  
  
  
  

