<!DOCTYPE html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <title><?php
        $title = '未知';
        if(is_home()) {
            $title = get_bloginfo('name');
        } else {
            $title = wp_title('', false) . ' - ' . get_bloginfo('name');
        }
        if($paged > 0 ) {
            $title .= " - 第{$paged}页";
        }
        echo $title;
    ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />

    <?php wp_head(); ?>
</head>
<body>
    <!-- 标题 -->
    <div id="header">
        <div id="headerimg">
            <h1>
                <a href="<?php echo get_option('home'); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>
            <div class="description">
                <?php bloginfo('description'); ?>
            </div>
            <?php
                $view = (get_option('view') == false ? 0 : get_option('view'));
                update_option('view', $view + 1);
            ?>
            <p>访问量：<?php echo $view; ?></p>
            <p><ul><?php wp_nav_menu(array('menu' => 'home')); ?></ul></p>
        </div>
    </div>