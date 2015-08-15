<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['thisgroup']))
	{
	$_SESSION['thisgroupid']=mysql_escape_string(trim($_GET['thisgroup']));
	}
//echo $_SESSION['thisgroupid'];
if (isset($_GET['groupname']))
	{
	$_SESSION['thisgroupname']=mysql_escape_string(trim($_GET['groupname']));
	}

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="updategroup"))
	{
	//clean up
	$pgroupname=mysql_escape_string(trim($_POST['groupname']));
	$pgroupdesc=mysql_escape_string(trim($_POST['groupdesc']));
	
	//validate and check for duplicate
	$sql_duplicategroup="SELECT idusrgroup FROM usrgroup WHERE usrgroupname='".$pgroupname."' AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_duplicategroup=mysql_query($sql_duplicategroup);
	$num_duplicategroup=mysql_num_rows($res_duplicategroup);
	
	if (strlen($pgroupname) < 1)
		{
		$error1="<div class=\"msg_warning\">".$msg_warning_nogroup."</div>";
		}
	
	if ((!isset($error1)) && ($num_duplicategroup > 0) && ($_SESSION['thisgroupname']!=$pgroupname) )
		{
		$error2="<div class=\"msg_warning\">".$msg_warning_duplicategroup."</div>";
		}
		
	//process it
	if ( (!isset($error1)) && (!isset($error2))  )
		{
		$sql_newrole="UPDATE usrgroup SET 
		usrgroupname='".$pgroupname."',
		usrgroupdesc='".$pgroupdesc."',
		modifiedby='".$_SESSION['MVGitHub_idacname']."',
		modifiedon='".$timenowis."'
		WHERE idusrgroup='".$_SESSION['thisgroupid']."' AND usrteam_idusrteam='".$_SESSION['MVGitHub_idacteam']."' LIMIT 1";
		
		mysql_query($sql_newrole);
		
		$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
		}
	} //close form action
	
// group details
$sql_groupdetails="SELECT * FROM usrgroup WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND idusrgroup=".$_SESSION['thisgroupid']." LIMIT 1";
$res_groupdetails=mysql_query($sql_groupdetails);
$num_groupdetails=mysql_num_rows($res_groupdetails);
	
	if ($num_groupdetails < 1)
		{
		echo "<div>".$msg_warn_contactadmin.$ec100."</div>";
		exit;
		}
$fet_groupdetails=mysql_fetch_array($res_groupdetails);
?>
<div >

	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
      	<div>
    <?php
		if (isset($_GET['msg']))
			{
			$msg=mysql_escape_string(trim($_GET['msg']));
			}
	 //if (isset($_GET['msg'])) { echo "<div class=\"msg_success\">".$_GET['msg']."</div>"; } 
	?>
        </div>
    <div>
    <?php
	//if (isset($msg)) { echo $msg; } 
	
	if ( (isset($error1)) || (isset($error2))  )
		{
		if (isset($error1)) { echo $error1; }
		if (isset($error2)) { echo $error2; }
		}
	?>
    </div>
<div style="padding:20px 0px 0px 0px">
    <div class="tbl_h2">
    <?php echo $lbl_groupdetails;?>
    </div>
    <div>
	<form method="post" action="" name="newrole" enctype="multipart/form-data">
        	<table border="0" cellpadding="3" cellspacing="0" class="border_thick">
            <tr>
            <td height="30" colspan="2" class="table_header">
			<?php echo $lbl_editgroup;?>
            </td>
            </tr>
		  <tr>
               <td width="171" height="40" class="tbl_data">
               <strong><?php echo $lbl_group;?></strong>
               </td>
               <td width="352" height="40"  class="tbl_data">
               <input type="text" name="groupname" maxlength="100" value="<?php if (isset($_GET['groupname'])) { echo $_GET['groupname']; } else { echo $fet_groupdetails['usrgroupname']; }?>" size="40">               </td>
			</tr>
                <tr>
               	  <td width="171" height="40" class="tbl_data" valign="top">
                    <strong><?php echo $lbl_description;?></strong>
                    </td>
                  <td height="40"  class="tbl_data" valign="top">
                  <textarea name="groupdesc" rows="4" cols="30" id="groupdesc"><?php echo $fet_groupdetails['usrgroupdesc'];?></textarea>
                  </td>
			  </tr>
                 <tr>
                 	<td height="50"></td>
               	   <td>
                    <table border="0" style="margin:5px 10px 5px 0px">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['newrole'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="updategroup" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>
                        </td>
				</tr>
	</table>   
	</form> 
	</div>
    <div>
  
    	<div class="tbl_h2">
        <?php echo $lbl_groupalloc; ?>
        </div>
        <div>
        <?php
if (isset($_GET['do']))
	{
		if ($_GET['do']=="assign_role")
			{
			$roleid=mysql_escape_string(trim($_GET['id']));
			//first check if a similar record already exists
			$sql_exists = "SELECT * FROM link_userrole_usergroup WHERE usrrole_idusrrole=".$roleid." AND usrgroup_idusrgroup=".$_SESSION['thisgroupid']." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
			$res_exists = mysql_query($sql_exists);
			$num_exists = mysql_num_rows($res_exists);
			
			if ($num_exists ==0) //if no record exists, then...
				{
			$sql_actor="INSERT INTO link_userrole_usergroup (usrteam_idusrteam,usrrole_idusrrole,usrgroup_idusrgroup,createdby,createdon) 
			VALUES ('".$_SESSION['MVGitHub_idacteam']."','".$roleid."','".$_SESSION['thisgroupid']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_actor);
			//echo $sql_actor;
				}
			
			}
			
		if ($_GET['do']=="unassign_role")
			{
			$roleid=mysql_escape_string(trim($_GET['id']));
			$sql_actor="DELETE FROM link_userrole_usergroup WHERE usrrole_idusrrole=".$roleid." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND usrgroup_idusrgroup =".$_SESSION['thisgroupid']." LIMIT 1";
			mysql_query($sql_actor);
			//echo $sql_actor;
			}
	}

//list the roles in this organisation and their respective users
$sql_teamrole="SELECT idusrrole,usrrolename,usrroledesc,usrname,utitle,fname,lname,acstatus,link_userrole_usergroup.usrrole_idusrrole,idlink_userac_usergroup as linkid FROM usrrole 
LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole 
LEFT JOIN link_userrole_usergroup ON usrrole.idusrrole=link_userrole_usergroup.usrrole_idusrrole 
INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
WHERE 
usrrole.idusrrole NOT
IN (
SELECT usrrole_idusrrole
FROM link_userrole_usergroup
WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."
AND usrgroup_idusrgroup=".$_SESSION['thisgroupid']."
) AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." GROUP BY idusrrole ORDER BY usrrolename ASC";
$res_teamrole=mysql_query($sql_teamrole);
$fet_teamrole=mysql_fetch_array($res_teamrole);
$num_teamrole=mysql_num_rows($res_teamrole);
//echo $sql_teamrole;
//exit;
?>
<div style="padding:15px 5px 10px 5px">
	<span class="msg_instructions"><?php echo $ins_rolegroup;?></span></div>
<div>
<table border="0" width="790" cellpadding="1" cellspacing="0">
	<tr>
    	<td valign="top" class="hline">
        	<table border="0" cellpadding="2" cellspacing="0" width="395">
            	<tr>
                	<td class="tbl_h"><?php echo $lbl_role;?></td>
                    <td class="tbl_h" ><?php echo $lbl_username;?></td>
                    <td class="tbl_h" >&gt;</td>
                </tr>
                <?php
				//echo "linkid >>".$fet_teamrole['linkid']
					do {
						//if (($num_teamrole>0) && ( ($fet_teamrole['linkid']=="") || ($fet_teamrole['linkid']<1) ) )//list only if not already listed on the other side
						if ($num_teamrole>0)//list only if not already listed on the other side
						{
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td class="tbl_data"><span title="<?php echo $fet_teamrole['usrroledesc'];?>"><?php echo $fet_teamrole['usrrolename'];?></span></td>
                    <td class="tbl_data">
                    <?php
					if (strlen($fet_teamrole['usrname'])>0) { ?>
                    <span title="<?php echo $fet_teamrole['utitle']." ".$fet_teamrole['lname']." , ".$fet_teamrole['fname'];?>"><?php echo $fet_teamrole['usrname'];?></span>
                    <?php } else { echo "---"; } ?>
                    </td>
                    <td width="30" class="tbl_data">
                    <a title="<?php echo $ins_assignresp; ?>" href="<?php echo $_SERVER['PHP_SELF'];?>?do=assign_role&amp;id=<?php echo $fet_teamrole['idusrrole'];?>" id="button_send_right"></a>
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
						} 
					} while ($fet_teamrole=mysql_fetch_array($res_teamrole));
					
				
				if ($num_teamrole < 1)
					{
				?>
              <tr>
                	<td colspan="3">
                    <div class="msg_warning">
                    <?php echo $msg_no_role;?>
                    </div>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </td>
        <td valign="top"  width="395">
        <?php
		$sql_rolesasi = "SELECT idusrrole,usrrolename,usrroledesc,usrname,utitle,fname,lname,acstatus FROM usrrole 
				LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole 
				INNER JOIN link_userrole_usergroup ON usrrole.idusrrole=link_userrole_usergroup.usrrole_idusrrole
				INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
				WHERE usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND usrgroup_idusrgroup=".$_SESSION['thisgroupid']." ORDER BY usrrolename ASC";
				$res_rolesasi = mysql_query($sql_rolesasi);
				$num_rolesasi = mysql_num_rows($res_rolesasi);
				$fet_rolesasi = mysql_fetch_array($res_rolesasi);
				
				if ($num_rolesasi > 0 )
					{
		?>
			<table border="0" cellpadding="2" cellspacing="0" width="100%">
            	<tr>
                	<td width="30" class="tbl_h">&lt;</td>
                  <td class="tbl_h"><?php echo $lbl_role;?></td>
                    <td class="tbl_h"><?php echo $lbl_username;?></td>
                </tr>
                <?php
				
					do {
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td width="30" class="tbl_data"><a href="<?php echo $_SERVER['PHP_SELF'];?>?do=unassign_role&amp;id=<?php echo $fet_rolesasi['idusrrole'];?>" id="button_send_left"></a></td>
                  <td class="tbl_data"><?php echo $fet_rolesasi['usrrolename']?></td>
                    <td class="tbl_data"><?php echo $fet_rolesasi['usrname']?></td>
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
					} while ($fet_rolesasi = mysql_fetch_array($res_rolesasi)); 
				?>
			</table>
				<?php	} else { ?>
            <table>
                <tr>
                	<td colspan="3" align="center" height="40">
                    <span class="msg_warning">
                    <?php echo $msg_select_role; ?>
                    </span>
                    </td>
                </tr>
                <?php } ?>
                </table>
        </td>
    </tr>
</table>
</div>
        </div>
    </div>
</div>
</div>