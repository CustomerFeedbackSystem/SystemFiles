<?php //require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

?>
<div>
    <div >
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
    </table>
    </div>
    <div style="padding:20px 20px 20px 0px">
    <table border="0" style="padding:20px">
    	<tr>
        	<td valign="top">
			<form method="get" action="generate_report.php" target="_blank" name="reports">
				<table border="0" cellpadding="2" cellspacing="0">
			  <tr>
						<td colspan="2" class="bg_section">
						
                            <?php
                            if (isset($fet_heading['submodule']))
                            {
                            echo $fet_heading['submodule'];
                            }
                            ?>
                            <input type="hidden" value="<?php echo $fet_heading['submodule']; ?>" name="pagetitle">
                            <input type="hidden" value="<?php echo $_SESSION['sec_submod'];?>" name="reportid">
                            
						</td>
					</tr>
					<tr>
						<td height="40" class="tbl_data">Specify: </td>
					  <td height="40" class="tbl_data">
                        <select name="reportid">
                        <option value="0">---</option>
						<?php
						$sql_mtr="SELECT idusrrole FROM usrrole WHERE reportingto=".$_SESSION['MVGitHub_iduserrole']." LIMIT 1"; 
						$res_mtr=mysql_query($sql_mtr);
						$num_mtr=mysql_num_rows($res_mtr);
						?>
                         <option value="-2" <?php if ($num_mtr<1){ echo "disabled=\"disabled\"  title=\"You don't seem to have anyone reporting to you\" "; } ?>> My Team ( Overview )</option>
                        <?php
						if ($is_perm_global==1) //if perm global, then give this reports options below
							{
						?>
                        <option value="-1"> Overall Work Status (<?php echo $_SESSION['MVGitHub_userteamshortname']; ?>)</option>
                        <?php
						$sql="SELECT idusrgroup,usrgroupname FROM usrgroup WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
						$res=mysql_query($sql);
						$fet=mysql_fetch_array($res);
						do {
						echo "<option value=\"".$fet['idusrgroup']."\">".$fet['usrgroupname']."</option>";
						} while ($fet=mysql_fetch_array($res));
							} //close if perm global
						?>
                        </select>
						</td>
					</tr>
					<tr>
                        <td height="40" class="tbl_data">Starting Date : </td>
                      <td height="40" class="tbl_data">
                        <input name="timestart"  onClick="displayDatePicker('timestart');" type="text" id="timestart" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
				  </tr>
					<tr>
                        <td height="40" class="tbl_data">Ending Date : </td>
                      <td height="40" class="tbl_data">
                        <input name="timestop" type="text" onClick="displayDatePicker('timestop');"  id="timestop" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">                        </td>
				  </tr>
					<tr>
						<td height="60">&nbsp;</td>
					  <td>
						<a href="#" id="button_submit" onClick="document.forms['reports'].submit();"></a>
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>
    </div>
</div>
