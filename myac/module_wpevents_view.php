<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<div>
	<div class="bg_section">
	<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
	</div>
    <div style="margin:10px 0px 0px 0px">
    <?php
	//get the data
$sql_activities="SELECT idreportactivities,nstatus,yearfrom,event_venue,usrname,reportactivities.enteredon,wpquarter,activitytype,event_startdate,event_starttime  FROM reportactivities
INNER JOIN notestatus ON reportactivities.notestatus_idnotestatus=notestatus.idnotestatus
INNER JOIN usrac ON reportactivities.enteredby=usrac.idusrac
INNER JOIN wpquarters ON reportactivities.wpquarters_idwpquarters=wpquarters.idwpquarters
INNER JOIN tktactivitytype ON reportactivities.tktactivitytype_idtktactivitytype =tktactivitytype.idtktactivitytype 
WHERE reportactivities.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND notestatus.tbl_name='reportactivities' ORDER BY reportactivities.enteredon DESC";
$res_activities=mysql_query($sql_activities); 
$fet_activities=mysql_fetch_array($res_activities);
$num_activities=mysql_num_rows($res_activities);
//echo $sql_activities;
if ($num_activities > 0 )
	{
	?>
    <table border="0" width="100%" cellpadding="2" cellspacing="0">
    	<tr>
        	<td height="30" class="tbl_h2">
            <?php
			echo $lbl_year;
			?>            </td>
            <td height="30" class="tbl_h2">
            <?php
			echo $lbl_quarter;
			?>            </td>
            <td height="30" class="tbl_h2">
            <?php 
			echo $lbl_activity;
			?>
            </td>
              <td height="30" class="tbl_h2">
            <?php 
			echo $lbl_date;
			?>
          </td>
              <td height="30" class="tbl_h2">
            <?php 
			echo $lbl_status;
			?>
            </td>
 			<td class="tbl_h2"><?php 
			echo $lbl_reportby;
			?>            </td>
      </tr>
            <?php
	do {
	?>
    	<tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td height="30" class="tbl_data">
            <?php
			echo $fet_activities['yearfrom']."&nbsp;/&nbsp;".($fet_activities['yearfrom'] + 1);
			?>            </td>
            <td height="30" class="tbl_data">
            <?php
			echo $fet_activities['wpquarter'];
			?>            </td>
            <td height="30" class="tbl_data">
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;submod=46&amp;uction=edit_submod&amp;idreport=<?php echo $fet_activities['idreportactivities'];?>">
           <?php
			echo $fet_activities['activitytype'];
			?>
            </a>
            </td>
             <td height="30" class="tbl_data">
           <?php
			echo date("D, M d, Y ",strtotime($fet_activities['event_startdate']))."&nbsp;".substr($fet_activities['event_starttime'],0,5);
			?>
            </td>
            <td height="30" class="tbl_data"><?php
			echo $fet_activities['nstatus'];
			?></td>
           <td class="tbl_data"><?php
			echo $fet_activities['usrname'];
			?></td>
      </tr>
        <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end


	} while ($fet_activities=mysql_fetch_array($res_activities));
?> 
    </table>
    <?php 
	} else { 
	echo "<div style=\"margin:30px\" class=\"msg_warning\">".$msg_no_record."</div>";
	}
	?>
    </div>
</div>    