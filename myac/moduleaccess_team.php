<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <!--
    <div style="margin:5px">
    <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=add_submod_access" id="button_newtma"></a>
    </div>
    -->
    <div style="padding:10px 0px 0px 0px">
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
    	<tr>
        	<td class="tbl_h"><?php echo $lbl_tbl_organisation;?></td>
            <td class="tbl_h"><?php echo $lbl_tbl_teamtype?></td>
            <td class="tbl_h"><?php echo $lbl_tbl_modules?></td>
        </tr>
        <?php
		$sql_teams = "SELECT idusrteam,usrteamtype_idusrteamtype,usrteamname,usrteamtypename,acstatus FROM usrteam INNER JOIN usrteamtype ON usrteam.usrteamtype_idusrteamtype=usrteamtype.idusrteamtype ORDER BY usrteamname";
		$res_teams = mysql_query($sql_teams);
		$num_teams = mysql_num_rows($res_teams);
		$fet_teams = mysql_fetch_array($res_teams);
		//echo $sql_teams;
		$i=1;
		if ($num_teams >0 )
			{
			do {
		?>
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td class="tbl_data">
			<a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;thisteam=<?php echo $fet_teams['idusrteam'];?>"><?php echo $i.".&nbsp;".$fet_teams['usrteamname'];?></a>
            </td>
            <td class="tbl_data"><?php echo $fet_teams['usrteamtypename'];?></td>
            <td class="tbl_data" valign="top">
            <?php
			$sql_mods="SELECT modulename FROM systeamaccess INNER JOIN sysmodule ON systeamaccess.sysmodule_idsysmodule=sysmodule.idsysmodule WHERE usrteam_idusrteam=".$fet_teams['idusrteam']." ORDER BY sysmodule.listorder ASC";
			$res_mods=mysql_query($sql_mods);
			$num_mods=mysql_num_rows($res_mods);
			$fet_mods=mysql_fetch_array($res_mods);
				if ($num_mods > 0 )
					{
					echo "<em><ol>";
					do {
				?>
                	<li><?php echo $fet_mods['modulename'];?></li>
            	<?php 
					} while ($fet_mods=mysql_fetch_array($res_mods));
					echo "</ol></em>";
				} else { ?>
				<span class="msg_warning"><?php echo $msg_no_module_alloc;?></span>
                <?php } ?>
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
			} while ($fet_teams = mysql_fetch_array($res_teams));
		} else {
		?>
        <tr>
        	<td colspan="3" height="50">
            <span class="msg_warning"><?php echo $msg_noteams; ?></span>
            </td>
        </tr>
        <?php
		}
		?>
    </table>
    </div>
</div>