<?php

/**
 * main.php
 * 配置文件，所有的配置都应该写到这里
 * 使用如：Application::App()->basePath;
 * @author lizuodu 
 */
date_default_timezone_set ('Asia/Chongqing'); 

// return []; php5.4
return array (
		
		// 程序根目录
		'basePath' => dirname (__FILE__ ) . DIRECTORY_SEPARATOR . '..', 
		
		// 应用相关配置
		'webApp' => array (
				'name' => 'Lizuodu的博客', 
				'domain' => 'http://lizuodu.com',
				'url' => 'http://test.com/blog/',
				'defaultController' => 'PostController', 
				'uploadPath' => 'assets/upload/', 
				'logo' => '/assets/img/logo-height-60.png'
		), 
		
		// 数据库相关配置
		'db' => array (
				'type' => 'sqlite', 
				'host' => '127.0.0.1', // 有端口需带上端口号
				'dbName' => 'lizuodu890_blog', 
				'loginName' => '', 
				'loginPass' => ''
		), 
		
		// 页面小物件
		'widget' => array (
				'header' => 'protected/widget/header.php', 
				'footer' => 'protected/widget/footer.php', 
				'duoshuo' => 'protected/widget/duoshuo.php', // 多说社会化评论系统
				'bdshare' => 'protected/widget/bdshare.php'// 百度分享按钮
		), 
		
		// 开发(Debug)|发布(Release)
		'runPattern' => 'Release', 
		
		// 使用多说评论系统
		'duoshuo' => true, 
		
		// 插件相关
		'plugin' => require (dirname (__FILE__ )) . DIRECTORY_SEPARATOR . 'plugin.php'
); 



