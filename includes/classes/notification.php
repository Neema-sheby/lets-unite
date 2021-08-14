<?php

class Notification {

    private $user_obj;
    private $con;

    public function __construct($con, $user){
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function getUnreadNumber(){

        $userLoggedIn = $this->user_obj->getUsername();
        
        $query = mysqli_query($this->con, "SELECT * FROM notifications WHERE  viewed = 'no' AND user_to ='$userLoggedIn'");
        return mysqli_num_rows($query);
    }

    public function insertNotification($post_id, $user_to, $type, $user_from){

        $userLoggedIn = $this->user_obj->getUsername();
        $userLoggedInName = $this->user_obj->getFirstAndLastName();

        $date_time = date("Y-m-d H:i:s");

        switch($type){
            case 'comment':
                $message = $userLoggedInName." "."commented on your post";
                break;
            case 'reply':
                $message = $userLoggedInName." "."commented on the post sent to you";
                break;
            case 'like':
                $message = $userLoggedInName." "."liked your post"; 
                break;  
            case 'profile_post':
                $message = $userLoggedInName." "."posted on your profile";
                break;
            case 'comment_non_owner':
                $message = $userLoggedInName." "."commented on a post you commented on";
                break;   
            case 'profile_comment':
                $message = $userLoggedInName." "."commented on your profile post";  
                break; 
        }

        $link = "post.php?id=".$post_id;

        if($user_from ==""){
            $insert_query = mysqli_query($this->con, "INSERT INTO notifications VALUES ('', '$user_to', '$userLoggedIn', '$message', '$link','$date_time','no','no')");

        }else{
            
            $insert_query = mysqli_query($this->con, "INSERT INTO notifications VALUES ('', '$user_to', '$user_from', '$message', '$link','$date_time','no','no')");

        }



    }

    public function loadNotifications($data, $limit){
        

        $page = $data['pageDropdown'];
        $userLoggedIn = $this->user_obj->getUsername();

        $str ="";

        if($page == 1){
            $start =0;
        }
        else{
            $start = ($page-1)*$limit;
        }

        $set_viewed_query = mysqli_query($this->con, "UPDATE notifications SET viewed='yes' WHERE user_to = '$userLoggedIn'");

        $query = mysqli_query($this->con, "SELECT * FROM notifications WHERE user_to = '$userLoggedIn' OR user_from ='$userLoggedIn' ORDER BY id DESC");


      if(mysqli_num_rows($query) == 0){
      echo "<p class ='noMoreDropdownText'>You have no notifications</p>" ;
      return;
      }
      
      $num_iterations = 0; //number of messages checked
      $count=1;// no of messages posted

      while ($row= mysqli_fetch_array($query)){

          $user_from = $row['user_from'];
          $date_time = $row['date_time'];
          $message = $row['message'];
          $link = $row['link'];
          $opened = $row['opened'];

          if($user_from != $userLoggedIn){

          $user_from_query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$user_from'");

          $user_from_data = mysqli_fetch_array($user_from_query);
          $user_from_img = $user_from_data['profile_pic'];

          //GET TIME FRAME
          $time_message = get_time_frame($date_time);

//////////////////////////////////////////////////////////
          if($num_iterations++ < $start) continue;
          if($count >$limit) break;
          else $count++;
//////////////////////////////////////////////////////////

          $style = (isset($row['opened'])) && ($row['opened']=="no")? "background-color:#DDEDFF": "";

          $str.= "
          <div class='notification' style='$style'>
            <a class='notification__link' href='$link'>
                <div class='notification__img-box'>
                   <img src='$user_from_img' alt='user' class='notification__img'> 
                </div>
                <div class='notification__text'>
                    <p class='notification__msg'> $message</p>
                    <span class='notification__time'>$time_message
                    </span>
                </div>
            </a>
          </div>
          ";  
          }
      }
      // if posts where loaded
      if($count > $limit){
          return $str.= "
          <input type='hidden' class='nextPageDropdownData' value='".($page + 1). "'>
          <input type='hidden' class='noMoreDropdownData' value='false'>
          ";
      }else{
        return $str.= "
        <input type='hidden' class='noMoreDropdownData' value='true'>
        <p class ='noMoreDropdownText'>No more notifications to load!</p>
        ";

      }
      return $str;
    }
}

?>