<?php

include("../../config/config.php");
include("../classes/User.php");

$userLoggedIn = $_POST['user'];
$userLookingFor = $_POST['value'];

$names = explode(" ", $userLookingFor);

// if $userLookingFor contains an '_', user might be searching for a username;
if(strpos($userLookingFor, '_')){
    $get_users = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$userLookingFor%' AND user_closed='no'LIMIT 8");
}

//if $userLookingFor has two words, user might be searching for a firstname and a lastname.
elseif(count($names)==2) {
    $get_users = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
}

//if $userLookingFor has only one word, user might be looking for a firstname or lastname
else {
    $get_users = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");
}


if($userLookingFor != ""){

    while($row = mysqli_fetch_array($get_users)){

        $userSearching = $row['username'];
        $userSearching_pic = $row['profile_pic'];
        $userSearching_name = $row['first_name']." ".$row['last_name'];



        $user = new User($con, $userLoggedIn);

        // if the name found is not userloggedin
        if($userSearching != $userLoggedIn){

            $mutual_friends = $user->getCommonFriends($userSearching);
        }
        else{
            $mutual_friends = "";
        }

        echo "
          <a class='search__link' href='$userSearching'>
            <div class='search__img-box'>
                <img class='search__img' src='$userSearching_pic' alt='user'>
            </div>
            <div class='search__details'>
                    <p class='search__name'>$userSearching_name</p>
                    <p class='search__username'>$userSearching</p>
                    <p class='search__mutualFriends'>$mutual_friends</p>
            </div>
          </a>
        ";
    }
}

?>