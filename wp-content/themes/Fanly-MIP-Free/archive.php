<?php defined( 'ABSPATH' )  or exit; get_header(); ?>
<div id="container">
	<div id="posts">
		<div class="page_title">正在显示 [ <?php single_cat_title(); ?> ] 分类下的文章</div>
		<div id="posts-list">
		<?php $i = 1;  if (have_posts()) :  while (have_posts()) : the_post();  if(($i == 1)) : ?>
			<div class="top-entry">
				<div class="top-entry-img-wrapper">
					<mip-link class="top-entry-img" href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
						<h2 class="entry-title"><?php the_title(); ?></h2>
					</mip-link>
				</div>
				<mip-link class="top-entry-title" href="<?php the_permalink() ?>"><h2><?php the_title(); ?></h2></mip-link>
				<div class="top-entry-content">
					<?php custom_excerpt(100); ?>
				</div>
			</div>
			<div class="clear"></div>
		<?php else: ?>
			<div class="entry">
				<div class="entry-thumb">
					<mip-link href="<?php the_permalink() ?>"><mip-img class="mip-img" alt="<?php the_title(); ?>" src="<?php post_thumbnail(); ?>"></mip-img></mip-link>
				</div>
				<div class="entry-body">
					<h2 class="entry-title"><mip-link href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></mip-link></h2>
					<div class="entry-meta">
                        <span class="entry-category-icon"><?php $category = get_the_category();if($category[0]){echo '<mip-link href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</mip-link>';}?></span>
                        <span class="entry-comment-icon"><?php comments_popup_link ('0 评论','1 评论','% 评论'); ?></span>
					</div>
					<div class="entry-content">
						<?php custom_excerpt(100); ?>
					</div>
				</div>
			</div>
		<?php endif;$i++; ?>
		<?php endwhile; endif; ?>
		</div>
		<?php t_nav($query_string); ?>
	</div>
</div>
<div class="clear"></div>
<?php get_footer(); ?>