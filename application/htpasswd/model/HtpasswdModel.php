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
use app\model\Model;
use app\model\ApacheValue;

class HtpasswdModel extends Model{
	public function init(){
		$this->path = 'aaa';
		$this->user = '';
		$this->passwd = '';
		$this->date = time();
	}
    public function create(array $data=[]){
    	if(!empty($data)){
    		$this->setData($data);
    	}
        $this->validate(['path'=>$this->path, 'user'=>$this->user, 'passwd'=>$this->passwd, 'date'=>$this->date], 'create');
        $this->insertDB();
        
        $this->insertQueue();
        
        return $this->runCreateCommand();
    }
    protected function insertDB(){
    	$dao = FactoryDao::createDao('HtpasswdDao', 'htpasswd');
    	$dao->insertUser($this->getData());
    }
    protected function insertQueue(){
    	Queue::push('\app\htpasswd\job\UserJob', ['user'=>$this->user, 'path'=>$this->path]);
    }
    protected function runCreateCommand(){
    	if(HtpasswdCommand::instance()->create($this->path, $this->user, $this->passwd)){
    		return '添加用户成功';
    	}
    	throw new \Exception('添加用户失败', 500);
    }
    public function delete(){
    	$this->validate(['path'=>$this->path, 'user'=>$this->user], 'delete');
    	$this->runDeleteDB();
    	return $this->runDeleteCommand();
    }
    protected function runDeleteDB(){
    	$dao = FactoryDao::createDao('HtpasswdDao', 'htpasswd');
    	$dao->deleteUser($this->path, $this->user);
    }
    protected function runDeleteCommand(){
    	if(HtpasswdCommand::instance()->delete($this->path, $this->user)){
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
    public function getDate(){
    	return date('Y-m-d', $this->data['date']);
    }
    public function isPath($path){
    	if($this->path==$path){
    		return true;
    	}
    	return false;
    }
    public function getPathList(){
    	return ApacheValue::instance()->path;
    }
}