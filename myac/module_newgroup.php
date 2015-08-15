<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="addgroup"))
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
	
	if ((!isset($error1)) && ($num_duplicategroup > 0) )
		{
		$error2="<div class=\"msg_warning\">".$msg_warning_duplicategroup."</div>";
		}
		
	//process it
	if ( (!isset($error1)) && (!isset($error2))  )
		{
		$sql_newrole="INSERT INTO usrgroup (usrteam_idusrteam,usrgroupname,usrgroupdesc,createdby,createdon) 
		VALUES ('".$_SESSION['MVGitHub_idacteam']."','".$pgroupname."','".$pgroupdesc."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
		mysql_query($sql_newrole);
		
		$msg=$msg_changes_saved;
		
		$sql_newid="SELECT idusrgroup,usrgroupname FROM usrgroup WHERE createdby='".$_SESSION['MVGitHub_idacname']."' ORDER BY idusrgroup DESC LIMIT 1";
		$res_newid=mysql_query($sql_newid);
		$fet_newid=mysql_fetch_array($res_newid);
		?>
		<script language="javascript">
		window.location='<?php echo $_SERVER['PHP_SELF'].'?uction=edit_submod&groupname='.urlencode($fet_newid['usrgroupname']).'&thisgroup='.$fet_newid['idusrgroup'].'&msg='.urlencode($msg).'&view=19';?>';
		</script>        
        <?php
		//header('location:'.$_SERVER['PHP_SELF'].'?uction=edit_submod&groupname='.urlencode($fet_newid['usrgroupname']).'&thisgroup='.$fet_newid['idusrgroup'].'&msg='.urlencode($msg).'&view=19');
		exit;
		}
	} //close form action
?>
<div >

	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div>
    <?php
	if (isset($msg)) { echo $msg; } 
	
	if ( (isset($error1)) || (isset($error2))  )
		{
		if (isset($error1)) { echo $error1; }
		if (isset($error2)) { echo $error2; }
		}
	?>
    </div>
<div style="padding:20px 0px 0px 0px">
<form method="post" action="" name="newrole" enctype="multipart/form-data">
        	<table border="0" cellpadding="3" cellspacing="0" class="border_thick">
            <tr>
            <td height="30" colspan="2" class="table_header">
			<?php echo $lbl_newgroup;?>            </td>
            </tr>
		  <tr>
               <td width="171" height="40" class="tbl_data">
               <strong><?php echo $lbl_group;?></strong>
               </td>
               <td width="352" height="40"  class="tbl_data">
               <input type="text" name="groupname" maxlength="100" value="<?php if (isset($_GET['groupname'])) { echo $_GET['groupname']; }?>" size="40">               </td>
			</tr>
                <tr>
               	  <td width="171" height="40" class="tbl_data" valign="top">
                    <strong><?php echo $lbl_description;?></strong>
                    </td>
                  <td height="40"  class="tbl_data" valign="top">
                  <textarea name="groupdesc" rows="4" cols="30" id="groupdesc"></textarea>
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
                                <input type="hidden" value="addgroup" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>?uction=view_submod" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>
                        </td>
				</tr>
	</table>   
</form> 
</div>
</div>