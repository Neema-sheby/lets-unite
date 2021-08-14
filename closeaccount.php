<?php
include("includes/header.php");

/////////////////////WHEN CLOSE ACCOUNT CLICKED

if(isset($_POST['cancel'])){
    header("Location: settings.php");
  }

if(isset($_POST['close_account'])){
    $close_query = mysqli_query($con, "UPDATE users SET user_closed ='yes' WHERE username='$userLoggedIn'");
    session_destroy();
    header("Location: register.php");
  }
  

?>
<div class="close-acc">
    <h2 class="heading-2">
        Are you sure you want to close your account? 
    </h2>
    <p class="close-acc__text">
    Your account deleted means your friends won't be able to contact you in Let's Unite. Your profile and all your activity will be hidden from them. But you can re-open you account at any time by simply logging in. Hoping you will come back to us soon. If you would like to go back to your profile page, please press on the 'No, I would like to stay' button or if would like to delete your account, please press on the 'Yes, I would like to close my account' button. 
    </p>
    <form action='' method='post' class='close-acc__form'>
        <input type='submit' name='cancel' class='btn__submit-green-large'value='No, I would like to stay'>
        <input type='submit' name='close_account' class='btn__submit-alert-x-large'value='Yes, I would like to close my account'>
    </form>
    
</div>