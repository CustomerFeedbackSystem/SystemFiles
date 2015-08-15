<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//determine the salutation to the customer
if (strlen($fet_ov['sendername']) < 2)
	{
	$sig="Customer";
	} else {
	$sig=$fet_ov['sendername'];
	}
	
//ensure that the WAG has set up his account number
$sql_phone="SELECT usrphone FROM usrac WHERE idusrac=".$_SESSION['MVGitHub_idacname']." LIMIT 1";
$res_phone=mysql_query($sql_phone);
$fet_phone=mysql_fetch_array($res_phone);

//check phone number format
if (strlen($fet_phone['usrphone'])==12)
	{
	$phone="0".substr($fet_phone['usrphone'],3,9);
	
	$sms_msg="[AUTO ALERT] Dear ".$sig.", regarding your Ticket Number ".$fet_ov['refnumber']." - ".$fet_ov['tktcategoryname'].", if UNSATISFIED, please contact ".$_SESSION['MVGitHub_usrfname']." (WAG Member) on ".$phone."";
	} else {
	
	$error_phonemissing="<span class=\"msg_warning\">".$msg_error_phonemissing."</span>";
	
	}

if ( (!isset($error_phonemissing)) && (isset($_GET['smsaction'])) && ($_GET['smsaction']=="send") )
	{
	//first, check if the message has been sent before from the activity table
	$sql_check="SELECT activity_date FROM tktactivity WHERE idtktactivitytype=6 AND idtktinPK=".$_SESSION['tktid']." ORDER BY activity_date DESC LIMIT 1 ";
	$res_check=mysql_query($sql_check);
	$fet_check=mysql_fetch_array($res_check);
	$num_check=mysql_num_rows($res_check);
	 
	if ($num_check > 0) //if a record exists, then let's examine it
		{
			//when was the last communication... shoudl be at least 24 hours old
			//24 hours ago is
			$hourdiff=(strtotime($timenowis) - strtotime($fet_check['activity_date']));//calculate time diff
			
			if ($hourdiff < 86400)
				{
				
				$error_hourdiff="<span class=\"msg_warning\">".$msg_warning_nomsg_24hrs."</span>";
				
				}
				
		}
	
	if ( (!isset($error_hourdiff)) && (!isset($error_phonemissing))  )
		{
		//log this as an activity
		$sql_logactivity="INSERT INTO tktactivity (idtktesclevel, idtktinPK, idtktactivitytype, activity_date, activity_details, entered_by, entered_by_role, addedby, addedon, modifiedby, modifiedon) 
		VALUES ('1', '".$_SESSION['tktid']."', '6', '".$timenowis."', 'Sent AUTO SMS alert invitation to Customer','".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$_SESSION['MVGitHub_idacname']."','".$timenowis."', '0000-00-00 00:00:00','0')";
		mysql_query($sql_logactivity);
					
		//insert into sms out table
		$sms_insert="INSERT INTO request (Number,shortcode,Message,clicked) 
		VALUES ('".$fet_ov['senderphone']."','6254','".$sms_msg."','0')";
		mysql_query($sms_insert);
				
		$msg_ok="<span class=\"msg_success\">".$msg_changes_saved."</span>";
		
		$success=1;
		}
			
	}


?>
<div class="table_header">
<?php echo $lbl_smsauto;?>
</div>
 	<?php
	if (!isset($error_phonemissing)) { 
	?>
<div class="msg_instructions">
<?php echo $ins_autosms;?>
</div>
	<?php
    }
	?>
<div style="padding:10px">
<?php 
if (isset($msg_ok)) { echo $msg_ok;}
if (isset($error_hourdiff)) { echo $error_hourdiff;}
if (isset($error_phonemissing)) { echo $error_phonemissing;}
?>
</div>
<div>
<?php
if (!isset($success))
	{
?>
<table width="446" height="25" border="0" cellpadding="2" cellspacing="0">
	<tr>
    	<td colspan="2">
       <div style="width:300px;" class="text_small_bold">
       <?php if (isset($sms_msg)) { echo $sms_msg; }?>
       </div>
        </td>
    </tr>
    <?php
	if (!isset($error_phonemissing)) { 
	?>
    <tr>
    	<td height="55">        </td>
    	<td height="55">
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?smsaction=send" onclick="return confirm('<?php echo $msg_prompt_sure_proceed;?>')" id="button_send"></a>
        </td>
    </tr>
    <?php
	}
	?>
</table>
<?php
}
?>
</div>