<?php
/*
 * @author shipSun
 */
 namespace app\command;
 
 use think\Config;
use think\Log;
 
class Command{
	protected static $instance=false;
	
	public static function instance(){
		if(!static::$instance){
			static::$instance = new static();
		}
		return static::$instance;
	}
 	protected function getBinPath(){
 		$config = $this->getConfig();
 		if(isset($config['bin_path'])){
 			return $config['bin_path'];
 		}
 		throw new \Exception('没有设置bin_path目录', 500);
 	}
 	protected function getSuffix(){
 		$config = $this->getConfig();
 		if(isset($config['suffix'])){
 			return $config['suffix'];
 		}
 		return '';
 	}
 	protected function getConfig(){
 		$config = Config::load(APP_PATH.'apache.php', 'apache');
 		log::record('加载apache配置:'.var_export($config,true), Log::DEBUG);
 		return $config;
 	}
 	protected function run($command){
 		Log::record('命令:'.$command, Log::DEBUG);
 		set_time_limit(0);
 		passthru($command, $return_var);
 		if($return_var==0){
 			return true;
 		}
 		return false;
 	}
 }