<div class="tpl-left-column">
	<ul>
	<?php foreach ($nav_items as $nav): ?>
		<?php if ($nav->level > 2) continue; ?>
		<li class="list-item-<?php echo $nav->level?>">
			<a href="<?php echo $nav->href ?>"><?php echo $nav->name ?></a>
		</li>	
	<?php endforeach ?>
	</ul>
</div>