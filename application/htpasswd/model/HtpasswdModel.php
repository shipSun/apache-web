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
    public function create(Request $request){
        $passwdFile =$request->param('path');
        $user = $request->param('user');
        $passwd = $request->param('passwd');
        
        $this->validate($request, 'create');
        $this->insertQueue($passwdFile, $user, $passwd);
        
        return $this->runCreateCommand($passwdFile, $user, $passwd);
    }
    protected function insertQueue($passwdFile, $user, $passwd){
    	$data['passwdFile'] = $passwdFile;
    	$data['user'] = $user;
    	Queue::push('\app\htpasswd\job\UserJob', $data);
    }
    protected function runCreateCommand($passwdFile, $user, $passwd){
    	if(HtpasswdCommand::instance()->create($passwdFile, $user, $passwd)){
    		return '添加用户成功';
    	}
    	throw new \Exception('添加用户失败', 500);
    }
    public function delete(Request $request){
    	$passwdFile =$request->param('path');
    	$user = $request->param('user');
    	$this->validate($request, 'delete');
    	return $this->runDeleteCommand($passwdFile, $user);
    }
    protected function runDeleteCommand($passwdFile, $user){
    	if(HtpasswdCommand::instance()->delete($passwdFile, $user)){
    		return '删除用户成功';
    	}
    	throw new \Exception('删除用户失败', 500);
    }
    protected function validate(Request $request, $scene){
    	$validate = new HtpasswdValidate();
    	$result  = $validate->scene($scene)->check($request->param());
    	if($result==false){
    		throw new \Exception($validate->getError(), 500);
    	}
    	return true;
    }
}