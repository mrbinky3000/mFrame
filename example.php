<?php

// init and start up mFrame
require_once('system/init_system.php');

// call the controller for this page
require_once('system/controllers/index_c.php');
$o_index = new indexController();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>mFrame Example</title>
</head>
<body>
<p>mFrame Example - <?php echo indexController::$s_today_date ?></p>
<p><?php echo indexController::$s_another_example; ?> This is an example of a page previously designed without mFrame. I've stripped
	out all the usual PHP from this file, and moved it to a controller. Now, the
	only PHP in this file is used to display template variables.</p>
	<form id="form1" name="form1" method="post" action="">
		<table border="0" cellspacing="1" cellpadding="3">
			<tr>
				<td><label>Login</label></td>
				<td><input name="login" type="text" id="login" size="30" maxlength="64" value="<?php echo fp('login') ?>"/></td>
			</tr>
			<tr>
				<td><label>Password</label></td>
				<td><input name="password" type="password" id="password" size="30" maxlength="128" value="<?php echo fp('password') ?>" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" id="submit" value="Submit" /></td>
			</tr>
		</table>
	</form>
	<p>Todo, improve this demo.</p>
</body>
</html>
