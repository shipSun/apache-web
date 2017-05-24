<?php
/**
 * @author ship <543999860@qq.com>
 */
namespace app\htpasswd\validate;

use think\Validate;

class HtpasswdValidate extends Validate{
	protected $rule=[
			'user'=>'require|max:10',
			'passwd'=>'require|max:10',
			'path'=>'require',
			'date'=>'require',
	];
	protected $message=[
			'user.require'=>'帐户不能为空',
			'user.max'=>'帐户最大长度10',
			'passwd.require'=>'密码不能为空',
			'passwd.max'=>'密码最大长度10',
			'path.require'=>'项目不能为空',
			'date.require'=>'时间不能为空'
	];
	protected $scene=[
			'create'=> ['user','passwd','path','date'],
			'delete'=> ['user','path']
	];
}
