<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

if (isset($_GET['idform']))	
	{
	$_SESSION['idform']=mysql_real_escape_string(trim($_GET['idform']));
	}

if (isset($_GET['profileid']))
	{
	$_SESSION['profileid']=mysql_real_escape_string(trim($_GET['profileid'])); // this profile id
	}

//check object selected
if  (isset($_GET['do']))
	{
	$_SESSION['do']=mysql_real_escape_string(trim($_GET['do']));
	}


//permissions 
if ( (isset($_GET['doto'])) && (isset($_GET['docmd'])) && (isset($_GET['accessid'])) )	
	{
	$doto=mysql_real_escape_string(trim($_GET['doto']));
	$docmd=mysql_real_escape_string(trim($_GET['docmd']));
	$accessid=mysql_real_escape_string(trim($_GET['accessid']));
	

	//first, check if user owns that accessid
	$sql_owner="SELECT idwfprocassets FROM wfprocassets
	INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup=wfprocassetsgroup.idwfprocassetsgroup	
	WHERE wfprocassetsgroup.userteam_owner=".$_SESSION['MVGitHub_idacteam']." AND idwfprocassets=".$accessid." LIMIT 1";
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
		$sql_exists="SELECT idwfprocassetsaccess FROM wfprocassetsaccess WHERE wfprocassets_idwfprocassets=".$accessid." AND sysprofiles_idsysprofiles=".$_SESSION['profileid']." LIMIT 1";
		$res_exists=mysql_query($sql_exists);
		$num_exists=mysql_num_rows($res_exists);
		//echo "<br><br><br>".$sql_exists."<br>";
		//echo "<br><br><br>".$num_exists;
		if ($num_exists < 1) //if no record
			{
			//then if command
			$sql_priv="INSERT INTO wfprocassetsaccess (wfprocforms_idwfprocforms,wfprocassets_idwfprocassets,".$doto.",sysprofiles_idsysprofiles,createdby,createdon)
			VALUES ('".$_SESSION['idform']."','".$accessid."','".$docmdval."','".$_SESSION['profileid']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_priv);
		//	echo "<br><br><br>".$sql_priv;
			} else {
			//then if command
			$sql_priv="UPDATE wfprocassetsaccess SET ".$doto."='".$docmdval."' WHERE wfprocassets_idwfprocassets=".$accessid." AND sysprofiles_idsysprofiles=".$_SESSION['profileid']." LIMIT 1";
			mysql_query($sql_priv);
		//	echo "<br><br><br><br>".$sql_priv;
			}
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
        	<td >Form Profiles Access</td>
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
                <div class="table_header" style="padding:8px 5px">
                Profiles</div>
            	<div>
                <?php
				//list the profiles associated with this form
				$sql_profiles="SELECT DISTINCT sysprofiles_idsysprofiles,sysprofile,userteamzonename 
				FROM wfprocforms
				INNER JOIN systemprofileaccess ON wfprocforms.syssubmodule_idsyssubmodule=systemprofileaccess.syssubmodule_idsyssubmodule
				INNER JOIN sysprofiles ON systemprofileaccess.sysprofiles_idsysprofiles=sysprofiles.idsysprofiles 
				INNER JOIN usrteamzone ON sysprofiles.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
				WHERE wfprocforms.idwfprocforms=".$_SESSION['idform']."";
				$res_profiles=mysql_query($sql_profiles);
				$num_profiles=mysql_num_rows($res_profiles);
				$fet_profiles=mysql_fetch_array($res_profiles);
				//echo $sql_profiles;
				if ($num_profiles > 0)
					{
					do {
					?>
                    <div class="tbl_data">
                    <a <?php if ( (isset($_SESSION['profileid'])) && ($_SESSION['profileid']==$fet_profiles['sysprofiles_idsysprofiles']) ) { echo "style=\"color:#ff0000;text-decoration:none; font-weight:bold\""; } ?> href="<?php echo $_SERVER['PHP_SELF'];?>?do=view_perms&amp;profileid=<?php echo $fet_profiles['sysprofiles_idsysprofiles'];?>">
                        <div>
                        <?php echo $fet_profiles['sysprofile']; ?> - <small>( <?php echo $fet_profiles['userteamzonename'];?> )</small>
                        </div>
                    </a>
                    </div>
                    <?php
						} while ($fet_profiles=mysql_fetch_array($res_profiles));
					} //greater than 0
				
				?>                  
                </div>
           </div>
            <!--- BR --->            
          </td>
            <td valign="top">
<?php

if ((isset($_SESSION['profileid'])) && ($_SESSION['profileid']>0))
	{

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
	<div class="table_header" style="padding:8px 5px">
    <?php
	$res_title=mysql_query('SELECT sysprofile FROM sysprofiles WHERE idsysprofiles='.$_SESSION['profileid'].'');
	$fet_title=mysql_fetch_array($res_title);
	echo $fet_title['sysprofile'];
	?>
    </div>
        <div>
        	<table border="0" cellpadding="2" cellspacing="0">
				
              <?php			 
			  if ($totalRows_rs_formitems > 0)
			  		{
					
						do {
						//nested loop below
						//loop through the access table to see the status of each object on the table and loop horizontally
						$sql_vals="SELECT idwfprocassetsaccess,perm_read,perm_write,perm_required FROM wfprocassetsaccess
						WHERE wfprocassets_idwfprocassets=".$row_rs_formitems['idwfprocassets']." 
						AND wfprocforms_idwfprocforms=".$_SESSION['idform']." 
						AND wfprocassetsaccess.sysprofiles_idsysprofiles=".$_SESSION['profileid']." LIMIT 1";
						$res_vals=mysql_query($sql_vals);
						$fet_vals=mysql_fetch_array($res_vals);
						//echo $sql_vals;
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
                            <!--<a href="<?php echo $_SERVER['PHP_SELF'];?>?do=edit_form_itemgrouplbl&amp;wfitemgroupid=<?php echo $row_rs_formitems['idwfprocassetsgroup'];?>" style="color:#FFFFFF"  id="button_edit_small"></a>-->
                            </td>
                        </tr>
                    </table> 
                  </td>
                 </tr>
                    <tr>
					<td width="115" height="25" class="tbl_h2">Field lable</td>
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
				</tr>
<?php } //End of Basic-UltraDev Simulated Nested Repeat?>
                      
					  <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
						<td class="tbl_data" height="35">
                       	<a name="<?php echo $row_rs_formitems['idwfprocassets']; ?>">
                         <small> [<?php echo  $row_rs_formitems['item_position'];?>] <?php echo  $row_rs_formitems['ordering'];?></small>
                       <br />
					   <?php echo $row_rs_formitems['assetname'];?>
                        </td>
                        <td class="tbl_data" height="35">
                       	<?php echo  $row_rs_formitems['procdtype'];?>
                        </td>
                        <td class="tbl_data" height="35">
                        <a id="button_check<?php if ($fet_vals['perm_read']==1) { $docmd="off"; echo "_on"; } else { $docmd="on"; } ?>" href="<?php echo $_SERVER['PHP_SELF']."?doto=perm_read&accessid=".$row_rs_formitems['idwfprocassets']."&docmd=".$docmd."&profileid=".$_SESSION['profileid']."";?>#<?php echo $row_rs_formitems['idwfprocassets']; ?>"></a>
                        </td>
                        <td class="tbl_data" height="35">
                        <?php
						if ($fet_vals['perm_read']==1) {
						?>
                        <a id="button_check<?php if ($fet_vals['perm_write']==1) { $docmd="off"; echo "_on"; } else { $docmd="on"; } ?>" href="<?php echo $_SERVER['PHP_SELF']."?doto=perm_write&accessid=".$row_rs_formitems['idwfprocassets']."&docmd=".$docmd."&profileid=".$_SESSION['profileid']."";?>#<?php echo $row_rs_formitems['idwfprocassets']; ?>"></a>
                        <?php } else { echo "---"; }?>
                        </td>
                        <td class="tbl_data" height="35">
                        <?php
						if (($fet_vals['perm_read']==1) && ($fet_vals['perm_write']==1)) {
						?>
                       	<a id="button_check<?php if ($fet_vals['perm_required']==1) { $docmd="off"; echo "_on"; } else { $docmd="on"; } ?>" href="<?php echo $_SERVER['PHP_SELF']."?doto=perm_required&accessid=".$row_rs_formitems['idwfprocassets']."&docmd=".$docmd."&profileid=".$_SESSION['profileid']."";?>#<?php echo $row_rs_formitems['idwfprocassets']; ?>"></a>
                        <?php
						} else { echo "---"; }
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
}
?>
            </td>
        </tr>
    </table> 

	</div>
</div>    
</body>
</html>
