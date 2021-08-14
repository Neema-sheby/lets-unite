
<?php
class User {
    private $user;
    private $con;

    public function __construct($con, $user){
        

        $this->con = $con;
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username = '$user'");
        $this->user = mysqli_fetch_array($user_details_query);
    }
    
    public function getFirstAndLastName(){     
        return $this->user['first_name']." ".$this->user['last_name'];
    } 

    public function getNumberOfFriendRequests(){

        $userLoggedIn = $this->user['username'];

        $query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_to='$userLoggedIn'");

        return mysqli_num_rows($query);

    }

    public function getNumPosts(){
        return $this->user['num_posts'];        
    }

    public function getUsername(){
        if(!$this->user) return;
        return $this->user['username'];
    }

    public function isClosed(){
        if($this->user['user_closed'] == 'true') return true;
        else return false;
    }

    public function getProfilePic(){
        return $this->user['profile_pic'];
    }

    public function getFriendArray(){
        return $this->user['friend_array'];
    }

    public function checkIfFriends($username_to_check){
        //if(!$this->user) return;
        $usernameComma = ','.$username_to_check.',';
        
        $friendList = $this->user['friend_array'];

        if((strstr($friendList, $usernameComma )) || $username_to_check == $this->user['username']){

         return true;
        }else {
            return false;
        }
    }
    public function receivedFriendRequest($user_from){
        $user_to = $this->user['username'];
        $check_request_query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_from = '$user_from'AND user_to = '$user_to'");

        if(mysqli_num_rows($check_request_query)>0) {return true;}
        else {return false;}
    }

    public function sendFriendRequest($user_to){
        $user_from = $this->user['username'];
        $check_request_query = mysqli_query($this->con, "SELECT * FROM friend_requests WHERE user_from = '$user_from'AND user_to = '$user_to'");

        if(mysqli_num_rows($check_request_query)>0) {return true;}
        else {return false;}
    }

    public function removeFriend($user_to_remove){

        $user_logged_in = $this->user['username'];

        // REMOVE FRIEND AND UPDATE THE USERLOGGED LIST OF FRIEND
      
        $query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username = '$user_to_remove'");

        $row_fr = mysqli_fetch_array($query);
        $friend_array_fr = $row_fr['friend_array'];

        $new_friend_array_fr = str_replace($user_logged_in.",", "", $friend_array_fr);

        $update_friend_array_fr = mysqli_query($this->con, "UPDATE users SET friend_array= '$new_friend_array_fr' WHERE username ='$user_to_remove' ") ;

        // REMOVE USERLOGGED AND UPDATE THE REMOVED FRIENDS LIST OF FRIENDS
        $new_friend_array_user = str_replace($user_to_remove.",", "", $this->user['friend_array']);

        $query = mysqli_query($this->con, "UPDATE users SET friend_array= '$new_friend_array_user' WHERE username ='$user_logged_in' ") ;  

    }
    public function addFriend($user_to_add){

        //ADD FRIEND AND UPDATE THE USERLOGGED LIST OF FRIEND
        $user_logged_in = $this->user['username'];
        $query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username = '$user_logged_in'");

        $row_fr = mysqli_fetch_array($query);
        $friend_array_user = $row_fr['friend_array'];

        $new_friend_array_user = $friend_array_user.$user_to_add.',';
        
        $query = mysqli_query($this->con, "UPDATE users SET friend_array = '$new_friend_array_user' WHERE username = '$user_logged_in'");

        // ADD USERLOGGED AND UPDATE THE NEW FRIENDS LIST OF FRIENDS
        $query =    mysqli_query($this->con, "SELECT friend_array FROM users WHERE username= '$user_to_add'");
        $row = mysqli_fetch_array($query);
        $friend_array_fr = $row['friend_array'];
        $new_friend_array_fr = $friend_array_fr.$user_logged_in.",";

        $query = mysqli_query($this->con, "UPDATE users SET friend_array='$new_friend_array_fr' WHERE username='$user_to_add'");

    }

    public function sendRequest($user_to){
        $user_from = $this->user['username'];
        $send_request = mysqli_query($this->con, "INSERT INTO friend_requests VALUES('','$user_to','$user_from')");
    }

    public function getCommonFriends($user_to_check){

        $common_friends = 0;
        $userLoggedIn_array = $this->user['friend_array'];
        // CREATES AN ARRAY OF FRIEND ARRAYS
        $userLoggedIn_array_explode = explode(",",$userLoggedIn_array);
        
        $query = mysqli_query($this->con, "SELECT friend_array FROM users WHERE username='$user_to_check'");

        $row = mysqli_fetch_array($query);
        
        $user_to_check_array = $row['friend_array'];

        $user_to_check_array_explode = explode(',',$user_to_check_array);

        foreach ($userLoggedIn_array_explode as $i){
            foreach ($user_to_check_array_explode as $j){
                if($i ==$j && $i != "" ){
                    $common_friends++;
                }
            }
        }
        if($common_friends== 0){
            return "No Common friend";
        }
        else if($common_friends== 1){
            return $common_friends." "."Common friend";
        }
        else{
            return $common_friends." "."Common friends";
        }

        
    }
    
}
?>