<?php
use app\model\ApacheValue;
/*
 * @author shipSun
 */
if (!function_exists('getPathName')) {
	function getPathName($key){
		$data = ApacheValue::instance()->getData();
		$data = $data['path'];
		if(isset($data[$key])){
			return $data[$key];
		}
		return '';
	}
}