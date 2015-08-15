<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body marginheight="0" marginwidth="0" >
<center>
<p class="text_small">
  <?php
if ( (isset($_GET['action'])) && ($_GET['action']=="send") )
	{
?>
   <span style="color:#00CC00;font-weight:bold">Sent</span>
  <?php
} else {
?>
 <a href="<?php $_SERVER['PHP_SELF']?>?action=send" style="color:#cc0000">Send Alert</a>
  <?php } ?>
</p>
</center>
</body>
</html>