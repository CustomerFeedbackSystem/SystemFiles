<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');
//echo $_SESSION['idtktintrans'];
//check submitted urls for id and title
if (isset($_SESSION['idtktintrans']))
	{
	$tktid=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_SESSION['idtktintrans'])));
	
	//get the task id
	$sql_taskid="SELECT idwftasks,tktin_idtktin FROM wftasks WHERE tktin_idtktin=".$tktid." ORDER BY idwftasks DESC LIMIT 1";
	$res_taskid=mysql_query($sql_taskid);
	$fet_taskid=mysql_fetch_array($res_taskid);
	//echo $sql_taskid;
	//exit;
	}
	
if (isset($_GET['title']))
	{
	$tkttitle=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['title'])));
	}
	
if (isset($_GET['msg']))
	{
	$msg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['msg'])));
	}	
//echo $tktid."-".$tkttitle;
//exit;
$_SESSION['tktid']=$fet_taskid['tktin_idtktin'];
$_SESSION['wtaskid']=$fet_taskid['idwftasks'];
?>
<script language="javascript">
window.location='pop_viewtaskhistory.php?task=<?php echo $fet_taskid['idwftasks'];?>&msg=<?php echo $msg;?>';
</script>