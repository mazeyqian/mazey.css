                <div class="col-md-8">
                    <?php require_once(dirname(__FILE__) . '/part-404.php'); ?>
                    <div class="row">
                        <div class="col-md-12 post-may-like">
                            <span>你也许会喜欢</span>
                        </div>
                        <?php
                            /* 随机文章 */
                            $get_posts_arr = array(
                                'numberposts' => 3,
                                'orderby' => 'rand'
                            );
                            $posts_random = get_posts($get_posts_arr);
                            foreach($posts_random as $post):
                                setup_postdata( $post );
                        ?>
                        <div class="col-md-4">
                            <p>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            </p>
                            <p class="post-single-time">
                                <?php the_time('Y年m月d日');?>
                            </p>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>