<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['task']))
	{
	$_SESSION['batch_taskid']=mysql_escape_string(trim($_GET['task']));
	}
	
if (isset($_GET['tkt']))
	{
	$_SESSION['batch_tktid']=mysql_escape_string(trim($_GET['tkt']));
	}	
	
if  ( (isset($_GET['confirm_removal'])) && ($_GET['confirm_removal']="OK") )
	{
	
$sql_batch_perms="SELECT permview,perminsert,permupdate,permdelete,mobile_access,global_access 
FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$submodule_batching." 
AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
$res_batch_perms=mysql_query($sql_batch_perms);
$num_batch_perms=mysql_num_rows($res_batch_perms);
$fet_batch_perms=mysql_fetch_array($res_batch_perms);	

if ($fet_batch_perms['permdelete']==0)
	{
	echo "<div style=\"color:#cc0000;\">Permission Denied - You don't have adequate permissions to carry this activity.<br>
	This incident has been logged. </div>";
	exit;
	}
	
	mysql_query("BEGIN");
	
/*	commented because its obslete
	//get the values
		$sql_check="SELECT wftasks_batch_idwftasks_batch,batch_number FROM wftasks WHERE idwftasks=".$_SESSION['batch_taskid']."";
		$res_check=mysql_query($sql_check);
		$fet_check=mysql_fetch_array($res_check);
		
		//new value for count
		if ($fet_check['batch_number'] > 0)
			{
			$newval=($fet_check['batch_number'] - 1);
			}
		
		//update the wftasks
		$sql_updatetask="UPDATE wftasks SET wftasks_batch_idwftasks_batch='0', batch_number='0' WHERE idwftasks=".$_SESSION['batch_taskid']."  ";
		$res_updatetask=mysql_query($sql_updatetask);*/
		//echo $sql_updatetask;
		//update the batch number
		
		//get the current wftaskbatchno
		$res_batchno=mysql_query("SELECT wftasks_batch_idwftasks_batch FROM tktin WHERE idtktinPK=".$_SESSION['batch_tktid']."");
		$fet_batchno=mysql_fetch_array($res_batchno);
		
		//added new caculatinon
		$sql_check="SELECT count(*) as vouchers,voucher_number
		 FROM tktin 
		WHERE wftasks_batch_idwftasks_batch=".$fet_batchno['wftasks_batch_idwftasks_batch']." GROUP BY wftasks_batch_idwftasks_batch";
		$res_check=mysql_query($sql_check);
		$num_check=mysql_num_rows($res_check);
		$fet_check=mysql_fetch_array($res_check);
	//	echo $sql_check;
		if ($num_check>0)
			{
			
			$newval=($fet_check['vouchers']-1);
			
			$sql_updatecount="UPDATE wftasks_batch SET countbatch='".$newval."' WHERE idwftasks_batch=".$fet_batchno['wftasks_batch_idwftasks_batch']."";
			$res_updatecount=mysql_query($sql_updatecount);
			
			//get the tktid to update the tktin as well
			$sql_tktin=mysql_query("SELECT tktin_idtktin FROM wftasks WHERE idwftasks=".$_SESSION['batch_taskid']." ");
			$fet_tktin=mysql_fetch_array($sql_tktin);
					
					if ($fet_tktin['tktin_idtktin']>0)
						{
						//update the tkt as well
						$sql_batchtkt="UPDATE tktin SET 
						wftasks_batch_idwftasks_batch='0',
						batch_number='0',
						voucher_number='0'
						WHERE idtktinPK=".$fet_tktin['tktin_idtktin']."";
						$res_batchtkt=mysql_query($sql_batchtkt);
						}
		//echo "1.".$res_check."<br>2.".$res_updatetask."<br>3.".$res_updatecount."<br>4.".$sql_tktin."<br>5.".$res_batchtkt;
			if ( ($res_check) && ($res_updatecount) && ($sql_tktin) && ($res_batchtkt) )
					{
					mysql_query("COMMIT");	
			
					header('location:pop_batch_processed.php');
					exit;
					
					} else {
					
					echo "<script language=\"javascript\">alert('Oops! Unable to batch. Try again');</script>";
					
					}
		}//confirmed record exists
		
	} //confirmed that its OK to delete
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="padding:40px">
	<div class="msg_warning" style="text-align:center">
    Are you sure you want to remove this Record from the Batch ?
    </div>
    <div style="text-align:center; padding:0px 0px 0px 100px">
    <table border="0">
    	<tr>
        	<td>
    <a href="<?php echo $_SERVER['PHP_SELF'];?>?confirm_removal=ok" id="button_ok"></a>
    		</td>
            <td>
    <a href="#" onClick="parent.tb_remove();" id="button_cancel"></a>
    		</td>
           </tr>
           </table>
    </div>
</div>
</body>
</html>
