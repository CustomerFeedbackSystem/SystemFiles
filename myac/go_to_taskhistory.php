<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

//if (isset($_GET['tkt'])) { echo "<br><br><br><br>--->".$_GET['tkt']; }

//ADDITION TO CATER FOR SECONDARY COMPLAINTS HISTORY --- BY DICKSON ON JULY 25TH 2014
if (isset($_GET['tkt_st']))
	{
	$_SESSION['tkt_sectkt']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_GET['tkt_st'])));
	}

if (isset($_GET['task_st']))
	{
	$_SESSION['tsk_sectkt']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_GET['task_st'])));
	}

//check submitted urls for id and title
//if (isset($_GET['tkt']))
	//{
	$tktid=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_GET['tkt'])));
	
	//get the task id
	$sql_taskid="SELECT idwftasks,tktin_idtktin FROM wftasks WHERE tktin_idtktin=".$tktid." ORDER BY idwftasks DESC LIMIT 1";
	$res_taskid=mysql_query($sql_taskid);
	$fet_taskid=mysql_fetch_array($res_taskid);
	//echo "<br><br><br><br>".$sql_taskid;
	echo "... one moment ...";
	//}
	
$_SESSION['tktid']=$fet_taskid['tktin_idtktin'];
$_SESSION['wtaskid']=$fet_taskid['idwftasks'];	
	
if (isset($_GET['title']))
	{
	$tkttitle=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_GET['title'])));
	}

if (isset($_GET['tktcat']))
	{
	$tktcat=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_GET['tktcat'])));
	}	
	
if (isset($_GET['duplicate']))
	{
	$duplicate="&duplicate=1";
	} else {
	$duplicate="";
	}
//echo $tktid."-".$tkttitle;
//exit;
//echo $fet_taskid['idwftasks'];
//exit;
?>
<script language="javascript">
window.location='pop_viewtaskhistory.php?task=<?php echo $fet_taskid['idwftasks'].$duplicate;?>';
</script>