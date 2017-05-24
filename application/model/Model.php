<?php
/*
 * @author shipSun
 */
 namespace app\model;
 
 class Model{
 	protected $data=[];
 	public function __construct(array $data=[]){
 		$this->data = $data;
 	}
 	public function setData(array $data){
 		$this->data = $data;
 	}
 	public function getData(){
 		return $this->data;
 	}
 	public function __set($key, $val){
 		$this->data[$key] = $val;
 	}
 	public function __get($key){
 		$fun = 'get'.ucfirst($key);
 		if(method_exists($this,$fun)){
 			return $this->$fun();
 		}
 		if(isset($this->data[$key])){
 			return $this->data[$key];
 		}
 		return '';
 	}
 }