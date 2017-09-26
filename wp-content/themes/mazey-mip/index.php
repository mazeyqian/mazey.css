<!DOCTYPE html>
<html mip>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
        <link rel="stylesheet" type="text/css" href="https://mipcache.bdstatic.com/static/v1/mip.css">
        <title><?php echo get_bloginfo('description') . ' - ' . get_bloginfo('name'); ?></title>
        <style mip-custom>
            <?php require_once(dirname(__FILE__) . '/css/main.css.php'); ?>
        </style>
    </head>
    <body>
        <header role="banner">
            <!--导航-->
            <nav class="navbar navbar-static-top navbar-default">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="<?php bloginfo('url'); ?>"><mip-img src="<?php bloginfo('template_url'); ?>/img/logo.png" alt="治白发网" ></a>
                    </div>
                    <ul class="nav navbar-nav hidden-xs">
                        <li class="active"><a href="<?php bloginfo('url'); ?>">首页</a></li>
                        <li class=""><a href="#">关于我们</a></li>
                        <li class=""><a href="#">联系我们</a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <?php
                        if(have_posts()) {
                            while(have_posts()) {
                                the_post();
                    ?>
                    <div>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php the_content(); ?></p>
                    </div>
                    <?php
                            }
                        } else {
                            echo "There is no post to show!";
                        }
                    ?>
                    <div>
                        <p><?php posts_nav_link(); ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <h2 class="text-center">侧边栏</h2>
                </div>
            </div>
        </div>
        <script src="https://mipcache.bdstatic.com/static/v1/mip.js"></script>
    </body>
</html>