<?php

/**
 * Class Base
 * @author lizuodu
 * @link http://lizuodu.com
 */
class Base {
	
	public function __construct($config = null) {
		if (is_string ( $config )) {
			$this->config ( require ($config) );
		}
	}
	
	/*
	 * public $seed = 1000; // 防止死循环，设置种子为1000
	 * public function config($config=null)
	 * {
	 * if(is_array($config))
	 * {
	 * foreach($config as $key=>$value)
	 * {
	 * if ($this->seed == 0)
	 * break;
	 * else
	 * {
	 * if (is_array($value))
	 * //$this->$key = (object)$value;
	 * $this->config($value);
	 * else
	 * $this->$key = $value;
	 * }
	 * $this->seed--;
	 * }
	 * }
	 * }
	 */
	
	/**
	 * 将配置文件中的数组键值对转换为对象的形式
	 * 不使用数组使用->方式请见上面递归方法
	 *
	 * @param
	 *        	s mixed $config 配置文件
	 */
	public function config($config = null) {
		if (is_array ( $config )) {
			foreach ( $config as $key => $value ) {
				if (! is_array ( $value ))
					$this->$key = $value;
				else {
					$this->$key = ( object ) $value;
				}
			}
		}
	}
	
	/**
	 * 输出信息并中断
	 * @param string $value
	 */
	public function zprint($value = '', $ex = '') {
		if (! isset ( $value ))
			return;
		header ( 'Content-Type: text/html;charset=utf-8' );
		if (Application::App ()->runPattern == 'Debug') {
			exit ( $value . $ex );
		} else {
			exit ( $value );
		}
	}
	
	/**
	 * 输出信息
	 * @param string $value
	 */
	public function zprintgo($value) {
		if (! isset ( $value ) )
			return;
		header ( 'Content-Type: text/html;charset=utf-8' );
		echo $value;
	}
	
}


