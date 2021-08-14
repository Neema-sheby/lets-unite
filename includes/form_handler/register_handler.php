<?php

//Declaring variables to prevent errors
$fname = "";// First name
$lname = "";// Last name
$email = "";// email
$email2 = "";// confirm email
$password = "";// password
$password2 = "";// confirm password
$date = "";// Sign up date
$error_array= array();//Hold error message
$errorIcon = "<svg class='icon-error'><use xlink:href='assets/images/sprite.svg#icon-warning'></use></svg>";

// if button pressed, start Handling the form
if(isset($_POST['reg_button'])){

  //Registration form values
  
  //# FIRST NAME
  // Below says that the values we got from the form to be saved as $fname.
  $fname = strip_tags($_POST['reg_fname']);//remove html tags
  $fname = str_replace(' ','', $fname); //remove spaces
  $fname = ucfirst(strtolower($fname)); //capitalise first letter and the remaining letters to lower case
  $_SESSION['reg_fname'] = $fname; //stores first name into session variable

  //# LAST NAME
  $lname = strip_tags($_POST['reg_lname']);
  $lname = str_replace(' ','', $lname); 
  $lname = ucfirst(strtolower($lname));
  $_SESSION['reg_lname'] = $lname;

  //# EMAIL
  $email = strip_tags($_POST['reg_email']);
  $email = str_replace(' ','', $email); 
  $_SESSION['reg_email'] = $email;

  //# EMAIL2
  $email2 = strip_tags($_POST['reg_email2']);
  $email2 = str_replace(' ','', $email2); 
  $_SESSION['reg_email2'] = $email2;

  //# PASSWORD
  $password = strip_tags($_POST['reg_password']);

  //# PASSWORD2
  $password2 = strip_tags($_POST['reg_password2']);

  //# DATE
  $date = date("Y-m-d");

  //Check email is in a valid format
  if($email == $email2){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){

      // Remove all illegal characters from email
      $email = filter_var($email, FILTER_VALIDATE_EMAIL);

      //Check if email exists
      $email_check = mysqli_query($con, "SELECT email FROM users WHERE email='$email'");

      //Count the number of rows returned
      $num_rows = mysqli_num_rows($email_check);

      if($num_rows > 0){
        array_push($error_array,"Email already in use<br>");
      }
    }
    else{
      array_push($error_array,"Invalid Format<br>");
    }
    
    // Checking no of characters in firstname and last name
    if(strlen($fname)>25 || strlen($fname)<2){
      array_push($error_array, "Your First name should be between 2 and 25 characters");
    }
    if(strlen($lname)>25 || strlen($lname)<2){
      array_push($error_array, "Your Last name should be between 2 and 25 characters");
    }
    if($password != $password2){
      array_push($error_array, "Passwords do not match");
    }
    else{
      if(preg_match('/\/[|^-_{}#~%]/', $password)){
        array_push($error_array, "Your password can contain only alphabets or numbers");
      }
    }
    if(strlen($password)>30 || strlen($password)<5){
      array_push($error_array, "Your password must be between 5 and 30 characters");
    }
  }
  else{
    array_push($error_array, "Emails don't match");
  }

  if(empty($error_array)){
    //Encrypt password before sending to database
    $password = md5($password);

    //Generate Username by concatenating First name and last name
    $username = strtolower($fname."_".$lname);

    // Check if username is in the database
    $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username= '$username'");
  

    $i = 0;
    $temp_username = $username;

    //if username exists add number to user name
    while(mysqli_num_rows($check_username_query) != 0){
      $i++;
      $temp_username = $username;
      $temp_username = $username.'_'.$i;
      $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$temp_username'");

    }

    $username = $temp_username;
 

    //Profile picture assignment
    $rand = rand(1,2); //Random number between 1 and 2

    if($rand ==1){
       $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
    }
    else if($rand==2){
      $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
    }

    $bg_pic = "assets/images/profile_pics/defaults/bg/main.jpg";

    $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$email','$password', '$date', '$profile_pic', '0', '0','no', ',','$bg_pic','')");

    //Display a success message and Removing details from form
    array_push($error_array, "<span class= 'success-msg'> You're all set! Go ahead and login!</span>");
    // clear session variables
    $_SESSION['reg_fname'] = "";
    $_SESSION['reg_lname'] = "";
    $_SESSION['reg_email'] = "";
    $_SESSION['reg_email2'] = "";
    
  }
}


?>