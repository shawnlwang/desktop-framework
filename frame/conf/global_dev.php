<?php
class Config_Global{
    static public $debug = null;
    static public $plugin = null;
    static public $log = null;
}

// --------------------------  调试配置  --------------------------- //
//是否是debug模式,debug模式会开启错误信息，禁用缓存
//0为关闭调试模式
//1表示开启调试模式，打印PHP错误，打印调试信息、日志，响应时间等信息
Config_Global::$debug['model'] = 0;


// --------------------------  插件配置  --------------------------- //
Config_Global::$plugin['before_controller'] = array();
Config_Global::$plugin['after_controller'] = array();
Config_Global::$plugin['before_action'] = array();
Config_Global::$plugin['after_action'] = array();

// --------------------------  日志配置  --------------------------- //

