<!DOCTYPE html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <title><?php bloginfo('name'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />

    <?php wp_head(); ?>
</head>
<body>
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
        </div>
    </div>

    <div id="home-loop">
        <?php
            global $post;
            if(have_posts()) {
                while(have_posts()) {
                    the_post();

                    /* var_dump($post); */
                ?>
                <div class="post-title"><h2>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2></div>
                <div class="post-content"><?php the_content(); ?></div>
                <div>
                    <?php
                        the_category(',');
                        the_author();
                        the_time('Y-m-d H:i:s');
                        edit_post_link('Edit', 'B', 'A');
                    ?>
                </div>
                <?php
                }
            } else {
                echo '没有日志可以显示';
            }
        ?>
    </div>
    <!-- 分页 -->
    <div><?php posts_nav_link(); ?></div>



    <?php wp_footer(); ?>
</body>
</html>