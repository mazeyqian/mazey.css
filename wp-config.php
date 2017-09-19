<?php
/**
 * WordPress鍩虹閰嶇疆鏂囦欢銆�
 *
 * 杩欎釜鏂囦欢琚畨瑁呯▼搴忕敤浜庤嚜鍔ㄧ敓鎴恮p-config.php閰嶇疆鏂囦欢锛�
 * 鎮ㄥ彲浠ヤ笉浣跨敤缃戠珯锛屾偍闇�瑕佹墜鍔ㄥ鍒惰繖涓枃浠讹紝
 * 骞堕噸鍛藉悕涓衡�渨p-config.php鈥濓紝鐒跺悗濉叆鐩稿叧淇℃伅銆�
 *
 * 鏈枃浠跺寘鍚互涓嬮厤缃�夐」锛�
 *
 * * MySQL璁剧疆
 * * 瀵嗛挜
 * * 鏁版嵁搴撹〃鍚嶅墠缂�
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/zh-cn:%E7%BC%96%E8%BE%91_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL 璁剧疆 - 鍏蜂綋淇℃伅鏉ヨ嚜鎮ㄦ鍦ㄤ娇鐢ㄧ殑涓绘満 ** //
/** WordPress鏁版嵁搴撶殑鍚嶇О */
define('DB_NAME', 'zhibaifa');

/** MySQL鏁版嵁搴撶敤鎴峰悕 */
define('DB_USER', 'root');

/** MySQL鏁版嵁搴撳瘑鐮� */
define('DB_PASSWORD', 'root');

/** MySQL涓绘満 */
define('DB_HOST', 'localhost');

/** 鍒涘缓鏁版嵁琛ㄦ椂榛樿鐨勬枃瀛楃紪鐮� */
define('DB_CHARSET', 'utf8');

/** 鏁版嵁搴撴暣鐞嗙被鍨嬨�傚涓嶇‘瀹氳鍕挎洿鏀� */
define('DB_COLLATE', '');

/**#@+
 * 韬唤璁よ瘉瀵嗛挜涓庣洂銆�
 *
 * 淇敼涓轰换鎰忕嫭涓�鏃犱簩鐨勫瓧涓诧紒
 * 鎴栬�呯洿鎺ヨ闂畕@link https://api.wordpress.org/secret-key/1.1/salt/
 * WordPress.org瀵嗛挜鐢熸垚鏈嶅姟}
 * 浠讳綍淇敼閮戒細瀵艰嚧鎵�鏈塩ookies澶辨晥锛屾墍鏈夌敤鎴峰皢蹇呴』閲嶆柊鐧诲綍銆�
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
 * WordPress鏁版嵁琛ㄥ墠缂�銆�
 *
 * 濡傛灉鎮ㄦ湁鍦ㄥ悓涓�鏁版嵁搴撳唴瀹夎澶氫釜WordPress鐨勯渶姹傦紝璇蜂负姣忎釜WordPress璁剧疆
 * 涓嶅悓鐨勬暟鎹〃鍓嶇紑銆傚墠缂�鍚嶅彧鑳戒负鏁板瓧銆佸瓧姣嶅姞涓嬪垝绾裤��
 */
$table_prefix  = 'zhibaifa_';

/**
 * 寮�鍙戣�呬笓鐢細WordPress璋冭瘯妯″紡銆�
 *
 * 灏嗚繖涓�兼敼涓簍rue锛學ordPress灏嗘樉绀烘墍鏈夌敤浜庡紑鍙戠殑鎻愮ず銆�
 * 寮虹儓寤鸿鎻掍欢寮�鍙戣�呭湪寮�鍙戠幆澧冧腑鍚敤WP_DEBUG銆�
 *
 * 瑕佽幏鍙栧叾浠栬兘鐢ㄤ簬璋冭瘯鐨勪俊鎭紝璇疯闂瓹odex銆�
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/**
 * zh_CN鏈湴鍖栬缃細鍚敤ICP澶囨鍙锋樉绀�
 *
 * 鍙湪璁剧疆鈫掑父瑙勪腑淇敼銆�
 * 濡傞渶绂佺敤锛岃绉婚櫎鎴栨敞閲婃帀鏈銆�
 */
define('WP_ZH_CN_ICP_NUM', true);

/* 濂戒簡锛佽涓嶈鍐嶇户缁紪杈戙�傝淇濆瓨鏈枃浠躲�備娇鐢ㄦ剦蹇紒 */

/** WordPress鐩綍鐨勭粷瀵硅矾寰勩�� */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** 璁剧疆WordPress鍙橀噺鍜屽寘鍚枃浠躲�� */
require_once(ABSPATH . 'wp-settings.php');
