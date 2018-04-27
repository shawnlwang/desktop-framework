<?php
/**
 * 环境变量初始化
 */
define('MODULE_PATH', ROOT_PATH . 'modules/');
define('LOG_PATH', ROOT_PATH . 'log/');
define('DATA_PATH', ROOT_PATH . 'data/');
define('LIB_PATH', ROOT_PATH . 'lib/');
define('CONFIG_PATH',ROOT_PATH . 'config/');
define('WEBROOT_PATH', ROOT_PATH . 'htdocs/');
define('PLUGIN_PATH', MODULE_PATH . 'plugin/');
define('FRAME_PATH', ROOT_PATH . '/frame/lib/');

//是否是开发环境\测试环境
if (get_cfg_var('domain.devmode')){
	define('DEV_MODE', 1);
}else{
	define('DEV_MODE', 0);
}
//是否是测试环境
if (get_cfg_var('domain.testmode')){
	define('TEST_MODE', 1);
}else{
	define('TEST_MODE', 0);
}
//设置时区
date_default_timezone_set("Asia/Shanghai");
header("Content-Type:text/html;charset=utf-8");
//框架公共函数
require(FRAME_PATH . 'functions.php');
//框架错误码
require(CONFIG_PATH . 'error.php');
//全局配置
if (DEV_MODE){
	require(CONFIG_PATH . 'global_dev.php');
}else {
	require(CONFIG_PATH . 'global.php');
}

