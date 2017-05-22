<?php
/*
 * @author shipSun <543999860@qq.com>
 */
 namespace app\htpasswd\dao\redis;
 
use app\dao\db\redis\RedisDB;
use app\model\RedisValue;
use app\htpasswd\dao\HtpasswdDaoInterface;

class HtpasswdDao implements HtpasswdDaoInterface{
 	public function insertUser($passwdFile, $user, $passwd){
 		$db = RedisDB::instance();
 		$db->multi();
 		try{
 			$db->watch($this->getKey($user));
	 		$db->expire($this->getKey($user), RedisValue::instance()->expire);
	 		$data = [];
	 		$data['user'] = $user;
	 		$data['passwd'] = $passwd;
	 		$data['path'] = $passwdFile;
	 		
	 		$db->hMset($this->getKey($user), $data);
	 		$db->exec();
	 		$db->unwatch();
	 		return true;
 		}catch (\Exception $e){
 			$db->discard();
 			throw new \Exception($e->getMessage(), 500);
 		}
 	}
 	public function deleteUser($user){
 		$db = RedisDB::instance();
 		$db->multi();
 		try{
 			$db->watch($this->getKey($user));
 			$db->del($this->getKey($user));
 			$db->exec();
 			$db->unwatch();
 			return true;
 		}catch (\Exception $e){
 			$db->discard();
 			throw new \Exception($e->getMessage(), 500);
 		}
 	}
 	protected function getKey($user){
 		return 'user:'.$user;
 	}
 }