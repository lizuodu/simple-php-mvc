<?php
$config = Application::App ();
$webapp = Application::App ()->webApp;
$plugin = Application::App ()->plugin;

$content = !isset($data['content']) ? '' : $data['content']; // 文章内容

// 例行一些检查
require_once Application::App()->basePath . '/utils/check/IECheck.php';
$processor = new ProxyProcessor(new IECheck());
$this->zprintgo($processor->Check());

?>
<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0, width=device-width" />
	<!-- 
	
	这不是 Wordpress 搭建的，是自己用 PHP 写了一个 MVC 框架，从前端到后端一行一行码起来的，后台管理系统使用的 ExtJS5.x 来码的。
	
	博客虽然简陋，确是自己的宁静港湾，文章虽然写得浅显，却胜于不写。所谓，不积跬步，无以至千里。
	-->
	<meta name="description" content="记录并分享一些自己所学到的知识" />
	<meta name="keywords" content=".NET,PHP" />
	<meta name="author" content="李坐都" />
	<link rel="shortcut icon" type="image/ico" href="<?php echo $plugin->favicon; ?>" />
	<title><?php echo $webapp->name; ?></title>
	<link rel="stylesheet" href="<?php echo $plugin->css;?>" type="text/css" />
</head>
<body>
	<h1 class="top">
		<a href="/" id="tname"><?php echo $webapp->name; ?></a>
		<a href="javascript:;" id="tmenu"><div></div><div></div><div></div></a>
	</h1>
	<div id="colwrap">
		<div id="col1">
			<br/>
			<div id="left-nav">
				<?php 
				$sidebar = "<ul><li>标签</li>"; 
				foreach ($data['tag'] as $key=>$value) {
					$sidebar .= "<li><a title='{$key}' href='post-tag-{$key}.html'>{$key}({$value})</a></li>";
				} 
				$sidebar .= "</ul>";
				echo $sidebar;
				?>
				<br/><br/><br/>
			</div>
		</div>
		<div id="col2">
			<div id="col2n">
				
