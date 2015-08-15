<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//echo "ttt";
if (isset($_GET['do']))
	{
		if ($_GET['do']=="assign_role")
			{
			$groupid=mysql_escape_string(trim($_GET['id']));
			//first check if a similar record already exists
			$sql_exists = "SELECT idwfactors FROM wfactors WHERE usrgroup_idusrgroup=".$groupid." AND wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1";
			$res_exists = mysql_query($sql_exists);
			$num_exists = mysql_num_rows($res_exists);
			
			if ($num_exists ==0) //if no record exists, then...
				{
				
				
				$sql_actor="INSERT INTO wfactors (usrrole_idusrrole,usrgroup_idusrgroup,wftskflow_idwftskflow,usrteamzone_idusrteamzone,createdby,createdon) 
				VALUES ('0','".$groupid."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_userteamzoneid']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
				mysql_query($sql_actor);
				
				//now, if the task group share is turned on, the enable it
				if ($_SESSION['group_task_share']==1) //
					{
					//get the id 
					$sql_taskshare_groupname="SELECT idwfactorsgroupname FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
					$res_taskshare_groupname=mysql_query($sql_taskshare_groupname);
					$fet_taskshare_groupname=mysql_fetch_array($res_taskshare_groupname);
					
					//get the loop of the user roles in that group
					$sql_group_roles="SELECT usrrole_idusrrole FROM link_userrole_usergroup WHERE usrgroup_idusrgroup=".$groupid." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY idlink_userac_usergroup ASC";
					$res_group_roles=mysql_query($sql_group_roles);
					$num_group_roles=mysql_num_rows($res_group_roles);
					$fet_group_roles=mysql_fetch_array($res_group_roles);
									
					//then loop and insert
					do {
					//as you insert, first precheck before inserting 
						$sql_exists_group="SELECT usrrole_idusrrole FROM wfactorsgroup WHERE usrrole_idusrrole=".$fet_group_roles['usrrole_idusrrole']." AND wftskflow_idwftskflow=".$_SESSION['idflow']."";
						$res_exists_group=mysql_query($sql_exists_group);
						$num_exists_group=mysql_num_rows($res_exists_group);
								
						if ($num_exists_group < 1) //if there is no record, then insert
							{
							$sql_insert_group="INSERT INTO wfactorsgroup (wfactorsgroupname_idwfactorsgroupname,usrrole_idusrrole,wftskflow_idwftskflow,createdby,createdon) 
							VALUES ('".$fet_taskshare_groupname['idwfactorsgroupname']."','".$fet_group_roles['usrrole_idusrrole']."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
							mysql_query($sql_insert_group);
							}										
										
						} while ($fet_group_roles=mysql_fetch_array($res_group_roles));
					
					} //group_task_share
				
				}
			
			}
			
		if ($_GET['do']=="unassign_role")
			{
			$groupid=mysql_escape_string(trim($_GET['id']));
			$sql_actor="DELETE FROM wfactors WHERE usrgroup_idusrgroup=".$groupid." AND wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1";
			mysql_query($sql_actor);
			
			
			}
	}

//list the roles in this organisation and their respective users
/*$sql_teamgroup="SELECT idusrgroup,usrgroupname,usrgroupdesc,usrgroup_idusrgroup FROM usrgroup 
LEFT JOIN wfactors ON usrgroup.idusrgroup=wfactors.usrgroup_idusrgroup
WHERE usrgroup.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."  ORDER BY usrgroupname ASC";*/
$sql_teamgroup = "SELECT idusrgroup,usrgroupname,usrgroupdesc, 
(SELECT idwfactors FROM wfactors WHERE usrgroup.idusrgroup=wfactors.usrgroup_idusrgroup AND wftskflow_idwftskflow=".$_SESSION['idflow'].") AS dis_user 
FROM usrgroup
WHERE usrgroup.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."  ORDER BY usrgroupname ASC";
$res_teamgroup=mysql_query($sql_teamgroup);
$fet_teamgroup=mysql_fetch_array($res_teamgroup);
$num_teamgroup=mysql_num_rows($res_teamgroup);

//list the roles that have been allocated
$sql_groupsasi = "SELECT idusrgroup,usrgroupname,usrgroupdesc FROM usrgroup 
INNER JOIN wfactors ON usrgroup.idusrgroup=wfactors.usrgroup_idusrgroup 
WHERE usrgroup.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND wftskflow_idwftskflow=".$_SESSION['idflow']." ORDER BY usrgroupname ASC";
$res_groupsasi = mysql_query($sql_groupsasi);
$num_groupsasi = mysql_num_rows($res_groupsasi);
$fet_groupsasi = mysql_fetch_array($res_groupsasi);
?>
<div style="padding:5px 5px 10px 5px">
	<span class="msg_instructions_small"><?php echo $ins_resp;?></span>
</div>
<div>
<table border="0" width="790" cellpadding="1" cellspacing="0">
	<tr>
    	<td valign="top" class="hline">
        	<table border="0" cellpadding="2" cellspacing="0" width="395">
            	<tr>
                	<td class="tbl_h"><?php echo $lbl_group;?></td>
                    <td class="tbl_h" ><?php echo $lbl_description;?></td>
                    <td class="tbl_h" >&gt;</td>
                </tr>
                <?php
				//echo "actorid >>".$fet_teamgroup['actorid']
					do {
					?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td class="tbl_data"><span title="<?php echo $fet_teamgroup['usrgroupdesc'];?>"><?php echo $fet_teamgroup['usrgroupname'];?></span></td>
                    <td class="tbl_data"><?php echo $fet_teamgroup['usrgroupdesc'];?></td>
                    <td width="30" class="tbl_data">
                    <?php
					if ($fet_cact['usrrole_idusrrole']<1) //if not set to roles
						{
					if ($fet_teamgroup['dis_user']=="") { ?>
                    <a title="<?php echo $ins_assignresp; ?>" href="<?php echo $_SERVER['PHP_SELF'];?>?do=assign_role&amp;id=<?php echo $fet_teamgroup['idusrgroup'];?>" id="button_send_right"></a>
                    <?php } else { ?>
                    <img src="../assets_backend/btns/btn_send_right_disabled.jpg" border="0" align="absmiddle" title="<?php echo $lbl_disabled;?>" />
                    <?php } 
						} //close if not set to roles
						?>
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
					} while ($fet_teamgroup=mysql_fetch_array($res_teamgroup));
					
				
				if ($num_teamgroup < 2)
					{
				?>
              <!--  <tr>
                	<td colspan="3">
                    <div class="msg_warning_small">
                    <?php //echo $msg_no_role;?>
                    </div>
                    </td>
                </tr> -->
                <?php } ?>
            </table>
        </td>
        <td valign="top"  width="395">
        <?php				
		if ($num_groupsasi > 0 )
			{
		?>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
            	<tr>
                	<td width="30" class="tbl_h">&lt;</td>
                  <td class="tbl_h"><?php echo $lbl_group;?></td>
                    <td class="tbl_h"><?php echo $lbl_description;?></td>
                </tr>
                <?php
				
					do {
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td width="30" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?do=unassign_role&amp;id=<?php echo $fet_groupsasi['idusrgroup'];?>" id="button_send_left"></a></td>
                  <td class="tbl_data"><?php echo $fet_groupsasi['usrgroupname']?></td>
                    <td class="tbl_data"><?php echo $fet_groupsasi['usrgroupdesc']?></td>
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
					} while ($fet_groupsasi = mysql_fetch_array($res_groupsasi)); 
				?>
			</table>
				<?php	} else { ?>
            <table>
                <tr>
                	<td colspan="3" align="center" height="40">
                    <span class="msg_warning_small">
                    <?php echo $msg_select_group; ?>
                    </span>
                    </td>
                </tr>
                <?php } ?>
                </table>
        </td>
    </tr>
</table>
</div>
