<?php
require_once('../../assets_backend/be_includes/config.php');
//require_once('../../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
if (!isset($_POST['dkm'])) //
	{
	include ('../../nocsrf.php');
	}
//declare some global variables to uniquely identify this water company from the Database
$pre_userteamid=1; // this sets the current user team id

$sql_team="SELECT usrteamname ,mainlogo_path,smalllogo_path,introtxt FROM usrteam WHERE idusrteam=".$pre_userteamid."";
$res_team=mysql_query($sql_team);
$num_team=mysql_num_rows($res_team);
$fet_team=mysql_fetch_array($res_team);
//echo $_GET['reset_']."<br>";
//echo $_GET['tk1']."<br>";
//echo $_GET['tk2']."<br>";
//echo $_GET['tk3']."<br>";
//validate the link that was used to get here in the first place
if ( (isset($_GET['reset_'])) && (isset($_GET['tk1'])) && (isset($_GET['tk2'])) && (isset($_GET['tk3'])) )
	{

			
			$userid=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['reset_'])));
			$tk1=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['tk1'])));
			$tk2=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['tk2'])));
			$tk3=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['tk3'])));	
			
			$sql_valpag="SELECT idusrpwdreminder,visited_on,password_reset FROM usrpwdreminder WHERE userid=".$userid." 
			AND token1='".$tk1."' AND token2='".$tk2."' AND token3='".$tk3."' LIMIT 1";
			$res_valpag=mysql_query($sql_valpag);
			$num_valpag=mysql_num_rows($res_valpag);
			$fet_valpag=mysql_fetch_array($res_valpag);
		
			if ($num_valpag < 1)
				{
				echo "<link href=\"../../assets_backend/css/style.css\" rel=\"stylesheet\" type=\"text/css\" />";
				require_once('../../admin/a/header.php');
				echo "<div class=\"msg_warning\" style=\"padding:0px 0px 0px 300px\">[1]".$msg_warning_oldlink."</div><div>";
				exit;
				}
				
			if ($fet_valpag['password_reset']!='0000-00-00 00:00:00')
				{
				echo "<link href=\"../../assets_backend/css/style.css\" rel=\"stylesheet\" type=\"text/css\" />";
				require_once('../../admin/a/header.php');
				echo "<div class=\"msg_warning\" style=\"padding:0px 0px 0px 300px\">[2]".$msg_warning_oldlink."</div>";
				exit;
				}
			
			$expir=date("Y-m-d H:i:s",strtotime($fet_valpag['visited_on']) + (1*86400));
			
			if ($expir<$timenowis)//old link
				{
				echo "<link href=\"../../assets_backend/css/style.css\" rel=\"stylesheet\" type=\"text/css\" />";
				require_once('../../admin/a/header.php');
				echo "<div class=\"msg_warning\" style=\"padding:0px 0px 0px 300px\">[3]".$msg_warning_oldlink."</div>";
				exit;
				}
			
			
			
			if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="send") )
				{
					
				try
					{
					// Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
					NoCSRF::check( 'dkm', $_POST, true, 60*10, false );
				
					$pusrpass = mysql_escape_string(trim($_POST['usrpass']));
					$pcusrpass = mysql_escape_string(trim($_POST['cusrpass']));
				
					if ( (strlen($pusrpass)<8) || (strlen($pcusrpass)<8)  )
						{
						$msg="<div class=\"msg_warning\">".$msg_warning_shortpwd."</div>";
						$error_short_password="YES";
						}
						
					if ( (!isset($error_short_password)) && ((preg_match('/[A-Z]/', $pcusrpass) && preg_match('/[a-z]/', $pcusrpass)  && preg_match('/[0-9]/', $pcusrpass)) ) )
						{
						$pwdsecure="SECURE";
						} else {
						$error_pwd_policy="<div class=\"msg_warning\">".$msg_warning_pwdpolicy."</div>";
						}
					
					
					
					if ( (isset($pwdsecure)) && ($pwdsecure=="SECURE") && (!isset($error_short_password)) && (!isset($error_pwd_policy)) ) 
						
						{
					
						//find if such a user exists
						$sql_useris="SELECT idusrpwdreminder,acstatus,visited_on,usremail,idusrac,utitle,lname FROM usrpwdreminder 
						INNER JOIN usrac ON usrpwdreminder.userid=usrac.idusrac 
						WHERE userid='".$userid."' AND token1='".$tk1."' AND token2='".$tk2."' AND token3='".$tk3."'  LIMIT 1";
						$res_useris=mysql_query($sql_useris);
						$num_useris=mysql_num_rows($res_useris);
						$fet_useris=mysql_fetch_array($res_useris);
						
						//if not, just give a general feedback on operation not successful
						if ($num_useris < 1) //checks if the link is valid
							{
							$msg_error="<div class=\"msg_warning\">".$msg_warning_invlink."</div>";
							} 
						
						if ($pusrpass != $pcusrpass) //checks if the link is valid
							{
							$msg_error="<div class=\"msg_warning\">".$msg_warning_matchpwd."</div>";
							} 
						
						if ($fet_useris['acstatus']==0)//inactive account
							{
							$msg_error="<div class=\"msg_warning\">".$msg_warning_acinactive."</div>";
							}
							
						//calculate expiry date for this link
						$expireon=date("Y-m-d H:i:s",strtotime($fet_useris['visited_on']) + (1*86400));	
							
						if ($expireon<$timenowis)//old link
							{
							$msg_error="<div class=\"msg_warning\">".$msg_warning_oldlink."</div>";
							}
						
						if (!isset($msg_error))
							{		
						
							//reset the password
							$sql_update="UPDATE usrac SET usrpass='".SHA1($pusrpass)."' WHERE idusrac=".$fet_useris['idusrac']." LIMIT 1 ";
							mysql_query($sql_update);
							
							//update token details
							$sql_token="UPDATE usrpwdreminder SET password_reset='".$timenowis."' WHERE idusrpwdreminder=".$fet_useris['idusrpwdreminder']."";
							mysql_query($sql_token);
					
							//the send the token to the user
							//send an email to that effect
							$to = $fet_useris['usremail'];
									
							$message = "
							Dear ".$fet_useris['utitle']." ".$fet_useris['lname'].", <br>
							This is to confirm that your Password to your ".$pagetitle." account has been reset successfuly.
							<br><br>
							Regards,<br>
							Support Team,<br>
							".$pagetitle."
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
							
							$acknowledge=1;
								
							$msg="<div class=\"msg_success\">".$msg_pwdreset_success."</div><div><a href=\"".$_SERVER["SERVER_NAME"]."\">Account Log In</a></div>";
							
							
							}//is not set error message
							
						} //close secure
						
					} catch ( Exception $e ) {
						// CSRF attack detected
						$result = $e->getMessage() . ' Form Error ';
					}
		
				} //close if form is set
$token = NoCSRF::generate( 'dkm' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle;?> - <?php echo $fet_team['usrteamname'];?></title>
<link href="../../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
	<div><?php require_once('../../admin/a/header.php');?></div>
    <div>
    	<table border="0" cellpadding="3" cellspacing="0" width="100%">
        	<tr>
             <td valign="top" width="100%" style="padding:45px 50px 0px 350px" align="left">
             	<div style="margin:0px 0px 25px 0px">
                <?php
				if (!isset($acknowledge)) {
				echo "<span class=\"msg_instructions\">".$msg_password_reset."<span>";
				}
				
                ?>
                </div>
           	   <div ><?php if (isset($msg)) { echo $msg; }
			   if (isset($msg_error)) { echo $msg_error; } 
			   if (isset($error_pwd_policy)) { echo $error_pwd_policy; }
			   ?> </div>
                <div>
                <?php
				if (!isset($acknowledge)) {
				?>
                <form method="post" action="" class="inline" name="reset">
                    <table width="458" height="221" border="0" cellpadding="2" align="center" cellspacing="0" class="border_thick_alt">
  				  <tr>
                            <td height="40" colspan="2" class="table_header_alt"><?php echo $lbl_pwdreset;?></td>
                        </tr>
                       
                        <tr>
                            <td width="171" height="55">
                              <strong><?php echo $lbl_newpassword;?></strong> </td>
                          <td width="275" height="55">
                          <input name="usrpass" type="password" id="usrpass"  autocomplete="off" size="30" maxlength="30" />                          </td>
                     </tr>
                         <tr>
                            <td height="40">
                              <strong><?php echo $lbl_confirmnewpassword;?></strong> </td>
                           <td height="40"><input name="cusrpass"  autocomplete="off" type="password" id="cusrpass" size="30" maxlength="30" /></td>
                      </tr>
                        <tr>
                            <td height="55"></td>
                          <td>
                            <a href="#" onclick="document.forms['reset'].submit()" id="button_submit"></a> 
                          </td>
                        </tr>
                        <tr>
                        	<td colspan="2" class="text_body">
                            <input type="hidden" value="<?php echo $token;?>" name="dkm" />
                            <input type="hidden" value="send" name="formaction" />
                              </td>
                      </tr>
                    </table>
                </form>
                <?php
				}
				?>
                </div>
              </td>
            
          </tr>
        </table>
    </div>
    <div>
    </div>
</div>
<?php
}
?>
</body>
</html>
