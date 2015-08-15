<?php //require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['orderlist']))
	{
	$order_logix=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['orderlist'])));
	if ($order_logix=="descending")
		 {
		 $_SESSION['order_logix']=" DESC ";
		 } else if ($order_logix=="ascending") {
		 $_SESSION['order_logix']=" ASC ";
		 } else {
		 $_SESSION['order_logix']=" DESC ";
		 }
				
	 } else {
	 $_SESSION['order_logix']=" DESC ";
	 }

 if (isset($_GET['orderwhat']))
     {
     $orderwhat=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['orderwhat'])));
     if ($orderwhat=="daterec")
         {
         $_SESSION['order_field']=" timereported ";
		}
         
     } else {
     $_SESSION['order_field']=" timereported ";
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
$query_rs_tickets = "SELECT tktin.timereported,tktin.timedeadline,tktin.idtktinPK,tktin.refnumber,tktchannel.tktchannelname,tktstatus.tktstatusname,tktcategory.tktcategoryname,tktcategory.idtktcategory,tktstatus.status_color,tkttype.tkttypename,city_town,(SELECT locationname FROM loctowns WHERE loctowns.idloctowns = tktin.loctowns_idloctowns) AS locationname FROM tktin INNER JOIN tktchannel ON tktin.tktchannel_idtktchannel=tktchannel.idtktchannel INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory INNER JOIN tkttype ON tktin.tkttype_idtkttype=tkttype.idtkttype WHERE tktin.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY ".$_SESSION['order_field']." ".$_SESSION['order_logix']."";
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
<script language="javascript">
function comp_hide()
{
if (document.filter.tktno.value == "Ticket No.")
document.filter.tktno.value = "";
}

function comp_show()
{
if ((document.filter.tktno.value == "") || (document.filter.tktno.value == "") )
document.filter.tktno.value = "Ticket No.";
}
</script>

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
            <?php
            if ($totalRows_rs_tickets > 0)
				{
			?>
                <table border="0" width="100%" cellpadding="2" cellspacing="0">
                <tr>
               	  <td colspan="7"  >
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#E8E8E8">
                            	<tr>
                                	<td width="90%" class="hline">
                                    <form method="get" action="" name="filter">
                                    <table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="text_body" rowspan="2">
		  <strong><?php echo $lbl_findtkt;?></strong> </td>
		<td>
			<table border="0" width="100%" cellpadding="0" cellspacing="0">
            	<tr>
                	<td class="text_small">
                    Ticket No.
                    </td>
                    <td class="text_small">
                    Tel No.
                    </td>
                    <td class="text_small">
                    AC No.
                    </td>
                    <td class="text_small">
                    Location
                    </td>
                </tr>
                <tr>
                	<td class="text_small"><input onkeyup="this.value=this.value.toUpperCase();"  name="tktno" type="text" class="small_field"  value="" size="20" maxlength="20" id="tktno" /></td>
                    <td class="text_small"><input onKeyUp="res(this,numb);"  name="telno" type="text" class="small_field"  value="" size="15" maxlength="12" id="telno" /></td>
                    <td class="text_small"><input onKeyUp="res(this,numb);"  name="acno" type="text" class="small_field"  value="" size="10" maxlength="10" id="acno" /></td>
                	<td>
                    <select  class="small_field" name="filter_cat">
                                            <option value="">--<?php echo $lbl_tktcat;?>--</option>
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
                                            </select>
                    </td>
                </tr>
            </table>
		</td>
        <td rowspan="2">
		<a href="#" onclick="document.forms['filter'].submit()" id="button_search"></a>
                                            <input type="hidden" value="2" name="parentviewtab" />
                                            <input type="hidden" value="8" name="mod" />
                                            <input type="hidden" value="63" name="submod" />
                                            <input type="hidden" value="view_submod" name="uction" />
		</td>
    </tr>
    <tr>
    	<td>
        <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                	<td class="text_small"><select class="small_field" name="filter_loc">
                                            <option value="">--<?php echo $lbl_location;?>--</option>
                                            <?php
											$sql_location="SELECT DISTINCT idloctowns,locationname FROM tktin INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns ORDER BY locationname ASC";
											$res_location=mysql_query($sql_location);
											$num_location=mysql_num_rows($res_location);
											$fet_location=mysql_fetch_array($res_location);
												do {
												echo "<option value=\"".$fet_location['idloctowns']."\">".$fet_location['locationname']."</option>";
												} while ($fet_location=mysql_fetch_array($res_location));
											?>
                                            </select></td>
                    <td class="text_small"><select  class="small_field" name="filter_stat">
                                            <option value="">--<?php echo $lbl_status;?>--</option>
                                            <?php
											$sql_status="SELECT idtktstatus,tktstatusname FROM tktstatus ";
											$res_status=mysql_query($sql_status);
											$num_status=mysql_num_rows($res_status);
											$fet_status=mysql_fetch_array($res_status);
												do {
												echo "<option value=\"".$fet_status['idtktstatus']."\">".$fet_status['tktstatusname']."</option>";
												} while ($fet_status=mysql_fetch_array($res_status));
											?>
                                            </select></td>
					<td class="text_small" align="right"><select  class="small_field" name="filter_chn">
                                            <option value="">--<?php echo $lbl_ticketchn;?>--</option>
                                            <?php
											$sql_channel="SELECT idtktchannel,tktchannelname FROM tktchannel ";
											$res_channel=mysql_query($sql_channel);
											$num_channel=mysql_num_rows($res_channel);
											$fet_channel=mysql_fetch_array($res_channel);
												do {
												echo "<option title=\"".$fet_channel['tktchannelname']."\" value=\"".$fet_channel['idtktchannel']."\">".substr($fet_channel['tktchannelname'],0,15)."</option>";
												} while ($fet_channel=mysql_fetch_array($res_channel));
											?>
                                           </select></td>
                </tr>
            </table>
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
                                    <a target="_blank" href="<?php echo $_SERVER['PHP_SELF'];?>?orderlist=ascending&amp;orderwhat=daterec" id="button_asc"></a>
                                    </td>
                                    <td width="12px">
                                    <a target="_blank" href="<?php echo $_SERVER['PHP_SELF'];?>?orderlist=descending&amp;orderwhat=daterec" id="button_desc"></a>
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
                          <a target="_blank" href="pop_taskreassign_admin.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;tktcat=<?php echo $row_rs_tickets['idtktcategory']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;display_reass=no&amp;tabview=1" >
						  <div><?php echo $row_rs_tickets['refnumber']; ?></div></a>
                        </td>
                          <td class="tbl_data">
                          <a style="text-decoration:none; color:#333333" target="_blank" href="pop_taskreassign_admin.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;tktcat=<?php echo $row_rs_tickets['idtktcategory']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;display_reass=no&amp;tabview=1" >
                          <div>
						  <?php 
						  echo date("D, M d, Y H:i",strtotime($row_rs_tickets['timereported'])); ?>
                          </div>
                          </a>
                          </td>
                        <td class="tbl_data">
                        <a style="text-decoration:none; color:#333333" target="_blank" href="pop_taskreassign_admin.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;tktcat=<?php echo $row_rs_tickets['idtktcategory']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;display_reass=no&amp;tabview=1" >
                          <div>
                          <?php 
						  if (strlen($row_rs_tickets['locationname'])>0) 
							  {  
							  echo "<span title=\"".$row_rs_tickets['city_town']."\">".$row_rs_tickets['locationname']."</span>"; 
							  } 
							  else if (strlen($row_rs_tickets['locationname'])<1)  
							  {
								if (strlen($row_rs_tickets['city_town'])>0) 
									{ 
									if (strlen($row_rs_tickets['city_town'])>21)
										{
										echo "<span title=\"".$row_rs_tickets['city_town']."\">".$row_rs_tickets['city_town']."...</span>"; 
										} else {
										echo $row_rs_tickets['city_town']."..."; 
										}
									} else {
									echo "-";
									}
								}	
							
						   ?>
                        </div>
                          </a>
                        </td>
                        <td class="tbl_data">
                        <a style="text-decoration:none; color:#333333" target="_blank" href="pop_taskreassign_admin.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;tktcat=<?php echo $row_rs_tickets['idtktcategory']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;display_reass=no&amp;tabview=1" >
                          <div>
                          <?php echo $row_rs_tickets['tktcategoryname']; ?>
                          </div>
                          </a>
                        </td>
                        <td class="tbl_data">
                        <a style="text-decoration:none; color:#333333" target="_blank" href="pop_taskreassign_admin.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;tktcat=<?php echo $row_rs_tickets['idtktcategory']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;display_reass=no&amp;tabview=1" >
                          <div>
                          <?php 
						   if ($row_rs_tickets['timedeadline'] > $timenowis)
								{
								$colorclass="txt_green";
								} else {
								$colorclass="txt_red";
								}
						  echo "<span class=\"".$colorclass."\">".$row_rs_tickets['tktstatusname']."</span>"; ?>
                          </div>
                          </a>
                        </td>
                        <td class="tbl_data">
                        <a style="text-decoration:none; color:#333333" target="_blank" href="pop_taskreassign_admin.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;tktcat=<?php echo $row_rs_tickets['idtktcategory']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;display_reass=no&amp;tabview=1" >
                          <div>
                          <?php echo $row_rs_tickets['tktchannelname']; ?>
                          </div>
                          </a>
                        </td>
                          <td class="tbl_data">
                          <a style="text-decoration:none; color:#333333" target="_blank" href="pop_taskreassign_admin.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;tktcat=<?php echo $row_rs_tickets['idtktcategory']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;display_reass=no&amp;tabview=1" >
                          <div>
                          <?php echo $row_rs_tickets['tkttypename']; ?>
                          </div>
                          </a>
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
	</div>
</div>
    <?php
mysql_free_result($res_heading);


mysql_free_result($rs_tickets);
?>
