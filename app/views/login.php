<div class="login">
	<h1>Login</h1>
	<p>You need a password to view these documents. You can enter that below.</p>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="password" name="password" value="" />
		<input type="submit" value="Submit" />
	</form>
</div> <!-- /login -->