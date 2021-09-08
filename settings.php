<?php
include("includes/header.php");
include("includes/form_handler/setting_handler.php");
?>

<div class='settings'>
    <div class='settings__container'>
            <h2 class='heading-1-lato '>Account Settings</h2>

            <div class='border-line margin-btm-small'></div>

        <div class='settings__img-container'>    

            <!-- ------------BACKGROUND IMAGE---------------->

            <div class='settings__img-box'>
                <img src="<?php echo $user['background_image'] ?>"
                alt='profile_image' class='settings__img'>
                <div class='settings__img-upload'>
                    <h3 class='heading-3 margin-btm-small'>Background picture</h3>
                    <a href='upload_bg-image.php' class="btn__submit-green-link-80">Upload</a>
                </div>
            </div>

            <!-- ------------PROFILE PICTURE--------------- -->

            <div class='settings__img-box'>
                <img src="<?php echo $user['profile_pic'] ?>" alt='empty' class='settings__img'>
                <div class='settings__img-upload'>
                    <h3 class='heading-3 margin-btm-small'>Profile picture</h3>
                    <a href='upload_image.php' class="btn__submit-green-link-80">Upload </a>
                </div>
            </div>
        </div>

        <!-- ------------CHANGE PROFILE DETAILS---------- -->

        <div class='settings__form-container'>    
            <div class='settings__form'>
                <h3 class='heading-3'>Change Profile details</h3>
                <div class='border-line'></div>
                <?php
                $updated_query = mysqli_query($con, "SELECT first_name, last_name, email FROM users WHERE username = '$userLoggedIn'");
                $row = mysqli_fetch_array($updated_query);
                $firstname = $row['first_name'];
                $lastname = $row['last_name'];
                $email = $row['email'];
                ?>
                <form action='settings.php' method='post' class='margin-top-small margin-btm-x-small'>
                    <div class="declined margin-btm-x-small"><?php echo $message1;?></div>
                    <div class="successful margin-btm-x-small"><?php echo $success1;?></div>
                    <label for='edit_firstname' class='settings__label'>First Name:</label >
                    <input type='text' name='edit_firstname' class='settings__input' value="<?php echo $firstname;?>" required>
                    <label for='edit_lastname' class='settings__label'>Last Name:</label>
                    <input type='text' name='edit_lastname' class='settings__input' value="<?php echo $lastname;?>" required>
                    <label for='edit_email' class='settings__label'>Email:</label>
                    <input type='email' name='edit_email' class='settings__input' value="<?php echo $email;?>" required>
                    <input type='submit' name='profile_update' class='btn__submit-green-x-large'id ='profile_update' value='Update details'>
                </form>
            </div>

            <!-- ------------CHANGE PASSWORD---------- -->

            <div class='settings__form'>
                <h2 class='heading-3'>Change Password</h2>
                <div class='border-line'></div>
                <form action='' method='post' class='margin-top-small margin-btm-x-small'>
                    <div class="declined margin-btm-x-small"><?php echo $message2;?></div>
                    <div class="successful margin-btm-x-small"><?php echo $success2;?></div>
                    <label for='old_password'class='settings__label' >Old Password:</label>
                    <input type='password' name='old_password' class='settings__input' required>
                    <label for='new_password'class='settings__label' >New Password:</label>
                    <input type='password' name='new_password' class='settings__input' required>
                    <label for='new_password_again' class='settings__label'>New Password Again:</label>
                    <input type='password' name='new_password_again' class='settings__input margin-btm-x-small'required>
                    <input type='submit' name='password_update' class='btn__submit-green-x-large 'value='Update password'>
                </form>
            </div>
        </div>

        <!-- ------------CLOSE ACCOUNT---------- -->
        <div class='settings__close-account text-align'>
            <h2 class='heading-3'>Close Account</h2>
            <div class='border-line margin-btm-x-small'></div>
            <a href='closeaccount.php' class='btn__submit-red-link '>Close Account</a>
        </div>
</div>
