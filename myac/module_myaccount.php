<?php //require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

//if for update
if ( (isset($_POST['formaction'])) && (isset($_SESSION['frmview'])) && ($_SESSION['frmview']=="EDIT_MODE") && ($_POST['formaction']=="save_details") )
	{
	//clean up the variables
	$f_actitle=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['actitle'])));
	$f_lname=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['lname'])));
	$f_fname=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['fname'])));
	$f_gender=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['usrgender'])));
	$f_usremail=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['usremail'])));
	$f_usrphone=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_POST['usrphone'])));
	
	//check duplicates of emails especially
	$sql_checkemail = "SELECT idusrac,usremail FROM usrac WHERE usrname='".$_SESSION['MVGitHub_acname']."' AND idusrac=".$_SESSION['MVGitHub_idacname']." LIMIT 1";
	$res_checkemail = mysql_query($sql_checkemail);
	$num_checkemail = mysql_num_rows($res_checkemail);
	$fet_checkemail = mysql_fetch_array($res_checkemail);
	//echo $sql_checkemail;
	if ($num_checkemail < 1)
		{
		echo "<div class=\"msg_warning\">".$msg_warn_contactadmin."</div>";
		} else {
		//else if the record exists
		 //then check if this email is already taken
		 	if ($f_usremail!=$_SESSION['MVGitHub_usremail'])
				{
				$sql_duplicate="SELECT usremail FROM usrac WHERE usremail='".$f_usremail."' LIMIT 1 ";
				$res_duplicate=mysql_query($sql_duplicate);
				$num_duplicate=mysql_num_rows($res_duplicate);
				
				if ($num_duplicate > 0)
					{
					$duplicate=1;
					}
					
				}
				
			if (!isset($duplicate))
				{
				//go ahead and update the database
				$sql_update="UPDATE usrac SET 
				utitle='".$f_actitle."',
				fname='".$f_fname."',
				lname='".$f_lname."',
				usrgender='".$f_gender."',
				usremail='".$f_usremail."',
				usrphone='".$f_usrphone."'
				WHERE usrname='".$_SESSION['MVGitHub_acname']."' AND idusrac=".$_SESSION['MVGitHub_idacname']." LIMIT 1";
				mysql_query($sql_update);
				//echo $sql_update;
				
				$_SESSION['MVGitHub_usrtitle'] = $f_actitle;
					$_SESSION['MVGitHub_usrfname'] = $f_fname;
					$_SESSION['MVGitHub_usrlname'] = $f_lname;
					
				echo "<div class=\"msg_success\">".$msg_changes_saved."</div>";
				}
				
			if (isset($duplicate))
				{
				echo "<div class=\"msg_warning\">".$msg_warning_dupemail."</div>";				
				}
		
		}
	
	
	}

//form action by the user to view or edit
if (isset($_GET['frmview']))
	{
	$frmview=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(trim($_GET['frmview'])));
		if ($frmview=="view")
			{
			$_SESSION['frmview']="VIEW_MODE";
			} else if ($frmview=="edit") {
			$_SESSION['frmview']="EDIT_MODE";
			}
	}

if (!isset($_SESSION['frmview']))
	{
	$_SESSION['frmview']="VIEW_MODE";
	}
	
//query db for the details of this user
$sql_myac="SELECT idusrac,usrrolename,usrname,utitle,fname,lname,usrgender,acstatus,usremail,usrphone FROM usrac
INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole
WHERE idusrac=".$_SESSION['MVGitHub_idacname']." AND usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." LIMIT 1";
$res_myac=mysql_query($sql_myac);
$fet_myac=mysql_fetch_array($res_myac);
$num_myac=mysql_num_rows($res_myac);

if ($num_myac < 1)
	{
	echo "<span class=\"msg_warning\">".$msg_warn_contactadmin."</span>";
	exit;
	}
?>
<div>
    <div >
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td width="100%" class="bg_section">
			<?php echo str_replace('_',' ',$fet_heading['modulename']); ?> &raquo; <?php echo $fet_heading['submodule']; ?>
            </td>
		</tr>
    </table>
    </div>
    <div style="padding:10px 0px 10px 0px">
    <table border="0" width="100%">
    	<tr>
        	<td valign="top" width="50%">
            <div class="table_header">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
				<?php echo $lbl_myaccountd;?>
                </td>
                <td width="50">
               <?php
		   		if ($_SESSION['frmview']=="VIEW_MODE") {
		   		?> <a href="<?php echo $_SERVER['PHP_SELF'];?>?frmview=edit" id="button_edit_small"></a>
           		<?php } ?>
                <?php
		   		if ($_SESSION['frmview']=="EDIT_MODE") {
		   		?> <a href="<?php echo $_SERVER['PHP_SELF'];?>?frmview=view" id="button_close_small"></a>
           		<?php } ?>
                </td>
			</tr>
            </table>
            </div>
           <div>
           <?php
		   if ($_SESSION['frmview']=="EDIT_MODE") {
		   ?>
            <form method="post" action="" name="myac">
			<div>
            <table border="0" width="100%" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
            	<tr>
                	<td height="30" class="tbl_data">
                    <?php echo $lbl_username;?>
                    </td>
                  <td height="30" class="tbl_data">
                    <input type="text" name="acname" value="<?php echo $fet_myac['usrname'];?>" style="background-color:#cccccc" readonly="readonly" />
                  </td>
                </tr>
                <tr>
                	<td height="30" class="tbl_data">
                    <?php echo $lbl_role;?>                    </td>
                  <td height="30" class="tbl_data">
                    <input type="text" name="acrole" value="<?php echo $fet_myac['usrrolename'];?>" style="background-color:#CCCCCC" readonly="readonly" />
                  </td>
                </tr>
                <tr>
                	<td class="tbl_data" colspan="2">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
               	    <tr>
                        	<td bgcolor="#E7E7E7" class="text_small">
                              <strong><?php echo $lbl_utitle;?></strong> </td>
                          <td bgcolor="#E7E7E7" class="text_small">
                            <strong><?php echo $lbl_fname;?></strong> </td>
                          <td bgcolor="#E7E7E7" class="text_small">
                            <strong><?php echo $lbl_lname;?></strong> </td>
						</tr>                            
                            <td>
                            <input type="text" name="actitle" value="<?php echo $fet_myac['utitle'];?>" maxlength="20" size="5" />
                            </td>
                            <td>
                            <input type="text" name="fname" value="<?php echo $fet_myac['fname'];?>" maxlength="40" size="13" />
                            </td>
                            <td>
                            <input type="text" name="lname" value="<?php echo $fet_myac['lname'];?>" maxlength="40" size="13" />
                            </td>
                        </tr>
                    </table>
                    </td>
                </tr>
                 <tr>
                	<td height="30" class="tbl_data">
                    <?php echo $lbl_status_ac;?>
					</td>
					<td height="30" class="tbl_data">
                    <input type="text" value="<?php if ($fet_myac['acstatus']==1) { echo "Active"; } else { echo "InActive"; }?>" size="10" style="background-color:#CCCCCC" readonly="readonly" />
					</td>
                </tr>
				<tr>
                	<td height="30" class="tbl_data">
                    <?php echo $lbl_gender;?>
					</td>
					<td height="30" class="tbl_data">
                    <select name="usrgender">
                    <option value="-" <?php if ($fet_myac['usrgender']=="-") { echo "selected=\"selected\""; }?> >---</option>
                    <option value="F" <?php if ($fet_myac['usrgender']=="F") { echo "selected=\"selected\""; }?> >Female</option>
                    <option value="M" <?php if ($fet_myac['usrgender']=="M") { echo "selected=\"selected\""; }?> >Male</option>
                    </select>
					</td>
                </tr>               
                <tr>
                	<td height="30" class="tbl_data">
                    <?php echo $lbl_email;?>
					</td>
					<td height="30" class="tbl_data">
                    <input type="text" maxlength="100" size="20" name="usremail" value="<?php echo $fet_myac['usremail'];?>" />
					</td>
                </tr>
                <tr>
                	<td height="30" class="tbl_data">
                    <?php echo $lbl_mobile;?>
					</td>
					<td height="30" class="tbl_data">
                    <input onKeyUp="res(this,numb);" type="text" maxlength="12" size="20" name="usrphone" autocomplete="off" value="<?php if (strlen($fet_myac['usrphone']) > 4) { echo $fet_myac['usrphone']; } else { echo "2547"; }?>" /></td>
                </tr>
                <tr>
                	<td>
                    </td>
                	<td style="padding:15px 15px 15px 0px">
                    
                    <table border="0" style="margin:0px" cellpadding="0" cellspacing="0">
								<tr>
									<td>
                                    <a href="#" onClick="document.forms['myac'].submit()" id="button_save"></a>
                                    </td>
									<td style="padding:0px 0px 0px 10px"><input type="hidden" value="save_details" name="formaction" />
									<a href="<?php echo $_SERVER['PHP_SELF'];?>?frmview=view" id="button_cancel" onClick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>
                                    </td>
								</tr>
							</table>
                    
                    </td>
                </tr>
            </table>
            
            </div>
            </form>
            <?php
			}
			?>
            <?php
			if ($_SESSION['frmview']=="VIEW_MODE") {
			?>
            <div>
            <table border="0" width="100%" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
            	<tr>
                	<td height="35" class="tbl_data">
                      <strong><?php echo $lbl_username;?></strong></td>
                  <td height="35" class="tbl_data">
                   <?php echo $fet_myac['usrname'];?></td>
                </tr>
                <tr>
                	<td height="35" class="tbl_data">
                      <strong><?php echo $lbl_role;?></strong></td>
                  <td height="35" class="tbl_data">
                    <?php echo $fet_myac['usrrolename'];?></td>
                </tr>
                <tr>
               	  <td colspan="2">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
               	  <tr>
                        	<td width="29%" bgcolor="#E7E7E7" class="text_small">
                              <strong><?php echo $lbl_utitle;?></strong> </td>
                      <td width="37%" bgcolor="#E7E7E7" class="text_small">
                            <strong><?php echo $lbl_fname;?></strong> </td>
                      <td width="34%" bgcolor="#E7E7E7" class="text_small">
                            <strong><?php echo $lbl_lname;?></strong> </td>
					  </tr>                            
                            <td  class="tbl_data">
                           <?php echo $fet_myac['utitle'];?></td>
                            <td  class="tbl_data">
                              <?php echo $fet_myac['fname'];?></td>
                            <td  class="tbl_data">
                           <?php echo $fet_myac['lname'];?></td>
                        </tr>
                    </table>                    </td>
                </tr>
                 <tr>
                	<td height="35" class="tbl_data">
                      <strong><?php echo $lbl_status_ac;?></strong> </td>
					<td height="35" class="tbl_data">
                      <?php if ($fet_myac['acstatus']==1) { echo "Active"; } else { echo "InActive"; }?></td>
                </tr>
				<tr>
                	<td height="35" class="tbl_data">
                      <strong><?php echo $lbl_gender;?></strong> </td>
					<td height="35" class="tbl_data">
                    <?php if ($fet_myac['usrgender']=="-") { echo "Not Indicated"; }?>
                    <?php if ($fet_myac['usrgender']=="F") { echo "Female"; }?>
                    <?php if ($fet_myac['usrgender']=="M") { echo "Male"; }?>                    </td>
                </tr>               
                <tr>
                	<td height="35" class="tbl_data">
                      <strong><?php echo $lbl_email;?></strong> </td>
					<td height="35" class="tbl_data">
                      <?php echo $fet_myac['usremail'];?></td>
                </tr>
                <tr>
                	<td height="35" class="tbl_data">
                      <strong><?php echo $lbl_mobile;?></strong> </td>
					<td height="35" class="tbl_data">
                   <?php echo $fet_myac['usrphone'];?></td>
                </tr>
            </table>
            </div>
            <?php
			}
			?>
           </div>
            </td>
            <td valign="top" width="50%" >
           <!--
            <div class="tbl_h2">
            <?php // echo $lbl_myalerts;?>
            </div>
            <div>
            <?php
			//check how many alerts this user has below
			?>
            </div>
            <div style="text-align:center; padding:40px 10px 40px 10px; background-color:#FFFFFF">
                <div style="margin:0px 0px 20px 0px">
                    <span class="msg_instructions">
                    You have not Configured any Alerts at the moment
                    </span>
                </div>
                <div>
                <span class="text_body">
                <a href="#" onclick="tb_open_new('pop_dashboardsettings.php?title=My_Alerts&amp;&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=300&amp;width=400')">Configure Alerts</a>
                </span>
                </div>
            </div>
            -->
            </td>
        </tr>
    </table>
    </div>
</div>
