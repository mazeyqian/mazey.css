                <div class="col-md-8">
                    <div class="post-category-header">
                        <!--分类标题 -->
                        <h3>分类：<?php single_cat_title(); ?></h3>
                    </div>
                    <?php require_once(dirname(__FILE__) . '/part-post-content.php'); ?>
                    <?php require_once(dirname(__FILE__) . '/part-total-pagination.php'); ?>
                </div>