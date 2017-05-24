<?php
/*
 * @author shipSun
 */
 namespace app\htpasswd\job;
 
 use think\Log;
 use think\queue\Job;
use app\htpasswd\dao\redis\HtpasswdDao;
use app\htpasswd\model\HtpasswdModel;
		 
 class UserJob{
 	public function fire(Job $job, $data){
 		Log::record('开始执行任务'.var_export($data,true), Log::DEBUG);
 		try{
 			$path = $data['path'];
 			$user = $data['user'];
 			$htpasswdDao = new HtpasswdDao();
 			$userInfo = $htpasswdDao->find($user, $path);
 			Log::record('数据:'.var_export($userInfo,true), Log::DEBUG);
 			if($userInfo && $userInfo['date']<=time()){
 				Log::record('删除时间:'.date('Y-m-d', time()), Log::DEBUG);
 				$htpasswdModel = new HtpasswdModel($userInfo);
 				$htpasswdModel->delete();
 				$job->delete();
 			}
 		}catch(\Exception $e){
 			throw new \Exception($e->getMessage());
 		}
 	}
 }