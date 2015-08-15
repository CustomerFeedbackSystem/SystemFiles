<?php
require_once('../../assets_backend/be_includes/config.php');
//require_once('../../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
/*if($_SERVER["HTTPS"] != "on") {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://secure552.websitewelcome.com/~maji/admin/a/");
   exit();
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="1800;URL=index.php" />
<title>Access Panel</title>
<link href="../../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
	<div><?php require_once('header.php');?></div>
    <div>
    	<table border="0" cellpadding="3" cellspacing="0" width="100%">
        	<tr>
            	<td width="50%" valign="top" style="padding:30px 10px 10px 10px" class="text_body">&nbsp;</td>
              <td width="50%" valign="top" style="padding:45px 20px 0px 10px" align="left">
            
						<div style="width:400px" class="msg_warning"><?php echo $msg_loginerror_4;?></div>
                       </td>
        	</tr>
        </table>
    </div>
    <div>
    </div>
</div>
</body>
</html>
