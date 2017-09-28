                <div class="col-md-8">
                    <?php
                        if(have_posts()) {
                            while(have_posts()) {
                                the_post();
                    ?>
                    <article class="post-index">
                        <header>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        </header>
                        <div>
                            <p><?php the_content(); ?></p>
                        </div>
                        <footer>
                        </footer>
                    </article>
                    <?php
                            }
                        } else {
                            echo "There is no post to show!";
                        }
                    ?>
                    <div class="post-pagination text-center">
                        <?php the_posts_pagination(array(
                            'prev_text' => '上一页',
                            'next_text' => '下一页'
                        )); ?>
                    </div>
                </div>