<?php
/**
 * context 封装了运行时上下文
 * 主要封装了请求参数和请求状态，以及 URL 解析等功能。
 * 使用了单例设计模式，因此只能使用 context::instance() 来获得context 对象的唯一实例
 */
class Context {

    /**
     * 默认控制器
     */
    const UDI_DEFAULT_CONTROLLER = 'Default';

    /**
     * 默认动作
     */
    const UDI_DEFAULT_ACTION = 'index';

    /**
     * @var 实例
     */
    static $instance;

    /**
     * 请求包含的控制器名称
     *
     * @var string
     */
    private  $_controller;

    /**
     * 请求包含的动作名
     *
     * @var string
     */
    private $_action;

    /**
     * 构造函数
     */
    private function __construct()
    {
        $this->_initSafe();
        $this->_initConfig();
        $this->_initRoute();
    }

    /**
     * 初始化安全过滤，安全检查
     *
     * @return unknown
     */
	private function _initSafe() {
		if(!get_magic_quotes_gpc()) {
			$_GET = daddslashes($_GET);
			$_POST = daddslashes($_POST);
			$_COOKIE = daddslashes($_COOKIE);
			$_FILES = daddslashes($_FILES);
		}
		$_GET = $this->_checkCSRF($_GET);
        $_POST = $this->_checkCSRF($_POST);
        $_COOKIE = $this->_checkCSRF($_COOKIE);
        $_FILES = $this->_checkCSRF($_FILES);
        $_ENV = $this->_checkCSRF($_ENV);
        $_REQUEST = $this->_checkCSRF($_REQUEST);
        $_SERVER = $this->_checkCSRF($_SERVER);
        if(isset($_SESSION)){
            $_SESSION = $this->_checkCSRF($_SESSION);
        }
		if(@$_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_SERVER['REQUEST_URI'])) {
				$temp = strtoupper(urldecode(urldecode($_SERVER['REQUEST_URI'])));
				if(strpos($temp, '<') !== false || strpos($temp, '"') !== false || strpos($temp, 'CONTENT-TRANSFER-ENCODING') !== false) {
					echo "error:异常请求！";
					exit();
				}
				return true;
		}
		//非法来源一切拒绝
		if(isset($_SERVER["HTTP_REFERER"]) && !preg_match("/^http:\/\/[\w\.]*\.(fenghuangshan)\.cn/",$_SERVER["HTTP_REFERER"]) ){
			echo "error:非法来源请求！";
		    exit();
		}
	}

    /**
     * 过滤安全漏洞
     * @param $param
     * @return mixed
     */
    private function _checkCSRF($param) {
        //过滤
        foreach($param as &$value){
            $value = str_replace("\n","",$value);
            $value = str_replace("%0d%0a","",$value);
        }
        return $param;
    }
	/**
	 * 初始化配置
	 *
	 */
	private function _initConfig() {
		//判断是否开启debug模式，开启后打印错误信息，debug日志
		if (Config_Global::$debug['model'] > 0){
			error_reporting(E_ALL);
			define('DEBUG_MODEL',intval(Config_Global::$debug['model']));
		} elseif (DEV_MODE) {
			error_reporting(E_ALL);
			define('DEBUG_MODEL',0);
		} else {
			error_reporting(E_ALL & ~E_NOTICE);
			define('DEBUG_MODEL',0);
		}
	}

	/**
     * 初始化路由配置
     *
     */
    private function _initRoute() {
    }

    /**
     * 返回 context 对象的唯一实例
     * @return Context|实例
     */
    static function instance()
    {
        if (is_null(self::$instance)){
        	self::$instance = new Context();
        }
        return self::$instance;
    }

	/**
     * 根据运行时上下文对象，调用相应的控制器动作方法
     */
    function dispatching()
    {
    	
    	$className = 'controller_'.$this->_controller;
    	$fileName = 'app/controller/'.$this->_controller.'.php';
        //构造控制器对象
        //执行before_controller插件
        $config = Config_Global::$plugin;
        if (!empty($config['before_controller'])){
        	foreach ($config['before_controller'] as $class){
                if(!class_exists($class)){
                    continue;
                }
        		$plugin = new $class();
        		$plugin->excute();
        	}
        }
		//controller不存在退出,优先使用配置的404方法
        if(!file_exists($fileName)){
            if(method_exists('config_business','notFound')){
                config_business::notFound();
            }elseif(method_exists('Config_Global','notFound')){
                Config_Global::notFound();
            }else{
                header('HTTP/1.1 404 Not Found');
                header("status: 404 Not Found");
                echo "controller[".$this->_controller."] not found.";
            }
			exit();
        }
        $controller = new $className();
        //执行controller定义的插件
        if(method_exists($controller,'_plugin')){
            $controllerPlugin = $controller->_plugin();
            if(is_array($controllerPlugin) && !empty($controllerPlugin)){
                foreach ($controllerPlugin as $pluginName){
                    if(!class_exists($pluginName)){
                        continue;
                    }
                    $plugin = new $pluginName();
                    $plugin->excute();
                }
            }
        }

        $actionName = $this->_action;
        if ($controller->existsAction($actionName))
        {
        	//执行before_action插件
	        if (!empty($config['before_action'])){
	        	foreach ($config['before_action'] as $class){
                    if(!class_exists($class)){
                        continue;
                    }
	        		$plugin = new $class();
	        		$plugin->excute();
	        	}
	        }
            // 如果指定动作存在，则调用
            $methodName = 'action'.ucfirst($actionName);
            $controller->$methodName();
            //执行after_action插件
	        if (!empty($config['after_action'])){
	        	foreach ($config['after_action'] as $class){
                    if(!class_exists($class)){
                        continue;
                    }
	        		$plugin = new $class();
	        		$plugin->excute();
	        	}
	        }
        }
        //执行after_controller插件
        if (!empty($config['after_controller'])){
        	foreach ($config['after_controller'] as $class){
                if(!class_exists($class)){
                    continue;
                }
        		$plugin = new $class();
        		$plugin->excute();
        	}
        }
    }

    /**
     * 获得当前的action
     *
     * @return unknown
     */
    function getAction(){
    	return $this->_action;
    }

    /**
     * 获得当前的controller
     *
     * @return unknown
     */
    function getController(){
    	return $this->_controller;
    }
}

