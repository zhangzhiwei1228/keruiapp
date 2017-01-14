<?php
// echo md5('KEY');
# Tag
define('BOCTAG', '0.9');

# 数据库
define('DB_TYPE', 'mysqli');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '123456');
define('DB_NAME', 'zzw_kerui');
define('DB_PREFIX', 'boc_');

# 全局URL路径
// 主域名 保留最后的 /
define('GLOBAL_URL', 'http://www.kerui.com/');
//define('GLOBAL_URL'  , 'http://localhost:8089/');
// 提供给后台做链接用的
define('STATIC_URL', GLOBAL_URL . 'static/');
define('UPLOAD_URL', GLOBAL_URL . 'upload/');
// 对应APP
define('SITE_URL', GLOBAL_URL . 'index.php/');
define('ADMINER_URL', GLOBAL_URL . 'bocadmin/');
define('API_URL', GLOBAL_URL . 'api/');
define('MOBILE_URL', GLOBAL_URL . 'mobile/');

// define('GLOBAL_URL'  , 'http://localhost:9000/');
// define('STATIC_URL'  , 'http://localhost:9001/');
// define('UPLOAD_URL'  , 'http://localhost:9002/');
// define('ADMINER_URL' , 'http://localhost:9003/');
// define('MOBILE_URL'  , 'http://localhost:9004/');

// // 快捷提供给JS
define('IMG_URL', STATIC_URL . 'img/');

# 引用绝对路径PATH定义
define('ROOT', __DIR__ . '/');
define('LOG_PATH', ROOT . 'boc/logs/');
define('LIBS_PATH', ROOT . 'boc/libs/');
define('CI_PATH', ROOT . 'boc/libs/ci/');
define('STATIC_PATH', ROOT . 'web/static/');
define('UPLOAD_PATH', ROOT . 'web/upload/');
define('SITE_PATH', ROOT . 'boc/site');
define('ADMIN_PATH', ROOT . 'boc/bocadmin');
define('API_PATH', ROOT . 'boc/api');

# 可忽略 当css|js改变时替换本地缓存,将false 替换为 'v[1,2...]'
define('STATIC_V', 'v3');

# 密钥设置;设置多个 用于 md5/sha1(hmac.value.time) 外部数据输入输出
# 提供给 app 的config 的 encryption_key
define('HMACPWD', 'SA1S2D3F4G5H6J7K8L9'); // PASSWD and cookie
define('HMAC', 'SA1S2D3F4G5H6J7K8L8'); // 提供第三方API验证使用
define('TOKEN_TIME_EXPIRE', 3600 * 24 * 7); // APP TOKEN 过期时间

define('EXPIRES_TIME', strtotime('2017-03-31')); // 优惠到期时间

// API 加密密钥
define('KEY', 'JiaoDao');

// 短信
define('SMS_ACCOUNT', '111111111');
define('SMS_PWD', '222222222222222');
define('SMS_PREFIX', '');


//融云
define('RY_APPKEY', '111111111');
define('RY_APPSECRET', '1111111');

//极光
define('CLIENT_JPUSH_APPKEY', '1111111111');
define('CLIENT_JPUSH_MASTERSSCRET', '11111111111111');

/*
 * 开发模式
 * 配置项目运行的环境，该配置会影响错误报告的显示和配置文件的读取。
 * development
 * testing
 * production
 * 使用 error_reporting();
 */
define('ENVIRONMENT', 'development');
// 有些服务器不支持调试，需要开启错误调试
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
// ini_set("error_reporting", 1);

// PHP 5 尝试加载未定义的类
// 挂载本地库 其他 core Controller
// 使用第三方报错工具可能会出现未加载的现象出现使
function BocLoader($class) {
	if (strpos($class, 'CI_') !== 0) {
		if (file_exists(APPPATH . 'core/' . $class . EXT)) {
			@include_once APPPATH . 'core/' . $class . EXT;
		} elseif (file_exists(LIBS_PATH . 'core/' . $class . EXT)) {
			@include_once LIBS_PATH . 'core/' . $class . EXT;
		}
	}
}
//注册自动加载,解决与其他自动加载第三方插件冲突
spl_autoload_register('BocLoader');
