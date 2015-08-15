<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
//echo "teste";
if (isset($_GET['thisteam']))
	{
	$_SESSION['thisteamid']=mysql_escape_string(trim($_GET['thisteam']));
	}

if ( (isset($_GET['switch'])) && ($_GET['switch']=="on") )
	{
	$idacc=mysql_escape_string(trim($_GET['idaccess']));
	
	$sql="INSERT INTO systeamaccess (usrteam_idusrteam,sysmodule_idsysmodule,createdby,createdon,permview,perminsert,permupdate,permdelete) 
	VALUES ('".$_SESSION['thisteamid']."','".$idacc."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','1','1','1','1')";
	mysql_query($sql);
	
	$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
	
	}

if ( (isset($_GET['switch'])) && ($_GET['switch']=="off") )
	{
	$idacc=mysql_escape_string(trim($_GET['idaccess']));
	
	$sql="DELETE FROM systeamaccess WHERE usrteam_idusrteam=".$_SESSION['thisteamid']." AND idsysteamaccess=".$idacc." LIMIT 1";
	mysql_query($sql);
	
	$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
	
	}


?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod"><?php echo $fet_heading['submodule']; ?></a>
    </div>
    <div style="padding:10px">
        <div ><span class="msg_instructions"><?php echo $msg_module_add;?></span></div>
        <div>
        <?php if (isset($msg)) { echo $msg; } ?>
        </div>
        <div style="padding:20px 10px 30px 10px">
        <form method="post" name="module_alloc_team" action="">
        	<table width="866" border="0" cellpadding="0" cellspacing="0" class="border_thick">
				<tr>
                    <td height="35" colspan="3" class="msg_welcome">
                   
<?php
$sql_team = "SELECT idusrteam,usrteamtype_idusrteamtype,usrteamname FROM usrteam WHERE idusrteam=".$_SESSION['thisteamid']." LIMIT 1";
$res_team = mysql_query($sql_team);
$num_team = mysql_num_rows($res_team);
$fet_team = mysql_fetch_array($res_team);
echo $fet_team['usrteamname'];
	?>              </td>
			  </tr>
                <tr>
                    <td class="tbl_h" colspan="2">
                   <?php echo $lbl_module;?>
                   </td>
                  <td width="563" class="tbl_h">
                    <?php echo $lbl_description;?>
                    </td>
              </tr>
              <?php
			  //loop the modules here
			  $sql_listmods="SELECT idsysmodule,modulename,module_desc FROM sysmodule WHERE  sys_status=1 ORDER BY listorder ASC";
			  $res_listmods=mysql_query($sql_listmods);
			  $fet_listmods=mysql_fetch_array($res_listmods);
			  $num_listmods=mysql_num_rows($res_listmods);
			  	if ($num_listmods > 0)
					{
				  	do {
			  ?>
                <tr>
                	<td width="22" class="tbl_data">
                    <a name="<?php echo $fet_listmods['idsysmodule'];?>"></a>
                    <?php
					//check for every individual module against the team module access table
					$sql_access="SELECT idsysteamaccess FROM systeamaccess WHERE usrteam_idusrteam=".$_SESSION['thisteamid']." AND sysmodule_idsysmodule=".$fet_listmods['idsysmodule']." LIMIT 1";
					$res_access=mysql_query($sql_access);
					$num_access=mysql_num_rows($res_access);
					$fet_access=mysql_fetch_array($res_access);
					//echo $num_access;
					if ($num_access > 0)
						{
						echo "<a href=\"".$_SERVER['PHP_SELF']."?idaccess=".$fet_access['idsysteamaccess']."&switch=off#".$fet_listmods['idsysmodule']."\" id=\"button_check_on\"><span>Click Here</span></a>";
						} else {
						echo "<a href=\"".$_SERVER['PHP_SELF']."?idaccess=".$fet_listmods['idsysmodule']."&switch=on#".$fet_listmods['idsysmodule']."\" id=\"button_check\"><span>Click Here</span></a>";
						}
					//echo $num_access;
					?>
                    </td>
                  <td width="216" class="tbl_data">
                  <strong><?php echo $fet_listmods['modulename'];?></strong>
                  </td>
                  <td height="45" class="tbl_data">
                  <em><?php echo $fet_listmods['module_desc'];?></em>
                  </td>
              </tr>
                <?php
					} while ($fet_listmods=mysql_fetch_array($res_listmods));
				} else {
				?>
                <div class="msg_warning">
                <?php echo $msg_warn_contactadmin;?>
                </div>
                <?php } ?>
                <!--
                <tr>
                    <td colspan="3">
                    	<table border="0" style="margin:5px 10px 5px 20px">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['module_alloc_team'].submit()" id="button_save"></a>
                                </td>
                                <td style="padding:0px 0px 0px 10px">
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                -->
            </table>
          </form>
      </div>
    </div>
</div>