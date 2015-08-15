<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//determine what WSB will see below based on WSPs reporting to it
$sql_wsps="SELECT idusrteam FROM usrteam WHERE reportto_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
$res_wsps=mysql_query($sql_wsps);
$fet_wsps=mysql_fetch_array($res_wsps);
$num_wsps=mysql_num_rows($res_wsps);
//echo $sql_wsps;
if ($num_wsps >0)
	{
	$qry_filter="";
	
		do {
		$qry_filter.=" tktin.usrteam_idusrteam=".$fet_wsps['idusrteam']." OR ";
		} while ($fet_wsps=mysql_fetch_array($res_wsps));
	
	$qry_filtered="(".substr($qry_filter,0,-3).")";
	//$qry_filtered=$qry_filter;
	//echo $qry_filtered;
//	exit;
	} else {
	
	$qry_filtered="";
	}
//echo $qry_filtered;
//exit;	
	
//construct the query below depending on what level the tickets are being viewed at

#	BuildNav for Dreamweaver MX v0.2
#              10-02-2002
#	Alessandro Crugnola [TMM]
#	sephiroth: alessandro@sephiroth.it
#	http://www.sephiroth.it
#	
#	Function for navigation build ::
function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true)
{
                GLOBAL $maxRows_rs_tickets,$totalRows_rs_tickets;
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
					if ($_get_name != "pageNum_rs_tickets") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_tickets=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_rs_tickets) + 1;
					$max_l = ($a*$maxRows_rs_tickets >= $totalRows_rs_tickets) ? $totalRows_rs_tickets : ($a*$maxRows_rs_tickets);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_tickets=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_tickets=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
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


$maxRows_rs_tickets = 20;
$pageNum_rs_tickets = 0;
if (isset($_GET['pageNum_rs_tickets'])) {
  $pageNum_rs_tickets = $_GET['pageNum_rs_tickets'];
}
$startRow_rs_tickets = $pageNum_rs_tickets * $maxRows_rs_tickets;

mysql_select_db($database_connSystem, $connSystem);
$query_rs_tickets = "SELECT tktin.idtktinPK, tktin.timereported,tktin.timedeadline, tktin.refnumber,tktcategory.tktcategoryname,loctowns.locationname,tktstatus.tktstatusname,tktstatus.status_color,TIMESTAMPDIFF(MINUTE, NOW(), tktin.timedeadline) AS time_to_deadline,usrteamname,usrteamshortname FROM tktin INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus  INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory  INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns INNER JOIN usrteam ON tktin.usrteam_idusrteam=usrteam.idusrteam WHERE ".$qry_filtered." AND tktin.timedeadline < '".$timenowis."'  ORDER BY tktin.timereported ASC";
$query_limit_rs_tickets = sprintf("%s LIMIT %d, %d", $query_rs_tickets, $startRow_rs_tickets, $maxRows_rs_tickets);
$rs_tickets = mysql_query($query_limit_rs_tickets, $connSystem) or die(mysql_error());
$row_rs_tickets = mysql_fetch_assoc($rs_tickets);

if (isset($_GET['totalRows_rs_tickets'])) {
  $totalRows_rs_tickets = $_GET['totalRows_rs_tickets'];
} else {
  $all_rs_tickets = mysql_query($query_rs_tickets);
  $totalRows_rs_tickets = mysql_num_rows($all_rs_tickets);
}
$totalPages_rs_tickets = ceil($totalRows_rs_tickets/$maxRows_rs_tickets)-1;

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td>
            </td>
        </tr>
        <tr>
        	<td>
            <?php
            if (($totalRows_rs_tickets > 0) && ($qry_filtered!="NONE"))
				{
			?>
                <table border="0" width="100%" cellpadding="2" cellspacing="0">
<!--                
Have the Ticket Filter Here
-->                	<tr>
                    	<td colspan="7" style="padding:15px 0px 0px 0px">
                        	<table border="0" cellpadding="2" cellspacing="0" width="100%">
                            	<tr>
                                	<td width="50%" class="text_small"> Tickets <?php echo ($startRow_rs_tickets + 1) ?> to <?php echo min($startRow_rs_tickets + $maxRows_rs_tickets, $totalRows_rs_tickets) ?> of <?php echo $totalRows_rs_tickets ?> </td>
                                  <td width="50%" align="center" class="text_small_bold"><?php 
# variable declaration
$prev_rs_tickets = "« previous";
$next_rs_tickets = "next »";
$separator = " | ";
$max_links = 10;
$pages_navigation_rs_tickets = buildNavigation($pageNum_rs_tickets,$totalPages_rs_tickets,$prev_rs_tickets,$next_rs_tickets,$separator,$max_links,true); 

print $pages_navigation_rs_tickets[0]; 
?>
                                  <?php print $pages_navigation_rs_tickets[1]; ?> <?php print $pages_navigation_rs_tickets[2]; ?></td>
                              </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="7" style="background-color:#E8E8E8">
                 <form method="get" name="filter" action="">
                 <table border="0" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<td class="text_body">
                                            <?php echo $lbl_ticketfilter;?>:
                                            </td>
                                          <td>
                                            <select class="small_field" name="filter_wsp">
                                            <option value="">--WSP--</option>
                                            <option value="-1"> >> All WSPs << </option>
                                            <?php
											$sql_location="SELECT idusrteam,usrteamname FROM usrteam WHERE reportto_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY usrteamname ASC";
											$res_location=mysql_query($sql_location);
											$num_location=mysql_num_rows($res_location);
											$fet_location=mysql_fetch_array($res_location);
												do {
												echo "<option value=\"".$fet_location['idusrteam']."\">".$fet_location['usrteamname']."</option>";
												} while ($fet_location=mysql_fetch_array($res_location));
											?>
                                            </select>
                                             <select  class="small_field" name="filter_cat">
                                            <option value="">--<?php echo $lbl_tktcat;?>--</option>
                                            <option value="-1">>> All Categories << </option>
                                            <?php
											$sql_cats="SELECT idtktcategory,tktcategory_idtktcategory,tktcategoryname FROM tktcategory WHERE tktcategory_idtktcategory=0 ORDER BY tktcategoryname ASC";
											$res_cats=mysql_query($sql_cats);
											$num_cats=mysql_num_rows($res_cats);
											$fet_cats=mysql_fetch_array($res_cats);
												do {
												echo "<option ";
												
												echo " value=\"".$fet_cats['idtktcategory']."\">".$fet_cats['tktcategoryname']."</option>";
												} while ($fet_cats=mysql_fetch_array($res_cats));
											?>
                                            </select>                                            </td>
                                            <td>
                                            <a href="#" onclick="document.forms['filter'].submit()" id="button_go_2"></a>
                                            <input type="hidden" value="2" name="parentviewtab" />
                                            <input type="hidden" value="4" name="mod" />
                                            <input type="hidden" value="6" name="submod" />
                                            <input type="hidden" value="view_submod" name="uction" />

                                            </td>
                                        </tr>
                                    </table>
                 </form>
                        </td>
                    </tr>
                    <tr>
                     <td class="tbl_h2">
                        <?php echo $lbl_ticketno;?>
                      </td>
                        <td class="tbl_h2">
                        	<table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="tbl_h2">
                                    <?php echo $lbl_timereported;?>
                                    </td>
                                    <td width="12px">
                                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?orderlist=ascending&amp;orderwhat=from" id="button_asc"></a>
                                    </td>
                                    <td width="12px">
                                    <a href="<?php echo $_SERVER['PHP_SELF'];?>?orderlist=descending&amp;orderwhat=from" id="button_desc"></a>
                                    </td>
                                </tr>
                            </table>
                      </td>
                       <td class="tbl_h2">
                        <?php echo $lbl_overdue;?>
                        </td>
                        <td class="tbl_h2">
                        <?php echo $lbl_location;?>
                        </td>
                        <td class="tbl_h2">
                        <?php echo $lbl_tktcat;?>
                        </td>
                        <td class="tbl_h2">
                        <?php echo $lbl_statuswsp;?>
                        </td>
                       
                        <td class="tbl_h2">
                        <?php echo $lbl_asdto;?>
                        </td>
                </tr>
                    <?php do { ?>
                      <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                        
                        <td class="tbl_data">                      
                          <a href="#" onclick="tb_open_new('pop_viewticket_escalated.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')" ><?php echo $row_rs_tickets['refnumber']; ?></a>
                        </td>
                          <td class="tbl_data">
                          <?php echo date("D, M d, Y H:i",strtotime($row_rs_tickets['timereported'])); ?>
                          </td>
                          <td class="tbl_data">
                          <?php
                          echo date("D, M d, Y H:i",strtotime($row_rs_tickets['timedeadline'])); 
						  
						  //deciding the color to use
							if ($row_rs_tickets['timedeadline'] > $timenowis)
								{
								$colorclass="txt_green";
								} else {
								$colorclass="txt_red";
								}
						  
						  $minutes=abs($row_rs_tickets['time_to_deadline']);
							$d = floor ($minutes / 1440);
							$h = floor (($minutes - $d * 1440) / 60);
							$m = $minutes - ($d * 1440) - ($h * 60);

							echo "&nbsp;<span class=\"".$colorclass."\">({$d}d {$h}hr {$m}m)</span>";
						  ?>
                          </td>
                        <td class="tbl_data">
                          <?php echo $row_rs_tickets['locationname']; ?></td>
                        <td class="tbl_data">
                          <?php echo $row_rs_tickets['tktcategoryname']; ?></td>
                        <td class="tbl_data">
                          <?php echo "<span style=\"color:".$row_rs_tickets['status_color']."\">".$row_rs_tickets['tktstatusname']."</span>"; ?></td>
                        
                          <td class="tbl_data">
                            <span title="<?php echo $row_rs_tickets['usrteamname'];?>">
							<?php echo $row_rs_tickets['usrteamshortname']; ?>
                            </span>
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
                      <?php } while ($row_rs_tickets = mysql_fetch_assoc($rs_tickets)); ?>
                    <tr>
                    	<td colspan="7">
                        	<table border="0" cellpadding="2" cellspacing="0" width="100%">
                            	<tr>
                                	<td width="50%" class="text_small"> Tickets <?php echo ($startRow_rs_tickets + 1) ?> to <?php echo min($startRow_rs_tickets + $maxRows_rs_tickets, $totalRows_rs_tickets) ?> of <?php echo $totalRows_rs_tickets ?> </td>
                                  <td width="50%" align="center" class="text_small_bold"><?php 
# variable declaration
$prev_rs_tickets = "« previous";
$next_rs_tickets = "next »";
$separator = " | ";
$max_links = 10;
$pages_navigation_rs_tickets = buildNavigation($pageNum_rs_tickets,$totalPages_rs_tickets,$prev_rs_tickets,$next_rs_tickets,$separator,$max_links,true); 

print $pages_navigation_rs_tickets[0]; 
?>
                                  <?php print $pages_navigation_rs_tickets[1]; ?> <?php print $pages_navigation_rs_tickets[2]; ?></td>
                              </tr>
                            </table>
<?php } else { ?>
<div style="text-align:center; margin:45px 15px;" class="msg_warning">
    <?php echo $msg_no_record; ?>
    </div>
<?php } ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
</table>
<?php
mysql_free_result($rs_tickets);
?>   