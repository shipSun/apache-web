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
 	public function create($product, $user, $passwd){
 		$command = $this->getBinPath().'htpasswd'.$this->getSuffix().' -b';
 		if(!is_file($this->getPasswdName($product))){
 			$command.='c';
 		}
 		$command.= ' '.$this->getPasswdName($product).' '.$user.' '.$passwd;
 		return $this->run($command);
 	}
 	public function delete($product, $user){
 		$command = $this->getBinPath().'htpasswd'.$this->getSuffix().' -D '.$this->getPasswdName($product).' '.$user;
 		return $this->run($command);
 	}
 	protected function getPasswdName($product){
 		$config = $this->getConfig();
 		$product = $config['product_path'].DS.$product;
 		if(isset($config['passwd_name'])){
 			return  $product.DS.$config['passwd_name'];
 		}
 		return $product.DS.'.passwd';
 	}
 }