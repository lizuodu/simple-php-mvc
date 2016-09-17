<?php require_once Application::App()->widget->header; ?>

<div id="content">
	<!-- breadcrumb -->
	<b><a href="<?php echo $webapp->url; ?>">首页</a></b> &gt; <b><?php echo $data['tagname']; ?></b>
	<ul id="list">
        <?php 
        $li = ''; 
        foreach ($data['list'] as $l) { 
        	$li .= "<li>[{$l->create_time}] <a title='{$l->title}' href='post-show-id-{$l->id}.html'>";
        	$li .= "{$l->title}</a></li>";
        } 
        echo $li;
        ?>
    </ul>
</div>

<?php require_once Application::App()->widget->footer; ?>








