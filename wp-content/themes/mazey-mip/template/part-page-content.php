                    <?php
                        if(have_posts()) {
                            while(have_posts()) {
                                the_post();
                    ?>
                    <article class="post-index">
                        <header>
                            <h2><?php global $Object_Show;$Object_Show->print_post_title_link(); ?></h2>
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