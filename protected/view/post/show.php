<?php require_once Application::App()->widget->header; ?>

<!-- content -->
<div id="content">

	<?php $d = $content[0]; ?>

	<!-- breadcrumb -->
	<b><a href="<?php echo $webapp->url; ?>">首页</a></b> &gt; <b>文章正文</b>
        <h2 class="title"><?php echo $d->title; ?></h2>	
	<div class="info">
	<?php
		$ca = '<p>文章分类:';
		$tags = explode ( ',', $d->tags );
		$newTags = '';
		foreach ( $tags as $t ) {
			$t = trim ( $t, ' ' );
			$newTags .= "<a href='post-tag-{$t}.html' title='分类:{$t}'>{$t}</a>,";
		}
		$ca .= trim ( $newTags, ',' );
		$ca .= "&nbsp;&nbsp;创建日期:{$d->create_time}</p>";
		echo $ca;
	?>
	</div>
	<p>作者: <i>李坐都</i></p><?php require_once Application::App()->widget->bdshare; ?>
	<div class="entry"><?php echo $d->content; ?></div>
		
	<?php if ( $config->duoshuo ) { // 评论开始
		// 使用多说评论系统
		require_once Application::App()->widget->duoshuo;
	} else { ?>
	<div class="comments"><strong>评论</strong></div>
	<div class="comments">
        <?php foreach ($data['comment'] as $c): ?>
            <div style="border-bottom:1px dashed #6C6C6C;padding:3px;">
            <?php echo "<a href='#{$c->id}'>{$c->id}#</a>"; ?>
			昵称：<a href="<?php echo $c->url; ?>" target="_blank"><?php echo $c->author; ?></a>
			<span>&nbsp;评论日期：<?php echo $c->create_time; ?></span>
			<div id="comments-content">内容：<?php echo $c->content; ?></div>
			</div>
        <?php endforeach; ?>
    </div>
	<form id="comments-form">
		<fieldset>
			<div><label for="name">昵称</label><span class="require">*</span></div>
			<div><input class="input" id="name" name="name" type="text" maxlength="50" /></div>
			<div><label for="email">邮件</label></div>
			<div><input class="input" id="email" name="email" type="text" maxlength="50" /></div>
			<div><label for="website">博客</label></div>
			<div><input class="input" id="website" name="website" type="text" maxlength="50" /></div>
			<div><label for="comment">留言</label><span class="require">*</span></div>
			<div><textarea class="textarea" rows="5" id="comment" name="content"></textarea></div>
			<input type="hidden" id="pid" name="pid" value="<?php echo $d->id;?>" />
			<a href="javascript:;" id="imgmark">加载验证码</a> 
			<input class="input" type="text" id="mark" name="mark" /><span class="require">*</span>
			<div class="msg"></div>
			<input type="button" id="btnSubmit" value="提交">
		</fieldset>
	</form>    
	<?php } // 评论结束 ?>
        
</div>

<?php require_once Application::App()->widget->footer; ?>

