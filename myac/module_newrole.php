<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="addrole"))
	{
	//clean up
	$prolename=mysql_escape_string(trim($_POST['rolename']));
	$pzone=mysql_escape_string(trim($_POST['zone']));
	$pprofile=mysql_escape_string(trim($_POST['profile']));
	$proledesc=mysql_escape_string(trim($_POST['roledesc']));
	$reporting = mysql_escape_string(trim($_POST['report_to']));
	$jlevel = mysql_escape_string(trim($_POST['jlevel']));
	$jdpt = mysql_escape_string(trim($_POST['jdpt']));
	
	//validate and check for duplicate
	$sql_duplicaterole="SELECT idusrrole FROM usrrole WHERE usrrolename='".$prolename."' AND usrteamzone_idusrteamzone=".$pzone." LIMIT 1";
	$res_duplicaterole=mysql_query($sql_duplicaterole);
	$num_duplicaterole=mysql_num_rows($res_duplicaterole);
	
	if (strlen($prolename) < 1)
		{
		$error1="<div class=\"msg_warning\">".$msg_warning_norole."</div>";
		}
	
	if ((!isset($error1)) && ($num_duplicaterole > 0) )
		{
		$error2="<div class=\"msg_warning\">".$msg_warning_duplicaterole."</div>";
		}
		
	if ($pzone < 1)
		{
		$error3="<div class=\"msg_warning\">".$msg_warning_teamzone."</div>";
		}
		
	if ( (!isset($pprofile)) || ($pprofile<1) )
		{
		$error4="<div class=\"msg_warning\">".$msg_warning_profile."</div>";
		}
		
	if ($jlevel < 1)
		{
		$error5="<div class=\"msg_warning\">Job Level is Missing</div>";
		}
		
	if ($jdpt < 1)
		{
		$error6="<div class=\"msg_warning\">Department / Unit is Missing</div>";
		}
	
	//process it
	if ( (!isset($error1)) && (!isset($error2)) && (!isset($error3)) && (!isset($error4)) && (!isset($error5)) && (!isset($error6))  )
		{
		$sql_newrole="INSERT INTO usrrole (sysprofiles_idsysprofiles,usrteamzone_idusrteamzone,usrrolename,usrroledesc,reportingto,joblevel,usrdpts_idusrdpts,createdby,createdon) 
		VALUES ('".$pprofile."','".$pzone."','".$prolename."','".$proledesc."','".$reporting."','".$jlevel."','".$jdpt."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
		mysql_query($sql_newrole);
		
		$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
		?>
		<script language="javascript">
		window.location='<?php echo $_SERVER['PHP_SELF'].'?uction=view_submod';?>';
		</script>
        <?php
		//header('location:'.$_SERVER['PHP_SELF'].'?uction=view_submod');
		}
	} //close form action
?>
<div >

	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div>
    <?php
	if (isset($msg)) { echo $msg; } 
	
	if ( (isset($error1)) || (isset($error2)) || (isset($error3)) || (isset($error4)) || (isset($error5)) || (isset($error6)) )
		{
		if (isset($error1)) { echo $error1; }
		if (isset($error2)) { echo $error2; }
		if (isset($error3)) { echo $error3; }
		if (isset($error4)) { echo $error4; }
		if (isset($error5)) { echo $error5; }
		if (isset($error6)) { echo $error6; }
		}
	?>
    </div>
<div style="padding:20px 0px 0px 0px">
<form method="post" action="" name="newrole" enctype="multipart/form-data">
        	<table border="0" cellpadding="3" cellspacing="0" class="border_thick">
            <tr>
            <td height="30" colspan="2" class="table_header">
			<?php echo $lbl_newrole;?>            </td>
            </tr>
		  <tr>
               <td width="171" height="40" class="tbl_data">
               <strong><?php echo $lbl_role;?></strong>
               </td>
               <td width="352" height="40"  class="tbl_data">
               <input type="text" name="rolename" maxlength="100" value="<?php if (isset($_GET['rolename'])) { echo $_GET['rolename']; }?>" size="40">               </td>
			</tr>
            <tr>
            	<td class="tbl_data">
                  <strong><?php echo $lbl_reportingto;?></strong>
				</td>
                <td class="tbl_data">
                <?php
				//list the roles in this userteam id
				$sql_reportto="SELECT idusrrole,usrrolename,userteamzonename FROM usrrole
 				INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone WHERE  
				usrteamzone.idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." OR usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
				ORDER BY userteamzonename,usrrolename ASC";
				$res_reportto=mysql_query($sql_reportto);
				$num_reportto=mysql_num_rows($res_reportto);
				$fet_reportto=mysql_fetch_array($res_reportto);
				//loop if there is a record
				echo "<select name=\"report_to\">";
				if ($num_reportto > 0)
					{
					echo "<option value=\"0\" selected>N/A</option>";
					do {
					echo "<option value=\"".$fet_reportto['idusrrole']."\">".$fet_reportto['usrrolename']." - ".$fet_reportto['userteamzonename']."</option>";
					} while ($fet_reportto=mysql_fetch_array($res_reportto));
				} else {
					echo "<option value=\"0\">N/A</option>";
				}
				echo "</select>";
				?>
                </td>
            </tr>
          <tr>
            	<td height="40" class="tbl_data">
                <strong><?php echo $lbl_zonename;?></strong>
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
                    <select name="zone" id="zone" onChange="getprofile(this.value)">
                    <option value="-1">---</option>
                  	<?php 
					do {
					?>
                    <option <?php if ( (isset($_POST['zone'])) && ($_POST['zone']==$fet_zone['idusrteamzone'])) { echo "selected=\"selected\""; } ?> value="<?php echo $fet_zone['idusrteamzone'];?>"><?php echo $fet_zone['userteamzonename'];?></option>
                    <?php } while ($fet_zone=mysql_fetch_array($res_zone)); ?>
                  </select>
                </td>
            </tr>
            <tr>
               <td width="171" height="40" class="tbl_data">
               <strong><?php echo $lbl_profile;?></strong>
               </td>
               <td height="40"  class="tbl_data">
               <div id="zoneprofilediv">
               <!--<select name="profile"><option value="-1">---</option></select>-->
               </div>
               </td>
			</tr>
            <tr>
               <td width="171" height="40" class="tbl_data">
               <strong>Job Level</strong>
               </td>
               <td height="40"  class="tbl_data">
               <select name="jlevel">
               <?php
			   $sql_job="SELECT idjoblvl,joblvl_lbl,joblvldesc FROM usrjoblvl 
			   WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY idjoblvl ASC";
			   $res_job=mysql_query($sql_job);
			   $fet_job=mysql_fetch_array($res_job);
			   	
				echo "<option value=\"\">---</option>";	
				do {
					
					echo "<option ";
					if  ( (isset($_POST['jlevel'])) && ($_POST['jlevel']==$fet_job['idjoblvl']) )
						{
						echo "selected=\"selected\"";
						}
					echo " value=\"".$fet_job['idjoblvl']."\"  >";
					echo "[".$fet_job['idjoblvl']."] ".$fet_job['joblvl_lbl'];
					echo "</option>";
					
				} while ($fet_job=mysql_fetch_array($res_job));
			   ?>               
               </select>
               </td>
			</tr>
             <tr>
               <td width="171" height="40" class="tbl_data">
               <strong>Unit / Department</strong>
               </td>
               <td height="40"  class="tbl_data">
               <?php
			   //select the departments from the db
			 $sql_dpt="SELECT idusrdpts,usrdptname,dptdesc,usrteam_idusrteam FROM usrdpts WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY usrdptname ASC";
			 $res_dpt=mysql_query($sql_dpt);
			 $fet_dpt=mysql_fetch_array($res_dpt);
			   ?>
               <select name="jdpt">
               <?php
			   echo "<option value=\"\">---</option>";
			   do {
			   echo "<option ";
			   	if  ( (isset($_POST['jdpt'])) && ($_POST['jdpt']==$fet_dpt['idusrdpts']) )
					{
					echo "selected=\"selected\"";
					}
			   echo " title=\"".$fet_dpt['dptdesc']."\" value=\"".$fet_dpt['idusrdpts']."\">".$fet_dpt['usrdptname']."</option>";
			   } while ($fet_dpt=mysql_fetch_array($res_dpt));?>
               </select>
               </td>
			</tr>
                <tr>
               	  <td width="171" height="40" class="tbl_data" valign="top">
                    <strong><?php echo $lbl_description;?></strong>
                    </td>
                  <td height="40"  class="tbl_data" valign="top">
                  <textarea name="roledesc" rows="4" cols="30" id="roledesc"></textarea>
                  </td>
			  </tr>
                 <tr>
                 	<td height="50">                    </td>
               	   <td>
                    <table border="0" style="margin:5px 10px 5px 0px">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['newrole'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="addrole" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>
                        </td>
				</tr>
	</table>   
</form> 
</div>
</div>