<?php
require_once('../assets_backend/be_includes/config.php');

require_once('../Connections/connSystem.php');

if (isset($_GET['parentviewtab']))
	{
	$_SESSION['parenttabview']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['parentviewtab'])));
	}
mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');
/*mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');*/

//CHECK IF I HAVE BEEN DELEGATED TASKS
$sql_delegated="SELECT usrrolename,utitle,fname,lname,wftasksdeleg_meta.idusrrole_from
FROM wftasksdeleg_meta 
INNER JOIN usrrole ON wftasksdeleg_meta.idusrrole_from=usrrole.idusrrole
INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
WHERE wftasksdeleg_meta.idusrrole_to=".$_SESSION['MVGitHub_iduserrole']."
AND wftasksdeleg_meta.deleg_status=1";
$res_delegated=mysql_query($sql_delegated);
$num_delegated=mysql_num_rows($res_delegated);
$fet_delegated=mysql_fetch_array($res_delegated);


if ( $num_delegated > 0 )
	{
	$qry_recepient = " (wftasks.usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." OR wftasks.usrrole_idusrrole=".$fet_delegated['idusrrole_from']." ".$_SESSION['idwfgroup']." ) ";
	$delegated_to_me = 1;
	} else {
	$qry_recepient = " wftasks.usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." ".$_SESSION['idwfgroup']." ";
	$delegated_to_me = 0;
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
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"index.php?mod=2&submod=0&ua=view&view=2&uction=view_mod&pageNum_rs_list=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
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
					$pagesArray .= "<a href=\"index.php?mod=2&submod=0&ua=view&view=2&uction=view_mod&pageNum_rs_list=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"index.php?mod=2&submod=0&ua=view&view=2&uction=view_mod&pageNum_rs_list=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_escape_string") ? mysql_escape_string($theValue) : mysql_escape_string($theValue);

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
$pageNum_rs_list = $_SESSION['pageNum_rs_list'];
if (isset($_GET['pageNum_rs_list'])) {
  $pageNum_rs_list = $_GET['pageNum_rs_list'];
}
$startRow_rs_list = $pageNum_rs_list * $maxRows_rs_list;

mysql_select_db($database_connSystem, $connSystem);
$query_rs_list = "
SELECT wftasks_batch.idwftasks_batch, wftasks_batch.countbatch, wftasks_batch.batch_no_verbose, wftasks_batch.wftasks_batchtype_idwftasks_batchtype, wftskstatustypes_idwftskstatustypes, wftskstatusglobal_idwftskstatusglobal, userteamzonename, 
(
SELECT batchtypedesc
FROM wftasks_batchtype
WHERE idwftasks_batchtype = wftasks_batch.wftasks_batchtype_idwftasks_batchtype
) AS batchtypedesc 
FROM wftasks_batch
INNER JOIN tktin ON wftasks_batch.idwftasks_batch = tktin.wftasks_batch_idwftasks_batch
INNER JOIN wftasks ON tktin.idtktinPK = wftasks.tktin_idtktin
INNER JOIN usrrole ON wftasks.sender_idusrrole = usrrole.idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone = usrteamzone.idusrteamzone
WHERE  ".$qry_recepient." 
GROUP BY wftasks_batch.idwftasks_batch 
ORDER BY wftasks_batch.createdon DESC";
/*
$query_rs_list = "SELECT 
wftasks_batch.idwftasks_batch,
wftasks_batch.countbatch, 
wftasks_batch.batch_no_verbose, 
wftasks_batchtype.batchtypedesc,
wftasks_batch.wftasks_batchtype_idwftasks_batchtype,
wftskstatustypes_idwftskstatustypes,
wftskstatusglobal_idwftskstatusglobal
userteamzonename
FROM wftasks_batch
INNER JOIN wftasks_batchtype ON wftasks_batch.wftasks_batchtype_idwftasks_batchtype=wftasks_batchtype.idwftasks_batchtype
INNER JOIN wftasks ON wftasks_batch.idwftasks_batch=wftasks.wftasks_batch_idwftasks_batch
INNER JOIN usrrole ON wftasks.sender_idusrrole=usrrole.idusrrole
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
WHERE ".$qry_recepient."
AND (wftskstatustypes_idwftskstatustypes=0 AND wftskstatusglobal_idwftskstatusglobal=1)
OR  (wftskstatustypes_idwftskstatustypes=6 AND wftskstatusglobal_idwftskstatusglobal=2)
GROUP BY wftasks_batch.idwftasks_batch";
*/
$query_limit_rs_list = sprintf("%s LIMIT %d, %d", $query_rs_list, $startRow_rs_list, $maxRows_rs_list);
$rs_list = mysql_query($query_limit_rs_list, $connSystem) or die(mysql_error());
$row_rs_list = mysql_fetch_assoc($rs_list);
//echo $query_rs_list;
if (isset($_GET['totalRows_rs_list'])) {
  $totalRows_rs_list = $_GET['totalRows_rs_list'];
} else {
  $all_rs_list = mysql_query($query_rs_list);
  $totalRows_rs_list = mysql_num_rows($all_rs_list);
}
$totalPages_rs_list = ceil($totalRows_rs_list/$maxRows_rs_list)-1;

?>
<div>
    <div >
    <form action="" method="get" name="search_tasks">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="40%" class="bg_section">
            Batch View            </td>
  <td width="60%" class="bg_section" align="right">&nbsp;</td>
        </tr>
    </table>
    </form>
    </div>
	<div style="padding:0px 0px 10px 0px">
    <?php
	if ($totalRows_rs_list > 0)
		{
	?>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
    </tr>
	<tr>
    	<td colspan="6">
        	<table border="0" cellpadding="0" cellspacing="0">
            	<tr>
					<td class="text_small"> Batches <?php echo ($startRow_rs_list + 1) ?> to <?php echo min($startRow_rs_list + $maxRows_rs_list, $totalRows_rs_list) ?> of <?php echo $totalRows_rs_list ?> </td>
					<td class="text_body"><?php 
# variable declaration
$prev_rs_list = "<< previous";
$next_rs_list = "next >>";
$separator = " | ";
$max_links = 10;
$pages_navigation_rs_list = buildNavigation($pageNum_rs_list,$totalPages_rs_list,$prev_rs_list,$next_rs_list,$separator,$max_links,true); 

print $pages_navigation_rs_list[0]; 
?>
				    <?php print $pages_navigation_rs_list[1]; ?> <?php print $pages_navigation_rs_list[2]; ?> </td>
			  </tr>
            </table>        </td>
    </tr>
	<tr>
		<td width="25%" height="30" class="tbl_h2">Batch No.</td>
		<td width="25%" height="30" class="tbl_h2">No. of Tasks</td>
		<td width="20%" height="30" class="tbl_h2">From</td>
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
        <td width="15%" height="30" class="tbl_h2">Subject</td>
            
         <td height="30" class="tbl_h2">&nbsp;</td>
	</tr>  
    <?php do { ?>
      <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
		<td class="tbl_data" >
        <strong>
        <a href="index.php?idbatch=<?php echo $row_rs_list['idwftasks_batch'];?>&amp;batchformview=<?php echo $row_rs_list['wftasks_batchtype_idwftasks_batchtype'];?>&amp;view=2&amp;page=&amp;mod=2&amp;submod=299999999">
		<div><?php echo $row_rs_list['batch_no_verbose'];?></div>
        </a>
        </strong>
        </td>
		<td class="tbl_data" ><div><strong><?php echo $row_rs_list['countbatch'];?></strong></div></td>
		<td class="tbl_data" ><strong><?php echo $row_rs_list['userteamzonename'];?></strong></td>
        <!--
		<td class="tbl_data">
        
		</td>
        -->
        <td class="tbl_data" ><strong><?php echo $row_rs_list['batchtypedesc'];?></strong></td>
        <td class="tbl_data" style="padding:0px; ">
       	<table border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td style="padding:2px 0px; ">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
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
    	<td colspan="6">
			<table border="0" cellpadding="0" cellspacing="0">
            	<tr>
					<td class="text_small"> Batches <?php echo ($startRow_rs_list + 1) ?> to <?php echo min($startRow_rs_list + $maxRows_rs_list, $totalRows_rs_list) ?> of <?php echo $totalRows_rs_list ?> </td>
					<td class="text_body"><?php 
# variable declaration
$prev_rs_list = "<< previous";
$next_rs_list = "next >>";
$separator = " | ";
$max_links = 10;
$pages_navigation_rs_list = buildNavigation($pageNum_rs_list,$totalPages_rs_list,$prev_rs_list,$next_rs_list,$separator,$max_links,true); 

print $pages_navigation_rs_list[0]; 
?>
				    <?php print $pages_navigation_rs_list[1]; ?> <?php print $pages_navigation_rs_list[2]; ?> </td>
			  </tr>
            </table>        </td>
    </tr>
</table>
<?php } else { //else if no record ?>
	<div style="text-align:center; margin:45px 15px;" class="msg_warning">
    <?php echo $msg_no_record_4u; ?>
    </div>
<?php } ?>
  </div>
</div>

<?php
mysql_free_result($rs_list);
?>
