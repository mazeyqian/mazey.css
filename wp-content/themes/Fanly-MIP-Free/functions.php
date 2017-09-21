<?php
defined( 'ABSPATH' )  or exit;
//reg thumbnails
if ( function_exists('add_theme_support') ){
	add_theme_support('post-thumbnails');
}
//echo thumbnail src
function post_thumbnail(){
	global $post;
	if( $values = get_post_custom_values("thumb") ) {	//输出自定义域图片地址
		$values = get_post_custom_values("thumb");
		$src = $values [0];
	} elseif( has_post_thumbnail() ){	//如果有特色缩略图，则输出缩略图地址
		$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
		$src = $thumbnail_src [0];
	} else {	//文章中获取
        $content = $post->post_content;  
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);  
        $n = count($strResult[1]);  
        if($n > 0){ // 提取首图
            $src = $strResult[1][0];  
        }else { // null
            $src = ''.get_bloginfo('template_url').'/images/nopic.jpg';//空
        } 
	};
		echo $src;
}

//页面导航
function t_nav($query_string){
	global $posts_per_page, $paged;
	$my_query = new WP_Query($query_string ."&posts_per_page=-1");
	$total_posts = $my_query->post_count;
	if(empty($paged))$paged = 1; 
	$prev = $paged - 1;
	$next = $paged + 1;
	$range = 2; // only edit this if you want to show more page-links
	$showitems = ($range * 2)+1;
	$pages = ceil($total_posts/$posts_per_page);
	if(1 != $pages){
		echo "<div class=\"prev-next\">";
		for ($i=2; $i <= $pages; $i++){
			if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
				echo ($paged == $i)? "<mip-link class=\"prev\" href='".get_pagenum_link($prev)."'>« 上一页</mip-link>": "";
				echo ($paged == $i)? "<span class=\"prev-next-page\">第".$i."页</span>": "";
			}
		}
		echo ($paged < $pages && $showitems >= 1) ? "<mip-link href='".get_pagenum_link($next)."'>下一页 »</mip-link>" :"";
		echo "</div>\n";
	}
}

//remove Google Fonts
function remove_open_sans() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'remove_open_sans' );

//replace admin font
function Fanly_admin_lettering() {
	echo '<style type="text/css">
* { font-family: "Microsoft YaHei"; }
#activity-widget #the-comment-list .avatar { max-width: 50px; max-height: 50px; }
</style>';
}
add_action( 'admin_head', 'Fanly_admin_lettering' );


// hide admin bar
add_filter('show_admin_bar', 'hide_admin_bar');
function hide_admin_bar($flag) {
	return false;
}

//WordPress文章内容自动添加nofollow及_blank属性
add_filter( 'the_content', 'auto_add_url_parse');
function auto_add_url_parse( $content ) {
	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
	if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
		if( !empty($matches) ) {
			$srcUrl = get_option('siteurl');
			for ($i=0; $i < count($matches); $i++){
				$tag = $matches[$i][0];
				$tag2 = $matches[$i][0];
				$url = $matches[$i][0];
 
				$noFollow = '';
 
				$pattern = '/target\s*=\s*"\s*_blank\s*"/';
				preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
				if( count($match) < 1 ){
					$noFollow .= ' target="_blank" ';
				}else{
					$target = '';//为文章中内链添加_blank
				}
 
				$pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
				preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
				if( count($match) < 1 ){
					$noFollow .= ' rel="nofollow" ';
				}elseif($noFollow ==''){
					$noFollow .= '';
				}

				$pos = strpos($url,$srcUrl);
				if ($pos === false) {
					$tag = rtrim ($tag,'>');
					$tag .= $noFollow.'>';
					$content = str_replace($tag2,$tag,$content);
				}elseif($target!==''){//为文章中内链添加_blank，匹配百度mip
					$tag = rtrim ($tag,'>');
					$tag .= ' target="_blank">';
					$content = str_replace($tag2,$tag,$content);
				}
			}
		}
	}
	$content = str_replace(']]>', ']]>', $content);
	return $content;
}

//自定义摘要控制
function custom_excerpt($len=100) {
	global $post;
	if ($post->post_excerpt) {
		$excerpt  = $post->post_excerpt;
	} else {
		if(preg_match('/<p>(.*)<\/p>/iU',trim(strip_tags($post->post_content,"<p>")),$result)){
			$post_content = $result['1'];
		} else {
			$post_content_r = explode("\n",trim(strip_tags($post->post_content)));
			$post_content = $post_content_r['0'];
		}
		$excerpt = $post_content;  
	}
	echo mb_strimwidth($excerpt,0,$len,'...');
}

//内容MIP规范
add_filter('the_content', 'fanly_mip_images');
function fanly_mip_images($content){
	//WordPress文章内图片适配百度MIP规范
	preg_match_all('/<img (.*?)\>/', $content, $images);
	if(!is_null($images)) {
		foreach($images[1] as $index => $value){
			$mip_img = str_replace('<img', '<mip-img', $images[0][$index]);
			$mip_img = str_replace('>', '></mip-img>', $mip_img);
			//以下代码可根据需要修改/删除
			$mip_img = preg_replace('/(width|height)="\d*"\s/', '', $mip_img );//移除图片width|height
			$mip_img = preg_replace('/ style=\".*?\"/', '',$mip_img);//移除图片style
			//$mip_img = preg_replace('/ class=\".*?\"/', '',$mip_img);//移除图片class
			//以上代码可根据需要修改/删除
			$content = str_replace($images[0][$index], $mip_img, $content);
		}
	}
	//WordPress文章内样式去除
	preg_match_all('/ style=\".*?\"/', $content, $style);
	if(!is_null($style)) {
		foreach($style[0] as $index => $value){
			$mip_style = preg_replace('/ style=\".*?\"/', '',$images[0][$index]);//移除文章内容中的style
			$content = str_replace($style[0][$index], $mip_style, $content);
		}
	}

	return $content;
}

//WordPress站内链接匹配mip-link
add_action('get_header', 'fanly_mip_link');
function fanly_mip_link(){
    function Fanly_mip_link_main ($content){
		preg_match_all('/<a (.*?)\>(.*?)<\/a>/', $content, $links);
		if(!is_null($links)) {
			$siteurl	= get_option('siteurl');
			foreach($links[1] as $index => $value){
				preg_match('/href="(.*)"/', $value, $a);
				if( strpos($a[1],strstr($siteurl, '//')) ){
					$mip_link = str_replace('<a', '<mip-link', $links[0][$index]);
					//以下代码可根据需要修改/删除
					$mip_link = preg_replace('/ target=\".*?\"/', '',$mip_link);//移除target
					$mip_link = preg_replace('/ style=\".*?\"/', '',$mip_link);//移除style
					//$mip_link = preg_replace('/ class=\".*?\"/', '',$mip_link);//移除class
					//以上代码可根据需要修改/删除
					$mip_link = str_replace('</a>', '</mip-link>', $mip_link);
					$content = str_replace($links[0][$index], $mip_link, $content);
				}
			}
		}
		return $content;
	}
	ob_start("Fanly_mip_link_main");
}

//WordPress SSL
add_filter('get_header', 'fanly_ssl');
function fanly_ssl(){
	if( is_ssl() ){
		function fanly_ssl_main ($content){
			$siteurl = get_option('siteurl');
			$upload_dir = wp_upload_dir();
			$content = str_replace( 'http:'.strstr($siteurl, '//'), 'https:'.strstr($siteurl, '//'), $content);
			$content = str_replace( 'http:'.strstr($upload_dir['baseurl'], '//'), 'https:'.strstr($upload_dir['baseurl'], '//'), $content);
			return $content;
		}
		ob_start("fanly_ssl_main");
	}
}


//全部设置结束
?>
