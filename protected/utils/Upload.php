<?php

/**
 * 文件上传类
 * @author lizuodu
 */
class Upload extends Base {
	
	public function __construct($date) {
		// var_dump($_FILES);
		// 获取文件信息
		$filename = $_FILES ['filename'] ['name'];
		$filetype = $_FILES ['filename'] ['type'];
		$filesize = $_FILES ['filename'] ['size'];
		$tmp_name = $_FILES ['filename'] ['tmp_name'];
		
		// 判断文件大小不超过2MB
		if ($filesize > (2 * 1024 * 1024)) {
			throw new Exception ( '文件大小不能操过2MB' );
		}
		
		// 判断文件上传成功
		if (! is_uploaded_file ( $tmp_name )) {
			throw new Exception ( '文件上传失败' );
		}
		
		// $date = date('Y/m/d');
		$filepath = Application::App ()->basePath . '/../' . Application::App ()->webApp->uploadPath . $date; // assets/upload
		if (! file_exists ( $filepath )) {
			mkdir ( $filepath, 0755, true );
		}
		
		$dot = strpos ( $filename, '.' );
		$suffix = substr ( $filename, $dot );
		$filename = iconv ( 'utf-8', 'gb2312', substr ( $filename, 0, $dot ) ) . uniqid () . $suffix;
		move_uploaded_file ( $tmp_name, $filepath . '/' . $filename );
		
		$url = Application::App ()->webApp->url . '/' . Application::App ()->webApp->uploadPath . '/' . $filename;
		
		// echo "{'url':{$url}}";
		echo json_encode ( $filename );
	}
}


