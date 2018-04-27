<?php
header('Content-type:text/html;Charset=UTF-8');
//如果非debug模式后面会关闭
error_reporting(E_ALL);
define('ROOT_PATH' , dirname(dirname(__file__)).'/');
require(ROOT_PATH . '/app/run.php');
$context = Context::instance();
$context->dispatching();
