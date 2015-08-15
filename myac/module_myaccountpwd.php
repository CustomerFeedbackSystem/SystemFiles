<?php //require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

//if for update
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="reset_pass") )
	{
	//clean up the variables
	$f_upassold=mysql_escape_string(trim($_POST['currpass']));
	$f_upassnew=mysql_escape_string(trim($_POST['usrpass']));
	$f_upassconfirm=mysql_escape_string(trim($_POST['cusrpass']));
		
	//check if the current password is the valid password
	$sql_checkpwd = "SELECT idusrac,usremail,usrpass,usrname,fname,lname,utitle FROM usrac WHERE usrpass='".SHA1($f_upassold)."' AND idusrac=".$_SESSION['MVGitHub_idacname']." LIMIT 1";
	$res_checkpwd = mysql_query($sql_checkpwd);
	$num_checkpwd = mysql_num_rows($res_checkpwd);
	$fet_checkpwd = mysql_fetch_array($res_checkpwd);
//	echo $sql_checkpwd;
	//validate

	if (strlen($f_upassnew) < 8)
		{
		$error1="<div class=\"msg_warning\">[Error 1] Your New Password must be at least 8 characters containing a Number, CAPITAL AND small Letters</div>";
		}
		
	if (!isset($error1)) 
		{		
		if ( SHA1($f_upassold)!=$fet_checkpwd['usrpass'] )
			{
			$error2="<div class=\"msg_warning\">[Error 2] Your 'Current Password' is Wrong</div>";
			}
		}
			
	if  (!isset($error2)) 
			{
				if ($f_upassnew!=$f_upassconfirm) 
				{
				$error3="<div class=\"msg_warning\">[Error 3] Please Confirm your New Password </div>";
				}
			}
		
	if  ( (!isset($error1)) && (!isset($error2)) && (!isset($error3)) )
		{
			//check the composition of the password
			if (preg_match('/[A-Z]/', $f_upassnew) && preg_match('/[a-z]/', $f_upassnew)  && preg_match('/[0-9]/', $f_upassnew))
				{
				$pwdsecure="SECURE";
				} else {
				$error4="<div class=\"msg_warning\">[Error 4] Your New Password must be at least 8 characters long with a mixture of Numbers, Capital AND Small Letters</div>";
				}
		}
	
	//echo $num_checkpwd;
	if ( $num_checkpwd < 1 )
		{
		$error5 = "<div class=\"msg_warning\">[Error 5] Your 'Current Password' is Wrong</div>";
		} else {
		//else if the 
			if ( (!isset($error1)) && (!isset($error2)) && (!isset($error3)) && (!isset($error4))  && (!isset($error5)) && (isset($pwdsecure)) && ($pwdsecure=="SECURE") )
				{
				//go ahead and update the database
				$sql_update="UPDATE usrac SET 
				usrpass = '".SHA1($f_upassnew)."'
				WHERE idusrac=".$_SESSION['MVGitHub_idacname']." LIMIT 1";
				mysql_query($sql_update);
				//echo $sql_update;
				$msg_success= "<div class=\"msg_success\">".$msg_password_saved."</div>";
				
				//then if this happens, reset this current users session for password
				$_SESSION['MVGitHub_acpass'] = SHA1($f_upassnew);
				
				//send them an automatic email notifying them that they have changed the password
				$to = $fet_checkpwd['usremail'];
						
				$message = "
				Dear ".$fet_checkpwd['utitle']." ".$fet_checkpwd['lname'].", <br>
				This is an Automated Confirmation to notify you that your Account Password has been reset successfuly.
				<br><br>
				Regards,<br>
				Support Team,<br>
				".$pagetitle.".
				<br><br>
				<div>
				DISCLAIMER: You received this email because your email address was used on ".$pagetitle."
				The Information contained in this email, including the links, is intended solely for the use of the designated recipient.
				If you have received this e-mail message in error please notify the ".$pagetitle." team through e-mail ".$support_email." and delete it immediately
				</div>";
						
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				
				// Additional headers
				$sendername=$pagetitle;		//	$headers .= 'To: '..' <'.$youremail.'>' . "\r\n";
				$headers .= 'From: '.$sendername.' <'.$support_email.'>' . "\r\n";
						
				$subject = "".$pagetitle." Password Reset Confirmation";
				// Mail it
				//mail($to, $subject, $message, $headers);
					
				mail($to,$subject,$message,$headers);
			
					
				}
				
		}
	
	}
?>
<div>
    <div >
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo str_replace('_',' ',$fet_heading['modulename']); ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
    </table>
    </div>
    <div>
    <?php
	if (isset($error1)) { echo $error1; }
	if (isset($error2)) { echo $error2; }
	if (isset($error3)) { echo $error3; }
	if (isset($error4)) { echo $error4; }
	if (isset($error5)) { echo $error5; }
	if (isset($msg_success)) { echo $msg_success; } 
	?>
    </div>
    <div style="padding:10px 0px 10px 0px">
    <table border="0" width="100%">
    	<tr>
        	<td valign="top" width="59%">
            <?php
			if (!isset($msg_success)) {
			?>
            <div class="table_header">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
				<?php  echo $lbl_password; ?>
                </td>
                <td width="50">&nbsp;</td>
			</tr>
            </table>
            </div>
            <?php
			}
			?>
           <div>
             <div>
<?php if (!isset($msg_success)) { ?>             
<form method="post" action="" name="form_pwd" autocomplete="off">             
            <table border="0" width="100%" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
            	<tr>
                	<td height="35" class="tbl_data">
                      <strong><?php echo $lbl_username;?></strong></td>
                  <td height="35" class="tbl_data">
                   <?php echo $_SESSION['MVGitHub_acname'];?></td>
                </tr>
                <tr>
                	<td height="35" class="tbl_data">
                      <strong><?php echo $lbl_cpassword;?></strong></td>
                  <td height="35" class="tbl_data">
                  <input type="password" maxlength="40" value="<?php if (isset($_POST['currpass'])) { echo $_POST['currpass']; } ?>"  size="20" name="currpass" id="currpass" /></td>
                </tr>
                 <tr>
                	<td width="216" height="40" class="tbl_data">
							<strong><?php echo $lbl_newpassword;?></strong>                            </td>
				  <td width="348" class="tbl_data">
                <input type="password" maxlength="40" value=""  size="20" name="usrpass" />
							</td>
					</tr>
                        <tr>
							<td width="216" height="40" class="tbl_data"><strong>Re-Enter your  New Password</strong></td>
						  <td width="348" class="tbl_data">
                            <input type="password" maxlength="40" size="20" name="cusrpass"  value=""  />
							</td>
                </tr>
                <tr>
                	<td height="35" class="tbl_data">&nbsp;</td>
					<td height="55" class="tbl_data">
                    <input type="hidden" value="reset_pass" name="formaction" />
                  <a href="#" onClick="document.forms['form_pwd'].submit()" id="button_save"></a>
                  </td>
                </tr>
            </table>
            </form>
<?php } ?>            
            </div>
            </div>
          </td>
          <td valign="top" width="41%" >            </td>
      </tr>
    </table>
    </div>
</div>
