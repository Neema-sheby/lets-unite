<?php
include("includes/header.php");

if(isset($_GET['id'])){

    $id = $_GET['id'];
}
else{
    $id=0;
}
?>

<div class="posts">
    <div class="posts__details">
        <a href="<?php echo $userLoggedIn ?>" class="user__image">
            <img src="<?php echo $user['profile_pic']?>" class="user__image--1">
        </a> 
        
        <a href="<?php echo $userLoggedIn ?>" class="user__name"><?php echo $user['first_name']." ".$user['last_name']?></a> 

        <p class="user__num-post"> Posts: <?php echo $user['num_posts']?></p>
        <p class="user__likes"> Likes: <?php  echo $user['num_likes']?></p> 
    </div>

    <div class='posts__message-box'>
      <div class='singlepost-area'>
        <?php 
        $user_posts = new Post($con, $userLoggedIn);
        $user_posts->getSinglePost($id);
        ?>
      </div>
    </div>

</div>