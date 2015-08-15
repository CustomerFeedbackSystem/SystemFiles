<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

$sms_length_initial =100-16; //16 is for the ticket "Tkt No A-AAAA001, and 100 is less 60 for the Trailing Advert"
//how long is the company shortname
$sms_reduce_by = strlen($_SESSION['MVGitHub_userteamshortname']);

$sms_length=($sms_length_initial-$sms_reduce_by);

//limited to zone
if ((isset($_GET['do'])) && ($_GET['do']=="restrict_zone_notification") )
	{
	$turn_value=trim(mysql_escape_string($_GET['turn']));
	$sql_update_limit="UPDATE wfnotification SET limit_to_zone='".$turn_value."' WHERE wftskflow_idwftskflow=".$_SESSION['idflow']."";
	mysql_query($sql_update_limit);
	}

//list the current records
$sql_prenotification="SELECT idwfnotification,tktstatusname,usrrolename,usrrole_idusrrole,notify_system,notify_email,notify_sms,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification
INNER JOIN tktstatus ON wfnotification.tktstatus_idtktstatus=tktstatus.idtktstatus
INNER JOIN tktmsgs ON wfnotification.idwfnotification = tktmsgs.wfnotification_idwfnotification
INNER JOIN usrrole ON wfnotification.usrrole_idusrrole=usrrole.idusrrole
WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." ORDER BY wfnotification.createdon DESC";
$res_prenotification=mysql_query($sql_prenotification);
$fet_prenotification=mysql_fetch_array($res_prenotification);
$num_prenotification=mysql_num_rows($res_prenotification);

	//set session for this update
	if (isset($_GET['wfn']))
	{
	$_SESSION['notification'] = trim($_GET['wfn']);
	}

if ( (isset($_GET['do'])) && ($_GET['do']=="delete") )
	{
	$idnot=mysql_escape_string(trim($_GET['id']));
	//ensure this user owns this record
	$sql_checkuser = "SELECT idwfnotification FROM wfnotification 
	INNER JOIN wftskflow ON wfnotification.wftskflow_idwftskflow=wftskflow.idwftskflow 
	INNER JOIN wfproc ON wftskflow.wfproc_idwfproc = wfproc.idwfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1  ";
	$res_checkuser = mysql_query($sql_checkuser);
	$num_checkuser = mysql_num_rows($res_checkuser);
	
	if ($num_checkuser < 1) //if this record does not exist for this userteam, then error
		{
		$msg = "<span class=\"msg_warning\">".$msg_warn_contactadmin."</span>";
		} else { //else it is ok just delete
		
		$sql_delete="DELETE FROM wfnotification WHERE idwfnotification=".$idnot." LIMIT 1 ";		
		mysql_query($sql_delete);
		
		//also delete the tskstatus
		$sql_deletemsg="DELETE FROM tktmsgs WHERE wfnotification_idwfnotification=".$idnot." LIMIT 1";
		mysql_query($sql_deletemsg);
		
		$msg = "<span class=\"msg_success\">".$msg_success_delete."</span>";
		}
	}


//process new inputs
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save") )
	{
	$drole=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['usrrole'])));
	$dstatus=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['tskstatus'])));
	$demail=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['notify2'])));
	$dsms=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['notify1'])));
	$ddash=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['notify'])));
	
	if (isset($_POST['sys_msg'])){
		$dsysmsg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['sys_msg'])));
		} else {
		$dsysmsg="";
		}
	if (isset($_POST['sms_msg']))
		{	
		$dsmsmsg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['sms_msg'])));
		} else {
		$dsmsmsg="";
		}
	if (isset($_POST['email_msg']))
		{
		$demailmsg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['email_msg'])));
		} else {
		$demailmsg="";
		}

	if  (  ((isset($dsmsmsg)) && ($dsms=="")) || ((isset($dsysmsg)) && ($ddash=="")) || ((isset($demailmsg)) && ($demail==""))  )
		{
		$msg_error_content= "<span class=\"msg_warning\">".$msg_warn_misscontent."</span>";
		}
		
	//validate form inputs
	if ( (!isset($msg_error_content)) && ($drole >0) && ($dstatus>0) && ($demail!="") && ($dsms!="") && ($ddash!="") && ( ((isset($dsysmsg)) && (strlen($dsysmsg) > 0))  || ((isset($dsmsmsg))  && (strlen($dsmsmsg) > 0)) || ((isset($demailmsg))  && (strlen($demailmsg) > 0)) ) ) //since all the fields have a value
		{
		//first check if this record already exist
		$sql_notification = "SELECT idwfnotification,tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms FROM wfnotification 
		WHERE tktstatus_idtktstatus=".$dstatus." AND
		usrrole_idusrrole=".$drole." AND wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1 ";
		$res_notification = mysql_query($sql_notification);
		$num_notification = mysql_num_rows($res_notification);
	//	echo $sql_notification."<br><Br><br>";
	
		if ($num_notification==0)
			{
				$sql_notification = "INSERT INTO wfnotification (tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,createdby,createdon) 
				VALUES('".$dstatus."','".$drole."','".$_SESSION['idflow']."','".$ddash."','".$demail."','".$dsms."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
				mysql_query($sql_notification);
				//echo $sql_notification;
				//exit;
				//if the messages have been set, then insert them in the appropriate table
				if ( (strlen($dsysmsg)>0) || (strlen($dsmsmsg)>0) || (strlen($demailmsg)>0) )
					{
						
					//first, get this wfnotification id
					$sql_notificationid="SELECT idwfnotification FROM wfnotification WHERE createdby='".$_SESSION['MVGitHub_idacname']."' ORDER BY idwfnotification DESC LIMIT 1";
					$res_notificationid=mysql_query($sql_notificationid);
					$num_notificationid=mysql_num_rows($res_notificationid);
					$fet_notificationid=mysql_fetch_array($res_notificationid);
					
					mysql_free_result($res_notificationid);
					
					//then insert the tktmsg table
					$sql_newmsg="INSERT INTO tktmsgs ( wfnotification_idwfnotification, tktstatus_idtktstatus, usrteam_idusrteam,tktmsgsubject,tktmsg_sms,tktmsg_email,tktmsg_dashboard,createdby, createdon) 
					VALUES ('".$fet_notificationid['idwfnotification']."', '".$dstatus."', '".$_SESSION['MVGitHub_idacteam']."','New Notification','".$dsmsmsg."','".$demailmsg."','".$dsysmsg."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
					
					mysql_query($sql_newmsg);
					
					} //close if message has been set
				
				
				$msg_ok = "<span class=\"msg_success\">".$msg_changes_saved."</span>";
				
				} else {
				$msg_error_2= "<span class=\"msg_warning\">".$msg_warning_duplicate_wf."</span>";
				}
			} else {
			$msg_error= "<span class=\"msg_warning\">".$msg_warning_no_content."</span>";
			}
	
	}


//process updated inputs
if ((isset($_POST['formaction'])) && ($_POST['formaction']=="save_updates") )
	{

	
	$drole=mysql_escape_string(trim($_POST['usrrole']));
	$dstatus=mysql_escape_string(trim($_POST['tskstatus']));
	$demail=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['notify2'])));
	$dsms=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['notify1'])));
	$ddash=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['notify'])));
	
	if (isset($_POST['sys_msg'])){
		$dsysmsg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['sys_msg'])));
		} else {
		$dsysmsg="";
		}
	if (isset($_POST['sms_msg']))
		{	
		$dsmsmsg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['sms_msg'])));
		} else {
		$dsmsmsg="";
		}
	if (isset($_POST['email_msg']))
		{
		$demailmsg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['email_msg'])));
		} else {
		$demailmsg="";
		}
	
	if (  ((isset($dsmsmsg)) && ($dsms=="")) || ((isset($dsysmsg)) && ($ddash=="")) || ((isset($demailmsg)) && ($demail==""))  )
		{
		$msg_error_content= "<span class=\"msg_warning\">".$msg_warn_misscontent."</span>";
		}

	//validate form inputs
	if ( (!isset($msg_error_content)) && ($drole >0) && ($dstatus>0) && ($demail!="") && ($dsms!="") && ($ddash!="") && ( ((isset($dsysmsg)) && (strlen($dsysmsg) > 0))  || ((isset($dsmsmsg))  && (strlen($dsmsmsg) > 0)) || ((isset($demailmsg))  && (strlen($demailmsg) > 0)) ) ) //since all the fields have a value
		{

		//capture this role id first - there can only be one role to every notification per task/event		
		if ($drole != $fet_prenotification['usrrole_idusrrole']) 
			{
			//first check if this record already exist
			$sql_duplicate = "SELECT * FROM wfnotification WHERE usrrole_idusrrole=".$drole." AND wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1 ";
			$res_duplicate = mysql_query($sql_duplicate);
			$num_duplicate = mysql_num_rows($res_duplicate);
			
			if ($num_duplicate>0)
				{
				$isduplicate = 1;
				} else {
				$isduplicate = 0;
				}
			
			} else {
			$isduplicate = 0;
			}
		
		if ($isduplicate==0)
			{
				$sql_update = "UPDATE wfnotification SET 
				tktstatus_idtktstatus='".$dstatus."',
				usrrole_idusrrole='".$drole."',
				notify_system='".$ddash."',
				notify_email='".$demail."',
				notify_sms='".$dsms."',
				modifiedby='".$_SESSION['MVGitHub_idacname']."',
				modifiedon='".$timenowis."'
				WHERE idwfnotification=".$_SESSION['notification']."  LIMIT 1";
				
				mysql_query($sql_update);
				
				//then update the tktmsg table below
				$sql_updatemsg="UPDATE tktmsgs SET tktstatus_idtktstatus='".$dstatus."', tktmsg_sms='".$dsmsmsg."', tktmsg_email='".$demailmsg."', tktmsg_dashboard='".$dsysmsg."', modifiedby='".$_SESSION['MVGitHub_idacname']."', modifiedon='".$timenowis."'  
				WHERE wfnotification_idwfnotification=".$_SESSION['notification']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
//				echo $sql_updatemsg;	
				mysql_query($sql_updatemsg);
					
				$msg_ok = "<span class=\"msg_success\">".$msg_changes_saved."</span>";
				
				} else {
				$msg_error_2= "<span class=\"msg_warning\">".$msg_warning_duplicate_wf."</span>";
				}
			} else {
			$msg_error= "<span class=\"msg_warning\">".$msg_warn_contactadmin."</span>";
		
		} //close if no error

	} //close form update action
	
//list the current records
$sql_notification="SELECT idwfnotification,tktstatusname,usrrolename,usrrole_idusrrole,notify_system,notify_email,notify_sms,tktmsg_sms,tktmsg_email,tktmsg_dashboard,userteamzonename,wfnotification.limit_to_zone FROM wfnotification
INNER JOIN tktstatus ON wfnotification.tktstatus_idtktstatus=tktstatus.idtktstatus
INNER JOIN tktmsgs ON wfnotification.idwfnotification = tktmsgs.wfnotification_idwfnotification
INNER JOIN usrrole ON wfnotification.usrrole_idusrrole=usrrole.idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." ORDER BY userteamzonename,usrrolename DESC";
$res_notification=mysql_query($sql_notification);
$fet_notification=mysql_fetch_array($res_notification);
$num_notification=mysql_num_rows($res_notification);
//echo $sql_notification;
//exit;

?>
<?php // if ( (!isset($_GET['do'])) || ( (isset($_GET['do'])) && ($_GET['do']!="add_notif"))  ) { ?>
<?php // if (!isset($_GET['do'])) { ?>
<div style="padding:5px 5px 5px 15px">
	<a href="<?php echo $_SERVER['PHP_SELF'];?>?do=add_notif" id="button_newnotification"></a>
</div>
<?php // } ?>
<div>
<?php
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save") )
	{
	if (isset($msg_ok)) { echo $msg_ok; } 
	if (isset($msg_error)) { echo $msg_error; } 
	if (isset($msg_error_2)) { echo $msg_error_2; }
	if (isset($msg_error_content)) { echo $msg_error_content; }
	}
?>
</div>
<?php if ((isset($_GET['do'])) && ($_GET['do']=="add_notif") ) { ?>
<div>
<?php if (!isset($msg_ok)) { ?>
<form action="" method="post" name="notification">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
    	<td >
        	<table border="0" width="100%" cellpadding="0" cellspacing="0">
            	<tr>
					<td class="tbl_h" width="98%">
					<?php echo $lbl_newnofit;?>
					</td>
					<td align="right" class="tbl_h" width="2%" style="padding:0px 5px;">
					<a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_close_small"></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top" >
			<table border="0" width="100%" cellpadding="2" cellspacing="0">
		  <tr>
					<td height="45" class="tbl_data">
                    <?php echo $lbl_role;?>					</td>
              <td height="45" class="tbl_data">
                    <?php
                    $sql_usrrole="SELECT DISTINCT idusrrole,usrrolename,userteamzonename,utitle,fname,lname,idusrac FROM usrrole 
					INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
					LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
					WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename,usrrolename ASC";
                    $res_usrrole=mysql_query($sql_usrrole);
                    $num_usrrole=mysql_num_rows($res_usrrole);
                    $fet_usrrole=mysql_fetch_array($res_usrrole);
					//echo $sql_usrrole;
                    ?>
                  <select name="usrrole">
                  <option value="0">---</option>
                    <?php
                    do {
                    ?>
                    <option title="<?php if ($fet_usrrole['idusrac']>0) {  echo $fet_usrrole['utitle']." ".$fet_usrrole['lname']." ".$fet_usrrole['fname']; } else { echo $lbl_vacant; }?>" value="<?php echo $fet_usrrole['idusrrole'];?>"><?php echo "[".$fet_usrrole['userteamzonename']."] ".$fet_usrrole['usrrolename']; if ($fet_usrrole['idusrac']>0) { echo "*"; }?></option>
                    <?php
                    } while ($fet_usrrole=mysql_fetch_array($res_usrrole));
                    ?>
                  </select>			    </td>
			  </tr>
                
                <tr>
                    <td height="45" class="tbl_data">
                    	<?php
						echo $lbl_status;
						?>                           </td>
                  <td height="45" class="tbl_data">
                    <?php 
                    $sql_status="SELECT idtktstatus,tktstatusname,tktstatusdesc FROM tktstatus ORDER BY idtktstatus ";
                    $res_status=mysql_query($sql_status);
                    $num_status=mysql_num_rows($res_status);
                    $fet_status=mysql_fetch_array($res_status);
                    if ($num_status<1)
                        {
                        echo $msg_warn_contactadmin;
                        } else {
                    ?>
              <select name="tskstatus">
              			 <option value="0">---</option>
                        <?php do { ?>
                        <option value="<?php echo $fet_status['idtktstatus'];?>" title="<?php echo $fet_status['tktstatusdesc'];?>"><?php echo $fet_status['tktstatusname'];?></option>
                        <?php } while ($fet_status=mysql_fetch_array($res_status));?>
                    </select>
                    <?php }?>                  </td>
                  
              </tr>
                
				<tr>
					<td valign="top" class="tbl_data" >
                    <?php echo $lbl_nsysnote;?>
					</td>
					<td class="tbl_data">
                    <div class="controls" style="padding:8px 0px">
<label class="radio inline" style="color:#009900; font-weight:bold; padding:0px 40px 0px 0px">
<input type="radio" name="notify" data_alert="1" id="notify_yes_1" value="1" tabindex="25" >
<?php echo $lbl_statusactive;?>
</label>
<label class="radio inline" style="color:#FF0000; font-weight:bold">
<input name="notify" type="radio" data_alert="1" id="notify_no_1" value="0" tabindex="26" checked >
<?php echo $lbl_statusactivenot;?>
</label>
</div>
                   <div class="controls">
                        <textarea cols="42" id="msgnote_1" rows="2" name="sys_msg" ></textarea>
					</div>                        
				  </td>
				</tr>
				<tr>
					<td class="tbl_data" valign="top">
                    <?php echo $lbl_smsmsg;?>
					</td>
					<td class="tbl_data" >
                    <div>
                    <div class="controls" style="padding:8px 0px">
                      <label class="radio inline" style="color:#009900; font-weight:bold;padding:0px 40px 0px 0px">
                            <input type="radio" name="notify1" data_alert="2" id="notify_yes_2" value="1" tabindex="25" >
                      <?php echo $lbl_statusactive;?> </label>
                        <label class="radio inline" style="color:#FF0000; font-weight:bold">
                            <input name="notify1" type="radio" data_alert="2" id="notify_no_2" value="0" tabindex="26" checked >
                           <?php echo $lbl_statusactivenot;?> </label>
                    </div>
                    
                    <div style="width:400px;" class="form_element" >
                            <div style="font-weight:bold;padding:0px 0px 0px 2px; background-color:#cccccc">
                            <input type="text" size="48" readonly style="border:0; overflow: auto; font-family:'Courier New', Courier, monospace; font-size:12px; background-color:#cccccc" name="sms_msg_pref" value="<?php echo $_SESSION['MVGitHub_userteamshortname']; ?><?php echo $sample_tktpref;?>CategoryName" />
                            </div>
                            <div >
                              
                                   <textarea id="msgnote_2" class="no_border" cols="42" rows="2" name="sms_msg"  onKeyDown="limitText(this.form.sms_msg,this.form.countdown,<?php echo $sms_length;?>);" onKeyUp="limitText(this.form.sms_msg,this.form.countdown,<?php echo $sms_length;?>);"></textarea>
                                    
                            </div>
                      </div>
                    </div>
                    
					</td>
				</tr>
				<tr>
					<td class="tbl_data" valign="top" >
                    <?php echo $lbl_emailmsg;?></td>
					<td class="tbl_data" >
                    <div class="controls" style="padding:8px 0px">
    <label class="radio inline" style="color:#009900; font-weight:bold;padding:0px 40px 0px 0px">
        <input type="radio" name="notify2" data_alert="3" id="notify_yes_3" value="1" tabindex="25" >
        <?php echo $lbl_statusactive;?> </label>
    <label class="radio inline" style="color:#FF0000; font-weight:bold">
        <input name="notify2" type="radio" data_alert="3" id="notify_no_3" value="0" tabindex="26" checked >
        <?php echo $lbl_statusactivenot;?> </label>
</div>
                    <div>
					<textarea name="email_msg" id="msgnote_3" cols="42" rows="8" ></textarea>
                    </div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td height="45" class="tbl_data">
						<table border="0" style="margin:0px" cellpadding="0" cellspacing="0">
							<tr>
								<td>
								<a href="#" onclick="document.forms['notification'].submit()" id="button_save"></a>
								</td>
								<td style="padding:0px 0px 0px 10px">
                                            <input type="hidden" value="save" name="formaction" />
                                           <a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>
<?php } ?>
</div>
<?php } ?>
<?php
if ( (isset($_GET['do'])) && ($_GET['do']=="update")) { 

//retrieve this record
//list the roles in this organisation and their respective users
$sql_notified="SELECT idwfnotification,tktmsgs.tktstatus_idtktstatus,usrrole_idusrrole,notify_system,notify_email,notify_sms,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification
LEFT JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." AND idwfnotification=".$_SESSION['notification']." LIMIT 1";
$res_notified=mysql_query($sql_notified);
$fet_notified=mysql_fetch_array($res_notified);
$num_notified=mysql_num_rows($res_notified);
//echo $sql_notified;

?>
<div>
<?php
if ((isset($_POST['formaction'])) && ($_POST['formaction']=="save_updates") )
	{
	if (isset($msg_ok)) { echo $msg_ok; } 
	if (isset($msg_error)) { echo $msg_error; } 
	if (isset($msg_error_2)) { echo $msg_error_2; }
	if (isset($msg_error_content)) { echo $msg_error_content; }
	}
?>
</div>
<div>
<form action="" method="post" name="notification">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
    	<td class="tbl_h">
        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
					<td class="tbl_h">
					<?php echo $lbl_updatenofit;?>
					</td>
					<td align="right" class="tbl_h" style="position:absolute; right:10px">
					<a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_close_small"></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
	  <td valign="top">
          <table border="0" width="100%" cellpadding="2" cellspacing="0">
				<tr>
					<td height="45" class="tbl_data"><?php echo $lbl_role;?> </td>
				  <td height="45" class="tbl_data"><?php
$sql_usrrole="SELECT DISTINCT idusrrole,usrrolename,utitle,fname,lname,idusrac,userteamzonename FROM usrrole 
					INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
					LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
					WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename,usrrolename ASC";
                    $res_usrrole=mysql_query($sql_usrrole);
                    $num_usrrole=mysql_num_rows($res_usrrole);
                    $fet_usrrole=mysql_fetch_array($res_usrrole);
		$res_usrrole=mysql_query($sql_usrrole);
		$num_usrrole=mysql_num_rows($res_usrrole);
		$fet_usrrole=mysql_fetch_array($res_usrrole);
		?>
        <select name="usrrole">
          <?php
		do {
		?>
          <option title="<?php if ($fet_usrrole['idusrac']>0) {  echo $fet_usrrole['utitle']." ".$fet_usrrole['lname']." ".$fet_usrrole['fname']; } else { echo $lbl_vacant; }?>" <?php if ($fet_notified['usrrole_idusrrole']==$fet_usrrole['idusrrole']) { echo "selected=\"selected\""; } ?> value="<?php echo $fet_usrrole['idusrrole'];?>"><?php echo "[".$fet_usrrole['userteamzonename']."] ".$fet_usrrole['usrrolename']; if ($fet_usrrole['idusrac']>0) { echo "*"; }?></option>
          <?php
		} while ($fet_usrrole=mysql_fetch_array($res_usrrole));
		?>
        </select>
					</td>
				</tr>
				<tr>
					<td  height="45" class="tbl_data"><?php echo $lbl_nstatus;?> </td>
					<td height="45" class="tbl_data"><?php 
		$sql_status="SELECT idtktstatus,tktstatusname,tktstatusdesc FROM tktstatus ORDER BY idtktstatus ";
		$res_status=mysql_query($sql_status);
		$num_status=mysql_num_rows($res_status);
		$fet_status=mysql_fetch_array($res_status);
		if ($num_status<1)
			{
			echo $msg_warn_contactadmin;
			} else {
		?>
        <select name="tskstatus">
          <?php do { ?>
          <option <?php if ($fet_notified['tktstatus_idtktstatus']==$fet_status['idtktstatus']) { echo "selected=\"selected\""; } ?> value="<?php echo $fet_status['idtktstatus'];?>" title="<?php echo $fet_status['tktstatusdesc'];?>"><?php echo $fet_status['tktstatusname'];?></option>
          <?php } while ($fet_status=mysql_fetch_array($res_status));?>
        </select>
        <?php }?>
					</td>
				</tr>
				<tr>
					<td valign="top" class="tbl_data" >
					<?php echo $lbl_nsysnote;?>
					</td>
					<td class="tbl_data" >
                     <div class="controls" style="padding:8px 0px">
<label class="radio inline" style="color:#009900; font-weight:bold; padding:0px 40px 0px 0px">
<input type="radio"  <?php if ($fet_notified['notify_system']==1){ echo "checked=\"checked\""; } ?> name="notify" data_alert="1" id="notify_yes_1" value="1" tabindex="25" >
<?php echo $lbl_statusactive;?>
</label>
<label class="radio inline" style="color:#FF0000; font-weight:bold">
<input name="notify"  <?php if ($fet_notified['notify_system']==0){ echo "checked=\"checked\""; } ?> type="radio" data_alert="1" id="notify_no_1" value="0" tabindex="26"  >
<?php echo $lbl_statusactivenot;?>
</label>
</div>
<div>
                    <textarea id="msgnote_1"  cols="42" rows="2" name="sys_msg"  ><?php echo $fet_notified['tktmsg_dashboard'];?></textarea>
                    </div>
				  </td>
				</tr>
				<tr>
					<td class="tbl_data" valign="top" ><?php echo $lbl_smsmsg;?> </td>
					<td class="tbl_data" >
                    <div class="controls" style="padding:8px 0px">
                      <label class="radio inline" style="color:#009900; font-weight:bold;padding:0px 40px 0px 0px">
                      <input type="radio" name="notify1" <?php if ($fet_notified['notify_sms']==1){ echo "checked=\"checked\""; } ?> data_alert="2" id="notify_yes_2" value="1" tabindex="25" >
                      <?php echo $lbl_statusactive;?> </label>
                        <label class="radio inline" style="color:#FF0000; font-weight:bold">
                        <input name="notify1" type="radio" <?php if ($fet_notified['notify_sms']==0){ echo "checked=\"checked\""; } ?> data_alert="2" id="notify_no_2" value="0" tabindex="26"  >
                           <?php echo $lbl_statusactivenot;?> </label>
                    </div>
                    <div style="font-weight:bold;padding:0px 0px 0px 0px"><?php echo $_SESSION['MVGitHub_userteamshortname']; ?> <?php echo $lbl_ticketno;?> <?php echo $lbl_ticketno_sample;?></div>
                    <div>
                      <textarea cols="42" rows="2" name="sms_msg" id="msgnote_2"   onKeyDown="limitText(this.form.sms_msg,this.form.countdown,<?php echo $sms_length;?>);" onKeyUp="limitText(this.form.sms_msg,this.form.countdown,<?php echo $sms_length;?>);"><?php echo $fet_notified['tktmsg_sms'];?></textarea>
                      <input readonly type="hidden" name="countdown" size="2" value="<?php echo ($sms_length-strlen($fet_notified['feedbacksms']));?>">
                  </div>
					</td>
				</tr>
				<tr>
					<td class="tbl_data"  valign="top" >
					<?php echo $lbl_emailmsg;?>
					</td>
					<td class="tbl_data"  >
                    <div class="controls" style="padding:8px 0px">
    <label class="radio inline" style="color:#009900; font-weight:bold;padding:0px 40px 0px 0px">
        <input type="radio" name="notify2" <?php if ($fet_notified['notify_email']==1){ echo "checked=\"checked\""; } ?> data_alert="3" id="notify_yes_3" value="1" tabindex="25" >
        <?php echo $lbl_statusactive;?> </label>
    <label class="radio inline" style="color:#FF0000; font-weight:bold">
        <input name="notify2" type="radio" <?php if ($fet_notified['notify_email']==0){ echo "checked=\"checked\""; } ?> data_alert="3" id="notify_no_3" value="0" tabindex="26"  >
        <?php echo $lbl_statusactivenot;?> </label>
</div>
                    <div>
                    <textarea id="msgnote_3"  name="email_msg" cols="42" rows="8" ><?php echo $fet_notified['tktmsg_email'];?></textarea>
                    </div>
                  </td>
                    
				<tr>
					<td></td>
					<td height="45" class="tbl_data">
							<table border="0" style="margin:0px" cellpadding="0" cellspacing="0">
								<tr>
									<td>
                                    <a href="#" onClick="document.forms['notification'].submit()" id="button_save"></a>
                                    </td>
									<td style="padding:0px 0px 0px 10px"><input type="hidden" value="save_updates" name="formaction" />
									<a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_cancel" onClick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>
                                    </td>
								</tr>
							</table>
					</td>
				</tr>
			</table>  
      </td>
	</tr>
</table>
</form>
</div>
<?php } ?>
<?php if (isset($msg)) { ?>
<div>
<?php echo $msg;?>
</div>
<?php } ?>
<div class="tbl_data" style="text-align:right">
<?php
if ($fet_notification > 0)
	{	
    	if ($fet_notification['limit_to_zone']==1)
						{
						$switch_to="0";
						$suffix="_on";
						} else {
						$switch_to="1";
						$suffix="";
						}
						
						echo "<table border=0><tr>
						<td>
	                    <a href=\"#\" style=\"text-decoration:none;\" class=\"tooltip\">
						<img src=\"../assets_backend/icons/help.gif\" border=\"0\" align=\"absmiddle\" />
						<span>".$msg_tip_limittozone."</span></a>
						</td>
						<td width=\"20\"><a href=\"".$_SERVER['PHP_SELF']."?do=restrict_zone_notification&amp;turn=".$switch_to."\" id=\"button_check".$suffix."\"></a></td>";						
						echo "<td align=\"left\"><strong>".$lbl_ltdregion_title."</strong></td></tr></table>";
    }
?>	
</div>
<div>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
		<td class="tbl_h"><?php echo $lbl_role;?></td>
      <td class="tbl_h"><?php echo $lbl_zone;?></td>
	  <td class="tbl_h" ><?php  echo $lbl_status;?></td>
	  <td class="tbl_h" ><?php echo $lbl_nsysdash;?></td>
	  <td class="tbl_h" ><?php echo $lbl_nemail;?></td>
      <td class="tbl_h" ><?php echo $lbl_nsms;?></td>
      <td class="tbl_h" >&nbsp;</td>
        
    </tr>
                <?php
if ($fet_notification > 0)
	{			
					do {
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td class="tbl_data">
					<a href="<?php echo $_SERVER['PHP_SELF'];?>?do=update&amp;wfn=<?php echo $fet_notification['idwfnotification'];?>"><?php echo $fet_notification['usrrolename'];?></a></td>
                    <td class="tbl_data">
                    <?php echo $fet_notification['userteamzonename'];?>
                    </td>
                    <td class="tbl_data">
                    <?php echo $fet_notification['tktstatusname'];?>
                    </td>
                  <td class="tbl_data">
                    <?php if ($fet_notification['notify_system']==1) { echo "<span style=\"color:#009900\">YES</span>";} else { echo "<span style=\"color:#FF0000\">NO</span>"; }?>
                    </td>
                    <td class="tbl_data">
                    <?php if ($fet_notification['notify_email']==1) { echo "<span style=\"color:#009900\">YES</span>";} else { echo "<span style=\"color:#FF0000\">NO</span>"; }?>
                  </td>
                    <td class="tbl_data">
                    <?php if ($fet_notification['notify_sms']==1) { echo "<span style=\"color:#009900\">YES</span>";} else { echo "<span style=\"color:#FF0000\">NO</span>"; }?>
                  </td>
                    <td width="60" class="tbl_data">
                    <a onclick="return confirm('<?php echo $msg_prompt_delete;?>');" href="<?php echo $_SERVER['PHP_SELF'];?>?do=delete&amp;id=<?php echo $fet_notification['idwfnotification'];?>" id="button_delete_small"></a>
                    </td>
				</tr>
              <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>
                <?php
			
			} while ($fet_notification=mysql_fetch_array($res_notification));
	} else {
?>
<tr>
	<td colspan="7" height="40">
    <div class="msg_instructions">
    <?php echo $msg_nonotification;?>
    </div>    
    </td>
</tr>
<?php } ?>
 </table>
</div>
