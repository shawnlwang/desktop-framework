<?php
/**
 * 路由规则实现类
 * @author laiwenhui
 * @date 2012-07-06
 */
class route
{
	/**
     * 路由规则
     *
     * @var array
     */
    private $_routes = array();

    /**
     * 用于分割匹配模式多个部分的定界符
     *
     * @var string
     */
    private $_partDelimiter = '/';
	/**
	 * 路由解析后的业务app
	 *
	 * @var string
	 */
	private $_app = '';
    /**
     * 路由解析后的控制器
     *
     * @var string
     */
    private $_controller = '';
    /**
     * 路由解析后的action
     *
     * @var string
     */
    private $_action = '';
    /**
     * 路由解析后的参数
     *
     * @var string
     */
    private $_param = array();
    private $_init = false;
    /**
     * 匹配路由，匹配失败则返回默认路由规则
     *
     * @param string $uri
     *
     * @return array $return=array(
     * 							'controller'=>'test',
     * 							'action'=>'index'
     * 							'param'=>array('id'=>'fdf'),
     * 						)
     */
    public function match($uri, $app=''){
    	if ($uri === '/' || $uri===''){
    		return false;
    	}
    	//去除前后的'/'
    	$uri = trim($uri,'/');
    	$flag = 0;

		$uriArr = explode('/', $uri);

		//如果执行app则不在匹配
		if(!empty($app)){
			$this->_app = $app;
			$this->_simpleMatch(implode('/',$uriArr));
		}else{
			//抽离业务app
			if(count($uriArr) == 1){
				$this->_app = $uriArr[0];
				$this->_controller = 'default';
				$this->_action = 'index';
			}else{
				$this->_app = $uriArr[0];
				//简单路由
				unset($uriArr[0]);
				$this->_simpleMatch(implode('/',$uriArr));
			}
		}

    	$return = array(
				'app' => $this->_app,
    			'controller' => $this->_controller,
    			'action' => $this->_action,
    			'param' => $this->_param,
    		);
    	return $return;
    }
    /**
     * 根据路由规则生成地址
     *
     * @param unknown_type $udi
     * @param unknown_type $param
     */
    public function url($udi, $param=array()){
    	if ($this->_init === false){
    		return false;
    	}
    	if ($udi === '/' || $udi===''){
    		$arrUdi = array('default','index');
    	}else{
    		$arrUdi = explode('/',$udi);
    		$arrUdi[0] = empty($arrUdi[0])?'default':$arrUdi[0];
    		$arrUdi[1] = empty($arrUdi[1])?'index':$arrUdi[1];
    	}
    	
    	$url = '';
    	$url = implode('/',$arrUdi);
    	if (!empty($param)){
    		foreach ($param as $key=>$var){
    			$url .= "/$key/$var";
    		}
    	}
    	return $url;
    }
    /**
     * 解析uri，支持的类型为：
     * 			1.domain/id	domain/1
     *          2.domain/controller
     * 			3.domain/controller/id domain/controller/1
     * 			4.domain/controller/action/id domain/controller/action/1
     * 			5.domain/controller/action/key/value 
     *
     * @param unknown_type $uri
     */
    private function _simpleMatch($uri){
    	$arrUri = explode($this->_partDelimiter,$uri);

    	$argNum = count($arrUri);
    	switch ($argNum){
    		case 1:
    			if (is_numeric($arrUri[0])){
    				$this->_controller = 'default';
					$this->_action = 'index';
					$this->_param['id'] = $arrUri[0];
    			}else {
    				$this->_controller = strtolower($arrUri[0]);
					$this->_action = 'index';
    			}
				break;
			case 2:
				if (is_numeric($arrUri[1])){
					$this->_controller = strtolower($arrUri[0]);
					$this->_action = 'index';
					$this->_param['id'] = $arrUri[1];
				}else{
					$this->_controller = strtolower($arrUri[0]);
					$this->_action = strtolower($arrUri[1]);
				}
				break;
			case 3:
				$this->_controller = strtolower($arrUri[0]);
				$this->_action = strtolower($arrUri[1]);
				if (is_numeric($arrUri[2])){
					$this->_param['id'] = $arrUri[2];
				}
				break;
			default:
				$this->_controller = strtolower($arrUri[0]);
				$this->_action = strtolower($arrUri[1]);
				for ($i = 2; $i < $argNum; $i = $i + 2)
				{
					$key = urldecode($arrUri[$i]);
					$val = isset($arrUri[$i + 1]) ? urldecode($arrUri[$i + 1]) : null;
					$this->_param[$key] = $val;
				}
				
    	}
    }
}
/*$config = array(
		'test' => array(
				'regex' => 'test/(\d+)',
				'udi' => array('test','index'),
				'param' => array('1'=>'id'),
			)
	);
	
$route = new route();
$route->init($config);
var_dump($route->match('/test/fdf/fdf/121f23/'));
var_dump($route->url('test/index',array('id'=>10)));*/