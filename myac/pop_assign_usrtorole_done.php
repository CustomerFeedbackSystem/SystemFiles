<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
</head>
<body>
<div>
	<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="<?php echo $_SESSION['tb_width'];?>">
  		<tr>
        	<td >
           <?php echo $_SESSION['usrrolename_to']." Located at  ".$_SESSION['usrrolenamezone_to'];?>            </td>
        	<td align="right">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                        <td><a href="#" onClick="parent.tb_remove();parent.location.reload(1)" id="button_closewin"></a></td>
                    </tr>
                 </table>
            	
            </td>
      </tr>
    </table>
    </div>
    <div style="padding:95px 10px 10px 10px">
    	<div class="msg_success_small">
        Operation Succesfully Executed. </div>
        <div class="msg_instructions_small">
        <?php echo $_SESSION['user_designate']. " is now ".$_SESSION['usrrolename_to']." ( Located at  ".$_SESSION['usrrolenamezone_to'].")";?>
        </div>
    </div>
</div>    
</body>
</html>
