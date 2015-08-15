<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login.php');

if (isset($_GET['do_ths']))
	{
	$_SESSION['do_ths']=mysql_escape_string(trim($_GET['do_ths']));
	}

if (isset($_GET['idrole']))
	{
	$_SESSION['idrole']=mysql_escape_string(trim($_GET['idrole']));
	}	
	
if (isset($_GET['idwfco']))
	{
	$_SESSION['idwfco']=mysql_escape_string(trim($_GET['idwfco']));
	}

if ( (isset($_GET['action'])) && ($_GET['action']=="del") )
	{
	$del=mysql_escape_string(trim($_GET['del']));
	$res_del="UPDATE wftasks_co SET 
	disabled_on='".$timenowis."',
	disabled_by=".$_SESSION['MVGitHub_idacname'].",
	co_status=2 
	WHERE idwftasks_co=".$del."";
	mysql_query($res_del);
	
	header('location:pop_colist.php?msg_del=1');
	exit;
	}
		
//variable for message
$var_sms=$_SESSION['MVGitHub_userteamshortname']. " : ".$_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname']." wants to handle tasks for you. Please give him validation code";

//validate
if ((isset($_POST['formaction'])) && ($_POST['formaction']=="validate") )
	{
	//
	$valcode=mysql_escape_string(trim($_POST['valcode']));
	
	//if there are values else
	if (strlen($valcode) <5)
		{
		$msg_validate= "<div class=\"msg_warning_small\">Invalid Validation Code</div>";
		} else {
		//check if valid	
		$res_valid=mysql_query("SELECT validation_code,fname FROM wftasks_co INNER JOIN usrac ON wftasks_co.idusrrole_owner=usrac.usrrole_idusrrole WHERE validation_code='".$valcode."' AND idwftasks_co=".$_SESSION['idwfco']."");
		$fet_valid=mysql_fetch_array($res_valid);
		$num_valid=mysql_num_rows($res_valid);

		if ($num_valid >0)
			{
			$update_wftasks="UPDATE wftasks_co SET co_status=1,
			validation_code_valattempt='".$timenowis."',
			validation_code_validated='".$timenowis."'
			WHERE idwftasks_co=".$_SESSION['idwfco']."";
			mysql_query($update_wftasks);
			$msg_validate="<div class=\"msg_success_small\">Validation Successful! You are now incharge of ".$fet_valid['fname']."'s tasks</div>";
			} else {
			$update_wftasks="UPDATE wftasks_co SET 
			validation_code_valattempt='".$timenowis."'
			WHERE idwftasks_co=".$_SESSION['idwfco']."";
			mysql_query($update_wftasks);
			$msg_validate="<div class=\"msg_warning_small\">Sorry! Validation Failed - Invalid code</div>";
			}
		}
	}
			
//send sms
if ((isset($_POST['formaction'])) && ($_POST['formaction']=="send_sms") )
	{
	//get code
	$res_code=mysql_query("SELECT validation_code,usrphone,fname,lname FROM wftasks_co INNER JOIN usrac ON wftasks_co.idusrrole_owner=usrac.usrrole_idusrrole 
	WHERE idwftasks_co=".$_SESSION['idwfco']."");
	$fet_code=mysql_fetch_array($res_code);
	
	//compose the phone number 0722123456
	if (strlen($fet_code['usrphone'])==10)
		{
		$tktphone="254".substr($fet_code['usrphone'],1,9);
		} else if (strlen($fet_code['usrphone'])==12) {
		$tktphone=trim($fet_code['usrphone']);
		}
	//echo "<br><br><bR><br><br><br>--->".$tktphone;
	if (strlen($tktphone)==12)
		{
	
		//send the update
		$update="UPDATE wftasks_co SET validation_code_sent='".$timenowis."' WHERE idusrrole_owner=".$_SESSION['idrole']." AND idusrrole_acting=".$_SESSION['MVGitHub_iduserrole']." AND co_status<2 LIMIT 1";
		mysql_query($update);
	
		//send text message
		$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext) 
		VALUES ('".$tktphone."','".$var_sms.":".$fet_code['validation_code']."')";
		$res_smsout=mysql_query($sql_smsout);				
		
		$sent_sms=1;
		$msg_success="<div class=\"msg_success_small\">SMS has been sent successfully. Ask ".$fet_code['fname']." for the validation code in about 5 minutes</div>";
		
		} else {
			
			$msg_fail="<div class=\"msg_warning_small\">Error! The User's Mobile Phone number is invalid</div>";
			
		}
	}
//now, if the action was to enable, then do the process below
if ($_SESSION['do_ths']=="enable")
	{
	//validate
	//1- check if the usrrole is defined AND check if usrrole belongs to this water company
	$res_usr=mysql_query("SELECT idusrrole,idusrac,fname,lname,usrphone
	FROM usrac 
	INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole
	INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
	WHERE usrrole.idusrrole=".$_SESSION['idrole']." AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."
	AND usrrole.idusrrole!=".$_SESSION['MVGitHub_iduserrole']."");
	
	$num_usr=mysql_num_rows($res_usr);
	$fet_usr=mysql_fetch_array($res_usr);
	
	if ( ($num_usr > 0) && (strlen($fet_usr['usrphone'])==12) )
		{
		//if there is no code, then go ahead and add
		$res_co=mysql_query("SELECT validation_code FROM wftasks_co WHERE co_status=0 AND idusrrole_owner=".$fet_usr['idusrrole']."");
		$num_co=mysql_num_rows($res_co);
		$fet_co=mysql_fetch_array($res_co);
		
		//
		//	if ( ($num_co <1) || (($num_co>0) && (strlen($fet_co['validation_code']<1)) )
			if ($num_co <1)
				{
				//create the code
				$val_code=rand(10000,99999);				
				//insert
				$sql_insert="INSERT INTO wftasks_co (idusrrole_acting,idusrac_acting,idusrrole_owner,idusrac_owner,created_on,created_by,co_status,validation_code,validation_code_created ) 
				VALUES ('".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$_SESSION['idrole']."','".$fet_usr['idusrac']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."','0','".$val_code."','".$timenowis."')";
				mysql_query($sql_insert);
				
				}
		
		} else {
		echo "<div class=\"msg_warning_small\">[Code 23] Fatal Error!</div>";
		if (strlen($fet_usr['usrphone'])!=12)
			{
			echo "<div class=\"msg_warning_small\">Missing valid Mobile Phone Number</div>";
			}
		}
	//2 - 
	
	//3 - check 
	
	}
	
//check status of the tasks co
$res_tasksco=mysql_query("SELECT idwftasks_co,co_status,validation_code,usrphone,fname,lname,validation_code_sent FROM wftasks_co 
INNER JOIN usrac ON wftasks_co.idusrrole_owner=usrac.usrrole_idusrrole 
WHERE idusrrole_owner=".$_SESSION['idrole']." AND idusrrole_acting=".$_SESSION['MVGitHub_iduserrole']." AND co_status<2");	
$fet_tasksco=mysql_fetch_array($res_tasksco);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body style="background-color:#ffffff">
<div class="tbl_sh" style="position:fixed; margin:0px 0px 35px 0px; padding:0px; top:0px; width:100%">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
  		<tr>
        	<td width="60%">
            <div>
            C / O Tasks
            </div>
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
<div style="padding:35px 10px 0px 10px">
  <div class="text_small">
	<a href="pop_colist.php?faction=all">&laquo; Back to My C / O List</a>
	</div>
  	<div>
  <table border="0" width="100%">
        	<tr>
            	<td valign="top" width="60%">
                
                  <?php
                  if ($fet_tasksco['co_status']==0)
                    {
                   ?>
                    <div class="tbl_sh">
                    STEP 1 : Request <?php echo $fet_usr['fname']." ".$fet_usr['lname'];?> for a Validation Code via SMS
                    by clicking on 'SEND' button below</div>
                    <div>
				  <?php 
				  if (isset($msg_success)) { echo $msg_success; } 
				  if (isset($msg_fail)) { echo $msg_fail;}
				  if (isset($sent_sms))
				  	{
				 	echo "<br><a href=\"pop_colist.php\" style=\"border:1px solid #cccccc; background-color:#CCCCCC; border-radius:5px; padding:5px 8px; text-decoration:none\">OK</a>";
					exit;
					}
				  ?>
                  </div>
                    <div style="background-color:#f7f7f7; margin:10px 0px">
                    <form method="post" action="" name="request_sms">
                    <table border="0" cellpadding="2" cellspacing="0" width="400px">
                        <tr>
                            <td class="text_body" >
                            <div><small>SMS Message </small></div>
                            <div style="background-color:#EAFFEA; padding:5px">
                            <em><strong><?php
                            echo $var_sms;
                            ?></strong></em>
                            </div>
                            </td>
                       </tr>
                       <tr>
                            <td style="text-align:center">
                            <?php
                            if ( ($fet_tasksco['validation_code_sent']=='0000-00-00 00:00:00') || ( ((strtotime($timenowis)-strtotime($fet_tasksco['validation_code_sent']))>3600)  ) )
                                {
                                //if (strlen($fet_usr['usrphone'])==12)
                                //	{
                            ?>
                            <input type="hidden" name="formaction" value="send_sms" />
                            <a href="#" onclick="document.forms['request_sms'].submit()" id="button_send"></a> 
                            <?php
                                //	}
                                }
                            ?>
                            </td>
                      </tr>
                    </table>
                    
                    </form>
                    </div>
                    <?php
                    }
                    
                    if ($fet_tasksco['co_status']==1)
                        {
                    
                    //check if this user has sent the validation code and if not, then request him to do so first
                    $res_val=mysql_query("SELECT validation_code_sent,fname,lname FROM wftasks_co INNER JOIN usrac ON wftasks_co.idusrrole_owner=usrac.usrrole_idusrrole  WHERE idwftasks_co=".$_SESSION['idwfco']."");	
                    $fet_val=mysql_fetch_array($res_val);
                    $num_val=mysql_num_rows($res_val);
                
                    ?>
                     <div class="tbl_sh">
                     Request <?php echo $fet_val['fname']." ".$fet_val['lname'];?> for a Validation Code via SMS
                    </div>
                    <div style="background-color:#f7f7f7; margin:10px 0px">
                    <form method="post" action="" name="request_sms">
                    <table border="0" cellpadding="2" cellspacing="0" width="400px">
                        <tr>
                            <td class="text_body" style="background-color:#EAFFEA">
                            <div style="padding:2px 2px 10px 0px"><strong>Send SMS Message to <?php echo $fet_val['fname']." ".$fet_val['lname'];?>:</strong></div>
                            <div>
                            <em><strong><?php
                            echo $var_sms;
                            ?></strong></em>
                            </div>
                            </td>
                       </tr>
                       <tr>
                            <td style="background-color:#EAFFEA; text-align:center">
                            <?php
                            if ( ($fet_tasksco['validation_code_sent']=='0000-00-00 00:00:00') || ( ((strtotime($timenowis)-strtotime($fet_tasksco['validation_code_sent']))>3600)  ) )
                                {
                                //if (strlen($fet_usr['usrphone'])==12)
                                //	{
                            ?>
                            <input type="hidden" name="formaction" value="send_sms" />
                            <a href="#" onclick="document.forms['request_sms'].submit()" id="button_send"></a> 
                            <?php
                                //	}
                                }
                            ?>
                            </td>
                      </tr>
                    </table>
                    
                    </form>
                    </div>
                    <?php
					}
					?>
                    <?php
                     if (isset($msg_validate)) { echo $msg_validate; }
                     ?> 
                   <?php
				   
				   //check status and also validation code is already sent
                   if (($fet_tasksco['co_status']==0) && ($fet_tasksco['validation_code_sent']!='0000-00-00 00:00:00'))
                        {
                    ?>
                    <hr />

                     <div class="tbl_sh" style="margin:10px 5px">
                     STEP 2 : Validate the Care Of Task
                     </div>
                    
                     <div>
                    <div style="background-color:#f7f7f7">
                    <form method="post" name="search_usr">
                    <table border="0">
                        <tr>
                            <td>SMS Validation Code</td>
                            <td>
                            <input type="text" name="valcode" size="30" maxlength="10" id="valcode" />
                            </td>
                            <td>
                            <input type="hidden" value="validate" name="formaction" />
                            <a href="#" id="button_submit"  onclick="document.forms['search_usr'].submit()"></a>
                           
                            </td>
                        </tr>
                    </table>
                    </form>
                    </div>
                   
                </div><?php
                   }
                   ?>
                </td>
                <td width="4%" valign="top" class="title_header_blue">&nbsp;</td>
                <td width="36%" valign="top">&nbsp;</td>
          </tr>
        </table>
    </div>
    <?php
	//
		if ($fet_tasksco['co_status']<2) 
			{
	?>
    <hr />
    <div style="padding:10px 15px">
    <a onclick="return confirm('Are you sure you want to release  <?php echo $fet_tasksco['fname'];?> tasks from your profile? Click OK to proceed otherwise Cancel');" href="<?php echo $_SERVER['PHP_SELF'];?>?action=del&amp;del=<?php echo $fet_tasksco['idwftasks_co'];?>" style="border:1px solid #cccccc; background-color:#CCCCCC; border-radius:5px; padding:5px 8px; text-decoration:none">
    Disable / Delete / Remove <?php echo $fet_tasksco['fname'];?>'s tasks from my Profile
    </a>
    </div>
    <?php
			} else {
			echo "<div class=\"msg_instructions_small\">This users tasks are now no longer available in your profile</div>";
			}
 
	?>
</div>
</body>
</html>
