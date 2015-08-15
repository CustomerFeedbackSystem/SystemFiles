<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//wpheader
if (isset($_GET['wpid']))
	{
	$_SESSION['idworkplan']=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_GET['wpid'])));
	}

if ((isset($_GET['qction'])) && ($_GET['qction']=="delete"))
	{
	$recid=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_GET['wplanid'])));

	//delete only if owner of the record
	$sql="DELETE FROM wpdetails WHERE wpheader_idwpheader=".$_SESSION['idworkplan']." AND idwpdetails=".$recid." LIMIT 1";
	mysql_query($sql);
	
	$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
	
	}

if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="save") )
	{
	//clean up the values
	$activity=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['activitytype'])));
	$qtr=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['qtr'])));
	$tnumber=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['tnumber'])));
	$tbudget=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['tbudget'])));
	
	//check if the required fields are keyed in
	if ( ($qtr < 1) || ($tnumber<1) || ($tbudget<1) )
		{
		//then show error
		$error="<div class=\"msg_warning\">".$msg_warning_afr."</div>";
		
		} else { //else process
		//check if similar record already exists
		$sql_exist="SELECT idwpdetails FROM wpdetails WHERE tktactivitytype_idtktactivitytype=".$activity." AND wpquarters_idwpquarters=".$qtr." LIMIT 1";
		$res_exist=mysql_query($sql_exist);
		$num_exist=mysql_num_rows($res_exist);
		
		if ($num_exist > 0)
			{
			
			$error="<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
			
			} else {
			
			//insert the record if it does not exist
			$sql_newd="INSERT INTO wpdetails (wpquarters_idwpquarters,tktactivitytype_idtktactivitytype,wpheader_idwpheader,value_number,value_budget,createdby,createdon) 
			VALUES ('".$qtr."','".$activity."','".$_SESSION['idworkplan']."','".$tnumber."','".$tbudget."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_newd);
			
			$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
			
			}
		
		}
	
	//validate to sees if record already exists
	}
	
/////////////////////////update
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="update") )
	{
	//clean up the values
	$activity=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['activitytype'])));
	$qtr=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['qtr'])));
	$tnumber=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['tnumber'])));
	$tbudget=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['tbudget'])));
	$yearfrom=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['yrfrom'])));
	$yearto=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_POST['yrto'])));
	
	//check if the required fields are keyed in
	if ( ($qtr < 1) || ($tnumber<1) || ($tbudget<1) || (strlen($yearfrom) < 4) || (strlen($yearto) < 4) )
		{
		//then show error
		$error="<div class=\"msg_warning\">".$msg_warning_afr."</div>";
		
		} else { //else process
		
		//check if similar record already exists
		$sql_exist="SELECT idwpdetails,wpquarters_idwpquarters FROM wpdetails WHERE tktactivitytype_idtktactivitytype=".$activity." AND wpheader_idwpheader=".$_SESSION['idworkplan']." AND wpquarters_idwpquarters!=".$qtr." LIMIT 1";
		$res_exist=mysql_query($sql_exist);
		$num_exist=mysql_num_rows($res_exist);
		$fet_exist=mysql_fetch_array($res_exist);

		if ($num_exist > 0)
			{
			
			$error="<div class=\"msg_warning\">".$msg_warning_duplicate_wf."</div>";
			
			} else {
			
			//update the record if it does not exist
			$sql_newd="UPDATE wpdetails SET 
			wpquarters_idwpquarters='".$qtr."',
			tktactivitytype_idtktactivitytype='".$activity."',
			value_number='".$tnumber."',
			value_budget='".$tbudget."',
			modifiedby='".$_SESSION['MVGitHub_idacname']."',
			createdon='".$timenowis."' WHERE idwpdetails=".$_SESSION['idwpdetail']."";
			mysql_query($sql_newd);
			
			//update the wpheader information if changed as well
			
			$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
			
			}
		
		}
	
	}



//pull the details from the workplan header
$sql_wpdetails="SELECT idwpheader,yearfrom,yearto,usrname,enteredon FROM wpheader
INNER JOIN usrac ON wpheader.enteredby=usrac.idusrac WHERE idwpheader=".$_SESSION['idworkplan']." AND wpheader.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
$res_wpdetails=mysql_query($sql_wpdetails);
$fet_wpdetails=mysql_fetch_array($res_wpdetails);
$num_wpdetails=mysql_num_rows($res_wpdetails);

if ($num_wpdetails > 0)
	{
?>
<div>
	<div class="bg_section">
	<?php echo $fet_heading['modulename']; ?> &raquo; <?php echo $fet_heading['submodule']; ?>
	</div>
    <div style="padding:10px 0px 10px 0px">
    <?php echo "<span class=\"msg_instructions\">".$ins_wp_keyin."</span>";?>
    </div>
    <table border="0" width="100%">
        	<tr>
            	<td valign="top" width="40%">
                <?php
				if (!isset($_REQUEST['altview'])) { 
				?>
                <form method="post" action="" name="wp" >
                 <div style="padding:10px 0px 0px 0px"> 
                    <table border="0" cellpadding="5" width="100%" cellspacing="0">
                    <tr>
                        <td height="30"  colspan="4" class="tbl_h2">
                        <?php echo $lbl_wpentry;?>    </td>
                    </tr>
                        <tr>
                            <td height="35" class="tbl_data" >
                            <?php echo $lbl_year_from;?>        </td>
                          <td height="35" class="tbl_data" >
                            <input readonly="readonly" style="background-color:#CCCCCC" onKeyUp="res(this,numb);" value="<?php echo $fet_wpdetails['yearfrom'];?>"  type="text" maxlength="4" name="yrfrom" size="10">                            </td>
                          <td height="35" class="tbl_data" >
                            <?php echo $lbl_year_to;?>        </td>
                          <td height="35" class="tbl_data" >
                          <input readonly="readonly" style="background-color:#CCCCCC" onKeyUp="res(this,numb);"  value="<?php echo $fet_wpdetails['yearto'];?>"   type="text" maxlength="4" name="yrto" size="10">
                          </td>
                      </tr>
                      <tr>
                      	<td class="tbl_data">
                        <?php echo $lbl_createdby;?>
                        </td>
                        <td colspan="3" class="tbl_data">
                        <?php echo $fet_wpdetails['usrname'];?>
                        </td>
                      </tr>
                      <tr>
                      	<td class="tbl_data">
                        <?php echo $lbl_timeaction;?>
                        </td>
                        <td colspan="3" class="tbl_data">
                        <?php echo date("D, M d, Y H:i",strtotime($fet_wpdetails['enteredon'])); ?>
                        </td>
                      </tr>
                    </table>
                        </div>
                        <?php
						if (isset($error)) { echo $error;} 
						if (isset($msg)) { echo $msg;} 
						?>
                        <div style="padding:10px 0px 10px 0px">
                            <table border="0" width="100%" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td height="30" colspan="2" class="tbl_h2">
                                    <?php echo $lbl_wpactdetails;?> 
                                    </td>
                                </tr>
                                 <tr>
                                    <td width="145" class="tbl_data">
                                    <?php echo $lbl_report_activity;?>
                                    </td>
                                    <td class="tbl_data">
                                    <?php
									$sql_activitylist="SELECT  idtktactivitytype,tktactivityclass_idtktactivityclass,activitytype,activitytypedesc FROM tktactivitytype WHERE tktactivityclass_idtktactivityclass=1 ";
									$res_activitylist=mysql_query($sql_activitylist);
									$fet_activitylist=mysql_fetch_array($res_activitylist);
										
									?>
                                    <select name="activitytype">
                                    	<option value="">----</option>
                                        <?php
							do {
							echo "<option value=\"".$fet_activitylist['idtktactivitytype']."\">".$fet_activitylist['activitytype']."</option>";
							} while ($fet_activitylist=mysql_fetch_array($res_activitylist));
							?>
                                    </select>
                                    </td>
                                 </tr>
                                <tr>
                                    <td width="145" class="tbl_data">
                                    <?php echo $lbl_wquarter;?>
                                    </td>
                                    <td width="201" class="tbl_data">
                                    <select name="qtr">
                                    <option value="">----</option>
                                    <?php 
									$sql_qtr="SELECT * FROM wpquarters";
									$res_qtr=mysql_query($sql_qtr);
									$fet_qtr=mysql_fetch_array($res_qtr);
									do {
									echo "<option value=\"".$fet_qtr['idwpquarters']."\">".$fet_qtr['wpquarter']."</option>";
									} while ($fet_qtr=mysql_fetch_array($res_qtr));
									?>
                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                	<td class="tbl_data">
                                    <?php echo $lbl_tnumber;?>
                                    </td>
                                    <td class="tbl_data">
                                    <input onKeyUp="res(this,numb);"  type="text" maxlength="11" name="tnumber" size="10">
                                    </td>
                                </tr>
                                <tr>
                                	<td class="tbl_data">
                                    <?php echo $lbl_tbudget;?>
                                    </td>
                                    <td class="tbl_data">
                                    <input onKeyUp="res(this,numb);"  type="text" maxlength="9" name="tbudget" size="10">
                                    </td>
                                </tr>
                                <tr>
                                	<td height="45">
                                    </td>
                                    <td>
                                    <a href="#" onclick="document.forms['wp'].submit()" id="button_save"></a>
                                    <input type="hidden" value="save" name="formaction" />
                                    </td>
                                </tr>
                            </table>
                      </div>
                      
                </form>
                <?php
				}
				?>
                <?php
if ( (isset($_GET['wplanid'])) && (isset($_GET['altview'])) && ($_GET['altview']=="edit") )
	{

$_SESSION['idwpdetail']=preg_replace('/[^a-z\-_0-9\.:@\/\s]/i','',mysql_escape_string(trim($_GET['wplanid'])));
	
$sql_activities="SELECT idwpdetails,activitytype,value_number,value_budget,tktactivitytype_idtktactivitytype,wpquarters_idwpquarters FROM wpdetails 
INNER JOIN tktactivitytype ON wpdetails.tktactivitytype_idtktactivitytype=tktactivitytype.idtktactivitytype 
WHERE wpheader_idwpheader=".$_SESSION['idworkplan']." AND idwpdetails=".$_SESSION['idwpdetail']." ";
$res_activities=mysql_query($sql_activities);
$fet_activities=mysql_fetch_array($res_activities);
$num_activities=mysql_num_rows($res_activities);
?>
 <form method="post" action="" name="wp_update" class="border_thin">
                         <?php
	if (isset($error)) { echo $error;} 
	if (isset($msg)) { echo $msg;} 
						?>
                 <div style="padding:10px 0px 0px 0px"> 
                    <table border="0" cellpadding="5" width="100%" cellspacing="0">
                    <tr>
                        <td height="30"  colspan="4" class="tbl_h2">
                        <?php echo $lbl_wpentry;?>    </td>
                    </tr>
                        <tr>
                            <td height="35" class="tbl_data" >
                            <?php echo $lbl_year_from;?>        </td>
                          <td height="35" class="tbl_data" >
                            <input  onKeyUp="res(this,numb);" value="<?php echo $fet_wpdetails['yearfrom'];?>"  type="text" maxlength="4" name="yrfrom" size="10">                            </td>
                          <td height="35" class="tbl_data" >
                            <?php echo $lbl_year_to;?>        </td>
                          <td height="35" class="tbl_data" >
                          <input  onKeyUp="res(this,numb);"  value="<?php echo $fet_wpdetails['yearto'];?>"   type="text" maxlength="4" name="yrto" size="10">
                          </td>
                      </tr>
                      <tr>
                      	<td class="tbl_data">
                        <?php echo $lbl_createdby;?>
                        </td>
                        <td colspan="3" class="tbl_data">
                        <?php echo $fet_wpdetails['usrname'];?>
                        </td>
                      </tr>
                      <tr>
                      	<td class="tbl_data">
                        <?php echo $lbl_timeaction;?>
                        </td>
                        <td colspan="3" class="tbl_data">
                        <?php echo date("D, M d, Y H:i",strtotime($fet_wpdetails['enteredon'])); ?>
                        </td>
                      </tr>
                    </table>
                        </div>

                        <div style="padding:10px 0px 10px 0px">
                            <table border="0" width="100%" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td height="30" colspan="2" class="tbl_h2">
                                    <?php echo $lbl_wpactdetails_edit;?> 
                                    </td>
                                </tr>
                                 <tr>
                                    <td width="145" class="tbl_data">
                                    <?php echo $lbl_report_activity;?>
                                    </td>
                                    <td class="tbl_data">
                                    <?php
									$sql_activitylist="SELECT  idtktactivitytype,tktactivityclass_idtktactivityclass,activitytype,activitytypedesc FROM tktactivitytype WHERE tktactivityclass_idtktactivityclass=1 ";
									$res_activitylist=mysql_query($sql_activitylist);
									$fet_activitylist=mysql_fetch_array($res_activitylist);
										
									?>
                                    <select name="activitytype">
                                    	<option value="">----</option>
                                        <?php
							do {
							echo "<option "; 
								if ($fet_activities['tktactivitytype_idtktactivitytype']==$fet_activitylist['idtktactivitytype'])
									{
									echo " selected=\"selected\" ";
									}
							echo " value=\"".$fet_activitylist['idtktactivitytype']."\">".$fet_activitylist['activitytype']."</option>";
							} while ($fet_activitylist=mysql_fetch_array($res_activitylist));
							?>
                                    </select>
                                    </td>
                                 </tr>
                                <tr>
                                    <td width="145" class="tbl_data">
                                    <?php echo $lbl_wquarter;?>
                                    </td>
                                    <td width="201" class="tbl_data">
                                    <select name="qtr">
                                    <option value="">----</option>
                                    <?php 
									$sql_qtr="SELECT * FROM wpquarters";
									$res_qtr=mysql_query($sql_qtr);
									$fet_qtr=mysql_fetch_array($res_qtr);
									do {
									echo "<option";
										if ($fet_activities['wpquarters_idwpquarters']==$fet_qtr['idwpquarters'])
											{
											echo " selected=\"selected\" ";
											}
									echo " value=\"".$fet_qtr['idwpquarters']."\">".$fet_qtr['wpquarter']."</option>";
									} while ($fet_qtr=mysql_fetch_array($res_qtr));
									?>
                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                	<td class="tbl_data">
                                    <?php echo $lbl_tnumber;?>
                                    </td>
                                    <td class="tbl_data">
                                    <input onKeyUp="res(this,numb);" value="<?php echo $fet_activities['value_number'];?>" type="text" maxlength="11" name="tnumber" size="10">
                                    </td>
                                </tr>
                                <tr>
                                	<td class="tbl_data">
                                    <?php echo $lbl_tbudget;?>
                                    </td>
                                    <td class="tbl_data">
                                    <input onKeyUp="res(this,numb);"  value="<?php echo $fet_activities['value_budget'];?>"   type="text" maxlength="9" name="tbudget" size="10">
                                    </td>
                                </tr>
                                <tr>
                                	<td></td>
                                    <td height="45">
                                    
                                    	<table border="0" width="100%" cellpadding="0">
                                        	<tr>
                                            	<td width="49%">
                                                 <a href="#" onclick="document.forms['wp_update'].submit()" id="button_save"></a>
                                    <input type="hidden" value="update" name="formaction" />
                                                </td>
                                              <td width="51%">
                                                <a href="<?php echo $_SERVER['PHP_SELF'];?>?altview=view" id="button_cancel"></a>                                                </td>
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
                
                </td>
                <td valign="top" width="60%" style="padding:10px 0px 10px 0px">
                <div style="padding:6px 0px 6px 5px; margin:0px 0px 0px 0px" class="table_header">
                    <?php echo $lbl_wpdetails;?>
                    </div>
                    <div style="padding:10px 0px 0px 5px">
                    <?php
                    //list of activities against quarters
                    $sql_qs="SELECT idwpquarters,wpquarter FROM wpquarters ORDER BY list_order ASC";
                    $res_qs=mysql_query($sql_qs);
                    $fet_qs=mysql_fetch_array($res_qs);
                    $num_qs=mysql_num_rows($res_qs);
                        
                    if ($num_qs > 0)
                        {
                            do {
                            
                                //get the array for this quarter
                                $sql_activities="SELECT idwpdetails,activitytype,value_number,value_budget FROM wpdetails 
                                INNER JOIN tktactivitytype ON wpdetails.tktactivitytype_idtktactivitytype=tktactivitytype.idtktactivitytype 
                                WHERE wpquarters_idwpquarters=".$fet_qs['idwpquarters']." AND wpheader_idwpheader=".$_SESSION['idworkplan']." ";
                                $res_activities=mysql_query($sql_activities);
                                $fet_activities=mysql_fetch_array($res_activities);
                                $num_activities=mysql_num_rows($res_activities);
                            
                                if ($num_activities > 0)
                                    {
                            
                                echo "<div class=\"tbl_sh\" style=\"margin:15px 0px 0px 0px\">\r\n";
                                echo $fet_qs['wpquarter'];
                                echo "</div>\r\n";
                                echo "<div>\r\n";
                                echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">\r\n";
                                echo "<tr>\r\n<td class=\"divcol\" width=\"40%\">".$lbl_activity."</td>\r\n<td width=\"20%\" class=\"divcol\">".$lbl_tnumber."</td>\r\n<td  width=\"20%\" class=\"divcol\">".$lbl_tbudget."</td>\r\n<td class=\"divcol\"  width=\"10%\"></td>\r\n<td width=\"10%\" class=\"divcol\"></td>\r\n</tr>\r\n";
                                    
                                            do {
                                                echo "<tr>\r\n";
                                                echo "<td class=\"tbl_data\">".$fet_activities['activitytype']."</td>\r\n";
                                                echo "<td class=\"tbl_data\">".$fet_activities['value_number']."</td>\r\n";
                                                echo "<td class=\"tbl_data\">".number_format($fet_activities['value_budget'],2)."</td>\r\n";
                                                echo "<td class=\"tbl_data\"><a href=\"".$_SERVER['PHP_SELF']."?altview=edit&amp;wplanid=".$fet_activities['idwpdetails']."\" id=\"button_edit_small\"></a></td>\r\n";
                                                echo "<td class=\"tbl_data\"><a onclick=\"return confirm('".$msg_prompt_delete."');\" href=\"".$_SERVER['PHP_SELF']."?qction=delete&amp;wplanid=".$fet_activities['idwpdetails']."\" id=\"button_delete_small\"></a></td>\r\n</tr>\r\n";
                                            
                                            } while ($fet_activities=mysql_fetch_array($res_activities));
                                    
                                echo "</table>\r\n";
                            echo "</div>\r\n";
							 }
                           } while ($fet_qs=mysql_fetch_array($res_qs));
                        } else {
                        echo "---";
                        }
                    ?>
                    </div>
                </td>
           </tr>
        </table>
    </div>

</div>
<?php
} //close if num is greater
?>