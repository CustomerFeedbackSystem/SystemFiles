<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

if (isset($_GET['idflow']))
	{
	$thislink=mysql_escape_string(trim($_GET['idflow']));
	}

//get the details of this task
$sql_task="SELECT idwftskflow, wftskflowname, wfsymbol_idwfsymbol,
(SELECT wftskflow_idwftskflow FROM wftasks WHERE wftasks.idwftasks =".$_SESSION['retask']." AND wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow) AS tskflowid
FROM wftskflow 
WHERE 
wftskflow.wfproc_idwfproc=".$_SESSION['thisWFsession']." 
ORDER BY wftskflow.listorder ASC";
$res_task=mysql_query($sql_task);
$num_task=mysql_num_rows($res_task);
$fet_task=mysql_fetch_array($res_task);	
//echo $thislink;
//echo $sql_task;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
<?php
do { 

	if (($fet_task['wfsymbol_idwfsymbol']!=1) && ($fet_task['wfsymbol_idwfsymbol']!=10) )
		{
			echo "<a style=\"text-decoration:none;";
					if ( (isset($thislink)) && ($thislink==$fet_task['idwftskflow']) )
						{
						echo "color:#ff0000";
						}
			echo "\" onclick=\"location.href='".$_SERVER['PHP_SELF']."?idflow=".$fet_task['idwftskflow']."';\" href=\"pop_taskreassign_admin_2.php?idflow=".$fet_task['idwftskflow']."\" target=\"reassign_2\">";
			echo "<div class=\"tbl_data\" ";
			echo " style=\"background-color:$mocolor ";
					if ( (isset($thislink)) && ($thislink==$fet_task['idwftskflow']) )
						{
						echo ";text-decoration:none;font-weight:bold\" ";
						} else {
						echo " \" ";
						}
			echo " onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\" ";
				
			echo ">[".$fet_task['idwftskflow']."] ".$fet_task['wftskflowname']."</div>";
			echo "</a>\r\n";
		} else {
			echo "<div class=\"tbl_data\" style=\"color:#cccccc\" ";
			echo " onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\" ";
			echo ">[".$fet_task['idwftskflow']."] ".$fet_task['wftskflowname']."</div>\r\n";
		}
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}

} while ($fet_task=mysql_fetch_array($res_task));
?>
</div>
</body>
</html>
