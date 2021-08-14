<?php
 include('../../config/config.php');
 include('../classes/User.php');
 include('../get_time_frame.php');
 include('../classes/notification.php');

 $limit = 5; //no of post to be loaded per call

 $notifications = new Notification($con, $_REQUEST['userDropdown']);
 echo $notifications->loadNotifications($_REQUEST,$limit);
?>
