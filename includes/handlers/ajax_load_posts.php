<?php
 include('../../config/config.php');
 include('../classes/User.php');
 include('../get_time_frame.php');
 include('../classes/Post.php');
 include('../classes/notification.php');

 $limit = 5; //no of post to be loaded per call

 $posts = new Post($con, $_REQUEST['userLoggedIn']);
 $posts->loadPostsFriends($_REQUEST, $limit);
?>

