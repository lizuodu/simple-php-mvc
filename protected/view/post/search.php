<?php require_once Application::App()->widget->header; ?>

<div id="content">
	<!-- breadcrumb -->
	搜索内容: <b><?php echo $data['keywords']; ?></b>
	<p>
	
	<ul>
        <?php foreach ($data['list'] as $l): ?>
            <li>
                [<?php echo $l->create_time; ?>]
				<a href="<?php echo '/post-show-id-'. $l->id; ?>.html"><?php echo $l->title?></a>
            </li>
        <?php endforeach; ?>
    </ul>
	</p>
</div>

<?php require_once Application::App()->widget->footer; ?>


