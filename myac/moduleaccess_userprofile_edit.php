<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
if (isset($_GET['profile']))
	{
	$_SESSION['thisprofile']=mysql_escape_string(trim($_GET['profile']));
	}

//process the on-click action
if (isset($_GET['permtype']))
	{
	/*echo $_GET['permtype']."<br>";
	echo $_GET['saction']."<br>";
	echo $_GET['idsubmod'];
	*/
	//is it add or remove
		$ptype=mysql_escape_string(trim($_GET['permtype']));
		$saction=mysql_escape_string(trim($_GET['saction']));
		$idsubmodule=mysql_escape_string(trim($_GET['idsubmod']));
		
		
			if ( (($saction=="add") || ($saction=="remove")) && ($idsubmodule>0))
				{
				
				if ($saction=="add")
					{
					$ptypeval=1;
					} else {
					$ptypeval=0;
					}
			//	echo $ptypeval;
				//check if it already exists first				
				$sql_exists="SELECT idsystemprofileaccess,permview,perminsert,permupdate,permdelete FROM systemprofileaccess 
				INNER JOIN sysprofiles ON systemprofileaccess.sysprofiles_idsysprofiles=sysprofiles.idsysprofiles
				INNER JOIN usrteamzone ON sysprofiles.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
				WHERE syssubmodule_idsyssubmodule=".$idsubmodule." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND  sysprofiles_idsysprofiles=".$_SESSION['thisprofile']."  LIMIT 1";
				$res_exists=mysql_query($sql_exists);
				$num_exists=mysql_num_rows($res_exists);
				$fet_exists=mysql_fetch_array($res_exists);
				//echo $sql_exists;
				//if not, insert the record otherwise, update the record
				if  ( ($num_exists < 1)  && ($saction=="add") )//if does not exist
						{ 
						$perm="INSERT INTO systemprofileaccess(syssubmodule_idsyssubmodule,sysprofiles_idsysprofiles,".$ptype.") 
						VALUES ('".$idsubmodule."','".$_SESSION['thisprofile']."','".$ptypeval."')";
						mysql_query($perm);
						$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>";
						} else {
						
						$perm="UPDATE systemprofileaccess SET ".$ptype."='".$ptypeval."' WHERE syssubmodule_idsyssubmodule=".$idsubmodule." AND sysprofiles_idsysprofiles=".$_SESSION['thisprofile']."";
						mysql_query($perm);
						$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>";
						}
					
				if  ($num_exists >0 )//if only one record remaining, then delete that record
						{ 
						//add up the totals for perms and if currently they are less than 2 (ie 1, then just delete that row
						$permtotals = $fet_exists['permview']+$fet_exists['perminsert']+$fet_exists['permupdate']+$fet_exists['permdelete'];
						
						if ($permtotals==1)
							{
							
							$perm="DELETE FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$idsubmodule." AND sysprofiles_idsysprofiles=".$_SESSION['thisprofile']."";
							mysql_query($perm);
							$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>";
							
							} else {
												
							$perm="UPDATE systemprofileaccess SET ".$ptype."='".$ptypeval."' WHERE syssubmodule_idsyssubmodule=".$idsubmodule." AND sysprofiles_idsysprofiles=".$_SESSION['thisprofile']."";
							mysql_query($perm);
							$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>";
							}
						} //num exists is  > 0	
					
					} //if add/remove and submod id is set
				
				
				if ( (($saction=="add_all") || ($saction=="remove_all")) && ($ptype=="permall"))
					{
					
					if ($saction=="remove_all")		
						{
					
						//first, check if it already exists first				
						$sql_exists="SELECT idsystemprofileaccess FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$idsubmodule." AND sysprofiles_idsysprofiles=".$_SESSION['thisprofile']." LIMIT 1";
						$res_exists=mysql_query($sql_exists);
						$num_exists=mysql_num_rows($res_exists);
						
						if ($num_exists > 0) //if there is a record,
							{
							//then update / delete that record accordingly if it already exists
							$sql_remove="DELETE FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$idsubmodule." AND sysprofiles_idsysprofiles=".$_SESSION['thisprofile']." LIMIT 1";
							mysql_query($sql_remove);
							$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>";
							} //close if number exists
						
						} //close if remove
						
						
						if ($saction=="add_all")		
							{
								//first, check if it already exists first				
								$sql_exists="SELECT idsystemprofileaccess FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$idsubmodule." AND sysprofiles_idsysprofiles=".$_SESSION['thisprofile']." LIMIT 1";
								$res_exists=mysql_query($sql_exists);
								$num_exists=mysql_num_rows($res_exists);
						
								//if not, insert the record otherwise, update the record
								if (($num_exists < 1) && ($saction=="add_all"))
									{ //if does not exist
									$perm="INSERT INTO systemprofileaccess(syssubmodule_idsyssubmodule,sysprofiles_idsysprofiles,permview,perminsert,permupdate,permdelete) 
									VALUES ('".$idsubmodule."','".$_SESSION['thisprofile']."','1','1','1','1')";
									mysql_query($perm);
									$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>";
									
									} else {
									
									$perm="UPDATE systemprofileaccess SET permview='1',perminsert='1',permupdate='1',permdelete='1' WHERE syssubmodule_idsyssubmodule=".$idsubmodule." AND sysprofiles_idsysprofiles=".$_SESSION['thisprofile']."";
									mysql_query($perm);
									$msg = "<div class=\"msg_success\">".$msg_changes_saved."</div>";
									}
								}  //close if add all
								
				}//if add_all/remove_all and submod id is set
				
	} //if permtype is set

$sql_profile="SELECT idsysprofiles,sysprofile,usrteam_idusrteam FROM sysprofiles
INNER JOIN usrteamzone ON sysprofiles.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone WHERE idsysprofiles=".$_SESSION['thisprofile']." LIMIT 1";
$res_profile=mysql_query($sql_profile);
$fet_profile=mysql_fetch_array($res_profile);

if ($fet_profile['usrteam_idusrteam']!=$_SESSION['MVGitHub_idacteam'])
	{
	echo "<div class=\"msg_warning\">".$msg_warn_violation."</div>";
	exit;
	}
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod"><?php echo $fet_heading['submodule']; ?></a>
    </div>
    <div style="padding:10px">
       
        <div>
        <?php if (isset($msg)) { echo $msg; } ?>
        </div>
        <div style="padding:20px 10px 30px 10px">
        <form method="post" name="sysprofile" action="">
        	<table width="754" border="0" cellpadding="0" cellspacing="0" class="border_thick">
  				<tr>
                	<td colspan="8" style="padding:10px 0px 20px 10px" class="table_header">
                    <table border="0" cellpadding="0" cellspacing="0">
                    	<tr>
							<td style="font-weight:bold">
							<small><?php echo $lbl_access_profile;?> :</small>  <?php echo $fet_profile['sysprofile'];?>
							</td>
							<td>
							<a id="button_edit_small"  href="pop_edituserprofile.php?uction=edit_submod&amp;profile=<?php echo $fet_profile['idsysprofiles'];?>&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=300&amp;width=600&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox"></a>
                            </td>
						</tr>
					</table>                            
                    </td>
              </tr>
                <tr>
                	<td width="200"></td>
                    <td width="100" class="tbl_h"><?php echo $lbl_permview;?></td>
                    <td width="100" class="tbl_h"><?php echo $lbl_permadd;?></td>
                    <td width="100" class="tbl_h"><?php echo $lbl_permupdate;?></td>
                    <td width="100" class="tbl_h"><?php echo $lbl_permdel;?></td>
                     <td width="100" class="tbl_h"><?php echo $lbl_permall;?></td>
                     <td width="100" class="tbl_h"><?php echo $lbl_permmobile;?>
                     <a href="#" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span><?php echo $msg_tip_mobileaccess;?></span></a>
                     </td>
                    <td width="100" class="tbl_h"><?php echo $lbl_permglobal;?>
                    <a href="#" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span><?php echo $msg_tip_globalaccess;?></span></a>
                    </td>
              </tr>
              <?php
			  //load the modules allocated from the sysprofileaccess table $_SESSION['thisprofile']
			  $sql_mods="SELECT idsysmodule,modulename FROM sysmodule
			  INNER JOIN systeamaccess ON sysmodule.idsysmodule=systeamaccess.sysmodule_idsysmodule 
			  WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND sys_status=1 ORDER BY listorder ASC";
			  $res_mods=mysql_query($sql_mods);
			  $fet_mods=mysql_fetch_array($res_mods);
			  $num_mods=mysql_num_rows($res_mods);
//			 echo  $sql_mods;
			  	if ($num_mods > 0)
					{
					do {
			  ?>
                <tr>
                	<td colspan="8" class="tbl_sh">
                    <?php echo $fet_mods['modulename'];?>
                    </td>
                </tr>
                <?php
				$sql_sub="SELECT idsyssubmodule,sysmodule_idsysmodule,submodule FROM syssubmodule WHERE sysmodule_idsysmodule=".$fet_mods['idsysmodule']." AND submod_status=1";
				$res_sub=mysql_query($sql_sub);
				$fet_sub=mysql_fetch_array($res_sub);
				$num_sub=mysql_num_rows($res_sub);
//echo $sql_sub;
				if ($num_sub > 0)
					{
					do {
					$sql_checkperm="SELECT permview,perminsert,permupdate,permdelete,mobile_access,global_access,syssubmodule_idsyssubmodule FROM systemprofileaccess WHERE sysprofiles_idsysprofiles=".$_SESSION['thisprofile']." AND syssubmodule_idsyssubmodule=".$fet_sub['idsyssubmodule']."";
					$res_checkperm=mysql_query($sql_checkperm);
					$fet_checkperm=mysql_fetch_array($res_checkperm);
					//echo $sql_checkperm."<br>";
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td class="tbl_data" style="padding:0px 0px 0px 20px">
                    <a name="<?php echo $fet_mods['idsysmodule'];?>"></a>
                    <?php echo $fet_sub['submodule'];?>
                    </td>				
                <td width="100" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?permtype=permview&amp;idsubmod=<?php echo $fet_sub['idsyssubmodule'];?>&amp;saction=<?php if ($fet_checkperm['permview']==1) { echo "remove";} else { echo "add"; }?>#<?php echo $fet_mods['idsysmodule'];?>" id="button_check<?php if ($fet_checkperm['permview']==1){ echo "_on";}?>"></a></td>
				<td width="100" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?permtype=perminsert&amp;idsubmod=<?php echo $fet_sub['idsyssubmodule'];?>&amp;saction=<?php if ($fet_checkperm['perminsert']==1) { echo "remove";} else { echo "add"; }?>#<?php echo $fet_mods['idsysmodule'];?>" id="button_check<?php if ($fet_checkperm['perminsert']==1){ echo "_on";}?>"></a></td>
				<td width="100" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?permtype=permupdate&amp;idsubmod=<?php echo $fet_sub['idsyssubmodule'];?>&amp;saction=<?php if ($fet_checkperm['permupdate']==1) { echo "remove";} else { echo "add"; }?>#<?php echo $fet_mods['idsysmodule'];?>" id="button_check<?php if ($fet_checkperm['permupdate']==1){ echo "_on";}?>"></a></td>
				<td width="100" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?permtype=permdelete&amp;idsubmod=<?php echo $fet_sub['idsyssubmodule'];?>&amp;saction=<?php if ($fet_checkperm['permdelete']==1) { echo "remove";} else { echo "add"; }?>#<?php echo $fet_mods['idsysmodule'];?>" id="button_check<?php if ($fet_checkperm['permdelete']==1){ echo "_on";}?>"></a></td>
				<td width="100" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?permtype=permall&amp;idsubmod=<?php echo $fet_sub['idsyssubmodule'];?>&amp;saction=<?php if ( ($fet_checkperm['permview']==1) && ($fet_checkperm['perminsert']==1) && ($fet_checkperm['permupdate']==1) && ($fet_checkperm['permdelete']==1) ) { echo "remove_all"; } else { echo "add_all"; }?>#<?php echo $fet_mods['idsysmodule'];?>" id="button_check<?php if ( ($fet_checkperm['permview']==1) && ($fet_checkperm['perminsert']==1) && ($fet_checkperm['permupdate']==1) && ($fet_checkperm['permdelete']==1) ) { echo "_on"; }?>"></a></td>
                <td width="100" class="tbl_data" style="background-color:#EFEFEF"><a href="<?php echo $_SERVER['PHP_SELF'];?>?permtype=mobile_access&amp;idsubmod=<?php echo $fet_sub['idsyssubmodule'];?>&amp;saction=<?php if ($fet_checkperm['mobile_access']==1) { echo "remove";} else { echo "add"; }?>#<?php echo $fet_mods['idsysmodule'];?>" id="button_check<?php if ($fet_checkperm['mobile_access']==1){ echo "_on";}?>"></a></td>
                <td width="100" class="tbl_data" style="background-color:#EFEFEF"><a href="<?php echo $_SERVER['PHP_SELF'];?>?permtype=global_access&amp;idsubmod=<?php echo $fet_sub['idsyssubmodule'];?>&amp;saction=<?php if ($fet_checkperm['global_access']==1) { echo "remove";} else { echo "add"; }?>#<?php echo $fet_mods['idsysmodule'];?>" id="button_check<?php if ($fet_checkperm['global_access']==1){ echo "_on";}?>"></a></td>
              </tr>
              	<?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>
              <?php 
			  	} while ($fet_sub=mysql_fetch_array($res_sub));
			  		}
			  
			  } while ($fet_mods=mysql_fetch_array($res_mods));
			  	} else {
				?>
              <tr>
                	<td colspan="8">
                    <div class="msg_warning"><?php echo $msg_warn_contactadmin; ?></div>
                    </td>
              </tr>
              <?php
			  }
			  ?>
			</table>
		</form>
        </div>
	</div>
</div>
    