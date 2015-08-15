<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

if (isset($_GET['idform']))	
	{
	$_SESSION['idform']=mysql_real_escape_string(trim($_GET['idform']));
	}

//check object selected
if  (isset($_GET['do']))
	{
	$_SESSION['do']=mysql_real_escape_string(trim($_GET['do']));
	}
if  (isset($_GET['iditem']))
	{
	$_SESSION['itemid']=mysql_real_escape_string(trim($_GET['iditem']));
	}
	
//
if  ((isset($_GET['do'])) && ($_GET['do']=="edit_form_item") ) //if edit, then query the db just to be sure
	{
	$sql_fields="SELECT count(*) as fields FROM wfprocassetschoice WHERE wfprocassets_idwfprocassets=".$_SESSION['itemid']."";
	$res_fields=mysql_query($sql_fields);
	$fet_fields=mysql_fetch_array($res_fields);
	//echo $sql_fields;
	$fields=$fet_fields['fields'];
	$field_add=0;
//	$total_fields=(5-$fields);
	$total_fields=15;
	} else {
	$fields=0;
	$total_fields=13;
	$field_add=2;
	}



//
if (isset($_GET['wfitemgroupid']))
	{
	$_SESSION['wfitemgroupid']=trim($_GET['wfitemgroupid']);
	}
	
	
//UPDATE
if ( (isset($_POST['form_action'])) && ($_POST['form_action']=="edit_record") )
	{

	//clean up values
	$dtype=mysql_real_escape_string(trim($_POST['dtype']));
	$dorder=mysql_real_escape_string(trim($_POST['ordering']));
	$formilbl=mysql_real_escape_string(trim($_POST['formilbl']));
	$itempos=mysql_real_escape_string(trim($_POST['position']));
	
	if (isset($_POST['assetname']))
		{
		$assetname=mysql_real_escape_string(trim($_POST['assetname']));
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
					$choice_insert.="'".mysql_real_escape_string(trim($_POST['choice'][$i]))."',";
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
		$sql_update="UPDATE wfprocassets SET assetname='".$assetname."',wfprocassetsgroup_idwfprocassetsgroup='".$formilbl."',ordering='".$dorder."',item_position='".$itempos."' WHERE idwfprocassets=".$_SESSION['itemid']."  AND wfprocforms_idwfprocforms=".$_SESSION['idform']."";
		mysql_query($sql_update);
		//echo "<br><br><br>".$sql_update;
		
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
						$option_insert.="('".$_SESSION['itemid']."','".mysql_real_escape_string(trim($_POST['choice'][$k]))."'),";
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
						$option_update="UPDATE wfprocassetschoice SET assetchoice='".mysql_real_escape_string(trim($_POST['choice'][$l]))."' WHERE idwfprocassetschoice=".mysql_real_escape_string(trim($_POST['choiceid'][$l]))."";
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
	$dtype=mysql_real_escape_string(trim($_POST['dtype']));
	$dorder=mysql_real_escape_string(trim($_POST['ordering']));
	$formilbl=mysql_real_escape_string(trim($_POST['formilbl']));
	$itempos=mysql_real_escape_string(trim($_POST['position']));


	if (isset($_POST['assetname']))
		{
		$assetname=mysql_real_escape_string(trim($_POST['assetname']));
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
					$choice_insert.="'".mysql_real_escape_string(trim($_POST['choice'][$i]))."',";
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
		$sql_insert_1="INSERT INTO wfprocassets (wfproc_idwfproc,wfprocforms_idwfprocforms,wfprocdtype_idwfprocdtype,wfprocassetsgroup_idwfprocassetsgroup,assetname,ordering,item_position,createdon,createdby) 
		VALUES ('0','".$_SESSION['idform']."','".$dtype."','".$formilbl."','".$assetname."','".$dorder."','".$itempos."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
		mysql_query($sql_insert_1);
		
		//retrieve the asset id just keyed in to use it for the next inserts below
		$sql_idasset="SELECT idwfprocassets FROM wfprocassets WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwfprocassets DESC LIMIT 1";
		$res_idasset=mysql_query($sql_idasset);
		$fet_idasset=mysql_fetch_array($res_idasset);
		
		$idasset=$fet_idasset['idwfprocassets'];
		
	
		//then if there happends to be a choice_vals set for dtype 2, then insert
			if ((isset($choice_vals_qry)) && (strlen($choice_vals_qry) > 1)) //if the count is greater than 1, (ie at least 2) then process this...
				{ //prepare the insert statement for the choices
				
				//reconstruct the insert statement below ensuring you have the two values as per insert construct below
				
				$option_insert="";
				//loop
				for ($k=0; $k<$count; $k++) 
					{
					//check if theres content of the option
					//if ((strlen($_POST['choice'][$k]) > 0) && (strlen($_POST['choiceid'][$k]) < 1) )
					if ((strlen($_POST['choice'][$k]) > 0) && (!isset($_POST['choiceid'][$k])) )
						{
						$option_insert.="('".$idasset."','".mysql_real_escape_string(trim($_POST['choice'][$k]))."'),";
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
	
	
//DELETES
//delete form item
if ( (isset($_GET['saction'])) && ($_GET['saction']=="delete_form_item"))
	{
	//first, clean up the value
	$delval=mysql_real_escape_string(trim($_GET['iditem']));
	
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
	$delval=mysql_real_escape_string(trim($_GET['choiceid']));
	
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
//restrict to numbers or alpha
var numb = "0123456789.";
var alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
function res(t,v){
var w = "";
for (i=0; i < t.value.length; i++) {
x = t.value.charAt(i);
if (v.indexOf(x,0) != -1)
w += x;
}
t.value = w;
}
</script>

<script language="javascript" type="text/javascript">
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

function getAJAXHTTPREQ() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }


	//positioning of the workflow item when designing workflows
	function getpos(posId) {		
		
//		var strURL="findpos.php?list_order="+parseFloat(posId);
		var strURL="findpos.php?list_order="+posId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('posdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
		
</script>
<script type="text/javascript">
function showstuff(element)
	{
    document.getElementById("2").style.display = element=="2"?"block":"none";
	document.addassets.assetname.disabled=false;
	document.getElementById("assetname").style.display = element!=""?"block":"none";
	if (document.addassets.dtype.value<1)
		{
		document.addassets.assetname.disabled=true;
		}
	}

//add new fields dynamically
fields = <?php echo $fields;?>;
function addInput()
	{
    if (fields!=<?php echo $total_fields;?>) {
    fields += 1;
    document.getElementById('text').innerHTML += '<div class="tbl_data"><table border="0" cellpadding="0" cellspacing="0"><tr><td><label for="choice_' + fields + '">Option #' + parseInt(fields+<?php echo $field_add;?>) + ' </label><input type="text" maxlength="20" name="choice[]" id="choice_' + fields + '" /><input type="hidden" size="5" name="choiceid[]" value="" /><input type="hidden" name="transtype[]" value="add"></td></tr></table></div>';
    } else {
    document.getElementById('text').innerHTML += "<div style='color:#ff0000;padding:2px'>Max of 10 choices allowed.</div>";
    document.addassets.add.disabled=true;
    }
	}



</script>
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type='text/javascript'>//<![CDATA[ 
$(function(){
    $('input[type=radio]').change(function(){
        var num = $(this).attr('data_alert');
        var $checked = $('input[id$=_' + num + ']:checked');
        $('#msgnote_' + num).prop('disabled',$checked.val() == '0');   
    }).change();
});//]]>  


<!--
function c(){}

function showhide(){
if(document["properties"]["share_task"].checked){
document.getElementById("myrow").style.visibility="visible"
}
else{
document.getElementById("myrow").style.visibility="hidden"
}
}
</script>
<title>Untitled Document</title>
</head>
<body>
<div>
	<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="830">
  		<tr>
        	<td >Form Section</td>
        	<td align="right">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                        <td><a href="#" onClick="parent.tb_remove();parent.location.reload(1)" id="button_closewin"></a></td>
                    </tr>
              </table>
            	
            </td>
      </tr>
    </table>
    </div>
  
    <div style="padding:45px 5px 0px 5px">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    	<tr>
        	<td valign="top" width="50%" style="padding:2px 0px">
			<div>
			<div class="table_header" style="padding:8px 5px">Form  Fields per Section | <a title="Add New Form Group to Group Form Items Together" href="<?php echo $_SERVER['PHP_SELF'];?>?do=new_form_item">Add Field </a></div>
<?php
$query_rs_formitems = "SELECT idwfprocassets,procdtype,assetname,wfprocassetsgrouplbl,idwfprocassetsgroup,ordering,item_position FROM wfprocassets 
INNER JOIN wfprocdtype ON wfprocassets.wfprocdtype_idwfprocdtype=wfprocdtype.idwfprocdtype 
INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup=wfprocassetsgroup.idwfprocassetsgroup 
WHERE wfprocassets.wfprocforms_idwfprocforms=".$_SESSION['idform']." 
AND userteam_owner=".$_SESSION['MVGitHub_idacteam']." 
AND wfprocassets.wfprocforms_idwfprocforms=".$_SESSION['idform']."  
AND wfprocassetsgroup.wfprocforms_idwfprocforms=".$_SESSION['idform']."  
ORDER BY wfprocassetsgrouplbl ASC, ordering ASC";
$rs_formitems = mysql_query($query_rs_formitems, $connSystem) or die(mysql_error());
$row_rs_formitems = mysql_fetch_assoc($rs_formitems);
$totalRows_rs_formitems = mysql_num_rows($rs_formitems);

$lastTFM_nest = "";
//echo $query_rs_formitems;
?>
        <div>
        	<table border="0" cellpadding="2" cellspacing="0" width="100%">
				
              <?php			 
			  if ($totalRows_rs_formitems > 0)
			  		{
					
						do {
						//nested loop below
						//loop through the access table to see the status of each object on the table and loop horizontally
						$sql_vals="SELECT idwfprocassetsaccess,perm_read,perm_write,perm_required FROM wfprocassetsaccess
						WHERE wfprocassets_idwfprocassets=".$row_rs_formitems['idwfprocassets']." AND wfprocforms_idwfprocforms=".$_SESSION['idform']."  LIMIT 1";
						$res_vals=mysql_query($sql_vals);
						$fet_vals=mysql_fetch_array($res_vals);
					  ?>
              <?php $TFM_nest = $row_rs_formitems['wfprocassetsgrouplbl'];
			if ($lastTFM_nest != $TFM_nest) { 
			$lastTFM_nest = $TFM_nest; ?><tr>
               	<td colspan="6" class="table_header_alt">
                	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                    	<tr>
                        	<td><?php echo $row_rs_formitems['wfprocassetsgrouplbl']; ?></td>
                            <td align="right">
                            <!--<a href="<?php echo $_SERVER['PHP_SELF'];?>?do=edit_form_itemgrouplbl&amp;wfitemgroupid=<?php echo $row_rs_formitems['idwfprocassetsgroup'];?>" style="color:#FFFFFF"  id="button_edit_small"></a>-->                            </td>
                        </tr>
                    </table>                  </td>
                 </tr>
                    <tr>
                    <td height="25" class="tbl_h2">ID</td>
					<td height="25" class="tbl_h2">Field lable</td>
					<td width="100" height="25" class="tbl_h2">
                    Type					</td>
					
				  <td width="20" height="25" class="tbl_h2">&nbsp;</td>
				</tr>
<?php } //End of Basic-UltraDev Simulated Nested Repeat?>
                      
					  <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
						<td class="tbl_data"><?php echo $row_rs_formitems['idwfprocassets'];?></td>
						<td class="tbl_data" height="35">
                       
                         <small> [<?php echo  $row_rs_formitems['item_position'];?>] <?php echo  $row_rs_formitems['ordering'];?></small>
                       <a <?php if ( (isset($_SESSION['do'])) && ($_SESSION['do']=="edit_form_item") && ($_SESSION['itemid']==$row_rs_formitems['idwfprocassets'] )) { echo "style=\"color:#ff0000;font-weight:bold\""; }?> href="<?php echo $_SERVER['PHP_SELF'];?>?do=edit_form_item&amp;iditem=<?php echo $row_rs_formitems['idwfprocassets'];?>&amp;assetgroupid=<?php echo $row_rs_formitems['idwfprocassetsgroup'];?>">
					   <?php echo $row_rs_formitems['assetname'];?></a>                        </td>
                        <td class="tbl_data" height="35">
                       	<?php echo  $row_rs_formitems['procdtype'];?>                        </td>
                       
                        <td class="tbl_data" height="35">
                        <?php
						echo "<a title=\"Delete Item\" onclick=\"return confirm('".$msg_prompt_delete."')\" href=\"".$_SERVER['PHP_SELF']."?saction=delete_form_item&amp;iditem=".$row_rs_formitems['idwfprocassets']."\" id=\"button_delete_vsmall\"></a>";
						?>                        </td>
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
					//	if ( (isset($_GET['do'])) && ($_GET['do']!="list_frm_groups") )
					//		{
					echo "<tr><td colspan=6><div class=\"msg_warning\">You don't have any form fields associated with this form</div></td></tr>";
					//		}
					}
					  ?>
</table>
        </div>
        <?php
mysql_free_result($rs_formitems);
?>
           </div>
            <!--- BR --->            
          </td>
            <td valign="top">
          <?php
		//for inserting new form assets
		if ((isset($_GET['do'])) && ($_GET['do']=="new_form_item") )
			{
		?>
        <div>
        <form method="post" action="" name="addassets" autocomplete="off" style="margin:0">
            <table border="0" cellpadding="2" cellspacing="0" width="394">
    <tr>
                	<td height="24" colspan="2" class="table_header">New Form Field</td>
                </tr>
            	<tr>
                	<td width="158" class="tbl_data">
                    <strong>Input Type</strong>                    </td>
                  <td width="228" class="tbl_data">
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
					if ( (isset($_POST['dtype'])) && ($_POST['dtype']==$fet_menu['idwfprocdtype']) )
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
                    <strong>Form Field Group</strong>
                    </td>
                    <td class="tbl_data">
                    <?php
					$sql_group="SELECT idwfprocassetsgroup,wfprocassetsgrouplbl FROM wfprocassetsgroup WHERE userteam_owner=".$_SESSION['MVGitHub_idacteam']." AND wfprocforms_idwfprocforms=".$_SESSION['idform']." ";
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
                	<td height="35" class="tbl_data">
                    <strong>Formula</strong>                    </td>
                    <td class="tbl_data">&lt; Save to Enable &gt;</td>
                </tr>
                <tr>
                    <td colspan="2">
                    <div id="2"  <?php if (!isset($_POST['form_action'])) { echo "style=\"display:none;\""; } if ( (isset($_POST['form_action'])) && ($_POST['dtype']!=2)) { echo "style=\"display:none;\""; } ?>>
                    <div style="padding:3px 0px 2px 0px"><strong>Choice Menus</strong></div>
                    <div class="tbl_data">
                    	<table border="0" cellpadding="0" cellspacing="0">
                        	<tr>
                            	<td>
                                <label for="choice">Option #1 </label><input maxlength="20" type="text" name="choice[]" />
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
/*			$sql_assetinfo="SELECT idwfprocassets,wfprocdtype_idwfprocdtype,assetname,ordering,item_position FROM wfprocassets
			LEFT JOIN wfprocassetsaccess ON wfprocassets.idwfprocassets=wfprocassetsaccess.wfprocassets_idwfprocassets
			WHERE wfprocassets.wfprocforms_idwfprocforms=".$_SESSION['idform']." AND idwfprocassets=".$_SESSION['itemid']." LIMIT 1";*/
			$sql_assetinfo="SELECT idwfprocassets,wfprocdtype_idwfprocdtype,assetname,ordering,item_position FROM wfprocassets 
			WHERE wfprocassets.wfprocforms_idwfprocforms=".$_SESSION['idform']." AND idwfprocassets=".$_SESSION['itemid']." LIMIT 1";
			$res_assetinfo=mysql_query($sql_assetinfo);
			$fet_assetinfo=mysql_fetch_array($res_assetinfo);
			
			//echo $sql_assetinfo;
		?>
        <div>
        <form method="post" action="" name="addassets" autocomplete="off">
            <table border="0" cellpadding="2" cellspacing="0" width="375">
            <tr>
                	<td height="24" colspan="2" class="table_header">Edit Form Field</td>
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
                    <?php
					$sql_menu="SELECT idwfprocdtype,procdtype FROM wfprocdtype WHERE idwfprocdtype=".$fet_assetinfo['wfprocdtype_idwfprocdtype']." LIMIT 1";
					$res_menu=mysql_query($sql_menu);
					$num_menu=mysql_num_rows($res_menu);
					$fet_menu=mysql_fetch_array($res_menu);
					//echo $sql_menu;
					?>
                    <select name="dtype">
                      <?php
					//echo "<option value=\"\">---</option>";
					do {
					echo "<option ";
					if ( (isset($_POST['dtype'])) && ($_POST['dtype']==$fet_menu['idwfprocdtype']) )
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
				   $sql_group="SELECT idwfprocassetsgroup,wfprocassetsgrouplbl FROM wfprocassetsgroup WHERE userteam_owner=".$_SESSION['MVGitHub_idacteam']." AND wfprocforms_idwfprocforms=".$_SESSION['idform']." ";
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
                	<td class="tbl_data">
                    <strong>Formula</strong>
                    </td>
                    <td class="tbl_data">
                    <?php 
					//echo $fet_assetinfo['wfprocdtype_idwfprocdtype'];
					if ($fet_assetinfo['wfprocdtype_idwfprocdtype']!=8) {
					echo "<span style=\"font-size:10px;color:#ff0000\">Formulae can only be applied to Text Boxes No. Only</span>";
					} else { 
					echo "<strong>".$fet_assetinfo['assetname']." = <span style=\"font-size:14px;\">[?]</span></strong>";
					?>
                    
                    
                    <?php } ?>
                    </td>
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
</div>    
</body>
</html>
