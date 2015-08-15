<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['idbatch']))
	{
	$_SESSION['idbatch']=mysql_escape_string(trim($_GET['idbatch']));
	}
//process the batch
if  ((isset($_POST['formaction'])) && ($_POST['formaction']=="procbatch") )
	{
	$count=count($_POST['b_idtask']);
	//echo $count;
	
		for ($i=0; $i<$count; $i++) 
			{ //loop
			//clean up variables
			$b_exec=mysql_escape_string(trim($_POST['b_exec'][$i]));
			$b_idtask=mysql_escape_string(trim($_POST['b_idtask'][$i]));
			$b_comment=mysql_escape_string(trim($_POST['b_comment'][$i]));
			$b_prior=mysql_escape_string(trim($_POST['prior'][$i]));
			$b_curr=mysql_escape_string(trim($_POST['curr'][$i]));
			$b_adtotal=mysql_escape_string(trim($_POST['adtotal'][$i]));
			
			//check the execution type selected
			if ($b_exec==9) //if it is return to sender, then do the following for that task
				{ //q
				
				} else { //q else if not return to sender, then perform the following for that task
				
				} //q
			
			} //loop
	}

//CHECK IF I HAVE BEEN DELEGATED TASKS
$sql_delegated="SELECT usrrolename,utitle,fname,lname,wftasksdeleg_meta.idusrrole_from FROM wftasksdeleg_meta 
INNER JOIN usrrole ON wftasksdeleg_meta.idusrrole_from=usrrole.idusrrole
INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
WHERE wftasksdeleg_meta.idusrrole_to=".$_SESSION['MVGitHub_iduserrole']."
AND wftasksdeleg_meta.deleg_status=1";
$res_delegated=mysql_query($sql_delegated);
$num_delegated=mysql_num_rows($res_delegated);
$fet_delegated=mysql_fetch_array($res_delegated);


if ( $num_delegated > 0 )
	{
	$qry_recepient = " (wftasks.usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." OR wftasks.usrrole_idusrrole=".$fet_delegated['idusrrole_from']." ".$_SESSION['idwfgroup']." ) ";
	$delegated_to_me = 1;
	} else {
	$qry_recepient = " (wftasks.usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." ".$_SESSION['idwfgroup'].") ";
	$delegated_to_me = 0;
	}

//construct the query from the DB
/*
$sql_batch="SELECT wftasks.idwftasks,wftasks.tasksubject,tktin.refnumber,tktin.waterac,tktin.sendername,wftasks_batch.batch_no_verbose,wftasks.batch_number,wftasks.wftskflow_idwftskflow,wftskflow.listorder,wftasks.wftskstatustypes_idwftskstatustypes,
(SELECT wfassetsdata.value_choice FROM wfassetsdata WHERE wfprocassets_idwfprocassets=".$prior_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as prior_period, 
(SELECT value_choice FROM wfassetsdata WHERE wfprocassets_idwfprocassets=".$curr_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as curr_period, 
(SELECT value_choice FROM wfassetsdata WHERE wfprocassets_idwfprocassets=".$total_adj." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as adj_total,
(SELECT idwfprocassetsaccess FROM wfprocassetsaccess INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets WHERE wfprocassetsaccess.wftskflow_idwftskflow=wftasks.wftskflow_idwftskflow AND wfprocassets.wfprocdtype_idwfprocdtype=".$fieldtype_approval." ) as approval_choice,
(SELECT wfprocassetsaccess.perm_read FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$prior_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as prior_read,
(SELECT wfprocassetsaccess.perm_write FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$prior_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as prior_write,
(SELECT wfprocassetsaccess.perm_read FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$curr_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as curr_read,
(SELECT wfprocassetsaccess.perm_write FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$curr_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as curr_write,
(SELECT wfprocassetsaccess.perm_read FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$total_adj." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as adjtotal_read,
(SELECT wfprocassetsaccess.perm_write FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$total_adj." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin) as adjtotal_write
FROM wftasks 
INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
INNER JOIN wftasks_batch ON wftasks.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
LEFT JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow
WHERE ".$qry_recepient." AND wftasks.wftasks_batch_idwftasks_batch=".$_SESSION['idbatch']." 
ORDER BY wftasks.batch_number ASC";
*/
$sql_batch="SELECT DISTINCT wftasks.idwftasks,wftasks.tasksubject,tktin.refnumber,tktin_idtktin,tktin.waterac,tktin.sendername,wftasks_batch.batch_no_verbose,tktin.voucher_number as batch_number,wftasks.wftskflow_idwftskflow,wftskflow.listorder,wftasks.wftskstatustypes_idwftskstatustypes,
(SELECT wfassetsdata.value_choice FROM wfassetsdata WHERE wfprocassets_idwfprocassets=".$prior_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as prior_period, 
(SELECT value_choice FROM wfassetsdata WHERE wfprocassets_idwfprocassets=".$curr_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as curr_period, 
(SELECT value_choice FROM wfassetsdata WHERE wfprocassets_idwfprocassets=".$total_adj." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as adj_total,
(SELECT idwfprocassetsaccess FROM wfprocassetsaccess INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets WHERE wfprocassetsaccess.wftskflow_idwftskflow=wftasks.wftskflow_idwftskflow AND wfprocassets.wfprocdtype_idwfprocdtype=".$fieldtype_approval."  LIMIT 1) as approval_choice,
(SELECT wfprocassetsaccess.perm_read FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$prior_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as prior_read,
(SELECT wfprocassetsaccess.perm_write FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$prior_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as prior_write,
(SELECT wfprocassetsaccess.perm_read FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$curr_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as curr_read,
(SELECT wfprocassetsaccess.perm_write FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$curr_period." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as curr_write,
(SELECT wfprocassetsaccess.perm_read FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$total_adj." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as adjtotal_read,
(SELECT wfprocassetsaccess.perm_write FROM wfprocassetsaccess INNER JOIN wfassetsdata ON wfprocassetsaccess.idwfprocassetsaccess=wfassetsdata.wfprocassetsaccess_idwfprocassetsaccess WHERE wfassetsdata.wfprocassets_idwfprocassets=".$total_adj." AND wfassetsdata.tktin_idtktin=wftasks.tktin_idtktin LIMIT 1) as adjtotal_write
FROM wftasks 
INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
LEFT JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow
WHERE tktin.wftasks_batch_idwftasks_batch=".$_SESSION['idbatch']." 
AND ".$qry_recepient." 
GROUP BY wftasks.tktin_idtktin
ORDER BY wftasks.batch_number ASC";
//echo $sql_batch;
$res_batch=mysql_query($sql_batch);
$num_batch=mysql_num_rows($res_batch);
$fet_batch=mysql_fetch_array($res_batch);

if ($num_batch < 1 )
	{
?>
<div style="padding:40px">
	<div class="msg_warning">
    No Records found in this Batch
    </div>
</div>
<?php
	} else {
//create the menu options for the menus on all the rows without having to loop over and over :)
$sql_exec="SELECT idwfprocdtype_approvals,wfprocdtype_approvalslbl FROM wfprocdtype_approvals";
$res_exec=mysql_query($sql_exec);
$fet_exec=mysql_fetch_array($res_exec);

$menu_options="";

do {
	$menu_options .="<option value=\"".$fet_exec['idwfprocdtype_approvals']."\">".$fet_exec['wfprocdtype_approvalslbl']."</option>\r\n";
	} while ($fet_exec=mysql_fetch_array($res_exec));

$menu_approval="<option value=\"0\">----</option>".$menu_options."<option value=\"0\" disabled=\"disabled\">-------------------</option><option value=\"".$val_rts."\">**Return to Sender**</option>\r\n"; //append the return to sender option :)

?>
<div style="padding:0px 0px 100px 0px">
<form method="post" action="" name="batch" >
<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr>
    	<td colspan="9">
        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                    <td>
                    	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                        	<tr>
                            	<td class="text_body" align="left"  style="margin:0px; padding:0px">
                                <div>
                                <span><a href="print_list.php?listtype=" target="_blank" id="btn_printsmall" style="float:left"></a></span>
                                <span> <a href="print_list.php?listtype=" target="_blank" class="tooltip" >&nbsp;Print Form <span>&nbsp;Click to Print Billing Adjustment Batch Control Sheet </span></a></span>                                 </div>
                              </td>
                                </tr>
                        </table>
                   </td>
                   <td align="right" style="padding:0px 35px 0px 0px">
                   <!--<a href="#" onClick="document.forms['batch'].submit();" id="button_procbatch"></a>  -->
                   <a href="#" onClick="alert('Feature Not Available at the Moment')" ><img border="0" align="absmiddle"	src="../assets_backend/btns/btn_processbatch_bw.jpg" /></a>
                   <input type="hidden" name="formaction" value="procbatch">
                   </td>
			  </tr>
            </table>        </td>
    </tr>
<?php
/*
//determine if there are extra columns AFTER CURRENT PERIOD for approval to display under this batch
$sql_precheck="SELECT DISTINCT wftskflow.listorder,wftskflow.wfproc_idwfproc FROM wftasks 
INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow
WHERE wftasks.wftasks_batch_idwftasks_batch=".$_SESSION['idbatch']." ORDER BY wftasks.batch_number ASC";
$res_precheck=mysql_query($sql_precheck);
$fet_precheck=mysql_fetch_array($res_precheck);

//echo $sql_precheck."<br><br>";

$cols="";

//concactenate
do {

	$cols.=" ( wftskflow.wfproc_idwfproc=".$fet_precheck['wfproc_idwfproc']." AND wftskflow.listorder < ".$fet_precheck['listorder']." ) AND ";

} while ($fet_precheck=mysql_fetch_array($res_precheck));

$cols_qry= substr($cols,0,-4);

$sql_extracols="SELECT DISTINCT wfprocassets.wfprocdtype_idwfprocdtype FROM wfprocassetsaccess
INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets 
INNER JOIN wftasks ON wfprocassetsaccess.wftskflow_idwftskflow=wftasks.wftskflow_idwftskflow
INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow
WHERE wfprocassets.wfprocdtype_idwfprocdtype=9  
AND ".$cols_qry."  ";
*/	
//echo $sql_extracols;

	?>
	<tr>
		<td height="35" class="tbl_h2">Serial No.</td>
		<td height="35" class="tbl_h2">Account No.</td>  
		<td  height="35" class="tbl_h2">Customer's Name</td>
		<td  height="35" class="tbl_h2">Voucher No.</td>
		<td  height="35" class="tbl_h2">Prior Period</td>
        <td  height="35" class="tbl_h2">Current Period</td>
        <td  height="35" class="tbl_h2">Total</td>
        <td height="35" align="right" class="tbl_h2"><!--Approval / Comments--></td> 
        <td></td>          
	</tr>  
<?php

do {
?>
      <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
		<td class="tbl_data" >
        <a href="pop_taskview.php?task=<?php echo $fet_batch['idwftasks']; ?>&amp;title=<?php echo $fet_batch['tasksubject']; ?>&amp;tabview=1&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=<?php echo $_SESSION['tb_height'];?>&amp;width=<?php echo $_SESSION['tb_width'];?>&amp;inlineId=hiddenModalContent&amp;modal=true" class="thickbox">
        <img src="../assets_backend/btns/btn_magnify.png" border="0" align="absmiddle" style="padding:0px 2px">
        <?php echo $fet_batch['batch_number'];?>
        </a>
        <input type="hidden" value="<?php echo $fet_batch['idwftasks'];?>" name="b_idtask[]"></td>
        </td>
		<td class="tbl_data" ><strong><?php echo $fet_batch['waterac'];?></strong></td>
		<td class="tbl_data" ><?php echo $fet_batch['sendername'];?></td>
        <td class="tbl_data" ><small><?php echo $fet_batch['batch_no_verbose'];?> / <?php echo $fet_batch['batch_number'];?></small></td>
        <td class="tbl_data" >
        
        <input <?php /*if ($fet_batch['prior_write']==0) {*/ echo "readonly=\"readonly\" style=\"background-color:#cccccc\" "; /* }*/ ?> <?php echo " onKeyUp=\"res(this,numb);\" " ?> type="text" maxlength="11" size="8" name="prior[]" value="<?php if (strlen($fet_batch['prior_period']) > 0) { echo $fet_batch['prior_period']; } else { echo ""; }?>">
        
        </td>
        <td class="tbl_data" ><input <?php /* if ($fet_batch['curr_write']==0){ echo "readonly=\"readonly\" style=\"background-color:#cccccc\" "; }*/ echo "readonly=\"readonly\" style=\"background-color:#cccccc\" "; ?> <?php echo " onKeyUp=\"res(this,numb);\" " ?> type="text" maxlength="11" size="8" name="curr[]" value="<?php if (strlen($fet_batch['curr_period']) > 0) { echo $fet_batch['curr_period']; } else { echo ""; }?>"></td>
        <td class="tbl_data" ><input readonly="readonly" <?php /* if ($fet_batch['adjtotal_write']==0){ echo "readonly=\"readonly\""; }*/ echo "readonly=\"readonly\" style=\"background-color:#cccccc\" "; ?> <?php echo " onKeyUp=\"res(this,numb);\" " ?>  type="text" maxlength="11" size="8" name="adtotal[]" value="<?php if (strlen($fet_batch['adj_total']) > 0) { echo $fet_batch['adj_total']; } else { echo ""; }?>"></td>
        <td class="tbl_data" style="padding:0px;" align="right">
        
		<?php
		//if already passed on, then too late
		 if ($fet_batch['wftskstatustypes_idwftskstatustypes']==2)
					 	{
						//query who has it
						$sql_taskwith="SELECT usrrolename,fname,lname FROM wftasks 
						INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE tktin_idtktin=".$fet_batch['tktin_idtktin']." ORDER BY idwftasks DESC LIMIT 1";
						$res_taskwith=mysql_query($sql_taskwith);
						$fet_taskwith=mysql_fetch_array($res_taskwith);
						
						echo "<div style=\"text-align:right\">
						<span class=\"msg_warning_small\" style=\"text-align:right\"> ".$fet_taskwith['fname']." ".$fet_taskwith['lname']." 
						</span><small>".$fet_taskwith['usrrolename']."</small>
						</div>";
						} else {
		?>
        <!--
       	<table border="0" cellpadding="0" cellspacing="0" align="right">
        	<tr>
            	<td style="padding:2px 0px;" align="right">
                <div style="padding:1px; text-align:left">
                <?php
				if ($fet_batch['approval_choice']>0)
					{					
				?>
                <select name="b_exec[]">
                	<?php echo $menu_approval;?>
                </select>
                	<?php
						}					
					?>
                <span class="text_small">
                <a href="#" title="Hide Comment Box. Your comments are retained" class="hide"><img src="../assets_backend/btns/btn_comm_small2.png" border="0" align="absmiddle"></a>
                <a href="#" title="Display Comment Box" class="show"><img src="../assets_backend/btns/btn_comm_small.png" border="0" align="absmiddle"></a>
                </span>
                </div>
                <div style="text-align:right; padding:1px">
                <comment>
                <textarea name="b_comment[]" cols="19" rows="3"><?php  ?></textarea>
                </comment>
                </div>
                </td>
            </tr>
        </table>
        -->
        	<?php
			 } //not too late
			?>
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
?> 
<?php

 } while ($fet_batch=mysql_fetch_array($res_batch));
?>    
</table>
</form>

<?php
}
//echo $sql_batch;
?>
</div>