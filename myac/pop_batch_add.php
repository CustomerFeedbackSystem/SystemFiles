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

$sql_batch_perms="SELECT permview,perminsert,permupdate,permdelete,mobile_access,global_access 
FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$submodule_batching." 
AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
$res_batch_perms=mysql_query($sql_batch_perms);
$num_batch_perms=mysql_num_rows($res_batch_perms);
$fet_batch_perms=mysql_fetch_array($res_batch_perms);	

if ($fet_batch_perms['perminsert']==0)
	{
	echo "<div style=\"color:#cc0000;\">Permission Denied - You don't have adequate permissions to carry this activity.<br>
	This incident has been logged. </div>";
	exit;
	}

//process form
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="assign_batch") )
	{
	//clean up values
	$var_batch=mysql_escape_string(trim($_POST['batch']));
	$var_batchtype=mysql_escape_string(trim($_POST['batch_type']));
	
	//validate
	if ($var_batch < 1)
		{
		$error_1="<div class=\"msg_warning\">Please select valid Batch Number</div>";
		}
	
	if ($var_batchtype < 1)
		{
		$error_2="<div class=\"msg_warning\">Please select valid Batch Type</div>";
		}
		
	//process the entry
	if ( (!isset($error_1)) && (!isset($error_2))  )
		{
		mysql_query("BEGIN");
		
			//first, lets check if this ticket already belonged to another batch before removing it
			$res_tktin=mysql_query("SELECT idtktinPK,tktcategory_idtktcategory,wftasks_batch_idwftasks_batch FROM tktin WHERE idtktinPK=".$_SESSION['batch_tktid']." ");
			$fet_tktin=mysql_fetch_array($res_tktin);
					
			if ($fet_tktin['wftasks_batch_idwftasks_batch']>0)
				{
				//update the tkt as well
				$sql_batchtkt="UPDATE tktin SET 
				wftasks_batch_idwftasks_batch='0',
				batch_number='0',
				voucher_number='0'
				WHERE idtktinPK=".$fet_tktin['idtktinPK']."";
				$res_batchtkt=mysql_query($sql_batchtkt);
				} else {
				$res_tktin=mysql_query("SELECT idtktinPK FROM tktin WHERE idtktinPK=".$_SESSION['batch_tktid']."");
				}
	
			//check the last batch_no
			$res_batchmeta=mysql_query("SELECT usrteamzone_idusrteamzone,wftasks_batchtype_idwftasks_batchtype FROM wftasks_batch WHERE idwftasks_batch=".$var_batch."");
			$fet_batchmeta=mysql_fetch_array($res_batchmeta);
			//changed to get the last max id given for this batch
//			$sql_lastbatchno="SELECT max(voucher_number) as countbatch FROM tktin WHERE wftasks_batch_idwftasks_batch=".$var_batch."";
			$sql_lastbatchno="SELECT max(tktin.voucher_number) as countbatch,wftasks_batch.wftasks_batchtype_idwftasks_batchtype,wftasks_batch.batch_year FROM tktin
			INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
			WHERE wftasks_batch.wftasks_batchtype_idwftasks_batchtype=".$fet_batchmeta['wftasks_batchtype_idwftasks_batchtype']."
			AND wftasks_batch.usrteamzone_idusrteamzone=".$fet_batchmeta['usrteamzone_idusrteamzone']."
			AND wftasks_batch.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  ";//AND YEAR(createdon)='".$this_year."'
			$res_lastbatchno=mysql_query($sql_lastbatchno);
			$fet_lastbatchno=mysql_fetch_array($res_lastbatchno);
			
		//	echo $sql_lastbatchno;
			//quick validation to avoid crossing over to another year in an older batch
		//	echo $fet_lastbatchno['batch_year']."!=".$this_year;
			if ( ($fet_lastbatchno['countbatch']!='') && ($fet_lastbatchno['batch_year']!=$this_year) )
				{
				$error_batchoutdated="<div style=\"color:#ff0000\">You can't assign ".$fet_lastbatchno['batch_year']." in ".$this_year."  </div>";
				echo $error_batchoutdated;
				exit;
				}
			
			//create the new batch_no
			$new_batchno=($fet_lastbatchno['countbatch']+1);
			//echo $sql_lastbatchno."<br><br>";
		//	echo $new_batchno;
		//	exit;
			//update the task
			/*$sql_update="UPDATE wftasks SET 
			wftasks_batch_idwftasks_batch='".$var_batch."',
			batch_number='".$new_batchno."'
			WHERE idwftasks=".$_SESSION['batch_taskid']."";
			$res_update=mysql_query($sql_update);
			*/		
			
			//update the batch_no meta table
			$sql_updatecount="UPDATE wftasks_batch SET countbatch=(countbatch+1) WHERE idwftasks_batch=".$var_batch."";
			$res_updatecount=mysql_query($sql_updatecount);
			
			//get the tktid to update the tktin as well
			$sql_tktin=mysql_query("SELECT tktin_idtktin FROM wftasks WHERE idwftasks=".$_SESSION['batch_taskid']." ");
			$fet_tktin=mysql_fetch_array($sql_tktin);
			
			if ($fet_tktin['tktin_idtktin']>0)
				{
				//update the tkt as well
				$sql_batchtkt="UPDATE tktin SET 
				wftasks_batch_idwftasks_batch='".$var_batch."',
				batch_number='".$new_batchno."',
				voucher_number='".$new_batchno."'
				WHERE idtktinPK=".$fet_tktin['tktin_idtktin']."";
				$res_batchtkt=mysql_query($sql_batchtkt);
				}
			
			//we need now to run the transactino or throw an error
			if ( ($res_tktin) && ($res_lastbatchno) && ($res_updatecount) && ($sql_tktin) && ($res_batchtkt) && ($res_batchmeta) )
				{
				mysql_query("COMMIT");	
		
				header('location:pop_batch_processed.php');
				exit;
				
				} else {
				
				echo "<script language=\"javascript\">alert('Oops! Unable to batch. Try again');</script>";
				
				}
		
		} //is valid
	
	} //is submitted
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
function getAJAXHTTPREQ() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }
		
	
	function getprofile(batchId) {		
		
		var strURL="findbatch.php?batch="+batchId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('batchdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
</script>	
</head>
<body>
<div>
	<div  class="tbl_sh">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
  		<tr>
        	<td width="60%" class="text_small_bold">
            Add to Batch
       		</td>
          	<td align="right" width="40%">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td>
						<a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                        </td>
					</tr>
				</table>
            </td>
      </tr>
    </table>
    </div>
    <div>
    <?php
	if (isset($error_1)) { echo $error_1; }
	if (isset($error_2)) { echo $error_2; }
	?>
    </div>
    <div>
    	<form method="post" action="" name="batchform">
        	<table border="0" width="100%" cellpadding="2" cellspacing="0">
            	<tr>
                	<td height="40" class="tbl_data" style="padding:0px 0px 0px 10px">
                    <strong>
                   	Select Batch Type
                    </strong>
                    </td>
                    <td height="40" class="tbl_data">
                <?php
			   $sql_batchtype="SELECT idwftasks_batchtype,batchtypelbl,batchtypedesc FROM wftasks_batchtype";
			   $res_batchtype=mysql_query($sql_batchtype);
			   $fet_batchtype=mysql_fetch_array($res_batchtype);
			   ?>
               <select name="batch_type" id="batch_type" onChange="getprofile(this.value)">
               <option value="0">---</option>
               <?php
			   do {
			   ?>
               <option value="<?php echo $fet_batchtype['idwftasks_batchtype'];?>" title="<?php echo $fet_batchtype['batchtypedesc'];?>"><?php echo $fet_batchtype['batchtypelbl'];?> - <?php echo $fet_batchtype['batchtypedesc'];?></option>
               <?php
			   	} while ($fet_batchtype=mysql_fetch_array($res_batchtype));
			   ?>
               </select>
                  </td>
                </tr>
                <tr>
                	<td height="40" class="tbl_data" style="padding:0px 0px 0px 10px"><strong>
                   Select Batch Number</strong></td>
                    <td height="40" class="tbl_data">
                    <div id="batchdiv">
                    <select name="batch"><option value="">----</option></select>
                    </div>
                  </td>
                </tr>
                <tr>
                	<td height="50" class="tbl_data"></td>
                    <td class="tbl_data">
                    <input type="hidden" value="assign_batch" name="formaction" />
                    <a href="#"  onclick="document.forms['batchform'].submit()" id="button_newbatch"></a>
                    </td>
                </tr>
          </table>
        </form>
    </div>
</div>
</body>
</html>
