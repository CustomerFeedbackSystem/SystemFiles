<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//check if this user is global or not for this module :)
$sql_globalperm="SELECT global_access FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$_SESSION['sec_submod']." AND
sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1 ";
$res_globalperm=mysql_query($sql_globalperm);
$fet_globalperm=mysql_fetch_array($res_globalperm);

if ($fet_globalperm['global_access']==1)
	{
	$_SESSION['qry_filter_global']=" usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."  "; //this is global
	$_SESSION['is_global_view']=1;
	} else {
	$_SESSION['qry_filter_global']=" usrteamzone.idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  "; //this is localised
	$_SESSION['is_global_view']=0;
	}

//add search filters here
if ( (isset($_SESSION['userrole_find'])) && (strlen($_SESSION['userrole_find']) > 0) )
	{
	$_SESSION['filter_userrole_find'] =" AND usrrole.usrrolename LIKE '%".$_SESSION['userrole_find']."%' ";
	} else {
	$_SESSION['userrole_find']="";
	$_SESSION['filter_userrole_find'] ="";
	}
if ( (isset($_SESSION['zone'])) && ($_SESSION['zone'] > 0) )
	{
	$_SESSION['filter_zone'] =" AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['zone']." ";
	} else {
	$_SESSION['filter_zone'] ="";
	}
if ( (isset($_SESSION['profile'])) && ($_SESSION['profile'] > 0) )
	{
	$_SESSION['filter_profile'] =" AND usrrole.sysprofiles_idsysprofiles=".$_SESSION['profile']." ";
	} else {
	$_SESSION['filter_profile'] ="";
	}	

//
$_SESSION['parenttabview']=1;

if ((isset($_GET['saction'])) && ($_GET['saction']=="delete_role") )
	{
	$roleid=mysql_escape_string(trim($_GET['recid']));
	
	//first, check if this is the owner of this record
	$sql_owner="SELECT idusrrole FROM usrrole INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND idusrrole=".$roleid." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	$num_owner=mysql_num_rows($res_owner);
	
	if ($num_owner <1)
		{
		echo "<div class=\"msg_warning\">".$msg_warn_contactadmin.$ec100."</div>";
		exit;
		} else {
	//second, if first is ok, check if this record is associated with any other records 
	//check 1
		$sql_linkusrgroup="SELECT idlink_userac_usergroup FROM link_userrole_usergroup WHERE usrrole_idusrrole=".$roleid." LIMIT 1";
		$res_linkusrgroup=mysql_query($sql_linkusrgroup);
		$num_linkusrgroup=mysql_num_rows($res_linkusrgroup);
	//check 2
		$sql_linkusrac="SELECT idusrac FROM usrac WHERE usrrole_idusrrole=".$roleid." LIMIT 1";
		$res_linkusrac=mysql_query($sql_linkusrac);
		$num_linkusrac=mysql_num_rows($res_linkusrac);
	//check 3
		$sql_linkactors="SELECT idwfactors FROM wfactors WHERE usrrole_idusrrole=".$roleid." LIMIT 1";
		$res_linkactors=mysql_query($sql_linkactors);
		$num_linkactors=mysql_num_rows($res_linkactors);
	//check 4
		$sql_linktsks="SELECT idwftasks FROM wftasks WHERE usrrole_idusrrole=".$roleid." LIMIT 1";
		$res_linktsks=mysql_query($sql_linktsks);
		$num_linktsks=mysql_num_rows($res_linktsks);
		
	//then if not, delete
			if ( ($num_linkusrgroup<1) && ($num_linkusrac<1) && ($num_linkactors<1) && ($num_linktsks<1) )
				{
				$sql_deleterole="DELETE FROM usrrole WHERE idusrrole=".$roleid." LIMIT 1";
				mysql_query($sql_deleterole);
				
				$msg="<div>".$msg_changes_saved."</div>";
				} else {
				$msg="<div class=\"msg_warning\">".$msg_warning_opdeny_related."</div>";
				}
	
		} //close if owner of record
	
	} // if delete

#	BuildNav for Dreamweaver MX v0.2
#              10-02-2002
#	Alessandro Crugnola [TMM]
#	sephiroth: alessandro@sephiroth.it
#	http://www.sephiroth.it
#	
#	Function for navigation build ::
function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true)
{
                GLOBAL $maxRows_role,$totalRows_role;
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
					if ($_get_name != "pageNum_role") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_role=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_role) + 1;
					$max_l = ($a*$maxRows_role >= $totalRows_role) ? $totalRows_role : ($a*$maxRows_role);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "<a href=\"$_SERVER[PHP_SELF]?pageNum_role=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_role=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
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

$maxRows_role = 50;
$pageNum_role = 0;
if (isset($_GET['pageNum_role'])) {
  $pageNum_role = $_GET['pageNum_role'];
}
$startRow_role = $pageNum_role * $maxRows_role;

mysql_select_db($database_connSystem, $connSystem);
$query_role = "SELECT idusrrole,usrrolename,userteamzonename,sysprofile FROM usrrole INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone INNER JOIN usrteam ON usrteamzone.usrteam_idusrteam=usrteam.idusrteam  INNER JOIN sysprofiles ON usrrole.sysprofiles_idsysprofiles=sysprofiles.idsysprofiles WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ".$_SESSION['filter_userrole_find']." ".$_SESSION['filter_zone']." ".$_SESSION['filter_profile']." ORDER BY usrrolename ASC";
$query_limit_role = sprintf("%s LIMIT %d, %d", $query_role, $startRow_role, $maxRows_role);
$role = mysql_query($query_limit_role, $connSystem) or die(mysql_error());
$row_role = mysql_fetch_assoc($role);
//echo $query_role;
if (isset($_GET['totalRows_role'])) {
  $totalRows_role = $_GET['totalRows_role'];
} else {
  $all_role = mysql_query($query_role);
  $totalRows_role = mysql_num_rows($all_role);
}
$totalPages_role = ceil($totalRows_role/$maxRows_role)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div style="padding:5px 0px 100px 0px">
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?></div>
    <div style="margin:5px; padding:0px 0px 15px 0px">
    <form method="get" action="" name="find_usrrole" style="margin:0px; padding:3px;">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td width="20%">
    <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=add_role" id="button_userrole"></a>
    		</td>
            
            <td width="80%" style="background-color:#f5f5f5" align="right">
            <?php
			if ($_SESSION['is_global_view']==1)
				{
			?>
           
    <table border="0" cellpadding="0" cellspacing="0" >
    	<tr>
            <td bgcolor="#f5f5f5">
            <input type="hidden" value="3" name="parentviewtab" />
           <input type="hidden" value="<?php echo $_SESSION['sec_mod'];?>" name="mod" />
               <input type="hidden" value="<?php echo $_SESSION['sec_submod'];?>" name="submod" />
            <input type="hidden" value="view_submod" name="uction" />
            <input name="userrole_find" type="text" id="userrole_find" value="" />
            </td>
            <td style="background-color:#f5f5f5"> <select name="zone" id="zone" onChange="getprofile(this.value)">
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
               <td style="background-color:#f5f5f5"><div id="zoneprofilediv">
               <select name="profile"><option value="-1">--All Profiles--</option></select>
               </div>
            </td>
            <td  bgcolor="#f5f5f5">
            <a href="#" id="button_search" onclick="document.forms['find_usrrole'].submit();"></a>
            </td>
		</tr>
	</table>
    
            <?php
			}
			?>
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
        	<span class="tab_off"><a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;parentviewtab=1"><?php echo $lbl_roles;?></a></span>
			 <span class="tab_off"><a href="<?php echo $_SERVER['PHP_SELF'];?>?parentviewtab=4">Vacant Roles</a></span>
            <span class="tab_off"><a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;parentviewtab=2"><?php echo $lbl_groups;?></a></span>
            <span class="tab_on">Search Results</span>            
        </div>
    </div>
    </div>
    <div>
<table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
<?php
if ($totalRows_role>0)
	{
?>
    	<tr>
        	<td class="tbl_h"><?php echo $lbl_role;?></td>
            <td class="tbl_h"><?php echo $lbl_zonename;?></td>
            <td class="tbl_h"><?php echo $lbl_access_profile;?></td>
            <td class="tbl_h"><?php echo $lbl_groups;?></td>
          <td class="tbl_h"><?php echo $lbl_occupied;?></td>
          
          <td class="tbl_h"></td>
      	</tr>
        <tr>
        	<td colspan="6">
       	  <table border="0" width="100%">
                	<tr>
                    	<td width="30%" class="text_body">&nbsp;
Records <?php echo ($startRow_role + 1) ?> to <?php echo min($startRow_role + $maxRows_role, $totalRows_role) ?> of <?php echo $totalRows_role ?> </td>
                      <td width="70%" class="text_body"><?php 
# variable declaration
$prev_role = "« previous";
$next_role = "next »";
$separator = " | ";
$max_links = 10;
$pages_navigation_role = buildNavigation($pageNum_role,$totalPages_role,$prev_role,$next_role,$separator,$max_links,true); 

print $pages_navigation_role[0]; 
?>
                      <?php print $pages_navigation_role[1]; ?> <?php print $pages_navigation_role[2]; ?> </td>
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
            <td class="tbl_data">
              <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;thisrole=<?php echo $row_role['idusrrole'];?>&amp;rolename=<?php echo urlencode($row_role['usrrolename']);?>"><?php echo $i.".&nbsp;".$row_role['usrrolename'];?></a>
            </td>
            <td class="tbl_data">
              <?php echo $row_role['userteamzonename'];?>              </td>
            <td class="tbl_data">
              <?php echo $row_role['sysprofile'];?>            </td>
            <td class="tbl_data">
              <?php 
			//check which groups this role belongs to
			$sql_groups="SELECT usrgroupname FROM link_userrole_usergroup 
			INNER JOIN usrgroup ON link_userrole_usergroup.usrgroup_idusrgroup=usrgroup.idusrgroup
			WHERE usrrole_idusrrole=".$row_role['idusrrole']." ORDER BY usrgroupname ASC";
			$res_groups=mysql_query($sql_groups);
			$num_groups=mysql_num_rows($res_groups);
			
				if ($num_groups<1)
					{
					echo "---";//not configured
					} else {
					echo $num_groups;
					}
			?>            </td>
            <td class="tbl_data" valign="top">
              <?php
			//check which user and user account occupies
			$sql_aco="SELECT usrname,utitle,fname,lname,acstatus FROM usrac WHERE usrrole_idusrrole=".$row_role['idusrrole']." LIMIT 1";
			$res_aco=mysql_query($sql_aco);
			$num_aco=mysql_num_rows($res_aco);
				if ($num_aco > 0)
					{
					$fet_aco=mysql_fetch_array($res_aco);
					echo $fet_aco['usrname']." <small>(".$fet_aco['utitle']." ".$fet_aco['lname'].", ".$fet_aco['fname'].")&nbsp;";
					if ($fet_aco['acstatus']==1)
						{
						echo "<span style=\"color:#009900\">".$lbl_statusactive."</span></small>";
						} else {
						echo "<span style=\"color:#FF0000\">".$lbl_statusactivenot."</span></small>";
						}
					} else {
					echo "---";
					}
			?>              </td>
            <td class="tbl_data">
            <table border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td class="text_body">
                    <small><a href="pop_assign_usertorole.php?roleid=<?php echo $row_role['idusrrole'];?>&amp;tabview=0&amp;keepThis=true&amp;TB_iframe=true&amp;height=<?php echo $_SESSION['tb_height'];?>&amp;width=<?php echo $_SESSION['tb_width'];?>&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox"> <?php if ($num_aco > 0){ echo "Change"; } else { echo "Assign"; }?> User</a></small></td>
                	<td>
              		<a onclick="return confirm('<?php echo $msg_prompt_delete;?>')" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=delete_role&amp;recid=<?php echo $row_role['idusrrole'];?>" id="button_delete_small"></a>
                    </td>
				</tr>
			</table>                    
                    </td>
          </tr>
          <?php 
		  $i++;
		  } while ($row_role = mysql_fetch_assoc($role)); ?>
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
        	<td colspan="6">
       	  <table border="0" width="100%">
                	<tr>
                    	<td width="30%" class="text_body">&nbsp;
Records <?php echo ($startRow_role + 1) ?> to <?php echo min($startRow_role + $maxRows_role, $totalRows_role) ?> of <?php echo $totalRows_role ?> </td>
                      <td width="70%" class="text_body"><?php 
# variable declaration
$prev_role = "« previous";
$next_role = "next »";
$separator = " | ";
$max_links = 10;
$pages_navigation_role = buildNavigation($pageNum_role,$totalPages_role,$prev_role,$next_role,$separator,$max_links,true); 

print $pages_navigation_role[0]; 
?>
                      <?php print $pages_navigation_role[1]; ?> <?php print $pages_navigation_role[2]; ?> </td>
            </tr>
                </table>
            </td>
        </tr>
      <?php } else { ?>
        <tr>
       	  <td colspan="6" height="50" style="padding:30px">
            <span class="msg_warning">No Results Found</span>
          </td>
        </tr>
       <?php } ?>
</table>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($role);
?>
