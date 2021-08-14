<?php
require 'includes/header.php';
?>


<div class="requests">
    <h4 class="heading-2 ">Your Friend requests</h4>
    <div class="requests__list">
        <?php
        $query = mysqli_query($con, "SELECT * FROM friend_requests WHERE user_to ='$userLoggedIn'");

        $num_requests = mysqli_num_rows($query);

        if($num_requests == 0){
        echo "<div class='requests__none'>You have no freiend requests</div>";
        }else{
            while($row = mysqli_fetch_array($query)){
                $user_from = $row['user_from'];
                $id = $row['id'];
                $user_from_obj = new User($con,$user_from );
                $user_from_img = $user_from_obj->getProfilePic();
                $user_from_name = $user_from_obj->getFirstAndLastName();
                $user_from_friend_array = $user_from_obj->getFriendArray();

                echo "
                <div class='requests__wrap'>
                    <div class='requests__details'>
                        <a class='requests__img-box href= '$user_from'>
                        <img class='requests__userfrom-img' src='$user_from_img' alt='$'>
                        </a>
                        <div class='requests__action'>
                            <a href= '$user_from' class='requests__userfrom-name'>$user_from_name</a>

                            <form action='requests.php?request_id=$id' class='requests__form' method='post' id ='$id'>
                                <input type='submit' name='accept_request' value='Confirm' class='btn__accept'>
                                <input type='submit' name='decline_request' value='Remove' class='btn__decline'>
                            </form>                
                        </div>
                    </div>
                </div>";

            }

            // IF CONFIRM REQUEST IS PRESSED
            if(isset($_POST['accept_request'])){

                $request_id= $_GET['request_id'];

                // GET THE USER WHO SEND THE FRIEND REQUEST 
                $query_user_from = mysqli_query($con, "SELECT user_from FROM friend_requests WHERE id ='$request_id' ");

                $row = mysqli_fetch_array($query_user_from);
                $user_from = $row['user_from'];

                // GET THE FRIEND ARRAY OF USER LOGGED IN
                $user_logged_in = new User($con, $userLoggedIn);
                $user_logged_in->addFriend($user_from);

                // DELETE THE FRIEND REQUEST
                $query = mysqli_query($con, "DELETE FROM friend_requests WHERE id='$request_id'");

                header("Location: requests.php");

                echo"
                <script>
                    const request = document.getElementById('$request_id');
                    const html = '<p class= action-confirmed> Friend request accepted </p>';
                    request.insertAdjacentHTML('beforeend',html);
                </script>";
            }

            // IF REMOVE REQUEST IS PRESSED
            if(isset($_POST['decline_request'])){

                $request_id= $_GET['request_id'];

                // DELETE THE FRIEND REQUEST
                $query = mysqli_query($con, "DELETE FROM friend_requests WHERE id='$request_id'");

                header("Location: requests.php");

                echo"
                <script>
                    const request = document.getElementById('$request_id');
                    const html = '<p class= action-confirmed> Friend request declined </p>';
                    request.insertAdjacentHTML('beforeend',html);
                </script>";
            }
            
        }

        ?>
    </div>
</div>

