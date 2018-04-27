<?php
/**
 * 控制器的基础类
 *
 * @author laiwenhui
 */
class Controller {
    /**
     * 封装请求的对象
     *
     * @var Context
     */
    protected $_context;

    /**
     * 构造函数
     */
    final  function __construct()
    {
        $this->_context = Context::instance();
        $this->_init();
    }

    /**
     * 初始化函数
     *
     */
    protected  function _init(){}
}
