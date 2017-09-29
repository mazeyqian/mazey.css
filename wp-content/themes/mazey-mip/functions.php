<?php
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