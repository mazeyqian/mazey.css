<?php
/* 包含所有类 */
require_once(dirname(__FILE__) . '/class-mip.php');
require_once(dirname(__FILE__) . '/class-show.php');

/* 注册所要用到的类 */
$GLOBALS['Object_MIP'] = new Class_MIP();
$GLOBALS['Object_Show'] = new Class_Show();