<div class="tpl-left-column">
	<ul>
	<?php foreach ($nav_items as $nav): ?>
		<li class="list-item-<?php echo $nav->level?>"><?php echo $nav->name ?></li>	
	<?php endforeach ?>
	</ul>
</div>