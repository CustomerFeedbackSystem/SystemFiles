<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['thisteam']))
	{
	$_SESSION['thisteamid']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['thisteam'])));
	}
if (isset($_GET['teamname']))
	{
	$_SESSION['thisteamname']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['teamname'])));
	}
if (isset($_GET['teamshort']))
	{
	$_SESSION['thisteamshort']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['teamshort'])));
	}

if ( (isset($_GET['saction'])) && ($_GET['saction']=="clearimage"))
	{
		if ($_GET['img']=="big_logo")
			{
			$sql_update="UPDATE usrteam SET mainlogo_path=null WHERE idusrteam=".$_SESSION['thisteamid']." LIMIT 1";
			mysql_query($sql_update);
			
			$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>"; //success message
			
			}
		
		if ($_GET['img']=="small_logo")
			{
			$sql_update="UPDATE usrteam SET smalllogo_path=null WHERE idusrteam=".$_SESSION['thisteamid']." LIMIT 1";
			mysql_query($sql_update);
			
			$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>"; //success message
			
			}
	}


if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="updateteam") )
	{
	//clean up the variables
	$pteamname=preg_replace('/[^a-z\-_0-9\.:&\/\s]/i','',mysql_escape_string(trim($_POST['teamname'])));
	$pshortname=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['shortname'])));
	$pteam_cat=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_POST['team_cat'])));
	$pteamintro=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['teamintro'])));
	$pgrouporg=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['grouporg'])));
	$preportto=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['reportto'])));
	$pcollab1=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['collab_1'])));
	$pcollab2=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['collab_2'])));
	$pcollab3=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['collab_3'])));
	$dtat=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tat'])));
	$dtat_cat=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['tat_cat'])));
	$dtat_acstatus=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['acstatus'])));
	
	
	$maxfilesize = 1000000 ;
	
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
	
	/*if ( ($_SESSION['thisteamname']!=$pteamname) || ( (strlen($pshortname)>0) && ($_SESSION['thisteamshort']!=$pshortname) ) )
		{
			//check duplicate team name
			$sql_uniq="SELECT usrteamname FROM usrteam WHERE usrteamname='".$pteamname."' OR usrteamshortname='".$pshortname."' LIMIT 1";
			$res_uniq=mysql_query($sql_uniq);
			$num_uniq=mysql_fetch_array($res_uniq);
			echo $sql_uniq;
			if ($num_uniq > 0)
				{
				$error_3b="<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
				}
		} //run only if the team names entered are different from what was originally there...otherwise, do not run this*/
		
	//if valid, process it
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3))  )
		{
		
		if ($dtat_cat=="Days")
				{
				$com_timeframe = $dtat*24*60*60;
				}
			if ($dtat_cat=="Hours")
				{
				$com_timeframe = $dtat*60*60;
				}
		
		//update the team table
		//create the new team first
			$sql_updateteam="UPDATE usrteam SET 
			usrteamgroup_idusrteamgroup='".$pgrouporg."',
			usrteamtype_idusrteamtype='".$pteam_cat."',
			reportto_idusrteam='".$preportto."',
			usrteamname='".$pteamname."',
			usrteamshortname='".$pshortname."',
			introtxt='".$pteamintro."',
			modifiedby='".$_SESSION['MVGitHub_idacname']."',
			modifiedon='".$timenowis."',
			esctimeframe='".$com_timeframe."',
			escorg1='".$pcollab1."',
			escorg2='".$pcollab2."',
			escorg3='".$pcollab3."',
			acstatus='".$dtat_acstatus."'
			WHERE idusrteam=".$_SESSION['thisteamid']." LIMIT 1";
			
			mysql_query($sql_updateteam);
		
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
							$sql = "UPDATE usrteam SET mainlogo_path='assets_backend/logos/".$new_file_name."' WHERE idusrteam=".$_SESSION['thisteamid']." LIMIT 1";
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
							$sql = "UPDATE usrteam SET smalllogo_path='assets_backend/logos/".$new_file_name."' WHERE idusrteam=".$_SESSION['thisteamid']." LIMIT 1";
							mysql_query($sql);
							}
						} //end file if isset
			
			$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>"; //success message
		
		}//close if no error
	
	} //close form action


$sql_team="SELECT * FROM usrteam INNER JOIN usrteamtype ON usrteam.	usrteamtype_idusrteamtype=usrteamtype.idusrteamtype WHERE idusrteam=".$_SESSION['thisteamid']." LIMIT 1";
$res_team=mysql_query($sql_team);
$num_team=mysql_num_rows($res_team);
//echo $sql_team;
if ($num_team<1)
	{
	echo "<div class=\"msg_warning\">".$msg_warn_contactadmin."</div>";
	exit;
	} else {
$fet_team=mysql_fetch_array($res_team);
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; 
	$_SESSION['thisteamtype']=$fet_team['usrteamtype_idusrteamtype'];
	$_SESSION['thisteamid']=$fet_team['idusrteam'];
	$_SESSION['lblmodule']=$fet_heading['modulename'];
	$_SESSION['lblsubmodule']=$fet_heading['submodule'];
	$_SESSION['lblusrteamname'] = $fet_team['usrteamname'];
	
	?>
    </div>
    <div style="padding:10px ">
    </div>
   	<div class="tab_area">
        	<span class="tab_on">
           <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;thisteam=<?php echo $_SESSION['thisteamid'];?>"><?php echo $lbl_teamdetails;?></a>
            </span>
            <span class="tab">
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_subsubmod&amp;parentviewtab=2"><?php echo $lbl_zone;?>
            <?php
			//count zones
			$sql_nozones="SELECT count(*) as zones FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['thisteamid']."";
			$res_nozones=mysql_query($sql_nozones);
			$fet_nozones=mysql_fetch_array($res_nozones);
			//echo $sql_nozones;
			echo " <span class=\"box_count\">".$fet_nozones['zones']."</span>";
			?>
            </a>
            </span>
	</div>
    <div style="padding:15px">
    <?php if (isset($_GET['msg'])) { ?><span class="msg_success"><?php echo $_GET['msg'];?></span><?php } ?>
    </div>
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
    <?php 
	if (isset($msg)) { echo $msg; }
	?>
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
                    	<option value="1" <?php if ($fet_team['acstatus']==1) { echo "selected=\"selected\""; } ?>><?php echo $lbl_statusactive;?></option>
                        <option value="0" <?php if ($fet_team['acstatus']==0) { echo "selected=\"selected\""; } ?>><?php echo $lbl_statusactivenot;?></option>
                    </select>
                    </td>
			  </tr>
                        
                        <tr>
                	<td width="171" height="40" class="tbl_data">
                    <strong><?php echo $lbl_team;?></strong></td>
                  <td width="515" height="40"  class="tbl_data">
                    <input type="text" name="teamname" value="<?php echo $fet_team['usrteamname'];?>" maxlength="70" size="40">
                    </td>
			  </tr>
              <tr>
            	<td height="40" class="tbl_data">
                <strong><?php echo $lbl_shortname;?></strong>
                </td>
                <td height="40" class="tbl_data">
                <input readonly="readonly" style="background-color:#E8E8E8" type="text" maxlength="20" value="<?php echo $fet_team['usrteamshortname'];?>" size="25" name="shortname" />
                </td>
            </tr>
              <tr>
               <td width="171" height="40" class="tbl_data">
               <strong><?php echo $lbl_teamtype;?></strong>
               </td>
               <td height="40"  class="tbl_data">
               <select name="team_cat">
               <?php 
			   $sql_teamcats = "SELECT idusrteamtype,usrteamtypename FROM usrteamtype";
			   $res_teamcats = mysql_query($sql_teamcats);
			   $fet_teamcats = mysql_fetch_array($res_teamcats);
			   do {
			   ?>
               	<option <?php if ($fet_team['usrteamtype_idusrteamtype']==$fet_teamcats['idusrteamtype']){ echo " selected=\"selected\" ";}?> value="<?php echo $fet_teamcats['idusrteamtype'];?>"><?php echo $fet_teamcats['usrteamtypename'];?></option>
                <?php
				} while ($fet_teamcats = mysql_fetch_array($res_teamcats));
				?>
               </select></td>
			</tr>
                <tr>
                	<td width="171" height="40" class="tbl_data">
                    <strong><?php echo $lbl_logomain;?></strong></td>
                  <td height="40"  class="tbl_data">
                 <?php if (strlen($fet_team['mainlogo_path']) > 3) { ?>
                 <div style="padding:5px 0px 8px 0px"><img src="../<?php echo $fet_team['mainlogo_path'];?>" border="0" width="145" height="60" /></div>
				 <div><a onclick="return confirm('<?php echo $msg_prompt_delete; ?>')" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=clearimage&amp;img=big_logo" id="button_delete_small"></a></div>
				 <?php } ?>
                 <div> <?php echo $lbl_replaceimg;?> : <input type="file" name="logomain" ></div>
                    </td>
			  </tr>
                <tr>
                	<td width="171" height="40" class="tbl_data">
                    <strong><?php echo $lbl_logosmall;?></strong>
                    </td>
                  <td height="40"  class="tbl_data">
                  <?php if (strlen($fet_team['smalllogo_path']) > 3) { ?>
                  <div style="padding:5px 0px 8px 0px">
                  <img src="../<?php echo $fet_team['smalllogo_path'];?>" border="0" width="90" height="40" />
                  </div>
                  <div><a onclick="return confirm('<?php echo $msg_prompt_delete; ?>')" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=clearimage&amp;img=small_logo" id="button_delete_small"></a></div>
                  <?php } ?>
                  <div>
                  <?php echo $lbl_replaceimg;?> : <input type="file" name="logosmall" >
                  </div>
                    </td>
			  </tr>
                 <tr>
                	<td width="171" class="tbl_data" valign="top">
                    <strong><?php echo $lbl_welcome;?></strong></td>
                   <td class="tbl_data">
<?php
include("fckeditor/fckeditor.php");

$sBasePath = $_SERVER['PHP_SELF'] ;
$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
$oFCKeditor = new FCKeditor('teamintro') ;
$oFCKeditor->Height = '300' ;
$oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
$oFCKeditor->Value =  $fet_team['introtxt'];
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
                                <input type="hidden" value="updateteam" name="formaction" />
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
                                <strong><?php echo $lbl_orgroup?></strong>
                                </td>
                                <td class="tbl_data">
                                <select name="grouporg">
                                <option value="0">---</option>
                                <?php
								$sql_tgroup="SELECT idusrteamgroup,usrteamgroupname FROM usrteamgroup ORDER BY usrteamgroupname ASC";
								$res_tgroup=mysql_query($sql_tgroup);
								$fet_tgroup=mysql_fetch_array($res_tgroup);
									do {
									echo "<option";
									if ($fet_team['usrteamgroup_idusrteamgroup']==$fet_tgroup['idusrteamgroup'])
										{
										echo " selected=\"selected\" ";
										}
									echo " value=\"".$fet_tgroup['idusrteamgroup']."\">".$fet_tgroup['usrteamgroupname']."</option>";
									} while ($fet_tgroup=mysql_fetch_array($res_tgroup));
								?>
                                </select>
                                </td>
                            </tr>
                            <tr>
                            	<td height="40" class="tbl_data">
                                <strong><?php echo $lbl_reportingto;?></strong>
                                </td>
                                <td class="tbl_data">
                                <select name="reportto" id="reportto">
                                <option value="0">---</option>
                                <?php
								$sql_reportto="SELECT idusrteam,usrteamname FROM usrteam ORDER BY usrteamname ASC";
								$res_reportto=mysql_query($sql_reportto);
								$fet_reportto=mysql_fetch_array($res_reportto);
									do {
									echo "<option "; 
									if ($fet_team['reportto_idusrteam']==$fet_reportto['idusrteam'])
										{
										echo " selected=\"selected\" ";
										}
									echo " value=\"".$fet_reportto['idusrteam']."\">".$fet_reportto['usrteamname']."</option>";
									} while ($fet_reportto=mysql_fetch_array($res_reportto));
								?>
                                </select>
                                </td>
                            </tr>
                            <tr>
                            	<td height="40" class="tbl_data">
                                <strong><?php echo $lbl_tpdeadline;?></strong></td>
                                <td class="tbl_data">
                                <input onkeyup="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?><?php if (!isset($_POST['tat'])) { if ($fet_team['esctimeframe']>"172800"){ echo ($fet_team['esctimeframe']/(60 * 60 * 24));} else { echo ($fet_team['esctimeframe']/(60 * 60));} }?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Hours")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_team['esctimeframe']<="172800"){ echo "selected=\"selected\""; } } ?> value="Hours">Hours</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Days")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_team['esctimeframe']>"172800"){ echo "selected=\"selected\""; } } ?> value="Days">Days</option>
                    </select>
                                </td>
                            </tr>
                             <tr>
                            	<td height="40" colspan="2" class="text_body_dark">
                                <strong><?php echo $lbl_exorg;?></strong>
                                </td>
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
									echo "<option "; 
									if ($fet_team['escorg1']==$fet_reportto['idusrteam'])
										{
										echo " selected=\"selected\" ";
										}
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
									echo "<option "; 
									if ($fet_team['escorg2']==$fet_reportto['idusrteam'])
										{
										echo " selected=\"selected\" ";
										}
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
									echo "<option "; 
									if ($fet_team['escorg3']==$fet_reportto['idusrteam'])
										{
										echo " selected=\"selected\" ";
										}
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
<?php
} //close if no fatal error
?>