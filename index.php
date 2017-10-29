2017.后除版权所有.<a href="//www.miitbeian.gov.cn">苏ICP备16050427号-3</a>
<a href="//www.mazey.net/baby/blog/">Blog</a>
<?php
exit;
require('config/config.php');
$the_host = $_SERVER['HTTP_HOST'];//取得当前域名
echo $the_host.'<br/>';
if($the_host=='shuxin.mazey.net'){
    header("HTTP/1.1 301 Moved Permanently");//发出301头部
    header("Location: http://www.mazey.net/baby/shuxin/");
	exit;
}elseif($the_host=='www.xueyuting.cc'||$the_host=='xueyuting.cc'){
	header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://www.mazey.net/baby/xueyuting/");
	exit;
}elseif($the_host=='www.mazey.net'||$the_host=='mazey.net'){
	header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://www.mazey.net/baby/blog/");
	exit;
}else{
    if(isMobileVisit()){ //如果是手机访问
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://www.mazey.net/baby/remind/");
        exit;
    }else{
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://www.mazey.net/baby/blog/");
        exit;
    }
}
?>
<html>
<head>
<title>Mazey|程程有一天</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="程程有一天与大家分享自己的生活和知识，享受生活，热爱生命，改变世界！" />
<meta name="keywords" content="程程有一天|钱程|程程|Mazey|MazeyQian|博客|代码|编程|读书|旅行|中医" />
<!--Mazey's Favicon-->
<link rel="shortcut icon" type="image/x-icon" href="http://www.mazey.net/favicon.ico" /> 
<!--script language="javascript" type="text/javascript" src="http://www.mazey.net/js/redirect.js"></script-->
<!--script language="javascript" type="text/javascript" src="http://www.mazey.net/js/visitor/record-visitor.js"></script-->
</head>
<body>
若你的页面没有跳转<a href="http://www.mazey.net/baby/blog/" target="_self">点击进入程程有一天的博客</a>
<h3>
    <span style="font-family:幼圆">童年</span>
</h3>
<p>
    童年一些事情。
</p>
</body>
</html>