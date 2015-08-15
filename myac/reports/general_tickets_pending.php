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

unset($_SESSION['timestart']);
unset($_SESSION['timestop']);
?>
<div style="padding:0px 0px 0px 8px">
  <h2>Pending Tickets</h2>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">	
	<tr>
    	<td valign="top" width="50%">
        <div style="padding:5px 0px 10px 0px" class="title_header_blue">
        Top 5 pending categories <small>( Global  )</small> as at <?php echo date("D, M d, Y",strtotime($timenowis)); ?></div>
        <div style="padding:5px 0px">
       <?php
       echo "<iframe style=\"padding:0px;margin:0px;\"  src=\"flot/reports/categories/all_complaints_global_pending.php\" width=\"455\" height=\"450\" scrolling=\"no\" frameborder=\"0\"></iframe> ";?>   
        </div>
	  </td>
        <td valign="top">
        <div class="msg_instructions_small" style="padding:5px 10px; margin:0px">Generate reports on Pending (in progress) Tickets on <?php echo $pagetitle;?></div>
        <div style="margin:0px">
        <ol>
   		
	    <li class="border_bottom">
			<?php
             $lbl_rpt_1="Global Pending Tickets across ".$_SESSION['MVGitHub_acteam']." ( By Category )";
            ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==1)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="reports/pending_tkts_cat_global.php?reportid=1&amp;reportname=<?php echo $lbl_rpt_1; ?>" target="_blank"><?php echo $lbl_rpt_1;?></a>
	    </li>

        <li class="border_bottom">
			<?php
             $lbl_rpt_8="Global Pending Tickets across ".$_SESSION['MVGitHub_acteam']." ( By Region )";
            ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==8)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="reports/pending_tkts_reg_global.php?reportid=8&amp;reportname=<?php echo $lbl_rpt_8; ?>" target="_blank"><?php echo $lbl_rpt_8;?></a>
        </li>    

        <li class="border_bottom">
            <?php
             $lbl_rpt_9="Global Pending Tickets by Category ( By Region )";
            ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==9)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="reports/pending_tkts_regcat_global.php?reportid=9&amp;reportname=<?php echo $lbl_rpt_9; ?>" target="_blank"><?php echo $lbl_rpt_9;?></a>
        </li>
    
        <li class="border_bottom">
            <?php
             $lbl_rpt_2="Global Pending Tickets by Department ( By Region )";
            ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==2)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="reports/pending_tkts_dpts_global.php?reportid=2&amp;reportname=<?php echo $lbl_rpt_2; ?>" target="_blank"><?php echo $lbl_rpt_2;?></a>
        </li>

        <li class="border_bottom">
            <?php
             $lbl_rpt_7="Regional Pending Tickets by Category ( ".$_SESSION['MVGitHub_userteamzone']." )";
            ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==7)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="reports/pending_tkts_cat_region.php?reportid=7&amp;reportname=<?php echo $lbl_rpt_7; ?>" target="_blank"><?php echo $lbl_rpt_7;?></a>
        </li>

        <li class="border_bottom">
            <?php
             $lbl_rpt_3="Regional Pending Tickets at HQ ( ".$_SESSION['MVGitHub_userteamzone']." )";
            ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==3)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="reports/pending_tkts_at_hq.php?reportid=3&amp;reportname=<?php echo $lbl_rpt_3; ?>" target="_blank"><?php echo $lbl_rpt_3;?></a>
        </li>

        <li class="border_bottom">
            <?php
             $lbl_rpt_4="Regional Pending Tickets per User ( ".$_SESSION['MVGitHub_userteamzone']." )";
            ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==4)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="reports/pending_tkts_per_user.php?reportid=4&amp;reportname=<?php echo $lbl_rpt_4; ?>" target="_blank"><?php echo $lbl_rpt_4;?></a>
        </li>
            
        </ol>
        </div>
        </td>
	</tr>
</table>