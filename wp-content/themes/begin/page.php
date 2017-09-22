    <!-- page.php -->
    <?php get_header(); ?>
    <!-- 日志列表 -->
    <div id="home-loop">
        <?php
            the_post();
            get_template_part('container', 'page');
        ?>
    </div>

    <?php get_sidebar(); ?>
    <?php get_footer(); ?>