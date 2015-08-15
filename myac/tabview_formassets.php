<?php
require_once('../assets_backend/be_includes/check_login_easy.php');

//DELETES
//delete form item
if ( (isset($_GET['saction'])) && ($_GET['saction']=="delete_form_item"))
	{
	//first, clean up the value
	$delval=mysql_escape_string(trim($_GET['iditem']));
	
	//then check if this userteam owns this record
	$sql_owner="SELECT wfproc.usrteam_idusrteam FROM wfprocassets INNER JOIN wfproc ON wfprocassets.wfproc_idwfproc=wfproc.idwfproc WHERE  wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	
	if (mysql_num_rows($res_owner) < 1)
		{
		echo "<div class=\"msg_warning\" >".$msg_warn_violation."</div>";
		$error_delete_1="FAIL";
		}
		
	//then check if there is a record saved / associated with this field
	if (!isset($error_delete_1)) //only check if the above error is not set... save the db some unncessary hits :)
		{
		$sql_record="SELECT idwfassetsdata FROM wfassetsdata WHERE wfprocassets_idwfprocassets=".$delval." LIMIT 1";
		$res_record=mysql_query($sql_record);
		
		if (mysql_num_rows($res_record)>0)
			{
			echo "<div class=\"msg_warning\" >".$ec12."</div>";
			$error_delete_2="FAIL";
			}
		}
	
	//if OK to all the above, then delete
	if ( (!isset($error_delete_1)) && (!isset($error_delete_2)) )
		{
		$sql_delete="DELETE FROM wfprocassets WHERE idwfprocassets=".$delval." LIMIT 1";
		mysql_query($sql_delete);
		
		$sql_delete_access="DELETE FROM wfprocassetsaccess WHERE wfprocassets_idwfprocassets=".$delval." ";
		mysql_query($sql_delete_access);
		
		//if it happends to be a menu list, delete all the related menus if they have no data saved in the wfdata table
		$sql_delete_choices="DELETE FROM wfprocassetschoice WHERE wfprocassets_idwfprocassets=".$delval."";
		mysql_query($sql_delete_choices);
		
		echo "<div class=\"msg_success\" >".$msg_changes_saved."</div>";		
		}
	
	}
	
	
	
	//delete form choice
if ( (isset($_GET['saction'])) && ($_GET['saction']=="delchoice"))
	{
	//first, clean up the value
	$delval=mysql_escape_string(trim($_GET['choiceid']));
	
	//then check if this userteam owns this record
	$sql_owner="SELECT wfproc.usrteam_idusrteam FROM wfprocassetschoice 
	INNER JOIN 	wfprocassets ON wfprocassetschoice.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
	 INNER JOIN wfproc ON wfprocassets.wfproc_idwfproc=wfproc.idwfproc WHERE  wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	
	if (mysql_num_rows($res_owner) < 1)
		{
		echo "<div class=\"msg_warning\" >".$msg_warn_violation."</div>";
		$error_delete_1="FAIL";
		}
		
	//then check if there is a record saved / associated with this field
	if (!isset($error_delete_1)) //only check if the above error is not set... save the db some unncessary hits :)
		{
		$sql_record="SELECT idwfassetsdata FROM wfassetsdata WHERE wfprocassets_idwfprocassets=".$delval." LIMIT 1";
		$res_record=mysql_query($sql_record);
		
		if (mysql_num_rows($res_record)>0)
			{
			echo "<div class=\"msg_warning\" >".$ec12."</div>";
			$error_delete_2="FAIL";
			}
		}
	
	//if OK to all the above, then delete
	if ( (!isset($error_delete_1)) && (!isset($error_delete_2)) )
		{
		$sql_delete="DELETE FROM wfprocassetschoice WHERE idwfprocassetschoice=".$delval." LIMIT 1";
		mysql_query($sql_delete);
		
		echo "<div class=\"msg_success\" >".$msg_changes_saved."</div>";		
		}
	}
	
//INSERT NEW FORM GROUP
if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="add_newfrmgrp") )
	{
	//clean it up
	$groupname=mysql_escape_string(trim($_POST['frmgrouplbl']));
	
	if (strlen($groupname)<2)
		{
		$msg_error="<div class=\"msg_warning\">Please enter a valid Form Items Group Name</div>";
		}
	
	if (!isset($msg_error))
		{
		//check if unique
		$sql_uniquegroup="SELECT wfprocassetsgrouplbl FROM wfprocassetsgroup 
		WHERE wfprocassetsgrouplbl='".$groupname."' AND userteam_owner=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
		$res_uniquegroup=mysql_query($sql_uniquegroup);
		$num_uniquegroup=mysql_num_rows($res_uniquegroup);
		
		
		if ($num_uniquegroup < 1)
			{
			//process the request
			$sql_newgroup="INSERT INTO wfprocassetsgroup(wfprocassetsgrouplbl,createdon,createdby,userteam_owner) 
			VALUES ('".$groupname."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."','".$_SESSION['MVGitHub_idacteam']."')";
			mysql_query($sql_newgroup);
			
			$msg="<div class=\"msg_success\">Form Item Group has been added successfully</div>";
			}
		}
	
	}
	

//
if (isset($_GET['wfitemgroupid']))
	{
	$_SESSION['wfitemgroupid']=trim($_GET['wfitemgroupid']);
	}
	
	
//UPDATE NEW FORM GROUP
if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="edit_newfrmgrpname") )
	{
	//echo $_POST['form_action'];
	//clean it up
	$groupname=mysql_escape_string(trim($_POST['frmgrouplbl']));
	$_SESSION['wfgroupitemid']=mysql_escape_string(trim($_GET['wfitemgroupid']));
	
	
	if (strlen($groupname)<2)
		{
		$msg_error="<div class=\"msg_warning\">Please enter a valid Form Items Group Name</div>";
		}
	
	if (!isset($msg_error))
		{
		//check if unique
		$sql_uniquegroup="SELECT wfprocassetsgrouplbl FROM wfprocassetsgroup 
		WHERE wfprocassetsgrouplbl='".$groupname."' AND userteam_owner=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
		$res_uniquegroup=mysql_query($sql_uniquegroup);
		$num_uniquegroup=mysql_num_rows($res_uniquegroup);
		$fet_uniquegroup=mysql_num_rows($res_uniquegroup);
		
		
		if (($num_uniquegroup < 1) && ($fet_uniquegroup['wfprocassetsgrouplbl']!=$groupname) )
			{
			//process the request
			$sql_updategroup="UPDATE wfprocassetsgroup SET wfprocassetsgrouplbl='".$groupname."',
			modifiedon='".$timenowis."',
			modifiedby='".$_SESSION['MVGitHub_idacname']."'
			WHERE userteam_owner=".$_SESSION['MVGitHub_idacteam']." AND idwfprocassetsgroup=".$_SESSION['wfgroupitemid']." LIMIT 1";
			mysql_query($sql_updategroup);
			
			$msg="<div class=\"msg_success\">Form Item Group has been updated successfully</div>";
			}
		}
	
	}	
	
	
//UPDATE
if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="edit_record") )
	{

	//clean up values
	$dtype=mysql_escape_string(trim($_POST['dtype']));
	$dorder=mysql_escape_string(trim($_POST['ordering']));
	$formilbl=mysql_escape_string(trim($_POST['formilbl']));
	$itempos=mysql_escape_string(trim($_POST['position']));
	
	if (isset($_POST['assetname']))
		{
		$assetname=mysql_escape_string(trim($_POST['assetname']));
		} else {
		$assetname="";
		}
	
	//if a choice, then run the following
	if ($dtype==2)
			{
			$count=count($_POST['choice']);
			
			$choice_insert="";
			$choice_insert_flag="";
			//loop
			for ($i=0; $i<$count; $i++) 
				{
				//check if theres content of the choice
				if (strlen($_POST['choice'][$i]) > 0)
					{
					$choice_insert_flag.=1;
					$choice_insert.="'".mysql_escape_string(trim($_POST['choice'][$i]))."',";
					}
					
				$choice_vals=substr($choice_insert,0,-1);		
				
				}
			
			$choice_vals_qrytemp="(".$choice_vals."),";	
			$choice_vals_qry=substr($choice_vals_qrytemp,0,-1); 
			
			}
	
	//validate the form
	if ($dtype < 1)
		{
		$error_1="<div class=\"msg_warning\">Please select a valid Input Type</div>";
		}
	if (strlen($assetname) < 1)
		{
		$error_2="<div class=\"msg_warning\">Please enter a valid Name for your Form Item</div>";
		}
		
	if ($dtype==2)
			{
//			if ($count < 2) //if the count is less than 2, then warn that one needs to have atleast two options
			if ($choice_insert_flag < 11)
				{
				$error_3="<div class=\"msg_warning\">Please provide at least 2 options for the menu list</div>";
				}
			}
						
	//go ahead and insert else warn
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) )
		{
		//if no errors, then do the following
		$sql_update="UPDATE wfprocassets SET assetname='".$assetname."',wfprocassetsgroup_idwfprocassetsgroup='".$formilbl."',ordering='".$dorder."',item_position='".$itempos."' WHERE idwfprocassets=".$_SESSION['itemid']."  AND wfproc_idwfproc=".$_SESSION['wfselected']."";
		mysql_query($sql_update);
		//echo $sql_update;
		
		//then it happens to be a choice_vals set for dtype 2, then process
		//differentiate between new inserts and updates to old records
			if ((isset($choice_vals_qry)) && (strlen($choice_vals_qry) > 1)) //if the count is greater than 1, (ie at least 2) then process this...
				{ //prepare the insert statement for the choices
				
				//reconstruct the insert statement below ensuring you have the two values as per insert construct below
				
				$option_insert="";
				//loop
				for ($k=0; $k<$count; $k++) 
					{
					//check if theres content of the option but no choiceid
					if ( (strlen($_POST['choice'][$k]) > 0) && ($_POST['transtype'][$k]=="add") )
						{
						$option_insert.="('".$_SESSION['itemid']."','".mysql_escape_string(trim($_POST['choice'][$k]))."'),";
						}
						
					$option_vals=$option_insert;		
					
					}
			
					$option_vals_qry=substr($option_vals,0,-1);
				
				
				$qry_values = "INSERT INTO wfprocassetschoice (wfprocassets_idwfprocassets,assetchoice) VALUES ".$option_vals_qry."";
				mysql_query($qry_values);
				
				
				//update query here
				$option_update="";
				//loop
				for ($l=0; $l<$count; $l++) 
					{
					//check if theres content of the option but no choiceid
					if ( (strlen($_POST['choice'][$l]) > 0) && ($_POST['transtype'][$l]=="update") )
						{
						$option_update="UPDATE wfprocassetschoice SET assetchoice='".mysql_escape_string(trim($_POST['choice'][$l]))."' WHERE idwfprocassetschoice=".mysql_escape_string(trim($_POST['choiceid'][$l]))."";
						mysql_query($option_update);
						}						
					
					}
				
				}
		
		}
	
	} // end push

//INSERT INTO 
if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="add_new") )
	{

	//clean up values
	$dtype=mysql_escape_string(trim($_POST['dtype']));
	$dorder=mysql_escape_string(trim($_POST['ordering']));
	$formilbl=mysql_escape_string(trim($_POST['formilbl']));
	$itempos=mysql_escape_string(trim($_POST['position']));


	if (isset($_POST['assetname']))
		{
		$assetname=mysql_escape_string(trim($_POST['assetname']));
		} else {
		$assetname="";
		}

	//if a choice, then run the following
	if ($dtype==2)
			{
			$count=count($_POST['choice']);
			
			$choice_insert="";
			$choice_insert_flag="";
			//loop
			for ($i=0; $i<$count; $i++) 
				{
				//check if theres content of the choice
				if (strlen($_POST['choice'][$i]) > 0)
					{
					$choice_insert_flag.=1;
					$choice_insert.="'".mysql_escape_string(trim($_POST['choice'][$i]))."',";
					}
					
				$choice_vals=substr($choice_insert,0,-1);		
				
				}
			
			$choice_vals_qrytemp="(".$choice_vals."),";	
			$choice_vals_qry=substr($choice_vals_qrytemp,0,-1); 
			
			}
	
	//validate the form
	if ($dtype < 1)
		{
		$error_1="<div class=\"msg_warning\">Please select a valid Input Type</div>";
		}
	if (strlen($assetname) < 1)
		{
		$error_2="<div class=\"msg_warning\">Please enter a valid Name for your Form Item</div>";
		}
		
	if ($dtype==2)
			{
//			if ($count < 2) //if the count is less than 2, then warn that one needs to have atleast two options
			if ($choice_insert_flag < 11)
				{
				$error_3="<div class=\"msg_warning\">Please provide at least 2 options for the menu list</div>";
				}
			}
						
	//go ahead and insert else warn
	if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3)) )
		{
		//if no errors, then do the following
		$sql_insert_1="INSERT INTO wfprocassets (wfproc_idwfproc,wfprocdtype_idwfprocdtype,wfprocassetsgroup_idwfprocassetsgroup,assetname,ordering,item_position,createdon,createdby) 
		VALUES ('".$_SESSION['wfselected']."','".$dtype."','".$formilbl."','".$assetname."','".$dorder."','".$itempos."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
		mysql_query($sql_insert_1);
		
		//retrieve the asset id just keyed in to use it for the next inserts below
		$sql_idasset="SELECT idwfprocassets FROM wfprocassets WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwfprocassets DESC LIMIT 1";
		$res_idasset=mysql_query($sql_idasset);
		$fet_idasset=mysql_fetch_array($res_idasset);
		
		$idasset=$fet_idasset['idwfprocassets'];
		
		//insert access to thhis wkflow
		$sql_access="INSERT INTO  wfprocassetsaccess (wfprocassets_idwfprocassets,wftskflow_idwftskflow,createdby,createdon)
		VALUES ('".$idasset."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
		mysql_query($sql_access);
		
		//then if there happends to be a choice_vals set for dtype 2, then insert
			if ((isset($choice_vals_qry)) && (strlen($choice_vals_qry) > 1)) //if the count is greater than 1, (ie at least 2) then process this...
				{ //prepare the insert statement for the choices
				
				//reconstruct the insert statement below ensuring you have the two values as per insert construct below
				
				$option_insert="";
				//loop
				for ($k=0; $k<$count; $k++) 
					{
					//check if theres content of the option
					if ((strlen($_POST['choice'][$k]) > 0) && (strlen($_POST['choiceid'][$k]) < 1) )
						{
						$option_insert.="('".$idasset."','".mysql_escape_string(trim($_POST['choice'][$k]))."'),";
						}
						
					$option_vals=$option_insert;		
					
					}
			
					$option_vals_qry=substr($option_vals,0,-1);
				
				
				$qry_values = "INSERT INTO wfprocassetschoice (wfprocassets_idwfprocassets,assetchoice) VALUES ".$option_vals_qry."";
				mysql_query($qry_values);
				
				//echo $qry_values;
				}
		
		}
	
	} // end push
	
//permissions 
if ( (isset($_GET['doto'])) && (isset($_GET['docmd'])) && (isset($_GET['accessid'])) )	
	{
	$doto=mysql_escape_string(trim($_GET['doto']));
	$docmd=mysql_escape_string(trim($_GET['docmd']));
	$accessid=mysql_escape_string(trim($_GET['accessid']));
	

	//first, check if user owns that accessid
	$sql_owner="SELECT wfproc.usrteam_idusrteam FROM wfprocassetsaccess
	INNER JOIN wftskflow ON wfprocassetsaccess.wftskflow_idwftskflow=wftskflow.idwftskflow 
	INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
	WHERE wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	$num_owner=mysql_num_rows($res_owner);

		if ($num_owner<1)
			{
			echo "<div class=\"msg_warning\" >".$msg_warn_violation."</div>";
			$error_delete_1="FAIL";
			}
		
	if (!isset($error_delete_1)) //if no error and owner owns that record, go ahead and process
		{
		if ($docmd=="on")
			{
			$docmdval=1;
			} else if ($docmd=="off") {
			$docmdval=0;
			}
		
		//check if the record exists so as to know if to insert or update
		$sql_exists="SELECT idwfprocassetsaccess FROM wfprocassetsaccess WHERE wfprocassets_idwfprocassets=".$accessid." AND wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
		$res_exists=mysql_query($sql_exists);
		$num_exists=mysql_num_rows($res_exists);
		
		if ($num_exists < 1) //if no record
			{
			//then if command
			$sql_priv="INSERT INTO wfprocassetsaccess (wfprocassets_idwfprocassets,".$doto.",wftskflow_idwftskflow,createdby,createdon)
			VALUES ('".$accessid."','".$docmdval."','".$_SESSION['idflow']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_priv);
		//	echo $sql_priv;
			} else {
			//then if command
			$sql_priv="UPDATE wfprocassetsaccess SET ".$doto."='".$docmdval."' WHERE wfprocassets_idwfprocassets=".$accessid." AND wftskflow_idwftskflow=".$_SESSION['idflow']." LIMIT 1";
			mysql_query($sql_priv);
		//	echo $sql_priv;
			}
		}
	}
?>
<div style="padding:2px">
<table border="0" cellpadding="1" cellspacing="2" width="790">
	<tr>
    	<td valign="top" width="395">
        <div class="table_header">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
        	<tr>            	
                <td >
                <a title="Add New Form Group to Group Form Items Together" href="<?php echo $_SERVER['PHP_SELF'];?>?do=new_form_itemlbl"><span class="text_small" style="color:#000066">[+] Form Group</span></a>
                <span style="padding:0px 10px">|</span>
				<a title="Add New Form Items" href="<?php echo $_SERVER['PHP_SELF'];?>?do=new_form_item"><span class="text_small" style="color:#000066">[+] Form Item</span></a> 
                <span style="padding:0px 0px 0px 80px; font-weight: bold"></span>
                <a  title="List existing Form Groups and their Items" href="<?php echo $_SERVER['PHP_SELF'];?>?do=list_frm_groups" style="color:<?php if ((isset($_GET['do'])) && ($_GET['do']=="list_frm_groups")) { echo "#ff0000; text-decoration:none;font-weight:bold"; } else { echo "#000066"; } ?>"><span class="text_small" style="color:<?php if ((isset($_GET['do'])) && ($_GET['do']=="list_frm_groups")) { echo "#ff0000; text-decoration:none"; } else { echo "#000066"; } ?>">List   Groups &amp; Items</span></a>            </td>
            </tr>
        </table>
        </div>
<div>
<?php
//list frm groups
if ((isset($_GET['do'])) && ($_GET['do']=="list_frm_groups") )
{
$sql_listg="SELECT count( * ) AS items, wfprocassetsgroup_idwfprocassetsgroup,wfprocassetsgrouplbl,idwfprocassetsgroup
FROM wfprocassets
INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup=wfprocassetsgroup.idwfprocassetsgroup
GROUP BY wfprocassetsgroup_idwfprocassetsgroup ORDER BY wfprocassetsgrouplbl ASC";
$res_listg=mysql_query($sql_listg);
$num_listg=mysql_num_rows($res_listg);
$fet_listg=mysql_fetch_array($res_listg);

if ($num_listg > 0)
	{
?>
<table border="0" width="100%" cellpadding="2" cellspacing="0">
	<tr>
		<td height="25" class="tbl_h2">
		Group Name		</td>
		<td height="25" class="tbl_h2">
		Form Items</td>
		<td width="45" height="25" class="tbl_h2">&nbsp;</td>
        <td class="tbl_h2">
        </td>
	</tr>   
<?php
do {
?>    
    <tr>
		<td height="25" class="tbl_data">
		<?php echo $fet_listg['wfprocassetsgrouplbl'];?>
        		
        </td>
		<td height="25" class="tbl_data">
		<a href="<?php echo $_SERVER['PHP_SELF'];?>?do_list=items&amp;do=list_frm_groups&amp;wfitemgroupid=<?php echo $fet_listg['idwfprocassetsgroup'];?>"><?php echo $fet_listg['items'];?></a>
        </td>
		<td width="45" height="25" class="tbl_data">&nbsp;</td>
        <td width="40" class="tbl_data">
        <a href="<?php echo $_SERVER['PHP_SELF'];?>?do=edit_form_itemgrouplbl&amp;wfitemgroupid=<?php echo $fet_listg['wfprocassetsgroup_idwfprocassetsgroup'];?>" id="button_edit_small"></a>        </td>
	</tr>       
<?php } while ($fet_listg=mysql_fetch_array($res_listg)); ?>              
</table>
<?php
}
?>

<?php
}
?>
</div>        
<?php
$query_rs_formitems = "SELECT idwfprocassets,procdtype,assetname,wfprocassetsgrouplbl,idwfprocassetsgroup,ordering,item_position FROM wfprocassets INNER JOIN wfprocdtype ON wfprocassets.wfprocdtype_idwfprocdtype=wfprocdtype.idwfprocdtype INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup=wfprocassetsgroup.idwfprocassetsgroup WHERE wfprocassets.wfproc_idwfproc=".$_SESSION['wfselected']."  AND userteam_owner=".$_SESSION['MVGitHub_idacteam']." ORDER BY wfprocassetsgrouplbl ASC ,ordering ASC";
$rs_formitems = mysql_query($query_rs_formitems, $connSystem) or die(mysql_error());
$row_rs_formitems = mysql_fetch_assoc($rs_formitems);
$totalRows_rs_formitems = mysql_num_rows($rs_formitems);

$lastTFM_nest = "";
//echo $query_rs_formitems;
?>
        <div>
        	<table border="0" cellpadding="2" cellspacing="0">
				
              <?php			 
			  if ($totalRows_rs_formitems > 0)
			  		{
					
						do {
						//nested loop below
						//loop through the access table to see the status of each object on the table and loop horizontally
						$sql_vals="SELECT idwfprocassetsaccess,perm_read,perm_write,perm_required FROM wfprocassetsaccess
						WHERE wfprocassets_idwfprocassets=".$row_rs_formitems['idwfprocassets']." AND wftskflow_idwftskflow=".$_SESSION['idflow']."  LIMIT 1";
						$res_vals=mysql_query($sql_vals);
						$fet_vals=mysql_fetch_array($res_vals);
					  ?>
              <?php $TFM_nest = $row_rs_formitems['wfprocassetsgrouplbl'];
if ($lastTFM_nest != $TFM_nest) { 
	$lastTFM_nest = $TFM_nest; ?><tr>
               	<td colspan="6" class="table_header_alt">
                	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                    	<tr>
                        	<td>
                            <?php echo $row_rs_formitems['wfprocassetsgrouplbl']; ?>
                            </td>
                            <td align="right">
                            <a href="<?php echo $_SERVER['PHP_SELF'];?>?do=edit_form_itemgrouplbl&amp;wfitemgroupid=<?php echo $row_rs_formitems['idwfprocassetsgroup'];?>" style="color:#FFFFFF"  id="button_edit_small"></a>
                            </td>
                        </tr>
                    </table> 
                  </td>
                 </tr>
                    <tr>
					<td width="115" height="25" class="tbl_h2">
                    Name / Label
					</td>
					<td width="105" height="25" class="tbl_h2">
                    Type
					</td>
					<td width="45" height="25" class="tbl_h2">
                   <small>Read</small>
					</td>
					<td width="45" height="25" class="tbl_h2">
                    <small>Write</small>
                    </td>
				  	<td width="45" height="25" class="tbl_h2">
                    <small>Required</small>
                    </td>
				  <td width="20" height="25" class="tbl_h2">&nbsp;</td>
				</tr>
<?php } //End of Basic-UltraDev Simulated Nested Repeat?>
                      
					  <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
						<td class="tbl_data" height="35">
                       
                         <small> [<?php echo  $row_rs_formitems['item_position'];?>] <?php echo  $row_rs_formitems['ordering'];?></small>
                       <br /><a <?php if ( (isset($_SESSION['do'])) && ($_SESSION['do']=="edit_form_item") && ($_SESSION['itemid']==$row_rs_formitems['idwfprocassets'] )) { echo "style=\"color:#ff0000;font-weight:bold\""; }?> href="<?php echo $_SERVER['PHP_SELF'];?>?do=edit_form_item&amp;iditem=<?php echo $row_rs_formitems['idwfprocassets'];?>&amp;assetgroupid=<?php echo $row_rs_formitems['idwfprocassetsgroup'];?>">
					   <?php echo $row_rs_formitems['assetname'];?></a>
                        </td>
                        <td class="tbl_data" height="35">
                       	<?php echo  $row_rs_formitems['procdtype'];?>
                        </td>
                        <td class="tbl_data" height="35">
                        <a id="button_check<?php if ($fet_vals['perm_read']==1) { $docmd="off"; echo "_on"; } else { $docmd="on"; } ?>" href="<?php echo $_SERVER['PHP_SELF']."?doto=perm_read&accessid=".$row_rs_formitems['idwfprocassets']."&docmd=".$docmd."";?>"></a>
                        </td>
                        <td class="tbl_data" height="35">
                        <?php
						if ($fet_vals['perm_read']==1) {
						?>
                        <a id="button_check<?php if ($fet_vals['perm_write']==1) { $docmd="off"; echo "_on"; } else { $docmd="on"; } ?>" href="<?php echo $_SERVER['PHP_SELF']."?doto=perm_write&accessid=".$row_rs_formitems['idwfprocassets']."&docmd=".$docmd."";?>"></a>
                        <?php } else { echo "---"; }?>
                        </td>
                        <td class="tbl_data" height="35">
                        <?php
						if (($fet_vals['perm_read']==1) && ($fet_vals['perm_write']==1)) {
						?>
                       	<a id="button_check<?php if ($fet_vals['perm_required']==1) { $docmd="off"; echo "_on"; } else { $docmd="on"; } ?>" href="<?php echo $_SERVER['PHP_SELF']."?doto=perm_required&accessid=".$row_rs_formitems['idwfprocassets']."&docmd=".$docmd."";?>"></a>
                        <?php
						} else { echo "---"; }
						?>
                        </td>
                        <td class="tbl_data" height="35">
                        <?php
						echo "<a title=\"Delete Item\" onclick=\"return confirm('".$msg_prompt_delete."')\" href=\"".$_SERVER['PHP_SELF']."?saction=delete_form_item&amp;iditem=".$row_rs_formitems['idwfprocassets']."\" id=\"button_delete_vsmall\"></a>";
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
						} while ($row_rs_formitems = mysql_fetch_assoc($rs_formitems));
					} else {
						if ( (isset($_GET['do'])) && ($_GET['do']!="list_frm_groups") )
							{
					echo "<tr><td colspan=6><div class=\"msg_warning\">No Form Items Selected</div></td></tr>";
							}
					}
					  ?>
</table>
        </div>
        <?php
mysql_free_result($rs_formitems);
?>
        </td>
        <td valign="top"  width="375">
		<div>
        <?php
		if (isset($error_1)) { echo $error_1; }
		if (isset($error_2)) { echo $error_2; }
		if (isset($error_3)) { echo $error_3; }
		?>
		</div>
        <?php
		//for inserting new form assets
		if ((isset($_GET['do_list'])) && ($_GET['do_list']=="items") )
			{
		?>
        	<table border="0" cellpadding="2" cellspacing="0" width="100%">
				<tr>
                	<td height="25" class="tbl_h2">
                    </td>
					<td height="25" class="tbl_h2">
                    Item Name / Label
					</td>
					<td height="25" class="tbl_h2">
                    Type
					</td>
					<td height="25" class="tbl_h2">
                    Workflow
					</td>
				</tr>
                <?php
$sql_items="SELECT idwfprocassets,assetname,ordering,procdtype,wfprocname,item_position FROM wfprocassets
INNER JOIN  wfprocdtype ON wfprocassets.wfprocdtype_idwfprocdtype=wfprocdtype.idwfprocdtype
INNER JOIN wfproc ON wfprocassets.wfproc_idwfproc=wfproc.idwfproc
WHERE wfprocassets.wfprocassetsgroup_idwfprocassetsgroup=".$_SESSION['wfitemgroupid']." ORDER BY wfprocname";

//echo $sql_items;
$res_items=mysql_query($sql_items);
$fet_items=mysql_fetch_array($res_items);
$num_items=mysql_num_rows($res_items);

if ($num_items >0 )
	{
	do {
				?>
                <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                	<td class="tbl_data">&nbsp;</td>
					<td height="25" class="tbl_data">
                   <small>
                   <strong>[ <?php echo $fet_items['item_position'];?> ]</strong>
                   </small>
                    <?php echo $fet_items['assetname'];?>
					</td>
					<td height="25" class="tbl_data">
                     <?php echo $fet_items['procdtype'];?>
					</td>
                    <td height="25" class="tbl_data">
                   <?php echo $fet_items['wfprocname'];?>
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

		} while ($fet_items=mysql_fetch_array($res_items));
	}

?>                
            </table>
        <?php
		}
		?>
        
        <?php
		//for inserting new form assets
		if ((isset($_GET['do'])) && ($_GET['do']=="new_form_itemlbl") )
			{
			if (isset($msg)) { echo $msg; }
			if (isset($msg_error)) { echo $msg_error; }
		?>
        <form method="post" action="" name="frmgroup" autocomplete="off">
        <table border="0" cellpadding="2" cellspacing="0" width="375">
        		<tr>
                	<td height="24" colspan="2" class="table_header">New Form Items Group</td>
                </tr>
            	<tr>
                	<td class="tbl_data">
                    <strong>Form Group Name</strong>
                    </td>
                    <td class="tbl_data">
                   <input type="text" name="frmgrouplbl" />
                   </td>
                </tr>
                <tr>
                	<td></td>
                    <td>
                    <input type="hidden" value="add_newfrmgrp" name="form_action" />
                    <a href="#" onclick="document.forms['frmgroup'].submit()" id="button_save"></a>
                    </td>
                </tr>
		</table>                
        </form>
        <?php
		}
		?>
        
        <?php
		//for inserting new form assets
		if ((isset($_GET['do'])) && ($_GET['do']=="edit_form_itemgrouplbl") )
			{
			$wfitemgroupid = mysql_escape_string(trim($_GET['wfitemgroupid']));
			$sql_group="SELECT  idwfprocassetsgroup,wfprocassetsgrouplbl FROM wfprocassetsgroup WHERE idwfprocassetsgroup=".$wfitemgroupid." LIMIT 1";
			$res_group=mysql_query($sql_group);
			$fet_group=mysql_fetch_array($res_group);
			
			if (isset($msg)) { echo $msg; }
			if (isset($msg_error)) { echo $msg_error; }
		?>
        <form method="post" action="" name="editfrmgroup" autocomplete="off">
        <table border="0" cellpadding="2" cellspacing="0" width="375">
        		<tr>
                	<td height="24" colspan="2" class="table_header">Edit Form Items Group</td>
                </tr>
            	<tr>
                	<td class="tbl_data">
                    <strong>Form Group Name</strong>
                    </td>
                    <td class="tbl_data">
                   <input type="text" value="<?php echo $fet_group['wfprocassetsgrouplbl'];?>" name="frmgrouplbl" />
                   </td>
                </tr>
                <tr>
                	<td></td>
                    <td>
                    <input type="hidden" value="edit_newfrmgrpname" name="form_action" />
                    <a href="#" onclick="document.forms['editfrmgroup'].submit()" id="button_save"></a>
                    </td>
                </tr>
		</table>                
        </form>
        <?php
		}
		?>
        
        <?php
		//for inserting new form assets
		if ((isset($_GET['do'])) && ($_GET['do']=="new_form_item") )
			{
		?>
        <div>
        <form method="post" action="" name="addassets" autocomplete="off" style="margin:0">
            <table border="0" cellpadding="2" cellspacing="0" width="375">
            	<tr>
                	<td height="24" colspan="2" class="table_header">New Form Item</td>
                </tr>
            	<tr>
                	<td class="tbl_data">
                    <strong>Input Type</strong>
                    </td>
                    <td class="tbl_data">
                   <strong>Name / label</strong></td>
                </tr>
                <tr>
                	<td class="tbl_data">
                    <select name="dtype" onchange="showstuff(this.value);">
                      <?php
					$sql_menu="SELECT idwfprocdtype,procdtype FROM wfprocdtype";
					$res_menu=mysql_query($sql_menu);
					$num_menu=mysql_num_rows($res_menu);
					$fet_menu=mysql_fetch_array($res_menu);
					
					echo "<option value=\"\">---</option>";
					do {
					echo "<option ";
					if ( (isset($_POST['dtype'])) && ($dtype==$fet_menu['idwfprocdtype']) )
						{
						echo " selected=\"selected\" ";
						}
					echo " value=\"".$fet_menu['idwfprocdtype']."\">".$fet_menu['procdtype']."</option>";
					} while ($fet_menu=mysql_fetch_array($res_menu));
					?>
                    </select>                    </td>
                    <td class="tbl_data">
                    <div id="assetname" <?php if (!isset($_POST['form_action'])) { echo "style=\"display:none;\""; } ?>>
                    <input type="text" <?php if (!isset($_POST['form_action'])) { ?> disabled="disabled" <?php } ?> maxlength="20" name="assetname" size="20" />
                    </div>
                    </td>
                </tr>
                <tr>
                	<td class="tbl_data">
                    <strong>Ordering</strong>
                    </td>
                    <td class="tbl_data">
                  <input type="text" name="ordering" size="5" onKeyUp="res(this,numb);" />
                   </td>
                 </tr>
                  <tr>
                	<td class="tbl_data">
                    <strong>Position</strong>
                    </td>
                    <td class="tbl_data">
                  <select name="position">
                  	<option value="L">[L] - Left</option>
                    <option value="R">[R] - Right</option>
                  </select>
                   </td>
                 </tr>
                 <tr>
                	<td class="tbl_data">
                    <strong>Form Items Group</strong>
                    </td>
                    <td class="tbl_data">
                    <?php
					$sql_group="SELECT idwfprocassetsgroup,wfprocassetsgrouplbl FROM wfprocassetsgroup WHERE userteam_owner=".$_SESSION['MVGitHub_idacteam']."";
				   $res_group=mysql_query($sql_group);
				   $num_group=mysql_num_rows($res_group);
				   $fet_group=mysql_fetch_array($res_group);
				  // echo $sql_group;
					?>
                   <select name="formilbl">
                   <?php
				   	do {
					echo "<option value=\"".$fet_group['idwfprocassetsgroup']."\">".$fet_group['wfprocassetsgrouplbl']."</option>";
					} while ($fet_group=mysql_fetch_array($res_group));
				   ?>
                   </select>
                   </td>
                 </tr>
                <tr>
                    <td colspan="2">
                    <div id="2"  <?php if (!isset($_POST['form_action'])) { echo "style=\"display:none;\""; } if ( (isset($_POST['form_action'])) && ($dtype!=2)) { echo "style=\"display:none;\""; } ?>>
                    <div style="padding:3px 0px 2px 0px"><strong>Choice Menus</strong></div>
                    <div class="tbl_data">
                    	<table border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <label for="choice">Option #1 </label><input  maxlength="20" type="text" name="choice[]" />
                                </td>
                                <td>
                                <input type="button" onclick="addInput()" tabindex="-1" name="add" value="Add Another" class="text_small" />
                                </td>
                            </tr>
						</table>
                        </div>
                    <div class="tbl_data">
                        <table border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <label for="choice">Option #2 </label><input  maxlength="20" type="text" name="choice[]" />
                                </td>
                                <td>
                                </td>
                            </tr>
						</table>
                        
                        </div>
                        <div id="text"></div>
                    </div>
					</td>
                </tr>
                <tr>
                	<td colspan="2">
                    <input type="hidden" value="add_new" name="form_action" />
                    <a href="#" onclick="document.forms['addassets'].submit()" id="button_save"></a>
                    </td>
                </tr>
            </table>
        </form>
        </div>
        <?php
			}
		?>
        
        <?php
		//for inserting new form assets
		if ((isset($_GET['do'])) && ($_GET['do']=="edit_form_item") )
			{
			
			//retreive the records based on the session itemid
			$sql_assetinfo="SELECT idwfprocassets,wfprocdtype_idwfprocdtype,assetname,ordering,item_position FROM wfprocassets
			INNER JOIN wfprocassetsaccess ON wfprocassets.idwfprocassets=wfprocassetsaccess.wfprocassets_idwfprocassets
			WHERE wfprocassets.wfproc_idwfproc=".$_SESSION['wfselected']." AND idwfprocassets=".$_SESSION['itemid']." LIMIT 1";
			$res_assetinfo=mysql_query($sql_assetinfo);
			$fet_assetinfo=mysql_fetch_array($res_assetinfo);
			
			//echo $sql_assetinfo;
		?>
        <div>
        <form method="post" action="" name="addassets" autocomplete="off">
            <table border="0" cellpadding="2" cellspacing="0" width="375">
            <tr>
                	<td height="24" colspan="2" class="table_header">Edit Form Item</td>
                </tr>
            	<tr>
                	<td class="tbl_data">
                    <strong>Input Type</strong>
                    </td>
                    <td class="tbl_data">
                   <strong>Name / label</strong>
                   </td>
                </tr>
                <tr>
                	<td class="tbl_data">
                    <select name="dtype">
                      <?php
					$sql_menu="SELECT idwfprocdtype,procdtype FROM wfprocdtype WHERE idwfprocdtype=".$fet_assetinfo['wfprocdtype_idwfprocdtype']." LIMIT 1";
					$res_menu=mysql_query($sql_menu);
					$num_menu=mysql_num_rows($res_menu);
					$fet_menu=mysql_fetch_array($res_menu);
					
					//echo "<option value=\"\">---</option>";
					do {
					echo "<option ";
					if ( (isset($_POST['dtype'])) && ($dtype==$fet_menu['idwfprocdtype']) )
						{
						echo " selected=\"selected\" ";
						}
					if ($fet_assetinfo['wfprocdtype_idwfprocdtype']==$fet_menu['idwfprocdtype'])
						{
						echo " selected=\"selected\" ";
						}
					echo " value=\"".$fet_menu['idwfprocdtype']."\">".$fet_menu['procdtype']."</option>";
					} while ($fet_menu=mysql_fetch_array($res_menu));
					?>
                    </select>                    </td>
                    <td class="tbl_data">
                    <input type="text"  maxlength="20" name="assetname" value="<?php echo $fet_assetinfo['assetname'];?>" size="20" />
                    </td>
                 	
                </tr>
                <tr>
                    <td class="tbl_data">
                    <strong>Ordering</strong>
                    </td>
                    <td class="tbl_data">
                        <input type="text" value="<?php echo $fet_assetinfo['ordering'];?>" name="ordering" size="5" onKeyUp="res(this,numb);" />
                        </td>
                </tr>
                 <tr>
                	<td class="tbl_data">
                    <strong>Position</strong>
                    </td>
                    <td class="tbl_data">
                  <select name="position">
                  	<option value="L" <?php if ($fet_assetinfo['item_position']=="L") { echo "selected=\"selected\""; } ?>>[L] - Left</option>
                    <option value="R" <?php if ($fet_assetinfo['item_position']=="R") { echo "selected=\"selected\""; } ?>>[R] - Right</option>
                  </select>
                   </td>
                 </tr>
                <tr>
                    <td class="tbl_data">
                    <strong>Form Items Group</strong>
                    </td>
                    <td class="tbl_data">
                        <select name="formilbl">
                   <?php
				   if (isset($_GET['assetgroupid']))
				   	{
					$assetgroupid=trim($_GET['assetgroupid']);
					}
				   $sql_group="SELECT idwfprocassetsgroup,wfprocassetsgrouplbl FROM wfprocassetsgroup WHERE userteam_owner=".$_SESSION['MVGitHub_idacteam']."";
				   $res_group=mysql_query($sql_group);
				   $num_group=mysql_num_rows($res_group);
				   $fet_group=mysql_fetch_array($res_group);
				   	do {
					echo "<option ";
					if ( (isset($assetgroupid)) && ($assetgroupid==$fet_group['idwfprocassetsgroup']) )
						{
						echo " selected=\"selected\" ";
						}
					echo " value=\"".$fet_group['idwfprocassetsgroup']."\">".$fet_group['wfprocassetsgrouplbl']."</option>";
					} while ($fet_group=mysql_fetch_array($res_group));
				   ?>
                   </select></td>
                </tr>
                <tr>
                    <td colspan="2">
                    <?php
					if ($fet_assetinfo['wfprocdtype_idwfprocdtype']==2)
						{
						//dynamically check the DB for the choices and Primary Keys for these values
						$sql_getchoices="SELECT idwfprocassetschoice,assetchoice FROM  wfprocassetschoice 
						WHERE wfprocassets_idwfprocassets=".$fet_assetinfo['idwfprocassets']."";
						$res_getchoices=mysql_query($sql_getchoices);
						$num_getchoices=mysql_num_rows($res_getchoices);
						$fet_getchoices=mysql_fetch_array($res_getchoices);
					//	echo $sql_getchoices;
					?>
                    <div >
                    <div style="padding:3px 0px 2px 0px"><strong>Choice Menus</strong></div>
                    	<?php 
						if ($num_getchoices > 0)
							{
							$p=1;
							do { ?>
                        <div class="tbl_data">
                        <table border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                Option #<?php echo $p;?>  <input  maxlength="20" type="text" value="<?php echo $fet_getchoices['assetchoice'];?>" name="choice[]" />
                                </td>
                                
                                <td>
                                <a <?php echo "onclick=\"return confirm('".$msg_prompt_delete."')\" ";?> href="<?php echo $_SERVER['PHP_SELF'];?>?saction=delchoice&amp;choiceid=<?php echo $fet_getchoices['idwfprocassetschoice'];?>&amp;do=edit_form_item&amp;itemid=<?php echo $fet_assetinfo['idwfprocassets'];?>" id="button_delete_vsmall"></a>
                                </td>
                                <td>
                                <input type="hidden" size="5" name="choiceid[]" value="<?php echo $fet_getchoices['idwfprocassetschoice'];?>" />
                        		<input type="hidden" value="update" name="transtype[]" />
                                <?php if ($p<2) { ?>
                                <input type="button" <?php if ($fields==15) { echo "disabled=\"disabled\"";} ?> onclick="addInput()" tabindex="-1" name="add" value="Add Choice" class="text_small" />
                                <?php } ?>
                                </td>
                            </tr>
						</table>
                        
                       
                        
                        
                        </div>
                        <?php 
							$p++;
								} while ($fet_getchoices=mysql_fetch_array($res_getchoices));
							}
						?>
                        
                        <div id="text"></div>
                    </div>
                    <?php
						}
					?>
					</td>
                </tr>
                <tr>
                	<td colspan="2">
                    <input type="hidden" value="edit_record" name="form_action" />
                    <a href="#" onclick="document.forms['addassets'].submit()" id="button_save"></a>
                    </td>
                </tr>
            </table>
        </form>
        </div>
        <?php
			}
		?>
        </td>
    </tr>
</table>
</div>