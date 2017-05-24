<?php
/*
 * @author shipSun <543999860@qq.com>
 */
 namespace app\htpasswd\dao;
 
 interface HtpasswdDaoInterface{
 	public function find($user, $path);
 	public function listUser($offset=0, $limit=1000);
 	public function insertUser($data);
 	public function deleteUser($path, $user);
 }