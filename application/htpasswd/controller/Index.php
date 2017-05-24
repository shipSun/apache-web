<?php
/**
 * @author ship <543999860@qq.com>
 */
namespace app\htpasswd\controller;

use think\Request;
use app\htpasswd\model\HtpasswdModel;
use think\Config;
use app\htpasswd\dao\redis\HtpasswdDao;
use think\Controller;
use app\model\Model;

class Index extends Controller
{
    public function index(Request $request){
    	$this->assign('title', '列表页');
    	
    	$ht = new HtpasswdDao();
    	$data= $ht->listUser();
    	return $this->fetch('list',['data'=>$data]);
    }
    public function create(Request $request){
    	$this->assign('title', '创建界面');
    	
    	$htpasswdModel = new HtpasswdModel();
    	$htpasswdModel->init();
    	
    	return $this->fetch('create',['model'=>$htpasswdModel]);
    }
    public function save(Request $request){
    	try{
	        $data['path'] =$request->param('path');
	        $data['user'] = $request->param('user');
	        $data['passwd'] = $request->param('passwd');
	        $data['date'] = strtotime($request->param('date').' 23:59:59');
	        $ht = new HtpasswdModel($data);
	        echo $ht->create();
        }catch (\Exception $e){
        	echo $e->getMessage();
        }
    }
    public function edit($id){
    	$this->assign('title', '编辑界面');
    	$request = $this->formatID($id);
    	$htpasswdDao = new HtpasswdDao();
    	$userInfo = $htpasswdDao->find($request[2], $request[0]);
    	
    	$htpasswdModel = new HtpasswdModel($userInfo);
    	return $this->fetch('edit',['model'=>$htpasswdModel]);
    }
    public function update(Request $request, $id){
    	try{
	    	$userPath= $this->formatID($id);
	    	$data['path'] =$userPath[0];
	        $data['user'] = $userPath[2];
	        $data['passwd'] = $request->param('passwd');
	        $data['date'] = strtotime($request->param('date').' 23:59:59');
	        $ht = new HtpasswdModel($data);
	        echo $ht->create();
    	}catch (\Exception $e){
    		echo $e->getMessage();
    	}
    }
    public function delete($id){
    	try{
	    	$request = $this->formatID($id);
	    	$data['path'] =$request[0];
	    	$data['user'] = $request[2];
	    	 $ht = new HtpasswdModel($data);
	        echo $ht->delete();
        }catch (\Exception $e){
        	echo $e->getMessage();
        }
    }
    protected function formatID($id){
    	$request = explode(':', $id);
    	if(count($request)!=3){
    		throw new \Exception('参数格式错误');
    	}
    	return $request;
    }
}
