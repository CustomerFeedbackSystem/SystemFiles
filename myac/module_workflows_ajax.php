<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../Connections/connSystem.php');

mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');
if	( (isset($_GET['saction'])) && ($_GET['saction']=="delete_wf") )
	{
	//first clean it up
	$dwf=mysql_escape_string(trim($_GET['wf']));
	
	//second, find if this is the owner of this record and if not, warn
	$sql_chk="SELECT idwfproc,usrteamzone_idusrteamzone,wfstatus FROM wfproc WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND idwfproc=".$dwf." LIMIT 1";
	$res_chk=mysql_query($sql_chk);
	$num_chk=mysql_num_rows($res_chk);
	$fet_chk=mysql_fetch_array($res_chk);
	//first, is there a record by this name
	if ($num_chk < 1)
		{
		$msg = "<div class=\"msg_warning\">".$msg_warn_contactadmin.$ec100."</div>";
		} else {
		
		//check if process is active or inactive
			if ($fet_chk['wfstatus']==1)
				{
				$msg = "<div class=\"msg_warning\">".$msg_warning_procdel."</div>";
				} else {
				
					//next find out if there is a category record and or taskflow associated with this process
					$sql_chkcats = "SELECT idlink_tskcategory_wfproc FROM link_tskcategory_wfproc WHERE wfproc_idwfproc=".$dwf." LIMIT 1";
					$res_chkcats = mysql_query($sql_chkcats);
					$num_chkcats = mysql_num_rows($res_chkcats);
					

					if ($num_chkcats>0)
						{
						$msg = "<div class=\"msg_warning\">".$msg_warning_opdeny.$ec12."</div>";
						} else {
				
				//delete if all the above passes
				//delete working hours first
				$sql_thisflow="SELECT idwftskflow FROM wftskflow WHERE wfproc_idwfproc=".$fet_chk['idwfproc']."";
				$res_thisflow=mysql_query($sql_thisflow);
				$fet_thisflow=mysql_fetch_array($res_thisflow);
				
				do {
				//delete working hours
				$sql_delete_hrs="DELETE FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_thisflow['idwftskflow']."";
				mysql_query($sql_delete_hrs);
				
				//delete actors
				$sql_delete_actors="DELETE FROM wfactors WHERE wftskflow_idwftskflow=".$fet_thisflow['idwftskflow']."";
				mysql_query($sql_delete_actors);
				
				//delete notifications
				$sql_delete_notify="DELETE FROM wfnotification WHERE  wftskflow_idwftskflow=".$fet_thisflow['idwftskflow']."";
				mysql_query($sql_delete_notify);
				
				//delete escalations
				$sql_delete_escalation="DELETE FROM wfescalation WHERE  wftskflow_idwftskflow=".$fet_thisflow['idwftskflow']."";
				mysql_query($sql_delete_escalation);
				
				//delete action status types
				$sql_delete_status="DELETE FROM wftskstatus WHERE  wftskflow_idwftskflow=".$fet_thisflow['idwftskflow']."";
				mysql_query($sql_delete_status);
				
				//delete feedback
				$sql_delete_feedback="DELETE FROM tktfeedback WHERE  wftskflow_idwftskflow=".$fet_thisflow['idwftskflow']."";
				mysql_query($sql_delete_feedback);
				
				} while ($fet_thisflow=mysql_fetch_array($res_thisflow));
				
				$sql_del="DELETE FROM wfproc WHERE usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND idwfproc=".$dwf." LIMIT 1";
				mysql_query($sql_del);
				
				$sql_delwf="DELETE FROM wftskflow WHERE wfproc_idwfproc=".$dwf."";
				mysql_query($sql_delwf);
				
				
				$msg = "<div class=\"msg_success\">".$msg_success_delete."</div>";
						}
					}
		}
	
	//third, find if this 
	}
$sql_heading = "SELECT modulename,submodule FROM sysmodule INNER JOIN syssubmodule ON sysmodule.idsysmodule=syssubmodule.sysmodule_idsysmodule WHERE idsyssubmodule=".$_SESSION['sec_submod']." LIMIT 1 ";
$res_heading = mysql_query($sql_heading);
$fet_heading = mysql_fetch_array($res_heading);
//echo $query_rs_list;
//echo rand(200000,9999999999);
?>
<div style="padding:5px 0px 100px 5px">
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
    </div>
    <div>
    <?php if (isset($msg)) { echo $msg; } ?>
    </div>
    <div style="margin:15px 5px 5px 5px">
    	<div class="tab_area">
        	<span class="tab"><a href="index.php?view=17&amp;page=&amp;mod=8&amp;submod=17&amp;uction=view_submod"><?php echo $tab_wftocat;?></a></span>
            <span class="tab_on"><a href="index.php?view=17&amp;page=&amp;mod=8&amp;submod=17&amp;uction=view_wfs"><?php echo $tab_wfs;?></a></span>
            <span class="tab"><a href="index.php?view=17&amp;page=&amp;mod=8&amp;submod=17&amp;uction=view_forms">Custom Forms</a></span>
        </div>
    </div>
    <div style="padding:5px">
    <a href="index.php?view=17&amp;page=&amp;mod=8&amp;submod=17&amp;uction=view_submod?uction=new_workflow" id="button_newwf"></a>
    </div>
    <div>
		<table border="0" cellpadding="2" cellspacing="0" width="100%" id="hi">
            <tr>
                <td width="23%" class="tbl_h"><?php echo $lbl_wf;?>df</td>
              <td width="30%" class="tbl_h"><?php echo $lbl_description;?></td>
              <td width="18%" class="tbl_h"><?php echo $lbl_tat;?></td>
              <td width="17%" class="tbl_h"><?php echo $lbl_lastedit;?></td>
              <td width="12%" class="tbl_h"></td>
          </tr>
            <?php
			$sql_wf="SELECT idwfproc,usrteamzone_idusrteamzone,wfprocname,wfproctat,wfprocdesc,wfstatus,mobileaccess,modifiedon,createdon FROM wfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY wfprocname ASC";
			$res_wf=mysql_query($sql_wf);
			$num_wf=mysql_num_rows($res_wf);
			$fet_wf=mysql_fetch_array($res_wf);
			
			if ($num_wf > 0) 
				{
			do {
			?>
            <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
            	<td class="tbl_data">
                <a href="index.php?view=17&amp;page=&amp;mod=8&amp;submod=17&amp;uction=view_submod?uction=edit_workflow&amp;wf=<?php echo $fet_wf['idwfproc'];?>">
				<?php 
				echo $fet_wf['wfprocname'];?>
                </a>
                <small>
                <?php
				if ($fet_wf['wfstatus']==0)
					{
					echo "&nbsp;<span style=\"color:#ff0000\" >[".$lbl_statusactivenot."]</span>";
					} else if ($fet_wf['wfstatus']==1) {
					echo "&nbsp;<span style=\"color:#009900\" >[".$lbl_statusactive."]</span>";
					}
				 ?>
                </small>
                </td>
                <td class="tbl_data">
                <?php echo $fet_wf['wfprocdesc'];?>
                </td>
                <td class="tbl_data">
                <?php 
				if ($fet_wf['wfproctat']>"172800")
					{ //if greater than 48 hours, then make it days, else keep it hours
					echo ($fet_wf['wfproctat']/(60 * 60 * 24))."&nbsp;".$lbl_days;
					} else {
					echo ($fet_wf['wfproctat']/(60 * 60))."&nbsp;".$lbl_hours;
					}
				?>
                </td>
                <td class="tbl_data">
                <?php
					 if ($fet_wf['modifiedon']!='0000-00-00 00:00:00') // if there was a modified, then
					 	{
						echo date("D, M d, Y H:i",strtotime($fet_wf['modifiedon'])); 
						} else {
						echo date("D, M d, Y H:i",strtotime($fet_wf['createdon'])); 
						}
				?>
                </td>
                <td class="tbl_data">
                <a onclick="return confirm('<?php echo $msg_prompt_delete;?>');" href="index.php?view=17&amp;page=&amp;mod=8&amp;submod=17&amp;uction=view_submod?saction=delete_wf&amp;wf=<?php echo $fet_wf['idwfproc'];?>" id="button_delete_small"></a>
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
			} while ($fet_wf=mysql_fetch_array($res_wf));
			
			} else {
			?>
            <tr>
            	<td colspan="5" align="center" height="50">
                <span class="msg_warning" >
                <?php echo $msg_warning_nowf; ?>
                </span>
                </td>
            </tr>
            <?php
			}
			?>
		</table>
    </div>
</div>