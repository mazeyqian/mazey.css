<?php
    /* 注册一个小工具 */
    register_sidebar(
        array(
            'name' => '侧边栏',
            'before_widget' => '<div class="sbox">',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>'
        )
    );

    register_sidebar(
        array(
            'name' => '侧边栏1',
            'before_widget' => '<div class="sbox">',
            'after_widget' => '</div>',
            'before_title' => '<h2>',
            'after_title' => '</h2>'
        )
    );
?>