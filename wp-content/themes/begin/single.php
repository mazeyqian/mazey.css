<!-- single.php -->
    <?php get_header(); ?>
    <!-- 日志列表 -->
    <div id="home-loop">
        <?php
            the_post();
            $cat = get_the_category($post->ID); /* 分类信息 */
            $name = $cat[0]->slug;
            echo $name;
            get_template_part('container', $name);
        ?>
    </div>

    <?php get_sidebar(); ?>
    <?php get_footer(); ?>