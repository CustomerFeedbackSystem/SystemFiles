<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//begin mass selection here
if ( (isset($_POST['allocate'])) && ($_POST['assign_to']!="")) 
	{
	$count=count($_POST['allocate']);
	$assignto=$_POST['assign_to'];
	//allocate within this loop
		for ($i=0; $i<$count; $i++) 
			{
			$sql_assign="INSERT INTO tktactivityowner (idtktinPK,idusrac,addedon,addedby ) 
			VALUES ('".$_POST['allocate'][$i]."','".$assignto."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
			mysql_query($sql_assign);
			
			//log this as an activity
			$sql_logactivity="INSERT INTO tktactivity (idtktesclevel, idtktinPK, idtktactivitytype, activity_date, activity_details, entered_by, entered_by_role, addedby, addedon, modifiedby, modifiedon) 
			VALUES ('1', '".$_POST['allocate'][$i]."', '1', '".$timenowis."', 'Ticket Assignment for Follow-up','".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$_SESSION['MVGitHub_idacname']."','".$timenowis."', '0000-00-00 00:00:00','0')";
			mysql_query($sql_logactivity);
			
			$success=1;
			}	
				
	}
								
							
//end mass selection here

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
$query_rs_tickets = "SELECT tktin.idtktinPK, tktin.timereported,tktin.timedeadline, tktin.refnumber,tktcategory.tktcategoryname,loctowns.locationname,tktstatus.tktstatusname,tktstatus.status_color,TIMESTAMPDIFF(MINUTE, NOW(), tktin.timedeadline) AS time_to_deadline,(SELECT usrname FROM usrac WHERE usrac.idusrac = tktactivityowner.idusrac AND tktactivityowner.idtktinPK=tktin.idtktinPK ) as assigned_to FROM tktin INNER JOIN orgesc ON tktin.usrteam_idusrteam = orgesc.idusrteam_from INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus  INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory  INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns INNER JOIN tktactivityowner ON tktin.idtktinPK=tktactivityowner.idtktinPK WHERE  orgesc.idusrteam_to=".$_SESSION['MVGitHub_idacteam']." AND tktin.timedeadline < '".$timenowis."' GROUP BY tktactivityowner.idtktinPK ORDER BY tktin.timereported ASC";
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
<form method="post" name="form" onsubmit="return checkbox_assign()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td>
            </td>
        </tr>
        <tr>
        	<td>
            <?php
            if ($totalRows_rs_tickets > 0)
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
                    	<td colspan="7">
                      
                        <table border="0" cellpadding="3" cellspacing="0" >
                        	<tr>
                            	<td class="text_small_bold"><a href="#" onClick="selectAll(true);"><input type="checkbox" /> Select All</a></td>
                                <td class="text_body">
                                <?php echo $lbl_astktto_s;?>
                                </td>
                                <td>
                             <select name="assign_to" class="small_field">
                            <option value="">--Select One --</option>
                           	<option value="<?php echo $_SESSION['MVGitHub_idacname'];?>"><?php echo $lbl_astome."&nbsp;(".$_SESSION['MVGitHub_acname'].")";?></option>
            <?php
            //enable list if user is perm_global
			//drop down other people who are in this group/team other than the current user
			if ($is_perm_global==1)
				{
				$sql_list="SELECT idusrac,usrname,acstatus FROM usrac WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY usrname ASC";
				$res_list=mysql_query($sql_list);
				$fet_list=mysql_fetch_array($res_list);
				
					do {
						
						//hide from list if it is the current user
						
						if ($fet_list['idusrac']!=$_SESSION['MVGitHub_idacname'])
							{
								echo "<option ";
									if ($fet_list['acstatus']==0) { echo " disabled=\"disabled\" "; }
								echo " value=\"".$fet_list['idusrac']."\">".$fet_list['usrname']."</option>";
							} 	
							
					} while ($fet_list=mysql_fetch_array($res_list));
				
				}
			
			?>
                            </select>
                            </td>
                                <td>
                                <a href="#" onclick="document.forms['form'].submit()" id="button_go"></a>
                                </td>
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
                        <?php
						if (strlen($row_rs_tickets['assigned_to'])<1)
						{
						?>
                         <input type="checkbox" name="allocate[]" id="allocate[]" value="<?php echo $row_rs_tickets['idtktinPK']; ?>">
<?php
}
?>                         
                          <a href="#" onclick="tb_open_new('pop_viewticketfollowup.php?tkt=<?php echo $row_rs_tickets['idtktinPK']; ?>&amp;title=<?php echo $row_rs_tickets['refnumber']; ?>&amp;&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')" ><?php echo $row_rs_tickets['refnumber']; ?></a>
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
                          <?php if (strlen($row_rs_tickets['assigned_to'])>1) { echo $row_rs_tickets['assigned_to']; } else { echo "<span class=\"txt_red\">---</span>"; }?>
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
</form>
<?php
mysql_free_result($rs_tickets);
?>   