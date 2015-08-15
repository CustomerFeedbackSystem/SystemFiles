<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<div>
	<div class="bg_section">
	<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
	</div>
    <div style="margin:10px 0px 0px 0px">
<?php
$sql_wps="SELECT idwpheader,yearfrom,yearto,utitle,fname,lname,nstatus FROM wpheader
INNER JOIN notestatus ON wpheader.notestatus_idnotestatus=notestatus.idnotestatus
INNER JOIN usrac ON wpheader.enteredby=usrac.idusrac
WHERE wpheader.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND notestatus.tbl_name='wpheader' ORDER BY yearfrom DESC ";
$res_wps=mysql_query($sql_wps);
$num_wps=mysql_num_rows($res_wps);
$fet_wps=mysql_fetch_array($res_wps);
//echo $sql_wps;
if ($num_wps > 0)
 {
?>
<table border="0" cellpadding="5" cellspacing="0" width="80%">

	<tr>
    	<td height="30" class="tbl_h2">
        <?php echo $lbl_year;?>        </td>

        <td height="30" class="tbl_h2">
        <?php echo $lbl_createdby;?>        </td>
         <td height="30" class="tbl_h2">
        <?php echo $lbl_status;?>        </td>
    </tr>
    <?php
	
		do {
	?>
    <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
    	<td class="tbl_data">
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;wpid=<?php echo $fet_wps['idwpheader'];?>">
		<?php echo $fet_wps['yearfrom']."-".$fet_wps['yearto'];?>
        </a>
        </td>
        <td class="tbl_data">
         <?php echo $fet_wps['utitle']." ".$fet_wps['fname']." ".$fet_wps['lname'];?>
        </td>
        <td class="tbl_data">
       <?php echo $fet_wps['nstatus'];?>
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
		} while ($fet_wps=mysql_fetch_array($res_wps));
	?>
	
</table>
<?php } else { ?>
<div style="text-align:center; margin:45px 15px;" class="msg_warning">
    <?php echo $msg_no_record; ?>
    </div>
<?php } ?>
	</div>
</div>    
