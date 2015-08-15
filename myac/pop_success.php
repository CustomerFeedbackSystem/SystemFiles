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
<title>Window</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
  <div class="tbl_sh">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
                            <td>
                            <?php echo $lbl_wfbasicinfo; ?>
                            </td>
                            <td align="right" style="position:absolute; right:5px">
                            <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                            </td>
                        </tr>
    </table>
  </div>
    <div style="padding:50px; margin:50px; text-align:center">
    <span class="msg_success">
    <?php echo $msg_changes_saved;?>
    </span>
    </div>
</div>
</body>
</html>
