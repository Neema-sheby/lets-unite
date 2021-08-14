<?php
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);


if(isset($_GET['u'])){
    $user_to = $_GET['u'];
}
else{
    $user_to = $message_obj->getHostRecentUser();
    if($user_to == false){
        $user_to = "new";
    }
    
}

if($user_to != "new"){
    $user_to_obj = new User($con, $user_to);
    $user_to_name = $user_to_obj->getFirstAndLastName();
    $query = mysqli_query($con, "UPDATE messages SET opened='yes' WHERE user_to = '$userLoggedIn' AND user_from = '$user_to'");

}

//WHEN POST MESSAGE BTN PRESSED, SEND MESSAGE TO DATABASE
if (isset($_POST['msg_post'])){

    if (isset($_POST['msg_body'])){

        $msg= mysqli_real_escape_string($con,$_POST['msg_body']);
        $date = date("Y-m-d H:i:s");
        $message_obj->sendMessage($user_to, $msg, $date);
    }
}

?>

<div class="message">
    <div class="user__details">
        <a href="<?php echo $userLoggedIn ?>" class="user__image">
        <img src="<?php echo $user['profile_pic']?>" class="user__image--1">
        </a> 
            
        <a href="<?php echo $userLoggedIn ?>" class="user__name"><?php echo $user['first_name']." ".$user['last_name']?></a> 

        <p class="user__num-post"> Posts: <?php echo $user['num_posts']?></p>
        <p class="user__likes"> Likes: <?php  echo $user['num_likes']?></p> 
    </div>

    <div class="message__box">
        <?php
        if($user_to != "new"){
            echo "
            <h2 class='heading-2-border'>You and <a href='$user_to' class='user__name'>$user_to_name</a>
            </h2>";
            echo "<div class='message__all-chats' id='scroll_to_new_msg'>";
            echo $message_obj->getMessage($user_to);
            echo "</div>";
            
        }
        ?>
        <div class="message__loaded">
            <form  action="" method="post" class="message__form">
            <?php
            if($user_to == "new"){
                echo "<h4 class='heading-2 margin-btm-small'>Select the friend you would like to message </h4>";
                echo "<label for='' class='message__label'>To:</label>";
                echo "<input type='text' class='textarea-80 message__friend-search' name='friend-name' placeholder='Enter Name' autocomplete='off'>";
                echo "<input type='hidden' class='friend-hidden' value='$userLoggedIn'>";
                echo "<div class='message__result-box'></div>";
                }
            else{
                echo "
                <div class='message__post-chat'>
                <textarea name='msg_body' class='textarea-80' placeholder='Write your message'></textarea>
                <input type='submit' name='msg_post' class='btn__submit-green-medium  message__submit' value='Send'>
                </div>";
            }
            ?>
            </form>
        </div>
    </div>
    <div class="message__conversation-box">
        <h2 class='heading-2-border '>Conversations</h2>
        <div class="message__all-conversations">
        <?php echo $message_obj -> getAllConversations(); ?>  
        </div>
        <a href="messages.php?u=new" class="btn__submit-green-link-80 margin-top-small">New Message</a>
    </div>    
</div>

<script>
    // SCROLL TO LATEST MESSAGE
    const scrollToMsg = document.getElementById('scroll_to_new_msg');
    if(scrollToMsg){
    scrollToMsg.scrollTop = scrollToMsg.scrollHeight;
    }
</script>
    






