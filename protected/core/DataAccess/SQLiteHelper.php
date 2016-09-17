<?php
require_once 'ISQL.php';
class SQLiteHelper implements ISQL {
	/**
	 * $var object 数据库连接对象
	 */
	public $db = null;
	
	/**
	 *
	 * @var string 数据库文件后缀
	 */
	public $dbSuffix = '.db3';
	
	/**
	 * 创建数据库文件
	 * 
	 * @param string $dbName
	 *        	数据库名称
	 */
	public function __construct($dbName) {
		try {
			// 建立数据目录
			$dataStore = Application::App ()->basePath . '/data/';
			if (! file_exists ( $dataStore ))
				mkdir ( $dataStore, 0777, true );
				
				// 创建或打开SQLite数据库文件
			$dbName = $dbName . $this->dbSuffix;
			$fileDB = new PDO ( 'sqlite:' . $dataStore . $dbName );
			
			// 设置异常信息等参数
			$fileDB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			
			$this->db = $fileDB;
			
			// 创建数据库及表结构
			// $this->CreateDBFromSQLFile('schema.sqlite.sql');
		} catch ( PDOException $e ) {
			exit ( $e->getMessage () );
		}
	}
	
	/**
	 * 执行没有返回值，也没有参数的SQL
	 * 
	 * @param string $sql        	
	 */
	public function ExecuteNonQuery($sql) {
		if ($this->db == null)
			return;
		try {
			$this->db->exec ( $sql );
		} catch ( PDOException $e ) {
			exit ( $e->getMessage () );
		}
	}
	
	/**
	 * 根据SQL文件创建数据库结构
	 * 文件脚本必须放置在/data/目录下，比如：data.sql
	 * 
	 * @param string $fileName
	 *        	数据库文件
	 */
	protected function CreateDBFromSQLFile($fileName) {
		if ($fileName == null)
			die ( "文件{$fileName}不能为Null" );
		
		$filePath = Application::App ()->basePath . '/data/' . $fileName;
		
		if (! file_exists ( $filePath ))
			return; // die("文件{$filePath}不存在");
		
		$encoding = mb_detect_encoding ( $filePath );
		$filePath = mb_convert_encoding ( $filePath, "UTF8", $encoding );
		
		$handle = fopen ( $filePath, "r" );
		if (! $handle)
			die ( "打开文件{$filePath}失败" );
		$fileContent = fread ( $handle, filesize ( $filePath ) );
		fclose ( $handle );
		
		$this->ExecuteNonQuery ( $fileContent );
	}
}




