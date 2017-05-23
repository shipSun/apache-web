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

class Index extends Controller
{
    public function index(Request $request)
    {
    	$this->assign('title', '列表页');
    	
    	$ht = new HtpasswdDao();
    	$data= $ht->listUser();
    	return $this->fetch('list',['data'=>$data]);
    }
    public function create(Request $request){
    	echo 'create';
    }
    public function save(Request $request){
        $ht = new HtpasswdModel();
        $path =$request->param('path');
        $user = $request->param('user');
        $passwd = $request->param('passwd');
        echo $ht->create($path,$user,$passwd);
    }
    public function edit(Request $request, $id){
        echo 'edit';
    }
    public function update(){
        echo 'update';
    }
    public function delete(Request $request, $id){
    	 $ht = new HtpasswdModel();
        echo $ht->delete($request->param('path'), $request->param('user'));
    }
}
