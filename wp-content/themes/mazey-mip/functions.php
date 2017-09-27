<?php
require_once(dirname(__FILE__) . '/class/class-load.php');
/* 注册所要用到的类 */
$Object_MIP = new Class_MIP();
$Object_Show = new Class_Show();
/* 注册一个小工具 */
register_sidebar(
    array(
        'name' => '侧边栏',
        'before_widget' => '<aside class="post-sidebar">',
        'after_widget' => '</aside>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    )
);