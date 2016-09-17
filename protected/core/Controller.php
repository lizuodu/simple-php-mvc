<?php

/**
 * Class Controller
 * 所有控制器的基类
 * 负责建立数据库连接，为view加载相应的model
 * @author lizuodu
 * @link http://lizuodu.com
 */
class Controller extends Base {
	/**
	 *
	 * @var object 数据库连接
	 */
	public $link = null;
	
	/**
	 *
	 * @var string 数据库类型
	 */
	public $dbType = null;
	
	/**
	 *
	 * @var string 数据库名称
	 */
	public $dbName = null;
	
	/**
	 *
	 * @var string 应用根目录
	 */
	public $basePath = null;
	
	/**
	 *
	 * @var string 数据库主机IP地址
	 */
	public $dbHost = null;
	
	/**
	 *
	 * @var string 数据库登录账号
	 */
	public $dbUser = null;
	
	/**
	 *
	 * @var string 数据库登录密码
	 */
	public $dbPassword = null;
	
	/**
	 * 建立数据库连接，将连接$link传入Model
	 */
	public function __construct() {
		$this->createDataBaseLink ();
	}
	
	/**
	 * 建立数据库连接
	 */
	public function createDataBaseLink() {
		try {
			$this->dbType = Application::App ()->db->type;
			if (empty ( $this->dbType ))
				return;
			$this->dbName = Application::App ()->db->dbName;
			$this->basePath = Application::App ()->basePath;
			$this->dbHost = Application::App ()->db->host;
			$this->dbUser = Application::App ()->db->loginName;
			$this->dbPassword = Application::App ()->db->loginPass;
		} catch ( Exception $e ) {
			$this->zprint ( '文件main.php缺少配置节点，详细信息如下：' . $e->getMessage () );
		}
		if ($this->dbType == 'sqlite') {
			require $this->basePath . '/core/DataAccess/SQLiteHelper.php';
			$sqlite = new SQLiteHelper ( $this->dbName );
			$this->link = $sqlite->db;
		} else if ($this->dbType == 'mysql') {
			require $this->basePath . '/core/DataAccess/MySQLHelper.php';
			$mysql = new MySQLHelper ( $this->dbHost, $this->dbName, $this->dbUser, $this->dbPassword );
			$this->link = $mysql->db;
		}
	}
	
	/**
	 * 加载相应的model
	 * 
	 * @param
	 *        	string model 模型名称
	 * @return object model 模型对象
	 */
	public function loadModel($model) {
		// 如果使用数据库
		if (! empty ( Application::App ()->db->type )) {
			// 判断连接已经创建
			if ($this->link == null) {
				$this->zprint ( "{$model}数据库连接未创建", '' );
			}
		}
		require Application::App ()->basePath . '/model/' . $model . '.php';
		// 返回包含数据库连接的model对象
		return new $model ( $this->link );
	}
	
	/**
	 * 渲染相应的视图
	 * 
	 * @param string $controller        	
	 * @param string $view        	
	 * @param mixed $data        	
	 */
	public function render($controller, $view, $data = null) {
		if (is_array ( $data )) {
			/**
			 * 传递数据到视图
			 * 将数组变为变量列表，键作为变量名，
			 * 转换同时检查键是否重复，如果重复，则加上前缀data
			 */
			extract ( $data, EXTR_PREFIX_SAME, 'data' );
		}
		require $this->basePath . '/view/' . $controller . '/' . $view . '.php';
	}
	
	/**
	 * 重定向到$url 
	 * @param string $url 
	 */
	public function redirect($url) {
		header("Location: {$url}");
		exit();
	}
	
} 

