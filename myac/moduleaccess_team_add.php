<?php
require_once('../assets_backend/be_includes/check_login_easy.php');
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod"><?php echo $fet_heading['submodule']; ?></a>
    </div>
    <div style="padding:10px">
        <div ><span class="msg_instructions"><?php echo $msg_module_add;?></span></div>
        <div style="padding:20px 10px 30px 10px">
        <form method="post" name="module_alloc_team" action="">
        	<table width="642" border="0" cellpadding="0" cellspacing="0" class="border_thick">
<tr>
                    <td colspan="3">
                        <table border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td width="226" class="text_body">
                                <?php echo $lbl_select_team;?>                                </td>
                              <td width="379">
<select name="teamname">
                                	<option value="">-------------</option>
                                   <?php
									$sql_avail="SELECT DISTINCT idusrteam,usrteam_idusrteam,usrteamname FROM usrteam LEFT JOIN systeamaccess ON usrteam.idusrteam=systeamaccess.usrteam_idusrteam ORDER BY usrteamname ASC";
									//echo $sql_avail;
									$res_avail=mysql_query($sql_avail) or die($sql_avail."<br/><br/>".mysql_error());
									$fet_avail=mysql_fetch_array($res_avail);
										do {
									?>
                                    <option value="<?php echo $fet_avail['idusrteam'];?>" <?php if ($fet_avail['usrteam_idusrteam']==$fet_avail['idusrteam']){ echo "disabled  style=\"color:#CCCCCC\""; } ?> ><?php echo $fet_avail['usrteamname'];?></option>
                                    <?php
										} while ($fet_avail=mysql_fetch_array($res_avail));
									?>
                                </select>
                                
                              </td>
                          </tr>
                        </table>
                    </td>
				</tr>
                <tr>
                    <td class="tbl_h" colspan="2">
                   <?php echo $lbl_module;?>                   </td>
                  <td width="404" class="tbl_h">
                    <?php echo $lbl_description;?>                    </td>
              </tr>
              <?php
			  //loop the modules here
			  $sql_listmods="SELECT idsysmodule,modulename,module_desc FROM sysmodule WHERE sys_status=1 ORDER BY listorder ASC";
			  $res_listmods=mysql_query($sql_listmods);
			  $fet_listmods=mysql_fetch_array($res_listmods);
			  $num_listmods=mysql_num_rows($res_listmods);
			  	if ($num_listmods > 0)
					{
				  	do {
			  ?>
                <tr>
                	<td width="22" class="tbl_data">
                    <input type="checkbox" name="mod[]" value="<?php echo $fet_listmods['idsysmodule'];?>">
                    </td>
                  <td width="216" class="tbl_data">
                  <strong><?php echo $fet_listmods['modulename'];?></strong>                  </td>
                  <td height="45" class="tbl_data">
                  <?php echo $fet_listmods['module_desc'];?>                    </td>
              </tr>
                <?php
					} while ($fet_listmods=mysql_fetch_array($res_listmods));
				} else {
				?>
                <div class="msg_warning">
                <?php echo $msg_warn_contactadmin;?>
                </div>
                <?php } ?>
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
            </table>
          </form>
      </div>
    </div>
</div>