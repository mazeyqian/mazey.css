<?php
require_once(dirname(__FILE__) . '/../../../../wp-load.php');
// Create post object
$my_post = array(
    'post_title'    => wp_strip_all_tags( $_POST['post_title'] ),
    'post_content'  => $_POST['post_content'],
    /* 'post_title'    => wp_strip_all_tags( 'post_title测试接收' ),
    'post_content'  => 'post_content测试接收', */
    'post_status'   => 'publish',
    'post_author'   => 1/* ,
    'post_category' => array( 8,39 ) */
);

// Insert the post into the database
echo wp_insert_post( $my_post );