
<?php
require 'config/config.php';
include('includes/classes/User.php');
include('includes/classes/Post.php');
include('includes/get_userLoggedIn.php');
include("includes/classes/notification.php");

//Get id of post

 if(isset($_GET['post_id'])){
    $post_id = $_GET['post_id'];

	$user_query = mysqli_query($con, "SELECT added_by, user_to FROM post WHERE id='$post_id'");

    $row = mysqli_fetch_array($user_query);

    $posted_by = $row['added_by'];
    $posted_to = $row['user_to'];
 }

if(isset($_POST['post_comment'])){
    $post_body = $_POST['post_body'];
    $post_body = mysqli_escape_string($con,$post_body);// remove special characters
    $date_time_now = date("Y-m-d H:i:s");

    $insert_post = mysqli_query($con, "INSERT INTO comments VALUES ('', '$post_body', '$userLoggedIn', '$posted_by','$date_time_now', 'no', '$post_id' ) ");

    if($posted_to != 'none' && $posted_by !=$userLoggedIn ){
        $notification =new Notification($con, $userLoggedIn);
        $notification->insertNotification($post_id,$posted_by, 'comment' ,"");
        if($posted_to != $userLoggedIn){
            $notification->insertNotification($post_id,$posted_to, 'reply' ,"");  
        }
    }

    if($posted_to != 'none' && $posted_by ==$userLoggedIn){
        $notification =new Notification($con, $userLoggedIn );
        $notification->insertNotification($post_id,$posted_to, 'reply',$posted_by );
    }

    if($posted_to == 'none' &&  $posted_by != $userLoggedIn){
        $notification = new Notification($con, $userLoggedIn);
        $notification->insertNotification($post_id,$posted_by, 'profile_comment',"" );
    }

    $get_commenters = mysqli_query($con, "SELECT * FROM comments WHERE post_id = '$post_id'");

    $notified_users = array();

    while($row = mysqli_fetch_array($get_commenters)){


        if($row['posted_by'] != $posted_by && $row['posted_by'] != $posted_to && $row['posted_by'] != $userLoggedIn && !in_array($row['posted_by'], $notified_users)){

            $notification1 = new Notification($con, $userLoggedIn);
            $notification1->insertNotification($post_id, $row['posted_by'], 'comment_non_owner',"" );
            array_push($notified_users,$row['posted_by'] );

        }
    }
    echo "<p class='comment-sent'>Comment posted!</p>";

} 
?>

<html lang="en">
<head>
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <script defer src="assets/js/comment.js"></script>
</head>
<body class="comment__body">
    <div class="comment__container">
            <!---- Comment container ---->
        <form action="comment_frame.php?post_id=<?php echo $post_id; ?>" class= "comment__form" id="comment_form" name="comment_form" method="POST">
            <div class= "comment__input-box">
                <textarea class= "comment__text" name="post_body" placeholder = "Your comment"></textarea>
                <input class= "btn__submit-green-small " type="submit" name="post_comment" value="Post">
            </div>
        </form>
            <!---- Load comments ---->
        <div class="comment__load-container">
            <?php
                include('includes/get_time_frame.php');
                include('includes/handlers/comment_load.php');
            ?>
        </div>
    </div>    

</html>