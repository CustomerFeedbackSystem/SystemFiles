<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login.php');

//get the value from the form
if (isset($_GET['fvid']))
	{
	$form_vid=mysql_escape_string(trim($_GET['fvid']));
	
	$sql_audit="SELECT value_choice_prev, value_choice_new, value_path_prev, value_path_new,audit_wfassetsdata.createdon,utitle,usrname,fname,lname FROM audit_wfassetsdata 
	 INNER JOIN usrac ON audit_wfassetsdata.createdby=usrac.idusrac
	WHERE idwfassetsdata=".$form_vid." AND tktin_idtktin=".$_SESSION['tktin_idtktin']."";
	//echo $sql_audit;
	$res_audit=mysql_query($sql_audit);
	$fet_audit=mysql_fetch_array($res_audit);
	$num_audit=mysql_num_rows($res_audit);
	
		if ($num_audit==0)
			{
			echo "Audit Trail Not Found";
			exit;
			}
	
	} else {
	echo "Fatal Error - Missing Parameter";
	exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
    	<td class="tbl_sh">
        Changed From
        </td>
        <td class="tbl_sh">
         To      </td>
      <td class="tbl_sh">
        By
        </td>
         <td class="tbl_sh">
        On
        </td>
    </tr>
    <?php do { ?>
    <tr>
    	<td class="tbl_data">
       <?php
	  echo $fet_audit['value_choice_prev'].$fet_audit['value_path_prev'];
	   ?>
        </td>
        <td class="tbl_data">
       <?php
	   echo $fet_audit['value_choice_new'].$fet_audit['value_path_new'];
	   ?>
        </td>
        <td class="tbl_data">
        <?php
		echo $fet_audit['utitle']." ".$fet_audit['fname']." ".$fet_audit['lname']." ( ".$fet_audit['usrname']." )";
		?>
        </td>
        <td class="tbl_data">
       <?php
	   echo $fet_audit['createdon'];
	   ?>
        </td>
    </tr>
    <?php
	} while ($fet_audit=mysql_fetch_array($res_audit));
	?>
</table>
</body>
</html>
