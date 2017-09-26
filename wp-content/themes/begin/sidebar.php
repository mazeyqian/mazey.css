<!-- sidebar.php -->
<div>
<?php
    if(is_dynamic_sidebar()) {
        dynamic_sidebar();
    } else {
        wp_list_cats();
        wp_list_pages();
        get_links();
        wp_register();
        wp_loginout();
    }
?>
</div>
<div>
    <h3>随机文章</h3>
    <div>
        <?php
            $args = array(
                'numberposts' => 5,
                'category' => '3',
                'orderby' => 'rand'
            );
            $rand_posts = get_posts($args);
        ?>
        <ul>
        <?php foreach($rand_posts as $post) {
            setup_postdata( $post );
            ?>
        	<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php } ?>
        </ul>
    </div>
</div>