<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//
$_SESSION['parenttabview']=1;

if ((isset($_GET['saction'])) && ($_GET['saction']=="delete_role") )
	{
	$roleid=mysql_escape_string(trim($_GET['recid']));
	
	//first, check if this is the owner of this record
	$sql_owner="SELECT idusrrole FROM usrrole INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND idusrrole=".$roleid." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	$num_owner=mysql_num_rows($res_owner);
	
	if ($num_owner <1)
		{
		echo "<div class=\"msg_warning\">".$msg_warn_contactadmin.$ec100."</div>";
		exit;
		} else {
	//second, if first is ok, check if this record is associated with any other records 
	//check 1
		$sql_linkusrgroup="SELECT idlink_userac_usergroup FROM link_userrole_usergroup WHERE usrrole_idusrrole=".$roleid." LIMIT 1";
		$res_linkusrgroup=mysql_query($sql_linkusrgroup);
		$num_linkusrgroup=mysql_num_rows($res_linkusrgroup);
	//check 2
		$sql_linkusrac="SELECT idusrac FROM usrac WHERE usrrole_idusrrole=".$roleid." LIMIT 1";
		$res_linkusrac=mysql_query($sql_linkusrac);
		$num_linkusrac=mysql_num_rows($res_linkusrac);
	//check 3
		$sql_linkactors="SELECT idwfactors FROM wfactors WHERE usrrole_idusrrole=".$roleid." LIMIT 1";
		$res_linkactors=mysql_query($sql_linkactors);
		$num_linkactors=mysql_num_rows($res_linkactors);
	//check 4
		$sql_linktsks="SELECT idwftasks FROM wftasks WHERE usrrole_idusrrole=".$roleid." LIMIT 1";
		$res_linktsks=mysql_query($sql_linktsks);
		$num_linktsks=mysql_num_rows($res_linktsks);
		
	//then if not, delete
			if ( ($num_linkusrgroup<1) && ($num_linkusrac<1) && ($num_linkactors<1) && ($num_linktsks<1) )
				{
				$sql_deleterole="DELETE FROM usrrole WHERE idusrrole=".$roleid." LIMIT 1";
				mysql_query($sql_deleterole);
				
				$msg="<div>".$msg_changes_saved."</div>";
				} else {
				$msg="<div class=\"msg_warning\">".$msg_warning_opdeny_related."</div>";
				}
	
		} //close if owner of record
	
	} // if delete
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?></div>
    <div style="margin:5px; padding:0px 0px 15px 0px">
    <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=add_role" id="button_userrole"></a>
    </div>
    <div>
    <?php
    if (isset($_GET['msg_success'])) { echo "<div class=\"msg_success\">".$_GET['msg_success']."</div>"; } 
	if (isset($msg)) { echo $msg; }
	?>
    </div>
    <div>
    <div>
    	<div class="tab_area">
        	<span class="tab_on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=1"><?php echo $lbl_roles;?></a></span>
            <span class="tab_off"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=4">Vacant Roles</a></span>
			<span class="tab_off"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=2"><?php echo $lbl_groups;?></a></span>
        </div>
    </div>
    </div>
    <div>
    <table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
    	<tr>
        	<td class="tbl_h"><?php echo $lbl_role;?></td>
            <td class="tbl_h"><?php echo $lbl_zonename;?></td>
            <td class="tbl_h"><?php echo $lbl_access_profile;?></td>
            <td class="tbl_h"><?php echo $lbl_groups;?></td>
          <td class="tbl_h"><?php echo $lbl_occupied;?></td>
          
          <td class="tbl_h"></td>
      </tr>
        <?php
		$sql_role = "SELECT idusrrole,usrrolename,userteamzonename,sysprofile FROM usrrole 
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
INNER JOIN usrteam ON usrteamzone.usrteam_idusrteam=usrteam.idusrteam 
INNER JOIN sysprofiles ON usrrole.sysprofiles_idsysprofiles=sysprofiles.idsysprofiles
WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY usrrolename ASC";
		$res_role = mysql_query($sql_role);
		$num_role = mysql_num_rows($res_role);
		$fet_role = mysql_fetch_array($res_role);
		//echo $sql_role;
		$i=1;
		if ($num_role >0 )
			{
			do {
		?>
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td class="tbl_data">
			<a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;thisrole=<?php echo $fet_role['idusrrole'];?>&amp;rolename=<?php echo urlencode($fet_role['usrrolename']);?>"><?php echo $i.".&nbsp;".$fet_role['usrrolename'];?></a> 
            </td>
              <td class="tbl_data">
          <?php echo $fet_role['userteamzonename'];?>
          </td>
            <td class="tbl_data">
            <?php echo $fet_role['sysprofile'];?>
            </td>
            <td class="tbl_data">
			<?php 
			//check which groups this role belongs to
			$sql_groups="SELECT usrgroupname FROM link_userrole_usergroup 
			INNER JOIN usrgroup ON link_userrole_usergroup.usrgroup_idusrgroup=usrgroup.idusrgroup
			WHERE usrrole_idusrrole=".$fet_role['idusrrole']." ORDER BY usrgroupname ASC";
			$res_groups=mysql_query($sql_groups);
			$num_groups=mysql_num_rows($res_groups);
			
				if ($num_groups<1)
					{
					echo "---";//not configured
					} else {
					echo $num_groups;
					}
			?>
            </td>
            <td class="tbl_data" valign="top">
            <?php
			//check which user and user account occupies
			$sql_aco="SELECT usrname,utitle,fname,lname,acstatus FROM usrac WHERE usrrole_idusrrole=".$fet_role['idusrrole']." LIMIT 1";
			$res_aco=mysql_query($sql_aco);
			$num_aco=mysql_num_rows($res_aco);
				if ($num_aco > 0)
					{
					$fet_aco=mysql_fetch_array($res_aco);
					echo $fet_aco['usrname']." <small>(".$fet_aco['utitle']." ".$fet_aco['lname'].", ".$fet_aco['fname'].")&nbsp;";
					if ($fet_aco['acstatus']==1)
						{
						echo "<span style=\"color:#009900\">".$lbl_statusactive."</span></small>";
						} else {
						echo "<span style=\"color:#FF0000\">".$lbl_statusactivenot."</span></small>";
						}
					} else {
					echo "---";
					}
			?>
          </td>
        
          <td class="tbl_data">
          <a onclick="return confirm('<?php echo $msg_prompt_delete;?>')" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=delete_role&amp;recid=<?php echo $fet_role['idusrrole'];?>" id="button_delete_small"></a>
          </td>
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
			} while ($fet_role = mysql_fetch_array($res_role));
		} else {
		?>
        <tr>
        	<td colspan="6" height="50">
            <span class="msg_warning"><?php echo $msg_noroles;?></span>
            </td>
        </tr>
        <?php
		}
		?>
    </table>
  </div>
</div>