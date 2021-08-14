
<?php

class Post {
    private $new_user;
    private $con;

    public function __construct($con, $user){
        $this->con = $con;
        $this->new_user = new User($con, $user);
    }

    public function submitPost($body,$user_to,$image_name){
        $video="";
        $body = strip_tags($body);
        $body = mysqli_real_escape_string($this->con, $body);
        $body = str_replace('\r\n',"\n",$body);
        $body = nl2br($body); // converts new lines to breaks
        $check_empty = preg_replace('/\s+/','',$body); //delete all spaces

        if($check_empty !="" || $image_name ){
            // "/\s+/" -  combines all-white spaces as a delimiter

            $array = explode(" ", $body);
            foreach($array as $key => $value){
            if(strpos($value, "https://www.youtube.com/watch?v=")!== false){
              $link = explode("&",$body);
              $value = preg_replace("!watch\?v=!", "embed/",$link[0]);
              $value = "
              <iframe width='853' height='480' src=".$value." title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen ></iframe>";
              
              $array[$key] = $value;
              }
            }
            
            $body = implode(" ",$array);
            
            // IF VIDEO DECODE 
            if(strpos($body, "iframe") !== false){
                $video = base64_encode($body);
                $body ="";
                }

            $date_added = date("Y-m-d H:i:s");
            //get username
            $added_by = $this->new_user->getUsername()
            ;

            //if user is not on own profile, user_to is 'none'
            if($user_to== $added_by){
                $user_to = "none";
            }

            $query = mysqli_query($this->con,"INSERT INTO post VALUES ('','$body','$added_by','$user_to', '$date_added', 'no', 'no','0','$video', '$image_name')");

            $returned_id = mysqli_insert_id($this->con);// returns id from the last query
            
            //Insert notification
            if($user_to != "none")
            {
                $notification = new Notification($this->con, $added_by);
                $notification->insertNotification($returned_id, $user_to,'profile_post');
            }            

            //Update post count for user
            $num_posts = $this->new_user->getNumPosts();
            $num_posts++;

            $update_query = mysqli_query($this->con, "UPDATE users SET num_posts = '$num_posts' WHERE username ='$added_by' "); 

            // CALCULATE TRENDING WORDS IN POSTS
            
			$stop_words = "a’s able about above according accordingly across actually after afterwards again against ain’t all allow allows almost alone along already also although always am among amongst an and another any anybody anyhow anyone anything anyway anyways anywhere apart appear appreciate appropriate are aren’t around as aside ask asking associated at available away awfully be became because become becomes becoming been before beforehand behind being believe below beside besides best better between beyond both brief but by c’mon c’s came can can’t cannot cant cause causes certain certainly changes clearly co com come comes concerning consequently consider considering contain containing contains corresponding could couldn’t course currently definitely described despite did didn’t different do does doesn’t doing don’t done down downwards during each edu eg eight either else elsewhere enough entirely especially et etc even ever every everybody everyone everything everywhere ex exactly example except far few fifth first five followed following follows for former formerly forth four from further furthermore get gets getting given gives go goes going gone got gotten greetings had hadn’t happens hardly has hasn’t have haven’t having he he’s hello help hence her here here’s hereafter hereby herein hereupon hers herself hi him himself his hither hopefully how howbeit however i’d i’ll i’m i’ve ie if ignored immediate in inasmuch inc indeed indicate indicated indicates inner insofar instead into inward is isn’t it it’d it’ll it’s its itself just keep keeps kept know knows known last lately later latter latterly least less lest let let’s like liked likely little look looking looks ltd mainly many may maybe me mean meanwhile merely might more moreover most mostly much must my myself name namely nd near nearly necessary need needs neither never nevertheless new next nine no nobody non none noone nor normally not nothing novel now nowhere obviously of off often oh ok okay old on once one ones only onto or other others otherwise ought our ours ourselves out outside over overall own particular particularly per perhaps placed please plus possible presumably probably provides que quite qv rather rd re really reasonably regarding regardless regards relatively respectively right said same saw say saying says second secondly see seeing seem seemed seeming seems seen self selves sensible sent serious seriously seven several shall she should shouldn’t since six so some somebody somehow someone something sometime sometimes somewhat somewhere soon sorry specified specify specifying still sub such sup sure t’s take taken tell tends th than thank thanks thanx that that’s thats the their theirs them themselves then thence there there’s thereafter thereby therefore therein theres thereupon these they they’d they’ll they’re they’ve think third this thorough thoroughly those though three through throughout thru thus to together too took toward towards tried tries truly try trying twice two un under unfortunately unless unlikely until unto up upon us use used useful uses using usually value various very via viz vs want wants was wasn’t way we we’d we’ll we’re we’ve welcome well went were weren’t what what’s whatever when whence whenever where where’s whereafter whereas whereby wherein whereupon wherever whether which while whither who who’s whoever whole whom whose why will willing wish with within without won’t wonder would would wouldn’t yes yet you you’d you’ll you’re you’ve your yours yourself yourselves zero";

            $stop_words = preg_split('/[\s,]+/',$stop_words);
            
            //replace anything that is not a letter or number with "";
            $no_punctuation = preg_replace('/[^a-zA-Z 0-9]+/',"",$body);

            if(strpos($no_punctuation, "height")=== false && strpos($no_punctuation, "width")=== false){
                    $no_punctuation = preg_split('/[\s,]+/', $no_punctuation);
                    
                    foreach($stop_words as $value1){
                      foreach($no_punctuation as $key => $value2){
                        if(strtolower($value1) == strtolower($value2)){
                          $no_punctution[$key] = "";
                        }
                    }
                } 
                
                foreach($no_punctuation as $value){
                              $this->calculateTrend(ucfirst($value));
                            }
            } 
        }
    }

    public function calculateTrend($term){
        if($term != ""){
            $query = mysqli_query($this->con,"SELECT * FROM trends WHERE title='$term'");
            if(mysqli_num_rows($query) == 0){
                $insert_query= mysqli_query($this->con, "INSERT INTO trends VALUES ('$term',1)");
            }
            else{
                $insert_query= mysqli_query($this->con, "UPDATE trends SET hits = hits + 1 WHERE title='$term' ");
            }
        }

    }
    
    public function loadPostsFriends($data, $limit){

        $profileUsername = $data['profileUsername'];

       // echo "<script> console.log('. json_encode( $profileUsername ).')</script>";   

        $userLoggedIn= $this->new_user ->getUsername();
        
        // IF USER IN THE PROFILE PAGE IS A FRIEND OF USERLOGGEDIN, LOAD THE POSTS OF THE USER IN THE PROFILE PAGE
        if($profileUsername && $this->new_user->checkIfFriends($profileUsername)){
           // echo "<script> console.log('. json_encode( $profileUsername ).')</script>";
            $this->load($profileUsername, $data, $limit);
        }

        // IF USER IN THE PROFILE PAGE IS THE USERLOGGEDIN ITSELF, LOAD THE POSTS OF THE USER IN THE PROFILE PAGE. ALSO TO LOAD POSTS IN MAIN PAGE.
        if(!$profileUsername || $profileUsername == $userLoggedIn ){
            //echo "<script> console.log('. json_encode( $userLoggedIn ).')</script>";
            $this->load($userLoggedIn, $data, $limit);
        }
    }

    // FUNCTION TO LOAD POST
    function load($user, $data, $limit ) {

    $page = $data['page'];

        $user_to_html="";

        if($page ==1){
            $start = 0;
        }else{
            $start = ($page-1)*$limit;
        }

        $str = ""; //string to return

        $data_query = mysqli_query($this->con, "SELECT * FROM post WHERE deleted = 'no' ORDER BY id DESC");// select all the posts that are not deleted and order by id in descending order

        if(mysqli_num_rows($data_query) > 0){

        $num_iterations = 0; //number of results checked not necessarily posted
        $count = 1;

          
        while($row = mysqli_fetch_array($data_query)){
            $id = $row['id'];
            $body = $row['body'];
            $added_by = $row['added_by'];
            $date_time = $row['date_added'];
            $video = base64_decode($row['video']);
            $imagePath = $row['image'];
            
            //check if the post is posted by himself or a friend

            if($row['user_to'] == 'none'){ 
                $user_to = '' ;
                $user_to_html="";
            }
            else{
                $user_to = $row['user_to'];
                $user_to_obj = new User($this->con, $user_to);
                $user_to_name = $user_to_obj-> getFirstAndLastName();
                $user_to_pic = $user_to_obj-> getProfilePic();

                $user_to_html = " 
                <span class= 'user__to'> to </span>
                <div class= 'user__profile-pic' >
                  <img src='$user_to_pic' class= 'user__profile-pic--1'>
                </div>
                <div class= 'user__posted-by'>
                  <a href='$user_to' class='user__link'>".$user_to_name."</a>
                </div>";
            }

            // check if account of the user the post is added by is closed.

            $added_by_obj = new User($this->con,$added_by );

            if($added_by_obj->isClosed()){
                continue;
            }

            // checking if the post is added by one of the friend in his friend list or himself.(the user can see only his or his friends posts )
            $userLoggedInObj = new User($this->con,$user);
           
            if( $userLoggedInObj->checkIfFriends($added_by)){

                    if($num_iterations++ < $start){
                        continue;
                    }

                    //Once 5 posts has been loaded, break
                    if($count > $limit){
                        break;
                    }
                    else {
                        $count++;
                    }


                    $user_details_query = mysqli_query($this->con,"SELECT first_name, last_name, profile_pic FROM users WHERE username = '$added_by'" );

                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];
                    ?>

                    <?php
                    // GET THE TIME  
                    $time_message = get_time_frame($date_time);

                    //GET NUMBER OF COMMENTS
                    $num_comments_query = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id ='$id'");
                    $num_comments = mysqli_num_rows($num_comments_query);
            
            // POST DELETE BUTTON
            if($user == $added_by){

                $delete_btn = "
                <button class='delete-btn'>
                   <svg class='icon-x-small'>
                      <use xlink:href='assets/images/sprite.svg#icon-bin'>
                      </use>
                   </svg>
                </button>
                <input type='hidden' name='bootbox-result-1' id='bootbox-result-1'>";
            }
            else{ $delete_btn = "";};

            if($imagePath !=""){
                $imageDiv = "
                <a href='#popup' class='user__img-postbox'>
                 <img src='$imagePath' alt='image-post' class='user__img-post' >
                </a>";
            }
            else{
                $imageDiv = "";
            }

            //($str.) === ($str + $str.)            
            $str .= "
                    
                    <div class='user__post-container' id= '$id'>
                        <div class='user__post-details'>
                           <div class='user__added-by'>
                                <div class= 'user__profile-pic'>
                                    <img src='$profile_pic' class= 'user__profile-pic--1'>
                                </div>
                                <a href= '$added_by' class='user__link'>$added_by&nbsp;</a> 
                            </div>
                            <div class='user__user-to'>$user_to_html</div> 
                            <span  class='user__time-posted'>
                                $time_message
                            </span>
                            $delete_btn
                        </div>
                        <div class='user__messages' id = 'post_body'>$body</div>
                        $imageDiv
                        <div class='user__messages' id = 'post_body'>$video</div>
                        <div class='user__interface-btns'>
                            <div class='user__comment-btn'>
                                <input type='submit' class='btn-text post-comment' name='post_id' value='Comments'>
                                <span class='num-comment'>($num_comments)</span>  
                            </div>
                            <div class='like-btn'>
                                <iframe src ='likes_frame.php?post_id=$id' id='like_iframe' frameborder= '0' allowtransparency = 'true' scrolling='no'></iframe>
                            </div>  
                         </div>
                        <div class='comment__load hidden'>
                            <iframe src ='comment_frame.php?post_id=$id' id='comment_iframe' frameborder= '0' allowtransparency = 'true'></iframe>
                       </div>
                    </div>";

                } 
        }
         if($count >$limit){
             $str.= "<input type ='hidden' class ='nextPage' value='".($page + 1). "'>"; 
             $str.= "<input type ='hidden' class ='noMorePosts' value='false'>"; 
         }
         else{
            $str.= "<input type ='hidden' class ='noMorePosts' value='true'>
            <p class ='noMorePostsText' style='margin-left:0rem;'>No more posts to show!</p>"; 
         }
       }
    echo $str;
  }
  
  // WHEN NOTIFICATION FROM BELL ICON IS CLICKED
  public function getSinglePost($post_id){

    $userLoggedIn_obj= $this->new_user;
    $userLoggedIn = $userLoggedIn_obj->getUsername();
    
    $str = ""; //string to return
    
    $opened_query = mysqli_query($this->con, "UPDATE notifications SET opened='yes' WHERE user_to = '$userLoggedIn' AND link LIKE '%=$post_id' ");

    $data_query = mysqli_query($this->con, "SELECT * FROM post WHERE deleted='no' AND id = '$post_id'");// select all the posts that are not deleted and order by id in descending order
    // echo '<script>';
    // echo 'console.log('. json_encode(mysqli_num_rows($data_query)) .')';
    // echo '</script>';
    
    if(mysqli_num_rows($data_query) > 0){
        $row = mysqli_fetch_array($data_query);

        $id = $row['id'];
        $body = $row['body'];
        $added_by = $row['added_by'];
        $date_time = $row['date_added'];        

        //check if the post is posted by himself or a friend
        if($row['user_to'] == 'none'){ 
            $user_to = '' ;
            $user_to_html = " ";
        }
        else{
            $user_to = $row['user_to'];
            $user_to_obj = new User($this->con, $user_to);
            $user_to_name = $user_to_obj-> getFirstAndLastName();
            $user_to_pic = $user_to_obj-> getProfilePic();

            $user_to_html = " 
            <span class= 'user__to'> to </span>
            <div class= 'user__profile-pic' >
              <img src='$user_to_pic' class= 'user__profile-pic--1'>
            </div>
            <div class= 'user__posted-by'>
              <a href='$user_to' class='user__link'>".$user_to_name."</a>
            </div>";
        }

        // check if account of the user the post is added by is closed.

        $added_by_obj = new User($this->con,$added_by );

        if($added_by_obj->isClosed()){
            return;
        }

        // checking if the post is added by one of the friend in his friend list or himself.(the user can see only his or his friends posts )

        if( $userLoggedIn_obj->checkIfFriends($added_by)){


                $user_details_query = mysqli_query($this->con,"SELECT first_name, last_name, profile_pic FROM users WHERE username = '$added_by'" );

                $user_row = mysqli_fetch_array($user_details_query);
                $first_name = $user_row['first_name'];
                $last_name = $user_row['last_name'];
                $profile_pic = $user_row['profile_pic'];
                ?>

                <?php
                // GET THE TIME  
                $time_message = get_time_frame($date_time);

                //GET NUMBER OF COMMENTS
                $num_comments_query = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id ='$id'");
                $num_comments = mysqli_num_rows($num_comments_query);
        
        // POST DELETE BUTTON
        if($userLoggedIn == $added_by){

            $delete_btn = "
            <button class='delete-btn'>
               <svg class='icon-x-small'>
                  <use xlink:href='assets/images/sprite.svg#icon-bin'>
                  </use>
               </svg>
            </button>
            <input type='hidden' name='bootbox-result-1' id='bootbox-result-1'>";
        }
        else{ $delete_btn = "";};

        //($str.) === ($str + $str.)            
        $str .= "
                <div class='user__post-container' id= '$id'>
                    <div class='user__post-details'>
                       <div class='user__added-by'>
                            <div class= 'user__profile-pic'>
                                <img src='$profile_pic' class= 'user__profile-pic--1'>
                            </div>
                            <a href= '$added_by' class='user__link'>$added_by&nbsp;</a> 
                        </div>
                        <div class='user__user-to'>$user_to_html</div> 
                        <span  class='user__time-posted'>
                            $time_message
                        </span>
                        $delete_btn
                    </div>
                    <div class='user__messages' id = 'post_body'>$body</div>
                    
                    <div class='user__interface-btns'>
                        <div class='user__comment-btn'>
                            <input type='submit' class='btn-text post-comment' name='post_id' value='Comments'>
                            <span class='num-comment'>($num_comments)</span>  
                        </div>
                        <div class='like-btn'>
                            <iframe src ='likes_frame.php?post_id=$id' id='like_iframe' frameborder= '0' allowtransparency = 'true' scrolling='no'></iframe>
                        </div>  
                     </div>
                    <div class='comment__load hidden'>
                        <iframe src ='comment_frame.php?post_id=$id' id='comment_iframe' frameborder= '0' allowtransparency = 'true'></iframe>
                   </div>
                </div>";
        }
        else{
            echo "<p class='alert-message'>You cannot see this post as you are not friends with this user.</p>";
            return;
        }
   }else{
    echo "<p class='alert-message'>No post found. If you clicked a link, it may be broken</p>";
    return;
   }
echo $str;

  }

}
?>

