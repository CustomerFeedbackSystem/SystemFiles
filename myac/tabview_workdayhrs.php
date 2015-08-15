<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

if ((isset($_POST['formaction'])) && ($_POST['formaction']=="save") )
	{
	$t=count($_POST['start_time']);
	
		for ($i=0; $i<$t; $i++) 
			{
			$start_time=$_POST['start_time'][$i];
			$end_time=$_POST['end_time'][$i];
			$id = $_POST['wkday'][$i];
			/*	if (isset($_POST['not_app'][$i]))
					{
					$notapp=1;
					} else {
					$notapp=0;
					}*/
		
			$sql_update="UPDATE wfworkinghrs SET  time_earliest='".$start_time."',time_latest='".$end_time."',notapplicable='0' 
			WHERE idwfworkinghrs=".$id." AND wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
			mysql_query($sql_update);
			}
			
	//next, update the wfproc for excluding holidays
	if (isset($_POST['exhol']))
		{
		$ex=1;
		} else {
		$ex=0;
		}
	$sql_update="UPDATE wftskflow SET expubholidays='".$ex."' WHERE idwftskflow=".$_SESSION['idflow']." LIMIT 1";
	mysql_query($sql_update);
	
	$msg_ok = "<div class=\"msg_success\">".$msg_changes_saved."</div>";
	}
?>
<form method="post" action="" name="wkhrs">
<div>
	<div class="tbl_h">
    Working Hours / Days
    </div>
    <div>
    <?php if (isset($msg_ok))
		{
		echo $msg_ok;
		}
	?>
    </div>
    <?php 
	$sql_workinghrs = "SELECT idwfworkinghrs,workingdaysdesc,wftskflow_idwftskflow,wfworkingdays_idwfworkingdays,time_earliest,time_latest,notapplicable,workingdays FROM wfworkinghrs
	INNER JOIN wfworkingdays ON wfworkinghrs.wfworkingdays_idwfworkingdays=wfworkingdays.idwfworkingdays WHERE wftskflow_idwftskflow=".$_SESSION['idflow']." ORDER BY idwfworkingdays ASC";
	$res_workinghrs = mysql_query($sql_workinghrs);
	$fet_workinghrs = mysql_fetch_array($res_workinghrs);
	$num_workinghrs = mysql_num_rows($res_workinghrs);
	?>
    <div>
    <table width="733" border="0" cellpadding="2" cellspacing="0">
    <?php 
	//no if because there should always be a record for all except end events
		do {
	?>
  <tr>
        	<td width="229" class="tbl_data">
            <input type="hidden" value="<?php echo $fet_workinghrs['idwfworkinghrs'];?>" name="wkday[]" />
            <strong><?php echo $fet_workinghrs['workingdays'];?></strong>
          </td>
          <td width="230" class="tbl_data">
           <?php echo $lbl_from;?> : 
           <input  onchange="validateHhMm(this);" type="text" value="<?php echo substr($fet_workinghrs['time_earliest'],0,5);?>" <?php if ($fet_workinghrs['time_earliest']=='00:00:00'){ echo "style=\"background-color:#fba\"";} ?> maxlength="5" size="5" name="start_time[]" />
           <?php echo $lbl_to;?> : <input onchange="validateHhMm(this);" type="text" value="<?php echo substr($fet_workinghrs['time_latest'],0,5);?>" <?php if ($fet_workinghrs['time_latest']=='00:00:00'){ echo "style=\"background-color:#fba\"";} ?> maxlength="5" size="5" name="end_time[]" />
            </td>
          <td width="262" class="tbl_data">
          <?php
		  if (strlen($fet_workinghrs['workingdaysdesc'])>0) { ?>
          <span class="msg_instructions_small"><small><?php echo $fet_workinghrs['workingdaysdesc']; ?></small></span>
          <?php } ?>
          </td>
        </tr>
       <?php } while ($fet_workinghrs = mysql_fetch_array($res_workinghrs));?>
    </table>
    </div>
    <div class="tbl_h">
    Public Holidays
    </div>
    <div style="padding:5px">
    <?php
	$sql_exhol="SELECT expubholidays FROM wftskflow WHERE idwftskflow=".$_SESSION['idflow']." LIMIT 1";
	$res_exhol=mysql_query($sql_exhol);
	$fet_exhol=mysql_fetch_array($res_exhol);
	?>
    <input name="exhol" type="checkbox" value="1" <?php if ($fet_exhol['expubholidays']==1){ echo "checked=\"checked\""; }?> />
    <?php echo $lbl_holidays;?>
    </div>
</div>
<div>
<table border="0" style="margin:15px 10px 5px 0px" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <a href="#" onclick="document.forms['wkhrs'].submit()" id="button_save"></a>                                </td>
                                <td style="padding:0px 0px 0px 10px">
                                <input type="hidden" value="save" name="formaction" />
                               <a href="<?php echo $_SERVER['PHP_SELF'];?>" id="button_cancel" onclick="return confirm('<?php echo $msg_prompt_sure_cancel;?>');"></a>                                </td>
                            </tr>
                        </table>
</div>
</form>