<?php
//require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');

mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

//regional filters
if (isset($_GET['teamview'])) 
	{
	//clean it
	$teamview=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['teamview'])));
	$_SESSION['teamview']=$teamview;
	if ($teamview!="all")
		{
		$filter_qry_region=" AND usrrole.usrteamzone_idusrteamzone=".$teamview." " ;
		} else {
		$filter_qry_region="";
		}
		
	} else {
	$_SESSION['teamview']=$_SESSION['MVGitHub_userteamzoneid'];//my region
	$filter_qry_region="";
	}

//check if there is a request commanded by user
if (isset($_GET['newstat']))
	{

//clean it up
	$memac=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['ac'])));
	$memstat=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['newstat'])));
	
		if ($memstat=="on")
			{
			$acstate=1;
			} else if ($memstat=="off")
			{
			$acstate=0;
			} else {
			$error="on";
			}
		
//		echo "test"
	//	echo $error;
		
	if (!isset($error))
		{
		//check if they actually own this person
			//$sql_mine="SELECT idusrrole FROM usrrole WHERE joblevel>".$_SESSION['MVGitHub_joblevel']." LIMIT 1";
			$sql_mine="SELECT idusrrole FROM usrrole 
			INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
			WHERE joblevel>".$_SESSION['MVGitHub_joblevel']." 
			AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." 
			LIMIT 1";
			$res_mine=mysql_query($sql_mine);
			$num_mine=mysql_num_rows($res_mine);
			$fet_mine=mysql_fetch_array($res_mine);
//echo $sql_mine;
			if ($num_mine > 0)
				{
				//does the person have any work pending on their desk? - 
				//use a limit of 1 instead of count to speed it up
				$sql_work="SELECT idwftasks FROM wftasks WHERE (usrrole_idusrrole=".$memac." AND wftskstatustypes_idwftskstatustypes=0) OR (usrrole_idusrrole=".$memac." AND timeactiontaken='0000-00-00 00:00:00') LIMIT 1";
				$res_work=mysql_query($sql_work);
				$num_work=mysql_num_rows($res_work);
				//echo $sql_work;
					if ($num_work > 0)
						{
						$msg = $msg_undonework;
						} else {
						//else if no work, go ahead and change the status
						$sql="UPDATE usrac SET acstatus=".$acstate." WHERE usrrole_idusrrole=".$memac." LIMIT 1";
						mysql_query($sql);
						$msg = $msg_changes_saved;
						}
				} //mine >0
			}//error !isset
	}
	
if ( ($_SESSION['MVGitHub_joblevel']==0) || ($_SESSION['MVGitHub_joblevel']==1) )//if md, no limit
	{
	$filter_qry="";
	} else if ($_SESSION['MVGitHub_joblevel']==3){ //if regional manager or  / rm, limit by region
	$filter_qry=" AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." ";
	} else if ( ($_SESSION['MVGitHub_joblevel']==4) || ($_SESSION['MVGitHub_joblevel']==5) || ($_SESSION['MVGitHub_joblevel']==6)) { //if coordinator,officer limit by department
	$filter_qry=" AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND usrrole.usrdpts_idusrdpts=".$_SESSION['MVGitHub_usrdpts']." ";
	} else {
	$filter_qry="";
	}
	
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_connSystem, $connSystem);
$query_rs_myteam = "SELECT idjoblvl,joblvl_lbl,idusrrole,usrrolename,usrac.utitle,usrac.lname,usrac.fname FROM usrrole 
LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
INNER JOIN usrjoblvl ON usrrole.joblevel = usrjoblvl.idjoblvl 
WHERE (usrrole.joblevel > ".$_SESSION['MVGitHub_joblevel']." AND usrrole.joblevel > 0) 
".$filter_qry."  ".$filter_qry_region."
AND usrac.usrteam_idusrteam='".$_SESSION['MVGitHub_idacteam']."'
ORDER BY idjoblvl ASC, joblvl_lbl ASC  ";
//echo $query_rs_myteam;
//exit;
$rs_myteam = mysql_query($query_rs_myteam, $connSystem) or die(mysql_error());
$row_rs_myteam = mysql_fetch_assoc($rs_myteam);
$totalRows_rs_myteam = mysql_num_rows($rs_myteam);
$lastTFM_nest = "";
?>
<div style="padding:0px 5px 80px 0px">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
        <tr>
        	<td style="padding:15px 0px 0px 0px">
            <div>
            <?php if (isset($msg)) { ?>
            <script language="javascript">
			alert ('<?php echo $msg;?>');
			</script>
            <?php } ?>
            </div>
            <div>
            <?php
//anyone below the supervisor level cannot have this
if ($_SESSION['MVGitHub_joblevel'] > 6 )
	{
	echo "<div class=msg_warning>--- There seems to be no teams configured to report to you ---</div>";
	} else {
?>
<table cellpadding="2" cellspacing="0" width="850">
	<tr>
    	<td colspan="6" style="padding:5px" class="text_small">
        <?php
		if ((isset($is_perm_global)) && ($is_perm_global==1) )
			{
			//get all the regions on a loop to be used on the query above
			$sql_regions="SELECT idusrteamzone,userteamzonename FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
			$res_regions=mysql_query($sql_regions);
			$fet_regions=mysql_fetch_array($res_regions);
			
			if (mysql_num_rows($res_regions) > 0)
				{
			?>
            <span>
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?teamview=all" class="tab<?php if ($_SESSION['teamview']=="all") { echo "_on"; }?>">All Regions</a>
        </span>
			<?php
				do {
		?>
        <span >
        <a class="tab<?php if ($_SESSION['teamview']==$fet_regions['idusrteamzone']) { echo "_on"; }?>" href="<?php echo $_SERVER['PHP_SELF'];?>?teamview=<?php echo $fet_regions['idusrteamzone'];?>"><?php echo str_replace('Region','',$fet_regions['userteamzonename']);?></a>
        </span>       	
        <?php 
				} while ($fet_regions=mysql_fetch_array($res_regions));
			}
		} else { ?>
        <span style="padding:0px 10px">
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?teamview=<?php echo $_SESSION['MVGitHub_userteamzoneid'];?>"><?php echo $_SESSION['MVGitHub_userteamzone'];?></a>
        </span>
        <?php
		}
		?>
        
        </td>
    </tr>
<?php do { ?>
<?php
$TFM_nest = $row_rs_myteam['joblvl_lbl'];
if ($lastTFM_nest != $TFM_nest) { 
$lastTFM_nest = $TFM_nest; 
?>
	<tr>
    	<td colspan="6" style="padding:5px" class="text_body">
        
        </td>
    </tr>
	<tr>
		<td colspan="6" class="table_header">
     	<?php echo $row_rs_myteam['joblvl_lbl']; ?>
		</td>
	</tr>
    <tr>
         <td class="tbl_h2" width="150">
         <?php echo $lbl_name;?>
         </td>
         <td class="tbl_h2" width="200">
         <?php echo $lbl_role;?>
      </td>
         <td class="tbl_h2" width="150">
         <?php echo $lbl_new;?>
      </td>
         <td class="tbl_h2" width="100">
         <?php echo $lbl_overdue;?>
      </td>
         <td class="tbl_h2" width="100">
         <?php echo $lbl_inprogress;?>
      </td>
         <td class="tbl_h2" width="50">
         <?php echo $lbl_status;?>
      </td>
  </tr>
	<?php } //End of Basic-UltraDev Simulated Nested Repeat?>
      
      <?php
				 //echo $timenowis."<br>";
				 $sql_myteam = "SELECT usrac.idusrac,usrrole.idusrrole,usrrole.usrrolename,usrac.utitle,usrac.lname,usrac.fname,usrac.acstatus,
				 (SELECT count(*) FROM wftasks WHERE usrrole.idusrrole=wftasks.usrrole_idusrrole AND wftskstatustypes_idwftskstatustypes=0 AND wftasks.usrrole_idusrrole=".$row_rs_myteam['idusrrole'].") as newtsks,
				 (SELECT count(*) FROM wftasks WHERE usrrole.idusrrole=wftasks.usrrole_idusrrole AND wftskstatustypes_idwftskstatustypes=0 AND timeactiontaken='0000-00-00 00:00:00' AND timedeadline<'".$timenowis."' ) as overduetsks,
				 (SELECT count(*) FROM wftasks WHERE usrrole.idusrrole=wftasks.usrrole_idusrrole AND wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2 ) as inprogress
				 FROM usrrole INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole WHERE usrrole.idusrrole=".$row_rs_myteam['idusrrole']." GROUP BY usrrole.idusrrole ";
				 $res_myteam = mysql_query($sql_myteam);
				 $num_myteam = mysql_num_rows($res_myteam);
				 $fet_myteam = mysql_fetch_array($res_myteam);
				 //echo "<font >".$sql_myteam."</font>";
				// if ($num_myteam > 0 )
				// 	{
					do {
				 ?>
	<tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
    	<td class="tbl_data" width="150" >
        <?php 
		echo "<a name=\"".$fet_myteam['idusrac']."\"></a>";
		if (strlen($row_rs_myteam['lname']) < 1)
			{
			echo "<span style=\"color:#ff0000\">Not Occupied</span>";
			}
		?>
         <?php echo $row_rs_myteam['utitle'];?> <?php echo $row_rs_myteam['fname'];?> <?php echo $row_rs_myteam['lname'];?>
        </td>
        <td class="tbl_data" width="200" >
        <?php echo $row_rs_myteam['usrrolename'];?> 
      </td>
        <td class="tbl_data" width="150">
        <?php if ($fet_myteam['newtsks']>0)
			{ 
							echo "<a style=\"color:#ffffff;background-color:#225C97;padding:2px;font-weight:bold\" href=\"pop_myteam_tasks.php?tabview=1&amp;team_member=".$row_rs_myteam['idusrrole']."&keepThis=true&TB_iframe=true&height=".$_SESSION['tb_height']."&width=".$_SESSION['tb_width']."&inlineId=hiddenModalContent&modal=true\" class=\"thickbox\" >".$fet_myteam['newtsks']."</a>";
							} else {
							echo $fet_myteam['newtsks'];
							}
							?> 
      </td>
        <td class="tbl_data" width="100">
        <?php 
						if ($fet_myteam['overduetsks'] > 0)
							{
							echo "<a  style=\"color:#ffffff;background-color:#ff0000;padding:2px;font-weight:bold\"  href=\"pop_myteam_tasks.php?tabview=2&amp;team_member=".$row_rs_myteam['idusrrole']."&keepThis=true&TB_iframe=true&height=".$_SESSION['tb_height']."&width=".$_SESSION['tb_width']."&inlineId=hiddenModalContent&modal=true\" class=\"thickbox\" >".$fet_myteam['overduetsks']."</a>";
							} else {
							echo $fet_myteam['overduetsks'];
							}
							?> 
      </td>
       <td class="tbl_data" width="100">
        <?php 
						if ($fet_myteam['inprogress'] > 0)
							{
							echo "<a  style=\"color:#ffffff;background-color:#ff9900;padding:2px;font-weight:bold\"  href=\"pop_myteam_tasks.php?tabview=3&amp;team_member=".$row_rs_myteam['idusrrole']."&keepThis=true&TB_iframe=true&height=".$_SESSION['tb_height']."&width=".$_SESSION['tb_width']."&inlineId=hiddenModalContent&modal=true\" class=\"thickbox\" >".$fet_myteam['inprogress']."</a>";
							} else {
							echo $fet_myteam['inprogress'];
							}
							?> 
      </td>
        <td class="tbl_data" width="50"> 
                      <?php
					 if (strlen($row_rs_myteam['lname']) > 0)
							{
					 
							//echo $fet_myteam['acstatus'];
							if ($fet_myteam['acstatus']==1)
								{
							?>
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?ac=<?php echo $row_rs_myteam['idusrrole'];?>&amp;newstat=off#<?php echo $fet_myteam['idusrac'];?>" id="button_duty_on" title="Turn Account OFF"></a>
							<?php
								} else if ($fet_myteam['acstatus']==0) { 
							?>
							<a href="<?php echo $_SERVER['PHP_SELF'];?>?ac=<?php echo $row_rs_myteam['idusrrole'];?>&amp;newstat=on#<?php echo $fet_myteam['idusrac'];?>" id="button_duty_off" title="Turn Account ON"></a>
							<?php
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

} while ($fet_myteam = mysql_fetch_array($res_myteam));
?>
<?php } while ($row_rs_myteam = mysql_fetch_assoc($rs_myteam)); ?>               
</table>
<?php
}
?>	
            </div>
            </td>
        </tr>
</table>        
<?php
mysql_free_result($rs_myteam);
?>        
</div>