<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['profile_find'])) //do a search for a profile with this or similar name
	{
	$search_term=mysql_escape_string(trim($_GET['profile_find']));
	$_SESSION['filter_profile']=" AND sysprofile LIKE '%".$search_term."%' ";
	} else {
	$_SESSION['filter_profile']='';
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

if ( (isset($_SESSION['filter_region'])) && ($_SESSION['filter_region']>0) )
	{
	$_SESSION['qry_filter_global']=" AND usrteamzone.idusrteamzone=".$_SESSION['filter_region']."  "; //this is localised
	} else {
	$_SESSION['qry_filter_global']=" ";
	}

if ( (isset($_GET['saction'])) && ($_GET['saction']=="delete_profile") )
	{
	
	$profileid=mysql_escape_string(trim($_GET['profile']));
	
	//first, check that this profile belongs to this user
	$sql_checkrecord="SELECT idsysprofiles FROM sysprofiles
	INNER JOIN usrteamzone ON sysprofiles.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
	WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND idsysprofiles=".$profileid." LIMIT 1";
	$res_checkrecord=mysql_query($sql_checkrecord);
	$num_checkrecord=mysql_num_rows($res_checkrecord);
	
	if ($num_checkrecord < 1)//not your record - warn
		{
		echo "<div class=\"msg_warning\">".$ec100."</div>";
		exit;
		}
	
	//then, check that this profile is not being used elsewhere namely
	//1-submodules alocation on sysprofileaccess table
	$sql_chk1="SELECT idsystemprofileaccess FROM systemprofileaccess WHERE sysprofiles_idsysprofiles=".$profileid." LIMIT 1";
	$res_chk1=mysql_query($sql_chk1);
	$num_chk1=mysql_num_rows($res_chk1);
	
	//2-usrrole table
	$sql_chk2="SELECT idusrrole FROM usrrole WHERE 	sysprofiles_idsysprofiles=".$profileid." LIMIT 2";
	$res_chk2=mysql_query($sql_chk2);
	$num_chk2=mysql_num_rows($res_chk2);
	
	//if ok, then delete
	if (($num_chk1<1) && ($num_chk1<2) )
		{
		$sql_delete="DELETE FROM sysprofiles WHERE idsysprofiles=".$profileid." LIMIT 1";
		mysql_query($sql_delete);
		
		$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
		} else {
		$msg="<div class=\"msg_warning\">".$ec12."</div>";
		}//close delete
	
	//show message
	}
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div style="margin:5px">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
        	<td width="33%">
   			 <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=add_userprof_access" id="button_newuseraccess"></a>
    		</td>
            <td width="33%">
            
            </td>
            <td width="33%">
             <form method="get" action="" name="find_usrprofile" style="margin:0px">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    <td bgcolor="#f5f5f5">
            <input type="hidden" value="3" name="parentviewtab" />
           <input type="hidden" value="<?php echo $_SESSION['sec_mod'];?>" name="mod" />
           <input type="hidden" value="<?php echo $_SESSION['sec_submod'];?>" name="submod" />
            <input type="hidden" value="view_submod" name="uction" />
            <input type="text" name="profile_find" />
            </td>
            <td  bgcolor="#f5f5f5">
            <a href="#" id="button_search" onclick="document.forms['find_usrprofile'].submit();"></a>
            </td>
                    </tr>
                </table>
                </form>
            </td>
    </tr>
    </table>
   
    </div>
    <div>
    <?php
	if (isset($msg)) { echo $msg; } 
	?>
    </div>
    <div>
    <div class="tab_area">
        	<span class="tab_off"><a href="<?php echo $_SERVER['PHP_SELF'];?>?&amp;parentviewtab=1">All Access Profiles</a></span>
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
    <div>
    <table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
    	<tr>
        	<td class="tbl_h"><?php echo $lbl_access_profile;?></td>
            <td class="tbl_h"><?php echo $lbl_zonename;?></td>
            <td class="tbl_h"><?php echo $lbl_roles?></td>
            <td class="tbl_h"><?php echo $lbl_modules;?></td>
            <td class="tbl_h"></td>
      </tr>
        <?php
		$sql_profiles = "SELECT idsysprofiles,usrteamzone_idusrteamzone,sysprofile,userteamzonename FROM sysprofiles
		INNER JOIN usrteamzone ON sysprofiles.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 
		WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ".$_SESSION['filter_profile']." ORDER BY userteamzonename,sysprofile ASC";
		$res_profiles = mysql_query($sql_profiles);
		$num_profiles = mysql_num_rows($res_profiles);
		$fet_profiles = mysql_fetch_array($res_profiles);
		//echo $sql_profiles;
		$i=1;
		if ($num_profiles >0 )
			{
			do {
		?>
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td class="tbl_data">
			<a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=edit_submod&amp;profile=<?php echo $fet_profiles['idsysprofiles'];?>"><?php echo $i.".&nbsp;".$fet_profiles['sysprofile'];?></a>
            </td>
            <td class="tbl_data">
            <?php echo $fet_profiles['userteamzonename'];?>
            </td>
            <td class="tbl_data">
			<?php 
			//roles
			$sql_roles="SELECT count(*) as no_of_roles FROM usrrole WHERE sysprofiles_idsysprofiles=".$fet_profiles['idsysprofiles']."";
			$res_roles=mysql_query($sql_roles);
			$fet_roles=mysql_fetch_array($res_roles)
			?>
          <a href="">[ <?php echo $fet_roles['no_of_roles']; ?> ]</a>
          </td>
            <td class="tbl_data" valign="top">
            <?php 
			//roles
			$sql_mods="SELECT count(*) as no_of_mods FROM systemprofileaccess WHERE sysprofiles_idsysprofiles=".$fet_profiles['idsysprofiles']."";
			$res_mods=mysql_query($sql_mods);
			$fet_mods=mysql_fetch_array($res_mods)
			?>
           <?php echo $fet_mods['no_of_mods']; ?>
           </td>
           <td class="tbl_data">
           <a onclick="return confirm('<?php echo $msg_prompt_delete;?>');" id="button_delete_small" href="<?php echo $_SERVER['PHP_SELF'];?>?saction=delete_profile&amp;profile=<?php echo $fet_profiles['idsysprofiles'];?>">
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
        <?php
			$i++;
			} while ($fet_profiles = mysql_fetch_array($res_profiles));
		} else {
		?>
        <tr>
        	<td colspan="5" height="50">
            <span class="msg_warning"><?php echo $msg_noprofiles;?></span>
            </td>
        </tr>
        <?php
		}
		?>
    </table>
  </div>
</div>