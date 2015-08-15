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
			<form method="get" action="generate_report.php" target="_blank" name="reports" >
				<table border="0" cellpadding="2" cellspacing="0">
			  <tr>
						<td colspan="2" class="bg_section">
					
                            <?php
                            if (isset($fet_heading['submodule']))
                            {
                            echo$fet_heading['submodule'];
                            }
                            ?>
                            <input type="hidden" value="<?php echo $fet_heading['submodule']; ?>" name="pagetitle">
                            <input type="hidden" value="<?php echo $_SESSION['sec_submod'];?>" name="reportid">
                           
						</td>
					</tr>
					<tr>
						<td height="40" class="tbl_data">Specify : </td>
					  <td height="40" class="tbl_data">
                        <select name="reportid">
                        <option value="">---</option>
                         <option value="-1">- Overall Ticket Volume -</option>
                        <?php
						$sql="SELECT idtktcategory,tktcategoryname FROM tktcategory WHERE tktcategory_idtktcategory=0";
						$res=mysql_query($sql);
						$fet=mysql_fetch_array($res);
						do {
						echo "<option value=\"".$fet['idtktcategory']."\">".$fet['tktcategoryname']."</option>";
						} while ($fet=mysql_fetch_array($res));
						?>
                        </select>
						</td>
					</tr>
					<tr>
                        <td height="40" class="tbl_data">Starting Date : </td>
                      <td height="40" class="tbl_data">
                        <input name="timestart" type="text" id="timestart" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" readonly="readonly" onClick="displayDatePicker('timestart');"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">
                        </td>
				  </tr>
					<tr>
                        <td height="40" class="tbl_data">Ending Date : </td>
                      <td height="40" class="tbl_data">
                        <input name="timestop" type="text"  id="timestop" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" onClick="displayDatePicker('timestop');" readonly="readonly"> 
                        <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
                        </td>
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
<!--
    </div>
    <div class="tbl_h">
    For Demonstration Purposes Only - Data Not Real
    </div>
    <div class="text_body">
    &raquo; <a href="demo_bar2.php" target="_blank">Demo - Comparative Analysis of Staff Work Load </a>
    </div>
     <div class="text_body">
    &raquo; <a href="demo_line.php" target="_blank">Demo - Trend / Indicator Comparative </a>
    </div>
-->    
</div>
