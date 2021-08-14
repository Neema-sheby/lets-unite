
<?php 
if(isset($_POST['login_button'])){

    $email = filter_var($_POST['login_email'],FILTER_SANITIZE_EMAIL); //SANITIZE EMAIL

    $_SESSION['login_email'] = $email; 

    $_password = md5($_POST['login_password']); // get password

    $check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email = '$email' AND password = '$_password' ");
    
    $check_login_query = mysqli_num_rows( $check_database_query);

    if( $check_login_query ==1){
     
     //fetches the result row as an associative array, a numeric array, or both.
     $row = mysqli_fetch_array($check_database_query); 

     // Create a new variable called username and value is obtained from the $row data array obtained from the database.
     $username = $row['username'];
     $_SESSION['username'] = $username;

     //check if account is closed

     $user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email = '$email' and user_closed = 'yes'");

     //If account is closed, change it to opened

     if(mysqli_num_rows($user_closed_query)==1){
       $reopen_account = mysqli_query($con, "UPDATE users SET user_closed = 'no' WHERE email = '$email'");
     }

     header("Location: index.php");
     exit();
    }
    else {
        array_push($error_array,"The email address or password is incorrect") ;
    }
}
?>