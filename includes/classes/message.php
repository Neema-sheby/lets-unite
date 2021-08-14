<?php
Class Message{
    private $user_obj;
    private $con;

    public function __construct($con, $user){
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function getHostRecentUser(){
        
        $userLoggedIn = $this->user_obj->getUsername();
        // limit 1 MEAN GET ONE MESSAGE
        $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to ='$userLoggedIn' OR user_from = '$userLoggedIn' ORDER BY id DESC LIMIT 1");

        if(mysqli_num_rows($query)==0){
            return false;
        }
        $row = mysqli_fetch_array($query);
        $user_to = $row ['user_to'];
        $user_from =$row['user_from'];

        if($user_to != $userLoggedIn){
            return $user_to;
        }
        else {
            return $user_from;
        }
    }

    public function sendMessage($user_to, $msg, $date){
        
        if($msg != ''){
            $userLoggedIn = $this->user_obj->getUsername();

            $query = mysqli_query($this->con, "INSERT INTO messages VALUES ('','$user_to','$userLoggedIn','$msg','$date', 'no','no','no')");
        }
    }

    public function getMessage($otherUser){
        $userLoggedIn = $this->user_obj->getUsername();

        $str ="";
        
        $query = mysqli_query($this->con, "SELECT * FROM messages WHERE user_to= '$userLoggedIn' AND user_from='$otherUser' OR user_from = '$userLoggedIn'AND user_to='$otherUser'");

        while($row = mysqli_fetch_array($query)){

            $user_to = $row['user_to'];
            $user_from = $row['user_from'];
            $msg = $row['body'];

            $div = $user_to == $userLoggedIn? "
            <div class='message__results' id= 'blue'>":"<div class='message__results' id='green'>";

            $str = $str.$div.$msg.'</div>';
        }
        return $str;
    }

    public function getLatestMessage($userLoggedIn, $user){

        $details_array = [];
        
        $query =mysqli_query($this->con, "SELECT * FROM messages WHERE (user_to = '$userLoggedIn' AND user_from ='$user') OR (user_to = '$user' AND user_from ='$userLoggedIn') ORDER BY id DESC LIMIT 1");

        $row = mysqli_fetch_array($query);

        $user_from_obj = new User($this->con,$row['user_from']);
        $user_from_name = $user_from_obj->getFirstAndLastName();

        // SENT BY TEXT
        $sent_by = $row['user_to'] == $userLoggedIn? $user_from_name." ".'said :': 'You said:';
        // LATEST MESSAGE
        $latest_msg = $row['body'];

        //TIME MESSAGE from include('../get_time_frame.php') in header.php;
        $time_message =  get_time_frame($row['date']);

        array_push($details_array, $sent_by, $latest_msg, $time_message);

        return $details_array;
    }

    public function getAllConversations(){
        $userLoggedIn = $this->user_obj->getUsername();

        $str ="";
        $user_array = array();

        $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to = '$userLoggedIn' OR user_from ='$userLoggedIn' ORDER BY id DESC");
        while($row = mysqli_fetch_array($query)){
            $user_to_push = $row['user_to'] != $userLoggedIn? $row['user_to'] : $row['user_from'];


            if(!in_array($user_to_push, $user_array)){

                array_push($user_array, $user_to_push);   
            }

      }
      foreach ($user_array as $user){
          $user_obj = new User($this->con, $user);
          $username = $user_obj->getFirstAndLastName();
          $user_profile_pic = $user_obj->getProfilePic();

          $details_array = $this->getLatestMessage($userLoggedIn, $user);

          $sent_by = $details_array[0];
          $latest_message = $details_array[1];
          $time_message = $details_array[2];

          $dots = strlen($latest_message)>12? "...": "";
          $split_msg = str_split($latest_message, 12);

          $latest_message = $split_msg[0].$dots;

          $str.= "
          <a class='message__link' href='messages.php?u=$user'>
            <div class='message__conversation'>
                <div class='message__img-box'>
                    <img src='$user_profile_pic' alt='picture' class='message__user-img'>
                </div>
                <div class='message__user-details'>
                    <span class='message__user-name'>$username</span>
                    <div class='message__text'>$sent_by&nbsp$latest_message</div>
                    <div class='message__time'>$time_message</div>
                </div>
            </div>
          </a>
          ";  
      }
      return $str;
    }

    public function getConversationsDropdown($data,$limit){

        $page = $data['pageDropdown'];
        $userLoggedIn = $this->user_obj->getUsername();

        $str ="";
        $user_array = array();

        if($page == 1){
            $start =0;
        }
        else{
            $start = ($page-1)*$limit;
        }

        $conv_viewed_query = mysqli_query($this->con, "UPDATE messages SET viewed='yes' WHERE user_to = '$userLoggedIn'");

        $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to = '$userLoggedIn' OR user_from ='$userLoggedIn' ORDER BY id DESC");
        while($row = mysqli_fetch_array($query)){
            $user_to_push = $row['user_to'] != $userLoggedIn? $row['user_to'] : $row['user_from'];


            if(!in_array($user_to_push, $user_array)){

                array_push($user_array, $user_to_push);   
            }

      }
      $num_iterations = 0; //number of messages checked
      $count=1;// no of messages posted

      foreach ($user_array as $user){

          if($num_iterations++ < $start) continue;

          if($count >$limit) break;
          else $count++;

          $is_unread_query = mysqli_query($this->con, "SELECT opened FROM messages WHERE user_to='$userLoggedIn'AND user_from='$user' ORDER BY id DESC");

          $row = mysqli_fetch_array($is_unread_query);
          $style = (isset($row['opened'])) && ($row['opened']=="no")? "background-color:#DDEDFF": "";

          $user_obj = new User($this->con, $user);
          $username = $user_obj->getFirstAndLastName();
          $user_profile_pic = $user_obj->getProfilePic();

          $details_array = $this->getLatestMessage($userLoggedIn, $user);

          $sent_by = $details_array[0];
          $latest_message = $details_array[1];
          $time_message = $details_array[2];

          $dots = strlen($latest_message)>12? "...": "";
          $split_msg = str_split($latest_message, 12);

          $latest_message = $split_msg[0].$dots;

          $str.= "
          <a class='message__link' href='messages.php?u=$user'>
          <div class='message__conversation-dropDown' style='$style'>
              <div class='message__img-box'>
                  <img src='$user_profile_pic' alt='picture' class='message__user-img-dropdown'>
              </div>
              <div class='message__user-details'>
                  <span class='message__user-name'>$username</span>
                  <div class='message__text'>$sent_by&nbsp$latest_message</div>
                  <div class='message__time'>$time_message</div>
              </div>
          </div>
        </a>
          ";  
      }
      // if posts where loaded
      if($count > $limit){
          return $str.= "
          <input type='hidden' class='nextPageDropdownData' value='".($page + 1). "'>
          <input type='hidden' class='noMoreDropdownData' value='false'>
          <a href='messages.php?u=new' class='btn__submit-green-link-100 margin-btm-small'>Send new Message</a>
          ";
      }else{
        return $str.= "
        <input type='hidden' class='noMoreDropdownData' value='true'>
        <p class ='noMoreDropdownText'>No more messages to load!</p>
        <a href='messages.php?u=new' class='btn__submit-green-link-100  margin-btm-small'>Send new Message</a>
        ";
      }
      
      return $str;
    }


    public function getUnreadNumber(){

        $userLoggedIn = $this->user_obj->getUsername();
        
        $query = mysqli_query($this->con, "SELECT * FROM messages WHERE  viewed = 'no' AND user_to ='$userLoggedIn'");
        return mysqli_num_rows($query);
    }
}
?>

