<?php
/*
 * @author shipSun <543999860@qq.com>
 */
 namespace app\htpasswd\dao;
 
 interface HtpasswdDaoInterface{
 	public function insertUser($passwdFile, $user, $passwd);
 	public function deleteUser($user);
 }