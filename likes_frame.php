
<?php

use ScssPhp\ScssPhp\Formatter\Nested;

require 'config/config.php';
include('includes/get_userLoggedIn.php');
include('includes/classes/User.php');
include('includes/classes/Post.php');
include("includes/classes/notification.php");


// WHEN THE URL HAS post_id
if(isset($_GET['post_id'])){
  $post_id = $_GET['post_id'];
}

  // GET NUMBER OF LIKES------------------------
  $get_post_likes = mysqli_query($con, "SELECT added_by, likes FROM post WHERE id ='$post_id'");
  $row_post = mysqli_fetch_array($get_post_likes);
  $tot_post_likes = $row_post['likes'];
  $user_liked = $row_post['added_by'];

  // GET NUMBER OF LIKES---------------------------
  $get_users_likes = mysqli_query($con, "SELECT * FROM users WHERE username ='$userLoggedIn'");
  $row_users = mysqli_fetch_array($get_users_likes);
  $tot_users_likes = $row_users['num_likes'];



  //echo "<script> console.log($num_like) </script>";
  // WHEN LIKE BUTTON IS CLICKED------------------
   if(isset($_POST['like_comment'])){
    
    // UPDATE LIKES IN POSTS
    $tot_post_likes++;
    $likes_post = mysqli_query($con, "UPDATE post SET likes = '$tot_post_likes' WHERE id ='$post_id'");

    // UPDATE LIKES IN USERS
    $tot_users_likes++;
    $likes_users = mysqli_query($con, "UPDATE users SET num_likes = '$tot_users_likes' WHERE username = '$userLoggedIn'");


    
    // INSERT LIKE DETAILS INTO LIKE
    $insert_like_data = mysqli_query($con, "INSERT INTO likes VALUES ('','$userLoggedIn','$post_id')");

    if($user_liked != $userLoggedIn){

       $notification = new Notification($con,$userLoggedIn);
       $notification ->insertNotification($post_id,$user_liked, 'like');


    }

   

   }

   // WHEN UNLIKE BUTTON IS CLICKED------------
   if(isset($_POST['unlike_comment'])){
    
    // UPDATE LIKES IN POSTS
    $tot_post_likes--;
    $likes_post = mysqli_query($con, "UPDATE post SET likes = '$tot_post_likes'WHERE post_id='$post_id'");

    // UPDATE LIKES IN USERS
    $tot_users_likes--;
    $likes_users = mysqli_query($con, "UPDATE users SET num_likes = '$tot_users_likes' WHERE username = '$userLoggedIn'");
    
    // INSERT LIKE DETAILS INTO LIKE
    $delete_like_data = mysqli_query($con, "DELETE FROM likes WHERE post_id = '$post_id' AND username ='$userLoggedIn'");
   }



  // CHECK FOR PREVIOUS LIKES ---------
  $like_query = mysqli_query($con, "SELECT * FROM likes WHERE username ='$userLoggedIn' AND post_id = '$post_id'");

  $tot_likes = mysqli_num_rows($like_query);
 

  if($tot_likes >0){
    
        echo "
        <form action='likes_frame.php?post_id=$post_id' class='likes__form' method='post'>
           <input type='submit' class='btn-text unlike-comment' name='unlike_comment' value='Unlike'>
          <span class='likes__num'>($tot_likes Likes)</span>
        </form>
        ";
      } 
  else{
    echo "
    <form action='likes_frame.php?post_id=$post_id' class='likes__form' method='post'>
      <input type='submit' class='btn-text like-comment ' name='like_comment' value='Like'>
      <span class='likes__num'>($tot_likes Likes)</span>
  </form>
    ";  
  }
?>


<html lang="en">
<head>
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
</head>
<body class="likes__body">
</body>
</html>