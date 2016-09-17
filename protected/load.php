<?php

/**
 * 程序主入口文件
 * @package mvc
 * @author lizuodu
 * @link http://lizuodu.com
 */
$protected = dirname ( __FILE__ );

$config = $protected . '/config/main.php';
// 加载配置文件
require_once $config;

// 加载框架核心文件
require_once $protected . '/core/Base.php';
require_once $protected . '/core/Application.php';
require_once $protected . '/core/Controller.php';
require_once $protected . '/core/Model.php';

Application::Run ( $config );




