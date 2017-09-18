<?php
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
define('DB_NAME', 'mazeyqian');

/** MySQL数据库用户名 */
define('DB_USER', 'mazeyqian');

/** MySQL数据库密码 */
define('DB_PASSWORD', 'cc1314');

/** MySQL主机 */
define('DB_HOST', 'sql.hk192.vhostgo.com');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8');

/** 数据库整理类型。如不确定请勿更改 */
define('DB_COLLATE', '');

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/
 * WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'u$`{OATPKsI^TZil6w.4C(n*tsXQlHX$9pMaMi|4P)0%);l:-_bF5m|Vt0?RK72o');
define('SECURE_AUTH_KEY',  'UUaW/mCXHL(oa9-0#!C=C6d>|OrVp-<TKrzZS/ugzOIkSN`0#D%WM9]3A0d9m}$`');
define('LOGGED_IN_KEY',    'hs]z*Z>0g2t&b]X]xTafy]Ro7SFtj~4 $MN.dpdGmU&ND)ETfW&607TC~_y_BeDk');
define('NONCE_KEY',        'l0;lmY_<17_04i.$,pjIU)A}.ke$Xel)h1(^dBAG-.nsu4Iq(5UW42f7xt]DMC|?');
define('AUTH_SALT',        '2_1W# h[YE4Kli[+vxuy$JD #4IOrMX1].|)s;3q{HL7Y~hU.yH94@V>zt9A`|;V');
define('SECURE_AUTH_SALT', 'y7d[cun0aA:B_`=zft31p@V)F?q/FKjk8v~Qej:;iB9i|=R3%*fAuGQ]OTwAkj#K');
define('LOGGED_IN_SALT',   'f]x]W*u=9=7Mm+=bwV3I`,-#4 uSbeNr c_;WO)@_IP5h-UO*~[$$krm?t5|<}bx');
define('NONCE_SALT',       '^$w9!FY<Zw=Q,r~z*#9V26b!arIp<B[1$4A51-hFVFYhtW56Q Y(CNE!H.b>fEUj');

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix  = 'zhibaifa_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问Codex。
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/**
 * zh_CN本地化设置：启用ICP备案号显示
 *
 * 可在设置→常规中修改。
 * 如需禁用，请移除或注释掉本行。
 */
define('WP_ZH_CN_ICP_NUM', true);

/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 设置WordPress变量和包含文件。 */
require_once(ABSPATH . 'wp-settings.php');
