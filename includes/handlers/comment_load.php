<!-- LOAD ALL THE COMMENTS -->

<?php

$load_comments = mysqli_query($con, "SELECT * FROM comments WHERE post_id ='$post_id' ORDER BY id DESC");

$num_row = mysqli_num_rows($load_comments);
$comment_box ="";
if($num_row >0){
    while($row = mysqli_fetch_array($load_comments)){
    $comment_body = $row['post_body'];
    $posted_by = $row['posted_by'];
    $posted_to = $row['posted_to'];
    $date_time = $row['date_added'];
    $removed = $row['removed'];
    $curr_id = $row['post_id'];
     
    $user_postedby = new User($con,$posted_by);
    $image_src =$user_postedby->getProfilePic() ;
    $name = $user_postedby->getFirstAndLastName();

    // GET THE TIME  
    $time_message = get_time_frame($date_time);
    
    $comment_box .= "
    <div class='postedby'>
      <a href='$posted_by' class ='postedby__img-box' target='_parent'>
        <img src='$image_src' alt='$name' class='postedby__img'>
      </a>
      <a href='$posted_by' class='postedby__name' target= '_parent'>$name</a>
      <p class='postedby__time'>$time_message</p>
      <p class='postedby__msg'>$comment_body</p>
    </div>";
    }
    echo $comment_box;
}
else{
  echo "<div class='success-comments'>No comments to show</div>";
}
?>

