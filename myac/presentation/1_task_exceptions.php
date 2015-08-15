<?php
require_once('../../assets_backend/be_includes/config.php');

require_once('../../assets_backend/be_includes/check_login_easy.php');

$sql="INSERT INTO z_msgread (read_id,read_time) 
VALUES ('".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
mysql_query($sql);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
  <div style="background-color:#efefef">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  		<tr>
        	<td >&nbsp;</td>
        	<td align="right">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td>&nbsp;</td>
                        <td><a href="#" onClick="parent.tb_remove();parent.location.reload(1)" id="button_closewin"></a></td>
                    </tr>
              </table>
            	
            </td>
      </tr>
    </table>
    </div>
    <div>
    <img src="presentation_images/1_task_exceptions.gif" border="0" usemap="#Map" />
<map name="Map" id="Map"><area shape="rect" coords="348,356,702,391" href="2_task_exceptions.php" />
</map>
  </div>
</div>
</body>
</html>
