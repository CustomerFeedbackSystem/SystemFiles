<?php
require_once('../Connections/connSystem.php');

mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SMS Broadcasts</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="532" height="195" border="0" cellpadding="2" cellspacing="0">
<tr>
    	<td height="28" colspan="2" class="table_header">
        Broadcast SMS to <?php echo $_REQUEST['to'];?>        </td>
  </tr>
    <tr>
    	<td width="183" height="88" valign="top" class="text_body"><strong>
        Compose Message        </strong></td>
      <td width="339" valign="top">
      <textarea cols="40" rows="4"></textarea>      </td>
  </tr>
    <tr>
    	<td height="76">        </td>
      <td>
       <a href="#" id="button_submit"></a>
        </td>
    </tr>
    </table>
</body>
</html>
