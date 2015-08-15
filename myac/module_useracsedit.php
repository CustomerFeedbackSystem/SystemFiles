<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['user']))
	{
	$_SESSION['thisuserid']=mysql_escape_string(trim($_GET['user']));
	}

if (isset($_GET['username']))
	{
	$_SESSION['thisusername']=urldecode(mysql_escape_string(trim($_GET['username'])));
	}

//get the role for this userid
$res_role=mysql_query("SELECT usrrole_idusrrole,acstatus FROM usrac WHERE idusrac=".$_SESSION['thisuserid']."");
$fet_role=mysql_fetch_array($res_role);

//store this temporarily for validation purpose incase one has tasks
$_SESSION['thisuserroleid']=$fet_role['usrrole_idusrrole']; 
$_SESSION['thisacstatus']=$fet_role['acstatus'];

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="updateusr") )
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
			
		if ( (strlen($pusrpass)<8) && (strlen($pusrpass)>0) ) //only if it is set - otherwise ignore validation if nothing is entered
			{
			$error2="<div class=\"msg_warning\">".$msg_warning_shortpwd."</div>";
			}
			
		if 	(strlen($pusrpass)>0)
			{
				if 	( (strlen($pusrpass)>7) && ($pusrpass!=$pcusrpass) )
				{
				$error3="<div class=\"msg_warning\">".$msg_warning_matchpwd."</div>";
				}
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
		
		if ( ($num_acunique>0) && (!isset($error1)) && ($pusrac!=$_SESSION['thisusername']) )
			{
			$error7="<div class=\"msg_warning\">".$msg_warning_actaken."</div>";
			}
		
		//1) if the role is different from what was there  or 2) the account status is changed to inactive	
		//then check if this user has any unfinished business
		if ( ($_SESSION['thisuserroleid']!=$prole) || ( ($_SESSION['thisacstatus']==1) && ($pacstatus==0) ) )
			{
			$sql_tasks="SELECT idwftasks FROM wftasks WHERE ( (wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2) OR (wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1))
			AND wftasks.usrrole_idusrrole=".$_SESSION['thisuserroleid']." LIMIT 1 ";
			$res_tasks=mysql_query($sql_tasks);
			$num_tasks=mysql_num_rows($res_tasks); //at least one task
			
			if ($num_tasks > 0)
				{
				$error8="<div class=\"msg_warning\">This user still has pending tasks. Please request the user to finish the tasks or ask his / her supervisor to transfer them.</div>";
				}
			
			}
			
		//if no error, process
		if ( (!isset($error1)) && (!isset($error2)) && (!isset($error3)) && (!isset($error4)) && (!isset($error5)) && (!isset($error6)) && (!isset($error7)) && (!isset($error8)) )
			{
			$update_ac="UPDATE usrac SET
			usrrole_idusrrole=".$prole.",
			usrname='".$pusrac."',
			fname='".$pfname."',
			lname='".$plname."',
			acstatus='".$pacstatus."',
			utitle='".$putitle."',
			usremail='".$puseremail."',
			usrphone='".$puserphone."',
			modifiedby='".$_SESSION['MVGitHub_idacname']."',
			modifiedon='".$timenowis."'
			WHERE idusrac=".$_SESSION['thisuserid']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
			//echo $update_ac;
			mysql_query($update_ac);
			
			$sql_audit_1="INSERT INTO audit_usrac_log (idusrac,usrrole_idusrrole,usrteam_idusrteam,usrname,usrpass,utitle,fname,lname,usrgender,acstatus,acstatus_work,mobileaccess,usremail,usrphone,usersess,createdby,createdon,modifiedby,modifiedon,lastaccess,currentsess,audit_by,audit_on)
			SELECT idusrac,usrrole_idusrrole,usrteam_idusrteam,usrname,usrpass,utitle,fname,lname,usrgender,acstatus,acstatus_work,mobileaccess,usremail,usrphone,usersess,createdby,createdon,modifiedby,modifiedon,lastaccess,currentsess,'".$_SESSION['MVGitHub_idacname']."','".$timenowis."'
			FROM usrac
			WHERE idusrac=".$_SESSION['thisuserid']."";
			mysql_query($sql_audit_1);
			
			//if password is set, then go ahead and update
			if (strlen($pusrpass) >7)
				{
				$update_pwd="UPDATE usrac SET usrpass='".SHA1($pusrpass)."' WHERE idusrac=".$_SESSION['thisuserid']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
				mysql_query($update_pwd);
				}
			$msg_success="<div class=\"msg_success\">".$msg_changes_saved."</div>";
			
			//header('location:'.$_SERVER['PHP_SELF'].'?uction=edit_submod&user='.$fet_id['idusrac'].'&msg_success='.$msg_success.'');
			//exit;
		}//close if no error
	} //close form action

//load my selected user
$sql_userdetails="SELECT usrac.idusrac,usrac.usrrole_idusrrole,usrac.usrteam_idusrteam,usrac.usrname,usrac.usrpass,usrac.utitle,usrac.fname,usrac.lname,usrac.usrgender,usrac.acstatus,usrac.mobileaccess,usrac.usremail,usrac.usrphone,usrac.usersess,usrac.createdby,usrac.createdon,usrac.modifiedby,usrac.modifiedon,usrac.lastaccess,usrrolename,userteamzonename,idusrrole,idusrteamzone FROM usrac 
LEFT JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole 
LEFT JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 
WHERE idusrac=".$_SESSION['thisuserid']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";	
$res_userdetails=mysql_query($sql_userdetails);
$num_userdetails=mysql_num_rows($res_userdetails);
//echo $sql_userdetails;
if ($num_userdetails <1) //possible illegal access attempt - warn and halt
	{
	echo "<div class=\"msg_warning\">".$msg_warn_contactadmin.$ec100."</div>";
	exit;
	}
	
$fet_userdetails=mysql_fetch_array($res_userdetails);

?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div>
    <?php
	if (isset($msg_success)) { echo $msg_success; } 
	if (isset($_GET['msg_success'])) { echo "<div class=\"msg_success\">".$_GET['msg_success']."</div>"; }
 		if ( (isset($error1)) || (isset($error2)) || (isset($error3)) || (isset($error4)) || (isset($error5)) || (isset($error6)) || (isset($error7)) || (isset($error8))  )
		{
			if (isset($error1)) { echo $error1; }
			if (isset($error2)) { echo $error2; }
			if (isset($error3)) { echo $error3; }
			if (isset($error4)) { echo $error4; }
			if (isset($error5)) { echo $error5; }
			if (isset($error6)) { echo $error6; }
			if (isset($error7)) { echo $error7; }
			if (isset($error8)) { echo $error8; }
		}
	?>
    </div>
    <div style="padding:10px">
    <form method="post" name="updateusr" action="">
<table width="100%" border="0" cellpadding="2" cellspacing="0">
<tr>
                    	<td colspan="2" class="table_header" height="35">
                        <?php echo $lbl_edit_ac;?></td>
						<tr>
							<td  height="40" class="tbl_data">
							<strong><?php echo $lbl_username;?></strong>                            </td>
						  <td  class="tbl_data">
                          <script language="javascript">
 function fixme(element) {
 var val = element.value;
 var pattern = new RegExp('[ ]+', 'g');
 val = val.replace(pattern, '');
 element.value = val;
}
						  </script>
                            <input type="text" onkeyup="fixme(this)" onblur="fixme(this)"  maxlength="40" value="<?php if (isset($_POST['usrname'])) { echo $_POST['usrname']; } else { echo $fet_userdetails['usrname']; }?>" size="20" name="usrname" />							</td>
						</tr>
                        <tr>
							<td  height="40" class="tbl_data">
							<strong><?php echo $lbl_password;?></strong>                            </td>
						  <td  class="tbl_data">
                            <input type="password" maxlength="40" value=""  size="20" name="usrpass" />							</td>
						</tr>
                        <tr>
							<td  height="40" class="tbl_data">
							<strong><?php echo $lbl_confirmpassword;?></strong></td>
						  <td  class="tbl_data">
                            <input type="password" maxlength="40" size="20" name="cusrpass"  value=""  />							</td>
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
                    <option <?php if ( (isset($_POST['zone'])) && ($_POST['zone']==$fet_zone['idusrteamzone'])) { echo "selected=\"selected\""; } if ($fet_userdetails['idusrteamzone']==$fet_zone['idusrteamzone']) { echo "selected=\"selected\""; }  ?> value="<?php echo $fet_zone['idusrteamzone'];?>"><?php echo $fet_zone['userteamzonename'];?></option>
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
                            <?php
                            $sql_role="SELECT idusrrole,usrrolename,usrrole_idusrrole,usrroledesc,userteamzonename FROM usrrole 
							LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
							INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 
							WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC,usrrolename ASC";
							$res_role=mysql_query($sql_role);
							$num_role=mysql_num_rows($res_role);
							//echo $sql_role;
							if ($num_role < 1)
								{
								echo "<div class=\"msg_warning\">".$msg_no_role."</div>";
								} else {
									$fet_role=mysql_fetch_array($res_role);
									echo "<select name=\"usrrole\" >";
									echo "<option value=\"NULL\">---</option>";
									if ($fet_userdetails['usrrole_idusrrole']>0)
										{
										echo "<option selected=\"selected\" value=\"".$fet_userdetails['idusrrole']."\">".$fet_userdetails['usrrolename']." - ".$fet_userdetails['userteamzonename']. "</option>";
										}
										do {
										echo "<option value=\"".$fet_role['idusrrole']."\" title=\"".$fet_role['usrroledesc']."\" ";
										//disable selection if the role has already been selected
										if ($fet_role['usrrole_idusrrole']==$fet_role['idusrrole'])
											{
											echo " disabled=\"disabled\" ";
											}
										
										echo ">".$fet_role['usrrolename']." - ".$fet_role['userteamzonename']."</option>";
										} while ($fet_role=mysql_fetch_array($res_role));
									echo "</select>";
								}
							?>   
                            </div>
                            </td>
                        </tr>
                        <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_utitle;?></strong>                            </td>
                            <td class="tbl_data"><input type="text" name="usertitle" maxlength="20"  value="<?php if (isset($_POST['usertitle'])) { echo $_POST['usertitle']; } else { echo $fet_userdetails['utitle']; } ?>"  size="15" /></td>
                        </tr>
                        
                         <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_fname;?></strong>                            </td>
                            <td class="tbl_data">
                           <input type="text" name="fname" maxlength="100"  value="<?php if (isset($_POST['fname'])) { echo $_POST['fname']; } else { echo $fet_userdetails['fname'];}?>"  size="22" />                            </td>
                        </tr>
                        <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_lname;?></strong>                            </td>
                            <td class="tbl_data">
                           <input type="text" name="lname" maxlength="100"  value="<?php if (isset($_POST['lname'])) { echo $_POST['lname']; } else { echo $fet_userdetails['lname']; } ?>" size="22" />                            </td>
                        </tr>
						<tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_email;?></strong>                            </td>
                            <td class="tbl_data">
                           <input type="text" name="useremail" maxlength="100"  value="<?php if (isset($_POST['useremail'])) { echo $_POST['useremail']; } else { echo $fet_userdetails['usremail'];} ?>" size="30" />                            </td>
                        </tr>
                        <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_telephone;?></strong>                            </td>
                            <td class="tbl_data">
                           <input type="text" value="<?php if (isset($_POST['userphone'])) { echo $_POST['userphone']; } else { echo $fet_userdetails['usrphone']; }?>" name="userphone"  maxlength="15" size="22" />                            </td>
                        </tr>
                         <tr>
                        	<td class="tbl_data" height="40">
                           <strong><?php echo $lbl_status_ac;?></strong>                            </td>
                            <td class="tbl_data">
                            <?php
							if ($_SESSION['thisuserid']!=$_SESSION['MVGitHub_idacname'])
								{
							
							
							?>
                           	<label for="1" style="padding:0px 30px 0px 0px"><input type="radio" name="acstatus" value="1" id="1" <?php if ($fet_userdetails['acstatus']==1){ echo "checked=\"checked\""; }?>> <?php echo $lbl_statusactive;?> </label>
                            <label for="2" style="padding:0px 30px 0px 0px"><input type="radio" name="acstatus" value="0" id="2" <?php if ($fet_userdetails['acstatus']==0){ echo "checked=\"checked\""; }?>> <?php echo $lbl_statusactivenot;?></label>
                            <?php
								} else {
								echo $lbl_statusactive;
								echo "<input type=\"hidden\" value=\"1\" name=\"acstatus\">";
								}
							
							
							?>
                            </td>
                        </tr>
                        <tr>
                        <td height="70"> </td>
               	   <td align="left">
                   <?php
				   if ($_SESSION['MVGitHub_idacname']!=$fet_userdetails['idusrac'])
					{
					?>			
                    <table border="0" style="margin:5px 10px 5px 0px">
                        	<tr>
                            	<td>
                                <a href="#" onClick="document.forms['updateusr'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="updateusr" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onClick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                     </table>
                     <?php
					 } else {
							
							echo "<div class=\"msg_warning_small\"> You cannot modify your own admin account</div>";
							
							}
					?>
                     </td>
                        </tr>
                </table>
      </form>
</div>
