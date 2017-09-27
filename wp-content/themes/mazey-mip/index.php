<!DOCTYPE html>
<html mip>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
        <link rel="stylesheet" type="text/css" href="https://mipcache.bdstatic.com/static/v1/mip.css">
        <link rel="canonical" href="<?php $Object_MIP->print_current_url(); ?>">
        <title><?php $Object_Show->print_page_title(); ?></title>
        <style mip-custom>
            <?php require_once(dirname(__FILE__) . '/css/main.css.php'); ?>
        </style>
    </head>
    <body>
        <!--div>页码：<?php echo $Object_Show->current_page; ?></div-->
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
                <?php get_template_part('container'); ?>
                <?php get_sidebar(); ?>
            </div>
        </div>
        <script src="https://mipcache.bdstatic.com/static/v1/mip.js"></script>
    </body>
</html>