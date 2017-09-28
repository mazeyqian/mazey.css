<?php defined( 'ABSPATH' )  or exit; get_header(); ?>
<div id="container">
	<div class="post">	
	<?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
		<h1 id="post-title"><?php the_title(); ?></h1>
		<div id="post-category"><?php $category = get_the_category();if($category[0]){echo '<mip-link href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</mip-link>';}?></div>
		<div class="post_content"><?php the_content(); ?></div>
		<div id="rating"></div>
		<div class="clear"></div>
		<div class="section_title">
			<span>继续阅读</span>
		</div>
		<div class="post_detail">
		<?php endwhile; endif; ?>
			<ul class="entry-relate-links">
<?php
$prev_post = get_previous_post();
if (!empty( $prev_post )){
	echo '<li><span>上一篇 &gt;：</span><mip-link href="'.get_permalink( $prev_post->ID ).'">'.$prev_post->post_title.'</mip-link></li>';
}?>

<?php
$next_post = get_next_post();
if (!empty( $next_post )){
	echo '<li><span>下一篇 &gt;：</span><mip-link href="'.get_permalink( $next_post->ID ).'">'.$next_post->post_title.'</mip-link></li>';
}?>            
			</ul>
		</div>
	</div>
</div>
<div class="clear"></div>
<?php get_footer(); ?>