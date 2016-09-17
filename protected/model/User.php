<?php

/**
 * Class User
 * @author lizuodu
 */
class User extends Model {
	private $_link;
	public function __construct($link) {
		$this->_link = $link;
	}
	
	/**
	 * 根据用户名获取用户对应的信息
	 * 
	 * @param string $username        	
	 * @param string $password        	
	 * @return mixed array|null
	 */
	public function getUserInfoByName($username, $password) {
		$userInfo = null;
		try {
			// 从数据库中获取人员对应的数据行
			$sql = 'SELECT id,username,password,email,website,profile 
					FROM tbl_user WHERE username=:username AND password=:password LIMIT 0,1;';
			$stmt = $this->_link->prepare ( $sql );
			$stmt->bindValue ( ':username', $username, PDO::PARAM_STR );
			$stmt->bindValue ( ':password', md5 ( $password ), PDO::PARAM_STR );
			$stmt->execute ();
			$userInfo = $stmt->fetchAll ();
		} catch ( PDOException $ex ) {
			$this->zprint ( '程序执行出错：' . $ex );
		}
		return $userInfo;
	}
}