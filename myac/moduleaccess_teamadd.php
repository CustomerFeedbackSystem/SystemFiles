<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ((isset($_POST['formaction'])) && ($_POST['formaction']=="addteam"))
	{
	//clean up the code
	$pteamname=mysql_escape_string($_POST['teamname']);
	$pshortname=mysql_escape_string($_POST['shortname']);
	$pteam_cat=mysql_escape_string($_POST['team_cat']);
	$pteamintro=mysql_escape_string($_POST['teamintro']);
	$maxfilesize = 1000000;
	$pgrouporg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['grouporg'])));
	$preportto=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['reportto'])));
	$pcollab1=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['collab_1'])));
	$pcollab2=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['collab_2'])));
	$pcollab3=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['collab_3'])));
	$dtat=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tat'])));
	$dtat_cat=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tat_cat'])));
	$dtat_acstatus=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['acstatus'])));
	
	//validate by checkig required fields and duplicate record
	if (strlen($pteamname)<1)
		{
		$error_1="<div class=\"msg_warning\">".$msg_warning_noteam."</div>";
		}
	if (strlen($pteam_cat) < 1)
		{
		$error_2="<div class=\"msg_warning\">".$msg_warning_noteamcat."</div>";
		}
	if (strlen($pteamintro) < 1)
		{
		$error_3="<div class=\"msg_warning\">".$msg_warning_noteamintro."</div>";
		}
	//check duplicate team name
	$sql_uniq="SELECT usrteamname FROM usrteam WHERE usrteamname='".$pteamname."' OR usrteamshortname='".$pshortname."' LIMIT 1";
	$res_uniq=mysql_query($sql_uniq);
	$num_uniq=mysql_fetch_array($res_uniq);
	if ($num_uniq > 0)
		{
		$error_3b="<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
		}
	//if valid, process it
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) && (!isset($error_3b))  )
		{
			//calculate the time frame
			if ($dtat_cat=="Days") 
				{
				$com_timeframe = $dtat*24*60*60;
				}
			if ($dtat_cat=="Hours")
				{
				$com_timeframe = $dtat*60*60;
				}
		//create the new team first
			$sql_newteam="INSERT INTO usrteam (usrteamgroup_idusrteamgroup,usrteamtype_idusrteamtype,reportto_idusrteam,usrteamname,usrteamshortname,introtxt,createdby,createdon,table_billing,esctimeframe,escorg1,escorg2,escorg3,acstatus)
			VALUES ('".$pgrouporg."','".$pteam_cat."','".$preportto."','".$pteamname."','".$pshortname."','".$pteamintro."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','billing_".substr($pshortname,0,12)."','".$com_timeframe."','".$pcollab1."','".$pcollab2."','".$pcollab3."','".$dtat_acstatus."')";
			mysql_query($sql_newteam);
			
		//retrieve id
			$sql_lastid="SELECT idusrteam,usrteamtype_idusrteamtype FROM usrteam WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idusrteam DESC LIMIT 1";
			$res_lastid=mysql_query($sql_lastid);
			$fet_lastid=mysql_fetch_array($res_lastid);
			
			//create a session for the team
			$_SESSION['thisteamtype'] = $fet_lastid['usrteamtype_idusrteamtype'];
			
			//allocate some modules as per the default settings 
			$sql_default_access="SELECT idsysdefaultteam,sysmodule_idsysmodule FROM sysdefaultteamaccess WHERE usrteamtype_idusrteamtype=".$pteam_cat." ORDER BY idsysdefaultteam ASC";
			$res_default_access=mysql_query($sql_default_access);
			$num_default_access=mysql_num_rows($res_default_access);
			$fet_default_access=mysql_fetch_array($res_default_access);
			
			if ($num_default_access > 0) //allocate system access rights
				{
					do {
					
					$sql_allocate_mod="INSERT INTO systeamaccess (usrteam_idusrteam,sysmodule_idsysmodule,createdby,createdon) VALUES 
					('".$fet_lastid['idusrteam']."','".$fet_default_access['sysmodule_idsysmodule']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
					mysql_query($sql_allocate_mod);
					
					} while ($fet_default_access=mysql_fetch_array($res_default_access));
				
				}
				
			//is there logo_big 
			if($_FILES['logomain']['name']!='')
					{
					$imagename = basename($_FILES['logomain']['name']);
						//check if maximum size
						if ($_FILES['logomain']['size'] > $maxfilesize)
							{
							$error_4="<div class=\"msg_warning\">Main Logo Image size has exceeded 1 MB</div>";
							//$error["size"]="Image size has exceeded 1 MB";
							}
						//check if image is empty
						if(empty($imagename))
							{
							//$error["imagename"] = "Image was not found";
							$error_5="<div class=\"msg_warning\">Main Logo Image was not found</div>";
							}
						//
						if ( (!isset($error_4)) && (!isset($error_5)) )
							{
							
							$random_digit=rand(00000001,99999999);
							$new_file_name=$random_digit.$imagename;
							$newimage = "../assets_backend/logos/".$new_file_name;
							//upload image
							$result = @move_uploaded_file($_FILES['logomain']['tmp_name'], $newimage);
							$sql = "UPDATE usrteam SET mainlogo_path='assets_backend/logos/".$new_file_name."' WHERE idusrteam=".$fet_lastid['idusrteam']." LIMIT 1";
							mysql_query($sql);
							}
						} //end file if isset
				
				//is there logo_big 
			if($_FILES['logosmall']['name']!='')
					{
					$imagename = basename($_FILES['logomain']['name']);
						//check if maximum size
						if ($_FILES['logosmall']['size'] > $maxfilesize)
							{
							$error_6="<div class=\"msg_warning\">Small Logo Image size has exceeded 1 MB</div>";
							//$error["size"]="Image size has exceeded 1 MB";
							}
						//check if image is empty
						if(empty($imagename))
							{
							//$error["imagename"] = "Image was not found";
							$error_7="<div class=\"msg_warning\">Small Logo Image was not found</div>";
							}
						//
						if ( (!isset($error_6)) && (!isset($error_7)) )
							{
							$random_digit=rand(00000001,99999999);
							$new_file_name=$random_digit.$imagename;
							$newimage = "../assets_backend/logos/".$new_file_name;
							//upload image
							$result = @move_uploaded_file($_FILES['logosmall']['tmp_name'], $newimage);
							$sql = "UPDATE usrteam SET smalllogo_path='assets_backend/logos/".$new_file_name."' WHERE idusrteam=".$fet_lastid['idusrteam']." LIMIT 1";
							mysql_query($sql);
							}
						} //end file if isset

		//redirect to edit mode and send a success message
		//header('location:?uction=edit_submod&thisteam='.$fet_lastid['idusrteam'].'&msg='.$msg_changes_saved.'');
		?>
		<script language="javascript">
		window.location='<?php echo $_SERVER['PHP_SELF'].'?uction=edit_submod&thisteam='.$fet_lastid['idusrteam'].'&msg='.$msg_changes_saved.''; ?>';
		</script>        
        <?php
		exit;
		} //close no error
	
	
	} //close form action
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
   	<div class="tab_area">
        	<span class="tab_on">
            <?php echo $lbl_teamdetails;?>
            </span>
            <span class="tab_off">
            <?php echo $lbl_zone;?>
            </span>
	</div>
    <div style="padding:15px">
    <?php if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) && (!isset($error_4)) && (!isset($error_5)) && (!isset($error_6)) && (!isset($error_7))) { ?>
    <span class="msg_instructions"><?php echo $msg_newteam;?></span>
    <?php } ?>
    <?php if ( (isset($error_1)) || (isset($error_2)) || (isset($error_3)) || (isset($error_3b)) || (isset($error_4)) || (isset($error_5)) || (isset($error_6)) || (isset($error_7)) ) { ?>
    <span class="msg_warning">
    <?php 
		if (isset($error_1)) { echo $error_1; }
		if (isset($error_2)) { echo $error_2; }
		if (isset($error_3)) { echo $error_3; }
		if (isset($error_3b)) { echo $error_3b; }
		if (isset($error_4)) { echo $error_4; }
		if (isset($error_5)) { echo $error_5; }
		if (isset($error_6)) { echo $error_6; }
		if (isset($error_7)) { echo $error_7; }
	?>
    </span>
    <?php } ?>
    </div>
    <div>
<form method="post" action="" name="newteam" enctype="multipart/form-data">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
   		<td valign="top" width="60%">
        	<table border="0" cellpadding="3" cellspacing="0">
		 	<tr>
                	<td width="171" height="40" class="tbl_data">
                    <strong><?php echo $lbl_status_ac;?></strong></td>
                  <td width="515" height="40"  class="tbl_data">
                    <select name="acstatus">
                    	<option value="1" <?php if ( (isset($_POST['acstatus'])) && ($_POST['acstatus']==1) ) { echo "selected=\"selected\""; } ?>><?php echo $lbl_statusactive;?></option>
                        <option value="0" <?php if ( (isset($_POST['acstatus']))  && ($_POST['acstatus']==0) ) { echo "selected=\"selected\""; } ?>><?php echo $lbl_statusactivenot;?></option>
                    </select>
                    </td>
			  </tr>
          <tr>
               <td width="171" height="40" class="tbl_data">
               <strong><?php echo $lbl_team;?></strong>
               </td>
               <td width="515" height="40"  class="tbl_data">
               <input type="text" name="teamname" maxlength="100" value="<?php if (isset($_GET['teamname'])) { echo $_GET['teamname']; }?>" size="40">
               </td>
			</tr>
            <tr>
            	<td height="40" class="tbl_data">
                <strong><?php echo $lbl_shortname;?></strong>
                </td>
                <td class="tbl_data">
                <input type="text" maxlength="20" size="25" name="shortname" />
                </td>
            </tr>
            <tr>
               <td width="171" height="40" class="tbl_data">
               <strong><?php echo $lbl_teamtype;?></strong>
               </td>
               <td height="40"  class="tbl_data">
               <select name="team_cat">
               <option value="">---</option>
               <?php 
			   $sql_teamcats = "SELECT idusrteamtype,usrteamtypename FROM usrteamtype";
			   $res_teamcats = mysql_query($sql_teamcats);
			   $fet_teamcats = mysql_fetch_array($res_teamcats);
			   do {
			   ?>
               	<option <?php if ((isset($_GET['team_cat'])) && ($_GET['team_cat']==$fet_teamcats['idusrteamtype']) ) { echo " selected=\"selected\" "; }?> value="<?php echo $fet_teamcats['idusrteamtype'];?>"><?php echo $fet_teamcats['usrteamtypename'];?></option>
                <?php
				} while ($fet_teamcats = mysql_fetch_array($res_teamcats));
				?>
               </select>
               </td>
			</tr>
                <tr>
                	<td width="171" height="40" class="tbl_data">
                    <strong><?php echo $lbl_logomain;?></strong>
                    </td>
                  <td height="40"  class="tbl_data">
                    <input type="file" name="logomain" >
                    </td>
			  </tr>
                <tr>
                	<td width="171" height="40" class="tbl_data">
                    <strong><?php echo $lbl_logosmall;?></strong>
                    </td>
                  	<td height="40"  class="tbl_data">
                    <input type="file" name="logosmall" >
                    </td>
			  </tr>
                 <tr>
                	<td width="171" class="tbl_data" valign="top">
                    <strong><?php echo $lbl_welcome;?></strong></td>
                   <td class="tbl_data">
<?php
$sBasePath = $_SERVER['PHP_SELF'] ;
$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
$oFCKeditor = new FCKeditor('teamintro') ;
$oFCKeditor->Height = '300' ;
$oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
//$oFCKeditor->Value =  $row_rs_articles['artsummary'];
$oFCKeditor->ToolbarSet = 'Basic' ;
$oFCKeditor->Create() ;
?>            
                    </td>
			  </tr>
                 <tr>
                 	<td height="50">                    </td>
               	   <td>
                    <table border="0" style="margin:5px 10px 5px 20px">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['newteam'].submit()" id="button_save"></a>
                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="addteam" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
				</tr>
	</table>   
     
    </td>
    <td valign="top" width="40%">
    <table border="0" cellpadding="3" cellspacing="0">
                        	<tr>
                            	<td height="40" class="tbl_data">
                                Organization Group
                                </td>
                                <td class="tbl_data">
                                <select name="grouporg">
                                <option value="0">---</option>
                                <?php
								$sql_tgroup="SELECT idusrteamgroup,usrteamgroupname FROM usrteamgroup ORDER BY usrteamgroupname ASC";
								$res_tgroup=mysql_query($sql_tgroup);
								$fet_tgroup=mysql_fetch_array($res_tgroup);
									do {
									echo "<option value=\"".$fet_tgroup['idusrteamgroup']."\">".$fet_tgroup['usrteamgroupname']."</option>";
									} while ($fet_tgroup=mysql_fetch_array($res_tgroup));
								?>
                                </select>
                                </td>
                            </tr>
                            <tr>
                            	<td height="40" class="tbl_data">
                                Reporting To
                                </td>
                                <td class="tbl_data">
                                <select name="grouporg">
                                <option value="0">---</option>
                                <?php
								$sql_reportto="SELECT idusrteam,usrteamname FROM usrteam ORDER BY usrteamname ASC";
								$res_reportto=mysql_query($sql_reportto);
								$fet_reportto=mysql_fetch_array($res_reportto);
									do {
									echo "<option value=\"".$fet_reportto['idusrteam']."\">".$fet_reportto['usrteamname']."</option>";
									} while ($fet_reportto=mysql_fetch_array($res_reportto));
								?>
                                </select>
                                </td>
                            </tr>
                            <tr>
                            	<td height="40" class="tbl_data">
                                Escalation Time-Frame</td>
                                <td class="tbl_data">
                                <input onkeyup="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Hours")){ echo "selected=\"selected\""; } ?> value="Hours">Hours</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Days")){ echo "selected=\"selected\""; } ?> value="Days">Days</option>
                    </select>
                                </td>
                            </tr>
                             <tr>
                            	<td height="40" colspan="2" class="tbl_data">External Organizations Task Collaboration</td>
                            </tr>
                            <tr>
                            	<td class="tbl_data"><span style="color:#FFFFFF">.</span></td>
                            	<td class="tbl_data">
                                <div style="padding:2px 0px 0px 0px">
                                <select name="collab_1">
                                <option value="0">---</option>
                                  <?php
								$sql_reportto="SELECT idusrteam,usrteamname FROM usrteam ORDER BY usrteamname ASC";
								$res_reportto=mysql_query($sql_reportto);
								$fet_reportto=mysql_fetch_array($res_reportto);
									do {
									echo "<option value=\"".$fet_reportto['idusrteam']."\">".$fet_reportto['usrteamname']."</option>";
									} while ($fet_reportto=mysql_fetch_array($res_reportto));
								?>
                                </select>
                                </div>
                                <div  style="padding:2px 0px 0px 0px">
                                <select name="collab_2">
                                <option value="0">---</option>
                                <?php
								$sql_reportto="SELECT idusrteam,usrteamname FROM usrteam ORDER BY usrteamname ASC";
								$res_reportto=mysql_query($sql_reportto);
								$fet_reportto=mysql_fetch_array($res_reportto);
									do {
									echo "<option value=\"".$fet_reportto['idusrteam']."\">".$fet_reportto['usrteamname']."</option>";
									} while ($fet_reportto=mysql_fetch_array($res_reportto));
								?>
                                </select>
                                </div>
                                <div  style="padding:2px 0px 0px 0px">
                                <select name="collab_3">
                                <option value="0">---</option>
                                <?php
								$sql_reportto="SELECT idusrteam,usrteamname FROM usrteam ORDER BY usrteamname ASC";
								$res_reportto=mysql_query($sql_reportto);
								$fet_reportto=mysql_fetch_array($res_reportto);
									do {
									echo "<option value=\"".$fet_reportto['idusrteam']."\">".$fet_reportto['usrteamname']."</option>";
									} while ($fet_reportto=mysql_fetch_array($res_reportto));
								?>
                                </select>
                                </div>
                                </td>
                            </tr>
                            
                        </table>
    </td>
</tr>
</table>
</form>  
	</div>
</div>