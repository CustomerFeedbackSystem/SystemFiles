<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save_updates") )
	{
	//clean up the form fields
	$txtsms=mysql_escape_string(trim($_POST['sms_msg']));
	$txtemail=nl2br(mysql_escape_string(trim($_POST['email_msg'])));
	
	$txtsmstreated=substr($txtsms,0,$sms_length);
	
	//update the results
	$sql_update="UPDATE tktfeedback SET feedbacksms='".$txtsmstreated."',feedbackemail='".$txtemail."',modifiedby='".$_SESSION['MVGitHub_idacname']."',modifiedon='".$timenowis."'  WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
	mysql_query($sql_update);
	
	$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
	
	}

$sql_feedback="SELECT actionstatus FROM tktfeedback WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
$res_feedback=mysql_query($sql_feedback);
$num_feedback=mysql_num_rows($res_feedback);
$fet_feedback=mysql_fetch_array($res_feedback);

mysql_free_result($res_feedback);

if (($num_feedback < 1) || ($fet_feedback['actionstatus']=="0") )
	{
	$actionstat="off";
	$actionstatchange="on";
	}

if ($fet_feedback['actionstatus']=="1")
	{
	$actionstat="on";
	$actionstatchange="off";
	}

if (isset($_GET['action']))
	{
	$act=mysql_escape_string(trim($_GET['action']));
	
		if ($act=="on")
			{
			
			if ($num_feedback < 1)
				{
				//insert
				$sql_new="INSERT INTO tktfeedback (wftskflow_idwftskflow,actionstatus,createdby,createdon) 
				VALUES ('".$_SESSION['idflow']."','1','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
				mysql_query($sql_new);
				
				
				//$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
				
				} else if ($num_feedback>0) {
				//else update
				$sql_update_status="UPDATE tktfeedback SET actionstatus='1' WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
				mysql_query($sql_update_status);
	
				
				//$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
					
				}
					
			
			}
		
		if ($act=="off")
			{
		//	echo $act;
			//change status
			$sql_update="UPDATE tktfeedback SET actionstatus='0' WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
			mysql_query($sql_update);
		//	echo $sql_update;
			}
	
	}

$sql_feedbackdetails="SELECT * FROM tktfeedback WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
$res_feedbackdetails=mysql_query($sql_feedbackdetails);
$num_feedbackdetails=mysql_num_rows($res_feedbackdetails);
$fet_feedbackdetails=mysql_fetch_array($res_feedbackdetails);
//echo $sql_feedbackdetails;
mysql_free_result($res_feedbackdetails);	

?>
<div>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td width="15">
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?action=<?php if ($fet_feedbackdetails['actionstatus']=="1"){ echo "off"; } if (($num_feedbackdetails=="0") || ($fet_feedbackdetails['actionstatus']=="0")) { echo "on";}?>" id="button_check<?php if ($fet_feedbackdetails['actionstatus']=="1"){ echo "_on"; } ?>"></a></td>
      <td class="text_body" style="font-weight:bold" align="left">
		<?php if ($fet_feedbackdetails['actionstatus']=="1"){ echo "<span style=\"color:#009900\">".$lbl_statusactive."</span>"; } else if ($fet_feedbackdetails['actionstatus']=="0"){ echo "<span style=\"color:#FF0000\">".$lbl_statusactivenot."</span>"; } ?>
        <span class="msg_instructions_small"><small>(<?php if ($fet_feedbackdetails['actionstatus']=="1"){  echo $msg_click_todeactivate; } else if ($fet_feedbackdetails['actionstatus']=="0") { echo $msg_click_toactivate; } else { echo $msg_click_toactivate; }?>)</small></span>
        </td>
    </tr>
</table>
</div>
<div>
<?php
if (isset($msg)) { echo $msg; } 
?>
</div>
<?php
if ( (strlen($fet_feedbackdetails['feedbacksms'])<1) && (strlen($fet_feedbackdetails['feedbackemail'])<1)) { ?>
<div class="msg_warning_small">
<?php echo $msg_warn_feedbackmissing; ?>
</div>
<?php } ?>

<?php 
if ($fet_feedbackdetails['actionstatus']=="1"){
?>
<div>
<form action="" method="post" name="feedback">
<table border="0" class="border_thick" width="650" cellpadding="2" cellspacing="0">
	<tr>
    	<td colspan="2" class="tbl_h">
        <?php echo $lbl_compose_customer_feedback;?>
        </td>
    	</tr>
		<tr>        
        <td class="text_body" valign="top" style="padding:10px">
        <?php echo $lbl_smsmsg;?>
        </td>
        <td class="text_body" style="padding:10px 10px 10px 17px ">
          <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td  >
        	<div style="width:500px;" class="form_element" >
            <div style="font-weight:bold;padding:0px 0px 0px 2px">
            <input type="text" size="50" readonly style="border:0; overflow: auto; font-family:'Courier New', Courier, monospace; background-color:#cccccc" name="sms_msg_pref" value="<?php echo $_SESSION['MVGitHub_userteamshortname']; ?><?php echo $sample_tktpref;?>CategoryName" />
            </div>
            <div >
              
                   <textarea class="no_border" cols="52" rows="2" name="sms_msg"  onKeyDown="limitText(this.form.sms_msg,this.form.countdown,<?php echo $sms_length;?>);" onKeyUp="limitText(this.form.sms_msg,this.form.countdown,<?php echo $sms_length;?>);"><?php echo $fet_feedbackdetails['feedbacksms'];?></textarea>
                    
            </div>
        </div>
        <div style="padding:10px 0px 10px 0px">
        <em><?php echo $lbl_youhave;?> <input readonly type="text" name="countdown" size="2" value="<?php echo ($sms_length-strlen($fet_feedbackdetails['feedbacksms']));?>"> 
                    <?php echo $lbl_charleft;?></em>
        </div>
        </td>
                    </tr>
                </table>
		</td>
    </tr>
     <tr>
    	<td class="text_body" valign="top" style="padding:10px">
        <?php echo $lbl_emailmsg;?>
        </td>
        <td class="text_body" style="padding:10px 10px 10px 17px">
        <textarea name="email_msg" cols="45" rows="4" ><?php echo $fet_feedbackdetails['feedbackemail'];?></textarea>
        </td>
    </tr>
    <tr>
    	<td></td>
        <td>
        <table border="0" style="margin:0px" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['feedback'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save_updates" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>
        </td>
    </tr>
</table>
</form>
<?php
}
?>
</div>