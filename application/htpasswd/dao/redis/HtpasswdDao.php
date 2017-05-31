<?php
/*
 * @author shipSun <543999860@qq.com>
 */
 namespace app\htpasswd\dao\redis;
 
use app\dao\db\redis\RedisDB;
use app\model\RedisValue;
use app\htpasswd\dao\HtpasswdDaoInterface;
use think\Log;

class HtpasswdDao implements HtpasswdDaoInterface{
	protected $key='htpasswd';
	
	public function find($user, $path){
		$db = RedisDB::instance();
		$key = $this->getKey($path, $user);
		$status = $db->exists($key);
		Log::record('key检测:'.$key.',返回:'.$status, Log::DEBUG);
		if($status==1){
			return $this->getUserInfo($key);
		}
		return false;
	}
	public function listUser($offset=0, $limit=1000){
		$db = RedisDB::instance();
		$data = $db->zRange($this->key, $offset, $limit);
		foreach($data as $key=>$val){
			$data[$key] = $this->getUserInfo($val);
			$data[$key]['key'] = $val;
		}
		return $data;
	}
	protected function getUserInfo($key){
		$db = RedisDB::instance();
		$data['path'] = $db->hGet($key, 'path');
		$data['user'] = $db->hGet($key, 'user');
		$data['passwd'] = $db->hGet($key, 'passwd');
		$data['use'] = $db->hGet($key, 'use');
		$data['date'] = $db->hGet($key, 'date');
		$data['id'] = $db->ZSCORE ($this->key, $key);
		$data['key'] = $key;
		Log::record($key.'数据:'.var_export($data,true), Log::DEBUG);
		return $data;
	}
 	public function insertUser($data){
 		$db = RedisDB::instance();
 		
 		$this->saveKey($data['path'], $data['user']);
 		
 		$db->multi();
 		try{
 			$db->watch($this->getKey($data['path'], $data['user']));
	 		$db->expire($this->getKey($data['path'], $data['user']), RedisValue::instance()->expire);
	 		
	 		$db->hMset($this->getKey($data['path'], $data['user']), $data);
	 		$db->exec();
	 		$db->unwatch();
	 		return true;
 		}catch (\Exception $e){
 			$db->discard();
 			throw new \Exception($e->getMessage(), 500);
 		}
 	}
 	protected function saveKey($path, $user){
 		$db = RedisDB::instance();
 		$num = $db->zSize($this->key);
 		$num++;
 		$db->zAdd($this->key, $num, $this->getKey($path, $user));
 	}
 	public function deleteUser($path, $user){
 		$db = RedisDB::instance();
 		$db->multi();
 		try{
 			$db->watch($this->getKey($path, $user));
 			
 			$this->delKey($this->getKey($path, $user));
 			
 			$db->del($this->getKey($path, $user));
 			$db->exec();
 			$db->unwatch();
 			return true;
 		}catch (\Exception $e){
 			$db->discard();
 			throw new \Exception($e->getMessage(), 500);
 		}
 	}
 	protected function delKey($key){
 		$db = RedisDB::instance();
 		$db->zRem($this->key, $key);
 	}
 	protected function getKey($path, $user){
 		return $path.':user:'.$user;
 	}
 }