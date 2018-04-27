<?php
/**
 * 错误码定义
 *
 */
class Config_Error{
	//系统公用错误信息
	const ERROR_SUCCESS = 0;
	const ERROR_INPUT = -1;
	const ERROR_CONTROLLER = -2;
	const ERROR_ACTION = -3; 
	const ERROR_REQUEST = -4; 
	const ERROR_LOGIN = -5;
	const ERROR_UNKNOW = -6;
	const ERROR_SESSION = -7;
	const ERROR_EXPIRED = -100;
	const ERROR_PERMITTED = -10;
    const ERROR_REFERER = -14;

	static  $errMsg = array(
		//系统公用错误信息
		error::ERROR_INPUT => '参数错误',
		error::ERROR_CONTROLLER => '控制器不存在',
		error::ERROR_ACTION => 'action不存在',
		error::ERROR_REQUEST => '无效的请求',
		error::ERROR_LOGIN => '登录错误',
		error::ERROR_UNKNOW => '未知错误',
		error::ERROR_SESSION => '登录态校验失败',
		error::ERROR_EXPIRED => 'session过期',
		error::ERROR_PERMITTED => '权限验证失败',
        error::ERROR_REFERER => '错误referer',
		
	);
}

