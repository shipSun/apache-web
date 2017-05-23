<?php
/**
 * @author ship <543999860@qq.com>
 */
namespace app\htpasswd\model;

use think\Request;
use app\command\HtpasswdCommand;
use app\htpasswd\validate\HtpasswdValidate;
use app\dao\FactoryDao;
use think\Queue;

class HtpasswdModel{
    public function create($path, $user, $passwd){
        $this->validate(['path'=>$path, 'user'=>$user, 'passwd'=>$passwd], 'create');
        $this->insertDB($path, $user, $passwd);
        
        $this->insertQueue($path, $user, $passwd);
        
        return $this->runCreateCommand($path, $user, $passwd);
    }
    protected function insertDB($path, $user, $passwd){
    	$dao = FactoryDao::createDao('HtpasswdDao', 'htpasswd');
    	$dao->insertUser($path, $user, $passwd);
    }
    protected function insertQueue($path, $user, $passwd){
    	$data['passwdFile'] = $path;
    	$data['user'] = $user;
    	Queue::push('\app\htpasswd\job\UserJob', $data);
    }
    protected function runCreateCommand($path, $user, $passwd){
    	if(HtpasswdCommand::instance()->create($path, $user, $passwd)){
    		return '添加用户成功';
    	}
    	throw new \Exception('添加用户失败', 500);
    }
    public function delete($path, $user){
    	$this->validate(['path'=>$path, 'user'=>$user], 'delete');
    	
    	return $this->runDeleteCommand($path, $user);
    }
    protected function runDeleteDB($path, $user){
    	$dao = FactoryDao::createDao('HtpasswdDao', 'htpasswd');
    	$dao->deleteUser($path, $user);
    }
    protected function runDeleteCommand($path, $user){
    	if(HtpasswdCommand::instance()->delete($path, $user)){
    		return '删除用户成功';
    	}
    	throw new \Exception('删除用户失败', 500);
    }
    protected function validate($data, $scene){
    	$validate = new HtpasswdValidate();
    	$result  = $validate->scene($scene)->check($data);
    	if($result==false){
    		throw new \Exception($validate->getError(), 500);
    	}
    	return true;
    }
}