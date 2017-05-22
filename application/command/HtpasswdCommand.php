<?php
/*
 * @author shipSun <543999860@qq.com>
 */
 namespace app\command;
 
 class HtpasswdCommand extends Command{
 	
 	/**
 	 * @return HtpasswdCommand
 	 */
 	public static function instance(){
 		return parent::instance();
 	}
 	public function create($passwdFile, $user, $passwd){
 		$command = $this->getBinPath().'htpasswd'.$this->getSuffix().' -b';
 		if(!is_file($this->getPasswdName($passwdFile))){
 			$command.='c';
 		}
 		$command.= ' '.$this->getPasswdName($passwdFile).' '.$user.' '.$passwd;
 		return $this->run($command);
 	}
 	public function delete($passwdFile, $user){
 		$command = $this->getBinPath().'htpasswd'.$this->getSuffix().' -D '.$this->getPasswdName($passwdFile).' '.$user;
 		return $this->run($command);
 	}
 	protected function getPasswdName($passwdFile){
 		$config = $this->getConfig();
 		if(isset($config['passwd_name'])){
 			return  $passwdFile.DS.$config['passwd_name'];
 		}
 		return $passwdFile.DS.'.passwd';
 	}
 }