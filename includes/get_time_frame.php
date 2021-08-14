<?php
function get_time_frame($date_time){
    //Time frame
$date_time_now = date('Y-m-d H:i:s');
$start_date = new DateTime($date_time);// time of post
$end_date = new DateTime($date_time_now);// current time
$interval = $start_date ->diff($end_date);

if($interval ->y >=1){
    if($interval -> y==1){
        return $time_message = $interval->y."year ago";
    }
    else{
        return $time_message = $interval->y."years ago";
    }

}
else if($interval ->m >=1){
    if($interval->d == 0){
        $days = " ago";
    }
    else if($interval->d ==1){
        $days = $interval->d." day ago";
    }
    else if($interval->d >1){
        $days = $interval->d." days ago";
    }

    if($interval->m ==1){
        return $time_message = $interval->m." month".$days;
    }
    else{
        return $time_message = $interval->m." months".$days;
    }

}
else if($interval->d >= 1){
    if($interval->d == 1){
        return $time_message = "yesterday";
    }
    else if($interval->d >1){
        return $time_message = $interval->d." days ago";
    }
}
else if($interval->h >=1){
    if($interval->h == 1){
        return $time_message = $interval->h." hour ago";
    }
    else{
        return $time_message = $interval->h." hours ago";
    }

}
else if($interval->i >=1){
    if($interval->i == 1){
        return $time_message = $interval->i." minute ago";
    }
    else{
        return  $time_message = $interval->i." minutes ago";
    }
}

else{
    if($interval->s <30){
        return $time_message = " Just now";
    }
    else{
        return $time_message = $interval->s." seconds ago";
    }
}

}

?>