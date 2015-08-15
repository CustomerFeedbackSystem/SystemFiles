<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['usrroleid']))
	{
	$_SESSION['ceg_idusrrole']=mysql_escape_string(trim($_GET['usrroleid']));
	}

#	BuildNav for Dreamweaver MX v0.2
#              10-02-2002
#	Alessandro Crugnola [TMM]
#	sephiroth: alessandro@sephiroth.it
#	http://www.sephiroth.it
#	
#	Function for navigation build ::
function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true)
{
                GLOBAL $maxRows_rs_list,$totalRows_rs_list;
	$pagesArray = ""; $firstArray = ""; $lastArray = "";
	if($max_links<2)$max_links=2;
	if($pageNum_Recordset1<=$totalPages_Recordset1 && $pageNum_Recordset1>=0)
	{
		if ($pageNum_Recordset1 > ceil($max_links/2))
		{
			$fgp = $pageNum_Recordset1 - ceil($max_links/2) > 0 ? $pageNum_Recordset1 - ceil($max_links/2) : 1;
			$egp = $pageNum_Recordset1 + ceil($max_links/2);
			if ($egp >= $totalPages_Recordset1)
			{
				$egp = $totalPages_Recordset1+1;
				$fgp = $totalPages_Recordset1 - ($max_links-1) > 0 ? $totalPages_Recordset1  - ($max_links-1) : 1;
			}
		}
		else {
			$fgp = 0;
			$egp = $totalPages_Recordset1 >= $max_links ? $max_links : $totalPages_Recordset1+1;
		}
		if($totalPages_Recordset1 >= 1) {
			#	------------------------
			#	Searching for $_GET vars
			#	------------------------
			$_get_vars = '';			
			if(!empty($_GET) || !empty($HTTP_GET_VARS)){
				$_GET = empty($_GET) ? $HTTP_GET_VARS : $_GET;
				foreach ($_GET as $_get_name => $_get_value) {
					if ($_get_name != "pageNum_rs_list") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_list=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_rs_list) + 1;
					$max_l = ($a*$maxRows_rs_list >= $totalRows_rs_list) ? $totalRows_rs_list : ($a*$maxRows_rs_list);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_list=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_list=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
}
?>
<?php
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

$maxRows_rs_list = 20;
$pageNum_rs_list = 0;
if (isset($_GET['pageNum_rs_list'])) {
  $pageNum_rs_list = $_GET['pageNum_rs_list'];
}
$startRow_rs_list = $pageNum_rs_list * $maxRows_rs_list;

if ($_SESSION['odue']>=10)
	{
	$var_oduefilter=" AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timereported)) > ".$_SESSION['var_dl']."";
	} else {
	$var_oduefilter=" AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timereported)) <= ".$_SESSION['var_dl']."
	AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timereported)) > ".$_SESSION['var_dl_min']." ";
	}
mysql_select_db($database_connSystem, $connSystem);

$query_rs_list = "SELECT idwftasks,wftasks.tktin_idtktin,wftasks.tasksubject,wftasks.timeinactual,wftasks.timedeadline,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.timeactiontaken,usrrole.usrrolename as sender_role,sender_idusrrole,TIMESTAMPDIFF(MINUTE, NOW(), wftasks.timedeadline) AS time_to_deadline ,usrac.utitle,usrac.lname,tktin.refnumber,tktin.senderphone,tktin.tktcategory_idtktcategory  FROM tktin 
INNER JOIN wftasks ON tktin.idtktinPK=wftasks.tktin_idtktin
LEFT JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
LEFT JOIN usrac ON wftasks.usrac_idusrac=usrac.idusrac
WHERE timeclosed='0000-00-00 00:00:00' 
".$var_oduefilter." 
AND tktin.tktcategory_idtktcategory=".$_SESSION['cat']."
AND tktin.usrteamzone_idusrteamzone=".$_SESSION['region']."
 
AND (
(wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1)
OR 
(wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2)
)
AND wftasks.usrrole_idusrrole=".$_SESSION['ceg_idusrrole']."";

$query_limit_rs_list = sprintf("%s LIMIT %d, %d", $query_rs_list, $startRow_rs_list, $maxRows_rs_list);
$rs_list = mysql_query($query_limit_rs_list, $connSystem) or die(mysql_error());
$row_rs_list = mysql_fetch_assoc($rs_list);
//echo "<br><br>".$query_rs_list;
if (isset($_GET['totalRows_rs_list'])) {
  $totalRows_rs_list = $_GET['totalRows_rs_list'];
} else {
  $all_rs_list = mysql_query($query_rs_list);
  $totalRows_rs_list = mysql_num_rows($all_rs_list);
}
$totalPages_rs_list = ceil($totalRows_rs_list/$maxRows_rs_list)-1;

//process the transference
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="transfer"))
	{
	//array checkbox
	if ( (isset($_POST['seltask'])) && ($_POST['seltask']!="")) 
		{
		$count=count($_POST['seltask']);
		//echo "<br><br><br><br>";
		
		if ($count > 0) //if a record has been chosen
			{
				$seltask="";
				for ($i=0; $i<$count; $i++) //then pick and store the variables
					{
					$seltask.=$_POST['seltask'][$i].",";
					}
				//then redirect the user to the pop_myteam_tasks_transfer.php page :)
				$_SESSION['tasks']=substr($seltask,0,-1); //the values
				$_SESSION['tasks_no']=$count;
				
				//create a new batch
				$sql_newbatch="INSERT INTO wftasks_transfers_batch (createdby_iduser,createdon) 
				VALUES ('".$_SESSION['WASReBSyS_idacname']."','".$timenowis."')";
				mysql_query($sql_newbatch);
				
				//get that value frmo the db
				$sql_batch="SELECT idtransferbatch FROM wftasks_transfers_batch WHERE createdby_iduser=".$_SESSION['WASReBSyS_idacname']." ORDER BY idtransferbatch DESC LIMIT 1";
				$res_batch=mysql_query($sql_batch);
				$fet_batch=mysql_fetch_array($res_batch);
				
				$_SESSION['batch']=$fet_batch['idtransferbatch'];
				?>
				<script language="javascript">
				window.location='pop_myteam_tasks.php?tabview=4';
				</script>                
                <?php
				exit;
				
			} else {
			$error="<div class=\"msg_warning\">Please select the Tasks you wish to transfer...</div>";
			}
		} else {
		$error="<div class=\"msg_warning\">Please select the Tasks you wish to transfer...</div>";
		}
	
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>

<!--<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>-->
<script type="text/javascript" src="../scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-timepicker-addon_.js"></script>

<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../thickbox/thickbox_be.js"></script>
<script type="text/javascript" src="../scripts/jquery.autocomplete.js"></script>

<title>Overdue Team Tasks</title>
</head>
<body>
<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px; width:<?php echo $_SESSION['tb_width'];?>">
<table border="0" cellpadding="0" cellspacing="0" width="<?php echo $_SESSION['tb_width'];?>">
	<tr>
    	<td width="100%" >
		<div>
        All Overdue Tasks
		</div>
		</td>
		<td align="right">
		<a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
		</td>
	</tr>
</table>
</div>
<div>
  	<div>
    <?php
	if (isset($error)) { echo $error; }
	?>
    </div>

	<div style="padding:0px 0px 10px 0px">
    <?php
//	echo  "<span style=\"color:#ffffff\">".$query_rs_list."</span>";
	if ($totalRows_rs_list > 0)
		{
	?>
<form name="form" action="" method="post" onSubmit="return checkbox_tasks()">    
<table border="0" cellpadding="2" cellspacing="0" width="100%">
    </tr>
	<tr>
    	<td colspan="5">
        	<table border="0" cellpadding="0" cellspacing="0">
            	<tr>
					<td class="text_small"> Tasks <?php echo ($startRow_rs_list + 1) ?> to <?php echo min($startRow_rs_list + $maxRows_rs_list, $totalRows_rs_list) ?> of <?php echo $totalRows_rs_list ?> </td>
					<td class="text_body"><?php 
# variable declaration
$prev_rs_list = "« previous";
$next_rs_list = "next »";
$separator = " | ";
$max_links = 10;
$pages_navigation_rs_list = buildNavigation($pageNum_rs_list,$totalPages_rs_list,$prev_rs_list,$next_rs_list,$separator,$max_links,true); 

print $pages_navigation_rs_list[0]; 
?>
				    <?php print $pages_navigation_rs_list[1]; ?> <?php print $pages_navigation_rs_list[2]; ?> </td>
			  </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td colspan="4">&nbsp;</td>
    </tr>
	<tr>
		<td class="tbl_h2">
	  <table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">
					<?php echo $lbl_from;?>
                    </td>
                    <td width="12px">&nbsp;</td>
                    <td width="12px">&nbsp;</td>
			  </tr>
			</table>
		</td>
		<td class="tbl_h2"><?php echo $lbl_subject;?></td>
		<td class="tbl_h2">
	<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">
					<?php echo $lbl_date;?>
                    </td>
                    <td width="12px">&nbsp;</td>
                  <td width="12px">&nbsp;</td>
			</tr>
			</table>
		</td>
       <!-- <td width="15%" class="tbl_h2">
	<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">
					<?php //echo $lbl_deadline_task;?>
                    </td>
                    <td width="12px">
                    <a href="index.php?mod=2&submod=0&ua=view&view=2&uction=view_mod&pageNum_rs_list=<?php echo $_SESSION['pageNum_rs_list'];?>&orderlist=ascending&orderwhat=deadline" id="button_asc"></a>
                    </td>
                    <td width="12px">
                    <a href="index.php?mod=2&submod=0&ua=view&view=2&uction=view_mod&pageNum_rs_list=<?php echo $_SESSION['pageNum_rs_list'];?>&orderlist=descending&orderwhat=deadline" id="button_desc"></a>
                    </td>
				</tr>
			</table>
		</td>
        -->
        <td class="tbl_h2">
	<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tbl_h2">
					Task <?php echo $lbl_timeremain;?>
                    </td>
                    <td width="12px">&nbsp;</td>
                  <td width="12px">&nbsp;</td>
			</tr>
			</table></td>
	</tr>  
    <?php do { ?>
      <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
		<td class="tbl_data" <?php if ($row_rs_list['wftskstatustypes_idwftskstatustypes']==0) { echo "style=\"font-weight:bold\""; } ?>><?php 
		if ($row_rs_list['sender_idusrrole']==2) 
		{ 
		echo $row_rs_list['senderphone']; 
		} else { 
		echo $row_rs_list['sender_role']."&nbsp;<small>(".$row_rs_list['utitle']." ".$row_rs_list['lname'].")</small>"; 
		} 
		?>        </td>
		<td class="tbl_data" <?php if ($row_rs_list['wftskstatustypes_idwftskstatustypes']==0) { echo "style=\"font-weight:bold\""; } ?>>
        <a href="go_to_taskhistory.php?tkt=<?php echo $row_rs_list['tktin_idtktin']; ?>&amp;tktcat=<?php echo $row_rs_list['tktcategory_idtktcategory']; ?>&amp;title=<?php echo $row_rs_list['refnumber']; ?>&amp;tabview=1&amp;team_member=9&keepThis=true&TB_iframe=true&height=<?php echo ($_SESSION['tb_height']-50);?>&width=<?php echo ($_SESSION['tb_width']-10);?>&inlineId=hiddenModalContent&modal=true" id="thickBoxLink" class="thickbox" >
		<?php 
		$subject=$row_rs_list['tasksubject'];
		if (strlen($subject)>40)
			{
			echo $subject."...";
			} else {
			echo $subject;
			}
		 ?>
         </a>
		</td>
		<td class="tbl_data" <?php if ($row_rs_list['wftskstatustypes_idwftskstatustypes']==0) { echo "style=\"font-weight:bold\""; } ?>>
		<?php echo date("D, M d, Y H:i",strtotime($row_rs_list['timeinactual'])); ?>
		</td>
        <!--
		<td class="tbl_data" <?php if ($row_rs_list['wftskstatustypes_idwftskstatustypes']==0) { echo "style=\"font-weight:bold\""; } ?>>
        <?php
        echo date("D, M d, Y H:i",strtotime($row_rs_list['timedeadline'])); 
		?>
		</td>
        -->
        <td class="tbl_data" <?php if ($row_rs_list['wftskstatustypes_idwftskstatustypes']==0) { echo "style=\"font-weight:bold\""; } ?>>
        
        <?php
		//deciding the color to use
		if ($row_rs_list['timedeadline'] > $timenowis)
			{
			$colorclass="txt_green";
			} else {
			$colorclass="txt_red";
			}
		
			//if time exceeded the deadline, then just use
			$minutes=abs($row_rs_list['time_to_deadline']);
			$d = floor ($minutes / 1440);
			$h = floor (($minutes - $d * 1440) / 60);
			$m = $minutes - ($d * 1440) - ($h * 60);

			echo "<span class=\"".$colorclass."\">{$d}d {$h}hr {$m}m</span>";
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
?>    
<?php } while ($row_rs_list = mysql_fetch_assoc($rs_list)); ?>
     <tr>
    	<td colspan="5">
			<table border="0" cellpadding="0" cellspacing="0">
            	<tr>
					<td class="text_small"> Tasks <?php echo ($startRow_rs_list + 1) ?> to <?php echo min($startRow_rs_list + $maxRows_rs_list, $totalRows_rs_list) ?> of <?php echo $totalRows_rs_list ?> </td>
					<td class="text_body">---</td>
			  </tr>
            </table>
        </td>
    </tr>
</table>
</form>
<?php } else { //else if no record ?>
	<div style="text-align:center; margin:45px 15px;" class="msg_warning">
    <?php echo $msg_no_record; ?>
    </div>
<?php } ?>
  </div>
</div>
</body>
<?php
mysql_free_result($rs_list);
?>
