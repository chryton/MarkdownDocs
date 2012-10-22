<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $doc_title; ?></title>
		<link href="<?php echo WEB_ROOT; ?>/assets/css/all.css" media="screen" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class="tpl-header">
			<h1><?php echo $doc_title; ?></h1>
		</div>
		<?php echo $yield; ?>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?php echo WEB_ROOT; ?>/assets/js/app.js" type="text/javascript" charset="utf-8"></script>
	</body>
</html>