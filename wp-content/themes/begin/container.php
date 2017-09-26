                <div class="post-title"><h2>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2></div>
                <div>
                    <?php
                        the_category(',');
                        the_author();
                        the_time('Y-m-d H:i:s');
                        edit_post_link('Edit', 'B', 'A');
                        echo get_post_meta($post->ID, '_download', true);
                    ?>
                </div>
                <div class="post-content"><?php the_content(); ?></div>
                <ul>
                	<li><?php previous_post_link('自定义文本%link'); ?></li>
                	<li><?php next_post_link('下一篇: %link'); ?></li>
                </ul>