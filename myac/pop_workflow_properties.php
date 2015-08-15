<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['symbol']))
	{
	$_SESSION['asymbol']=mysql_escape_string(trim($_GET['symbol']));
	}
//	echo "<br><br><br><br>------------->".$_SESSION['asymbol'];

if (isset($_GET['wftskid']))
	{
	$_SESSION['idflow']=mysql_escape_string(trim($_GET['wftskid']));
	}

if (isset($_GET['tabview']))
	{
	$_SESSION['tabview_con'] = mysql_escape_string(trim($_GET['tabview']));
	}

if (!isset($_SESSION['tabview_con'])) //set default tab
	{
	$_SESSION['tabview_con'] = 1;
	}

if (isset($_GET['title']))
	{
	$_SESSION['wtitle'] = mysql_escape_string(trim($_GET['title']));
	}
	
//determine the form choices initial values
if  ((isset($_GET['do'])) && ($_GET['do']=="new_form_item") )
	{
	$fields="0";
	}
	
//check object selected
if  (isset($_GET['do']))
	{
	$_SESSION['do']=mysql_escape_string(trim($_GET['do']));
	}
if  (isset($_GET['iditem']))
	{
	$_SESSION['itemid']=mysql_escape_string(trim($_GET['iditem']));
	}
	
	
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
//echo $fields."<br>".$total_fields;
	
//store in a session if this task is groupshare
$sql_prop="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,listorder,wftskflowname,wftskflowdesc,wftsktat,expubholidays,createdby,createdon,modifiedby,modifiedon,h_pos,limit_to_zone,limit_to_dpt,group_task_share 
FROM wftskflow WHERE idwftskflow=".$_SESSION['idflow']." AND wfproc_idwfproc=".$_SESSION['wfselected']." LIMIT 1";
$res_prop=mysql_query($sql_prop);
$fet_prop=mysql_fetch_array($res_prop);

$_SESSION['group_task_share']=$fet_prop['group_task_share'];

//check permissions for this user to view,add,select,delete for this module
$sql_modperm = "SELECT idsystemprofileaccess,syssubmodule_idsyssubmodule,sysprofiles_idsysprofiles,permview,permupdate,perminsert,permdelete,global_access FROM systemprofileaccess 
INNER JOIN syssubmodule ON systemprofileaccess.syssubmodule_idsyssubmodule = syssubmodule.idsyssubmodule 
WHERE permview=1 AND syssubmodule.sysmodule_idsysmodule=".$_SESSION['sec_mod']."  AND syssubmodule.idsyssubmodule=".$_SESSION['sec_submod']."
AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
$res_modperm = mysql_query($sql_modperm);
$num_modperm = mysql_num_rows($res_modperm);
$fet_modperm = mysql_fetch_array($res_modperm);
//echo "<br><Br><br>".$sql_modperm;
//global access
$_SESSION['globalaccess_workflow']=$fet_modperm['global_access'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Window</title>
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
//-->
</script>
<script type="text/javascript" src="../uilock/jquery.uilock.js"></script>
<script language="javascript">
//Preloader for Batch Processing
			$(document).ready(function() {
				//$('#lock').click(function(){
				$('#button_send_left').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>One Moment Please...</center>');
					
				});
				
				
				// To unlock user interface interactions
				//$.uiUnlock();

			});
			
			$(document).ready(function() {
				//$('#lock').click(function(){
				$('#button_send_right').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>One Moment Please...</center>');
					
				});
				
				
				// To unlock user interface interactions
				//$.uiUnlock();

			});

</script>			
</head>
<body>
<div>
<div class="tbl_sh" style="position:fixed; margin:0px 0px 30px 0px; padding:0px; top:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="820">
  <tr>
        	<td width="680">
            <?php echo $_SESSION['wtitle'];?>
       		</td>
          	<td width="140" align="right" style="padding:0px 20px">
            <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
            </td>
      </tr>
    </table>
    </div>
    <div style="padding:30px 0px 0px 0px ">
    	<div class="tab_area">
        <span class="tab<?php if ($_SESSION['tabview_con']==1){ echo "_on"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=1"><?php echo $tab_genprops;?></a></span>
        <?php if ($_SESSION['asymbol']==2) { ?><span class="tab<?php if ($_SESSION['tabview_con']==4){ echo "_on"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=4"><?php echo $lbl_actors;?></a></span><?php } ?>
        <?php if ($_SESSION['asymbol']==2) { ?><span class="tab<?php if ($_SESSION['tabview_con']==5){ echo "_on"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=5"><?php echo $tab_actions;?></a></span><?php } ?>
        <?php if (($_SESSION['asymbol']==1) || ($_SESSION['asymbol']==2) ) { ?><span class="tab<?php if ($_SESSION['tabview_con']==2){ echo "_on"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=2"><?php echo $tab_workinghrs;?></a></span><?php } ?>
        <?php if (($_SESSION['asymbol']==1) || ($_SESSION['asymbol']==2) ) { ?><span class="tab<?php if ($_SESSION['tabview_con']==3){ echo "_on"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=3"><?php echo $tab_wfnotify;?></a></span><?php } ?>
        <?php if ($_SESSION['asymbol']==2) { ?><span class="tab<?php if ($_SESSION['tabview_con']==6){ echo "_on"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=6"><?php echo $tab_esclations;?></a></span><?php } ?>
        <?php if (($_SESSION['asymbol']==1) || ($_SESSION['asymbol']==2)) { ?><span class="tab<?php if ($_SESSION['tabview_con']==7){ echo "_on"; } ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=7"><?php echo $tab_customer_feedback;?></a></span><?php } ?>             
        </div>
    </div>
        <div style="padding:5px">
        <?php
        if ($_SESSION['tabview_con']==1){ require_once('tabview_properties.php'); } 
        if ($_SESSION['tabview_con']==2){ require_once('tabview_workdayhrs.php'); } 
        if ($_SESSION['tabview_con']==3){ require_once('tabview_notification.php'); }
        if ($_SESSION['tabview_con']==4){ require_once('tabview_responsible.php'); } 
        if ($_SESSION['tabview_con']==5){ require_once('tabview_actions.php'); } 
        if ($_SESSION['tabview_con']==6){ require_once('tabview_escalation.php'); } 
        if ($_SESSION['tabview_con']==7){ require_once('tabview_feedback.php'); } 
		if ($_SESSION['tabview_con']==8){ require_once('tabview_formassets.php'); }
        ?>
        </div>
</div>
</body>
</html>
