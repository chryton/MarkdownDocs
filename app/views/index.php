<div class="tpl-left-column">
	<ul>
	<?php foreach ($nav_items as $nav): ?>
		<?php if ($nav->level > 2) continue; ?>
		<li class="list-item-<?php echo $nav->level; ?>">
			<a href="<?php echo WEB_ROOT, $nav->href; ?>"><?php echo $nav->name; ?></a>
		</li>	
	<?php endforeach ?>
	</ul>
</div> <!-- /tpl-left-column -->
<div class="tpl-content">
	<div class="content">
		<?php echo $page->content; ?>
	</div> <!-- /content -->
	<div class="footer">
		Get this on <a href="https://github.com/sdover102/MarkdownDocs" target="_blank">Github</a>
	</div> <!-- /footer -->
</div> <!-- /tpl-content -->