<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//
if (!isset($_GET['parentviewtab']))
	{
	$_SESSION['parenttabview']=1;
	} else {
	$_SESSION['parenttabview']=trim($_GET['parentviewtab']);
	}
	
//check if this user is global or not for this module :)
$sql_globalperm="SELECT global_access FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$_SESSION['sec_submod']." AND
sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1 ";
$res_globalperm=mysql_query($sql_globalperm);
$fet_globalperm=mysql_fetch_array($res_globalperm);

if ($fet_globalperm['global_access']==1)
	{
	$_SESSION['is_global_view']=1;
	} else {
	$_SESSION['is_global_view']=0;
	}	
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
                GLOBAL $maxRows_rs_usrs,$totalRows_rs_usrs;
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
					if ($_get_name != "pageNum_rs_usrs") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_usrs=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_rs_usrs) + 1;
					$max_l = ($a*$maxRows_rs_usrs >= $totalRows_rs_usrs) ? $totalRows_rs_usrs : ($a*$maxRows_rs_usrs);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_usrs=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_rs_usrs=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
}
?><?php require_once('../Connections/connSystem.php'); ?>
<?php
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

$maxRows_rs_usrs = 50;
$pageNum_rs_usrs = 0;
if (isset($_GET['pageNum_rs_usrs'])) {
  $pageNum_rs_usrs = $_GET['pageNum_rs_usrs'];
}
$startRow_rs_usrs = $pageNum_rs_usrs * $maxRows_rs_usrs;

//check if there is a search filter set for this result so as to use a different query for search as opposed to view only
if ($_SESSION['parenttabview']==3)//if search, then lets redo the query
	{
	$find_user=trim(mysql_escape_string($_GET['userac_find']));
	$find_dpts=trim(mysql_escape_string($_GET['dpts']));
	
	if ($find_dpts>0)
		{
		$_SESSION['filter_qry_dpts']="  AND usrrole.usrdpts_idusrdpts=".$find_dpts."  ";
		} else {
		$_SESSION['filter_qry_dpts']=" ";
		}
	
	if (((isset($_SESSION['filter_region'])) && ($_SESSION['filter_region']>0) ) || ( (isset($_SESSION['filter_qry_dpts'])) && ($_SESSION['filter_qry_dpts']>0) ))
		{ 
		$_SESSION['filter_qry_region']=" AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['filter_region']." "; 
		} else { 
		$_SESSION['filter_qry_region']=""; 
		}
	
	$qry="SELECT idusrac,usrname,usrpass,utitle,fname,lname,usrac.acstatus,usremail,usrphone,lastaccess,usrrole_idusrrole,lastaccess,(SELECT usrrolename FROM usrrole WHERE idusrrole=usrac.usrrole_idusrrole) as usrrolename,usrdptname FROM usrac 
	LEFT JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole
	LEFT JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
	WHERE usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND (usrname LIKE '%".$find_user."%' OR fname LIKE '%".$find_user."%' OR lname LIKE '%".$find_user."%' OR usrrolename LIKE '%".$find_user."%') ".$_SESSION['filter_qry_region']." 
	".$_SESSION['filter_qry_dpts']."
	ORDER BY usrname ASC";	
	} else {
	$_SESSION['filter_region']=0;
	$qry="SELECT idusrac,usrname,usrpass,utitle,fname,lname,usrac.acstatus,usremail,usrphone,lastaccess,usrrole_idusrrole,lastaccess,(SELECT usrrolename FROM usrrole WHERE idusrrole=usrac.usrrole_idusrrole) as usrrolename,usrdptname FROM usrac 
	LEFT JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole
	LEFT JOIN usrdpts ON usrrole.usrdpts_idusrdpts=usrdpts.idusrdpts
	WHERE usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND usrac.acstatus=".$_SESSION['parenttabview']." ORDER BY usrname ASC";	
	}

mysql_select_db($database_connSystem, $connSystem);
$query_rs_usrs = $qry;
$query_limit_rs_usrs = sprintf("%s LIMIT %d, %d", $query_rs_usrs, $startRow_rs_usrs, $maxRows_rs_usrs);
$rs_usrs = mysql_query($query_limit_rs_usrs, $connSystem) or die(mysql_error());
$row_rs_usrs = mysql_fetch_assoc($rs_usrs);
//echo $query_rs_usrs;
if (isset($_GET['totalRows_rs_usrs'])) {
  $totalRows_rs_usrs = $_GET['totalRows_rs_usrs'];
} else {
  $all_rs_usrs = mysql_query($query_rs_usrs);
  $totalRows_rs_usrs = mysql_num_rows($all_rs_usrs);
}
$totalPages_rs_usrs = ceil($totalRows_rs_usrs/$maxRows_rs_usrs)-1;
?>

<div style="padding:0px 5px 100px 5px">
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div style="margin:5px; padding:0px 0px 25px 0px">
    <form method="get" action="" name="find_usr" style="margin:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    	<tr>
        	<td>
    		<a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=add_usr" id="button_userac"></a>
    		</td>
            <td bgcolor="#f5f5f5" class="text_small" align="right">
            Search : 
            </td>
            <td  bgcolor="#f5f5f5" align="left">
            	
                <table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td>
            
            <input type="hidden" value="3" name="parentviewtab" />
             <input type="hidden" value="8" name="mod" />
           <input type="hidden" value="14" name="submod" />
            <input type="hidden" value="view_submod" name="uction" />
            <input type="text" name="userac_find" />
            			</td>
                        <td>
                        <select name="filter_region" onchange="this.form.submit();">
               <option value="0">--ALL Regions--</option>
               <?php
			   //show the other regions if permissions are allowed--otherwise restrictt to my region
			   if ($_SESSION['is_global_view']==1)
			   	{
				$sql_regions="SELECT idusrteamzone,userteamzonename FROM usrteamzone
				WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
				$res_regions=mysql_query($sql_regions);
				$fet_regions=mysql_fetch_array($res_regions);
					do {
					echo "<option ";
						if ($_SESSION['filter_region']==$fet_regions['idusrteamzone'])
							{
							echo "selected=\"selected\"";
							}
					echo " value=\"".$fet_regions['idusrteamzone']."\">".$fet_regions['userteamzonename']."</option>";
					} while ($fet_regions=mysql_fetch_array($res_regions));
				}
			   ?>
               </select>
                        </td>
                        <td>
                        <select name="dpts">
                        <option value="0">--All Departments--</option>
                        <?php 
						$res_dpt=mysql_query("SELECT idusrdpts,usrdptname FROM usrdpts ORDER BY usrdptname ASC");
						$fet_dpt=mysql_fetch_array($res_dpt);
						do {
						echo "<option value=\"".$fet_dpt['idusrdpts']."\">".$fet_dpt['usrdptname']."</option>";
						} while ($fet_dpt=mysql_fetch_array($res_dpt));
						?>
                        </select>
                        </td>
                        <td>
                        <a href="#" id="button_search" onclick="document.forms['find_usr'].submit();"></a>
                        </td>
                    </tr>
                    </table>
            
            </td>
		</tr>
	</table>
   </form>
    </div>
    <div>
    <?php
    if (isset($_GET['msg_success'])) { echo "<div class=\"msg_success\">".$_GET['msg_success']."</div>"; } 
	if (isset($msg)) { echo $msg; }
	?>
    </div>
    <div>
    <div>
    	<div class="tab_area">
        	<span class="tab_<?php if ($_SESSION['parenttabview']==1) { echo "on";} else { echo "off"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;parentviewtab=1"><?php echo $lbl_statusactive;?></a></span>
			<span class="tab_<?php if ($_SESSION['parenttabview']==0) { echo "on";} else { echo "off"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;parentviewtab=0"><?php echo $lbl_statusactivenot;?></a></span>            
        	<?php
			if ($_SESSION['parenttabview']==3)
			{
			?>
            <span class="tab_<?php if ($_SESSION['parenttabview']==3) { echo "on";} else { echo "off"; } ?>">
			<?php echo $lbl_search_result?>
            </span>
            <?php
			}
			?>
        </div>
    </div>
    </div>
    <div>
    <?php
	if ($totalRows_rs_usrs > 0 )
		{
	?>
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
    	<tr>
        	<td class="tbl_h">
            <?php echo $lbl_username;?>
            </td>
             <td class="tbl_h">
            <?php echo $lbl_role;?>
            </td>
            <td class="tbl_h">
            Departments
            </td>
             <td class="tbl_h">
            <?php echo $lbl_access_profile;?>
            </td>
            <td class="tbl_h">
            <?php echo $lbl_zonename;?>
            </td>
            <td class="tbl_h">
            <?php echo $lbl_acholder;?>
            </td>
             <td class="tbl_h">
            <?php echo $lbl_mobile;?>
            </td>
            <td class="tbl_h">
            <?php echo $lbl_nemail;?>
            </td>
             <td class="tbl_h">
            <?php echo $lbl_status;?>
            </td>
             <td class="tbl_h">
            <?php echo $lbl_access_last;?>
            </td>
        </tr>
        <tr>
        	<td colspan="9">
            	<table border="0" width="100%">
                	<tr>
                    	<td width="30%" class="text_body" >
Records <?php echo ($startRow_rs_usrs + 1) ?> to <?php echo min($startRow_rs_usrs + $maxRows_rs_usrs, $totalRows_rs_usrs) ?> of <?php echo $totalRows_rs_usrs ?> </td>
                        <td width="70%" class="text_body"><?php 
# variable declaration
$prev_rs_usrs = "<< previous";
$next_rs_usrs = "next >>";
$separator = " | ";
$max_links = 10;
$pages_navigation_rs_usrs = buildNavigation($pageNum_rs_usrs,$totalPages_rs_usrs,$prev_rs_usrs,$next_rs_usrs,$separator,$max_links,true); 

print $pages_navigation_rs_usrs[0]; 
?>
                        <?php print $pages_navigation_rs_usrs[1]; ?> <?php print $pages_navigation_rs_usrs[2]; ?> </td>
                    </tr>
                </table>
                </td>
        </tr>
        <?php 
		$i=1;
		do { ?>
          <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
            <td class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;user=<?php echo $row_rs_usrs['idusrac']; ?>&amp;username=<?php echo urlencode($row_rs_usrs['usrname']);?>"><?php echo $i.". ".$row_rs_usrs['usrname']; ?></a></td>
            <td class="tbl_data"><?php if (strlen($row_rs_usrs['usrrolename'])>0) {echo $row_rs_usrs['usrrolename']; } else { echo "---"; } ?></td>
             <td class="tbl_data"><?php echo $row_rs_usrs['usrdptname'];?></td>
            <td class="tbl_data">
            <?php
			if (strlen($row_rs_usrs['usrrolename'])>0)
				{
				$sql_role="SELECT usrrolename,userteamzonename,sysprofile FROM sysprofiles 
				INNER JOIN usrrole ON sysprofiles.idsysprofiles=usrrole.sysprofiles_idsysprofiles
				INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 
				WHERE usrrole.idusrrole=".$row_rs_usrs['usrrole_idusrrole']." LIMIT 1";
				$res_role=mysql_query($sql_role);
				$fet_role=mysql_fetch_array($res_role);
				$num_role=mysql_num_rows($res_role);
				//echo $sql_role;
					if ($num_role > 0)
						{
						echo $fet_role['sysprofile'];
						} else {
						echo "---";
						}
				}
			?></td>
            <td class="tbl_data">
            <?php
			if ( (isset($num_role)) && ($num_role > 0))
						{
						echo $fet_role['userteamzonename'];
						} else {
						echo "---";
						}
			?>
            </td>
            <td class="tbl_data">
              <?php echo $row_rs_usrs['utitle']; ?> <?php echo $row_rs_usrs['lname']; ?>, <?php echo $row_rs_usrs['fname']; ?></td>
            <td class="tbl_data">
            <?php if (strlen($row_rs_usrs['usrphone'])>0) { echo $row_rs_usrs['usrphone'];} else { echo "---"; } ?> </td>
            <td class="tbl_data">
            <?php echo $row_rs_usrs['usremail']; ?> </td>
            <td class="tbl_data"><?php if ($row_rs_usrs['acstatus']==1){ echo "<span style=\"color:#009900\">".$lbl_statusactive."</span>"; } else { echo "<span style=\"color:#FF0000\">".$lbl_statusactivenot."</span>"; } ?></td>
            <td class="tbl_data">
              <?php if ( ($row_rs_usrs['lastaccess']!='0000-00-00 00:00:00') && ($row_rs_usrs['lastaccess']!='') ) { echo date("D, j M Y H:i",strtotime($row_rs_usrs['lastaccess'])); } else { echo "---"; } ?>              </td>
          </tr>
          <?php 
		  $i++;
		  } while ($row_rs_usrs = mysql_fetch_assoc($rs_usrs)); ?>
          <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>
         <tr>
        	<td colspan="9">
            	<table border="0" width="100%">
                	<tr>
                    	<td width="30%" class="text_body">
Records <?php echo ($startRow_rs_usrs + 1) ?> to <?php echo min($startRow_rs_usrs + $maxRows_rs_usrs, $totalRows_rs_usrs) ?> of <?php echo $totalRows_rs_usrs ?> </td>
                        <td width="70%" class="text_body"><?php 
# variable declaration
$prev_rs_usrs = "&laquo; previous";
$next_rs_usrs = "next &raquo;";
$separator = " | ";
$max_links = 10;
$pages_navigation_rs_usrs = buildNavigation($pageNum_rs_usrs,$totalPages_rs_usrs,$prev_rs_usrs,$next_rs_usrs,$separator,$max_links,true); 

print $pages_navigation_rs_usrs[0]; 
?>
                        <?php print $pages_navigation_rs_usrs[1]; ?> <?php print $pages_navigation_rs_usrs[2]; ?> </td>
                    </tr>
                </table>
                <?php } else { ?>
                <div class="msg_warning">
                <?php echo $msg_no_record;?>
                </div>
                <?php } ?>
                </td>
        </tr>
    </table>
  </div>
</div>
<?php
mysql_free_result($rs_usrs);
?>
