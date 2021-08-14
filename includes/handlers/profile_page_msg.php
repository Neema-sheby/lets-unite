
    <div class="message__box">
        <?php
            echo "
            <h4 class='heading-2'>You and <a href='$username' class='user__name'>$user_to_name</a>
            </h4>";
            echo "<div class='message__all-chats' id='scroll_to_new_msg'>";
            echo $message_obj->getMessage($username);
            echo "</div>";
        ?>
        <div class="message__loaded">
            <form  action="" method="post" class="message__form">
            <?php
                echo "
                <div class='message__post-chat'>
                <textarea name='msg_body' class='textarea-80' placeholder='Write your message'></textarea>
                <input type='submit' name='msg_post' class='btn__submit-green-medium  message__submit' value='Send'>
                </div>";
            ?>
            </form>
        </div>
    </div>





