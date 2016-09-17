<?php
/**
 * Class IECheck
 * 实现ICheckProcessor接口，检查IE浏览器
 * @author lizuodu
 */
require_once 'ICheckProcessor.php';
require_once 'ProxyProcessor.php';

class IECheck implements ICheckProcessor {
	
	/**
	 * 检查IE<=8
	 * @return string 
	 */
	public function Check() {
		$result = '';
		$msg = $_SERVER['HTTP_USER_AGENT']; 
		$str = strstr($msg, 'MSIE') ? true : false; 
		if($msg && $str){
			$ie = preg_split('/MSIE/', $msg); 
			if((int)floatval($ie['1']) <= 8){
				$ie = explode(';', $ie['1']);
				if (count($ie) > 0) {
					$result = "博客样式不完整支持IE{$ie[0]}及以下浏览器。";
				}
			}	
		}
		return $result;
	}
	

}

