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
<p>You logged in</p>
</body>
</html>
