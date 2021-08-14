<?php
include("../../config/config.php");
include("../classes/User.php");
include("../classes/message.php");
include("../get_time_frame.php");

$limit = 7;//number of messages to load

$message = new Message($con,$_REQUEST['userDropdown']);

echo $message->getConversationsDropdown($_REQUEST,$limit);

?>