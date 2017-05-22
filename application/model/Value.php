<?php
/*
 * @author shipSun
 */
 namespace app\model;
 
 class Value {
 	protected $data=[];
 	
 	protected function __construct(){
 		
 	}
 	public function getData(){
 		return $this->data;
 	}
 	public function __get($key){
 		if(isset($this->data[$key])){
 			return $this->data[$key];
 		}
 		return '';
 	}
 }