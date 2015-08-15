<?php
require_once('../required/check_user_lite.php');
?>
<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../Connections/connSystem.php');

mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

$sql_heading = "SELECT modulename,submodule FROM sysmodule INNER JOIN syssubmodule ON sysmodule.idsysmodule=syssubmodule.sysmodule_idsysmodule WHERE idsyssubmodule=".$_SESSION['sec_submod']." LIMIT 1 ";
$res_heading = mysql_query($sql_heading);
$fet_heading = mysql_fetch_array($res_heading);
//echo $query_rs_list;
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    
    <div>
    <table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
    	<tr>
        	<td class="tbl_h"><?php echo $lbl_tktcat;?></td>
            <td class="tbl_h"><?php echo $lbl_wf;?></td>
            <td class="tbl_h"><?php echo $lbl_lastedit;?></td>
      </tr>
        <?php
		$sql_tktcats = "SELECT idtktcategory,tktcategory_idtktcategory,tktcategoryname FROM tktcategory WHERE tktcategory_idtktcategory=0 ORDER BY idtktcategory ASC";
		$res_tktcats = mysql_query($sql_tktcats);
		$num_tktcats = mysql_num_rows($res_tktcats);
		$fet_tktcats = mysql_fetch_array($res_tktcats);
		//echo $sql_tktcats;
		$i=1;
		if ($num_tktcats >0 )
			{
			do {
		?>
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td class="tbl_data">
			<?php echo $i.".&nbsp;".$fet_tktcats['tktcategoryname'];?>
            </td>
            <?php
			//check if there is a workflow for this category with this userzone
			$sql_wf = "SELECT idwfproc,wfprocname,wfproc.createdon,wfproc.modifiedon FROM wfproc INNER JOIN link_tskcategory_wfproc ON wfproc.idwfproc=link_tskcategory_wfproc.wfproc_idwfproc WHERE link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_tskcategory_wfproc.tktcategory_idtktcategory=".$fet_tktcats['idtktcategory']."";
			$res_wf = mysql_query($sql_wf);
			$fet_wf = mysql_fetch_array($res_wf);
			$num_wf = mysql_num_rows($res_wf);
			//echo $sql_wf;
			?>
            <td class="tbl_data">
				<?php if ($num_wf < 1) //if no record found
						{
						echo "<span style=\"color:#ff0000;font-weight:bold\">NO</span>&nbsp;&nbsp;";
						echo "<a href=\"#\" onclick=\"tb_open_new('pop_wftocat_properties.php?cat=".$fet_tktcats['idtktcategory']."&amp;keepThis=true&amp;TB_iframe=true&amp;height=410&amp;width=780&amp;modal=true')\" >[Assign]</a>";
						} else {
						echo "<a href=\"#\" onclick=\"tb_open_new('pop_wftocat_properties.php?cat=".$fet_tktcats['idtktcategory']."&amp;keepThis=true&amp;TB_iframe=true&amp;height=410&amp;width=780&amp;modal=true')\" >";
						echo $fet_wf['wfprocname'];
						echo "</a>";
						}
						
				?>
            </td>
            <td class="tbl_data" valign="top">
            <?php
				if ($num_wf < 1)
					{
					echo "N/A";
					} else { //if there is a record, then
					 if ($fet_wf['modifiedon']!='0000-00-00 00:00:00') // if there was a modified, then
					 	{
						echo date("D, M d, Y H:i",strtotime($fet_wf['modifiedon'])); 
						} else {
						echo date("D, M d, Y H:i",strtotime($fet_wf['createdon'])); 
						}
					}
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

			$i++; //numbering
			
			// do a loop for the subcategories if they exist
		$sql_tktsubcats = "SELECT idtktcategory,tktcategory_idtktcategory,tktcategoryname FROM tktcategory WHERE tktcategory_idtktcategory=".$fet_tktcats['idtktcategory']." ORDER BY idtktcategory ASC";
		$res_tktsubcats = mysql_query($sql_tktsubcats);
		$num_tktsubcats = mysql_num_rows($res_tktsubcats);
		$fet_tktsubcats = mysql_fetch_array($res_tktsubcats);
		
					
		} while ($fet_tktcats = mysql_fetch_array($res_tktcats));
		} else {
		?>
        <tr>
        	<td colspan="3" height="50">
            <span class="msg_warning"><?php echo $msg_noprofiles;?></span></td>
        </tr>
        <?php
		}
		?>
    </table>
  </div>
</div>
<?php
mysql_free_result($res_heading);
mysql_free_result($res_tktcats);
?>