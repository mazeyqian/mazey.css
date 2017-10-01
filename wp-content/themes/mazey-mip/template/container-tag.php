                <div class="col-md-8">
                    <div class="post-category-header">
                        <!--标签标题 -->
                        <h3>标签：<?php single_tag_title(); ?></h3>
                    </div>
                    <?php require_once(dirname(__FILE__) . '/part-post-content.php'); ?>
                    <?php require_once(dirname(__FILE__) . '/part-total-pagination.php'); ?>
                </div>