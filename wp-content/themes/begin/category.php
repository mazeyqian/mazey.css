    <?php get_header(); ?>
    <div>
            分类：<?php single_cat_title(); ?>
    </div>

    <!-- 日志列表 -->
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


    <?php get_sidebar(); ?>
    <?php get_footer(); ?>