<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['thisteam']))
	{
	$_SESSION['thisteamid']=mysql_escape_string(trim($_GET['thisteam']));
	}

if (isset($_GET['teamname']))
	{
	$_SESSION['thisteamname']=urldecode(trim($_GET['teamname']));
	}
if (isset($_GET['teamshort']))
	{
	$_SESSION['thisteamshort']=urldecode(trim($_GET['teamshort']));
	}

if ((isset($_GET['saction'])) && ($_GET['saction']=="deletezone") )
	{
	$dzone=mysql_escape_string(trim($_GET['zone']));

//check if there are related records before carrying out this operation
	//first, check the Workflow Table
	$sql_wf="SELECT idwfproc FROM wfproc WHERE usrteamzone_idusrteamzone=".$dzone." LIMIT 1";
	$res_wf=mysql_query($sql_wf);
	$num_wf=mysql_num_rows($res_wf);
	
	//then, the sysprofile table
	$sql_prof="SELECT idsysprofiles FROM sysprofiles WHERE usrteamzone_idusrteamzone=".$dzone." LIMIT 1";
	$res_prof=mysql_query($sql_prof);
	$num_prof=mysql_num_rows($res_prof);
	
	//then, check the UserRole Table
	$sql_rol="SELECT idusrrole FROM usrrole WHERE usrteamzone_idusrteamzone=".$dzone." LIMIT 1";
	$res_rol=mysql_query($sql_rol);
	$num_rol=mysql_num_rows($res_rol);
	
//if any of the tables have a record, then throw an error
if ( ($num_wf>0) || ($num_prof>0) || ($num_rol>0) )
	{
		$error_1="<div class=\"msg_warning\">".$msg_warn_contactadmin.$ec12."</div>";
	} else {
		//ONLY WASREB SUPER ADMIN role id 1 or by the owner of this record
		if ($_SESSION['MVGitHub_iduserrole']!=1) //Check only when IT IS NOT THE SUPER ADMIN Logged in
			{
			$sql_zone="SELECT idusrteamzone FROM usrteamzone WHERE idusrteamzone=".$dzone." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
			$res_zone=mysql_query($sql_zone);
			$num_zone=mysql_num_rows($res_zone);
		
			if ($num_zone > 0)
				{
				echo "<div class=\"msg_warning\">".$msg_warn_contactadmin.$ec100."</div>";
				exit;
				} else {		
				//delete
				$sql_deletezone="DELETE FROM usrteamzone WHERE idusrteamzone=".$dzone." LIMIT 1";
				mysql_query($sql_deletezone);
				$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
				
				}
			} else { //else if it is super admin, the delete
			$sql_deletezone="DELETE FROM usrteamzone WHERE idusrteamzone=".$dzone." LIMIT 1";
			mysql_query($sql_deletezone);
			$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
			} //if not superadmin
			
		}	//close if no records found
		
	} /// close if delete request is set
?>
<div>
	<div class="bg_section">
    <?php echo $_SESSION['lblmodule']; ?> &raquo; <?php echo $_SESSION['lblsubmodule']; ?>
    </div>
   	<div class="tab_area">
        	<span class="tab">
           <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;thisteam=<?php echo $_SESSION['thisteamid'];?>"><?php echo $lbl_teamdetails;?></a>
            </span>
            <span class="tab_on">
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_subsubmod&amp;submod=18&amp;parentviewtab=2"><?php echo $lbl_zone;?>
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
    <div style="padding:5px">
    <div style="padding:5px;">
    <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=add_subsubmod" id="button_newzone"></a>
    </div>
    <div class="msg_instructions"><?php echo $msg_zones;?></div>
     <div>
    <?php if (isset($_GET['msg_success'])) { echo "<div class=\"msg_success\">".$_GET['msg_success']."</div>"; } ?>
    </div>
    </div>
    <div class="table_header">
    <?php echo $_SESSION['thisteamname'];?>
    </div>
    
    <div>
	<?php
    if (isset($msg)) { 
	echo $msg;
	}
	if (isset($error_1)) { 
	echo $error_1;
	}
	?>
    </div>
<?php
$sql_zones="SELECT idusrteamzone,usrteam_idusrteam,userteamzonename,teamzonephone,teamzoneemail,physicaladdress,postaladdress,usrteamzone.lat,usrteamzone.lng,countryname,locationname  FROM usrteamzone 
INNER JOIN loccountry ON usrteamzone.loccountry_idloccountry=loccountry.idloccountry
INNER JOIN loctowns ON usrteamzone.loctowns_idloctowns=loctowns.idloctowns
WHERE usrteam_idusrteam=".$_SESSION['thisteamid']." ORDER BY userteamzonename ASC";
$res_zones=mysql_query($sql_zones);
$num_zones=mysql_num_rows($res_zones);
$fet_zones=mysql_fetch_array($res_zones);
//echo $sql_zones;
$_SESSION['number_of_zones']=$num_zones;
?>    
    <div>
    <?php 
	if ($num_zones>0) { 
	?>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
    	<tr>
        	<td class="tbl_h">
            <?php echo $lbl_name;?>
            </td>
            <td class="tbl_h">
            <?php echo $lbl_email;?>
            </td>
            <td class="tbl_h">
            <?php echo $lbl_telephone;?>
            </td>
            <td class="tbl_h">
            <?php echo $lbl_location;?>
            </td>
            <td class="tbl_h">
            <?php echo $lbl_physical;?>
            </td>
            <td class="tbl_h"></td>
        </tr>
<?php do { ?>        
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td class="tbl_data">
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_subsubmod&amp;zoneid=<?php echo $fet_zones['idusrteamzone'];?>&amp;zonename=<?php echo urlencode($fet_zones['userteamzonename']); ?>"><?php echo $fet_zones['userteamzonename'];?></a>
            </td>
            <td class="tbl_data">
            <?php echo $fet_zones['teamzoneemail'];?>
            </td>
            <td class="tbl_data">
            <?php if (strlen($fet_zones['teamzonephone'])>0){ echo $fet_zones['teamzonephone']; } else { echo "-"; }?>
            </td>
            <td class="tbl_data">
            <?php echo $fet_zones['locationname'].", ".$fet_zones['countryname'];?>
            </td>
            <td class="tbl_data">
            <?php if (strlen($fet_zones['physicaladdress']) >0) { echo $fet_zones['physicaladdress']; } else { echo "-"; }?>
            </td>
            <td class="tbl_data">
            <a onclick="return confirm('<?php echo $msg_prompt_delete;?>');" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=deletezone&amp;zone=<?php echo $fet_zones['idusrteamzone'];?>" id="button_delete_small"></a>
            </td>
        </tr>
<?php 
	} while ($fet_zones=mysql_fetch_array($res_zones));
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>        
    </table>
    <?php } else { ?>
    <div class="msg_warning">
    <?php echo $msg_warning_nozone; ?>
    </div>
    <?php } ?>
    </div>
</div>
    
    
    