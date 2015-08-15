<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['zoneid']))
	{
	$_SESSION['thiszone']=mysql_escape_string(trim($_GET['zoneid']));
	}

if (isset($_GET['zonename']))
	{
	$_SESSION['thiszonename']=urldecode(mysql_escape_string(trim($_GET['zonename'])));
	}

$sql_zonedetails = "SELECT * FROM usrteamzone 
INNER JOIN loctowns ON usrteamzone.loctowns_idloctowns=loctowns.idloctowns 
INNER JOIN usrteam ON usrteamzone.usrteam_idusrteam = usrteam.idusrteam
WHERE idusrteamzone=".$_SESSION['thiszone']." LIMIT 1";
$res_zonedetails = mysql_query($sql_zonedetails);
$fet_zonedetails = mysql_fetch_array($res_zonedetails);
$num_zonedetails = mysql_num_rows($res_zonedetails);

if ((isset($_POST['formaction'])) && ($_POST['formaction']=="updatezone") )
	{
	//clean up the data
	$pteamz=mysql_escape_string(trim($_POST['teamnamez']));
	$pcountry=mysql_escape_string(trim($_POST['country']));
	$pregion=mysql_escape_string(trim($_POST['region']));
	$pcounty=mysql_escape_string(trim($_POST['county']));
	$plocation=mysql_escape_string(trim($_POST['locationtown']));
	$pemail=mysql_escape_string(trim($_POST['emailadd']));
	$pphone=mysql_escape_string(trim($_POST['telephone']));
	$pphysical=mysql_escape_string(trim($_POST['physical']));
	$ppostal=mysql_escape_string(trim($_POST['postal']));
	$plon=mysql_escape_string(trim($_POST['longi']));
	$plat=mysql_escape_string(trim($_POST['latit']));
	
	//validate - check for duplicate zones / for this team or organization
	$sql_duplicate="SELECT userteamzonename FROM usrteamzone WHERE userteamzonename='".$pteamz."' LIMIT 1";
	$res_duplicate=mysql_query($sql_duplicate);
	$num_duplicate=mysql_num_rows($res_duplicate);
		
	
	if (strlen($pteamz)<1)
		{
		$error1="<div class=\"msg_warning\">".$msg_warning_teamzone."</div>";
		}
	
	if ( ( ($num_duplicate>0) && (!isset($error_1)) ) && ($_SESSION['thiszonename']!=$pteamz) )
		{
		$error2="<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
		}
	
	if ($pcountry<1)
		{
		$error3="<div class=\"msg_warning\">".$msg_warning_country."</div>";
		}
	
	if ($pregion<1)
		{
		$error4="<div class=\"msg_warning\">".$msg_warning_region."</div>";
		}
		
	if ($pcounty<1)
		{
		$error5="<div class=\"msg_warning\">".$msg_warning_county."</div>";
		}
		
	if (strlen($plocation)<1)
		{
		$error6="<div class=\"msg_warning\">".$msg_warning_location."</div>";
		}
	
	if (strlen($pemail)<6)
		{
		$error7="<div class=\"msg_warning\">".$msg_warning_email."</div>";
		}

	//add extra validation for situations where there is an Admin Default Role for where number of zone < 1
	if ($_SESSION['number_of_zones'] < 1 ) 
		{
		$pusrac = trim($_POST['usrname']);
		$pusrpass = trim($_POST['usrpass']);
		$pcusrpass = trim($_POST['cusrpass']);
		$pfname = trim($_POST['fname']);
		$plname = trim($_POST['lname']);
		$puseremail = trim($_POST['useremail']);
		$puserphone = trim($_POST['userphone']);
		
		if (strlen($pusrac)<6)
			{
			$error8="<div class=\"msg_warning\">".$msg_warning_ac."</div>";
			}
			
		if (strlen($pusrpass)<8)
			{
			$error9="<div class=\"msg_warning\">".$msg_warning_shortpwd."</div>";
			}
			
		if ( (strlen($pusrpass)<7) && ($pusrpass!=$pcusrpass) )
			{
			$error10="<div class=\"msg_warning\">".$msg_warning_matchpwd."</div>";
			}
		if (strlen($pfname)<1)
			{
			$error11="<div class=\"msg_warning\">".$msg_warning_fname."</div>";
			}
		if (strlen($plname)<1)
			{
			$error12="<div class=\"msg_warning\">".$msg_warning_lname."</div>";
			}
		if (strlen($puseremail)<6)
			{
			$error13="<div class=\"msg_warning\">".$msg_warning_useremail."</div>";
			}
		//check if the ac name is unique
		$sql_acunique="SELECT usrname FROM usrac WHERE usrname='".$pusrac."' LIMIT 1";
		$res_acunique=mysql_query($sql_acunique);
		$num_acunique=mysql_num_rows($res_acunique);
		
		if ($num_acunique>0)
			{
			$error14="<div class=\"msg_warning\">".$msg_warning_actaken."</div>";
			}
		
	} //close number of zones
	
	
	
	//check if there is no error to proceed
	if ( (!isset($error1)) && (!isset($error2)) && (!isset($error3)) && (!isset($error4)) && (!isset($error5)) && (!isset($error6)) && (!isset($error7)) && (!isset($error8)) && (!isset($error9)) && (!isset($error10)) && (!isset($error11)) &&  (!isset($error12)) &&  (!isset($error13)) &&  (!isset($error14))  )
		{
		//first, check if that location exists in the db and pick the latitude and longitude and location id
		$sql_coord="SELECT idloctowns,lng,lat FROM loctowns WHERE locationname='".$plocation."' LIMIT 1";
		$res_coord=mysql_query($sql_coord);
		$num_coord=mysql_num_rows($res_coord);
		$fet_coord=mysql_fetch_array($res_coord);

		
		if ($num_coord > 0)
			{
			$lng=$fet_coord['lng'];
			$lat=$fet_coord['lat'];
			$locid=$fet_coord['idloctowns'];
			//echo $locid;
			} else {//if it is not, then create one, and retrieve the id 
			
			$sql_newloc="INSERT INTO loctowns(loccountry_idloccountry,locationname,lng,lat,createdby,createdon,is_valid) 
			VALUES ('".$pcountry."','".$plocation."','".$plon."','".$plat."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','1')";
			mysql_query($sql_newloc);
					
			//retrieve
			$sql_recoord="SELECT idloctowns,lng,lat FROM loctowns WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idloctowns DESC LIMIT 1";
			$res_recoord=mysql_query($sql_recoord);
			$num_recoord=mysql_num_rows($res_recoord);
			$fet_recoord=mysql_fetch_array($res_recoord);
			
			$lng=$fet_recoord['lng'];
			$lat=$fet_recoord['lat'];
			$locid=$fet_recoord['idloctowns'];	
			}

		//now, insert the new record for this zone
		$sql_newzone="UPDATE usrteamzone SET 
		userteamzonename='".$pteamz."',
		loccountry_idloccountry='".$pcountry."',
		locregion_idlocregion='".$pregion."',
		loccounty_idloccounty='".$pcounty."',
		loctowns_idloctowns='".$locid."',
		teamzonephone='".$pphone."',
		teamzoneemail='".$pemail."',
		physicaladdress='".$pphysical."',
		postaladdress='".$ppostal."',
		lat='".$lat."',
		lng='".$lng."',
		modifiedby='".$_SESSION['MVGitHub_userteamzoneid']."',
		modifiedon='".$timenowis."' WHERE idusrteamzone=".$_SESSION['thiszone']." LIMIT 1";
		mysql_query($sql_newzone);

		//retrieve id
		$sql_zoneid="SELECT idusrteamzone FROM usrteamzone WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idusrteamzone DESC LIMIT 1";
		$res_zoneid=mysql_query($sql_zoneid);
		$fet_zoneid=mysql_fetch_array($res_zoneid);
		
			$msg_success="<div class=\"msg_success\">".$msg_changes_saved."</div>";
			
		//	header('location:'.$_SERVER['PHP_SELF'].'?uction=view_submod');
		//	exit;
			
		} //close if no error
	} //close this form action
?>
<div>
	<div class="bg_section">
    <?php echo $_SESSION['lblmodule']; ?> &raquo; <?php echo $_SESSION['lblsubmodule']; ?>
    </div>
   	<div class="tab_area">
        	<span class="tab">
           <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;thisteam=<?php echo $_SESSION['thisteamid'];?>"><?php echo $lbl_teamdetails;?></a>
            </span>
            <span class="tab_on">
            <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_subsubmod&amp;submod=18&amp;parentviewtab=2"><?php echo $lbl_zone;?>
             <?php
			//count zones
			$sql_nozones="SELECT count(*) as zones FROM usrteamzone WHERE usrteam_idusrteam=".$_SESSION['thisteamid']."";
			$res_nozones=mysql_query($sql_nozones);
			$fet_nozones=mysql_fetch_array($res_nozones);
			//echo $sql_nozones;
			echo " <strong>[".$fet_nozones['zones']."]</strong>";
			?>
            </a>
            </span>
	</div>
    <div>
    <?php
	if (isset($msg_success)) { echo $msg_success; } 
 		if ( (isset($error1)) || (isset($error2)) || (isset($error3)) || (isset($error4)) || (isset($error5)) || (isset($error6)) || (isset($error7)) || (isset($error8)) || (isset($error9)) || (isset($error10)) || (isset($error11)) ||  (isset($error12)) ||  (isset($error13)) ||  (isset($error14))  )
		{
			if (isset($error1)) { echo $error1; }
			if (isset($error2)) { echo $error2; }
			if (isset($error3)) { echo $error3; }
			if (isset($error4)) { echo $error4; }
			if (isset($error5)) { echo $error5; }
			if (isset($error6)) { echo $error6; }
			if (isset($error7)) { echo $error7; }
			if (isset($error8)) { echo $error8; }
			if (isset($error9)) { echo $error9; }
			if (isset($error10)) { echo $error10; }
			if (isset($error11)) { echo $error11; }
			if (isset($error12)) { echo $error12; }
			if (isset($error13)) { echo $error13; }
			if (isset($error14)) { echo $error14; }
		}
	?>
    </div>
    <div style="padding:15px 10px 50px 10px">
<form method="post" action="" name="newteamzone" enctype="multipart/form-data">
        <table border="0">
        	<tr>
            	<td width="590" valign="top">
               	  <table border="0" cellpadding="3" cellspacing="0">
		  <tr>
               <td width="169" height="40" class="tbl_data">
               <strong><?php echo $lbl_zonename;?></strong>               </td>
               <td width="404" height="40"  class="tbl_data">
               <input type="text" name="teamnamez" maxlength="100" value="<?php if (isset($_GET['teamnamez'])) { echo $_GET['teamnamez']; } else { echo $fet_zonedetails['userteamzonename']; }?>" size="40">               </td>
			</tr>
            <tr>
               <td width="169" height="40" class="tbl_data">
               <strong><?php echo $lbl_team;?></strong>
               </td>
               <td height="40"  class="tbl_data"><?php echo $fet_zonedetails['usrteamname'];?></td>
			</tr>
                <tr>
                	<td width="169" height="40" class="tbl_data">
                    <strong><?php echo $lbl_country;?></strong>                    </td>
                  <td height="40"  class="tbl_data">
                  <?php
					$sql_country="SELECT idloccountry,countryname FROM loccountry ORDER BY countryname ASC";
					$res_country=mysql_query($sql_country);
					$num_country=mysql_num_rows($res_country);
					$fet_country=mysql_fetch_array($res_country);
						if ($num_country < 1)
							{
							echo "<div class=\"msg_warninng\">".$msg_warn_contactadmin."</div>";
							exit;
							}
					?>
                    <select name="country" id="country" onChange="getregion(this.value)">
                    <option value="-1">---</option>
                  	<?php 
					do {
					?>
                    <option <?php if ( (isset($_POST['country'])) && ($_POST['country']==$fet_country['idloccountry'])) { echo "selected=\"selected\""; } else if ($fet_zonedetails['loccountry_idloccountry']==$fet_country['idloccountry']) { echo "selected=\"selected\""; } ?> value="<?php echo $fet_country['idloccountry'];?>"><?php echo $fet_country['countryname'];?></option>
                    <?php } while ($fet_country=mysql_fetch_array($res_country)); ?>
                  </select>                  </td>
			  </tr>
                <tr>
                	<td width="169" height="40" class="tbl_data">
                    <strong><?php echo $lbl_region;?></strong></td>
               	  <td height="40"  class="tbl_data">
                    <div id="regiondiv">
                      <select name="region">
                        <?php if (isset($_POST['region']))
						{
						$sql_region_selected="SELECT idlocregion,loccountry_idloccountry,locregionname FROM locregion WHERE idlocregion=".trim($_POST['region'])." LIMIT 1";
						} else {
						$sql_region_selected="SELECT idlocregion,loccountry_idloccountry,locregionname FROM locregion WHERE idlocregion=".$fet_zonedetails['locregion_idlocregion']." LIMIT 1";
						}
						$res_region_selected=mysql_query($sql_region_selected);
						$fet_region_selected=mysql_fetch_array($res_region_selected);
			echo "<option value=\"".$fet_region_selected['idlocregion']."\">".$fet_region_selected['locregionname']."</option>";
						?>
                        <option value="">---</option>
                      </select>
                    </div>                  </td>
			  </tr>
                 <tr>
                	<td width="169" height="40" valign="top" class="tbl_data">
                    <strong><?php echo $lbl_county;?></strong></td>
                   <td height="40" class="tbl_data">
                    <div id="countydiv">
                    <select name="county" >
                    <?php if (isset($_POST['county']))
						{
						$sql_county_selected="SELECT idloccounty,locregion_idlocregion,loccountyname,liststatus FROM loccounty WHERE idloccounty=".trim($_POST['county'])." AND locregion_idlocregion=".trim($_POST['region'])." LIMIT 1";
						} else {
						$sql_county_selected="SELECT idloccounty,locregion_idlocregion,loccountyname,liststatus FROM loccounty WHERE idloccounty=".$fet_zonedetails['loccounty_idloccounty']." AND locregion_idlocregion=".$fet_zonedetails['locregion_idlocregion']." LIMIT 1";
						}
						$res_county_selected=mysql_query($sql_county_selected);
						$fet_county_selected=mysql_fetch_array($res_county_selected);
			echo "<option value=\"".$fet_county_selected['idloccounty']."\">".$fet_county_selected['loccountyname']."</option>";
						 ?>
                        <option value="">---</option>
                    </select>
                    </div>                   </td>
			  </tr>
               <tr>
                	<td width="169" height="40" valign="top" class="tbl_data">
                    <strong><?php echo $lbl_town_city;?></strong></td>
                 <td height="40" class="tbl_data">
                   <input type="text" value="<?php if (isset($_POST['locationtown'])) { echo $_POST['locationtown']; } else { echo $fet_zonedetails['locationname'];  } ?>" name="locationtown" id="locationtown" autocomplete="off" maxlength="100" size="35" onkeyup="lookup(this.value);" onblur="fill();" />
					<div class="suggestionsBox3" id="suggestions" style="display:none;">
					<div class="suggestionList" id="autoSuggestionsList">&nbsp;</div>
					</div>                 </td>
			  </tr>
              <tr>
                	<td width="169" height="40" valign="top" class="tbl_data">
                    <strong><?php echo $lbl_email;?></strong></td>
               <td height="40" class="tbl_data">
                   <input type="text" value="<?php if (isset($_POST['emailadd'])) { echo $_POST['emailadd'];} else { echo $fet_zonedetails['teamzoneemail'];  } ?>" name="emailadd" maxlength="60" size="35" />                </td>
			  </tr>
              <tr>
                	<td width="169" height="40" valign="top" class="tbl_data">
                    <strong><?php echo $lbl_telephone;?></strong></td>
               <td height="40" class="tbl_data">
                   <input type="text" name="telephone" value="<?php if (isset($_POST['telephone'])) { echo $_POST['telephone'];} else { echo $fet_zonedetails['teamzonephone'];  }?>" maxlength="60" size="35" />                </td>
			  </tr>
              <tr>
                	<td width="169" height="40" valign="top" class="tbl_data">
                    <strong><?php echo $lbl_physical;?></strong></td>
                <td height="40" class="tbl_data"><textarea cols="30" rows="3" name="physical"><?php if (isset($_POST['physical'])) { echo $_POST['physical'];} else { echo $fet_zonedetails['physicaladdress'];  } ?></textarea></td>
			  </tr>
                <tr>
                	<td width="169" height="40" valign="top" class="tbl_data">
                    <strong><?php echo $lbl_postal;?></strong></td>
                <td height="40" class="tbl_data"><textarea cols="30" rows="3" name="postal"><?php if (isset($_POST['postal'])) { echo $_POST['postal'];}  else { echo $fet_zonedetails['postaladdress'];  }  ?></textarea></td>
			  </tr>
              <tr>
                	<td width="169" height="40" valign="top" class="tbl_data">
                    <strong><?php echo $lbl_mapcoord;?></strong></td>
              <td height="40" class="tbl_data">
                   	<table border="0" cellpadding="0" cellspacing="0">
                    	<tr>
                        	<td class="tbl_data">
							<div><?php echo $lbl_longitude;?></div>
                            <div><input type="text" name="longi" value="<?php if (isset($_POST['longi'])) { echo $_POST['longi'];}  else { echo $fet_zonedetails['lng'];  }  ?>" maxlength="10" size="10"/></div>                            </td>
                            <td  class="tbl_data" style="padding:0px 0px 0px 15px">
							<div><?php echo $lbl_latitude;?></div>
                            <div><input type="text" name="latit" value="<?php if (isset($_POST['latit'])) { echo $_POST['latit'];}  else { echo $fet_zonedetails['lat'];  }  ?>" maxlength="10" size="10"/></div>                            </td>
                            <td style="padding:15px 0px 0px 15px"><a href="" target="_blank" onmousedown="changeURL();" id="button_pickgeo"></a></td>
                        </tr>
                    </table>                </td>
			  </tr>
                 <tr>
                 	<td height="70"> </td>
               	   <td align="left">
                    <table border="0" style="margin:5px 10px 5px 0px">
                        	<tr>
                            	<td>
                                <a href="#" onClick="document.forms['newteamzone'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="updatezone" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onClick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                     </table>                   </td>
				</tr>
	</table>              </td>
              <td width="337" valign="top">&nbsp;</td>
          </tr>
        </table>
    </form> 
  
    </div>
</div>