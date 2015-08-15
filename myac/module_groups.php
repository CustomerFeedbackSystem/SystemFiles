<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//check if this user is global or not for this module :)
$sql_globalperm="SELECT global_access FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$_SESSION['sec_submod']." AND
sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1 ";
$res_globalperm=mysql_query($sql_globalperm);
$fet_globalperm=mysql_fetch_array($res_globalperm);

if ($fet_globalperm['global_access']==1)
	{
	$_SESSION['qry_filter_global']=" usrgroup.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."  "; //this is global
	$_SESSION['is_global_view']=1;
	} else {
	$_SESSION['qry_filter_global']=" usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  AND usrgroup.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." "; //this is localised
	$_SESSION['is_global_view']=0;
	}

if ((isset($_GET['saction'])) && ($_GET['saction']=="delete_group") )
	{
	$groupid=mysql_escape_string(trim($_GET['recid']));
	
	//first, check if this is the owner of this record
	$sql_owner="SELECT idusrgroup FROM usrgroup WHERE usrteam_idusrteam =".$_SESSION['MVGitHub_idacteam']." AND idusrgroup=".$groupid." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	$num_owner=mysql_num_rows($res_owner);
	
	if ($num_owner <1)
		{
		echo "<div class=\"msg_warning\">".$msg_warn_contactadmin.$ec100."</div>";
		exit;
		} else {
	//second, if first is ok, check if this record is associated with any other records 
	//check 1
		$sql_linkusrgroup="SELECT idlink_userac_usergroup FROM link_userrole_usergroup WHERE usrgroup_idusrgroup=".$groupid." LIMIT 1";
		$res_linkusrgroup=mysql_query($sql_linkusrgroup);
		$num_linkusrgroup=mysql_num_rows($res_linkusrgroup);
		//echo $sql_linkusrgroup;
	//check 2
		$sql_linktsks="SELECT idwfactors FROM wfactors WHERE usrgroup_idusrgroup=".$groupid." LIMIT 1";
		$res_linktsks=mysql_query($sql_linktsks);
		$num_linktsks=mysql_num_rows($res_linktsks);
		
	//then if not, delete
			if ( ($num_linkusrgroup<1) && ($num_linktsks<1) )
				{
				$sql_deletegroup="DELETE FROM usrgroup WHERE idusrgroup=".$groupid." LIMIT 1";
				mysql_query($sql_deletegroup);
	
				$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
				} else {
				$msg="<div class=\"msg_warning\">".$msg_warning_opdeny_related."</div>";
				}
	
		} //close if owner of record
	
	} // if delete

?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div style="margin:5px; padding:0px 0px 15px 0px">
    <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=add_group" id="button_usergroup"></a>
    </div>
    <div>
    <?php
	//echo $_GET['msg'];
	 if (isset($_GET['msg'])) { echo "<div class=\"msg_success\">".$_GET['msg']."</div>"; } 
	?>
    </div>
    <div>
        <div class="tab_area">
            <span class="tab_off"><a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;parentviewtab=1"><?php echo $lbl_roles;?></a></span>
            <span class="tab_off"><a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;parentviewtab=4">Vacant Roles</a></span>
            <span class="tab_on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;parentviewtab=2"><?php echo $lbl_groups;?></a></span>            
        ff</div>
    </div>
    <div>
    <?php
    if (isset($_GET['msg']))
		{
		echo "<div class=\"msg_success\">".urldecode($_GET['msg'])."</div>";
		}
	if (isset($msg))
		{
		echo $msg;
		}
	?>
    </div>
    <div>
    <table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
    	<tr>
        	<td class="tbl_h"><?php echo $lbl_group;?></td>
            <td class="tbl_h"><?php echo $lbl_roles;?></td>
          <td class="tbl_h"></td>
      </tr>
        <?php
		$sql_groups = "SELECT usrgroupname,idusrgroup FROM usrgroup 
		INNER JOIN link_userrole_usergroup ON usrgroup.idusrgroup=link_userrole_usergroup.usrgroup_idusrgroup 
		INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole=usrrole.idusrrole
		WHERE ".$_SESSION['qry_filter_global']." GROUP BY usrgroup.idusrgroup ORDER BY usrgroupname ASC";
		$res_groups = mysql_query($sql_groups);
		$num_groups = mysql_num_rows($res_groups);
		$fet_groups = mysql_fetch_array($res_groups);
		//echo $sql_groups;
		$i=1;
		if ($num_groups >0 )
			{
			do {
		?>
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td class="tbl_data">
			<a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;thisgroup=<?php echo $fet_groups['idusrgroup'];?>&amp;groupname=<?php echo urlencode($fet_groups['usrgroupname']);?>"><?php echo $i.".&nbsp;".$fet_groups['usrgroupname'];?></a>
            </td>
            <td class="tbl_data">
			<?php 
			//check which groups this role belongs to
			$sql_roles="SELECT usrrolename FROM link_userrole_usergroup 
			INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole=usrrole.idusrrole
			WHERE link_userrole_usergroup.usrgroup_idusrgroup=".$fet_groups['idusrgroup']." ORDER BY usrrolename ASC";
			$res_roles=mysql_query($sql_roles);
			$num_roles=mysql_num_rows($res_roles);
			$fet_roles=mysql_fetch_array($res_roles);
			//echo $sql_roles;
				if ($num_roles<1)
					{
					echo "---";//not configured
					} else {
						do {
						echo "-&nbsp;".$fet_roles['usrrolename']."<br>";
						} while ($fet_roles=mysql_fetch_array($res_roles));
					}
			?>             </td>
          <td class="tbl_data">
          <a onclick="return confirm('<?php echo $msg_prompt_delete;?>');" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=delete_group&amp;recid=<?php echo $fet_groups['idusrgroup'];?>" id="button_delete_small"></a></td>
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
			$i++;
			} while ($fet_groups = mysql_fetch_array($res_groups));
		} else {
		?>
        <tr>
        	<td colspan="3" height="50">
            <span class="msg_warning"><?php echo $msg_nogroups;?></span>            </td>
        </tr>
        <?php
		}
		?>
    </table>
    </div>
</div>    