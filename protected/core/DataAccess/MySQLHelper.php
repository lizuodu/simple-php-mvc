<?php
require_once 'ISQL.php';
class MySQL implements ISQL {
	/**
	 * $var object 数据库连接对象
	 */
	public $db;
	
	/**
	 * 创建数据库文件
	 * 
	 * @param string $dbHost
	 *        	数据库IP地址
	 * @param string $dbName
	 *        	数据库名称
	 * @param string $dbUser
	 *        	数据库登录账号
	 * @param string $dbPassword
	 *        	数据库登录密码
	 */
	public function __construct($dbHost, $dbName, $dbUser, $dbPassword) {
		try {
			$param = array (
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",
					PDO::ATTR_PERSISTENT => true 
			);
			$this->db = new PDO ( 'mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPassword, $param );
		} catch ( PDOException $e ) {
			exit ( $e->getMessage () );
		}
	}
}




