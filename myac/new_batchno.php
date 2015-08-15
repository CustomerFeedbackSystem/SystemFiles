<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

//
if ( (isset($_POST['formaction'])) && ($_POST['formaction']==1) && ($_POST['chk']==0) )
	{
	//var
	$batcat=trim(mysql_escape_string($_POST['batch_type']));
	
	if ($batcat==0)
		{
		$error="<div class=\"msg_warning\">Please Select the Batch Category</div>";
		}
	
	if (!isset($error))
		{
		//then check the last batch number created for this Category+Zone
		$sql_lastno="SELECT batch_no FROM wftasks_batch WHERE wftasks_batchtype_idwftasks_batchtype=".$batcat." AND usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." ORDER BY batch_no DESC LIMIT 1";
		$res_lastno=mysql_query($sql_lastno);
		$fet_lastno=mysql_fetch_array($res_lastno);
		$num_lastno=mysql_num_rows($res_lastno);
		
		if ($num_lastno > 0)
			{
			$last_no=$fet_lastno['batch_no'];
			} else {
			$last_no=0;
			}
		
		//Create the new Batch
		$next_no = ($last_no+1);
		
		//check before inserting
		$sql_recent="SELECT TIMESTAMPDIFF(SECOND,createdon,'".$timenowis."') as lasttime FROM wftasks_batch WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftasks_batchtype_idwftasks_batchtype=".$batcat." ORDER BY  	idwftasks_batch DESC LIMIT 1";
		$res_recent=mysql_query($sql_recent);
		$num_recent=mysql_num_rows($res_recent);
		$fet_recent=mysql_fetch_array($res_recent);
//		echo $sql_recent;
	//	if ($fet_recent['lasttime'] > 60) //only add if not more than 1 minutes ago when a similar batch was added
	//		{
			//get prefix for batchtype
			$sql_batchtype="SELECT batchtypelbl FROM wftasks_batchtype WHERE idwftasks_batchtype=".$batcat." LIMIT 1";
			$res_batchtype=mysql_query($sql_batchtype);
			$fet_batchtype=mysql_fetch_array($res_batchtype);
			
			$batch_year=date("Y",time());
			$batch_month=date("m",time());
			
			$batch_verbose=$fet_batchtype['batchtypelbl']."/".$_SESSION['MVGitHub_regionpref']."/".$batch_year."/".$batch_month."/".str_pad($next_no, 4, '0', STR_PAD_LEFT);	
			
			$sql_new="INSERT INTO wftasks_batch (usrteamzone_idusrteamzone,wftasks_batchtype_idwftasks_batchtype,batch_no,batch_year,batch_month,batch_no_verbose,countbatch,createdby,createdon) 
			VALUES ('".$_SESSION['MVGitHub_userteamzoneid']."','".$batcat."','".$next_no."','".$batch_year."','".$batch_month."','".$batch_verbose."','0','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_new);
			
	//		} else {
	//		$error = "<div class=\"msg_warning\">A Batch for a similar Category was created a Minute ago</div>";
	//		}
		
		//Display the New Batch
		$sql_display="SELECT idwftasks_batch,batch_no,countbatch,batchtypelbl,region_pref FROM wftasks_batch
		INNER JOIN wftasks_batchtype ON wftasks_batch.wftasks_batchtype_idwftasks_batchtype=wftasks_batchtype.idwftasks_batchtype
		INNER JOIN usrteamzone ON wftasks_batch.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
		WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND wftasks_batchtype_idwftasks_batchtype=".$batcat." AND batch_no=".$next_no."  LIMIT 1 ";
		$res_display=mysql_query($sql_display);
		$fet_display=mysql_fetch_array($res_display);
		$num_display=mysql_num_rows($res_display);
	
		if ($num_display > 0)
			{
			$hideform=1;
			}
		}

	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
window.history.forward(1);
document.attachEvent("onkeydown", my_onkeydown_handler);
function my_onkeydown_handler()
{
switch (event.keyCode)
{
case 116 : // 'F5'
event.returnValue = false;
event.keyCode = 0;
//window.status = "We have disabled F5";
break;
}
}
document.onmousedown=disableclick;
status="Right Click is not allowed";
function disableclick(e)
{
if(event.button==2)
{
alert(status);
return false;
}
}
</script> 
</head>
<body bgcolor="#FFFFFF">
<div>
	<div class="tbl_h2">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
  		<tr>
        	<td width="60%">
            <div>
            New Batch No.
            </div>
       		</td>
          	<td align="right" width="40%">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td>&nbsp;</td>
                    	<td>&nbsp;</td>
                    	<td>
						<a href="#" onClick="parent.tb_remove();<?php if (isset($hideform)){ echo "parent.location.reload(1)";} ?>" id="button_closewin"></a>
                        </td>
					</tr>
				</table>
            </td>
      </tr>
    </table>
    </div>
    <div style="padding:5px 10px">
    <?php
	//display the error
	if (isset($error))
		{
		echo $error;
		}
	
	if (!isset($hideform))
		{
	?>
<form method="post" action="" name="newbatch">
    	<table border="0" width="450" cellpadding="2" cellspacing="0">
        	<tr>
            	<td colspan="2">
                <?php
				if (!isset($error))
					{
				?>
                <div class="msg_instructions">
                Select the Category for the New Batch and Click 'Submit'</div>
                <?php
				}
				?>
                </td>
            </tr>
        	<tr>
            	<td height="40" class="tbl_data" style="padding:0px 0px 0px 20px"><strong>
                Select Batch Category :                </strong></td>
              <td class="tbl_data">
               <?php
			   $sql_batchtype="SELECT idwftasks_batchtype,batchtypelbl,batchtypedesc FROM wftasks_batchtype";
			   $res_batchtype=mysql_query($sql_batchtype);
			   $fet_batchtype=mysql_fetch_array($res_batchtype);
			   ?>
               <select name="batch_type">
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
            	<td height="50"></td>
              <td>
              <input type="hidden" value="1" name="formaction" />
              <input type="hidden" value="<?php if (isset($hideform)) { echo $hideform; } else { echo "0"; }?>" name="chk" />
                <a href="#" onClick="document.forms['newbatch'].submit()" id="button_submit"></a>
                </td>
            </tr>
        </table>
        </form>
        <?php } ?>
        <?php 
		if ( (isset($hideform)) && ($hideform==1) )
			{
		?>
      <div style="padding:30px">
        <div class="msg_success_small">
        The New Batch  is :</div>
        <div class="title_header_blue">
        <img src="../assets_backend/images/icon_folder.jpg" border="0" align="left" style="padding:10px" />
        <h1>
        <?php 
		echo $batch_verbose;
//		echo $fet_display['region_pref']." / ".$fet_display['batchtypelbl']." - ".$fet_display['batch_no'];?>
        </h1>
        </div>
      </div>
        <?php
		}
		?>
  </div>
</div>
</body>
</html>
