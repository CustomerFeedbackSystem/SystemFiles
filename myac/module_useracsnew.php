<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="addusr") )
	{
	//clean up entries
		$pusrac = mysql_escape_string(trim($_POST['usrname']));
		$pusrpass = mysql_escape_string(trim($_POST['usrpass']));
		$pcusrpass = mysql_escape_string(trim($_POST['cusrpass']));
		$pfname = mysql_escape_string(trim($_POST['fname']));
		$plname = mysql_escape_string(trim($_POST['lname']));
		$puseremail = mysql_escape_string(trim($_POST['useremail']));
		$puserphone = mysql_escape_string(trim($_POST['userphone']));
		$prole=mysql_escape_string(trim($_POST['usrrole']));
		$pacstatus=mysql_escape_string(trim($_POST['acstatus']));
		$putitle=mysql_escape_string(trim($_POST['usertitle']));
		

		if (strlen($pusrac)<3)
			{
			$error1="<div class=\"msg_warning\">".$msg_warning_ac."</div>";
			}
		
		//if the password is left blank, then compose a random password for this user
		if ( (strlen($pusrpass)<1) && (strlen($pcusrpass)<1) )
			{
			$pusrpass=rand(11111111,99999999);
			$pass_exempt=1;
			}		
		
		if ( (strlen($pusrpass)<8) && (!isset($pass_exempt)) )
			{
			$error2="<div class=\"msg_warning\">".$msg_warning_shortpwd."</div>";
			}
			
		if ( (strlen($pusrpass)>7) && ($pusrpass!=$pcusrpass) && (!isset($pass_exempt)) )
			{
			$error3="<div class=\"msg_warning\">".$msg_warning_matchpwd."</div>";
			}
		if (strlen($pfname)<1)
			{
			$error4="<div class=\"msg_warning\">".$msg_warning_fname."</div>";
			}
		if (strlen($plname)<1)
			{
			$error5="<div class=\"msg_warning\">".$msg_warning_lname."</div>";
			}
		if (strlen($puseremail)<6)
			{
			$error6="<div class=\"msg_warning\">".$msg_warning_useremail."</div>";
			}
		//check if the ac name is unique
		$sql_acunique="SELECT usrname FROM usrac WHERE usrname='".$pusrac."' LIMIT 1";
		$res_acunique=mysql_query($sql_acunique);
		$num_acunique=mysql_num_rows($res_acunique);
		
		if ( ($num_acunique>0) && (!isset($error1)) )
			{
			$error7="<div class=\"msg_warning\">".$msg_warning_actaken."</div>";
			}
			

		//if no error, process
		if ( (!isset($error1)) && (!isset($error2)) && (!isset($error3)) && (!isset($error4)) && (!isset($error5)) && (!isset($error6)) && (!isset($error7))  )
		{
			$new_ac="INSERT INTO usrac (usrrole_idusrrole,usrteam_idusrteam,usrname,usrpass,utitle,fname,lname,acstatus,mobileaccess,usremail,usrphone,createdby,createdon)
			VALUES ('".$prole."','".$_SESSION['MVGitHub_idacteam']."','".$pusrac."','".SHA1($pusrpass)."','".$putitle."','".$pfname."','".$plname."','".$pacstatus."','0','".$puseremail."','".$puserphone."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($new_ac);
			
			//retrieve
			$sql_id="SELECT idusrac FROM usrac WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idusrac DESC LIMIT 1";
			$res_id=mysql_query($sql_id);
			$fet_id=mysql_fetch_array($res_id);
			
			//send sms
			if ( (isset($_POST['alert_sms'])) && ($_POST['alert_sms']==1) && (strlen($puserphone)==12) )
				{
				$sms_msg="Your Account Details - Account : ".$pusrac." Password : ".$pusrpass." available at ".$url_absolute."";
				
				//insert the message
				$sql_smsthis="INSERT INTO  mdata_out_sms (destnumber,msgtext) 	VALUES ('".$puserphone."','".$sms_msg."')";
				mysql_query($sql_smsthis);
				}
			
			$msg_success=$msg_changes_saved;
			
			//header('location:'.$_SERVER['PHP_SELF'].'?uction=view_submod&user='.$fet_id['idusrac'].'&msg_success='.$msg_success.'');
			?>
            <script language="javascript">
			window.location='<?php echo $_SERVER['PHP_SELF'].'?uction=view_submod&user='.$fet_id['idusrac'].'&msg_success='.$msg_success.'';?>';
			</script>
            <?php
			exit;
		}//close if no error
	} //close form action
	

?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div>
    <?php
	if (isset($msg_success)) { echo $msg_success; } 
	if (isset($_GET['msg_success'])) { echo "<div class=\"msg_success\">".$_GET['msg_success']."</div>"; }

 		if ( (isset($error1)) || (isset($error2)) || (isset($error3)) || (isset($error4)) || (isset($error5)) || (isset($error6)) || (isset($error7))  )
		{
			if (isset($error1)) { echo $error1; }
			if (isset($error2)) { echo $error2; }
			if (isset($error3)) { echo $error3; }
			if (isset($error4)) { echo $error4; }
			if (isset($error5)) { echo $error5; }
			if (isset($error6)) { echo $error6; }
			if (isset($error7)) { echo $error7; }
		}
	?>
    </div>
    <div style="padding:10px">
    <form method="post" name="addusr" action="">
<table width="100%" border="0" cellpadding="2" cellspacing="0">
<tr>
                    	<td colspan="2" class="table_header" height="35">
                        <?php echo $lbl_new_ac;?>
                        </td>
						<tr>
							<td  height="40" class="tbl_data">
							<strong><?php echo $lbl_username;?></strong>
                            </td>
						  <td  class="tbl_data">
                          <script language="javascript">
 function fixme(element) {
 var val = element.value;
 var pattern = new RegExp('[ ]+', 'g');
 val = val.replace(pattern, '');
 element.value = val;
}
						  </script>
                            <input type="text" onkeyup="fixme(this)" onblur="fixme(this)"  maxlength="40" value="<?php if (isset($_POST['usrname'])) { echo $_POST['usrname']; }?>" size="20" name="usrname" />
							</td>
	</tr>
                        <tr>
							<td  height="40" class="tbl_data">
							<strong><?php echo $lbl_password;?></strong>							</td>
						  <td  class="tbl_data">
                            <input type="password" maxlength="40" value="<?php if (isset($_POST['usrpass'])) { echo $_POST['usrpass']; }?>"  size="20" name="usrpass" /> 
                            <?php
							echo "<small>".$ins_autopass."</small>";
							?>
							</td>
	</tr>
                        <tr>
							<td  height="40" class="tbl_data">
							<strong><?php echo $lbl_confirmpassword;?></strong>							</td>
						  <td  class="tbl_data">
                            <input type="password" maxlength="40" size="20" name="cusrpass"  value="<?php if (isset($_POST['cusrpass'])) { echo $_POST['cusrpass']; }?>"  />
                            <?php
							echo "<small>".$ins_autopass."</small>";
							?>
							</td>
						</tr>
                        <tr>
                        	<td class="tbl_data" height="40">
                            <strong><?php echo $lbl_zone;?></strong>
                            </td>
                            <td class="tbl_data">
                     <?php
					$sql_zone="SELECT idusrteamzone,usrteam_idusrteam,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
					$res_zone=mysql_query($sql_zone);
					$num_zone=mysql_num_rows($res_zone);
					$fet_zone=mysql_fetch_array($res_zone);
						if ($num_zone < 1)
							{
							echo "<div class=\"msg_warninng\">".$msg_warn_contactadmin."</div>";
							exit;
							}
					?>
                    <select name="zone" id="zone" onChange="getregionrole(this.value)">
                    <option value="-1">---</option>
                  	<?php 
					do {
					?>
                    <option <?php if ( (isset($_POST['zone'])) && ($_POST['zone']==$fet_zone['idusrteamzone'])) { echo "selected=\"selected\""; }  ?> value="<?php echo $fet_zone['idusrteamzone'];?>"><?php echo $fet_zone['userteamzonename'];?></option>
                    <?php } while ($fet_zone=mysql_fetch_array($res_zone)); ?>
                  </select>
                            </td>
                           </tr>                            
                        <tr>
                        	<td class="tbl_data" height="40">
                            <strong><?php echo $lbl_role;?></strong>
                            </td>
                            <td class="tbl_data">
                            <div id="zonerolediv">
                            </div>
                            </td>
                        </tr>
                        <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_utitle;?></strong>
                            </td>
                            <td class="tbl_data"><input type="text" name="usertitle" maxlength="20"  value="<?php if (isset($_POST['usertitle'])) { echo $_POST['usertitle']; }?>"  size="15" /></td>
                        </tr>
                         <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_fname;?></strong>
                            </td>
                            <td class="tbl_data">
                           <input type="text" name="fname" maxlength="100"  value="<?php if (isset($_POST['fname'])) { echo $_POST['fname']; }?>"  size="22" />
                            </td>
                        </tr>
                        <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_lname;?></strong>
                            </td>
                            <td class="tbl_data">
                           <input type="text" name="lname" maxlength="100"  value="<?php if (isset($_POST['lname'])) { echo $_POST['lname']; }?>" size="22" />
                            </td>
                        </tr>
						<tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_email;?></strong>
                            </td>
                            <td class="tbl_data">
                           <input type="text" name="useremail" maxlength="100"  value="<?php if (isset($_POST['useremail'])) { echo $_POST['useremail']; }?>" size="22" />
                            </td>
                        </tr>
                        <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_telephone;?></strong>
                            </td>
                            <td class="tbl_data">
                           <input type="text" value="<?php if (isset($_POST['userphone'])) { echo $_POST['userphone']; } else { echo "2547"; }?>" name="userphone"  maxlength="15" size="22" />
                            </td>
                        </tr>
                          <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_status_ac;?></strong>
                            </td>
                            <td class="tbl_data">
                           	<label for="1" style="padding:0px 30px 0px 0px"><input type="radio" name="acstatus" value="1" id="1"> <?php echo $lbl_statusactive;?> </label>
                            <label for="2" style="padding:0px 30px 0px 0px"><input type="radio" name="acstatus" value="0" id="2" checked="checked"> <?php echo $lbl_statusactivenot;?></label>
                            </td>
                        </tr>
                        <tr>
                       	   <td height="40" class="tbl_data"><strong>Send SMS Alert</strong></td>
                            <td class="tbl_data">
                            <label for="alert_sms">
                            <input type="checkbox" value="1" name="alert_sms" id="alert_sms" /> Alert User Via SMS                          
                            </label>
                            </td>
                        </tr>
                        <tr>
                        <td height="70"> </td>
               	   <td align="left">
                    <table border="0" style="margin:5px 10px 5px 0px">
                        	<tr>
                            	<td>
                                <a href="#" onClick="document.forms['addusr'].submit()" id="button_save"></a>
                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="addusr" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onClick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>
                                </td>
                            </tr>
                     </table>
                   </td>
                        </tr>
                </table>
      </form>
</div>
