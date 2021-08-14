<!-- ***************************************************************************************************************************************************
                                        CREATES THE FILE PATH SENT FROM upload_image.js / upload_background-image.js
*************************************************************************************************************************************************** -->

<?php
require "config/config.php";

function createFilePath($image_folder_name){

    // GET THE  SOURCE
    $folderPath = 'assets/images/'.$image_folder_name.'/';
    $image_parts = explode(";base64,", $_POST['image']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $img_src = $folderPath . uniqid() . '.png';

    // IMAGE FILE
    file_put_contents($img_src, $image_base64); 
    echo "<script>";
    echo 'console.log('. json_encode($folderPath) .')';
    echo "</script>";

    return $img_src;

}

?>