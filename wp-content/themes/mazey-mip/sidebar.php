<div class="col-md-4">
    <?php
        if(is_dynamic_sidebar()) {
            dynamic_sidebar();
        } else {
            // do nothing
        }
    ?>
    <aside class="post-sidebar">
        <h3>随机文章</h3>
        <?php
            $arr_random_post_para = array('numberposts' => 5, 'orderby' => 'rand');
            $arr_random_post_list = get_posts($arr_random_post_para);
        ?>
        <ul>
        <?php
            foreach($arr_random_post_list as $post) {
                setup_postdata($post);
        ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php } ?>
        </ul>
    </aside>
    <aside class="post-sidebar">
        <h3>文章搜索</h3>
        <div>
            <mip-form method="get" action="<?php bloginfo('url'); ?>" class="">
                <input type="search" name="s">
                <button type="submit">搜索</button>
            </mip-form>
        </div>
    </aside>
</div>