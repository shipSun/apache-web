<?php
/*
 * @author shipSun
 */
 namespace app\htpasswd\job;
 
 use think\Log;
 use think\queue\Job;
 use app\command\HtpasswdCommand;
 
 class UserJob{
 	public function fire(Job $job, $data){
 		Log::record('开始执行任务'.var_export($data,true), Log::DEBUG);
 		try{
 			$passwdFile = $data['passwdFile'];
 			$user = $data['user'];
 			HtpasswdCommand::instance()->delete($passwdFile, $user);
 			$job->delete();
 		}catch(\Exception $e){
 			throw new \Exception($e->getMessage());
 		}
 	}
 }