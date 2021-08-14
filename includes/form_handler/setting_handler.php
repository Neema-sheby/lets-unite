
<?php

////////////////////////////////WHEN UPDATE DETAILS BUTTON IS CLICKED
$message1 = "";
$message2 = "";
$success1 = "";
$success2 = "";

if(isset($_POST['profile_update'])){

    $first_name = strip_tags($_POST['edit_firstname']);
    $first_name = str_replace(' ','',$first_name);
    $first_name = ucfirst(strtolower($first_name)); 

    $last_name = strip_tags($_POST['edit_lastname']);
    $last_name = str_replace(' ','',$last_name);
    $last_name = ucfirst(strtolower($last_name)); 

    $email = strip_tags($_POST['edit_email']);

    // Checking no of characters in firstname and last name
    if(strlen($first_name)>25 || strlen($first_name)<2 || strlen($last_name)>25 || strlen($last_name)<2 ){
        $message1 = "Your First name or Last name should be between 2 and 25 characters";
      }
      else{
        $email_check = mysqli_query($con, "SELECT email FROM users WHERE email ='$email'");
        $matched_username = $row['username'];
    
        if($matched_username == "" || $matched_username == $userLoggedIn){
            $success = "Details updated!";
            $email_update = mysqli_query($con,"UPDATE users SET first_name = '$first_name', last_name ='$last_name', email='$email' WHERE username='$userLoggedIn'");
        }
        else{
            $message1= "Email Already in use!";
        }
      }
}
else{
    $message1= "";
    $success1 = "";
}


///////////////////////////////WHEN UPDATE PASSWORD BUTTON IS CLICKED

if(isset($_POST['password_update'])){

    $old_password = strip_tags($_POST['old_password']);
    $new_password_1 = strip_tags($_POST['new_password']);
    $new_password_2 = strip_tags($_POST['new_password_again']);
    
    $password_query = mysqli_query($con, "SELECT password FROM users WHERE username = '$userLoggedIn'");

    $row = mysqli_fetch_array($password_query);

    $password_user = $row['password'];

    if(md5($old_password) != $password_user){

    $message2 = "The old password is incorrect!";
    }
    else{
        if($new_password_1 != $new_password_2){
            $message2 = "Your two new passwords don't match!";
        }
        else{
            if(preg_match('/\/[|^-_{}#~%]/', $new_password_1) || strlen($new_password_1)>30 || strlen($new_password_1)<5){
                $message2 = "Your password can contain only alphabets or numbers. It must be between 5 and 30 characters!";
              }
              else{
                  $md5_new_password = md5($new_password_1);

                  $update_password = mysqli_query($con, "UPDATE users SET password = '$md5_new_password' WHERE username = '$userLoggedIn'");

                  $success2 = "Password has been changed successfully!";
              }
        }
    }
}
else{
    $message2 ="";
    $success2 ="";
}

?>