<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../Connections/connSystem.php');

mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login_easy.php');

$sql_heading = "SELECT modulename,submodule FROM sysmodule INNER JOIN syssubmodule ON sysmodule.idsysmodule=syssubmodule.sysmodule_idsysmodule WHERE idsyssubmodule=".$_SESSION['sec_submod']." LIMIT 1 ";
$res_heading = mysql_query($sql_heading);
$fet_heading = mysql_fetch_array($res_heading);
	

	//check if this is the owner
	$sql_wf="SELECT * FROM wfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND idwfproc=".$_SESSION['wfselected']." LIMIT 1";
	$res_wf=mysql_query($sql_wf);
	$fet_wf=mysql_fetch_array($res_wf);
	$num_wf=mysql_num_rows($res_wf);

	//store the wfname on a session for comparison in case the user tries to edit
	$_SESSION['wfprocname'] = $fet_wf['wfprocname']; //

//display the process menu here 
$sql_flow="SELECT idwftskflow,wftskflowname,wftskflowdesc,wfsymbol_idwfsymbol,wftsktat,listorder,h_pos,is_milestone,
(SELECT idwftskflow_gateways FROM wftskflow_gateways WHERE wftskflow_gateways.wftskflow_idwftskflow=wftskflow.idwftskflow) as gw
FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." GROUP BY listorder ORDER BY listorder ASC";
$res_flow=mysql_query($sql_flow);
$num_flow=mysql_num_rows($res_flow);
$fet_flow=mysql_fetch_array($res_flow);
//echo $sql_flow;


	//check if this is the owner
	$sql_wf="SELECT * FROM wfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND idwfproc=".$_SESSION['wfselected']." LIMIT 1";
	$res_wf=mysql_query($sql_wf);
	$fet_wf=mysql_fetch_array($res_wf);
	$num_wf=mysql_num_rows($res_wf);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../thickbox/thickbox_be.js"></script>
<script language="javascript" type="text/javascript" src="../scripts/datetimepicker.js"></script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<div style="background-color:#ffffff">
<div class="bg_section">
<?php echo $fet_heading['modulename']; ?> &raquo; <a href="index.php?uction=view_wfs"><?php echo $fet_heading['submodule']; ?></a>
</div>
<div style="padding:20px">
<table border="0" cellpadding="3" cellspacing="0" class="border_thin">
				<tr>
                	<td colspan="2" class="tbl_h" align="right">
                    <div style="padding:0px 0px 0px 500px">
                    <a href="#" id="button_edit_small" onclick="tb_open_new('pop_workflow_basicinfo_edit.php?uction=edit_workflow&amp;fa=edit&amp;title=New_Item&amp;keepThis=true&amp;TB_iframe=true&amp;height=460&amp;width=800&amp;modal=true')"></a>
                    </div>
                    </td>
                </tr>
				<tr>
                	<td width="180" height="35" class="tbl_data">
                    <strong><?php echo $lbl_wfname;?></strong>                    </td>
              <td width="330" height="35" class="tbl_data">
			  <?php echo $fet_wf['wfprocname'];?></td>
    </tr>
              <tr>
              	 <td class="tbl_data">
                   <strong>
                   <?php 
				echo $lbl_wftype;
				?>
                   </strong> </td>
                <td class="tbl_data">
                <?php 
				$sql_type="SELECT wftypelbl FROM wftype WHERE idwftype=".$fet_wf['wftype_idwftype']."";
				$res_type=mysql_query($sql_type);
				$fet_type=mysql_fetch_array($res_type);
				echo $fet_type['wftypelbl'];
				?>
                </td>
  </tr>
                <tr>
                	<td class="tbl_data">
                   <strong> <?php echo $lbl_description;?></strong></td>
                  <td class="tbl_data">
                   <?php echo $fet_wf['wfprocdesc'];?>
                   </td>
                </tr>
                 <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_tat;?></strong>                    </td>
                   <td height="30" class="tbl_data">
                     <?php 
				if ($fet_wf['wfproctat']>"172800")
					{ //if greater than 48 hours, then make it days, else keep it hours
					echo ($fet_wf['wfproctat']/(60 * 60 * 24))."&nbsp;".$lbl_days;
					} else {
					echo ($fet_wf['wfproctat']/(60 * 60))."&nbsp;".$lbl_hours;
					}
				//store this information on a session
				$_SESSION['workflow_tat']=$fet_wf['wfproctat'];	
				?></td>
              </tr>
              <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_status;?></strong>
                    </td>
                    <td class="tbl_data">
                   <?php if ($fet_wf['wfstatus']==1) { echo "<span style=\"color:#009900\">".$lbl_statusactive."</span>"; } else { echo "<span style=\"color:#ff0000\">".$lbl_statusactivenot."</span>"; }?>
                    </td>
              </tr>
                 <!--<tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_mobileaccess;?></strong>                    </td>
                   <td height="30" class="tbl_data">
                    <?php if ($fet_wf['mobileaccess']=="1") { echo $lbl_yes; } else { echo $lbl_no; }  ?> 
                    </td>
              </tr>-->
			</table>
</div>
<div style="padding:2px">
           
	<ul id="nav">
		<li style="margin:0px 0px 0px -35px">
		<a href="#"><img src="../assets_backend/btns/btn_newwf.jpg" border="0" align="absmiddle" /></a>
            <ul>
                  <?php
                    $sql_symbol="SELECT idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc FROM wfsymbol WHERE list_status=1";
                    $res_symbol=mysql_query($sql_symbol);
                    $fet_symbol=mysql_fetch_array($res_symbol);
                    $num_symbol=mysql_num_rows($res_symbol);
                        
                        if ($num_symbol > 0 )
                            {
                            do {
							//if less than 2 steps, then the end process (symbol number 8 is disabled) has to be disabled
                                if ( 
									(($num_flow < 2) && ($fet_symbol['idwfsymbol']==10)) 
									||
									($fet_symbol['idwfsymbol']==1)
									
									) {
                    ?>
                    
                        <li style="color:#999999"><a href="#" onclick="alert('<?php echo $msg_proc_notavail; ?>');" class="tooltip"><img src="../assets_backend/bpm_icon/<?php echo $fet_symbol['wfsymbol_imgpath'];?>" width="42" height="30" border="0" align="absmiddle" style="padding:2px; margin:2px" /><?php echo $fet_symbol['wfsymbolname'];?><span><?php echo nl2br($fet_symbol['wfsymboldesc']);?></span></a></li>
                        <?php } else { ?>
                        <li><a href="#" onclick="tb_open_new('pop_newworkflow_properties.php?wftskid=new_item&amp;title=New_Item&amp;symbol=<?php echo $fet_symbol['idwfsymbol'];?>&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')" class="tooltip"><img style="padding:2px; margin:2px" width="42" height="30" src="../assets_backend/bpm_icon/<?php echo $fet_symbol['wfsymbol_imgpath'];?>" border="0" align="absmiddle" /><?php echo $fet_symbol['wfsymbolname'];?><span><?php echo $fet_symbol['wfsymboldesc'];?></span></a></li>
                        <?php } ?>
                    <?php
                        } while ($fet_symbol=mysql_fetch_array($res_symbol));
                    }
                    ?>
            </ul>
		</li>
	</ul>
</div>
<div style="text-align:center">
<?php
echo "<span style=\"color:#ffffff\">-</span>";
do {
//echo $fet_flow['idwftskflow']."<br>";
//echo "&nabla;";
?>
<div>
<?php
if ($fet_flow['gw']=="") { //if it is not a split, then go a head and list the middle colum
?>
<table border="0" cellpadding="0" cellspacing="0" align="center">
    	<tr>
        	<td width="140" height="100" align="center" class="border_thin">
			<?php
			if ($fet_flow['wfsymbol_idwfsymbol']==5)
				{
				$sql_condition="SELECT gateway_vars FROM wftskflow_gateways WHERE gateway_splitpoint=".$fet_flow['idwftskflow']." AND wfsymbol_idwfsymbol=".$fet_flow['wfsymbol_idwfsymbol']." AND h_pos='-1' LIMIT 1";
				$res_condition=mysql_query($sql_condition);
				$fet_condition=mysql_fetch_array($res_condition);
				//echo $sql_condition;
				echo "<div class=\"msg_instructions\" style=\"padding:5px; text-align:right; font-weight:bold;font-size:10px\">".$fet_condition['gateway_vars']."</div>";
				
				} else {
				
				//get the symbol
				$sql_symbol="SELECT idwftskflow,idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc,wftskflowname,wftskflowdesc,idwfsymbol,wftsktat,listorder,h_pos 
				FROM wftskflow 
				INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol 
				WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder=".$fet_flow['listorder']." AND h_pos='-1'  LIMIT 1";
				$res_symbol=mysql_query($sql_symbol);
				$fet_symbol=mysql_fetch_array($res_symbol);
				$num_symbol=mysql_num_rows($res_symbol);
				//echo $fet_symbol['idwftskflow'];
				if ($num_symbol>0)
					{
					echo "<div style=\"padding:2px\">
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
						<tr>
							<td width=\"20\" style=\"margin:0px; padding:5px 0px 0px 0px\">
							<a title=\"Delete Item\" onclick=\"return confirm('".$msg_prompt_delete."')\" href=\"index.php?uction=edit_workflow&saction=delete_step&amp;resolv=".$fet_symbol['idwftskflow']."\" id=\"button_delete_vsmall\"></a>
							</td>
							<td class=\"text_small\" style=\"margin:0px; padding:0px\"> 
							";
							if (strlen($fet_symbol['wftskflowname'])>18)
								{
								echo "<span title=\"".$fet_symbol['wftskflowname']."\">".substr($fet_symbol['wftskflowname'],0,18)."...</span>";
								} else {
								echo $fet_symbol['wftskflowname'];
								}
							echo "</td>
						</tr>
					</table>
					</div>";
					echo "<div style=\"padding:6px 0px\">
					<a href=\"#\" onclick=\"tb_open_new('pop_workflow_properties.php?wftskid=".$fet_symbol['idwftskflow']."&amp;title=".$fet_wf['wfprocname']."&nbsp;-&nbsp;".$fet_symbol['wftskflowname']."&amp;symbol=".$fet_symbol['idwfsymbol']."&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')\" class=\"tooltip\">
		            <img src=\"../assets_backend/bpm_icon/".$fet_symbol['wfsymbol_imgpath']."\" border=\"0\" align=\"absmiddle\" />
					<span><small>[Click to Configure] - </small>".$fet_symbol['wftskflowdesc']."</span></a>
					</div>"; 
					echo "<div class=\"text_small\">".$fet_symbol['idwftskflow']."</div>";
					}
				
				}
			
			
			?>
            </td>
            <td width="140" height="100" align="center" class="border_thin" valign="top">
             <?php
			//
			if ($fet_flow['h_pos']=='0')
				{
				//get the symbol
				$sql_symbol="SELECT idwftskflow,idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc,wftskflowname,wftskflowdesc,idwfsymbol,wftsktat,listorder,h_pos 
				FROM wftskflow 
				INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol 
				WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder=".$fet_flow['listorder']." AND h_pos='0' LIMIT 1";
				$res_symbol=mysql_query($sql_symbol);
				$fet_symbol=mysql_fetch_array($res_symbol);
				$num_symbol=mysql_num_rows($res_symbol);
				//echo $fet_symbol['idwftskflow'];
				//echo $fet_flow['listorder'];
				if ($num_symbol>0)
					{
					echo "<div style=\"padding:2px\">
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
						<tr>";
							if ($fet_symbol['idwfsymbol']!=1)//if not the start event, then you can delete
							{
							echo "<td width=\"20\" style=\"margin:0px; padding:5px 0px 0px 0px\">
							<a title=\"Delete Item\" onclick=\"return confirm('".$msg_prompt_delete."')\" href=\"index.php?uction=edit_workflow&saction=delete_step&amp;resolv=".$fet_symbol['idwftskflow']."\" id=\"button_delete_vsmall\"></a>
							</td>";
							}
							echo "<td class=\"text_small\" style=\"margin:0px; padding:0px\">";
							
							
							
							if (strlen($fet_symbol['wftskflowname'])>18)
								{
								echo "<span title=\"".$fet_symbol['wftskflowname']."\">".substr($fet_symbol['wftskflowname'],0,18)."...</span>";
								} else {
								echo $fet_symbol['wftskflowname'];
								}
							
							if ($fet_flow['is_milestone']==1)
								{
								echo "<img title=\"This is a Major Milestone\" src=\"../assets_backend/icons/icon_milestone.png\" border=\"0\" align=\"absmiddle\" />";
								}
							echo "</td>
						</tr>
					</table>
					</div>";
					echo "<div style=\"padding:6px 0px\">
					<a href=\"#\" onclick=\"tb_open_new('pop_workflow_properties.php?wftskid=".$fet_symbol['idwftskflow']."&amp;title=".$fet_wf['wfprocname']."&nbsp;-&nbsp;".$fet_symbol['wftskflowname']."&amp;symbol=".$fet_symbol['idwfsymbol']."&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')\" class=\"tooltip\">
		            <img src=\"../assets_backend/bpm_icon/".$fet_symbol['wfsymbol_imgpath']."\" border=\"0\" align=\"absmiddle\" />
					<span><small>[Click to Configure] - </small>".$fet_symbol['wftskflowdesc']."</span></a>
					</div>"; 
					echo "<div class=\"text_small\" style=\"color:#cccccc\">
					".$fet_symbol['idwftskflow']."
					</div>";
					}
				}
			?>
            </td>
            <td width="140" height="100" align="center" class="border_thin">
         	<?php
			if ($fet_flow['wfsymbol_idwfsymbol']==5)
				{
				$sql_condition="SELECT gateway_vars FROM wftskflow_gateways WHERE gateway_splitpoint=".$fet_flow['idwftskflow']." AND wfsymbol_idwfsymbol=".$fet_flow['wfsymbol_idwfsymbol']." AND h_pos='1' LIMIT 1";
				$res_condition=mysql_query($sql_condition);
				$fet_condition=mysql_fetch_array($res_condition);
				//echo $sql_condition;
				echo "<div class=\"msg_instructions\" style=\"padding:5px;font-weight:bold;font-size:10px\">".$fet_condition['gateway_vars']."</div>";
				
				} else {
				
				//get the symbol
				$sql_symbol="SELECT idwftskflow,idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc,wftskflowname,wftskflowdesc,idwfsymbol,wftsktat,listorder,h_pos 
				FROM wftskflow 
				INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol 
				WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder=".$fet_flow['listorder']." AND h_pos='1'  LIMIT 1";
				$res_symbol=mysql_query($sql_symbol);
				$fet_symbol=mysql_fetch_array($res_symbol);
				$num_symbol=mysql_num_rows($res_symbol);
				//echo $fet_symbol['idwftskflow'];
				if ($num_symbol>0)
					{
					echo "<div style=\"padding:2px\">
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
						<tr>
							<td width=\"20\" style=\"margin:0px; padding:5px 0px 0px 0px\">
							<a title=\"Delete Item\" onclick=\"return confirm('".$msg_prompt_delete."')\" href=\"index.php?uction=edit_workflow&saction=delete_step&amp;resolv=".$fet_symbol['idwftskflow']."\" id=\"button_delete_vsmall\"></a>
							</td>
							<td class=\"text_small\" style=\"margin:0px; padding:0px\"> 
							";
							if (strlen($fet_symbol['wftskflowname'])>18)
								{
								echo "<span title=\"".$fet_symbol['wftskflowname']."\">".substr($fet_symbol['wftskflowname'],0,18)."...</span>";
								} else {
								echo $fet_symbol['wftskflowname'];
								}
							echo "</td>
						</tr>
					</table>
					</div>";
					echo "<div style=\"padding:6px 0px\">
					<a href=\"#\" onclick=\"tb_open_new('pop_workflow_properties.php?wftskid=".$fet_symbol['idwftskflow']."&amp;title=".$fet_wf['wfprocname']."&nbsp;-&nbsp;".$fet_symbol['wftskflowname']."&amp;symbol=".$fet_symbol['idwfsymbol']."&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')\" class=\"tooltip\">
		            <img src=\"../assets_backend/bpm_icon/".$fet_symbol['wfsymbol_imgpath']."\" border=\"0\" align=\"absmiddle\" />
					<span><small>[Click to Configure] - </small>".$fet_symbol['wftskflowdesc']."</span></a>
					</div>"; 
					echo "<div class=\"text_small\">".$fet_symbol['idwftskflow']."</div>";
					}
				
				}
			
			
			?>
            </td>
        </tr>
    </table>
<?php
}//

if ($fet_flow['gw']!="") { //if a split, then go a head and list the side columns with content
//in that case,then we use different query below
?>
<table border="0" cellpadding="0" cellspacing="0" align="center">
    	<tr>
        	<td width="140" height="100" align="center" class="border_thin" >
            <?php
				//get the symbol
				$sql_symbol="SELECT idwftskflow,idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc,wftskflowname,wftskflowdesc,idwfsymbol,wftsktat,listorder,h_pos 
				FROM wftskflow 
				INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol 
				WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder=".$fet_flow['listorder']." AND h_pos='-1'  LIMIT 1";
				$res_symbol=mysql_query($sql_symbol);
				$fet_symbol=mysql_fetch_array($res_symbol);
				$num_symbol=mysql_num_rows($res_symbol);
			//	echo $fet_symbol['idwftskflow'];
				if ($num_symbol>0)
					{
					echo "<div style=\"padding:2px\">
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
						<tr>
							<td width=\"20\" style=\"margin:0px; padding:5px 0px 0px 0px\">
							<a title=\"Delete Item\" onclick=\"return confirm('".$msg_prompt_delete."')\" href=\"index.php?uction=edit_workflow&saction=delete_step&amp;resolv=".$fet_symbol['idwftskflow']."\" id=\"button_delete_vsmall\"></a>
							</td>
							<td class=\"text_small\" style=\"margin:0px; padding:0px\"> 
							";
							if (strlen($fet_symbol['wftskflowname'])>18)
								{
								echo "<span title=\"".$fet_symbol['wftskflowname']."\">".substr($fet_symbol['wftskflowname'],0,18)."...</span>";
								} else {
								echo $fet_symbol['wftskflowname'];
								}
							echo "</td>
						</tr>
					</table>
					</div>";
					echo "<div style=\"padding:6px 0px\">
					<a href=\"#\" onclick=\"tb_open_new('pop_workflow_properties.php?wftskid=".$fet_symbol['idwftskflow']."&amp;title=".$fet_wf['wfprocname']."&nbsp;-&nbsp;".$fet_symbol['wftskflowname']."&amp;symbol=".$fet_symbol['idwfsymbol']."&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')\" class=\"tooltip\">
		            <img src=\"../assets_backend/bpm_icon/".$fet_symbol['wfsymbol_imgpath']."\" border=\"0\" align=\"absmiddle\" />
					<span><small>[Click to Configure] - </small>".$fet_symbol['wftskflowdesc']."</span></a>
					</div>"; 
					echo "<div class=\"text_small\">".$fet_symbol['idwftskflow']."</div>";
					}
				
			?>
            </td>
            <td width="140" height="100" align="center" class="border_thin" valign="top">
           
            </td>
            <td width="140" height="100" align="center" class="border_thin">
           <?php
		 	//get the symbol
				$sql_symbol="SELECT idwftskflow,idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc,wftskflowname,wftskflowdesc,idwfsymbol,wftsktat,listorder,h_pos 
				FROM wftskflow 
				INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol 
				WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND listorder=".$fet_flow['listorder']." AND h_pos='1'  LIMIT 1";
				$res_symbol=mysql_query($sql_symbol);
				$fet_symbol=mysql_fetch_array($res_symbol);
				$num_symbol=mysql_num_rows($res_symbol);
				//echo $fet_symbol['idwftskflow'];
				if ($num_symbol>0)
					{
					echo "<div style=\"padding:2px\">
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\">
						<tr>
							<td width=\"20\" style=\"margin:0px; padding:5px 0px 0px 0px\">
							<a title=\"Delete Item\" onclick=\"return confirm('".$msg_prompt_delete."')\" href=\"index.php?uction=edit_workflow&saction=delete_step&amp;resolv=".$fet_symbol['idwftskflow']."\" id=\"button_delete_vsmall\"></a>
							</td>
							<td class=\"text_small\" style=\"margin:0px; padding:0px\"> 
							";
							if (strlen($fet_symbol['wftskflowname'])>18)
								{
								echo "<span title=\"".$fet_symbol['wftskflowname']."\">".substr($fet_symbol['wftskflowname'],0,18)."...</span>";
								} else {
								echo $fet_symbol['wftskflowname'];
								}
							echo "</td>
						</tr>
					</table>
					</div>";
					echo "<div style=\"padding:6px 0px\">
					<a href=\"#\" onclick=\"tb_open_new('pop_workflow_properties.php?wftskid=".$fet_symbol['idwftskflow']."&amp;title=".$fet_wf['wfprocname']."&nbsp;-&nbsp;".$fet_symbol['wftskflowname']."&amp;symbol=".$fet_symbol['idwfsymbol']."&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=800&amp;modal=true')\" class=\"tooltip\">
		            <img src=\"../assets_backend/bpm_icon/".$fet_symbol['wfsymbol_imgpath']."\" border=\"0\" align=\"absmiddle\" />
					<span><small>[Click to Configure] - </small>".$fet_symbol['wftskflowdesc']."</span></a>
					</div>"; 
					echo "<div class=\"text_small\">".$fet_symbol['idwftskflow']."</div>";
					}
			?>
            </td>
        </tr>
    </table>
<?php
}
?>
    
</div>
<?php
	} while ($fet_flow=mysql_fetch_array($res_flow));
?> 
<div style="padding:100px"></div>
</div>
</div>
</body>
</html>
