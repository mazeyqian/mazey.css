<div class="col-md-4">
    <aside class="post-sidebar">
        <h3>文章搜索</h3>
        <div class="post-search">
            <mip-form method="get" url="<?php bloginfo('url'); ?>">
                <div class="form-group">
                    <label for="sidebar-search" class="sr-only">搜索</label>
                    <input type="text" name="s" class="form-control" id="sidebar-search" placeholder="请输入关键词">
                    <button type="submit" class="btn btn-default">搜索</button>
                </div>
            </mip-form>
        </div>
    </aside>
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
            <li><?php global $Object_Show;$Object_Show->print_post_title_link(); ?></li>
        <?php } ?>
        </ul>
    </aside>
    <?php
        if(is_dynamic_sidebar()) {
            dynamic_sidebar();
        } else {
            // do nothing
        }
    ?>
</div>