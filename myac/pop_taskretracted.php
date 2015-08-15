<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<center>
<div class="msg_warning" style="text-align:center; width:300px; margin:30px; padding:100px"> 
<div>This Task is now back in your Tasks IN</div>
<div style="padding:30px 0px 0px 90px; text-align:center">
<a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
</div>
</div>
</center>
</body>
</html>
