<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//if process
if ( (isset($_GET['mematt'])) && ($_GET['mematt']=="sign") )
	{
	//capture the user and the status request
	$sto=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_GET['switchto'])));
	$memid=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_GET['mem'])));
	$attendid=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_GET['attend'])));
	
	if ($sto=="on") //then process
		{
		//check if record already exists
		$sql_attend="SELECT * FROM  reportattendance WHERE reportactivities_idreportactivities=".$_SESSION['idreporting']." AND usrac_idusrac=".$memid." LIMIT 1";
		$res_attend=mysql_query($sql_attend);
		$num_attend=mysql_num_rows($res_attend);
		
		if ($num_attend > 0) //if it does, then just update - otherwise, insert
			{
			$sql_update="UPDATE reportattendance SET reportattendancetype_idreportattendancetype='".$attendid."',modifiedby='".$_SESSION['MVGitHub_idacname']."',modifiedon='".$timenowis."' WHERE reportactivities_idreportactivities=".$_SESSION['idreporting']." AND usrac_idusrac=".$memid." LIMIT 1";
			mysql_query($sql_update);
			//echo $sql_update;
			} else {
			$sql_insert="INSERT INTO reportattendance (reportattendancetype_idreportattendancetype,reportactivities_idreportactivities,usrac_idusrac,addedby,addedon)
			 VALUES ('".$attendid."','".$_SESSION['idreporting']."','".$memid."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."' )";
			 mysql_query($sql_insert);
			
			}

		}
	
	
	
	}
?>
<div class="msg_instructions">
<?php echo $ins_attendance;?>
</div>

<div style="padding:15px">
<table border="0" cellpadding="3" cellspacing="0" width="90%">
	<tr>
    	<td class="tbl_h2" height="30">
        <?php echo $lbl_name;?>
        </td>
        <td class="tbl_h2" height="30">
        <?php echo $lbl_username;?>
        </td>
        <td class="tbl_h2">
        <?php echo $lbl_attstatus; ?>
        </td>
    </tr>
    <?php
	//list my users
	$sql_team="SELECT idusrac,usrname,fname,lname FROM usrac WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY fname ASC";
	$res_team=mysql_query($sql_team);
	$fet_team=mysql_fetch_array($res_team);
	
	do {
	?>
    <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
    	<td class="tbl_data">
        <?php 
		echo $fet_team['fname']." ".$fet_team['lname'];
		?>
        </td>
        <td class="tbl_data">
        <?php 
		echo $fet_team['usrname'];
		?>
        </td>
        <td class="tbl_data">
        <?php
		$sql_status="SELECT idreportattendancetype,attendancetype FROM reportattendancetype";
		$res_status=mysql_query($sql_status);
		$fet_status=mysql_fetch_array($res_status);
		
		echo "<table border=\"0\"><tr>";
			do {
			//find out the status of each member
			$sql_memstate="SELECT reportattendancetype_idreportattendancetype FROM reportattendance WHERE reportactivities_idreportactivities=".$_SESSION['idreporting']." AND usrac_idusrac=".$fet_team['idusrac']." ";
			$res_memstate=mysql_query($sql_memstate);
			$fet_memstate=mysql_fetch_array($res_memstate);
			//echo $sql_memstate;
			if ($fet_status['idreportattendancetype']==$fet_memstate['reportattendancetype_idreportattendancetype'])
				{
				$btn_suf="on";
				$btn_newstate="off";
				$btn_color="";
				$font_color="#ffffff";
				} else {
				$btn_suf="off";
				$btn_newstate="on";
				$btn_color="color:#999999";
				$font_color="";
				}
			echo "<td class=\"btn_".$btn_suf."\"><a style=\"text-decoration:none;".$btn_color."\" href=\"".$_SERVER['PHP_SELF']."?mematt=sign&amp;attend=".$fet_status['idreportattendancetype']."&amp;switchto=".$btn_newstate."&amp;mem=".$fet_team['idusrac']."\">".$fet_status['attendancetype']."</td>";
			} while ($fet_status=mysql_fetch_array($res_status));
		echo "</tr></table>";
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

	} while ($fet_team=mysql_fetch_array($res_team));
	?>
</table>
</div>