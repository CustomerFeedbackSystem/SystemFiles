<?php //require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

if ((isset($_POST['formaction'])) && ($_POST['formaction']=="sendhelp"))
	{
	$msgsubject=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['usrsubject'])));
	$msg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['usrmsg'])));
	$msgurgency=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['urgency'])));
	$msgfeedback=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['feedback'])));
	$usrphone=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['usrphone'])));
	//validate... all fields are required
	if (strlen($msgsubject) < 1)
		{
		$error_1="<div class=\"msg_warning\">".$msg_subject_missing."</div>";
		}
	if (strlen($msg) < 1)
		{
		$error_2="<div class=\"msg_warning\">".$msg_warn_msgmis."</div>";
		}
	if ($msgurgency < 1)
		{
		$error_3="<div class=\"msg_warning\">".$msg_urgency_missing."</div>";
		}
	if ($msgfeedback < 1)
		{
		$error_4="<div class=\"msg_warning\">".$msg_feedback_missing."</div>";
		}
	if (strlen($usrphone)<12)
		{
		$error_5="<div class=\"msg_warning\">Invalid Phone Number. Please enter your phone number in International Format. eg: 254722123123</div>";
		}
		
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) && (!isset($error_4)) && (!isset($error_5))  )
		{
		//process
		$sql="INSERT INTO hd (hd_feedback_idhd_feedback,hd_urgency_idhd_urgency,datesent,helpsubject,helpmsg,createdon,createdby) 
		VALUES ('".$msgfeedback."','".$msgurgency."','".$timenowis."','".$msgsubject."','".$msg."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";		
		mysql_query($sql);
		
		//retrieve this record
		$sql_rec="SELECT idhelpdesk,hdfeedback,hdurgency FROM hd
		INNER JOIN hd_feedback ON hd.hd_feedback_idhd_feedback=hd_feedback.idhd_feedback
		INNER JOIN hd_urgency ON hd.hd_urgency_idhd_urgency=hd_urgency.idhd_urgency
		WHERE hd.createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idhelpdesk DESC LIMIT 1";
		$res_rec=mysql_query($sql_rec);
		$num_rec=mysql_num_rows($res_rec);
		$fet_rec=mysql_fetch_array($res_rec);
		
		if ($msgurgency==3) //if extremely urgent, send SMS
			{
			$txtmsg_raw="URGENT Help Request ".$_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname']."-".$_SESSION['MVGitHub_userteamzone']." (".$usrphone.") (".$msgsubject.")";
			$txtmsg=substr($txtmsg_raw,0,160);
			
			$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext) VALUES ('254722342084','".$txtmsg."'),('254725481036','".$txtmsg."'),('254728536865','".$txtmsg."')";
			mysql_query($sql_sms);
			}
			
		//update the pohone number just incase
		$sql_updatephone="UPDATE usrac SET usrphone='".$usrphone."' WHERE idusrac=".$_SESSION['MVGitHub_idacname']." LIMIT 1";	
		mysql_query($sql_updatephone);
		//echo $sql;
		//send an email to that effect
		$to = $support_email;
				
		$message = "
		Subject : ".$msgsubject."\r\n
		Time Sent : ".$timenowis."\r\n
		Message : FROM: ".$_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrlname']." (".$_SESSION['MVGitHub_userrole']." - ".$_SESSION['MVGitHub_userteamzone'].")
		".$msg."\r\n	
		Urgency : ".addslashes($fet_rec['hdurgency'])."\r\n
		Preferred Feedback : ".addslashes($fet_rec['hdfeedback'])."\r\n
		Telephone : ".$usrphone."\r\n
		eMail : ".$_SESSION['MVGitHub_usremail']."
		\r\n
		DISCLAIMER: You received this email because your email address was used on ".$pagetitle."
		The Information contained in this email, including the links, is intended solely for the use of the designated recipient.
		If you have received this e-mail message in error please notify the ".$pagetitle." team through e-mail ".$support_email." and delete it immediately
		";
				
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		$sendername=$_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrlname']."&nbsp;(".$_SESSION['MVGitHub_userrole'];
		//	$headers .= 'To: '..' <'.$youremail.'>' . "\r\n";
		$headers .= 'From: '.$sendername.' <'.$_SESSION['MVGitHub_usremail'].'>' . "\r\n";
		//$headers .= 'Bcc: whateve@email.com' . "\r\n";
				
		$subject = "Help ".$pagetitle." - ".$fet_rec['hdurgency']."";
		// Mail it
		
		if (strlen($support_email)>6) 
			{
			if ($mailserver_avail==1)
					{
					
					mail($to,$subject,$message,$headers);
					
					} else {
					//if mail server is not available, then save the function in a variable and parse this to the online server for processing
					$sql_mailout="INSERT INTO mdata_emailsout (email_to,email_subject,email_message,email_headers,createdon) 
					VALUES ('".$to."','".$subject."','".$message."','".$headers."','".$timenowis."')";
					mysql_query($sql_mailout);
					}
							
			$acknowledge=1;
			} else {
			$error_5= "<div class=\"msg_warning\">Error! Missing Support Email Contact at the configuration file</div>";
			}
		}
	}
?>
<div>
    <div >
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
    </table>
	</div>
    <div>
    <?php
	if (!isset($acknowledge)) { 
	?>
    	<div style="width:400px" class="msg_instructions">
        <?php echo $msg_helpform;?>
        </div>
     <?php
	 }
	 ?>
        <div>
        <?php
		if ( (isset($error_1)) || (isset($error_2)) || (isset($error_3)) || (isset($error_4)) || (isset($error_5))  )
			{
			if (isset($error_1)) { echo $error_1; }
			if (isset($error_2)) { echo $error_2; }
			if (isset($error_3)) { echo $error_3; }
			if (isset($error_4)) { echo $error_4; }
			if (isset($error_5)) { echo $error_5; }
			}
		?>
        </div>
    </div>
    <div style="padding:0px 20px 5px 10px">
    <?php
	if (!isset($acknowledge))
		{
	?>
    <form method="post" action="" name="helpme">
    	<table border="0">
        	<tr>
            	<td valign="top" width="50%" style="padding:20px; background-color:#FFFFFF">
                <table border="0" cellspacing="0" cellpadding="2" class="border_thin">
   	  		<tr>
            	<td colspan="2" class="tbl_h">
                <?php echo $lbl_submithelpform;?>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
                  <strong><?php echo $lbl_name;?></strong> </td>
              <td class="tbl_data">
                <?php echo $_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrlname']."&nbsp;(".$_SESSION['MVGitHub_userrole'].")";?>
                </td>
            </tr>
            <tr>
            	<td height="40" class="tbl_data">
                  <strong><?php echo $lbl_subject;?></strong> </td>
              <td height="40" class="tbl_data">
                <input type="text" value="<?php if (isset($msgsubject)) { echo $msgsubject; } ?>" maxlength="20" size="30" name="usrsubject" />
                </td>
          </tr>
            <tr>
            	<td valign="top" class="tbl_data">
                  <strong><?php echo $lbl_youraction_msg;?></strong> </td>
              <td class="tbl_data">
                <textarea cols="30" rows="3" name="usrmsg"><?php if (isset($msg)) { echo $msg; } ?></textarea>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
                <strong><?php echo $lbl_urgency;?></strong>
                </td>
                <td class="tbl_data">
                <select name="urgency">
                <option value="0">--How Urgent is it?--</option>
                <?php
				$sql_urgent="SELECT * FROM hd_urgency ";
				$res_urgent=mysql_query($sql_urgent);
				$num_urgent=mysql_num_rows($res_urgent);
				$fet_urgent=mysql_fetch_array($res_urgent);
					
					if ($num_urgent>0)
						{
						do {
				?>
                <option <?php if ((isset($msgurgency)) && ($msgurgency==$fet_urgent['idhd_urgency'])) { echo "selected=\"selected\"";}?>  value="<?php echo $fet_urgent['idhd_urgency'];?>"><?php echo $fet_urgent['hdurgency'];?></option>
                <?php
							} while ($fet_urgent=mysql_fetch_array($res_urgent));
						}
				?>
                </select>
                </td>
            </tr>
            <tr>
            	<td class="tbl_data">
                
                </td>
                <td class="tbl_data">
                <select name="feedback">
                <option value="0">-- Preferred Feedback --</option>
                <?php
				$sql_feedback="SELECT * FROM hd_feedback ";
				$res_feedback=mysql_query($sql_feedback);
				$num_feedback=mysql_num_rows($res_feedback);
				$fet_feedback=mysql_fetch_array($res_feedback);
					
					if ($num_feedback>0)
						{
						do {
				?>
                <option <?php if ((isset($msgfeedback)) && ($msgfeedback==$fet_urgent['idhd_feedback'])) { echo "selected=\"selected\"";}?> value="<?php echo $fet_feedback['idhd_feedback'];?>"><?php echo $fet_feedback['hdfeedback'];?></option>
                <?php
							} while ($fet_feedback=mysql_fetch_array($res_feedback));
						}
				?>
                </select>
                </td>
            </tr>
            <tr>
            	<td valign="top" class="tbl_data"><strong>
                Your Mobile No.                </strong></td>
                <td class="tbl_data">
                <?php 
				$sql_myno="SELECT usrphone FROM usrac WHERE idusrac=".$_SESSION['MVGitHub_idacname']."";
				$res_myno=mysql_query($sql_myno);
				$fet_myno=mysql_fetch_array($res_myno);
				?>
                <input type="text" onKeyUp="res(this,numb);" maxlength="12" size="15" name="usrphone" value="<?php echo $fet_myno['usrphone'];?>" />
                <br /><small>(In International Format eg:254722654321)</small>
                </td>
                </tr>
            <tr>
            	<td></td>
                <td height="60">
                <a href="#" onclick="document.forms['helpme'].submit()" id="button_send"></a>
                <input type="hidden" value="sendhelp" name="formaction" /></td>
            </tr>
        </table>
                </td>
                <td valign="top" width="50%" style="padding:50px" class="text_body">

<p>&nbsp;</p>


 
                </td>
          </tr>
        </table>
    </form>
    <?php } else { ?>
	<div class="msg_success" style="width:350px">
        <div><?php echo $msg_submitsuccess;?></div>
        <div><?php echo $msg_xpectfb;?></div>
    </div>
    <?php } ?>
    </div>
</div>    