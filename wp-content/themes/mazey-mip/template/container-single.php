                <div class="col-md-8">
                    <?php require_once(dirname(__FILE__) . '/part-post-content.php'); ?>
                    <div class="row">
                        <div class="col-md-12 post-may-like">
                            <span>你也许会喜欢</span>
                        </div>
                        <?php
                            $arr_the_category = get_the_category();
                            $category_list_arr = array();
                            foreach($arr_the_category as $value_the_category):
                                $category_list_arr[] = $value_the_category->term_id;
                            endforeach;
                            $category_list_str = implode(',', $category_list_arr);
                            $get_posts_arr = array(
                                'numberposts' => 3,
                                'category' => $category_list_str,
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
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>