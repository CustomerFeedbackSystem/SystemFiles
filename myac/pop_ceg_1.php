<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<?php
#	BuildNav for Dreamweaver MX v0.2
#              10-02-2002
#	Alessandro Crugnola [TMM]
#	sephiroth: alessandro@sephiroth.it
#	http://www.sephiroth.it
#	
#	Function for navigation build ::
function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true)
{
                GLOBAL $maxRows_rs_tsks,$totalRows_rs_tsks;
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
					if ($_get_name != "pageNum_rs_tsks") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_tsks=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_rs_tsks) + 1;
					$max_l = ($a*$maxRows_rs_tsks >= $totalRows_rs_tsks) ? $totalRows_rs_tsks : ($a*$maxRows_rs_tsks);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_tsks=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_tsks=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
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

$maxRows_rs_tsks = 20;
$pageNum_rs_tsks = 0;
if (isset($_GET['pageNum_rs_tsks'])) {
  $pageNum_rs_tsks = $_GET['pageNum_rs_tsks'];
}
$startRow_rs_tsks = $pageNum_rs_tsks * $maxRows_rs_tsks;

mysql_select_db($database_connSystem, $connSystem);

if ($_SESSION['odue'] > 9)
	{
	//loop through the region data below for the 6 or so regions if regions is set to all
	$query_rs_tsks="SELECT reports_wftasks.usrrole_idusrrole,count(*)as tkts,usrrolename,utitle,fname,lname,idusrac FROM tktin 
	INNER JOIN reports_wftasks ON tktin.idtktinPK=reports_wftasks.tktin_idtktin
	LEFT JOIN usrrole ON reports_wftasks.usrrole_idusrrole=usrrole.idusrrole
	LEFT JOIN usrac ON reports_wftasks.usrac_idusrac=usrac.idusrac
	WHERE timeclosed='0000-00-00 00:00:00' 
	AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timereported)) > ".$_SESSION['var_dl']." 
	AND tktin.tktcategory_idtktcategory=".$_SESSION['cat']."
	AND	tktin.usrteamzone_idusrteamzone=".$_SESSION['region']." 

	AND tktin.usrteamzone_idusrteamzone=".$_SESSION['region']." 
	AND ((wftskstatustypes_idwftskstatustypes=0 AND	wftskstatusglobal_idwftskstatusglobal=1)
	OR (wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2))
	GROUP BY reports_wftasks.usrrole_idusrrole";

	} else {
	
	$query_rs_tsks="SELECT reports_wftasks.usrrole_idusrrole,count(*)as tkts,usrrolename,utitle,fname,lname,idusrac FROM tktin 
	INNER JOIN reports_wftasks ON tktin.idtktinPK=reports_wftasks.tktin_idtktin
	LEFT JOIN usrrole ON reports_wftasks.usrrole_idusrrole=usrrole.idusrrole
	LEFT JOIN usrac ON reports_wftasks.usrac_idusrac=usrac.idusrac
	WHERE timeclosed='0000-00-00 00:00:00' 
	AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timereported)) <= ".$_SESSION['var_dl']."
	AND TIME_TO_SEC(TIMEDIFF('".$timenowis."',tktin.timereported)) > ".$_SESSION['var_dl_min']."
	AND tktin.tktcategory_idtktcategory=".$_SESSION['cat']."
	AND	tktin.usrteamzone_idusrteamzone=".$_SESSION['region']." 

	AND tktin.usrteamzone_idusrteamzone=".$_SESSION['region']." 
	AND ((wftskstatustypes_idwftskstatustypes=0 AND	wftskstatusglobal_idwftskstatusglobal=1)
	OR (wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2))
	GROUP BY reports_wftasks.usrrole_idusrrole";

    }
//echo $query_rs_tsks;
$query_limit_rs_tsks = sprintf("%s LIMIT %d, %d", $query_rs_tsks, $startRow_rs_tsks, $maxRows_rs_tsks);
$rs_tsks = mysql_query($query_limit_rs_tsks, $connSystem) or die(mysql_error());
$row_rs_tsks = mysql_fetch_assoc($rs_tsks);

if (isset($_GET['totalRows_rs_tsks'])) {
  $totalRows_rs_tsks = $_GET['totalRows_rs_tsks'];
} else {
  $all_rs_tsks = mysql_query($query_rs_tsks);
  $totalRows_rs_tsks = mysql_num_rows($all_rs_tsks);
}
$totalPages_rs_tsks = ceil($totalRows_rs_tsks/$maxRows_rs_tsks)-1;
?>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
	<tr>
    	<td colspan="4">
        <table border="0" cellpadding="2" cellspacing="0">
        	<tr>
            	<td class="tbl_data">
				Users <?php echo ($startRow_rs_tsks + 1) ?> to <?php echo min($startRow_rs_tsks + $maxRows_rs_tsks, $totalRows_rs_tsks) ?> of <?php echo $totalRows_rs_tsks ?> </td>
                <td class="tbl_sh"><?php 
# variable declaration
$prev_rs_tsks = "&laquo; previous";
$next_rs_tsks = "next &raquo;";
$separator = " | ";
$max_links = 10;
$pages_navigation_rs_tsks = buildNavigation($pageNum_rs_tsks,$totalPages_rs_tsks,$prev_rs_tsks,$next_rs_tsks,$separator,$max_links,true); 

print $pages_navigation_rs_tsks[0]; 
?>
                <?php print $pages_navigation_rs_tsks[1]; ?> <?php print $pages_navigation_rs_tsks[2]; ?> </td>
            </tr>
        </table>        </td>
    </tr>
	<tr>
    	<td class="tbl_sh">
        Users with the Tickets       </td>
        <td class="tbl_sh">
        No. of Tickets        </td>
        <td class="tbl_sh">
        Last Logged In        </td>
        <td class="tbl_sh">
        <a class="tooltip">[?]<span> The reminder is a Standard email message which will read<br />Dear FirstName, you have x tasks which are way overdue. Please see to it that they are done as they are just days from become a regulatory matter.</span></a> Send e-Reminder        </td>
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
       <div><?php echo $row_rs_tsks['usrrolename'];?></div>
       <div><small><?php if ($row_rs_tsks['idusrac']>0) { echo "(".$row_rs_tsks['fname'];?> <?php echo $row_rs_tsks['lname'].")"; } else { echo "<span style=\"background-color:#ff0000;color:#ffffff\">Vacant</span>"; }?></small></div>        </td>
        <td class="tbl_data" style="font-weight:bold">
        <a href="pop_ceg_1_odue.php?usrroleid=<?php echo $row_rs_tsks['usrrole_idusrrole']; ?>&amp;title=OverDue&amp;tabview=1&amp;team_member=9&keepThis=true&TB_iframe=true&height=<?php echo ($_SESSION['tb_height']-20);?>&width=<?php echo $_SESSION['tb_width'];?>&inlineId=hiddenModalContent&modal=true" id="thickBoxLink" class="thickbox">
        <?php
		echo $row_rs_tsks['tkts'];
		?> 
        </a>
        </td>
        <td class="tbl_data">
        <?php
		$res_logged=mysql_query("SELECT datediff('".$timenowis."',usrac.lastaccess) as days, lastaccess FROM usrac WHERE usrrole_idusrrole=".$row_rs_tsks['usrrole_idusrrole']."");
		$fet_logged=mysql_fetch_array($res_logged);
		if ($fet_logged['lastaccess'] > '0000-00-00 00:00:00')
			{
			echo date("D, M d, Y H:i",strtotime($fet_logged['lastaccess']));
			
			if ($fet_logged['days'] <1)
				{
				echo "<br><small> few hours ago</small>";
				} else if ($fet_logged['days']==1) {
				echo "<br><small>".$fet_logged['days']." day ago</small>";
				} else {
				echo "<br><small>".$fet_logged['days']." days ago</small>";
				}
			}
		?>        </td>
        <td class="tbl_data">
        <?php
        if ($row_rs_tsks['idusrac']>0) 
			{
		?>
        <iframe src="pop_ceg_elert.php?sendto=<?php echo $row_rs_tsks['usrrolename'];?>&amp;roleid=<?php echo $row_rs_tsks['usrrole_idusrrole'];?>&amp;" frameborder="0" height="30" width="100" marginheight="0" marginwidth="0"></iframe>
        <?php
			}
		?>        </td>
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
      <?php } while ($row_rs_tsks = mysql_fetch_assoc($rs_tsks)); ?>
</table>
<?php
mysql_free_result($rs_tsks);
?>
