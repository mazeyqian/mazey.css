<!-- 评论模版 -->
<?php
    var_dump(comments_open());
    var_dump(is_user_logged_in());
    wp_list_comments();
    comment_form();
?>