<?php require_once Application::App()->widget->header; ?>

<?php $result = (array)$data['content']; // 文章结果集 ?>

<!-- content -->
<div id="content">
	<!-- breadcrumb -->
	<b><a href="<?php echo $webapp->url; ?>">首页</a></b> &gt; <b>最新列表</b>

	<?php 
		$ct = ''; 
		foreach ($result as $d) {
			$ct .= "<div class='list'>";
			$ct .= "<h2 class='title'><a href='post-show-id-{$d->id}.html' title='{$d->title}'>{$d->title}</a></h2>";
			$ct .= '<p>文章分类:';
			$tags = explode ( ',', $d->tags );
			$new_tags = '';
			foreach ( $tags as $t ) {
				$t = trim ( $t, ' ' );
				$new_tags .= "<a href='post-tag-{$t}.html' title='分类:{$t}'>{$t}</a>,";
			}
			$ct .= trim ( $new_tags, ',' );
			$ct .= "&nbsp;&nbsp;创建日期:{$d->create_time}</p><p>作者: <i>李坐都</i></p>";
		
			$tmp = explode ( '<div id="summary"> </div>', $d->content );
			if (count($tmp) <= 0) {
				$tmp = explode ( '<div id="summary"></div>', $d->content );
			}
			if (count ( $tmp ) > 1) {
				$ct .= $tmp [0];
			}
			else {
				$ct .= $d->content;
			}
		
			$ct .= "<p><a href='post-show-id-{$d->id}.html' class='more'>阅读全文 &gt;&gt;</a></p>";
			// $ct .= "<div class='postmetadata'><span>评论({$d->comment_count})</span></div>";
			$ct .= "</div>";
		} 
		echo $ct;
	?>  
</div>

<?php 
	if (count($result) <= 0) {
		echo "<h5>暂无最新文章...充电学习中...</h5>";
	}
?>

<?php require_once Application::App()->widget->footer; ?>


