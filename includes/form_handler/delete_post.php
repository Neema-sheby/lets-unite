
<?php

require '../../config/config.php';

if(isset(($_GET['id'])))

$result_id = $_GET['id'];

echo "<script> console.log( $result_id)</script>";

$delete_post_query = mysqli_query($con,"UPDATE post SET deleted='yes' WHERE id = '$result_id'" );

?>