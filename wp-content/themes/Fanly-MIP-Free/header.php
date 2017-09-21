<?php defined( 'ABSPATH' )  or exit;?><!DOCTYPE html>
<html mip>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
<link rel="stylesheet" type="text/css" href="https://mipcache.bdstatic.com/static/v1/mip.css">
<?php if ( is_home() ) { ?>
<title><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?><?php if ($paged > 1) echo '-第 ', $paged, ' 页'; ?></title>
<?php } ?>
<?php if ( is_search() ) { ?>
<title>搜索结果 - <?php bloginfo('name'); ?></title>
<?php } ?>
<?php if ( is_single() ) { ?>
<title><?php echo trim(wp_title('',0)); ?><?php if (get_query_var('page')) { echo '-第'; echo get_query_var('page'); echo '页';}?> - <?php bloginfo('name'); ?></title>
<?php } ?>
<?php if ( is_page() ) { ?>
<title><?php echo trim(wp_title('',0)); ?> - <?php bloginfo('name'); ?></title>
<?php } ?>
<?php if ( is_category() ) { ?>
<title><?php single_cat_title(); ?> - <?php bloginfo('name'); ?></title>
<?php } ?>
<?php if ( is_author() ) { ?>
<title><?php the_author_nickname(); ?> - <?php bloginfo('name'); ?></title>
<?php } ?>
<?php if (function_exists('is_tag')) { if ( is_tag() ) { ?>
<title><?php  single_tag_title("", true); ?> - <?php bloginfo('name'); ?></title>
<?php } ?><?php } ?>
<?php //wp_head(); ?>
<?php
if(is_home()){
	echo '<link rel="canonical" href="'.get_bloginfo('url').'" />'."\n";
}else
if(is_tax() || is_tag() || is_category()){
	$term = get_queried_object();
	echo '<link rel="canonical" href="'.get_term_link( $term, $term->taxonomy ).'" />'."\n";
}else
if(is_page()){
	echo '<link rel="canonical" href="'.get_permalink().'" />'."\n";
}else
if(is_single()){
	echo '<link rel="canonical" href="'.get_permalink().'" />'."\n";
}
?>
<style mip-custom>
html,body,div,p,ul,ol,li,h1,h2,h3,h4,pre,form,p,button,img{margin:0;padding:0;border:0}input,button,select,textarea{outline:0}textarea{resize:none}body{-webkit-text-size-adjust:none;background:#f5f6f9;font-size:12px;color:#494949;font-family:"PingFang SC","Microsoft JhengHei";}ul,li{list-style-type:none}#container{text-align:left;margin:0 auto;padding:0}mip-link{text-decoration:none;color:#00AAEE;cursor:pointer;}mip-link:hover{text-decoration:none;color:#4e4e4e}.round,.pagenavi{border-radius:5px}.clear{clear:both;overflow:hidden}.none{display:none}#logo{background:url('<?php bloginfo('template_url')?>/images/logo.png') no-repeat 0 0;width:123px;height:42px;border-left:none;border-right:0;margin:0;padding:0}#nav{margin:0 0 2px 0;width:100%;font-size:12px;height:42px;color:#ccc;position:fixed;z-index:998}.width #nav{font-size:14px}.nav-ul{height:42px;background:#FFF;position:relative;border-top:2px solid #00AAEE;border-bottom:1px solid #f0f0f0;box-shadow:0 1px 3px rgba(0,0,0,.03);}.nav-ul li{float:left;}.nav-ul li mip-link{color:#bbb;display:inline-block;padding:0 8px;text-decoration:none;height:30px;}.nav-ul .dropdownlink{float:right}.nav-ul .current_page_item mip-link{background:#000;color:#efefef;text-decoration:none}.prev-next{display:block;text-align:center;margin:15px 0 0 0;padding:18px 0;position:relative;border-top:1px solid #eee;border-bottom:1px solid #eee}.prev-next mip-link{display:inline-block;padding:10px 22px;background:#4e4e4e;color:#fff;border-radius:5px;font-weight:bold}.prev-next mip-link:hover{background:#00AAEE;color:#fff}.prev-next .prev{margin-right:10px}.prev-next-page{color:#aaa;margin-right:10px;display:inline-block;padding:7px 0}.pagenavi{display:block;text-align:center;margin:15px 0}.pagenavi mip-link,.pagenavi span{display:inline-block;padding:0 8px;margin:0 2px 0 0;height:25px;line-height:25px}.pagenavi .current{color:#bbb;border:1px solid #EEE;padding:0 9px;border-radius:5px}.post_detail .prev-next{border-top:0;margin-top:0}.post_detail .prev-next mip-link{background:##4e4e4e;color:#fff}.post_detail .prev-next mip-link:hover{background:#00AAEE;color:#fff;}#posts{padding:0 8px;padding-top:50px;margin-bottom:20px;}.entry{margin-top:12px;padding:9px 9px;min-height:60px;background:#FFF;}.entry p{margin:15px 0}.entry h2{font-size:14px;margin:0 0 5px 0}.entry h2 mip-link{color:#333}.entry h2 mip-link:hover{color:#00AAEE;text-decoration:none}.top-entry{margin-top:8px;padding:0;position:relative;border:0;clear:both}.top-entry .entry-title{font-size:14px;border-right:9px solid #00AAEE;position:absolute;display:inline-block;width:60%;background:#000;color:#fff;padding:5px;margin:0;bottom:2px;right:2px;opacity:.75}.top-entry-img{display:block;height:150px;}.top-entry-img-wrapper{overflow:hidden;border:1px solid #888;padding:1px;background:#fff}.top-entry-title,.top-entry-content{display:none}.entry-thumb{float:left;display:inline-block;margin-right:9px;overflow:hidden}.entry-thumb mip-link{display:inline-block;border:1px solid #888;padding:1px;height:57px}.entry-thumb mip-link img{width:80px;height:57px}.entry-body{color:#888;width:auto;}.entry-meta mip-link{color:#888}.entry-meta mip-link:hover{color:#00AAEE}.entry-category-icon,.entry-comment-icon{background:#fff url("<?php bloginfo('template_url')?>/images/sprite.png") no-repeat;margin-right:10px;padding-left:20px;display: inline-block;}.entry-category-icon{background-position:-104px 0}.entry-comment-icon{background-position:-104px -21px}.entry-content{display:none;margin:10px 0 0 0;line-height:18px;font-size:14px}.post,.page{color:#777;font-size:14px;padding:0 12px;margin:10px 10px;margin-top:66px;background:#FFF;}.post mip-link.mip-layout-container, .page mip-link.mip-layout-container{display: initial;}#post-title{display:block;padding:10px 0 6px 0;font-weight:bold;font-size:20px;border-bottom:2px solid #4e4e4e;color:#000}#post-category{color:#fff;background:#4e4e4e;float:right;padding:2px 6px;clear:left}#post-category mip-link{color:#fff}.post_content{color:#444;padding:12px 0 0 0;font-size:16px}.post_content p,.page_content p{margin:15px 0 0 0;line-height:24px;}.post_content h2,.post_content h3{border-left:3px solid #00AAEE;color:#333;padding-left:12px;margin:15px 0 0 0;line-height:20px;font-size:18px;font-weight:bold;}.post_content img{max-width:95%;height:auto;clear:both;display:block;margin:0 auto;}.post_content ul,.post_content ol{margin:20px 0;}.post_content ul li{font-size:16px;list-style-type:disc;padding:0;margin-left:40px;margin-bottom:12px;}.post_content ol li{font-size:16px;list-style-type:decimal;padding:0;margin-left:40px;margin-bottom:12px;}.post_detail ul li{padding:2px 0}.entry-relate-links{padding:5px 0;margin:5px 0;border-bottom:1px solid #eee;}.img-align{display:block;margin-left:auto;margin-right:auto}img.aligncenter{margin:0 auto;display:block;text-align:center}img.alignleft{float:left;margin-right:15px;margin-bottom:15px}img.alignright{float:right;margin-left:15px;margin-bottom:15px}blockquote,#post_expire_msg{border:1px solid #eee;background:#fcfcfc;margin:5px 0;padding:5px 15px}.post_content blockquote p{margin:0}#post_expire_msg{color:#db0000;line-height:20px;margin:10px 0 0 0;text-align:center;display:none}.selflink{color:#666}.selflink:hover{color:#666;text-decoration:none}.post .entry-author{margin:50px 0 40px 0}.entry-author{border:1px solid #bbb;padding:10px;margin:20px 0;background:#f9f9f9;font-size:12px;position:relative}.entry-author mip-link{color:#0086e3}.entry-author mip-link:hover{text-decoration:underline}.entry-author-title{color:#fff;position:absolute;right:10px;top:-1px;background:#0086e3;height:20px;line-height:20px;padding:0 15px}.entry-author .avatar{float:left;margin:0 10px 5px 0;width:40px;height:40px;border:1px solid #b1d2ea;padding:1px}.entry-author-about{color:#999}.entry-author-name{font-weight:bold;font-size:16px}.entry-author-description{margin:8px 0 4px 0}.entry-author-links{color:#ddd;text-align:center}.same-cat-post li mip-link{display:block;padding:7px 0;border-bottom:1px dashed #ddd}.section_title{border-bottom:1px solid #bbb;margin:30px 0 10px 0;position:relative;height:1px;font-weight:bold}.section_title span,.section_title mip-link{position:absolute;top:-22px;padding:0 15px 2px 0;color:#000;border-bottom:7px solid #4e4e4e}#footer{font-size:12px;padding:20px 0 20px 0;clear:both;width:100%;background:#3b3c41;margin-top:20px;color:#888;border-top:12px solid #494c54}#footer mip-link{color:#aaa;display: inline-block;}#footer-body{margin:0 auto}#footer-content{text-align:center}#footer-btn-list{margin-bottom:15px}.footer-button{display:inline-block;margin:0 2px;padding:0 7px;border:1px solid #232427;border-radius:3px;background:#4f5158;background:-webkit-gradient(linear,left top,left bottom,color-stop(0,#5c5e65),color-stop(100%,#4f5158));box-shadow:inset 0 0 1px rgba(127,130,142,.65);font-size:12px;line-height:25px;color:#fbfbfb}.page_title{text-align:center;margin:15px 0 15px 0;padding:7px 0;border:1px solid #fcfcfc;border-bottom:4px solid #4e4e4e;background:#FFF;font-size:16px;color:#000;font-weight:bold}.highlight_title{background:#f1f7fd none repeat scroll 0 0;border:1px solid #d2e8fa;color:#3c99c9;font-size:14px;font-weight:normal;height:28px;line-height:28px;margin-bottom:10px;text-align:center;width:100%}.button{-webkit-appearance:none;width:70px;display:inline-block;padding:7px 0;background:#4e4e4e;color:#fff;font-weight:bold;font-size:14px;border:none;}.button:hover{background:#00AAEE;}@media all and (min-width:800px){body .top-entry{float:left;background:#FFF;width:100%;}body .top-entry .entry-title{display:none}body .top-entry-title{color:#000;display:inline-block;padding:10px 10px;background:#fff;float:left;margin-left:460px;border-right:10px solid #00AAEE}body .top-entry-title h2{font-size:24px;font-weight:bold}body .top-entry-content{font-size:16px;color:#888;display:inline-block;float:left;margin:10px 0 0 460px;padding:0 10px;}body .top-entry-img{height:250px;width:450px;background-size:auto;position:relative}body .top-entry-img-wrapper{border:0;float:left;margin-right:-460px}body .comment-r,body .comment-l{width:45%;float:left}}@media all and (min-width:600px){body #nav{font-size:16px}body .prev-next{font-size:16px}body .entry{min-height:105px;padding:12px 12px}body .entry h2{font-size:20px}body .entry-thumb mip-link{height:100px;display:inline-block}body .entry-thumb mip-link img{width:140px;height:100px}body .entry-thumb{margin-right:12px;}body .entry-content{display:block}body .post,body .page{font-size:16px;padding:6px 15px}body #post-title{font-size:24px}body .post_content{font-size:18px}body .post_content p,body .page_content p{line-height:27px}body .post_content img{height:none}}
<?php
if(is_home()){
	$args = array(
		'post__in'  =>get_option( 'sticky_posts' ),
		'showposts' => 1,//显示文章数量
		'ignore_sticky_posts' => 1//忽略sticky_posts，即不置顶（不将置顶文章提至最前），但是输出置顶文章
	);
	$query = new WP_Query( $args );
	if ($query->have_posts() && (is_home()&&!is_paged())) : while ($query->have_posts()) : $query->the_post();
		echo '.top-entry-img{background:url("';
		post_thumbnail();
		echo '") no-repeat center center;background-size: cover;}';
	endwhile; endif;
}elseif(!is_single()&&!is_page()){$i = 1;
	if(have_posts()):while(have_posts()):the_post();if(($i == 1)):
		echo '.top-entry-img{background:url("';
		post_thumbnail();
		echo '") no-repeat center center;background-size: cover;}';
	endif;$i++;endwhile; endif;
}
?>
</style>
</head>
<body>
<div id="nav">
<ul class="nav-ul">
	<li><mip-link id="logo" href="<?php echo get_option('home'); ?>"></mip-link></li>
</ul>
</div>
<div class="clear"></div>