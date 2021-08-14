
<?php
//Use scss in php
require "includes/scssphp.php";
//importing file (should be in order as some file depends on other)
require 'config/config.php';
require 'includes/form_handler/register_handler.php';
require 'includes/login_handler/login_handler.php';
?>

<!DOCTYPE html>
<html>
  <head>

  <link rel="stylesheet" href="assets/css/style.css" type="text/css">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

  <script defer src="assets/js/reg_log.js"></script>

  <title>Lets unite</title>
  
  </head>
  <body>
    <div class="header-container">
      <div class="header">
        <div class="form-box__heading">
          <h2 class="heading-1">Lets unite!</h2>
          <h4 class="heading-4">Login or sign up below!</h4>
        </div>
        <div class="form-box">
          <div class="login">
            <form action="register.php" method="POST" class = "login-form">
            <div class="input-box"> 
              <input id = "login-email" type="email" name="login_email" placeholder = "Email address" class="login-form__details" value = "<?php
              if(isset($_SESSION['login_email'])){echo $_SESSION['login_email'];}
              ?>" required>
            </div>

            <div class="input-box"> 
              <input id = "login-password" type="password" name="login_password" class="login-form__details" placeholder = "Password" required>
            </div>

              <input type="submit" name="login_button" value="Login" class="btn margin-btm-small btn-login">
            
              <?php
              if(in_array("The email address or password is incorrect",$error_array)){
                echo "<p class='err-msg error-hidden'> The email address or password is incorrect</p>";
              }
              ?>
            </form>
            
            <a href ="#"
                    class="btn-link">Create an account?     <b class="join">Register here!</b></a>
          </div>
          <div class="register">
            <form action="register.php" method="POST" class="register-form">
              <div id = "firstname" class="input-box">
                <input type="text" name="reg_fname" placeholder="First Name"class="register-form__details"value = "<?php if(isset($_SESSION['reg_fname'])){ echo $_SESSION['reg_fname'];}?>" required  >
                            <?php
                            if(in_array("Your First name should be between 2 and 25 characters", $error_array)){
                              echo  $errorIcon;
                              echo "<p class='c-box__left c-box__arrow--left'> Your First name should be between 2 and 25 characters</p>";
                              }
                            ?>           
              </div>
              <div id = "lastname" class="input-box">
                <input type="text" name="reg_lname" placeholder="Last Name"class="register-form__details" value="<?php if(isset($_SESSION['reg_lname'])){ echo $_SESSION['reg_lname'];} ?>" required>
                            <?php
                            if(in_array("Your Last name should be between 2 and 25 characters", $error_array)){
                              echo  $errorIcon;
                              echo "<p class='c-box__right c-box__arrow--right'> Your Last name should be between 2 and 25 characters</p>";
                            }
                            ?>
              </div>
              <div id= "email1" class="input-box">       
                <input type="email" name="reg_email" placeholder="Email"class="register-form__details" value="<?php if(isset($_SESSION['reg_email'])) {echo $_SESSION['reg_email'];}?>" required>
              </div>

              
              <div id= "email" class="input-box">
                <input type="email" name="reg_email2" placeholder="Confirm email"class="register-form__details" value ="
                <?php if(isset($_SESSION['reg_email2'])){ echo $_SESSION['reg_email2'];} ?>" required>
                            <?php
                          

                            if(in_array("Emails don't match", $error_array)){
                            echo  $errorIcon;
                            echo "<p class='c-box__left c-box__arrow--left'> Emails don't match</p>";
                            }

                            else if(in_array("Email already in use", $error_array)){
                              echo  $errorIcon;
                              echo "<p class='c-box__left c-box__arrow--left '> Email already in use</p>";
                            }

                            else if(in_array("Invalid Format", $error_array)){
                              echo  $errorIcon;
                              echo "<p class='c-box__left c-box__arrow--left'> Invalid Format</p>";
                            }
                            ?>
              </div> 
              <div id="password" class="input-box">       
                <input type="password" name="reg_password" placeholder="password"class="register-form__details" required >
          
                <?php
                            if(in_array("Your password can contain only alphabets or numbers", $error_array)){
                              echo  $errorIcon;
                              echo "<p class='c-box__right c-box__arrow--right'> Your password can contain only alphabets or numbers</p>"; 
                            }

                            else if(in_array("Your password must be between 5 and 30 characters", $error_array)){
                              echo  $errorIcon;
                              echo "<p class='c-box__right c-box__arrow--right'> Your password must be between 5 and 30 characters</p>";
                            }

                            else if(in_array("Passwords do not match", $error_array)){
                              echo  $errorIcon;
                              echo "<p class='c-box__right c-box__arrow--right'>Passwords do not match</p>";
                            }
                            ?>
              </div>
              <div id="password1" class="input-box"> 
                <input  type="password" name="reg_password2" placeholder="Confirm password"class="register-form__details" required >
              </div>       

              <input type="submit" name="reg_button" value="Register" class="btn margin-top-small ">
          </form>
          <div class="register-form__link">
            <a href ="#" class="btn-link">Already a member?     <b class="signin">Sign here!</b></a>
          </div>   
                          <?php
                          if(in_array("<span class= 'success-msg'> You're all set! Go ahead and login!</span>", $error_array)){
                            echo "<span class= 'success-msg'> You're all set! Go ahead and login!</span>";
                          }
                          ?>
        </div>        
      </div> 
     </div>                  
    </div>
  </body>
</html>