<?php

/**
 * Class Application
 * 每一次请求的时候创建，作为应用的入口，
 * 功能：负责解析url，路由相应的controller和action等
 * @author lizuodu
 * @link http://lizuodu.com
 */
class Application extends Base {
	private $_controller = null; // 控制器
	private $_action = null;
	private $_paras = null; // 参数
	public static $config = null; // 配置文件实例
	
	/**
	 * 构造函数
	 */
	public function __construct() {
		// post-show-id-55.html
		// $uri = ! isset ( $_SERVER ['REQUEST_URI'] ) ? '' : $_SERVER ['REQUEST_URI'];
		// 路由url
		$url = ! isset ( $_SERVER ['QUERY_STRING'] ) ? '' : $_SERVER ['QUERY_STRING']; // url=home/login
		if (empty ( $url )) {
			$controller = self::$config->webApp->defaultController;
			require self::$config->basePath . '/controller/' . $controller . '.php';
			$home = new $controller ();
			$home->actionIndex ();
			exit ();
		}
		// echo $uri, "<br/>", $url;exit;
		$url = str_replace ( '?', '/', $url );
		$url = str_replace ( '&', '/', $url );
		$url = str_replace ( 'url=', '', $url );
		$url = str_replace ( '=', '/', $url );
	
		$url = explode ( '/', $url );
		if (count ( $url ) >= 2) {
			$this->_controller = ucfirst ( $url [0] ) . 'Controller';
			$this->_action = 'action' . ucfirst ( $url [1] );
			$this->_paras = array ();
			$length = count ( $url );
			$j = 0;
			for($i = 2; $i < $length - 1; $i ++) {
				$this->_paras [$j ++] = $url [$i + 1];
				$i ++;
			}
		}

		$root = self::$config->basePath;
		if (file_exists ( $root . '/controller/' . $this->_controller . '.php' )) {
			require $root . '/controller/' . $this->_controller . '.php';
			if (method_exists ( $this->_controller, $this->_action )) {
				if (! empty ( $this->_paras )) {
					// 包含参数的action
					$entity = new $this->_controller ();
					$callback = array (
							$entity,
							$this->_action 
					);
					@call_user_func_array ( $callback, $this->_paras );
				} else {
					$entity = new $this->_controller ();
					@$entity->{$this->_action} ();
				}
			} else {
				$this->zprint ( "<p style='color:red;font-weight:bold;'>action错误:-)</p>" );
			}
		} else {
			$this->zprint ( "<p style='color:red;font-weight:bold;'>controller错误:-)</p>" );
		}
	}
	
	/**
	 * 启动程序
	 */
	public static function Run($config) {
		$class = 'Base';
		self::$config = new $class ( $config );
		// 启动应用
		$app = new Application ();
	}
	
	/**
	 * 获取配置文件
	 */
	public static function App() {
		return self::$config;
	}
}



