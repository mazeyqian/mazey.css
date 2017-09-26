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
<?php if(!is_home()){ ?>
    <ul>
        <li><a href="<?php bloginfo('url');?>">首页</a></li>
        <li><?php
            if (is_category()) {
                single_cat_title();
            } elseif (is_search()) {
                echo $s;
            } elseif (is_single()) {
                $cat = get_the_category();
                $cat = $cat[0];
                echo '<a href="' . get_category_link($cat) . '">' . $cat->name . '</a>';
            } elseif (is_page()) {
                the_title();
            } elseif (is_404()) {
                echo '404';
            } else {
                echo 'Unknown';
            }
        ?></li>
        <li><a href=""></a></li>
        <li><a href=""></a></li>
        <li><a href=""></a></li>
    </ul>
<?php } ?>