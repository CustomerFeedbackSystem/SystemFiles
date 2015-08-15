<?php
require_once('../../assets_backend/be_includes/config.php');
/*require_once('../../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);
*/
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

if ((isset($_POST['formaction'])) && ($_POST['formaction']=="reset_pwd") )
	{
	try
		{
		//echo "so far..";
		// Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
		NoCSRF::check( 'dkm', $_POST, true, 60*10, false );

			$user_account=preg_replace('/[^a-z\-_0-9\.:@\/]/i','',mysql_real_escape_string(trim($_POST['usrac'])));
			$user_email=mysql_escape_string(trim($_POST['usrmail']));
			
			if ( (strlen($user_account)<8) || (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $user_email)) )
				{
				$msg="<div class=\"msg_warning\">".$msg_warning_invalid."</div>";
				} else {
				
				//find if such a user exists
				$sql_useris="SELECT idusrac,utitle,lname FROM usrac WHERE usrname='".$user_account."' AND usremail='".$user_email."' LIMIT 1";
				$res_useris=mysql_query($sql_useris);
				$num_useris=mysql_num_rows($res_useris);
				$fet_useris=mysql_fetch_array($res_useris);
				//echo $sql_useris;
				//if not, just give a general feedback on operation not successful
				if ($num_useris < 1)
					{
					$msg="<div class=\"msg_warning\">".$msg_warning_resetnot."</div>";
					
					} else {
						
					//reset the password by creating a token
					$char_1 = 'abcdefghijklmnopqrstuvwxyz0123456789'; //token for reseting
					$char_2 = 'abcdefghijklmnopqrstuvwxyz0123456789'; //token for reseting
					$char_3 = 'abcdefghijklmnopqrstuvwxyz0123456789'; //token for reseting
					
					$random_str_1=16;
					$token_1 = '';
					 for ($i = 0; $i < $random_str_1; $i++) 
						{
						$token_1 .= $char_1[rand(0, strlen($char_1) - 1)];
						}
					
					$random_str_2=26;
					$token_2 = '';
					 for ($i = 0; $i < $random_str_2; $i++) 
						{
						$token_2 .= $char_2[rand(0, strlen($char_2) - 1)];
						}
						
					$random_str_3=26;
					$token_3 = '';
					 for ($i = 0; $i < $random_str_3; $i++) 
					{
					$token_3 .= $char_3[rand(0, strlen($char_3) - 1)];
					}
					
				//insert the new token details
				$sql_token="INSERT INTO usrpwdreminder (userid,useremail,token1,token2,token3,visited_on,visited_ip,visited_uri)
				VALUES ('".$fet_useris['idusrac']."','".$user_email."','".$token_1."','".$token_2."','".$token_3."','".$timenowis."','".$_SERVER['REMOTE_ADDR']."','".curPageURL()."')";
				mysql_query($sql_token);

	
				//the send the token to the user
				//send an email to that effect
$to = $user_email;

$url = "".$_SERVER["SERVER_NAME"]."/training/reset_password/reset_now/index.php?reset_=".$fet_useris['idusrac']."&tk1=".$token_1."&tk2=".$token_2."&tk3=".$token_3."";
						
$message = "Dear ".$fet_useris['utitle']." ".$fet_useris['lname'].", \n
We have received a Password Reset Request from your account. If you wish to reset your password, please click on the link below ( or paste it on your browser ) within 24 hours.\n
IMPORTANT! Please ensure that you are within your Company Network to access the link below.
". html_entity_decode($url)."\n\n\nHowever, if you did not request for password reset or in case you remembered your password, you can ignore this email.\n
\n\nBest Regards,\n\nSupport Team,\n".$pagetitle.".\n\nDISCLAIMER: You received this email because your email address was used on ".$pagetitle.".The Information contained in this email, including the links, is intended solely for the use of the designated recipient.If you have received this e-mail message in error please notify the ".$pagetitle." team through e-mail ".$support_email." and delete it immediately";
				// Additional headers
				$sendername=$pagetitle;	
					
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '.$sendername.' <'.$support_email.'>' . "\r\n";
				$headers .= "Reply-To: ".$support_email."\r\n";
				$headers .= "Return-Path: ".$support_email."\r\n";
				
				
				//	$headers .= 'To: '..' <'.$youremail.'>' . "\r\n";
						
				$subject = "".$pagetitle." Password Reset Instructions";
				// Mail it
				mail($to, $subject, $message, $headers);
					
				/*
				if ($mailserver_avail==1)
					{
					mail($to,$subject,$message,$headers);
					
					} else {
					//if mail server is not available, then save the function in a variable and parse this to the online server for processing
					$sql_mailout="INSERT INTO mdata_emailsout (email_to,email_subject,email_message,email_headers,createdon) 
					VALUES ('".$to."','".$subject."','".$message."','".$headers."','".$timenowis."')";
					mysql_query($sql_mailout);
					}
				*/
				
				//just send this directly via curl
				
				
				
				$acknowledge=1;
				
				$msg="<div class=\"msg_success\">".$msg_warning_reset."</div><div class=\"text_body\"><a href=\"../\">Back to Login Page</a></div>";
					}
				} // if no error
		
			} catch ( Exception $e ) {
		// CSRF attack detected
		$result = $e->getMessage() . ' Form ignored.';
		}
		
	} //close form
	
$token = NoCSRF::generate( 'dkm' );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="180;URL=../../access" />
<title><?php echo $pagetitle;?> - <?php echo $fet_team['usrteamname'];?></title>
<link href="../../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
	<div><?php require_once('../a/header.php');?></div>
    <div>
    	<table border="0" cellpadding="3" cellspacing="0" width="100%">
        	<tr>
              <td width="50%"></td>
              <td valign="top" width="50%" style="padding:80px 20px 0px 10px" align="left">
              	<div ><?php if (isset($msg)) { echo $msg; } ?> </div>
                <?php
				if (!isset($acknowledge)) { 
				?>
                <div>
                <form method="post" action="" class="inline" name="reset">
                    <table width="400px" height="221" border="0" cellpadding="2" cellspacing="0" class="border_thick_alt">
		  				<tr>
                            <td height="40" colspan="2" class="table_header_alt"><?php echo $lbl_login_password;?></td>
                        </tr>
                       
                        <tr>
                            <td width="153" height="55">
                              <strong><?php echo $lbl_email;?></strong> </td>
                          <td width="260" height="55">
                          <input name="usrmail" type="text" id="usrmail"  autocomplete="off" size="30" maxlength="80" />                          </td>
                     </tr>
                         <tr>
                            <td height="40">
                              <strong><?php echo $lbl_mvac;?></strong> </td>
                           <td height="40"><input name="usrac"  autocomplete="off" type="text" id="usrac" size="30" maxlength="20" /></td>
                      </tr>
                        <tr>
                            <td height="55"></td>
                          <td>
                            <a href="#" onclick="document.forms['reset'].submit()" id="button_submit"></a> 
                          </td>
                        </tr>
                        <tr>
                        	<td colspan="2" class="text_body">
                            <input type="hidden" value="reset_pwd" name="formaction" />
                              <a href="../../user_login/a"><?php echo $link_bklogin;?></a>
                             <input type="hidden" value="<?php echo $token;?>" name="dkm" />
                              </td>
                      </tr>
                    </table>
                </form>
               	</div>
				<?php
				}
				?>
              </td>
            
            </tr>
        </table>
    </div>
    <div>
    </div>
</div>
<?php
if ( (isset($acknowledge)) && ($acknowledge==1) )
	{
//process only if is OK
//Place your Mail Function below
				  
	}				  
?>
<div class="text_small" style="background-color:#F6F6F6; padding:15px; position:fixed; bottom:1px; width:100%; font-size:10px ">

</div>
</body>
</html>
