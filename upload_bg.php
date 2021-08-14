<!-- ***************************************************************************************************************************************************
                                        GET THE BACKGROUND IMAGE FROM upload_image.js AND SAVE IT TO THE DATABASE
*************************************************************************************************************************************************** -->

<?php

include('includes/handlers/create_file-path.php');

//GET THE IMAGE PATH
$img_src = createFilePath("backgrounds");
//GET THE USERLOGGED IN
$userLoggedIn = $_POST['userLoggedIn'];
$query = mysqli_query($con, "UPDATE users SET background_image='$img_src' WHERE username='$userLoggedIn'
");

?>