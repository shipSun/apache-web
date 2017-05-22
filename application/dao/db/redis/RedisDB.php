<?php
/*
 * @author shipSun
 */
 namespace app\dao\db\redis;
 
 use think\Config;

class RedisDB extends \Redis{
 	protected static $db=false;
 	/**
 	 * @return \Redis
 	 */
 	public static function instance(){
 		if(!static::$db){
 			
 			$config = Config::load(APP_PATH.'redis.php', 'redis');
 			if(!is_array($config)){
 				throw new \Exception('redis配置文件格式错误', 500);
 			}
 			
 			if (!extension_loaded('redis')) {
 				throw new \Exception('redis扩展未安装', 500);
 			}
 			
 			static::$db = new static();
 			$func = $config['persistent'] ? 'pconnect' : 'connect';
 			static::$db->$func($config['host'], $config['port'], $config['timeout']);
 			if ('' != $config['password']) {
 				static::$db->auth($config['password']);
 			}
 			
 			if (0 != $config['select']) {
 				static::$db->select($config['select']);
 			}
 		}
 		return static::$db;
 	}
 }