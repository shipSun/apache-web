<?php
/**
 * @author ship <543999860@qq.com>
 */
namespace app\htpasswd\controller;

use think\Request;
class Index
{
    public function index()
    {
        echo 'list';
    }
    public function create(){
        echo 'create';
    }
    public function save(){
        echo 'save';
    }
    public function read(Request $request, $id){
        var_dump($request->param());
        echo 'read';
    }
    public function edit(){
        echo 'edit';
    }
    public function update(){
        echo 'update';
    }
}
