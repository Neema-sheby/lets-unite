<!-- FRIEND SEARCH FOR SENDING MESSAGE -->

<?php
include("../../config/config.php");
include("../classes/User.php");

$new_name = [];

$value =$_POST['value'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(' ', $value);


if(strpos($value,'_') !== false){
    $query = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$value' AND user_closed='no' LIMIT 8");
}

elseif(count($names)==2){
    $query = mysqli_query($con, "SELECT * FROM users WHERE (first_name  LIKE '$names[0]%' AND last_name  LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
}

else{
    $query = mysqli_query($con, "SELECT * FROM users WHERE (first_name  LIKE '$names[0]%' OR last_name  LIKE '$names[0]%') AND user_closed='no' LIMIT 8");
}

if($value != ''){

    while($row = mysqli_fetch_array($query)){

        $user = new User($con, $userLoggedIn);

        if($row['username'] != $userLoggedIn){

            $mutual_friends = $user->getCommonFriends($row['username']);
        }
        else{
            $mutual_friends = '';
        }

        if($row['username'] != $userLoggedIn){

            if($user->checkIfFriends($row['username'])){
                echo "
                <div class='message__display-result'>
                    <a href='messages.php?u=".$row['username']."' class='message__link flex-row-center'>
                        <div class='message__livesearch-profile-pic-box'>
                            <img src='".$row['profile_pic']."'alt='Friend' class='message__livesearch-profile-img'>                 
                        </div>
                        <div class='message__livesearch-text'><b>".$row['first_name']." ".$row['last_name']."</b>
                            <p class='message__livesearch-username'>".
                            $row['username']."
                            </p>
                            <p class='message__livesearch-mutual-friend'>".
                            $mutual_friends."
                            </p>
                        </div>
                    </a>
                </div>
                ";
            }
        }
    }
}

?>
