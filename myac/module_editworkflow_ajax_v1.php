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
	$_SESSION['wfprocname'] = $fet_wf['wfprocname'];
	
//process this form
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save") )
	{
	//first, clean up the data
	$ddesc=mysql_escape_string(trim($_POST['wfdesc']));
	$dtat=mysql_escape_string(trim($_POST['tat']));
	$dtat_cat=mysql_escape_string(trim($_POST['tat_cat']));
	$dwfname=mysql_escape_string(trim($_POST['wfname']));
	$dstatus=mysql_escape_string(trim($_POST['wfstate']));
	
	if (isset($_POST['mobacc']))
		{
		$dmobacc=1;
		} else {
		$dmobacc=0;
		}
	
	if ($_SESSION['wfprocname']!=$dwfname) //if 
		{
		//check for duplicate workflow for this userteam
		$sql_exists = "SELECT idwfproc FROM wfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND wfprocname='".$dwfname."' LIMIT 1";
		$res_exists = mysql_query($sql_exists);
		$num_exists = mysql_num_rows($res_exists);
		}
	
	//second, look for the required fields
	if (strlen($ddesc) < 1)
		{
		$error_1 = "<div class=\"msg_warning\">".$msg_warning_desc_required."</div>";
		}
	if (($dtat<1) || ($dtat==""))
		{
		$error_2 = "<div class=\"msg_warning\">".$msg_warning_tat_required."</div>";
		}
	if (strlen($dtat_cat)<2)
		{
		$error_3 = "<div class=\"msg_warning\">".$msg_warning_tatcat_required."</div>";
		}
	
	if ($_SESSION['wfprocname']!=$dwfname) //if what is keyed in is different from what was in the db, thats the only time to check
		{
			if ($num_exists > 0) //if record already exists
			{
			$error_4 = "<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
			}
		}
		
	//if there is no error, then process	
		if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3))  && (!isset($error_4)) )
			{
			
			//first, lets fix the hours or days to seconds depending on the choice selected
			if ($dtat_cat=="Days")
				{
				$com_timeframe = $dtat*24*60*60;
				}
			if ($dtat_cat=="Hours")
				{
				$com_timeframe = $dtat*60*60;
				}
			//now, lets add the record
			$sql_wf="UPDATE wfproc SET wfprocname='".$dwfname."',wfproctat='".$com_timeframe."',wfprocdesc='".$ddesc."',mobileaccess='".$dmobacc."',wfstatus='".$dstatus."',modifiedby='".$_SESSION['MVGitHub_idacname']."',modifiedon='".$timenowis."'
			WHERE idwfproc=".$_SESSION['wfselected']." LIMIT 1";
			mysql_query($sql_wf);
			
			?>
			<script language="javascript">
			alert ('<?php echo $msg_workflow_updated;?>');
			window.location='<?php echo "index.php?uction=edit_workflow&wf=".$_SESSION['wfselected']."&fa=saved";?>';
			</script>		
			<?php
			} //close if no error
		
	} //close if form is submitted
?>
<div>
	<div class="bg_section">
    <?php echo $fet_heading['modulename']; ?> &raquo; <a href="index.php?uction=view_wfs"><?php echo $fet_heading['submodule']; ?></a>
    </div>
    <div>
    <?php
	if (isset($delerror_1)) { echo $delerror_1; }
	if (isset($delmsg)) { echo $delmsg; }
	?>
    </div>
    <?php
	if ($num_wf < 1) //if not owner, then warn
		{
	?>
    <span class="msg_warning"><?php echo $msg_warn_violation;?></span>
	<?php	} else { // else if owner, then proceed	?>
    <div>
    	<div style="padding:5px 5px 10px 5px">
		<div class="tbl_h" style="padding:5px 0px 5px 5px">
		<?php echo $lbl_wfbasicinfo; ?>
        <div style="right:30px; top:135px; position:absolute">
        <?php 
		if ((!isset($_GET['fa'])) || ($_GET['fa']!="edit") ) { //show only if edit is not set ?>
       <!-- <a href="index.php?uction=edit_workflow&amp;fa=edit" id="button_edit_small"></a>-->
        <a href="#" id="button_edit_small" onclick="tb_open_new('pop_workflow_basicinfo_edit.php?uction=edit_workflow&amp;fa=edit&amp;title=New_Item&amp;keepThis=true&amp;TB_iframe=true&amp;height=460&amp;width=800&amp;modal=true')">
        </a>
        <?php } ?>
        </div>
        </div>
        <div>
        <div>
        <?php
		if ( (isset($error_1)) || (isset($error_2)) || (isset($error_3)) || (isset($error_4)) )
			{
			if (isset($error_1)) { echo $error_1; }
			if (isset($error_2)) { echo $error_2; }
			if (isset($error_3)) { echo $error_3; }
			if (isset($error_4)) { echo $error_4; }
			}
		if (isset($_SESSION['delerror_1']))
			{
			echo $_SESSION['delerror_1'];
			}
		if (isset($_SESSION['delmsg']))
			{
			echo $_SESSION['delmsg'];
			}
		?>
        </div>
        
        <?php
		 if ((isset($_GET['fa'])) && ($_GET['fa']=="edit")) { ?>
       <form method="post" action="" name="wfbasics">
        	<table border="0" cellpadding="3" cellspacing="0">
				<tr>
                	<td width="171" height="35" class="tbl_data">
                    <strong><?php echo $lbl_wfname;?></strong>
                    </td>
              <td width="336" height="35" class="tbl_data">
			  <input type="text" maxlength="60" size="40" value="<?php echo $fet_wf['wfprocname'];?>" name="wfname" /></td>
           	  </tr>
              <tr>
              	<td class="tbl_data">
                  <strong>
                  <?php 
				echo $lbl_wftype;
				?>
                  </strong> </td>
                <td class="tbl_data">
                <select name="wftype">
                <?php
				$sql_wftype="SELECT idwftype,wftypelbl,wftypedesc,recstatus FROM wftype";
				$res_wftype=mysql_query($sql_wftype);
				$fet_wftype=mysql_fetch_array($res_wftype);
				
				do {
				?>
                <option <?php if ($fet_wftype['recstatus']==0) { echo "disabled=\"disabled\""; }  if ($fet_wf['wftype_idwftype']==$fet_wftype['idwftype']) { echo "selected=\"selected\""; }?> value="<?php echo $fet_wftype['idwftype'];?>" title="<?php echo $fet_wftype['wftypedesc'];?>"><?php echo $fet_wftype['wftypelbl'];?></option>
                <?php
				} while ($fet_wftype=mysql_fetch_array($res_wftype));
				?>
                </select>
                </td>
          </tr>
                <tr>
                	<td class="tbl_data">
                   <strong> <?php echo $lbl_description;?></strong>                   </td>
                  <td class="tbl_data">
                    <textarea cols="30" rows="3" name="wfdesc"><?php if (isset($_POST['wfdesc'])){ echo $_POST['wfdesc'];} if (!isset($_POST['wfdesc'])){ echo $fet_wf['wfprocdesc']; }?></textarea>                  </td>
                </tr>
                 <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_tat;?></strong>                    </td>
                   <td height="30" class="tbl_data">
                    <input onkeyup="res(this,numb);" type="text" value="<?php if ( (isset($_POST['tat'])) && ($_POST['tat'])) { echo $_POST['tat'];} ?><?php if (!isset($_POST['tat'])) { if ($fet_wf['wfproctat']>"172800"){ echo ($fet_wf['wfproctat']/(60 * 60 * 24));} else { echo ($fet_wf['wfproctat']/(60 * 60));} }?>" size="4" maxlength="2" name="tat" /> 
                    <select name="tat_cat">
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="")){ echo "selected=\"selected\""; } ?> value="">---</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Hours")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_wf['wfproctat']<="172800"){ echo "selected=\"selected\""; } } ?> value="Hours">Hours</option>
                    <option <?php if ( (isset($_POST['tat_cat'])) && ($_POST['tat_cat']=="Days")){ echo "selected=\"selected\""; } ?><?php if (!isset($_POST['tat_cat'])){ if ($fet_wf['wfproctat']>="172800"){ echo "selected=\"selected\""; } } ?> value="Days">Days</option>
                    </select>                   </td>
              </tr>
              <tr>
                	<td class="tbl_data" height="30">
                    <strong><?php echo $lbl_status;?></strong>
                    </td>
                    <td class="tbl_data">
                    <select name="wfstate">
                    	<option value="1" <?php if ($fet_wf['wfstatus']==1) { echo "selected=\"selected\""; }?>>ON (Active &amp; Running)</option>
                        <option value="0" <?php if ($fet_wf['wfstatus']==0) { echo "selected=\"selected\""; }?>>OFF (Inactive)</option>
                    </select>
                    <?php
					
					?>
                    </td>
              </tr>
                 <tr>
                	<td class="tbl_data" height="30">
                    <!--<strong><?php //echo $lbl_mobileaccess;?></strong>-->                    </td>
                   <td height="30" class="tbl_data">
                    <input type="hidden" value="1" name="mobacc"   />
                    </td>
              </tr>
                <tr>
                	<td colspan="2">
                    <table border="0" style="margin:5px 10px 5px 20px">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['wfbasics'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save" name="formaction" />
                               <a href="index.php?uction=edit_workflow" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>                    </td>
                </tr>
			</table>
		</form>
        <?php } else { ?>
        <table border="0" cellpadding="3" cellspacing="0">
				<tr>
                	<td width="171" height="35" class="tbl_data">
                    <strong><?php echo $lbl_wfname;?></strong>
                    </td>
              <td width="336" height="35" class="tbl_data">
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
        
        <?php } ?>
        </div>
        </div>
        <?php

			//display the process menu here 
			$sql_flow="SELECT idwftskflow,idwfsymbol,wfsymbolname,wfsymbol_imgpath,wfsymboldesc,wftskflowname,wftskflowdesc,idwfsymbol,wftsktat,listorder FROM wftskflow 
			INNER JOIN wfsymbol ON wftskflow.wfsymbol_idwfsymbol=wfsymbol.idwfsymbol WHERE wfproc_idwfproc=".$_SESSION['wfselected']." ORDER BY listorder ASC";
			$res_flow=mysql_query($sql_flow);
			$num_flow=mysql_num_rows($res_flow);
			$fet_flow=mysql_fetch_array($res_flow);
			?>
        <div>
        	<div class="tbl_h" style="padding:5px 0px 5px 5px"><?php echo $lbl_wfdesign;?> : <?php echo $fet_wf['wfprocname'];?></div>
           <div>
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
                        <li><a href="#" onclick="tb_open_new('pop_newworkflow_properties.php?wftskid=new_item&amp;title=New_Item&amp;symbol=<?php echo $fet_symbol['idwfsymbol'];?>&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=460&amp;width=800&amp;modal=true')" class="tooltip"><img style="padding:2px; margin:2px" width="42" height="30" src="../assets_backend/bpm_icon/<?php echo $fet_symbol['wfsymbol_imgpath'];?>" border="0" align="absmiddle" /><?php echo $fet_symbol['wfsymbolname'];?><span><?php echo $fet_symbol['wfsymboldesc'];?></span></a></li>
                        <?php } ?>
                    <?php
                        } while ($fet_symbol=mysql_fetch_array($res_symbol));
                    }
                    ?>
            </ul>
		</li>
	</ul>
</div>
<div>

</div>
          </div>
           <div style="color:#FFFFFF">
           -
           </div>
            <div style="padding:10px">
			<?php			
			if ($num_flow>0)
				{
				do {
			?>
            <div class="border_thin" style="text-align:center">
            	<table border="0" cellpadding="0" cellspacing="0" align="center">
                	<tr>
                    	<td align="center">
                       <div class="text_body" style="text-align:center">
                       <table border="0" cellpadding="0" cellspacing="0">
                       	<tr>
                        	<td width="20" style="margin:0; padding:0" >
                        <?php
						if ($fet_flow['idwfsymbol']!=1){ //if not the start event, then you can delete
						?>
                        <a onclick="return confirm('<?php echo $msg_prompt_delete;?>')" href="index.php?uction=edit_workflow&amp;saction=delete_step&amp;r=<?php echo $fet_flow['idwftskflow'];?>" id="button_delete_vsmall"></a>
                        <?php
						}
						?>
                       		</td>
                            <td class="text_small" style="margin:0; padding:0">
                        <?php echo $fet_flow['wftskflowname'];?>
                        	</td>
                            <td>
						<?php  if (($fet_flow['wftsktat']<1) && ($fet_flow['idwfsymbol']!=5)) { ?>
	                    <a class="tooltip"><img src="../assets_backend/icons/warning.gif" border="0" align="absmiddle"  /><span><?php echo $msg_set_wftat;?></span></a>
                        <?php } ?>
                        	</td>
                            </tr>
                            </table>
                        </div>
                        </td>
                        <td></td>
                    </tr>
                	<tr>
                    	<td width="200" height="75">
                        <div>
                        <a href="#" onclick="tb_open_new('pop_workflow_properties.php?wftskid=<?php echo $fet_flow['idwftskflow']; ?>&amp;title=<?php echo $fet_wf['wfprocname']."&nbsp;-&nbsp;".$fet_flow['wftskflowname'];?>&amp;symbol=<?php echo $fet_flow['idwfsymbol'];?>&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=460&amp;width=800&amp;modal=true')" class="tooltip"><img src="../assets_backend/bpm_icon/<?php echo $fet_flow['wfsymbol_imgpath'];?>" border="0" align="absmiddle" /><span><small>[Click to Configure] - </small><?php echo $fet_flow['wftskflowdesc'];?></span></a>
                        </div>
                        </td>
          				<!--	<td width="200" class="text_body" valign="middle" align="left">
                        	<table border="0" cellpadding="0" cellspacing="0">
                            	<tr>
                                	<td style="font-size:12px"><strong></strong></td>
                                </tr>
                                <tr>
                                    <td>
                                    
                                    </td>
                                </tr>
                            </table>
                       </td>-->
                      <td width="50">
                       
                        </td>
                    </tr>
                </table>
            </div>
            <?php
					} while ($fet_flow=mysql_fetch_array($res_flow));
				}
			?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php
//unset the session errors at the end of this page
unset($_SESSION['delerror_1']);
unset($_SESSION['delmsg']);
?>