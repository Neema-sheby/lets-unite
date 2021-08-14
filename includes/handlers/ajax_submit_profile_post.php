

<?php
require '../../config/config.php';
require '../../includes/get_userLoggedIn.php';
include('../../includes/classes/User.php');
include('../../includes/classes/Post.php');
include("../../includes/classes/notification.php");

// SUBMIT THE POST WITH THE HELP OF AJAX CALL in file: profile_post_message.js
//$msg = json_decode($_POST['text_msg']);
//echo $msg;

if(isset($_POST['text_msg'])){
    
    $post = new Post($con, $_POST['user_from']);
    $post->submitPost($_POST['text_msg'],$_POST['user_to'],"");
}

?>
 
<!--BY GET METHOD  
    if(isset($_GET['text_msg'])){
    
    $post = new Post($con, $_GET['user_from']);
    $post->submitPost($_GET['text_msg'],$_GET['user_to']);

    echo $_GET['user_from'];
    echo $_GET['user_to'];
    echo $_GET['text_msg'];
} -->