<?php
include("includes/header.php");

if(isset($_GET['q'])){
    $name_query = $_GET['q'];
}
else{
    $name_query="";
}

if(isset($_GET['type'])){
    $type = $_GET['q'];
}
else{
    $type="name";
}
?>
<div class='results'>
    <div class='results__display'>
        <?php
        if($name_query == ""){
            echo "<p class='alert-message'>You must enter a name in the search box!</p>";
        }
        else{
;
                // if $userLookingFor contains an '_', user might be searching for a username;
                if(strpos($name_query, '_') || $type == "username"){
                    $get_users = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$name_query%' AND user_closed='no'");
                }

                else{
                    $names = explode(" ", $name_query);

                    //if $userLookingFor has three words, user might be searching for a firstname, middle name and a lastname.
                    if(count($names)==3 && $type == "name") {
                        $get_users = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[2]%') AND user_closed='no'");
                    }

                    //if $userLookingFor has two words, user might be searching for a firstname and a lastname.
                    elseif(count($names)==2 && $type == "name") {
                        $get_users = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no'");
                    }
                    //if $userLookingFor has only one word, user might be looking for a firstname or lastname
                    else {
                        $get_users = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no'");
                    }
                }
                // check if results where found
                if(mysqli_num_rows($get_users)== 0){
                    echo "<p class='alert-message'>No user found with a"." ".$type." "."like"." ". $name_query ."!</p>";
                }
                else{
                    $num_users_found = mysqli_num_rows($get_users);

                    echo "<p class='results__num-users'>".$num_users_found." "."results found</p>";

                    echo "<p class='results__text'>Try searching for:</p>";
                    echo "
                    <a href='search.php?q=".$name_query."&type=name"." ' class='btn__submit-green-link-15'>Names</a>
                    <a href='search.php?q=".$name_query."&type=username"." ' class='btn__submit-green-link-15'>Usernames</a>
                    ";
                    
                    while($row=mysqli_fetch_array($get_users)){

                        $result_username = $row['username'];
                        $result_pic = $row['profile_pic'];
                        $result_name = $row['first_name'].' '.$row['last_name'];

                        $user_obj = new User($con, $userLoggedIn);
                        $button='';
                        $mutual_friends='';

                       if($userLoggedIn != $result_username){

                        if($user_obj->checkIfFriends($result_username)){
                            $button = "<input type='submit' name='".$result_username."_remove_friend' class='btn__submit-alert-medium' value='Remove friend'>";
                        }
                        elseif($user_obj->receivedFriendRequest($result_username)){
                            $button = "<a  href='requests.php' class='btn__submit-yellow-link-20-lg'>Respond to Request</a>";
                        }
                        elseif($user_obj->sendFriendRequest($result_username)){
                            $button = "<input type='button' name='".$result_username." 'class='btn__submit-warning-medium' value='Request Sent' >";
                        }
                        else{
                            $button = "<input type='submit' name='".$result_username."_add_friend' class='btn__submit-green-medium' value='Add Friend' >";
                        }
                        
                        $mutual_friends = $user_obj->getCommonFriends($result_username);

                        $remove_btn = $result_username."_remove_friend";
                        $add_btn = $result_username."_add_friend";

                        //IF REMOVE BUTTON IS CLICKED
                        if(isset($_POST[$remove_btn])){
                            $user_obj->removeFriend($result_username);
                            //COMES BACK TO THE SAME PAGE
                            header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                        }

                        if(isset($_POST[$add_btn])){
                            $user_obj->sendRequest($result_username);
                            header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                        }

                       }

                       echo "
                       <div class = results__box>
                            <a class='results__link' href='$result_username'>
                            <div class='results__img-box'>
                                <img class='results__img' src='$result_pic' alt='user'>
                            </div>
                            <div class='results__details'>
                                    <p class='results__name'>$result_name</p>
                                    <p class='results__username'>$result_username</p>
                                    <p class='results__mutualFriends'>$mutual_friends</p>
                            </div>
                            </a>
                            <form action='' method='POST' class='results__form'>$button</form>
                       </div>
                       
                       ";
                    }
                }
        }
        ?>
    </div>
</div>