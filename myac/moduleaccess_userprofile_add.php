<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ((isset($_POST['formaction'])) && ($_POST['formaction']=="newprofile"))
	{
	//clean up the data
	$fprof=mysql_escape_string(trim($_POST['profile_name']));
	$fzone=trim($_POST['new_zone']);

	

	if (strlen($fprof) < 1)
		{
		$error_1="<div class=\"msg_warning\">".$msg_warning_profile."</div>";
		}
	if ($fzone < 1)
		{
		$error_2="<div class=\"msg_warning\">".$msg_warning_teamzone."</div>";
		}
		
	if ( (strlen($fprof)) > 0 && ($fzone > 0) ) 
		{
	//validate - ensure no duplicate profile for this team
	$sql_duplicate="SELECT idsysprofiles FROM sysprofiles WHERE usrteamzone_idusrteamzone=".$fzone." AND sysprofile='".$fprof."' LIMIT 1";
	$res_duplicate=mysql_query($sql_duplicate);
	$num_duplicate=mysql_num_rows($res_duplicate);
	//echo $sql_duplicate;
			if ($num_duplicate > 0)
			{
			$error_3="<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
			}
		}
		
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) )
		{
		$sql_newzone="INSERT INTO sysprofiles (usrteamzone_idusrteamzone,sysprofile,createdby,createdon) 
		VALUES ('".$fzone."','".$fprof."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
		mysql_query($sql_newzone);
		
		//retrieve the last profile id
		$sql_id="SELECT idsysprofiles FROM sysprofiles WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idsysprofiles DESC LIMIT 1";
		$res_id=mysql_query($sql_id);
		$fet_id=mysql_fetch_array($res_id);
		
		if (isset($_POST['profile']))
			{
			$idprofile_parent=mysql_escape_string(trim($_POST['profile']));
			}
		if ($idprofile_parent > 0)
			{	
			//get the permissions of this profile
			$sql_perms="SELECT syssubmodule_idsyssubmodule,sysprofiles_idsysprofiles,permview,perminsert,permupdate,permdelete,mobile_access,global_access FROM  systemprofileaccess
			WHERE sysprofiles_idsysprofiles=".$idprofile_parent." ORDER BY idsystemprofileaccess ASC";
			$res_perms=mysql_query($sql_perms);
			$fet_perms=mysql_fetch_array($res_perms);
			$num_perm=mysql_num_rows($res_perms);
			
			if ($num_perm > 0)
			 	{
			  	do {
				//process the new profile with the same permission
				$sql_newprof="INSERT INTO systemprofileaccess (syssubmodule_idsyssubmodule,sysprofiles_idsysprofiles,permview,perminsert,permupdate,permdelete,mobile_access,global_access) 
				VALUES ('".$fet_perms['syssubmodule_idsyssubmodule']."','".$fet_id['idsysprofiles']."','".$fet_perms['permview']."','".$fet_perms['perminsert']."','".$fet_perms['permupdate']."','".$fet_perms['permdelete']."','".$fet_perms['mobile_access']."','".$fet_perms['global_access']."')";
				mysql_query($sql_newprof);
				 	} while ($fet_perms=mysql_fetch_array($res_perms));
			 	}
			}

		//header('location:'.$_SERVER['PHP_SELF'].'?uction=edit_submod&profile='.$fet_id['idsysprofiles'].'&msg='.urlencode($msg_changes_saved.$msg_prompt_assign).'');
		?>
		<script language="javascript">
		window.location='<?php echo $_SERVER['PHP_SELF'].'?uction=edit_submod&profile='.$fet_id['idsysprofiles'].'&msg='.$msg_changes_saved.'';?>';
		</script>        
        <?php
		exit;
		
		}
	
	}
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod"><?php echo $fet_heading['submodule']; ?></a>
    </div>
    <div style="padding:10px">
        <div class="msg_instructions"><?php echo $msg_profile_new;?></div>
        <div style="padding:10px 10px 30px 10px">
        <div>
        <?php
		if (isset($error_1)) { echo $error_1; }
		if (isset($error_2)) { echo $error_2; }
		if (isset($error_3)) { echo $error_3; }
		?>
        </div>
       
                    	<form method="post" name="newsysprofile" action="">
                        <table width="561" border="0" cellpadding="0" cellspacing="0" class="border_thick">
					  <tr>
                            	<td height="32" colspan="2" class="table_header"><?php echo $lbl_access_new; ?></td>
                          </tr>
                        	<tr>
                            	<td width="173" height="41" valign="middle" class="tbl_data">
                               <strong><?php echo $lbl_profile;?></strong>                               </td>
                              <td width="388" height="41" valign="middle" class="tbl_data">
                              <input type="text" maxlength="30" size="30" name="profile_name" /></td>
                          </tr>
                            <tr>
                            	<td height="40" class="tbl_data">
                               <strong><?php echo $lbl_zonename;?></strong>
                               </td>
                              <td height="40" class="tbl_data">
                              <select name="new_zone">
                                	<option value="0">---</option>
                                    <?php
									$sql_zones="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY userteamzonename ASC";
									$res_zones=mysql_query($sql_zones);
									$num_zones=mysql_num_rows($res_zones);
									$fet_zones=mysql_fetch_array($res_zones);
										do {
										echo "<option value=\"".$fet_zones['idusrteamzone']."\">".$fet_zones['userteamzonename']."</option>";
										} while ($fet_zones=mysql_fetch_array($res_zones));
									?>
                                </select>
                                </td>
                          </tr>
					<tr>
                    	<td height="30" colspan="2" class="tbl_h2">
                <a href="#" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span>Save time and copy similar permissions from another profile</span></a> Save Time ! Copy Permissions from another Profile using the menu below&nbsp;                			</td>
                    </tr>
                          <tr>
            	<td height="40" class="tbl_data">&nbsp;</td>
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
                    <select name="zone" id="zone" onChange="getprofile(this.value)">
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
               <td width="173" height="40" class="tbl_data">&nbsp;</td>
              <td height="40"  class="tbl_data">
               <div id="zoneprofilediv">
               </div>
               </td>
			</tr>
                            <tr>
                            	<td height="59"></td>
                              <td>
                                <a href="#" onClick="document.forms['newsysprofile'].submit()" id="button_saveandcont"></a>
                                <input type="hidden" name="formaction" value="newprofile" />
                                </td>
                          </tr>
                        </table>
          </form> 
                      
      </div>
	</div>
</div>
    