<?php defined( 'ABSPATH' )  or exit; get_header(); ?>
<div id="container">
	<div id="posts">
		<div id="posts-list">
<?php
$args = array(
	'post__in'  => get_option( 'sticky_posts' ),
	'showposts' => 1,//显示文章数量
	'ignore_sticky_posts' => 1//忽略sticky_posts，即不置顶（不将置顶文章提至最前），但是输出置顶文章
);
$query = new WP_Query( $args );
if ($query->have_posts() && (is_home()&&!is_paged())) : while ($query->have_posts()) : $query->the_post(); 
?>
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
<?php endwhile; endif; ?>
<?php 
$args = array(
	'ignore_sticky_posts' => 1,//忽略sticky_posts，即不置顶（不将置顶文章提至最前），但是输出置顶文章
	'paged' => $paged,
	'cat' => get_option('_ex_display'), //排除显示的分类目录
	'post__not_in' => array_slice(get_option( 'sticky_posts' ),-1,1),//排除首页前1篇置顶文章
);
$query = new WP_Query( $args );
if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
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
		<?php endwhile;endif; ?>
	</div>
	<?php t_nav($query_string); ?>
</div>
<div class="clear"></div>
<?php get_footer(); ?>