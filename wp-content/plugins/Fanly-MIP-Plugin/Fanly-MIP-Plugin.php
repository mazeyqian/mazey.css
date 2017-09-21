<?php
/*
Plugin Name: Fanly MIP
Plugin URI: http://zhangzifan.com
Description: WordPress MIP插件(免费版)，实现MIP页面数据主动实时推送，包括更新、删除、Mip-cache清理，手动推送更新，其它站点推送更新等接口功能！
Version: 1.0
Author: Fanly
Author URI: http://zhangzifan.com
*/

//WordPress MIP页面主动推送功能
add_action('save_post', 'fanly_mip_notify_baidu', 10, 2);
function fanly_mip_notify_baidu($post_id, $post){
	if($post->post_status != 'publish') return;
	$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项

	if($post->post_date == $post->post_modified){
		$baidu_api_url = 'http://data.zz.baidu.com/urls?site='.$Fanly['Site'].'&token='.$Fanly['Token'].'&type=mip';
	}else{
		$baidu_api_url = 'http://data.zz.baidu.com/update?site='.$Fanly['Site'].'&token='.$Fanly['Token'].'&type=mip';
	}
 
	$response = wp_remote_post($baidu_api_url, array(
		'headers' => array('Accept-Encoding'=>'','Content-Type'=>'text/plain'),
		'timeout'	=> 10,
		'sslverify' => false,
		'blocking' => false,
		'body' => str_replace( strtok(str_replace("//","",strstr(home_url(),"//")), '/'), $Fanly['Site'], get_permalink($post_id) )
	));
}
//WordPress MIP页面主动删除功能
add_action('before_delete_post', 'fanly_mip_delete_notify_baidu');
function fanly_mip_delete_notify_baidu($post_ID) {
	if(get_post_status($post_ID) != 'publish') return;
	$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项
	
	$baidu_api_url = 'http://data.zz.baidu.com/del?site='.$Fanly['Site'].'&token='.$Fanly['Token'].'&type=mip';
	
	$response = wp_remote_post($baidu_api_url, array(
		'headers' => array('Accept-Encoding'=>'','Content-Type'=>'text/plain'),
		'timeout'	=> 10,
		'sslverify' => false,
		'blocking' => false,
		'body' => str_replace( strtok(str_replace("//","",strstr(home_url(),"//")), '/'), $Fanly['Site'], get_permalink($post_id) )
	));
}

//WordPress Mip-cache清理
add_action('save_post', 'fanly_mip_cache_clean', 10, 2);
function fanly_mip_cache_clean($post_id, $post){
	if($post->post_status != 'publish') return;
	$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项

	if($post->post_date != $post->post_modified){
		$api = 'http://mipcache.bdstatic.com/update-ping/c/';
		$url = $api.urlencode( strtok(str_replace("//","",strstr(get_permalink($post_id),"//")), '/') );
		$postData = 'key='.$Fanly['Authkey'];
	}
 
	$response = wp_remote_post($url, array(
		'method'	=> 'POST',
		'timeout'	=> 10,
		'sslverify'	=> false,
		'blocking'	=> false,
		'body'		=> $postData
	));
}
//发布写文章更新首页
add_action('publish_post', 'fanly_mip_cache_clean_home', 10, 2);
function fanly_mip_cache_clean_home($post_id, $post){
	if($post->post_status != 'publish') return;
	$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项

	$api = 'http://mipcache.bdstatic.com/update-ping/c/';
	$url = $api.urlencode( strtok(str_replace("//","",strstr(home_url(),"//")), '/') );
	$postData = 'key='.$Fanly['Authkey'];
 
	$response = wp_remote_post($url, array(
		//'headers' => array('Accept-Encoding'=>'','Content-Type'=>'text/plain'),
		'method'	=> 'POST',
		'timeout'	=> 10,
		'sslverify'	=> false,
		'blocking'	=> false,
		'body'		=> $postData
	));
}

//add plugin link
add_filter( 'plugin_action_links', 'Fanly_MIP_Add_Link', 10, 2 );
function Fanly_MIP_Add_Link( $actions, $plugin_file ) {
	static $plugin;
	if (!isset($plugin))
		$plugin = plugin_basename(__FILE__);
	if ($plugin == $plugin_file) {
			$settings = array('settings' => '<a href="options-general.php?page=Fanly_MIP">' . __('Settings') . '</a>');
			$site_link = array('support' => '<a href="http://zhangzifan.com" target="_blank">Fanly</a>');
			$actions = array_merge($settings, $actions);
			$actions = array_merge($site_link, $actions);
	}
	return $actions;
}
//remove plugin 
add_filter( 'plugin_action_links', 'Fanly_MIP_remove_plugin_link', 10, 4 );
function Fanly_MIP_remove_plugin_link( $actions, $plugin_file, $plugin_data, $context ){
	if(isset($actions['edit'])){
		switch($plugin_file){
			case 'Fanly-MIP/Fanly-MIP.php':unset( $actions['edit'] );
			break;
		}
	}
	return $actions;
}

//设置默认数据   
add_action('admin_init', 'fanly_mip_default_options');
function fanly_mip_default_options(){
	$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项
	if( $Fanly == '' ){   
		$Fanly = array(
			'Site'			=> '',
			'Token'			=> '',
			'Authkey'		=> '',
			'CustomSite'	=> '',
		);
		update_option('Fanly_MIP', serialize($Fanly));//更新选项   
	}
}

//插件设置菜单
add_action('admin_menu', 'fanly_mip_menus');
function fanly_mip_menus() {
	$icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNi4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IuWbvuWxgl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgd2lkdGg9IjY0cHgiIGhlaWdodD0iNjRweCIgdmlld0JveD0iMCAwIDY0IDY0IiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA2NCA2NCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8cG9seWdvbiBmaWxsPSIjRENEREREIiBwb2ludHM9IjIzLjU5Nyw1Ny43MTkgMTIuNzUzLDU3LjcxOSAxOC4xNTksNi4yODEgNTEuMjQ3LDYuMjgxIDUwLjM0MSwxNC45IDI4LjA5NywxNC45IDI2LjgxMiwyNy4xMzQgDQoJNDcuNjY0LDI3LjEzNCA0Ni43NiwzNS43NTQgMjUuOTA1LDM1Ljc1NCAiLz4NCjwvc3ZnPg0K';
	add_menu_page('Fanly MIP 设置', 'Fanly MIP' ,'manage_options', 'Fanly_MIP' , 'fanly_mip_options', $icon  );
	add_submenu_page('Fanly_MIP','Fanly MIP 基础设置', '基础设置','manage_options', 'Fanly_MIP' , 'fanly_mip_options');
	add_submenu_page('Fanly_MIP','Fanly MIP 缓存更新', '缓存更新','manage_options', 'Fanly_MIP_Cache' , 'fanly_mip_cache');
	add_submenu_page('Fanly_MIP','Fanly MIP 批量推送', '批量推送','manage_options', 'Fanly_MIP_Submit' , 'fanly_mip_submit');
}

//基础设置
function fanly_mip_options() {
//保存数据
if(isset($_POST['save_submit'])){
    //处理数据
	if($_POST['Site']=='custom'){
		$CustomSite = $_POST['CustomSite'];
		if(strstr($CustomSite,"//")){ 
			$CustomSite = strtok(str_replace("//","",strstr($CustomSite,"//")), '/');
		}else{$CustomSite = strtok($CustomSite, '/');}
		$Site = $CustomSite;
	}else{$Site=$_POST['Site'];}
	$Fanly = array(
		'Site'			=> trim($Site),
		'Token'			=> trim($_POST['Token']),
		'Authkey'		=> trim($_POST['Authkey']),
		'CustomSite'	=> trim($CustomSite),
	);
    update_option('Fanly_MIP', serialize($Fanly));//更新选项   
}
$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项
echo '<div class="wrap">';
echo '<h2>Fanly MIP 基础设置</h2>';
echo '<form method="post">';
echo '<table class="form-table">';

echo '<tr valign="top">';
echo '<th scope="row">MIP Site</th>';
echo '<td><select name="Site" id="Site">';
$FanlyDomainTheme = unserialize(get_option("FanlyDomainTheme_options"));
if(class_exists('FanlyDomainTheme') && $FanlyDomainTheme) {
	foreach($FanlyDomainTheme as $domain){
		if($Fanly['Site'] == $domain['url']){
			echo '<option value="'.$domain['url'].'" selected>'.$domain['url'].'</option>';
		}else{
			echo '<option value="'.$domain['url'].'">'.$domain['url'].'</option>';
		}
	}
	if($Fanly['Site'] == strtok(str_replace("//","",strstr(home_url(),"//")), '/')){
		echo '<option value="'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'" selected>'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'</option>';
	}else{
		echo '<option value="'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'">'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'</option>';
	}
	if($Fanly['Site']==$Fanly['CustomSite']){
		echo '<option value="custom" selected>自定义Site</option>';
	}else{
		echo '<option value="custom">自定义Site</option>';
	}
}else{
	if($Fanly['Site'] == strtok(str_replace("//","",strstr(home_url(),"//")), '/')){
		echo '<option value="'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'" selected>'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'</option>';
		echo '<option value="custom">自定义Site</option>';
		echo '<option value="'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'">更多域名需要 FanlyDomainTheme 插件支持</option>';
	}else{
		echo '<option value="'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'">'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'</option>';
		if($Fanly['Site']==$Fanly['CustomSite']){
			echo '<option value="custom" selected>自定义Site</option>';
		}else{
			echo '<option value="custom">自定义Site</option>';
		}
		echo '<option value="'.strtok(str_replace("//","",strstr(home_url(),"//")), '/').'">更多域名需要 FanlyDomainTheme 插件支持</option>';
	}
}
echo '</select></td>';
echo '</tr>';

echo '<tr valign="top" id="CustomSite">';
echo '<th scope="row">Custom Site</th>';
echo '<td><input type="text" name="CustomSite" value="'.$Fanly['CustomSite'].'" placeholder="请输入自定义Site"/></td>';
echo '</tr>';

echo '<tr valign="top">';
echo '<th scope="row">MIP Token</th>';
echo '<td><input type="text" name="Token" value="'.$Fanly['Token'].'" placeholder="请输入Token"/></td>';
echo '</tr>';

echo '<tr valign="top">';
echo '<th scope="row">Mip-cache Authkey</th>';
echo '<td><input type="text" name="Authkey" value="'.$Fanly['Authkey'].'" style="width: 266px;" placeholder="请输入Authkey"/></td>';
echo '</tr>';


echo '</table>';
echo '<p class="submit">';
echo '<input type="submit" name="save_submit" id="submit" class="button" value="保存更改" />';
echo '</p>';
echo '</form>';
echo '<p><strong>使用提示</strong>：<br>
通过百度站长平台获取接口：<a target="_blank" href="http://zhanzhang.baidu.com/mip">zhanzhang.baidu.com/mip</a><br>
例如：<span>http://data.zz.baidu.com/urls?<em style="color:red">site</em>=<span style="color:green">mip.zhangzifan.com</span>&amp;<em style="color:red">token</em>=<span style="color:green">kzyx6XOtXxojbxuY</span>&amp;type=mip</span><br>
1.Site: mip.zhangzifan.com<br>
2.Token: kzyx6XOtXxojbxuY<br>
3.Authkey: 6d191a880d49f0e1822698b70276x1f1<br>
注明：Site,Token用于MIP页面主动推送；Authkey用于MIP-cache清除。</p>';
echo '</div>';
echo '
<script type="text/javascript">
	jQuery(function(){';
if($Fanly['Site']!=$Fanly['CustomSite']){
echo 'jQuery("#CustomSite").hide();';
	
}
echo '		jQuery("select#Site").change(function(){
			var selected = jQuery("select#Site").val();
			if(selected == "custom"){
				jQuery("#CustomSite").show();
			}else{
				jQuery("#CustomSite").hide();
			}
		});
	});
</script> 
';
}
//缓存更新
function fanly_mip_cache() {
if(isset($_POST['clean']) && $_POST['Site'] && $_POST['URL'] ){
	$api = 'http://mipcache.bdstatic.com/update-ping/c/';
	$u = $_POST['URL'];
	if(strstr($u,"//")){ 
		$u = strtok(str_replace("//","",strstr($u,"//")), '/');
	}else{$u = strtok($u, '/');}
	$url = $api.urlencode( $u );
	if($_POST['Site'] != 'custom'){
		$postData = 'key='.$_POST['Site'];
	}else{
		$postData = 'key='.$_POST['Authkey'];
	}

	$response = wp_remote_post($url, array(
		'method'	=> 'POST',
		'timeout'	=> 10,
		'sslverify'	=> false,
		'blocking'	=> true,
		'body'		=> $postData
	));
	
	if ( is_wp_error( $response ) ) {
		echo '<div class="error" id="message"><p>未知错误或超时，请重试！</p></div>';
		//$error_message = $response->get_error_message();
		//echo "Something went wrong: $error_message";
	} else {
		//echo $response['body'];//测试专用
		$json = json_decode($response['body'], true);
		if($json['status']==0){//更新成功
			echo '<div class="updated notice is-dismissible" id="message"><p>'.$_POST['URL'].' 更新成功!</p></div>';
		}elseif($json['status']==1){//更新失败
			if($json['msg']=='auth check fail'){//授权校验失败
				echo '<div class="error" id="message"><p>授权校验失败，请检查 Authkey 和 MIP URL！</p></div>';
			}elseif($json['msg']=='limit check fail'){//更新次数超限
				echo '<div class="error" id="message"><p>更新次数超限，100s内最多允许更新10次！</p></div>';
			}
		}
	}
	
}   
$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项
echo '<div class="wrap">';
echo '<h2>Fanly MIP 缓存更新</h2>';
echo '<form method="post">';
echo '<table class="form-table">';

echo '<tr valign="top">';
echo '<th scope="row">MIP Site</th>';
echo '<td><select name="Site" id="Site">';
if($Fanly['Site']){
	echo '<option value="'.$Fanly['Authkey'].'" selected>'.$Fanly['Site'].'</option>';
}else{
	echo '<option value="">无Site，可以手动输入Authkey</option>';
}
if(!empty($_POST['Authkey'])){
	echo '<option value="custom" selected>输入Authkey</option>';
}else{
	echo '<option value="custom">输入Authkey</option>';
}
echo '</select></td>';
echo '</tr>';

echo '<tr valign="top" id="Authkey">';
echo '<th scope="row">MIP Authkey</th>';
if (!empty($_POST['Authkey'])){$Authkey=$_POST['Authkey'];}else{$Authkey='';}
echo '<td><input type="text" name="Authkey" value="'.$Authkey.'" size="33" style="width: 266px;" placeholder="请输入Authkey"/></td>';
echo '</tr>';

echo '<tr valign="top">';
echo '<th scope="row">Mip URL</th>';
echo '<td><input type="text" name="URL" value="" style="width: 266px;" placeholder="请输入MIP URL"/></td>';
echo '</tr>';


echo '</table>';
echo '<p class="submit">';
echo '<input type="submit" name="clean" id="submit" class="button" value="立即清除" />';
echo '</p>';
echo '</form>';
echo '<p><strong>使用提示</strong>：<br>可以选择已经配置的MIP Site然后输入需要清除MIP-cache的URL即可，也可选择输入Authkey更新任意MIP页面缓存。</p>';
echo '</div>';
echo '
<script type="text/javascript">
	jQuery(function(){';
if(empty($_POST['Authkey'])){
	echo 'jQuery("#Authkey").hide();';
}
echo 'jQuery("select#Site").change(function(){
			var selected = jQuery("select#Site").val();
			if(selected == "custom"){
				jQuery("#Authkey").show();
			}else{
				jQuery("#Authkey").hide();
			}
		});
	});
</script> 
';
}

//批量推送
function fanly_mip_submit() {
if(isset($_POST['link_submit']) && $_POST['Mode']){
	$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项
	$urls = array();
	if($_POST['Mode']=='all'){
		$args = array(
			'nopaging'		=> 1,//不分页，整站链接
			'post_type'		=> 'post',//只推送post文章,any为所有类型
			'post_status'	=> array( 'publish' )//已发布
		);
		$loop = new WP_Query( $args );
		if ($loop->have_posts()):while ( $loop->have_posts() ) : $loop->the_post();
			$urls[] = str_replace( strtok(str_replace("//","",strstr(home_url(),"//")), '/'), $Fanly['Site'], get_permalink());
		endwhile;endif;
	}elseif($_POST['Mode']=='one'){
		$urls[] = $_POST['one'];
	}elseif($_POST['Mode']=='custom'){
		$content=empty($_POST['custom'])?false:trim($_POST['custom']);
		if($content)$urls = explode("\r\n", trim($content));
	}

	$api = 'http://data.zz.baidu.com/urls?site='.$Fanly['Site'].'&token='.$Fanly['Token'].'&type=mip';
	$ch = curl_init();
	$options =  array(
	    CURLOPT_URL => $api,
	    CURLOPT_POST => true,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POSTFIELDS => implode("\n", $urls),
	    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
	);
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);//状态码
	//echo $result;测试专用
	if($httpCode == 200){
		$json = json_decode($result, true);
		if($json['success']){
			echo '<div class="updated notice is-dismissible" id="message">
			<p>成功推送 '.$json['success'].' 条<!--，当天剩余 '.$json['remain'].' 可推送条数-->！</p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button>
			</div>';
		}
		if($json['not_same_site']){
			echo '<div class="error notice is-dismissible" id="message">';
			echo '<p>不是本站URL未处理的列表:</p>';
			foreach($json['not_same_site'] as $not_same_site){
				echo '<p>'.$not_same_site.'</p>';
			}
			echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button>
			</div>';
		}
		if($json['not_valid']){
			echo '<div class="error notice is-dismissible" id="message">';
			echo '<p>不合法的URL列表:</p>';
			foreach($json['not_valid'] as $not_valid){
				echo '<p>'.$not_valid.'</p>';
			}
			echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">忽略此通知。</span></button>
			</div>';
		}
	}else{
		$json = json_decode($result, true);
		if($json['error']==401){
			echo '<div class="error" id="message"><p>Token设置无效</p></div>';
		}else{
			echo '<div class="error" id="message"><p>'.$json['message'].'</p></div>';
		}
	}
}   
$Fanly = unserialize(get_option('Fanly_MIP'));//获取选项
echo '<div class="wrap">';
echo '<h2>Fanly MIP 批量推送</h2>';
echo '<form method="post">';
echo '<table class="form-table">';

echo '<tr valign="top">';
echo '<th scope="row">推送模式</th>';
echo '<td><select name="Mode" id="Mode">';
switch ($_POST['Mode']){
case 'all':$all = 'selected';break;
case 'one':$one = 'selected';break;
case 'custom':$custom = 'selected';break;
case 'other':$other = 'selected';break;
default:$all = $one = $custom = $other = '';
}
echo '<option value="all" '.$all.'>整站推送</option>';
echo '<option value="one" '.$one.'>单条推送</option>';
echo '<option value="custom" '.$custom.'>批量推送</option>';
echo '<option value="other" '.$other.'>其它站点推送</option>';
echo '</select></td>';
echo '</tr>';

echo '<tr valign="top" id="one">';
echo '<th scope="row">单条推送</th>';
echo '<td><input type="text" name="one" value="" size="33" style="width: 266px;" placeholder="请输入一个URL地址"/></td>';
echo '</tr>';

echo '<tr valign="top" id="custom">';
echo '<th scope="row">批量推送</th>';
echo '<td><textarea name="custom" cols="" rows="5" style="resize:both;width:266px;white-space:nowrap;" placeholder="请输入URL，每行一个"/></textarea></td>';
echo '</tr>';

echo '<tr valign="top" id="othersite">';
echo '<th scope="row">MIP Site</th>';
echo '<td><input type="text" name="OtherSite" value="" size="33" style="width: 266px;" placeholder="请输入其它MIP站点"/></td>';
echo '</tr>';

echo '<tr valign="top" id="othertoken">';
echo '<th scope="row">MIP Token</th>';
echo '<td><input type="text" name="othertoken" value="" size="33" style="width:266px;" placeholder="请输入其它MIP Token"/></td>';
echo '</tr>';

echo '<tr valign="top" id="other">';
echo '<th scope="row">批量推送</th>';
echo '<td><textarea name="other" cols="" rows="5" style="resize:both;width:266px;white-space:nowrap;" placeholder="请输入其它MIP URL，每行一个"/></textarea></td>';
echo '</tr>';

echo '</table>';
echo '<p class="submit">';
echo '<input type="submit" name="link_submit" id="submit" class="button" value="开始推送" />';
echo '</p>';
echo '</form>';
echo '<p><strong>使用提示</strong>：<br>整站推送模式文章越多需要等待的时间越久；<br>合理使用有助于提高百度收录和页面的更新！</p>';
echo '</div>';
echo '
<script type="text/javascript">
	jQuery(function(){';
if(!empty($_POST['Mode']) && $_POST['Mode']!='all'){
	if($_POST['Mode']!='one')echo 'jQuery("#one").hide();';
	if($_POST['Mode']!='custom')echo 'jQuery("#custom").hide();';
	if($_POST['Mode']!='other')echo 'jQuery("#other").hide();jQuery("#othersite").hide();jQuery("#othertoken").hide();';
}else{
	echo '
		jQuery("#one").hide();
		jQuery("#custom").hide();
		jQuery("#other").hide();
		jQuery("#othersite").hide();
		jQuery("#othertoken").hide();
	';
}
echo '	jQuery("select#Mode").change(function(){
			var selected = jQuery("select#Mode").val();
			if(selected == "one"){
				jQuery("#one").show();
				jQuery("#custom").hide();
				jQuery("#other").hide();
				jQuery("#othersite").hide();
				jQuery("#othertoken").hide();
			}else if(selected == "custom"){
				jQuery("#custom").show();
				jQuery("#one").hide();
				jQuery("#other").hide();
				jQuery("#othersite").hide();
				jQuery("#othertoken").hide();
			}else if(selected == "other"){
				jQuery("#other").show();
				jQuery("#othersite").show();
				jQuery("#othertoken").show();
				jQuery("#custom").hide();
				jQuery("#one").hide();
			}else{
				jQuery("#one").hide();
				jQuery("#custom").hide();
				jQuery("#other").hide();
				jQuery("#othersite").hide();
				jQuery("#othertoken").hide();
			}
		});
	});
</script> 
';
}