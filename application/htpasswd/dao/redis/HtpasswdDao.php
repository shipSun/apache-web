<?php
/*
 * @author shipSun <543999860@qq.com>
 */
 namespace app\htpasswd\dao\redis;
 
use app\dao\db\redis\RedisDB;
use app\model\RedisValue;
use app\htpasswd\dao\HtpasswdDaoInterface;

class HtpasswdDao implements HtpasswdDaoInterface{
	protected $key='htpasswd';
	
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
		$data['id'] = $db->ZSCORE ($this->key, $key);
		return $data;
	}
 	public function insertUser($path, $user, $passwd){
 		$db = RedisDB::instance();
 		
 		$this->saveKey($path, $user);
 		
 		$db->multi();
 		try{
 			$db->watch($this->getKey($path, $user));
	 		$db->expire($this->getKey($path, $user), RedisValue::instance()->expire);
	 		$data = [];
	 		$data['user'] = $user;
	 		$data['passwd'] = $passwd;
	 		$data['path'] = $path;
	 		
	 		$db->hMset($this->getKey($path, $user), $data);
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
 			
 			$this->delKey($user);
 			
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