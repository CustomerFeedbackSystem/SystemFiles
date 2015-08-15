<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//echo "test";
if (isset($_GET['do']))
	{
		if ($_GET['do']=="assign_role")
			{
			$roleid=mysql_escape_string(trim($_GET['id']));
			//first check if a similar record already exists
			$sql_exists = "SELECT idwfactors FROM wfactors WHERE usrrole_idusrrole=".$roleid." AND wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1";
			$res_exists = mysql_query($sql_exists);
			$num_exists = mysql_num_rows($res_exists);
			
			if ($num_exists ==0) //if no record exists, then...
				{
			$sql_actor="INSERT INTO wfactors (usrrole_idusrrole,usrgroup_idusrgroup,wftskflow_idwftskflow,usrteamzone_idusrteamzone,createdby,createdon) 
			VALUES ('".$roleid."','0','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_userteamzoneid']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_actor);
				}
			
			}
			
		if ($_GET['do']=="unassign_role")
			{
			//echo $_GET['do'];
			$roleid=mysql_escape_string(trim($_GET['id']));
			$sql_actor="DELETE FROM wfactors WHERE usrrole_idusrrole=".$roleid." AND wftskflow_idwftskflow=".$_SESSION['idflow']." AND usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1";
			mysql_query($sql_actor);
			}
	}

//list the roles in this organisation and their respective users
$sql_teamrole="SELECT idusrrole, usrrolename, usrroledesc, usrname, utitle, fname, lname, acstatus,
(SELECT idwfactors FROM wfactors WHERE usrrole.idusrrole = wfactors.usrrole_idusrrole AND wftskflow_idwftskflow=".$_SESSION['idflow'].") AS dis_user
FROM usrrole
INNER JOIN usrac ON usrrole.idusrrole = usrac.usrrole_idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone = usrteamzone.idusrteamzone
WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."
ORDER BY usrrolename ASC";
$res_teamrole=mysql_query($sql_teamrole);
$fet_teamrole=mysql_fetch_array($res_teamrole);
$num_teamrole=mysql_num_rows($res_teamrole);

//list the roles that have been allocated
$sql_rolesasi = "SELECT idusrrole,usrrolename,usrroledesc,usrname,utitle,fname,lname,acstatus FROM usrrole 
LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole 
INNER JOIN wfactors ON usrrole.idusrrole=wfactors.usrrole_idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND wftskflow_idwftskflow=".$_SESSION['idflow']." ORDER BY usrrolename ASC";
$res_rolesasi = mysql_query($sql_rolesasi);
$num_rolesasi = mysql_num_rows($res_rolesasi);
$fet_rolesasi = mysql_fetch_array($res_rolesasi);

?>
<div style="padding:5px 5px 10px 5px">
	<span class="msg_instructions"><?php echo $ins_resp;?></span>
</div>
<div>
<table border="0" width="790" cellpadding="1" cellspacing="0">
	<tr>
    	<td valign="top" class="hline">
        	<table border="0" cellpadding="2" cellspacing="0" width="395">
            	<tr>
                	<td class="tbl_h"><?php echo $lbl_role;?></td>
                    <td class="tbl_h" ><?php echo $lbl_username;?></td>
                    <td class="tbl_h" >&gt;</td>
                </tr>
                <?php
					do {
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td class="tbl_data"><span title="<?php echo $fet_teamrole['usrroledesc'];?>"><?php echo $fet_teamrole['usrrolename'];?></span></td>
                    <td class="tbl_data">
                    <?php
					if (strlen($fet_teamrole['usrname'])>0) { ?>
                    <span title="<?php echo $fet_teamrole['utitle']." ".$fet_teamrole['lname']." , ".$fet_teamrole['fname'];?>"><?php echo $fet_teamrole['usrname'];?></span>
                    <?php } else { echo "---"; } ?>
                    </td>
                    <td width="30" class="tbl_data">
                    <?php
				if ((!isset($fet_cact['usrgroup_idusrgroup'])) || ($fet_cact['usrgroup_idusrgroup'] < 1)) //if not set to group
					{
					if ($fet_teamrole['dis_user']=="") { ?>
                    <a title="<?php echo $ins_assignresp; ?>" href="<?php echo $_SERVER['PHP_SELF'];?>?do=assign_role&amp;id=<?php echo $fet_teamrole['idusrrole'];?>" id="button_send_right"></a>
                    <?php } else { ?>
                    <img src="../assets_backend/btns/btn_send_right_disabled.jpg" border="0" align="absmiddle" title="<?php echo $lbl_disabled;?>" />
                    <?php } 
					} //if not set to group
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
					
					} while ($fet_teamrole=mysql_fetch_array($res_teamrole));
					
				
				if ($num_teamrole < 2)
					{
				?>
              <!--  <tr>
                	<td colspan="3">
                    <div class="msg_warning">
                    <?php //echo $msg_no_role;?>
                    </div>
                    </td>
                </tr> -->
                <?php } ?>
            </table>
        </td>
        <td valign="top"  width="395">
        <?php				
		if ($num_rolesasi > 0 )
			{
		?>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
            	<tr>
                	<td width="30" class="tbl_h">&lt;</td>
                  <td class="tbl_h"><?php echo $lbl_role;?></td>
                    <td class="tbl_h"><?php echo $lbl_username;?></td>
                </tr>
                <?php
				
					do {
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td width="30" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?do=unassign_role&amp;id=<?php echo $fet_rolesasi['idusrrole'];?>" id="button_send_left"></a></td>
                  <td class="tbl_data"><?php echo $fet_rolesasi['usrrolename']?></td>
                    <td class="tbl_data">
					<span title="<?php echo $fet_rolesasi['utitle']." ".$fet_rolesasi['lname']." , ".$fet_rolesasi['fname'];?>">
					<?php echo $fet_rolesasi['usrname']?>
                    </span>
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
					} while ($fet_rolesasi = mysql_fetch_array($res_rolesasi)); 
				?>
			</table>
				<?php	} else { ?>
            <table>
                <tr>
                	<td colspan="3" align="center" height="40">
                    <span class="msg_warning">
                    <?php echo $msg_select_role; ?>
                    </span>
                    </td>
                </tr>
                <?php } ?>
                </table>
        </td>
    </tr>
</table>
</div>
