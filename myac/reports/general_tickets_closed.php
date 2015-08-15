<?php
require_once("../assets_backend/be_includes/check_login_easy.php");
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
	
if (isset($_GET['reportid']))
	{
	$_SESSION['reportid']=mysql_escape_string(trim($_GET['reportid']));
	} else {
	$_SESSION['reportid']=0;
	}	
	
unset($_SESSION['regname']);
unset($_SESSION['rptname']);	
?>
<div style="padding:0px 0px 0px 8px">
  <h2>Closed Tickets</h2>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">	
	<tr>
    	<td valign="top" width="50%">
        <div style="padding:5px 0px 10px 0px" class="title_header_blue">
        Top 5 categories closed <small>( Global - Past 30 Days )</small></div>
        <div style="padding:5px 0px">
       <?php
       echo "<iframe style=\"padding:0px;margin:0px;\"  src=\"flot/reports/categories/all_complaints_global_closed.php\" width=\"455\" height=\"450\" scrolling=\"no\" frameborder=\"0\"></iframe> ";?>   
        </div>
	  </td>
        <td valign="top">
        <div class="msg_instructions_small" style="padding:5px 10px; margin:0px">Generate reports on Closed Complaints<sup>[1]</sup> via <?php echo $pagetitle;?></div>
        <div class="text_small">[1] Closed Complaints includes Invalidated &amp; Resolved Complaints</div>
        <div style="margin:0px">
        <ol>
   			<li class="border_bottom">
            <?php $lbl_rpt_1="Closed Tickets Resolution ( By Region )"; ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==1)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=1"><?php echo $lbl_rpt_1;?></a>
            <br />
            <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==1)) { ?>
            <form method="get" action="reports/closed_tickets_tat_global.php" target="_blank" style="margin:0px">
            <table border="0">
                <tr>
                    <td><input name="timestart" type="text" id="timestart" onClick="displayDatePicker('timestart');" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" size="10" readonly="readonly"> 
                                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                                
                    <td>
                    <input name="timestop" type="text"  id="timestop" onClick="displayDatePicker('timestop');" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" size="10" readonly="readonly"> 
                                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
                    </td> 
                    <td>
                    <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_1;?>" />
                    <input type="submit" value="Run Report" />
                    </td>                       
                </tr>
            </table>
            </form>
            <?php } ?>
            </li>

<?php /*?>   			<li class="border_bottom">
            <?php $lbl_rpt_2="Closed Tickets ( ".$_SESSION['MVGitHub_userteamzone']." )"; ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==2)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=2"><?php echo $lbl_rpt_2;?></a>
            <br />
            <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==2)) { ?>
            <form method="get" action="reports/closed_tickets_tat_regional.php" target="_blank" style="margin:0px">
            <table border="0">
                <tr>
                    <td><input name="timestart" type="text" id="timestart" onClick="displayDatePicker('timestart');" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" size="10" readonly="readonly"> 
                                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                                
                    <td>
                    <input name="timestop" type="text"  id="timestop" onClick="displayDatePicker('timestop');" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" size="10" readonly="readonly"> 
                                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
                    </td> 
                    <td>
                    <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_2;?>" />
                    <input type="submit" value="Run Report" />
                    </td>                       
                </tr>
            </table>
            </form>
            <?php } ?>
            </li>
<?php */?>  
            </ol>
        </div>
        </td>
	</tr>
</table>