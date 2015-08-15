<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

//task
if (isset($_GET['task']))
	{
	$print_taskid=mysql_escape_string(trim($_GET['task']));
	}

if ($print_taskid > 0 )
	{
	//find out type of form
	$sql_frmtype="SELECT wftasks_batch.wftasks_batchtype_idwftasks_batchtype FROM wftasks 
	INNER JOIN wftasks_batch ON wftasks.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
	WHERE wftasks.idwftasks=".$print_taskid." LIMIT 1";
	$res_frmtype=mysql_query($sql_frmtype);
	$fet_frmtype=mysql_fetch_array($res_frmtype);
	
	require_once('print_form_'.$fet_frmtype['wftasks_batchtype_idwftasks_batchtype'].'.php');
	}
?>
