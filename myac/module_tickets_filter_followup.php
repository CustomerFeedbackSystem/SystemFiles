<?php //require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

//clean up inputs from the form submission
$floc=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['filter_loc'])));
$fcat=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['filter_cat'])));
$fstat=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['filter_stat'])));
$fchn=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['filter_chn'])));
$ftktno=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['tktno'])));

if ((isset($floc)) && ($floc>0) )
	{
	$_SESSION['filter_loc']= " AND tktin.loctowns_idloctowns=".$floc." ";
	} else {
	$_SESSION['filter_loc']=" ";
	}

if ((isset($fcat)) && ($fcat>0) )
	{
	$_SESSION['filter_cat']= " AND 	tktin.tktcategory_idtktcategory=".$fcat." ";
	} else {
	$_SESSION['filter_cat']=" ";
	}
	
if ((isset($fstat)) && ($fstat>0) )
	{
	$_SESSION['filter_stat']=" AND  tktin.tktstatus_idtktstatus=".$fstat." ";
	} else {
	$_SESSION['filter_stat']=" ";
	}
	
if ((isset($fchn)) && ($fchn>0) )
	{
	$_SESSION['filter_chn']=" AND tktin.tktstatus_idtktstatus=".$fchn." ";
	} else {
	$_SESSION['filter_chn']=" ";
	}

if ((isset($ftktno)) && (strlen($ftktno)>0) )
	{
	$_SESSION['filter_tktno']=" AND tktin.refnumber LIKE '%".$ftktno."%' ";
	} else {
	$_SESSION['filter_tktno']=" ";
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
$query_rs_tickets = "SELECT tktin.timereported,tktin.idtktinPK,tktin.refnumber,tktchannel.tktchannelname,tktstatus.tktstatusname,tktcategory.tktcategoryname,loctowns.locationname,tktstatus.status_color,tkttype.tkttypename FROM tktin INNER JOIN tktchannel ON tktin.tktchannel_idtktchannel=tktchannel.idtktchannel INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns INNER JOIN tkttype ON tktin.tkttype_idtkttype=tkttype.idtkttype WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." ".$_SESSION['filter_loc']." ".$_SESSION['filter_cat']." ".$_SESSION['filter_stat']."  ".$_SESSION['filter_chn']." ".$_SESSION['filter_tktno']."";
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
<?php require_once('../Connections/connSystem.php'); ?>
<div>
    <div >
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
        <tr>
        	<td>
                <table border="0" width="100%" cellpadding="2" cellspacing="0">
                <tr>
               	  <td colspan="7" style="background-color:#E8E8E8" >
<table border="0" cellpadding="0" cellspacing="0" width="100%">
                            	<tr>
                                	<td width="90%" class="hline">
                                    <form method="get" action="" name="filter">
                                    <table border="0" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<td class="text_body">
                                            <?php echo $lbl_findtkt;?>:
                                            </td>
                                            <td>
                                            <select class="small_field" name="filter_loc">
                                            <option value="">--<?php echo $lbl_location;?>--</option>
                                            <?php
											$sql_location="SELECT DISTINCT idloctowns,locationname FROM tktin INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns ORDER BY locationname ASC";
											$res_location=mysql_query($sql_location);
											$num_location=mysql_num_rows($res_location);
											$fet_location=mysql_fetch_array($res_location);
												do {
												echo "<option ";
												if ( (isset($floc)) && ($floc==$fet_location['idloctowns']) )
													{
													echo "selected=\"selected\"";
													}
												echo " value=\"".$fet_location['idloctowns']."\">".$fet_location['locationname']."</option>";
												} while ($fet_location=mysql_fetch_array($res_location));
											?>
                                            </select>
                                             <select  class="small_field" name="filter_cat">
                                            <option value="">--<?php echo $lbl_tktcat;?>--</option>
                                            <?php
											$sql_cats="SELECT idtktcategory,tktcategory_idtktcategory,tktcategoryname FROM tktcategory WHERE tktcategory_idtktcategory=0 ORDER BY tktcategoryname ASC";
											$res_cats=mysql_query($sql_cats);
											$num_cats=mysql_num_rows($res_cats);
											$fet_cats=mysql_fetch_array($res_cats);
												do {
												echo "<option ";
												if ( (isset($fcat)) && ($fcat==$fet_cats['idtktcategory']) )
													{
													echo " selected=\"selected\" ";
													}
												echo " value=\"".$fet_cats['idtktcategory']."\">".$fet_cats['tktcategoryname']."</option>";
												} while ($fet_cats=mysql_fetch_array($res_cats));
											?>
                                            </select>
                                             <select  class="small_field" name="filter_stat">
                                            <option value="">--<?php echo $lbl_status;?>--</option>
                                            <?php
											$sql_status="SELECT idtktstatus,tktstatusname FROM tktstatus ";
											$res_status=mysql_query($sql_status);
											$num_status=mysql_num_rows($res_status);
											$fet_status=mysql_fetch_array($res_status);
												do {
												echo "<option " ;
												if ( (isset($fstat)) && ($fstat==$fet_status['idtktstatus']) )
												{
												echo " selected=\"selected\" ";
												}
												echo " value=\"".$fet_status['idtktstatus']."\">".$fet_status['tktstatusname']."</option>";
												} while ($fet_status=mysql_fetch_array($res_status));
											?>
                                            </select>
                                             <select  class="small_field" name="filter_chn">
                                            <option value="">--<?php echo $lbl_ticketchn;?>--</option>
                                            <?php
											$sql_channel="SELECT idtktchannel,tktchannelname FROM tktchannel ";
											$res_channel=mysql_query($sql_channel);
											$num_channel=mysql_num_rows($res_channel);
											$fet_channel=mysql_fetch_array($res_channel);
												do {
												echo "<option ";
												if ( (isset($fchn)) && ($fchn==$fet_channel['idtktchannel']) )
													{
													echo " selected=\"selected\" ";
													}
												echo " title=\"".$fet_channel['tktchannelname']."\" value=\"".$fet_channel['idtktchannel']."\">".substr($fet_channel['tktchannelname'],0,15)."</option>";
												} while ($fet_channel=mysql_fetch_array($res_channel));
											?>
                                            </select>
                                             <input value="<?php if (isset($ftktno)) { echo $ftktno; } ?>" type="text" name="tktno" maxlength="10" size="10" />
                                            <a href="#" style="text-decoration:none" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle"><span><?php echo $msg_tktsearch;?></span></a><!--<select  class="small_field" name="filter_type">
                                            <option value="">--<?php echo $lbl_tkttype;?>--</option>
                                            <?php
											$sql_type="SELECT idtkttype,tkttypename,tkttypedesc FROM  tkttype ";
											$res_type=mysql_query($sql_type);
											$num_type=mysql_num_rows($res_type);
											$fet_type=mysql_fetch_array($res_type);
												do {
												echo "<option title=\"".$fet_type['tkttypedesc']."\" value=\"".$fet_type['idtkttype']."\">".$fet_type['tkttypename']."</option>";
												} while ($fet_type=mysql_fetch_array($res_type));
											?>
                                            </select>-->                                            </td>
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
                            </table>
                        </td>
                    </tr>
                    <tr>
                	<td colspan="7">
                    <div class="tab_area">
        	<span class="tab"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=1"><?php echo $lbl_alltkts;?></a></span>
            <span class="tab_on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=2"><?php echo $lbl_tktfiltersts?></a></span>
        </div>
                    </td>
                </tr>
                	<tr>
                    	<td colspan="7" style="padding:15px 0px 0px 0px">
                        	<table border="0" cellpadding="2" cellspacing="0" width="100%">
                            	<tr>
                                	<td width="50%" class="text_small"> Tickets <?php echo ($startRow_rs_tickets + 1) ?> to <?php echo min($startRow_rs_tickets + $maxRows_rs_tickets, $totalRows_rs_tickets) ?> of <?php echo $totalRows_rs_tickets ?> </td>
                                  <td width="50%"><?php 
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
                        <?php echo $lbl_location;?>
                        </td>
                        <td class="tbl_h2">
                        <?php echo $lbl_tktcat;?>
                        </td>
                        <td class="tbl_h2">
                        <?php echo $lbl_status;?>
                        </td>
                        <td class="tbl_h2">
                        <?php echo $lbl_ticketchn;?>
                        </td>
                        <td class="tbl_h2">
                        <?php echo $lbl_tkttype;?>
                        </td>
                </tr>
                    <?php do { ?>
                      <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                        
                        <td class="tbl_data">
                          <a href="#" onclick="tb_open_new('go_to_taskhistory.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')" ><?php echo $row_rs_tickets['refnumber']; ?></a>
                          </td>
                          <td class="tbl_data">
                          <?php echo date("D, M d, Y H:i",strtotime($row_rs_tickets['timereported'])); ?>
                          </td>
                        <td class="tbl_data">
                          <?php echo $row_rs_tickets['locationname']; ?></td>
                        <td class="tbl_data">
                          <?php echo $row_rs_tickets['tktcategoryname']; ?></td>
                        <td class="tbl_data">
                          <?php echo "<span style=\"".$row_rs_tickets['status_color']."\">".$row_rs_tickets['tktstatusname']."</span>"; ?></td>
                        <td class="tbl_data">
                          <?php echo $row_rs_tickets['tktchannelname']; ?></td>
                          <td class="tbl_data">
                          <?php echo $row_rs_tickets['tkttypename']; ?></td>
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
                                  <td width="50%"><?php 
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
                </table>
            </td>
        </tr>
	</table>
	</div>
</div>
    <?php
mysql_free_result($res_heading);


mysql_free_result($rs_tickets);
?>
