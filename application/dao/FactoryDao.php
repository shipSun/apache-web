<?php
/*
 * @author shipSun <543999860@qq.com>
 */
 namespace app\dao;
 
 
use app\model\ApacheValue;
class FactoryDao{
	protected static $classMap=[];
	
 	public static function createDao($daoName, $module){
 		$config = ApacheValue::instance()->getData();
 		$type = !empty(ApacheValue::instance()->type) ? ApacheValue::instance()->type : 'redis';
 		$className = 'app\\'.$module.'\\dao\\'.$type.'\\'.$daoName;
 		if(!isset(static::$classMap[$className])){
 			if(!class_exists($className,true)){
 				throw new \Exception('类未定义:'.$className, 500);
 			}
 			static::$classMap[$className] = new $className();
 		}
 		return static::$classMap[$className];
 	}
 }