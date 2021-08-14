
<!-- ***************************************************************************************************************************************************
                                        GET THE IMAGE FROM upload_image.js AND SAVE IT TO THE DATABASE
*************************************************************************************************************************************************** -->

<?php

include('includes/handlers/create_file-path.php');

//GET THE IMAGE PATH
$img_src = createFilePath("profile_pics");
//GET THE USERLOGGED IN
$userLoggedIn = $_POST['userLoggedIn'];
$query = mysqli_query($con, "UPDATE users SET profile_pic='$img_src' WHERE username='$userLoggedIn'
");

?>
