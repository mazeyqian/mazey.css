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
    
    <?php wp_footer(); ?>
</body>
</html>