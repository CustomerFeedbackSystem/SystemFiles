<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ( (isset($_GET['saction'])) && ($_GET['saction']=="delete_team") )
	{
	if (isset($_GET['team']))
		{
		$usrteam=mysql_escape_string(trim($_GET['team']));
		}
		
	//then check ssociated records
	
	//2-usrteamzone table
	$sql_chk2="SELECT idusrteamzone FROM usrteamzone WHERE usrteam_idusrteam=".$usrteam." LIMIT 1";
	$res_chk2=mysql_query($sql_chk2);
	$num_chk2=mysql_num_rows($res_chk2);
	
	//3- systeamaccess
	$sql_chk3="SELECT idsysteamaccess FROM systeamaccess WHERE usrteam_idusrteam=".$usrteam." LIMIT 1";
	$res_chk3=mysql_query($sql_chk3);
	$num_chk3=mysql_num_rows($res_chk3);
	
	//4- wfproc
	$sql_chk4="SELECT idwfproc FROM wfproc WHERE usrteam_idusrteam=".$usrteam." LIMIT 1";
	$res_chk4=mysql_query($sql_chk4);
	$num_chk4=mysql_num_rows($res_chk4);
	
	//5- usrgroup
	$sql_chk5="SELECT idusrgroup FROM usrgroup WHERE usrteam_idusrteam=".$usrteam." LIMIT 1";
	$res_chk5=mysql_query($sql_chk5);
	$num_chk5=mysql_num_rows($res_chk5);
	
	//6- usrac
	$sql_chk6="SELECT idusrac FROM usrac WHERE usrteam_idusrteam=".$usrteam." LIMIT 1";
	$res_chk6=mysql_query($sql_chk6);
	$num_chk6=mysql_num_rows($res_chk6);
	
	//7- link_userrole_usergroup
	$sql_chk7="SELECT idlink_userac_usergroup FROM link_userrole_usergroup WHERE usrteam_idusrteam=".$usrteam." LIMIT 1";
	$res_chk7=mysql_query($sql_chk7);
	$num_chk7=mysql_num_rows($res_chk7);
	
	if ( ($num_chk2<1) && ($num_chk3<1) && ($num_chk4<1) && ($num_chk5<1) && ($num_chk6<1) && ($num_chk7<1)  )
		{
		//delete
		$sql_delete="DELETE FROM usrteam WHERE idusrteam=".$usrteam." LIMIT 1";
		mysql_query($sql_delete);
		
		$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
		} else {
		$msg="<div class=\"msg_warning\">".$ec12."</div>";
		}
	
	} //close delete formaction

?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div style="margin:5px; padding:0px 0px 15px 0px">
    <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=add_team" id="button_newteam"></a>
    </div>
    <div>
    <?php if (isset($_GET['msg_success'])) { echo "<div class=\"msg_success\">".$_GET['msg_success']."</div>"; } ?>
    </div>
	<div>
    <?php
	if (isset($msg)) { echo $msg; } 
	?>
    </div>
    <div>
    <table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
    	<tr>
        	<td class="tbl_h"><?php echo $lbl_tbl_organisation;?></td>
            <td class="tbl_h"><?php echo $lbl_teamtype;?></td>
          <td class="tbl_h"><?php echo $lbl_zone;?></td>
          <td class="tbl_h"></td>
      </tr>
        <?php
		$sql_profiles = "SELECT idusrteam,usrteamtype_idusrteamtype,usrteamname,usrteamtypename,usrteamshortname,acstatus FROM usrteam INNER JOIN usrteamtype ON usrteam.usrteamtype_idusrteamtype=usrteamtype.idusrteamtype ORDER BY usrteamname ASC";
		$res_profiles = mysql_query($sql_profiles);
		$num_profiles = mysql_num_rows($res_profiles);
		$fet_profiles = mysql_fetch_array($res_profiles);
		//echo $sql_profiles;
		$i=1;
		if ($num_profiles >0 )
			{
			do {
		?>
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td class="tbl_data">
			<a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;thisteam=<?php echo $fet_profiles['idusrteam'];?>&amp;teamname=<?php echo urlencode($fet_profiles['usrteamname']);?>&amp;teamshort=<?php echo urlencode($fet_profiles['usrteamshortname']);?>"><?php echo $i.".&nbsp;".$fet_profiles['usrteamname'];?></a>
            <?php
			if ($fet_profiles['acstatus']==0)
				{
				echo "&nbsp;<span style=\"color:#ff0000\">[ <small>".$lbl_statusactivenot."</small> ]</span>";
				}
			?>
            </td>
            <td class="tbl_data">
			<?php echo $fet_profiles['usrteamtypename'];?>			</td>
            <td class="tbl_data" valign="top">
            <?php 
			//roles
			$sql_zones="SELECT count(*) as no_of_zones FROM usrteamzone WHERE usrteam_idusrteam=".$fet_profiles['idusrteam']."";
			$res_zones=mysql_query($sql_zones);
			$fet_zones=mysql_fetch_array($res_zones)
			?>
          <?php if ($fet_zones['no_of_zones']>0) { ?>
          <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_subsubmod&amp;parentviewtab=2&amp;thisteam=<?php echo $fet_profiles['idusrteam'];?>&amp;teamname=<?php echo urlencode($fet_profiles['usrteamname']);?>&amp;teamshort=<?php echo urlencode($fet_profiles['usrteamshortname']);?>">[ <?php echo $fet_zones['no_of_zones']; ?> ]</a>
          <?php } else { ?>
          0
          <?php } ?>
          </td>
          <td class="tbl_data">
          <a onclick="return confirm('<?php echo $msg_prompt_delete;?>')" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=delete_team&amp;team=<?php echo $fet_profiles['idusrteam'];?>" id="button_delete_small"></a>
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
			} while ($fet_profiles = mysql_fetch_array($res_profiles));
		} else {
		?>
        <tr>
        	<td colspan="3" height="50">
            <span class="msg_warning"><?php echo $msg_noprofiles;?></span>            </td>
        </tr>
        <?php
		}
		?>
    </table>
  </div>
</div>