<?php

/**
 * Class Request
 * 对所有请求进行处理
 * @author lizuodu
 */
class Request {
	/**
	 * 过滤请求数据，一般使用GET请求时使用
	 * 
	 * @param string $value        	
	 * @return string $value
	 */
	public static function Filter($value) {
		if (! isset ( $value ) || empty ( $value ))
			return $value;
			
			// 替换SQL当中常用字符
		$sql_str = array (
				'SELECT',
				'INSERT',
				'UPDATE',
				'DELETE',
				'-- ',
				'`' 
		);
		$sql_str_taget = array (
				'',
				'',
				'',
				'',
				'',
				'' 
		);
		$value = str_ireplace ( $sql_str, $sql_str_taget, $value );
		
		// 替换HTML中常用字符
		// htmlentities转换所有的html标记,htmlspecialchars只格式化& ' " < 和 > 这几个特殊符号
		$value = htmlspecialchars ( $value );
		
		return $value;
	}
}


