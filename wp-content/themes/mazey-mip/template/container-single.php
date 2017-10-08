                <div class="col-md-8">
                    <?php require_once(dirname(__FILE__) . '/part-post-content.php'); ?>
                    <div class="row">
                        <div class="col-md-12 post-may-like">
                            <span>你也许会喜欢</span>
                        </div>
                        <?php
                            /* 当前文章ID */
                            $listExclude = array();
                            /* DONE: Judge the exist of the id, it may return false. */
                            $thisID = get_the_ID();
                            if($thisID != false):
                                $listExclude[] = get_the_ID();
                            else:
                                $listExclude[] = '';
                            endif;
                            /* 当前分类 */
                            $arr_the_category = get_the_category();
                            $category_list_arr = array();
                            foreach($arr_the_category as $value_the_category):
                                $category_list_arr[] = $value_the_category->term_id;
                            endforeach;
                            $category_list_str = implode(',', $category_list_arr);
                            $get_posts_arr = array(
                                'numberposts' => 3,
                                'category' => $category_list_str,
                                'orderby' => 'rand',
                                'exclude' => $listExclude
                            );
                            $posts_random = get_posts($get_posts_arr);
                            foreach($posts_random as $post):
                                setup_postdata( $post );
                        ?>
                        <div class="col-md-4">
                            <p>
                                <?php global $Object_Show;$Object_Show->print_post_title_link(); ?>
                            </p>
                            <p class="post-single-time">
                                <?php the_time('Y年m月d日');?>
                            </p>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>