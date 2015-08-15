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
?>

<div style="padding:0px 0px 0px 5px"><h2> Welcome to Report Center</h2></div>
<div class="msg_instructions_small">Below are some overview stats. For more, please use the links on the left</div>
<div style="padding:30px 0px 10px 0px" class="title_header_blue">
Global Weekly Overview ( Past 7 Days )
</div> 
<div class="text_body" style="padding:0px 5px 100px 0px">
<table width="644" border="0" cellpadding="2" cellspacing="0" style="border:1px solid #999999">
<tr>
        	<td width="321" height="30" class="tbl_sh" style="color:#cccccc">-</td>
      <td width="141" height="30" class="tbl_sh">Number</td>
          <td width="168" height="30" class="tbl_sh">% within TAT</td>
    </tr>
        <tr>
        	<td height="35" class="tbl_data"><a href="../reports/index.php?view=29&amp;page=&amp;mod=5&amp;submod=29&amp;uction=view_submod"><strong>Tickets Received</strong>  ( Past Week )</a></td>
            <td height="35" class="tbl_data">
            <?php
			$sql_received="SELECT count(*) as total_in FROM tktin WHERE  timereported >'".$seven_days_ago."'";
			$res_received=mysql_query($sql_received);
			$fet_received=mysql_fetch_array($res_received);
			echo number_format($fet_received['total_in'],0);
			?>
            </td>
			<td height="35" class="tbl_data">-</td>
      </tr>
        <tr>
        	<td height="35" bgcolor="#FBFBFB" class="tbl_data"><a href="../reports/index.php?view=66&amp;page=&amp;mod=5&amp;submod=66&amp;uction=view_submod">Tickets Currently Pending</a></td>
          <td height="35" bgcolor="#FBFBFB" class="tbl_data">
          <?php
			$sql_pending="SELECT count(*) as total_pending FROM tktin WHERE tktstatus_idtktstatus<3 ";
			$res_pending=mysql_query($sql_pending);
			$fet_pending=mysql_fetch_array($res_pending);
			echo number_format($fet_pending['total_pending'],0);
			?>          </td>
		  <td height="35" bgcolor="#FBFBFB" class="tbl_data">
            <?php
			$sql_pendingwt="SELECT count(idtktinPK) as total_pendingwt FROM tktin
			INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory 
			WHERE TIME_TO_SEC(TIMEDIFF(NOW(),tktin.timereported)) < tktcategory.tat 
			AND tktstatus_idtktstatus<4";
			
			//REPLACED ON OCT 3RD 2014	
			//$sql_pendingwt="SELECT count(*) as total_pendingwt FROM tktin WHERE tktstatus_idtktstatus<3 AND timedeadline>='".$timenowis."' ";
			$res_pendingwt=mysql_query($sql_pendingwt);
			$fet_pendingwt=mysql_fetch_array($res_pendingwt);
			echo number_format($fet_pendingwt['total_pendingwt'],0);
			
			if ($fet_pending['total_pending'] > 0)
				{
				$var_value=number_format((($fet_pendingwt['total_pendingwt']/$fet_pending['total_pending'])*100),2);
				
				if ($var_value < 40)
					{
					$var_color="#ff0000";
					} else if ( ($var_value >= 40) && ($var_value < 75)  ) 
					{
					$var_color="#ff9900";
					} else if ($var_value >= 75)
					{
					$var_color="#009900";
					}
				}
			
			echo "<span style=\"color:".$var_color."\">&nbsp;( ".$var_value." % )</span>";
			?>            </td>
    </tr>
        <tr>
        	<td height="35" class="tbl_data"><a href="../reports/index.php?view=67&amp;page=&amp;mod=5&amp;submod=67&amp;uction=view_submod"><strong>Tickets Closed</strong> ( Past Week )</a><sup> [ 1 ]</sup></td>
          <td height="35" class="tbl_data">
            <?php
			$sql_closed="SELECT count(*) as total_closed FROM tktin WHERE timeclosed >='".$seven_days_ago."'  AND tktstatus_idtktstatus>3";
			$res_closed=mysql_query($sql_closed);
			$fet_closed=mysql_fetch_array($res_closed);
			echo number_format($fet_closed['total_closed'],0);
			?>
          </td>
			<td height="35" class="tbl_data">
            <?php
//			$sql_closedwt="SELECT count(*) as total_closedwt FROM tktin WHERE tktstatus_idtktstatus>3 AND timereported>='".$timenowis."' AND timedeadline>=timeclosed ";
			$sql_closedwt="SELECT count(*) as total_closedwt FROM tktin WHERE tktstatus_idtktstatus>3 AND timeclosed >='".$seven_days_ago."' AND timedeadline>=timeclosed ";			
			$res_closedwt=mysql_query($sql_closedwt);
			$fet_closedwt=mysql_fetch_array($res_closedwt);
			echo number_format($fet_closedwt['total_closedwt'],0);
			
			if ($fet_closed['total_closed']>0)
				{
			$var_value=number_format((($fet_closedwt['total_closedwt']/$fet_closed['total_closed'])*100),2);
				
				if ($var_value < 40)
					{
					$var_color="#ff0000";
					} else if ( ($var_value >= 40) && ($var_value < 75)  ) 
					{
					$var_color="#ff9900";
					} else if ($var_value >= 75)
					{
					$var_color="#009900";
					}
				
				echo "<span style=\"color:".$var_color."\">&nbsp;( ".$var_value." % )</span>";
				}
			?>
          </td>
      </tr>
      <tr>
      	<td colspan="3" style="padding:15px 0px 10px 5px">
        <div class="text_small"><small>[ 1 ]</small> Tickets Closed includes Resolved as well as Invalidated Tickets</div>
        </td>
      </tr>
    </table>
    
    
    <div style="padding:15px 0px">
    <div style="padding:30px 0px 0px 0px" class="title_header_blue">
    Compliance Reports
    </div> 
    <div>
        <ol>
          	<li class="border_bottom">
				<?php $lbl_rpt_13="Global Compliance Performance Report ( By Zone )"; ?>
                <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==13)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=13"><?php echo $lbl_rpt_13;?></a>
                <br />
                <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==13)) { ?>
                <form method="get" action="reports/complaints_resolution_compliance_global.php" target="_blank" style="margin:0px">
                <table border="0">
                    <tr>
                        <td><input name="timestart" type="text" id="timestart" onClick="displayDatePicker('timestart');" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" size="10" readonly="readonly"> 
                                    <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                                    
                        <td>
                        <input name="timestop" type="text"  id="timestop" onClick="displayDatePicker('timestop');" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" size="10" readonly="readonly"> 
                                    <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
                        </td> 
						<td>	
                        <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_13;?>" />
                        <input type="submit" value="Run Report" />
                        </td>                       
                    </tr>
                </table>
                </form>
                <?php } ?>
          	</li>

            <li class="border_bottom">
				<?php $lbl_rpt_12="Daily Report: Global Complaints Resolution Rate ( By Zone )"; ?>
                <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==12)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=12"><?php echo $lbl_rpt_12;?></a>
                <br />
                <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==12)) { ?>
                <form method="get" action="reports/complaints_resolution_compliance_daily.php" target="_blank" style="margin:0px">
                <table border="0">
                    <tr>
                        <td><input name="timestart" type="text" id="timestart" onClick="displayDatePicker('timestart');" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" size="10" readonly="readonly"> 
                                    <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                                    
                        <td>
                        <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_12;?>" />
                        <input type="submit" value="Run Report" />
                        </td>                       
                    </tr>
                </table>
                </form>
                <?php } ?>
           </li>
        
            <li class="border_bottom">
			<?php $lbl_rpt_6="Monthly Report : Global Complaints Resolution Compliance ( Per Zone ) ";?>
           <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==6)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=6">
          <!--  <a href="#" style="color:#CCCCCC">-->
           <?php echo $lbl_rpt_6;?></a>
            <br />
            <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==6)) { ?>
            <form method="get" action="reports/complaints_resolution_compliance_monthly.php" target="_blank" style="margin:0px">
            <table border="0">
                <tr>
                    <td>
                    <?php
                    $sql_month="SELECT report_month_lbl,report_month_val FROM reports_var_months ORDER BY list_order ASC";
                    $res_month=mysql_query($sql_month);
                    $num_month=mysql_num_rows($res_month);
                    $fet_month=mysql_fetch_array($res_month);
                    ?>
                    <select name="month" style="padding:0px">
                    <option value="">--Select Month--</option>
                    <?php
                    do {
                    echo "<option value=\"".$fet_month['report_month_val']."\">".$fet_month['report_month_lbl']."</option>";
                    } while ($fet_month=mysql_fetch_array($res_month));
                    ?>
                    </select>
                    </td>
                                
                    <td>
                    <?php
                    $sql_yr="SELECT report_year_lbl FROM reports_var_years ORDER BY report_year_lbl ASC";
                    $res_yr=mysql_query($sql_yr);
                    $num_yr=mysql_num_rows($res_yr);
                    $fet_yr=mysql_fetch_array($res_yr);
                    ?>
                    <select name="year" style="padding:0px">
                    <option value="">--Select Year--</option>
                    <?php
                    do {
                    echo "<option value=\"".$fet_yr['report_year_lbl']."\">".$fet_yr['report_year_lbl']."</option>";
                    } while ($fet_yr=mysql_fetch_array($res_yr));
                    ?>
                    </select>
                    </td> 
                    <td>
                    <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_6;?>" />
                    <input type="submit" value="Run Report" />
                    </td>                       
                </tr>
            </table>
            </form>
            <?php } ?>
            </li>
            
            
            <li class="border_bottom">
				<?php $lbl_rpt_4="Monthly Report : Complaints Resolution Compliance ( ".$_SESSION['MVGitHub_userteamzone']." ) ";?>
                 <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==4)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=4">
                
               <!-- <a href="#" style="color:#CCCCCC">-->
                <?php echo $lbl_rpt_4;?></a>
                <br />
                <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==4)) { ?>
                <form method="get" action="reports/complaints_resolution_compliance_regional.php" target="_blank" style="margin:0px">
                <table border="0">
                    <tr>
                        <td>
                        <?php
                        $sql_month="SELECT report_month_lbl,report_month_val FROM reports_var_months ORDER BY list_order ASC";
                        $res_month=mysql_query($sql_month);
                        $num_month=mysql_num_rows($res_month);
                        $fet_month=mysql_fetch_array($res_month);
                        ?>
                        <select name="month" style="padding:0px">
                        <option value="">--Select Month--</option>
                        <?php
                        do {
                        echo "<option value=\"".$fet_month['report_month_val']."\">".$fet_month['report_month_lbl']."</option>";
                        } while ($fet_month=mysql_fetch_array($res_month));
                        ?>
                        </select>
                        </td>
                                    
                        <td>
                        <?php
                        $sql_yr="SELECT report_year_lbl FROM reports_var_years ORDER BY report_year_lbl ASC";
                        $res_yr=mysql_query($sql_yr);
                        $num_yr=mysql_num_rows($res_yr);
                        $fet_yr=mysql_fetch_array($res_yr);
                        ?>
                        <select name="year" style="padding:0px">
                        <option value="">--Select Year--</option>
                        <?php
                        do {
                        echo "<option value=\"".$fet_yr['report_year_lbl']."\">".$fet_yr['report_year_lbl']."</option>";
                        } while ($fet_yr=mysql_fetch_array($res_yr));
                        ?>
                        </select>
                        </td> 
                        <td>
                        <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_4;?>" />
                        <input type="submit" value="Run Report" />
                        </td>                       
                    </tr>
                </table>
                </form>
                <?php } ?>
                </li>
                            
        </ol>
        </div>
    </div>
    
        <div style="padding:10px 0px">
    <div style="padding:0px 0px 0px 0px" class="title_header_blue">
    Team Reports
    </div> 
    <div>
        <ol>
            <li class="border_bottom">
            <?php $lbl_rpt_11="Overall Tasks Report per Individual by Department ( ".$_SESSION['MVGitHub_userteamzone']." )"; ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==11)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=11"><?php echo $lbl_rpt_11;?></a>
            <br />
            <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==11)) { ?>
            <form method="get" action="reports/team_overall_tasks_region.php" target="_blank" style="margin:0px">
            <table border="0">
                <tr>
                    <td><input name="timestart" type="text" id="timestart" onClick="displayDatePicker('timestart');" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" size="10" readonly="readonly"> 
                                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                                
                    <td>
                    <input name="timestop" type="text"  id="timestop" onClick="displayDatePicker('timestop');" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" size="10" readonly="readonly"> 
                                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
                    </td> 
                    <td>
                    <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_11;?>" />
                    <input type="submit" value="Run Report" />
                    </td>                       
                </tr>
            </table>
            </form>
            <?php } ?>
            </li>
            <li class="border_bottom">
            <?php $lbl_rpt_14="Overall Tasks Report per Individual Grouped by Zone"; ?>
            <a class="text_body" <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==14)) { echo "style=\"text-decoration:none;color:#cc0000;font-weight:bold\""; } ?> href="<?php $_SERVER['PHP_SELF'];?>?reportid=14"><?php echo $lbl_rpt_14;?></a>
            <br />
            <?php if ((isset($_SESSION['reportid'])) &&  ($_SESSION['reportid']==14)) { ?>
            <form method="get" action="reports/team_overall_tasks_global.php" target="_blank" style="margin:0px">
            <table border="0">
                <tr>
                    <td><input name="timestart" type="text" id="timestart" onClick="displayDatePicker('timestart');" value="<?php if (isset($_SESSION['timestart'])) { echo $_SESSION['timestart']; } ?>" size="10" readonly="readonly"> 
                                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestart');">                        </td>
                                
                    <td>
                    <input name="timestop" type="text"  id="timestop" onClick="displayDatePicker('timestop');" value="<?php if (isset($_SESSION['timestop'])) { echo $_SESSION['timestop']; } ?>" size="10" readonly="readonly"> 
                                <img src="../assets_backend/btns/cal.gif" alt="Pick a date" width="30" height="30" border="0" align="absmiddle" onClick="displayDatePicker('timestop');">
                    </td> 
                    <td>
                    <input type="hidden" name="reportname" value="<?php echo $lbl_rpt_14;?>" />
                    <input type="submit" value="Run Report" />
                    </td>                       
                </tr>
            </table>
            </form>
            <?php } ?>
            </li>
       </ol>
        </div>
    </div>

</div>