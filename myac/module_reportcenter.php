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
    <div style="padding:5px 20px 20px 0px">
    <table border="0" style="padding:5px" width="100%">
    	<tr>
        	<td valign="top">
            <?php 
			if ($_SESSION['sec_submod']==28) { require_once('reports/general_tickets_overview.php');}  //Previously general_reports_regional.php
			if ($_SESSION['sec_submod']==29) { require_once('reports/general_tickets_received.php');}  //Previousl general_reports_global.php
			if ($_SESSION['sec_submod']==66) { require_once('reports/general_tickets_pending.php');}  //Previousl general_reports_global.php
			if ($_SESSION['sec_submod']==67) { require_once('reports/general_tickets_closed.php');}  //Previousl general_reports_global.php
			?>
            <!--
			<form method="get" action="generate_report.php" target="_blank" name="reports">
				<table border="0" cellpadding="2" cellspacing="0">
			  <tr>
						<td colspan="2" class="text_body_large">
						<h3>
                            <?php
                          /*  if (isset($fet_heading['submodule']))
                            {
                            echo $fet_heading['submodule'];
                            }
							*/
                            ?>
                            <input type="hidden" value="<?php echo $fet_heading['submodule']; ?>" name="pagetitle">
                            <input type="hidden" value="<?php echo $_SESSION['sec_submod'];?>" name="reportid">
                            </h3>
						</td>
					</tr>
					<tr>
						<td height="40" class="tbl_data">Specify Report : </td>
					  <td height="40" class="tbl_data">
                        <?php
						/*
						//specify the type
						$sql_stable="SELECT dropdown_source_table,option_value,option_lbl,allow_overall,allow_overall_label FROM reports_config WHERE syssubmodule_idsyssubmodule=".$_SESSION['sec_submod']." LIMIT 1";
						$res_stable=mysql_query($sql_stable);
						$fet_stable=mysql_fetch_array($res_stable);
						$num_stable=mysql_num_rows($res_stable);
//						echo $sql_stable;
						if ($num_stable > 0)
							{
							//generate the menu
							$sql_menu="SELECT  ".$fet_stable['option_value']." as opvalue, ".$fet_stable['option_lbl']." as oplbl, ".$fet_stable['allow_overall']." as opoverall FROM ".$fet_stable['dropdown_source_table']." ORDER BY ".$fet_stable['option_lbl']."";
							$res_menu=mysql_query($sql_menu);
							$fet_menu=mysql_fetch_array($res_menu);
							$num_menu=mysql_num_rows($res_menu);
							//echo $sql_menu;
							*/
						?>
                        <select name="rtype">
                        <option value="">------</option>
                        <?php
				/*		if ( ($num_menu > 0) && ($fet_menu['opoverall']==1) )
							{
							echo "<option value=\"overview\">".$fet_stable['allow_overall_label']."</option>";
							}
							*/
						?>
                        <?php
					/*			do {
								echo "<option value=\"".$fet_menu['opvalue']."\" title=\"".$fet_menu['opdesc']."\">".$fet_menu['oplbl']."</option>";
								} while ($fet_menu=mysql_fetch_array($res_menu));
							
					*/		
						?>
                        
                        </select>
						<?php
					/*	} else {
						echo "---Contact Admin---";
						}
					*/
						?>
					  </td>
					</tr>
					<tr>
                        <td height="40" class="tbl_data">Starting Date: </td>
                      <td height="40" class="tbl_data">
                        <input name="timestart" type="text" id="timestart" value="<?php // if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" onClick="displayDatePicker('timestart');" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
				  </tr>
					<tr>
                        <td height="40" class="tbl_data">Ending Date: </td>
                      <td height="40" class="tbl_data">
                        <input name="timestop" type="text"  id="timestop" value="<?php // if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" onClick="displayDatePicker('timestop');" readonly="readonly"> 
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
            -->
		</td>
	</tr>
</table>
    </div>
</div>
