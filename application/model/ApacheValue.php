<?php
/*
 * @author shipSun <543999860@qq.com>
 */
namespace app\model;
 
use think\Config;
 
class ApacheValue extends Value{
	protected static $instance=false;
	
 	protected function __construct(){
 		$this->data = Config::load(APP_PATH.'apache.php', 'apache');
 	}
 	/**
 	 * @return Value
 	 */
 	public static function instance(){
 		if(!static::$instance){
 			static::$instance = new static();
 		}
 		return static::$instance;
 	}
 }