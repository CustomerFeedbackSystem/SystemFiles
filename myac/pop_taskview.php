<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login.php');

//ADDITION TO CATER FOR SECONDARY COMPLAINTS HISTORY --- BY DICKSON ON JULY 25TH 2014
unset($_SESSION['tkt_sectkt']);
unset($_SESSION['tsk_sectkt']);
unset($_SESSION['prevtktid']);
unset($_SESSION['prevtskid']);

//get the title and id and sanitize the inputs
if (isset($_GET['title']))
	{
	$_SESSION['wtitle']=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_GET['title'])));
	}
if (isset($_GET['task']))
	{
	$_SESSION['wtaskid']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_GET['task'])));
	}
	
//CHECK IF I HAVE BEEN DELEGATED TASKS TO DETERMINE IF THE LIST SHOWS OR NOT
$sql_delegated="SELECT usrrolename,utitle,fname,lname,wftasksdeleg_meta.idusrrole_from FROM wftasksdeleg_meta 
INNER JOIN usrrole ON wftasksdeleg_meta.idusrrole_from=usrrole.idusrrole
INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
WHERE wftasksdeleg_meta.idusrrole_to=".$_SESSION['MVGitHub_iduserrole']."
AND wftasksdeleg_meta.deleg_status=1";
$res_delegated=mysql_query($sql_delegated);
$num_delegated=mysql_num_rows($res_delegated);
$fet_delegated=mysql_fetch_array($res_delegated);

//echo "<br><br><br><br><br><br><br>";
	
if (isset($_SESSION['wtaskid']))
	{ 	//003
	//find out if this task has a tskflowid and recepient whether it is to an individual or a group
	$sql_istskflow="SELECT wftskflow_idwftskflow,usrrole_idusrrole,usrac_idusrac,wfactorsgroup_idwfactorsgroup FROM wftasks WHERE idwftasks=".$_SESSION['wtaskid']."";
	$res_istskflow=mysql_query($sql_istskflow);
	$num_istskflow=mysql_num_rows($res_istskflow);
	$fet_istskflow=mysql_fetch_array($res_istskflow);
	
	//is a group task
	$is_group_task=$fet_istskflow['wfactorsgroup_idwfactorsgroup'];
		
	if ($fet_istskflow['wftskflow_idwftskflow'] > 0) //if there is a taskflow, then proceed
		{ //2121
		
		if ( ($fet_istskflow['usrrole_idusrrole']==0) && ($fet_istskflow['usrac_idusrac']==0) && ($fet_istskflow['wfactorsgroup_idwfactorsgroup']>0) )
			{ //if this is a group task		
			//check if it is a group task, then query the specific user role from the group...otherwise use default query
			$sql_task="SELECT idwftasks,wftasks.wftaskstrac_idwftaskstrac,wftasks.usrrole_idusrrole,wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,tasksubject,taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,TIMESTAMPDIFF(MINUTE, NOW(), wftasks.timedeadline) AS time_to_deadline,wftskflow.wfproc_idwfproc,wftskflow.listorder,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftasks.wftasks_batch_idwftasks_batch,wfproc.usrteam_idusrteam FROM wftasks
			INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow = wftskflow.idwftskflow
			INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
			WHERE wftasks.idwftasks=".$_SESSION['wtaskid']."";			
			} else { //else if not a group task
			$sql_task="SELECT idwftasks,wftasks.wftaskstrac_idwftaskstrac,wftasks.usrrole_idusrrole,wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,tasksubject,taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,TIMESTAMPDIFF(MINUTE, NOW(), wftasks.timedeadline) AS time_to_deadline,usrrole.usrrolename,usrrole.usrteamzone_idusrteamzone,usrteamzone.usrteam_idusrteam,usrac.utitle,usrac.lname,wftskflow.wfproc_idwfproc,wftskflow.listorder,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftasks.wftasks_batch_idwftasks_batch FROM wftasks
			INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
			INNER JOIN usrac ON wftasks.usrac_idusrac = usrac.idusrac
			INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow = wftskflow.idwftskflow
			INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
			WHERE wftasks.idwftasks=".$_SESSION['wtaskid']."";
			//echo "<br><br><br>".$sql_task."<br>";
			}
		$res_task=mysql_query($sql_task);
		$fet_task=mysql_fetch_array($res_task);
		$num_task=mysql_num_rows($res_task);

		//this check is only for the first step of entry... after the START list order 0.00 on the workflow
		$sql_wfproc="SELECT wfproc_idwfproc,idwftskflow FROM wftskflow WHERE idwftskflow=".$fet_task['wftskflow_idwftskflow']." LIMIT 1";
		$res_wfproc=mysql_query($sql_wfproc);
		$fet_wfproc=mysql_fetch_array($res_wfproc);
	
		//set the task flow variables here
		$_SESSION['wftaskstrac']=$fet_task['wftaskstrac_idwftaskstrac'];
		$_SESSION['thistskflow']=$fet_task['wftskflow_idwftskflow'];
		$_SESSION['tktin_idtktin']=$fet_task['tktin_idtktin'];
		$_SESSION['wfproc_idwfproc']=$fet_task['wfproc_idwfproc'];
		$_SESSION['listorder']=$fet_task['listorder'];
		
		//get the workflow process ID as well
		
						
		} else { //2121 else possibly this is an exception or the first task wkflowid==0

		//query the database for this task details
		if ( ($fet_istskflow['usrrole_idusrrole']==0) && ($fet_istskflow['usrac_idusrac']==0) && ($fet_istskflow['wfactorsgroup_idwfactorsgroup']>0) )
			{ //if this is a group task		
			//check if it is a group task, then query the specific user role from the group...otherwise use default query
			$sql_task="SELECT idwftasks,wftasks.wftaskstrac_idwftaskstrac,wftasks.usrrole_idusrrole,wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,tasksubject,taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,TIMESTAMPDIFF(MINUTE, NOW(), wftasks.timedeadline) AS time_to_deadline,wftskflow.wfproc_idwfproc,wftskflow.listorder,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftasks.wftasks_batch_idwftasks_batch,tktin.usrteam_idusrteam 
			FROM wftasks
			INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
			WHERE wftasks.idwftasks=".$_SESSION['wtaskid']."";			
			} else { //else if not a group task
			$sql_task="SELECT idwftasks,wftasks.wftaskstrac_idwftaskstrac,wftasks.usrrole_idusrrole,wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,tasksubject,taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,TIMESTAMPDIFF(MINUTE, NOW(), wftasks.timedeadline) AS time_to_deadline,usrrole.usrrolename,usrrole.usrteamzone_idusrteamzone,usrteamzone.usrteam_idusrteam,usrac.utitle,usrac.lname,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftasks.wftasks_batch_idwftasks_batch
			FROM wftasks
			INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
			INNER JOIN usrac ON wftasks.usrac_idusrac = usrac.idusrac
			INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
			WHERE wftasks.idwftasks=".$_SESSION['wtaskid']."";
			$res_task=mysql_query($sql_task);
			$fet_task=mysql_fetch_array($res_task);
			$num_task=mysql_num_rows($res_task);
			}
		
		
	
		//get the workflow process id
		$sql_wfproc="SELECT wfproc_idwfproc,idwftskflow,listorder FROM wftskflow 
		INNER JOIN wftasks ON wftskflow.idwftskflow=wftasks.wftskflow_idwftskflow
		WHERE wftaskstrac_idwftaskstrac=".$fet_task['wftaskstrac_idwftaskstrac']." AND wftskflow_idwftskflow>0 LIMIT 1";
		$res_wfproc=mysql_query($sql_wfproc);
		$fet_wfproc=mysql_fetch_array($res_wfproc);
		
		//set taskflow variables here here	
		$_SESSION['wftaskstrac']=$fet_task['wftaskstrac_idwftaskstrac'];
		$_SESSION['thistskflow']=0; //NOT SET
		$_SESSION['tktin_idtktin']=$fet_task['tktin_idtktin'];
		$_SESSION['wfproc_idwfproc']=$fet_wfproc['wfproc_idwfproc'];
		$_SESSION['listorder']=$fet_wfproc['listorder'];	
		} //2121
			
	if (isset($_POST['tktcat'])) 
		{
		$sql_ticketcat="SELECT tktcategoryname FROM tktcategory WHERE idtktcategory=".$_POST['tktcat']."";
		$res_ticketcat=mysql_query($sql_ticketcat);
		$fet_ticketcat=mysql_fetch_array($res_ticketcat);
		}
	} //003
	
$sql_co="SELECT idwftasks_co,fname,lname FROM wftasks_co 
INNER JOIN usrac ON wftasks_co.idusrrole_owner=usrac.usrrole_idusrrole
WHERE idusrrole_acting=".$_SESSION['MVGitHub_iduserrole']."
AND	idusrrole_owner=".$fet_task['usrrole_idusrrole']." AND co_status=1";
$res_co=mysql_query($sql_co);
$num_co=mysql_num_rows($res_co);
$fet_co=mysql_fetch_array($res_co);

//create the variable
if ($num_co > 0)
	{
	$_SESSION['var_wftaskco']=$fet_co['idwftasks_co'];
	} else {
	$_SESSION['var_wftaskco']=0;
	}	
	
//find out where the task is as at now
		$sql_taskat="SELECT usrrole_idusrrole,wftaskstrac_idwftaskstrac FROM wftasks WHERE wftaskstrac_idwftaskstrac=".$fet_task['wftaskstrac_idwftaskstrac']." ORDER BY idwftasks DESC LIMIT 1";
		$res_taskat=mysql_query($sql_taskat);
		$fet_taskat=mysql_fetch_array($res_taskat);
//		echo "<br><br>".$sql_taskat;	
//echo "<br><br>".$sql_task;				
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<title>Task Details</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>
<!--
<script type="text/javascript" src="../scripts/jquery-1.9.1.min.js"></script>
-->
<script type="text/javascript" src="../scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-timepicker-addon_.js"></script>
<script src="../scripts/gen_validatorv4.js"  type="text/javascript"></script>
<script type="text/javascript" src="../scripts/jquery.autocomplete.js"></script>
<!-- <msdropdown> 
<link rel="stylesheet" type="text/css" href="../assets_backend/css/dd.css" />
<script src="js/jquery.dd.js"></script>
-->
<script type="text/javascript" src="../scripts/animatedcollapse.js">
/***********************************************
* Animated Collapsible DIV v2.4- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/
</script>
<script type="text/javascript">
animatedcollapse.addDiv('details', 'fade=0,speed=400,group=pets')
animatedcollapse.addDiv('contacts', 'fade=0,speed=400,group=pets,persist=1,hide=1')
animatedcollapse.addDiv('dataform1', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform2', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform3', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform4', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform5', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform6', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform7', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform8', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform9', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('dataform10', 'fade=0,speed=400,group=pets,hide=0')
animatedcollapse.addDiv('feedback', 'fade=0,speed=400,group=pets,hide=1')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}

animatedcollapse.init()
</script>
<!--<script language="javascript" src="../scripts/ts_picker.js"></script>-->
<!--<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>-->
<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../thickbox/thickbox_be.js"></script>
<script language="javascript">
//restrict to numbers or alpha
var numb = "0123456789.-";
var alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";
function res(t,v){
var w = "";
for (i=0; i < t.value.length; i++) {
x = t.value.charAt(i);
if (v.indexOf(x,0) != -1)
w += x;
}
t.value = w;
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
	
	
function getwkflow(tktcatId) {		
		
		var strURL="findworkflow_step_1.php?tktcat="+tktcatId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('workflowdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}	
	

//autocomplete the Location
$().ready(function() {
	$("#locationtown").autocomplete("findlocation_2.php", {
		width: 350,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: true,
		//multipleSeparator: ",",
		selectFirst: false
	});
});


//autocomplete the staff 
$().ready(function() {
	$("#recepient_alt").autocomplete("findrole_alt.php", {
		width: 450,
		matchContains: true,
		//mustMatch: true
		//minChars: 0,
		//multiple: true,
		//highlight: true,
		//multipleSeparator: ",",
		selectFirst: false
	});
});

</script>
<style type='text/css'>
    .actionlist {
     display: none;
	 padding:0px;
	 margin:0px;   
}

.optionvalue {
     border: 0px;   
}

</style>
<script type='text/javascript'>
//list the relevant fields basedon the action selected by the user
//<![CDATA[ 
$(window).load(function(){
$('.switchaction').change(function(){
    var selected = $(this).find(':selected');
    $('.actionlist').hide();
   $('.'+selected.val()).show(); 
    $('.optionvalue').html(selected.html());
});
});//]]>  


//hide show invalid reasons text box
    $(function() {
        $('#invalid_id').change(function(){
            $('.invalid_new').hide();
            $('#' + $(this).val()).show();
        });
    });
	
//hide or show the exceptional recepients
function showstuff(element){
    document.getElementById("other_exception").style.display = element=="other_exception"?"block":"none";
}

</script>
<!-- Preloader on Click Below -->
<script type="text/javascript" src="../uilock/jquery.uilock.js"></script>
<script language="javascript">
			$(document).ready(function() {
				//$('#lock').click(function(){
				$('#button_passiton').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>Please Wait One Moment ...</center>');
					
				});
				
				
				// To unlock user interface interactions
				//$.uiUnlock();

			});
			
			
			$(document).ready(function() {
				//$('#lock').click(function(){
				$('#button_progup').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>Please Wait One Moment ...</center>');
					
				});
				
				
				// To unlock user interface interactions
				//$.uiUnlock();

			});
			
			

//get the next workflow step in casese of exceptions
//get the next workflow step in casese of exceptions
function nextstep(nextstepId) {		
		
		var strURL="ajax_nextstep.php?nextstep="+nextstepId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('nextstepdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}	
	}
	
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("ajax_nextstep_rec.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
</script>
<style type="text/css">

	.suggestionsBox {
		position: relative;
		left: 0px;
		margin: 2px 0px 0px 0px;
		width: 350px;
		background-color: #CCCCCC;
		-moz-border-radius: 7px;
		-webkit-border-radius: 7px;
		border: 1px solid #333;	
		color: #333333;
		font-size:12px;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}
</style>
<?php
//form data calculations validations
$sql_formdata_calc="SELECT idwfprocassetsaccess, assetname, perm_read, perm_write, perm_required, wfprocassets.wfprocdtype_idwfprocdtype, idwfprocassets, wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms,
wfprocassets.is_calc
FROM wfprocassetsaccess
INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=5 
AND wfprocassetsaccess.perm_read=1 
AND is_calc=1 AND wfprocdtype_idwfprocdtype=8
ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
 $res_formdata_calc=mysql_query($sql_formdata_calc);
 $num_formdata_calc=mysql_num_rows($res_formdata_calc);
 $fet_formdata_calc=mysql_fetch_array($res_formdata_calc);
 
 if ($num_formdata_calc>0)
 	{
	 do {
 //if text numbers only, then check
$res_calc=mysql_query("SELECT wfassets_var1,calc,wfassets_var2,wfassets_results,
(SELECT idwfprocassetsaccess FROM wfprocassetsaccess WHERE wfprocassets_idwfprocassets=wfassets_results AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1)  as result_id,
(SELECT idwfprocassetsaccess FROM wfprocassetsaccess WHERE wfprocassets_idwfprocassets=wfassets_var1 AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1)  as x_id,
(SELECT idwfprocassetsaccess FROM wfprocassetsaccess WHERE wfprocassets_idwfprocassets=wfassets_var2 AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1)  as y_id
FROM wfprocassets_calcs 
WHERE wfassets_results=".$fet_formdata_calc['idwfprocassets']." ");
$num_calc=mysql_num_rows($res_calc);
$fet_calc=mysql_fetch_array($res_calc);

//validate to ensure that
if (($num_calc > 0) && ($fet_calc['result_id'] >0) && ($fet_calc['x_id'] >0) && ($fet_calc['y_id'] >0) )
	{
	//create the javascript
	echo "<script language=\"javascript\">
	function calculate_".$fet_calc['result_id']."() {
	
	// temporary place to store the result
	var result = 0;
	
	// convert the vales in the boxes into integers
	var x = parseFloat(document.task.item_".$fet_calc['x_id'].".value);
	var y = parseFloat(document.task.item_".$fet_calc['y_id'].".value);
	
	result = parseFloat((x ".$fet_calc['calc']." y).toFixed(2));
	document.task.item_".$fet_calc['result_id'].".value = result;
	
	}
	</script>";
	
	//set the onMouse var
	$onMouse=" readonly=\"readonly\" style=\"background-color:#f7f7f7 \" ";	

	} else {
	
	echo "<script language=\"javascript\">
	function calculate_".$fet_calc['result_id']."() {
	}
	</script>";
	
	}	
 
 
 	} while ($fet_formdata_calc=mysql_fetch_array($res_formdata_calc));
 }
?>
</head>
<?php
//Get the Ticket Details on the form
$sql_ticket="SELECT idtktinPK,tktchannelname,tktstatusname,tktcategoryname,locationname,tktlang_idtktlang,usrteamzone_idusrteamzone,usrteam_idusrteam,tktin.tktgroup_idtktgroup,tktin.tktchannel_idtktchannel,tktin.tktstatus_idtktstatus,tktin.tktcategory_idtktcategory,tktin.tkttype_idtkttype,sendername,senderphone,senderemail,refnumber,tktdesc,timereported,timedeadline,timeclosed,city_town,loctowns_idloctowns,road_street,building_estate,unitno,waterac,kioskno,tkttype.idtkttype,tkttype.tkttypename,tktin.landmark,tktin.sendergender,refnumber_prev,wftasks_batch_idwftasks_batch,voucher_number FROM tktin
INNER JOIN tktchannel ON tktin.tktchannel_idtktchannel=tktchannel.idtktchannel
INNER JOIN tktstatus ON tktstatus_idtktstatus=tktstatus.idtktstatus
INNER JOIN tkttype ON tktin.tkttype_idtkttype=tkttype.idtkttype
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory
INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns WHERE idtktinPK=".$fet_task['tktin_idtktin']." 
AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']."";
$res_ticket=mysql_query($sql_ticket);
$fet_ticket=mysql_fetch_array($res_ticket);

$_SESSION['tktupdate']=$fet_ticket['idtktinPK']; //store the id for the ticket to update
$_SESSION['wtitle']=$fet_ticket['refnumber'];
$_SESSION['prev_tkt_num']=$fet_ticket['refnumber_prev'];
//echo "<br><br><br>".$sql_ticket;
//ADDITION TO CATER FOR SECONDARY COMPLAINTS HISTORY --- BY DICKSON ON JULY 25TH 2014
//If its a secondary ticket -- Get the ID of the primary ticket
//echo $fet_ticket['refnumber_prev']."----";

if(strlen($fet_ticket['refnumber_prev'])>0)
	{
	$sql_prevtkt="SELECT idtktinPK FROM tktin 
	WHERE refnumber='".$fet_ticket['refnumber_prev']."' LIMIT 1";
	$res_prevtkt=mysql_query($sql_prevtkt);
	$fet_prevtkt=mysql_fetch_array($res_prevtkt);
	$num_prevtkt=mysql_num_rows($res_prevtkt);
		
	if($num_prevtkt>0)
		{
		$_SESSION['prevtktid']=$fet_prevtkt['idtktinPK'];
		
		//Get the last task for this primary ticket.
		$sql_prevtsk="SELECT idwftasks FROM wftasks WHERE tktin_idtktin=".$fet_prevtkt['idtktinPK']." ORDER BY idwftasks DESC LIMIT 1";
		$res_prevtsk=mysql_query($sql_prevtsk);
		$fet_prevtsk=mysql_fetch_array($res_prevtsk);
		$num_prevtsk=mysql_num_rows($res_prevtsk);

		if($num_prevtsk>0)
			{
			$_SESSION['prevtskid']=$fet_prevtsk['idwftasks'];
			}
		}	
	}

flush();
?>
<body style="background-color:#ffffff">
<div style="background-color:#ffffff">
	<div class="tbl_sh" style="position:fixed; margin:0px 0px 35px 0px; padding:0px; top:0px; width:100%">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
  		<tr>
        	<td width="60%">
            <div>
            <?php if (isset($_SESSION['wtitle'])) { if(isset($_SESSION['prevtktid'])) {?><span style="background-color:#990000; color:#FFFFFF; padding:3px;"><?php echo $_SESSION['wtitle']." - This is a Secondary Ticket"; ?></span> <?php } else { echo $_SESSION['wtitle']; }}?>
            </div>
       		</td>
          	<td align="right" width="40%">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td>&nbsp;</td>
               	  <td>
                        	<?php if( (isset($_SESSION['prevtktid'])) && (isset($_SESSION['prevtskid'])) ) { ?>
                        		<a href="go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>&task=<?php echo $_SESSION['wtaskid'];?>&tkt_st=<?php echo $_SESSION['prevtktid'];?>&task_st=<?php echo $_SESSION['prevtskid'];?>" onClick="" id="button_viewhistory"></a>
                           	<?php } else { ?>     
                            	<a href="go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>&task=<?php echo $_SESSION['wtaskid'];?>" onClick="" id="button_viewhistory"></a>
                            <?php } ?>
                        </td>
                    	<td>
						<a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                        </td>
					</tr>
				</table>
            </td>
      </tr>
    </table>
</div>
<div style="padding:33px 0px 0px 0px">
<?php
if ( (isset($_POST['update_ticket_details'])) && ($_POST['update_ticket_details']=="Save") ) //Save Ticket Details
	{ 
	//clean up
	$tktacno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['acnumber'])));
	$tktkiosk=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['kiosk'])));
	$txtmsg=mysql_real_escape_string(trim($_POST['txtmsg']));
	
	$sql_update="UPDATE tktin SET 
	kioskno='".$tktkiosk."',
	tktdesc='".$txtmsg."'
	WHERE idtktinPK=".$_SESSION['tktin_idtktin'].""; //waterac='".$tktacno."',
	
	mysql_query($sql_update);
	
	//set the message
	$msg_ticket_details="<div class=\"msg_success_small\">Changed Successfully</div>";
	} 


if ( (isset($_POST['update_location_details']))  && ($_POST['update_location_details']=="Save") ) //Save Location and Contact Details
	{
	//clean up
	$tktsender=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['sendername'])));
	$tktsenderphone=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['senderphone'])));
	$tktsenderemail=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['senderemail'])));
	$tktstreet=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['roadstreet'])));
	$tktbuilding=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['building'])));
	$tktunitno=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['unitnumber'])));
	$tktloc=mysql_real_escape_string(trim($_POST['locationtown']));
	$usrgender=mysql_real_escape_string($_POST['usrgender']);
	$directions=mysql_real_escape_string($_POST['directions']);
	$tktchannel=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['tktchannel'])));
	
	//validate the process
	if (strlen($tktsender) < 2)
		{
		$error_details_1="Sender Name";
		}
	if (strlen($tktloc) < 1)
		{
		$error_details_2="Town / City";
		}
		
	//check if sender phone or at least sender email is set
	if (strlen($tktsenderphone)<12) 
		{
		$error_details_3_1=1;
		}
		
	if ( (strlen($tktsenderemail) > 5) && (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $tktsenderemail)) )
		{
		$error_details_3_2=1;
		}
		
	if ( ( (isset($error_details_3_1)) && (isset($error_details_3_2)) ) || ( (isset($error_details_3_1)) || (isset($error_details_3_2)) )  )
		{
		$error_details_3="Valid Mobile No. or eMail Address";
		}
		
	if (!isset($error_details_2))
			{	
			$sql_confirmloc="SELECT idloctowns,locationname FROM loctowns WHERE locationname='".$tktloc."' LIMIT 1";
			$res_confirmloc=mysql_query($sql_confirmloc);
			$num_confirmloc=mysql_num_rows($res_confirmloc);
			$fet_confirmloc=mysql_fetch_array($res_confirmloc);
			
			if ($num_confirmloc > 0) //if there is a location then
				{
				
				$location_id=$fet_confirmloc['idloctowns'];
				
				} else {
				//add new location
				$sql_newloc="INSERT INTO loctowns (loccountry_idloccountry,locationname,lng,lat,createdby,createdon,is_valid,is_town)
				VALUES ('1','".$tktloc."','0','0','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','1','0')";
				mysql_query($sql_newloc);
				
				//retreive that number
				$sql_idloc="SELECT idloctowns,locationname FROM loctowns WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idloctowns DESC LIMIT 1"; 
				$res_idloc=mysql_query($sql_idloc);
				$fet_idloc=mysql_fetch_array($res_idloc);
				
				$location_id=$fet_idloc['idloctowns']; //thats the new location
				
				//just send email to support to map the new address
				
				
				//in such a case, the new location does not have the coordinates. Therefore, alert ICT team to add the new coordinates
				//the ict teams must be of that region
				//1. check if this region has a ICT_SUPPORT_MV role 
/*				$sql_checksupport="SELECT idusrrole,wfactors.wftskflow_idwftskflow,idusrac FROM usrrole 
				INNER JOIN wfactors ON usrrole.idusrrole=wfactors.usrrole_idusrrole
				INNER JOIN wftskflow ON wfactors.wftskflow_idwftskflow=wftskflow.idwftskflow
				INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
				INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
				WHERE usrrole.usrrolename='ICT_SUPPORT_MV' AND wfproc.wftskflowname='ICT_SUPPORT_MV' AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1";
				$res_checksupport=mysql_query($sql_checksupport);
				$num_checksupport=mysql_num_rows($res_checksupport);
				$fet_checksupport=mysql_fetch_array($res_checksupport);
				
				if ($num_checksupport > 0)
					{
					//2. then create a task for that role 
					$sql_gentracx="INSERT INTO wftaskstrac (createdon,createdby) VALUES ('".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
					mysql_query($sql_gentracx);

					$sql_tracx="SELECT idwftaskstrac FROM wftaskstrac WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftaskstrac DESC LIMIT 1";
					$res_tracx=mysql_query($sql_tracx);
					$fet_tracx=mysql_fetch_array($res_tracx);

															
					//insert new task for the recepeint
					$sql_new_taskx="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon,createdby) 
					VALUES ('".$fet_tracx['idwftaskstrac']."','".$fet_checksupport['iduserrole']."','0','".$fet_checksupport['wftskflow_idwftskflow']."','".$fet_tktid['idtktinPK']."','".$_SESSION['MVGitHub_idacname']."','1','3','".$fet_tktid['tktcategoryname']." - ".$ticketref."','[MANUAL ENTRY]".$tktmsg."','".$tktdate_fin."','".$deadline."','".$task_starttime_final."','".$task_deadline_final."','".$timenowis."','2','2','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
					mysql_query($sql_new_taskx);
						
					//3. initiate the notifications as well for them to do the job of validating the new address
					
					}
*/
				}
			}
	
	if ( (!isset($error_details_1)) && (!isset($error_details_2)) && (!isset($error_details_3)) ) //if no error, then process this
		{
		$sql_update="UPDATE tktin SET 
		sendername='".$tktsender."',
		sendergender='".$usrgender."',
		senderemail='".$tktsenderemail."',
		senderphone='".$tktsenderphone."',
		city_town='".$tktloc."',
		loctowns_idloctowns='".$location_id."',
		road_street='".$tktstreet."',
		building_estate='".$tktbuilding."',
		unitno='".$tktunitno."',
		landmark='".$directions."'
		WHERE idtktinPK=".$_SESSION['tktin_idtktin']."";
		//echo $sql_update;
		mysql_query($sql_update);
		
		//msg_success
		$msg_location_details_ok="<div class=\"msg_success_small\">Location Details Saved Successfully</div>";
		} else {
		$msg_location_details_warn="Warning : Missing ";
		}
	
	} //Save

//form action
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="process_task") && (!isset($_POST['update_ticket_details'])) && (!isset($_POST['update_location_details'])) )
	{ //form action
	//now, sanitize the inputs
		$tktno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['tktnumber'])));
		$tktcat=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['tktcat'])));
		$tktacno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['acnumber'])));
		$tktkiosk=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['kiosk'])));
		$tktsender=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['sendername'])));
		$tktsenderphone=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['senderphone'])));
		$tktsenderemail=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['senderemail'])));
		$tktstreet=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['roadstreet'])));
		$tktbuilding=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['building'])));
		$tktunitno=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['unitnumber'])));
		$tktloc=mysql_real_escape_string(trim($_POST['locationtown']));
		$usrgender=mysql_real_escape_string($_POST['usrgender']);
		$directions=mysql_real_escape_string($_POST['directions']);

		
		$tktaction=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['action_to'])));
		$updateperm=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['up'])));
		
		//generate SMS if closed ticket / task
		if ($tktaction==1)
			{
			//retrieve the message composed for this kind of ticket
			$tktsms_raw=$_SESSION['MVGitHub_acteamshrtname']." [".$tktno."] This matter has been resolved. ";
			$tktsms=substr($tktsms_raw,0,160);
			} else {
				if (isset($_POST['txtsms']))
					{
					$tktsms_raw=" ".preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['txtsms'])));
					$tktsms=substr($tktsms_raw,0,160);
					}
			}

		//clean up optional fields
		if (isset($_POST['close_1']))
			{
			$tkticonfirm=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['close_1'])));
			}
		if (isset($_POST['task_msg_1']))
			{
			$tkttskmsg1=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(strip_tags(trim($_POST['task_msg_1']))));
			}
		if (isset($_POST['task_msg_2']))
			{
			$tkttskmsg2=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(strip_tags(trim($_POST['task_msg_2']))));
			}
		if (isset($_POST['task_msg_3']))
			{
			$tkttskmsg3=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(strip_tags(trim($_POST['task_msg_3']))));
			}
		if (isset($_POST['task_msg_4']))
			{
			$tkttskmsg4=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(strip_tags(trim($_POST['task_msg_4']))));
			}
		if (isset($_POST['task_msg_5']))
			{
			$tkttskmsg5=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(strip_tags(trim($_POST['task_msg_5']))));
			}
		if (isset($_POST['task_msg_6']))
			{
			$tkttskmsg6=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(strip_tags(trim($_POST['task_msg_6']))));
			}
		if (isset($_POST['task_msg_8']))
			{
			$tkttskmsg8=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(strip_tags(trim($_POST['task_msg_8']))));
			}	
		if (isset($_POST['task_msg_9']))
			{
			$tkttskmsg9=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(strip_tags(trim($_POST['task_msg_9']))));
			}
		if (isset($_POST['assign_to_9']))
			{
			$tktasito9=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['assign_to_9'])));
			}	
		if (isset($_POST['assign_to_2']))
			{
			$tktasito2=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['assign_to_2'])));
			}
						
		if (isset($_POST['assign_to_8']))
			{
			$tktasito8=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['assign_to_8'])));
			}
		if (isset($_POST['assign_to_3']))
			{
			$tktasito3=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['assign_to_3'])));
			}
		if (isset($_POST['assign_to_5']))
			{
			$tktasito5=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['assign_to_5'])));
			}
		if (isset($_POST['invalid_id']))
			{
			$tktinvalidid=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_real_escape_string(trim($_POST['invalid_id'])));
			}
		if (isset($_POST['add_reason']))
			{
			$tktinvalidnew=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['add_reason'])));
			}
		if (isset($_POST['senderemail']))
			{
			$tktsenderemail=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['senderemail'])));
			}
		if (isset($_POST['newdeadline']))
			{
			$tktnewdeadline=preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_real_escape_string(trim($_POST['newdeadline'])));
			$tktnewdeadline_trans=str_replace('/','-',$tktnewdeadline);

			$tktnewdeadline_fin=date("Y-m-d H:i:s",strtotime($tktnewdeadline_trans));
			}
		
		if (isset($_POST['batch_no']))
			{
				if ($_POST['batch_no'] >0)
					{
					$batch_no=mysql_real_escape_string(trim($_POST['batch_no']));
					} else {
					$batch_no=0;
					}
			} else {
			$batch_no=0;
			}
		
		// let's validate that all the fields have been entered
		if ($tktcat < 1)
			{
			$error_1="<div class=\"msg_warning_small\">".$msg_warning_nocategory."</div>";
			}
		if (strlen($tktloc) < 1)
			{
			$error_2="<div class=\"msg_warning_small\">".$msg_warning_location."</div>";
			}
		$sql_confirmloc="SELECT idloctowns,locationname FROM loctowns WHERE locationname='".$tktloc."' LIMIT 1";
		$res_confirmloc=mysql_query($sql_confirmloc);
		$num_confirmloc=mysql_num_rows($res_confirmloc);
		$fet_confirmloc=mysql_fetch_array($res_confirmloc);
		
		if ($num_confirmloc < 1)
			{
			$error_3="<div class=\"msg_warning_small\">".$msg_warning_invalidloc."</div>";
			}
		if ($tktaction < 1)
			{
			$error_4="<div class=\"msg_warning_small\">".$msg_select_action."</div>";
			}
			
			if ( (!isset($error_1)) && (!isset($error_2)) && (!isset($error_3))  && (!isset($error_4)) )
				{ //if no error set
				
				$sql_idtask="SELECT wftaskstrac_idwftaskstrac FROM wftasks WHERE idwftasks=".$_SESSION['wtaskid']." ORDER BY idwftasks DESC LIMIT 1";
				$res_idtask=mysql_query($sql_idtask);
				$fet_idtask=mysql_fetch_array($res_idtask);
				
///////////////  ACTION 1  ///////////////////////////////////////////////////////////////////////////////////////////////////
		
				//option 1 = Close Task as per DB key
				if ($tktaction==1) //Select Task Action 1
					{
					
					//validate
					if (strlen($tkttskmsg1) < 1)
						{
						$error_1_1="<div class=\"msg_warning_small\">".$msg_warn_msgmis."</div>";
						}
					if ((!isset($tkticonfirm)) || ($tkticonfirm!=1))
						{
						$error_1_2="<div class=\"msg_warning_small\">".$msg_warn_confirm."</div>";
						}
						
					if ( (!isset($error_1_1)) && (!isset($error_1_2)) )//if the no error 
						{	//if the no error 1_1
						
						mysql_query("BEGIN");
						
						//create an update message on the record
						$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
						VALUES ('".$fet_idtask['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','3','1','".$_SESSION['wtaskid']."','".$tkttskmsg1."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						$query_1=mysql_query($sql_update_msg);
						
						
						//check if there is a form data and if so, go ahead and process this transaction with inserts or updates
						if ( (isset($_POST['formdata_available'])) && ($_POST['formdata_available']==1) )
							{ //go ahead process
							//echo "processed <br>";
							//check the db for this field by reusing the sql statement above
							/*
							$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets FROM wfprocassetsaccess 
							INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
							WHERE wftskflow_idwftskflow=".$fet_task['wftskflow_idwftskflow']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassets.ordering ASC";
							*/
							$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets,wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms
							FROM wfprocassetsaccess
							INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
							INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
							INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
							WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=".$fet_ticket['tktcategory_idtktcategory']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
//echo $sql_val;
							$res_val=mysql_query($sql_val);
							$num_val=mysql_num_rows($res_val);
							$fet_val=mysql_fetch_array($res_val);
							
							
							if ($num_val > 0) //if there are some values, then
								{
								do {
								//master-checklist if  | it is required | there is a value | the data type to determine the field |  whether an update or insert
								
								//validate required
							//	echo "validation ";
							//	echo $_POST['required_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
							//	echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].''];
								if ( (isset($_POST['required_'.$fet_val['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_val['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']=="") )
									{
									//echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
									$error_formdata=1;									
									echo "<div class=\"msg_warning_small\">Form: ".$fet_val['assetname']." is required</div>";				
									}
									
								//if no error on the dataform, then process
								if (!isset($error_formdata))
									{	
									if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="INSERT")
										{
										//check the form item type first
										$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
										
											
											if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10)   ) //if textbox OR yes/no OR datepicker OR datetimepicker
												{
												$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
												
												//then process as below
												$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
												wfprocassetschoice_idwfprocassetschoice,
												wfprocassets_idwfprocassets,
												wftasks_idwftasks,
												value_choice,
												value_path,
												wftaskstrac_idwftaskstrac,
												tktin_idtktin,
												createdby,
												createdon)
												VALUES ('".$fet_val['idwfprocassetsaccess']."',
												'0',
												'".$fet_val['idwfprocassets']."',
												'".$_SESSION['wtaskid']."',
												'".$fvalue."',
												'',
												'".$_SESSION['wftaskstrac']."',
												'".$_SESSION['tktin_idtktin']."',
												'".$_SESSION['MVGitHub_idacname']."',
												'".$timenowis."'
												)";
												
												mysql_query($sql_insert);
												//echo $sql_insert;
												//exit;
												}
												
											if ($ttype==2)//if menulist
												{
												$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
												
												$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
												wfprocassetschoice_idwfprocassetschoice,
												wfprocassets_idwfprocassets,
												wftasks_idwftasks,
												value_choice,
												value_path,
												wftaskstrac_idwftaskstrac,
												tktin_idtktin,
												createdby,
												createdon)
												VALUES ('".$fet_val['idwfprocassetsaccess']."',
												'".$fvalue."',
												'".$fet_val['idwfprocassets']."',
												'".$_SESSION['wtaskid']."',
												'',
												'',
												'".$_SESSION['wftaskstrac']."',
												'".$_SESSION['tktin_idtktin']."',
												'".$_SESSION['MVGitHub_idacname']."',
												'".$timenowis."'
												)";
												
												mysql_query($sql_insert);
												
												}
												
										if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											//just keep the file name only
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
											//check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
												
												$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
												
												//validation before uploading										 
												//check if file exists
												if (file_exists($target_file)) 
													{
													$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
													}
												
												if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
													{
													$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
													}
												
												if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
													&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
													&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
													&& $imageFileType != "ppt" && $imageFileType != "pptx"  && $imageFileType != "csv"    ) {
														
													$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
													}
												//echo $upload_error_1.$upload_error_2.$upload_error_3;	
												//echo "Size -->".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
												if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
													{
													 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
														{
														$upload_success=1;
														//log the record into the Database
														$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
														wfprocassetschoice_idwfprocassetschoice,
														wfprocassets_idwfprocassets,
														wftasks_idwftasks,
														value_choice,
														value_path,
														wftaskstrac_idwftaskstrac,
														tktin_idtktin,
														createdby,
														createdon)
														VALUES ('".$fet_val['idwfprocassetsaccess']."',
														'0',
														'".$fet_val['idwfprocassets']."',
														'".$_SESSION['wtaskid']."',
														'',
														'".$file_name_only."',
														'".$_SESSION['wftaskstrac']."',
														'".$_SESSION['tktin_idtktin']."',
														'".$_SESSION['MVGitHub_idacname']."',
														'".$timenowis."'
														)";
														
														mysql_query($sql_insert);
														
														//create the audit log
														$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, wfprocassets_idwfprocassets) 
														VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."','".$fet_val['idwfprocassets']."')";
														mysql_query($sql_audit);
														
														} else {
															$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
														}
													} //no error
												} //where strlen > 4
											} //type==3
										
										} //close INSERT
										
										
										
									if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="UPDATE")
									{
									$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
									$itempk=mysql_real_escape_string(trim($_POST['itempk_'.$fet_val['idwfprocassetsaccess'].'']));
									
									//value captured - //this hack for checkbox
									if ((($ttype==3)||($ttype==4)) && (!isset($_POST['item_'.$fet_val['idwfprocassetsaccess'].''])) )
										{
										$fvalue=0;
										} else {
										$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
										}
									
									//only if there are records
									if (
										( ($fvalue > 0) || (strlen($fvalue) > 0) && ($ttype!=4) ) 
										|| 
										( ($ttype==4) && (($fvalue=='') || ($fvalue==0) || ($fvalue!=0)) ) 
										) 
									/*if ( ($fvalue!='') && (strlen($fvalue) > 0) )*/
										{
										//check the form item type first
										if (($ttype==1) || ($ttype==4)  || ($ttype==5) || ($ttype==6) || ($ttype==7)  || ($ttype==8) || ($ttype==9) || ($ttype==10)  ) //if textbox OR yes/no OR datepicker OR datetimepicker
												{
												///audit log
												$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
												SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '".$fvalue."', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
												FROM wfassetsdata
												WHERE idwfassetsdata=".$itempk." AND value_choice!='".$fvalue."' ";
												//echo $sql_auditlog_form."<br>";
												mysql_query($sql_auditlog_form);
												
												//then process as below
												$sql_update="UPDATE wfassetsdata SET 
												value_choice='".$fvalue."',
												wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
												tktin_idtktin='".$_SESSION['tktin_idtktin']."',
												modifiedby='".$_SESSION['MVGitHub_idacname']."',
												modifiedon='".$timenowis."'
												WHERE idwfassetsdata=".$itempk." LIMIT 1";
												
												mysql_query($sql_update);
												//echo $sql_update;
												}
										
										if ($ttype==2)//if menulist
												{
												//enter the audit trail only if there is a change
												$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
												SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'".$fvalue."', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
												FROM wfassetsdata
												WHERE idwfassetsdata=".$itempk." AND wfprocassetschoice_idwfprocassetschoice!='".$fvalue."' ";
												//echo $sql_auditlog_form."<br>";
												mysql_query($sql_auditlog_form);
												
												$sql_update="UPDATE wfassetsdata SET 
												wfprocassetschoice_idwfprocassetschoice='".$fvalue."',
												wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
												tktin_idtktin='".$_SESSION['tktin_idtktin']."',
												modifiedby='".$_SESSION['MVGitHub_idacname']."',
												modifiedon='".$timenowis."'
												WHERE idwfassetsdata=".$itempk." LIMIT 1";
												
												mysql_query($sql_update);
												}
										
										if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];

											////check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
											
													$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
													
													//validation before uploading											 
													//check if file exists
													if (file_exists($target_file)) 
														{
														$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
														}
													
													if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
														{
														$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
														}
													
													if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
														&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
														&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
														&& $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "csv"    ) {
															
														$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
														}

													if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
														{
														//echo "soo farr soo good";
														 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
															{
															$upload_success=1;
															
															$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
															SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'".$file_name_only."', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
															FROM wfassetsdata
															WHERE idwfassetsdata=".$itempk." AND value_path!='".$file_name_only."' ";
															//echo $sql_auditlog_form."<br>";
															mysql_query($sql_auditlog_form);
															
															//log the record into the Database
															$sql_update="UPDATE wfassetsdata SET 
															value_path='".$file_name_only."',
															modifiedby='".$_SESSION['MVGitHub_idacname']."',
															modifiedon='".$timenowis."'
															WHERE idwfassetsdata=".$itempk." LIMIT 1";

															mysql_query($sql_update);
															
															//create the audit log
															$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip,wfprocassets_idwfprocassets) 
															VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."',".$itempk.")";
															mysql_query($sql_audit);
																											
															} else {
																$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
															}
														} //no error
													} // if fvalue strlen>4
											
												} //type==3				
											}
											
											} //close UPDATE
										
										} //close form data error
									
									} while ($fet_val=mysql_fetch_array($res_val)); //close WHILE
							
								} //if record is > 0 // data checker
								
							} //form data available
							
							//Close this Task
							$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='1',wftskstatusglobal_idwftskstatusglobal='3',timeactiontaken='".$timenowis."',actedon_idusrrole=".$_SESSION['MVGitHub_iduserrole'].", actedon_idusrac='".$_SESSION['MVGitHub_idacname']."' WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
							$query_2=mysql_query($sql_update_task);
							
							//Feedback SMS to send customer/sender a message
							if ( (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) )
								{
								$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext) 
								VALUES ('".$tktsenderphone."','".$tktsms."')";
								mysql_query($sql_smsout);
								}
								
							//Update the ticket status
						
								
								$sql_updatetkt="UPDATE tktin SET 
								tktstatus_idtktstatus='4',
								timeclosed='".$timenowis."'
								WHERE idtktinPK=".$_SESSION['tktupdate']." 
								LIMIT 1";								
								$query_3=mysql_query($sql_updatetkt);
								
								
							
								//notify if anyone is to be notified
								$sql_notify="SELECT idwfnotification,wfnotification.tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,idtktmsgs,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification 
								INNER JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
								WHERE wftskflow_idwftskflow=".$_SESSION['thistskflow']." ORDER BY idwfnotification ASC";
								$res_notify=mysql_query($sql_notify);
								$num_notify=mysql_num_rows($res_notify);
								$fet_notify=mysql_fetch_array($res_notify);
					
								if ($num_notify > 0 ) // if there is a notification setting
									{
										do {			
										//check for each of the settings 
											if ( ($fet_notify['notify_system']==1) && (strlen($fet_notify['tktmsg_dashboard'])>2) ) //system dashboard set on
												{
												$sql_dash="INSERT INTO tktmsglogs_dashboard (tktmsgs_idtktmsgs,msgto_roleid,msgto_subject,msgto_body,createdon,readon)
												VALUES ('".$fet_notify['idtktmsgs']."','".$fet_notify['usrrole_idusrrole']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname'].",'".$fet_notify['tktmsg_dashboard']." - ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
												mysql_query($sql_dash);									
												}// system dashboard set on
											
												//get this roles email address and phone numbers
												//ensure the account is active as well...
												$sql_rolecontacts="SELECT usremail,usrphone FROM usrac WHERE usrrole_idusrrole=".$fet_notify['usrrole_idusrrole']." AND acstatus=1 LIMIT 1";
												$res_rolecontacts=mysql_query($sql_rolecontacts);
												$fet_rolecontacts=mysql_fetch_array($res_rolecontacts);
												$num_rolecontacts=mysql_num_rows($res_rolecontacts);
									
											if ( ($fet_notify['notify_email']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usremail'])>6) && (strlen($fet_notify['tktmsg_email'])>2) )//email set on
												{
												$sql_email="INSERT INTO tktmsgslog_emails(tktmsgs_idtktmsgs,emailto,emailsubject,emailbody,createdon,senton) 
												VALUES ('".$fet_notify['idtktmsgs']."','".$fet_rolecontacts['usremail']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$fet_notify['tktmsg_email']." - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
											
												mysql_query($sql_email);
												}
										
											if ( ($fet_notify['notify_sms']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usrphone'])==13) )
												{
												$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext)
												VALUES ('".$fet_rolecontacts['usrphone']."','Auto Notification - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']." received')";
						
												mysql_query($sql_sms);
												}
											
											} while ($fet_notify=mysql_fetch_array($res_notify));								
									
										} //close - if there is a notification setting
										
										
										/////////////////////////////check and insert a new subscriber
										if ($fet_task['usrrole_idusrrole']==2) //if this is the first ticket from the customer [customer is reserved as userrole 2], then do this...
											{
											//check if a subscriber with the same credentials matches
											$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
											$res_subis=mysql_query($sql_subis);
											$num_subis=mysql_num_rows($res_subis);
											
											//if not, add the new credentials
											if ($num_subis==0)
												{
												$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)
												VALUES ('".$_SESSION['wtaskid']."','".$fet_task['tktin_idtktin']."','".$fet_confirmloc['idloctowns']."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
												mysql_query($sql_subnew);
												}
											}
											
								
								if ( (isset($batch_no)) && ($batch_no>0) && ($fet_ticket['wftasks_batch_idwftasks_batch']!=$batch_no) )
										{
										//first, lets check if this ticket already belonged to another batch before removing it
										$res_tktin=mysql_query("SELECT idtktinPK,wftasks_batch_idwftasks_batch,tktcategory_idtktcategory FROM tktin WHERE idtktinPK=".$fet_ticket['idtktinPK']."  ");
										$fet_tktin=mysql_fetch_array($res_tktin);
											
										if ($fet_tktin['wftasks_batch_idwftasks_batch']>0)
											{
											//update the tkt as well
											$sql_batchtkt="UPDATE tktin SET 
											wftasks_batch_idwftasks_batch='0',
											batch_number='0',
											voucher_number='0'
											WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
											
											//update the countbatch
											$sql_updatecount_old="UPDATE wftasks_batch SET countbatch=(countbatch-1) WHERE idwftasks_batch=".$fet_tktin['wftasks_batch_idwftasks_batch']."";
														
											//log audit 1
											$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
											VALUES ( 'MOVE', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '".$fet_tktin['wftasks_batch_idwftasks_batch']."', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
														
											} else {
											
											$sql_batchtkt="SELECT idtktinPK from tktin LIMIT 1";
											$sql_updatecount_old="SELECT idtktinPK from tktin LIMIT 1";
											
											//log audit 1
											$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
											VALUES ( 'NEW', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '0', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
											
											}
											
									$res_batchtkt=mysql_query($sql_batchtkt);
									$res_updatecount_old=mysql_query($sql_updatecount_old);
									$res_audit1=mysql_query($sql_audit1);
							
									//check the last batch_no
									$res_batchmeta=mysql_query("SELECT usrteamzone_idusrteamzone,wftasks_batchtype_idwftasks_batchtype FROM wftasks_batch WHERE idwftasks_batch=".$batch_no."");
									$fet_batchmeta=mysql_fetch_array($res_batchmeta);
									//changed to get the last max id given for this batch
						//			$sql_lastbatchno="SELECT max(voucher_number) as countbatch FROM tktin WHERE wftasks_batch_idwftasks_batch=".$batch_no."";
									$sql_lastbatchno="SELECT max(tktin.voucher_number) as countbatch,wftasks_batch.wftasks_batchtype_idwftasks_batchtype,wftasks_batch.batch_year FROM tktin
									INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
									WHERE wftasks_batch.wftasks_batchtype_idwftasks_batchtype=".$fet_batchmeta['wftasks_batchtype_idwftasks_batchtype']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$fet_batchmeta['usrteamzone_idusrteamzone']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  ";//AND YEAR(createdon)='".$this_year."'
									$res_lastbatchno=mysql_query($sql_lastbatchno);
									$fet_lastbatchno=mysql_fetch_array($res_lastbatchno);
									
									//quick validation to avoid crossing over to another year in an older batch
									if ( ($fet_lastbatchno['countbatch']!='') && ($fet_lastbatchno['batch_year']!=$this_year) )
										{
										$error_batchoutdated="<div style=\"color:#ff0000\">You can't assign ".$fet_lastbatchno['batch_year']." in ".$this_year."  </div>";
										//exit;
										}
									
									//create the new batch_no
									$new_batchno=($fet_lastbatchno['countbatch']+1);							
									
									//new update the batch_no meta table
									$sql_updatecount="UPDATE wftasks_batch SET countbatch=(countbatch+1) WHERE idwftasks_batch=".$batch_no."";
									$res_updatecount=mysql_query($sql_updatecount);
									
									//get the tktid to update the tktin as well
									$sql_tktin=mysql_query("SELECT tktin_idtktin FROM wftasks WHERE idwftasks=".$_SESSION['wtaskid']." ");
									$fet_tktin=mysql_fetch_array($sql_tktin);
									
									//update the tkt as well
									$sql_batchtktnew="UPDATE tktin SET 
									wftasks_batch_idwftasks_batch='".$batch_no."',
									batch_number='".$new_batchno."',
									voucher_number='".$new_batchno."'
									WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
									$res_batchtktnew=mysql_query($sql_batchtktnew);
									
									} else { //else if no batch now, then create some dummy queries to run the transction commit succssfully
									////////
									$res_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_lastbatchno=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$sql_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtktnew=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount_old=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_audit1=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchmeta=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									
									/////
									}//batch now
								
									
								if ( ($query_1) && ($query_2) && ($query_3) && (!isset($error_formdata)) && ($res_tktin) && (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) && (!isset($upload_error_4)) && ($res_batchtkt) && ($res_lastbatchno) && ($res_updatecount) && ($sql_tktin) && ($res_batchtktnew) && ($res_batchtkt) && ($res_updatecount_old) && ($res_audit1) && ($res_batchmeta) && (!isset($error_batchoutdated)) )
									{
									mysql_query("COMMIT");	
									///////////////////////////// close check and insert new subscriber ////////////////////////////
									//redirect to the correct page
									?>
									<script language="javascript">
									window.location='go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>';
									</script>
									<?php
									exit;
									} else {
									mysql_query("ROLLBACK");
									?>
                                    <script language="javascript">
									 alert ('Sorry! Please Try Again!');
									</script>
                                    <?php
									if (isset($query_1)) { mysql_free_result($query_1);  }
									if (isset($query_2)) { mysql_free_result($query_2);  }
									if (isset($query_3)) { mysql_free_result($query_3); }	
									}
						} //if the no error 1_1
					
					} //Select Task Action 1
					
					
				///////////////  ACTION 2  ///////////////////////////////////////////////////////////////////////////////////////////////////
				if ($tktaction==2) 
					{ //Select Task Action 2 ie: pass it on

					//validate
					if (strlen($tkttskmsg2) < 1)
						{
						$error_2_1="<div class=\"msg_warning_small\">".$msg_warn_msgmis."</div>";
						}
					if ( (isset($tktasito2)) && ($tktasito2<1) && (strlen($tktasito2) < 1) ) //to cater for exception
						{
						$error_2_2="<div class=\"msg_warning_small\">".$msg_warn_assign."</div>";
						}
					if  ( (isset($tktasito2)) && ($tktasito2=="other_exception") && (strlen($_POST['recepient_alt']) < 3) )
						{
						$error_2_3="<div class=\"msg_warning_small\">Please indicate the person to send the task to</div>";
						}
					if ( (isset($tktasito2)) && ($tktasito2=="other_exception")) //if it's an exception
						{
						//check if the selected account_usr account is valid
									$str_prex=mysql_real_escape_string(trim($_POST['recepient_alt']));
									$str_ex=explode(',',$str_prex);
									
									//take ther ole
									$str_role=trim($str_ex[0]); //the first variable after comma
									$str_last=trim($str_ex[1]);
									
									$str_region=substr($str_last,-3,2);
									
									//get the id and user from the userac table
									$sql_userid="SELECT idusrac,usrrole_idusrrole FROM usrac 
									INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole
									INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
									WHERE usrrolename='".$str_role."' 
									AND usrteamzone.region_pref='".$str_region."'
									AND acstatus=1 
									LIMIT 1";
									$res_userid=mysql_query($sql_userid);
									$fet_userid=mysql_fetch_array($res_userid);
									$num_userid=mysql_num_rows($res_userid);
									//echo "line 1039: ".$sql_userid."<br>";
									//exit;
									if ($num_userid>0)
										{
										//echo $sql_userid;
										$recepient_roleid=$fet_userid['usrrole_idusrrole'];
										$recepient_usrid=$fet_userid['idusrac'];
										$recepient_groupid=0;
										} else {
										$error_2_4="<div class=\"msg_warning_small\">The Name or Role you entered does not exist or is not active</div>";
										}
	
						}
						
					//check if the selection is a Group or an Individual Role
					if (isset($tktasito2))
						{
						$tktasito2_prefix=substr($tktasito2,0,3); //if a group, the result should be GRP						
						}
					
											
							if ( (!isset($error_2_1)) && (!isset($error_2_2)) && (!isset($error_2_3)) && (!isset($error_2_4)) )//if the no error 
								{
								
								mysql_query("BEGIN");
								
								//update this task 
								$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='2',wftskstatusglobal_idwftskstatusglobal='2',timeactiontaken='".$timenowis."',actedon_idusrrole=".$_SESSION['MVGitHub_iduserrole'].", actedon_idusrac='".$_SESSION['MVGitHub_idacname']."' WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
								$query_1=mysql_query($sql_update_task);
								
								//create an update message on the record
								$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
								VALUES ('".$fet_idtask['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','2','2','".$_SESSION['wtaskid']."','".$tkttskmsg2."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
								$query_2=mysql_query($sql_update_msg);
								
								//get task details
								//if tskflow > 0 then ok 
								if ($fet_istskflow['wftskflow_idwftskflow'] > 0) //if there is a taskflow, then proceed
									{
									$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftskflow.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftskflow.listorder,wftskflow.idwftskflow,wftskflow.wftsktat,wfproc.wfproctat FROM wftasks 
									INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow 
									INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
									WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
									} else {
									/*$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,link_tskcategory_wfproc.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wfproc.wfproctat FROM wftasks 
									INNER JOIN wftasks_exceptions ON wftasks.wftasks_idwftasks=wftasks_exceptions.wftasks_idwftasks								
									INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
									INNER JOIN link_tskcategory_wfproc ON tktin.tktcategory_idtktcategory=tktin.tktcategory_idtktcategory
                                    INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc
									WHERE idwftasks=".$_SESSION['wtaskid']." AND tktin.idtktinPK=".$_SESSION['tktin_idtktin']."
									AND link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";*/
									$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,link_tskcategory_wfproc.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wfproc.wfproctat FROM wftasks 
									INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
									INNER JOIN link_tskcategory_wfproc ON tktin.tktcategory_idtktcategory=tktin.tktcategory_idtktcategory
                                    INNER JOIN wfproc ON link_tskcategory_wfproc.wfproc_idwfproc=wfproc.idwfproc
									WHERE idwftasks=".$_SESSION['wtaskid']." AND tktin.idtktinPK=".$_SESSION['tktin_idtktin']."
									AND link_tskcategory_wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";									
									}
								
								$res_task_details = mysql_query($sql_task_details);
								$fet_task_details = mysql_fetch_array($res_task_details);
								//echo "<br><br><br>".$sql_task_details."<br>";
								//exit;
								//}
								
								//the next task flow is depended on the value in a hidden field
								$wftaskflow_id_txtbox=mysql_real_escape_string(trim($_POST['wftaskflow_id']));
								
										
								////////////// START CALCULATION OF TIME /////////
								if ( ($tktasito2!="other_exception") || ($fet_task['wftskflow_idwftskflow']!=0) ) //if NOT other exception OR sender had idtksflow, then follow this steps
									{ 
										//lock the query below to the above variable
										//find the next tasks
										$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays
										FROM wftskflow INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
										WHERE wfproc_idwfproc=".$fet_task_details['wfproc_idwfproc']." AND wftskflow.wfsymbol_idwfsymbol=2 AND wftskflow.idwftskflow=".$wftaskflow_id_txtbox." ORDER BY listorder ASC LIMIT 1";
										//echo $sql_nextwf."<br>";
										//exit;
										$res_nextwf=mysql_query($sql_nextwf);
										$num_nextwf=mysql_num_rows($res_nextwf);
										$fet_nextwf=mysql_fetch_array($res_nextwf);
										
	
												//1. construct deadlines and start times against TATs and time task was received
												$ticket_wday = date("w",strtotime($timenowis)); //ticket day of the week
												$ticket_hour = date("H:i",strtotime($timenowis)); //ticket hour	
												$ticket_actualtimein = $timenowis;
												//$ticket_timein = $timenowis;
												
												$task_starttime_raw = $ticket_actualtimein;//exactly the time the task came in on the record
												$task_deadline_raw = date("Y-m-d H:i:s",strtotime($ticket_actualtimein)+$fet_nextwf['wftsktat']); //this is for the specific task
												$task_overalldeadline_raw = date("Y-m-d H:i:s",strtotime($ticket_actualtimein)+$fet_task_details['wfproctat']); //this is the overall time set for the whole process should take
												
													
												//CONSTRUCT STARTING TIME
												//a) Did this task fall on a Weekday, Working hours ?
												if (($ticket_wday>0) && ($ticket_wday<6)) //Monday - Friday
													{
													//check if it was a weekday
													$sql_workinghrs="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_task_details['idwftskflow']." AND 	wfworkingdays_idwfworkingdays=1";
													$res_workinghrs=mysql_query($sql_workinghrs);
													$num_workinghrs=mysql_num_rows($res_workinghrs);
													$fet_workinghrs=mysql_fetch_array($res_workinghrs);
													
													//echo $sql_workinghrs;
													//check time in
													if ( ( ($ticket_hour>=$fet_workinghrs['time_earliest']) && ($ticket_hour<=$fet_workinghrs['time_latest']) ) || ($ticket_hour<$fet_workinghrs['time_earliest']) )
														{
														
														$push_weekday = 0;//then do not add a day to the start time
														
														} else {
														
														$push_weekday = 1;
														
														}
														
													} else {
													
													$push_weekday = 0;
													
													}//close Monday - Friday
													
													
												if ($ticket_wday==6) // Saturday
													{
													//check if the task applies for Saturdays
					
													$sql_saturdays="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_task_details['idwftskflow']." AND wfworkingdays_idwfworkingdays=2 LIMIT 1";
													$res_saturdays=mysql_query($sql_saturdays);
													$num_saturdays=mysql_num_rows($res_saturdays);
													$fet_saturdays=mysql_fetch_array($res_saturdays);
														
													
					
													if ( ($fet_saturdays['time_earliest']=='00:00:00') && ($fet_saturdays['time_latest']=='00:00:00') )
														{
														$push_saturday = 1; // push a day
														
														} else { //then if not set to 00:00:00 as per the query above, compare the timein
				
															//check the time the ticket came in
															if ( ( ($ticket_hour>=$fet_saturdays['time_earliest']) && ($ticket_hour<=$fet_saturdays['time_latest']) ) || ($ticket_hour<$fet_saturdays['time_earliest']) )
																{
																$push_saturday =0;//then do not add a day to the start time
																} else {
																$push_saturday =1;//then do not add a day to the start time
																}
																	
														} //close if not set to 00:00:00
													
													} else {
															
													$push_saturday=0;
																						
													} //close if saturday
														
													
												if ($ticket_wday==0) // Sunday
														{
														//check if the task applies for sundays
														$sql_sundays="SELECT time_earliest,time_latest,wfworkingdays_idwfworkingdays FROM wfworkinghrs WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND wfworkingdays_idwfworkingdays=3 LIMIT 1";
														
														$res_sundays=mysql_query($sql_sundays);
														$num_sundays=mysql_num_rows($res_sundays);
														$fet_sundays=mysql_fetch_array($res_sundays);
														
														if (($fet_sundays['time_earliest']=='00:00:00') && ($fet_sundays['time_latest']=='00:00:00')) 
															{
															$push_sunday = 1; // push a day
															} else { //then if not set to 00:00:00 as per the query above, compare the timein
															//check the time the ticket came in
															if ( ( ($ticket_hour>=$fet_sundays['time_earliest']) && ($ticket_hour<=$fet_sundays['time_latest']) ) || ($ticket_hour<$fet_sundays['time_earliest']) )
																{
																
																$push_sunday =0;//then do not add a day to the start time
																
																} else {
																
																$push_sunday =1;//then do not add a day to the start time
																
																} //close if not within the pre-set sunday time frames
																																			
															} //close if not set to 00:00:00
														
														} else {
														
														$push_sunday=0;
														
														} //close if a Sunday
												
												
												//Adjust the Start and Stop Times
												$total_pushes = ($push_weekday + $push_saturday + $push_sunday); //number of adjustments across
												$total_pushes_sec = ($total_pushes * 86400); //
												
												$task_starttime_refined = date("Y-m-d H:i:s",strtotime($task_starttime_raw) + $total_pushes_sec);
												$task_deadline_refined = date("Y-m-d H:i:s",strtotime($task_deadline_raw) + $total_pushes_sec);
												$task_overalldeadline_refined = date("Y-m-d H:i:s",strtotime($task_overalldeadline_raw) + $total_pushes_sec);
												
												
												//Are public holidays Excluded
												if ($fet_nextwf['expubholidays']==1) //if set, then find out how many public holidays will count between the new start and end dates
													{
													
													$sql_holidays = "SELECT idwftskholiday FROM wftskholiday WHERE wftskholidaydate>='".$task_starttime_refined."' AND  wftskholidaydate<='".$task_deadline_refined."' ";
													$res_holidays = mysql_query($sql_holidays);
													$num_holidays = mysql_num_rows($res_holidays);
													
													
													
													$push_holidays=($num_holidays * 86400);
													
													} else { //else not set, then no holiday found
													
													$push_holidays=0;
													
													}
													
												//start and end times almost final
												$task_starttime_prefinal = date("Y-m-d H:i:s",strtotime($task_starttime_refined) + $push_holidays);
												$task_deadline_prefinal = date("Y-m-d H:i:s",strtotime($task_deadline_refined) + $push_holidays);
												$task_overalldeadline_prefinal = date("Y-m-d H:i:s",strtotime($task_overalldeadline_refined) + $push_holidays);
											
													
												//finally, within the span of the refined Start and End days, find how many Saturdays and Sundays will be exempted if excempt

												if ($push_saturday==1) //if Saturday was excempted
													{
													
													$count_saturdays = 0;
													
													$start_ts = strtotime($task_starttime_prefinal); // start time stamp
													$end_ts = strtotime($task_deadline_prefinal); // end time stamp
					
													
													while ($start_ts<=$end_ts) 
														{
															$day = date('w', $start_ts);
																if ($day == 6) 
																	{ // this is a saturday
																	//echo date('d', $working_ts)."<br>";
																	$count_saturdays++;
																	}
															$start_ts = $start_ts + $day_sec;
														}
													
													//number of exempt saturdays


													$ex_saturdays = $count_saturdays;
													
													} else {
													
													$ex_saturdays = 0;
													
													} //if Saturday was excempted
												
												
												
												if ($push_sunday==1) //if sunday was excempted
													{
													
													$count_sundays = 0;
													
													$start_ts = strtotime($task_starttime_prefinal); // start time stamp
													$end_ts = strtotime($task_deadline_prefinal); // end time stamp
					
													
													while ($start_ts<=$end_ts) 
														{
															$day = date('w', $start_ts);
																if ($day == 0) 
																	{ // this is a sunday
																	//echo date('d', $working_ts)."<br>";
																	$count_sundays++;
																	}
															$start_ts = $start_ts + $day_sec;
														}
													
													//number of exempt sundays
													$ex_sundays = $count_sundays;
													
													} else {
													
													$ex_sundays = 0;
													
													} //if sunday was excempted
												
												
												
												//FINAL START AND END DAYS FOR THE TASKS THEN AS FOLLOWS
												$push_ex_saturdays = ($ex_saturdays * 86400);
												$push_ex_sundays = ($ex_sundays * 86400);
																			
												$next_workflow_id=$fet_nextwf['idwftskflow'];
												
												
											} //close if NO EXCEPTION
											
											//if this is an actor exception, then there is no Next Workflow 
											if ( ($tktasito2=="other_exception") || ($fet_task['wftskflow_idwftskflow']==0) ) //if NOT other exception, then follow this steps
												{ 
												//so just calculate manually
												$task_starttime_final = $timenowis; //time now
												$task_deadline_final = date("Y-m-d H:i:s",strtotime($timenowis) + (2*86400));; //just add 48 hours to the current
												$task_overalldeadline_final = $fet_task['timeoveralldeadline']; //as original time deadline for this task
												
												} else { //if not exception
												
												$task_starttime_final = $task_starttime_prefinal; //the start time remains the same... only adjust the deadline to discount off the extra weekend days
												$task_deadline_final = date("Y-m-d H:i:s",strtotime($task_deadline_prefinal) + ($push_ex_saturdays + $push_ex_sundays));
												$task_overalldeadline_final = date("Y-m-d H:i:s",strtotime($task_overalldeadline_prefinal) + ($push_ex_saturdays + $push_ex_sundays));
											
												}
												
								///////////// END CALCULATION OF TIME ////////
													
								///////////// GET THE RECEPIENT DETAILS DEPENDING ON CONDITIONS FULFILLED								
								//get user account id
								if ( ($tktasito2_prefix!="GRP") && ($tktasito2!="other_exception") ) //It's not a group and Not an exception, then great
									{
									$sql_userid="SELECT idusrac FROM usrac WHERE usrrole_idusrrole=".$tktasito2." LIMIT 1";
									$res_userid=mysql_query($sql_userid);
									$fet_userid=mysql_fetch_array($res_userid);
								//vars below
									$recepient_roleid=$tktasito2;
									$recepient_usrid=$fet_userid['idusrac'];
									$recepient_groupid=0;
									
									}
									
								if ($tktasito2_prefix=="GRP")  //It's  a group, then
									{
									
									$tktasito2_suffix=substr($tktasito2,3); //if a group, the result should be GRP
									
									$sql_userid="SELECT idwfactorsgroupname FROM wfactorsgroupname WHERE idwfactorsgroupname=".$tktasito2_suffix." LIMIT 1";
									$res_userid=mysql_query($sql_userid);
									$fet_userid=mysql_fetch_array($res_userid);
								//vars below
									$recepient_roleid=0;
									$recepient_usrid=0;
									$recepient_groupid=$tktasito2_suffix; //groups id is store on the same select menu as the rolws
								//	echo $sql_userid;
									}
								
								
									
								///////////// END OF RECEPIENT DETAILS //////////////////
								
								//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
								
								//insert new task for the recepeint
								
								if ($fet_task_details['wftaskstrac_idwftaskstrac'] > 0) //ensure the track >0 - just a caution
									{
									$sql_new_task="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon,createdby,wfactorsgroup_idwfactorsgroup,wftasks_batch_idwftasks_batch,batch_number) 
									VALUES ('".$fet_task_details['wftaskstrac_idwftaskstrac']."','".$recepient_roleid."','".$fet_task_details['idwftasks']."','".$wftaskflow_id_txtbox."','".$fet_task_details['tktin_idtktin']."','".$recepient_usrid."','0','1','".$fet_task_details['tasksubject']."','".$tkttskmsg2."','".$timenowis."','".$fet_task_details['timeoveralldeadline']."','".$task_starttime_final."','".$task_deadline_final."','0000-00-00 00:00:00','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."','".$recepient_groupid."','".$batch_no."','0')";
									$query_3=mysql_query($sql_new_task);
									}
								//echo "1-".$sql_new_task."<br>";
								//exit; 
								
							//check if there is a form data and if so, go ahead and process this transaction with inserts or updates
							if ( (isset($_POST['formdata_available'])) && ($_POST['formdata_available']==1) )
								{
								//echo "processed <br>";
								//check the db for this field by reusing the sql statement above
/*								$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets FROM wfprocassetsaccess 
								INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
								WHERE wftskflow_idwftskflow=".$fet_task['wftskflow_idwftskflow']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassets.ordering ASC";
*/								
								$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets,wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms
								FROM wfprocassetsaccess
								INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
								INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
								INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
								WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=".$fet_ticket['tktcategory_idtktcategory']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
							
								$res_val=mysql_query($sql_val);
								$num_val=mysql_num_rows($res_val);
								$fet_val=mysql_fetch_array($res_val);
//			echo $sql_val;
								if ($num_val > 0) //if there are some values, then
									{
									do {
									//master-checklist if  | it is required | there is a value | the data type to determine the field |  whether an update or insert
									
									//validate required
								//	echo "validation ";
								//	echo $_POST['required_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
								//	echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].''];
										if (
										(isset($_POST['required_'.$fet_val['idwfprocassetsaccess'].''])) 
										&& ($_POST['required_'.$fet_val['idwfprocassetsaccess'].'']==1) 
										&&  ($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']=="")   
										)
											{
											//echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
											$error_formdata=1;
											echo "<div class=\"msg_warning_small\">Form : ".$fet_val['assetname']." is required</div>";
											
											}
									
									//if no error on the dataform, then process
									if (!isset($error_formdata))
										{	
			
										if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="INSERT")
											{
											//check the form item type first
											$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
											
												
												if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10) ) //if textbox OR yes/no OR datepicker OR datetimepicker
													{
													$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
													
													//then process as below
													$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
													wfprocassetschoice_idwfprocassetschoice,
													wfprocassets_idwfprocassets,
													wftasks_idwftasks,
													value_choice,
													value_path,
													wftaskstrac_idwftaskstrac,
													tktin_idtktin,
													createdby,
													createdon)
													VALUES ('".$fet_val['idwfprocassetsaccess']."',
													'0',
													'".$fet_val['idwfprocassets']."',
													'".$_SESSION['wtaskid']."',
													'".$fvalue."',
													'',
													'".$_SESSION['wftaskstrac']."',
													'".$_SESSION['tktin_idtktin']."',
													'".$_SESSION['MVGitHub_idacname']."',
													'".$timenowis."'
													)";
													
													mysql_query($sql_insert);
													}
													
												if ($ttype==2)//if menulist
													{
													$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
													
													$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
													wfprocassetschoice_idwfprocassetschoice,
													wfprocassets_idwfprocassets,
													wftasks_idwftasks,
													value_choice,
													value_path,
													wftaskstrac_idwftaskstrac,
													tktin_idtktin,
													createdby,
													createdon)
													VALUES ('".$fet_val['idwfprocassetsaccess']."',
													'".$fvalue."',
													'".$fet_val['idwfprocassets']."',
													'".$_SESSION['wtaskid']."',
													'',
													'',
													'".$_SESSION['wftaskstrac']."',
													'".$_SESSION['tktin_idtktin']."',
													'".$_SESSION['MVGitHub_idacname']."',
													'".$timenowis."'
													)";
													
													mysql_query($sql_insert);
													//echo $sql_insert."<br><br>";
													
													}
													
										if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											//just keep the file name only
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
											//check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
												
												$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
												
												//validation before uploading										 
												//check if file exists
												if (file_exists($target_file)) 
													{
													$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
													}
												
												if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
													{
													$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
													}
												
												if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
													&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
													&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
													&& $imageFileType != "ppt" && $imageFileType != "pptx"  && $imageFileType != "csv"    ) {
														
													$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
													}
												//echo $upload_error_1.$upload_error_2.$upload_error_3;	
												//echo "Size -->".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
												if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
													{
													 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
														{
														$upload_success=1;
														//log the record into the Database
														$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
														wfprocassetschoice_idwfprocassetschoice,
														wfprocassets_idwfprocassets,
														wftasks_idwftasks,
														value_choice,
														value_path,
														wftaskstrac_idwftaskstrac,
														tktin_idtktin,
														createdby,
														createdon)
														VALUES ('".$fet_val['idwfprocassetsaccess']."',
														'0',
														'".$fet_val['idwfprocassets']."',
														'".$_SESSION['wtaskid']."',
														'',
														'".$file_name_only."',
														'".$_SESSION['wftaskstrac']."',
														'".$_SESSION['tktin_idtktin']."',
														'".$_SESSION['MVGitHub_idacname']."',
														'".$timenowis."'
														)";
														
														mysql_query($sql_insert);
														
														//create the audit log
														$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, wfprocassets_idwfprocassets) 
														VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."','".$fet_val['idwfprocassets']."')";
														mysql_query($sql_audit);
														
														} else {
															$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
														}
													} //no error
												} //where strlen > 4
											} //type==3
											
											} //inserts end
											
											
										if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="UPDATE")
									{
									$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
									$itempk=mysql_real_escape_string(trim($_POST['itempk_'.$fet_val['idwfprocassetsaccess'].'']));
									
									//value captured - //this hack for checkbox
									if ((($ttype==3)||($ttype==4)) && (!isset($_POST['item_'.$fet_val['idwfprocassetsaccess'].''])) )
										{
										$fvalue=0;
										} else {
										$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
										}
									
									//only if there are records
									if (
										( ($fvalue > 0) || (strlen($fvalue) > 0) && ($ttype!=4) ) 
										|| 
										( ($ttype==4) && (($fvalue=='') || ($fvalue==0) || ($fvalue!=0)) ) 
										) 
									/*if ( ($fvalue!='') && (strlen($fvalue) > 0) )*/
										{
											//check the form item type first
											if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10)   ) //if textbox OR yes/no OR datepicker OR datetimepicker
													{
													///audit log
													$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
													SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '".$fvalue."', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
													FROM wfassetsdata
													WHERE idwfassetsdata=".$itempk." AND value_choice!='".$fvalue."' ";
													//echo $sql_auditlog_form."<br>";
													mysql_query($sql_auditlog_form);
													
													//then process as below
													$sql_update="UPDATE wfassetsdata SET 
													value_choice='".$fvalue."',
													wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
													tktin_idtktin='".$_SESSION['tktin_idtktin']."',
													modifiedby='".$_SESSION['MVGitHub_idacname']."',
													modifiedon='".$timenowis."'
													WHERE idwfassetsdata=".$itempk." LIMIT 1";
													
													mysql_query($sql_update);
													//echo $sql_update."<br><br>";
													}
											
											if ($ttype==2)//if menulist
													{
													//enter the audit trail only if there is a change
													$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
													SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'".$fvalue."', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
													FROM wfassetsdata
													WHERE idwfassetsdata=".$itempk." AND wfprocassetschoice_idwfprocassetschoice!='".$fvalue."' ";
													//echo $sql_auditlog_form."<br>";
													mysql_query($sql_auditlog_form);
													
													
													$sql_update="UPDATE wfassetsdata SET 
													wfprocassetschoice_idwfprocassetschoice='".$fvalue."',
													wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
													tktin_idtktin='".$_SESSION['tktin_idtktin']."',
													modifiedby='".$_SESSION['MVGitHub_idacname']."',
													modifiedon='".$timenowis."'
													WHERE idwfassetsdata=".$itempk." LIMIT 1";
													//echo $sql_update."<br><br>";
													mysql_query($sql_update);
													}
													
											if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];

											////check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
											
													$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
													
													//validation before uploading											 
													//check if file exists
													if (file_exists($target_file)) 
														{
														$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
														}
													
													if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
														{
														$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
														}
													
													if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
														&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
														&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
														&& $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "csv"    ) {
															
														$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
														}

													if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
														{
														//echo "soo farr soo good";
														 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
															{
															$upload_success=1;
															
															$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
															SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'".$file_name_only."', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
															FROM wfassetsdata
															WHERE idwfassetsdata=".$itempk." AND value_path!='".$file_name_only."' ";
															//echo $sql_auditlog_form."<br>";
															mysql_query($sql_auditlog_form);
															
															//log the record into the Database
															$sql_update="UPDATE wfassetsdata SET 
															value_path='".$file_name_only."',
															modifiedby='".$_SESSION['MVGitHub_idacname']."',
															modifiedon='".$timenowis."'
															WHERE idwfassetsdata=".$itempk." LIMIT 1";

															mysql_query($sql_update);
															
															//create the audit log
															$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip,wfprocassets_idwfprocassets) 
															VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."',".$itempk.")";
															mysql_query($sql_audit);
																											
															} else {
																$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
															}
														} //no error
													} // if fvalue strlen>4
											
												} //type==3			
											}	
										} //end update
											
										}
										
									} while ($fet_val=mysql_fetch_array($res_val));
									
									} //if record is > 0
								
								} //close form data checker
														
								//Feedback SMS to send customer/sender a message
								if ( (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) )
									{
									$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext)  
									VALUES ('".$tktsenderphone."','".$_SESSION['MVGitHub_userteamshortname'].",Ticket ".$tktno." ".$tktsms."')";
									mysql_query($sql_smsout);
									}
							
									
		
									//notify if anyone is to be notified
									$sql_notify="SELECT idwfnotification,wfnotification.tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,idtktmsgs,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification 
									INNER JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
									WHERE wftskflow_idwftskflow=".$_SESSION['thistskflow']." ORDER BY idwfnotification ASC";
									$res_notify=mysql_query($sql_notify);
									$num_notify=mysql_num_rows($res_notify);
									$fet_notify=mysql_fetch_array($res_notify);
									//echo $sql_notify;
									if ($num_notify > 0 ) // if there is a notification setting
										{
										do {			
										//check for each of the settings 
											if ( ($fet_notify['notify_system']==1) && (strlen($fet_notify['tktmsg_dashboard'])>2) ) //system dashboard set on
												{
												$sql_dash="INSERT INTO tktmsglogs_dashboard (tktmsgs_idtktmsgs,msgto_roleid,msgto_subject,msgto_body,createdon,readon)
												VALUES ('".$fet_notify['idtktmsgs']."','".$fet_notify['usrrole_idusrrole']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname'].",'".$fet_notify['tktmsg_dashboard']." - ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
												mysql_query($sql_dash);									
												}// system dashboard set on
														
												//get this roles email address and phone numbers
												//ensure the account is active as well...
												$sql_rolecontacts="SELECT usremail,usrphone FROM usrac WHERE usrrole_idusrrole=".$fet_notify['usrrole_idusrrole']." AND acstatus=1 LIMIT 1";
												$res_rolecontacts=mysql_query($sql_rolecontacts);

												$fet_rolecontacts=mysql_fetch_array($res_rolecontacts);
												$num_rolecontacts=mysql_num_rows($res_rolecontacts);
												
												if ( ($fet_notify['notify_email']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usremail'])>6) && (strlen($fet_notify['tktmsg_email'])>2) )//email set on
													{
													$sql_email="INSERT INTO tktmsgslog_emails(tktmsgs_idtktmsgs,emailto,emailsubject,emailbody,createdon,senton) 
													VALUES ('".$fet_notify['idtktmsgs']."','".$fet_rolecontacts['usremail']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$fet_notify['tktmsg_email']." - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
														
													mysql_query($sql_email);
													}
													
													if ( ($fet_notify['notify_sms']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usrphone'])==13) )
													{
													$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext) 
													VALUES ('".$fet_rolecontacts['usrphone']."','Auto Notification - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']." received')";
							
													mysql_query($sql_sms);
													}
														
											} while ($fet_notify=mysql_fetch_array($res_notify));								
												
										} //close - if there is a notification setting
							
							/////////////////////////////check and insert a new subscriber
							if ($fet_task['usrrole_idusrrole']==2) //if this is the first ticket from the customer, then do this...
								{
								//check if a subscriber with the same credentials matches
								$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
								$res_subis=mysql_query($sql_subis);
								$num_subis=mysql_num_rows($res_subis);
								
								//if not, add the new credentials
								if ($num_subis==0)
									{
									$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)
									VALUES ('".$_SESSION['wtaskid']."','".$fet_task['tktin_idtktin']."','".$fet_confirmloc['idloctowns']."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
									mysql_query($sql_subnew);
									}
								}
							///////////////////////////// close check and insert new subscriber ////////////////////////////
							
							//if exception, then follow the following path...remember to add wftasks_exceptions table transaction
							if ($tktasito2=="other_exception") //if it's an exception
								{
								$sql_exceptionlog="INSERT INTO wftasks_exceptions (wftasks_idwftasks,wftskflow_idwftskflow,idusrrole_from,idusrac_from,idusrrole_to,idusrac_to,wfprocassetsaccess_idwfprocassetsaccess,createdon,createdby) 
								VALUES ('".$fet_task['idwftasks']."','".$fet_task['wftskflow_idwftskflow']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$recepient_roleid."','".$recepient_usrid."','0','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
								mysql_query($sql_exceptionlog);
								}
							
							//echo $sql_task_details."<br>".$sql_update_task."<br>".$sql_update_msg."<br>".$sql_new_task;
							//exit;
							//if no error, then redirect to the correct page
//							echo "<br><Br><br>batch=>".$batch_no;
							//if there was a batch, then you need to include that in the process
								if ( (isset($batch_no)) && ($batch_no>0) && ($fet_ticket['wftasks_batch_idwftasks_batch']!=$batch_no) )
									 {
									//first, lets check if this ticket already belonged to another batch before removing it
									$res_tktin=mysql_query("SELECT idtktinPK,wftasks_batch_idwftasks_batch,tktcategory_idtktcategory FROM tktin WHERE idtktinPK=".$fet_ticket['idtktinPK']."  ");
									$fet_tktin=mysql_fetch_array($res_tktin);
											
									if ($fet_tktin['wftasks_batch_idwftasks_batch']>0)
										{
										//echo "huu";
										//update the tkt as well
										$sql_batchtkt="UPDATE tktin SET 
										wftasks_batch_idwftasks_batch='0',
										batch_number='0',
										voucher_number='0'
										WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
										
										//update the countbatch
										$sql_updatecount_old="UPDATE wftasks_batch SET countbatch=(countbatch-1) WHERE idwftasks_batch=".$fet_tktin['wftasks_batch_idwftasks_batch']."";
													
										//log audit 1
										$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
										VALUES ( 'MOVE', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '".$fet_tktin['wftasks_batch_idwftasks_batch']."', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
													
										} else {
										
										$sql_batchtkt="SELECT idtktinPK from tktin LIMIT 1";
										$sql_updatecount_old="SELECT idtktinPK from tktin LIMIT 1";
										
										//log audit 1
										$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
										VALUES ( 'NEW', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '0', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
										
										} //if found
											
									$res_batchtkt=mysql_query($sql_batchtkt);
									$res_updatecount_old=mysql_query($sql_updatecount_old);
									$res_audit1=mysql_query($sql_audit1);
													
									//check the last batch_no
									$res_batchmeta=mysql_query("SELECT usrteamzone_idusrteamzone,wftasks_batchtype_idwftasks_batchtype FROM wftasks_batch WHERE idwftasks_batch=".$batch_no."");
									$fet_batchmeta=mysql_fetch_array($res_batchmeta);
									//changed to get the last max id given for this batch
						//			$sql_lastbatchno="SELECT max(voucher_number) as countbatch FROM tktin WHERE wftasks_batch_idwftasks_batch=".$batch_no."";
									$sql_lastbatchno="SELECT max(tktin.voucher_number) as countbatch,wftasks_batch.wftasks_batchtype_idwftasks_batchtype,wftasks_batch.batch_year FROM tktin
									INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
									WHERE wftasks_batch.wftasks_batchtype_idwftasks_batchtype=".$fet_batchmeta['wftasks_batchtype_idwftasks_batchtype']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$fet_batchmeta['usrteamzone_idusrteamzone']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  ";//AND YEAR(createdon)='".$this_year."'
									$res_lastbatchno=mysql_query($sql_lastbatchno);
									$fet_lastbatchno=mysql_fetch_array($res_lastbatchno);
									
									//quick validation to avoid crossing over to another year in an older batch
									if ( ($fet_lastbatchno['countbatch']!='') && ($fet_lastbatchno['batch_year']!=$this_year) )
										{
										$error_batchoutdated="<div style=\"color:#ff0000\">You can't assign ".$fet_lastbatchno['batch_year']." in ".$this_year."  </div>";
										//exit;
										}
									
									//create the new batch_no
									$new_batchno=($fet_lastbatchno['countbatch']+1);							
									
									//new update the batch_no meta table
									$sql_updatecount="UPDATE wftasks_batch SET countbatch=(countbatch+1) WHERE idwftasks_batch=".$batch_no."";
									$res_updatecount=mysql_query($sql_updatecount);
									
									//get the tktid to update the tktin as well
									$sql_tktin=mysql_query("SELECT tktin_idtktin FROM wftasks WHERE idwftasks=".$_SESSION['wtaskid']." ");
									$fet_tktin=mysql_fetch_array($sql_tktin);
									
									//update the tkt as well
									$sql_batchtktnew="UPDATE tktin SET 
									wftasks_batch_idwftasks_batch='".$batch_no."',
									batch_number='".$new_batchno."',
									voucher_number='".$new_batchno."'
									WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
									$res_batchtktnew=mysql_query($sql_batchtktnew);
									
									} else { //else if no batch now, then create some dummy queries to run the transction commit succssfully
									////////
									$res_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_lastbatchno=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$sql_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtktnew=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount_old=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_audit1=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchmeta=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									/////
									}//batch now
							
							
							if ( (!isset($error_formdata)) && ($fet_task_details['wftaskstrac_idwftaskstrac']>0) )
								{
							//	echo "1>".$res_task_details."<br>2>".$query_1."<br>3>".$query_2."<br>4>".$query_3."<br>4>".$error_formdata."<br>5>".$res_tktin."<br>6>".$upload_error_1."<br>7>".$upload_error_2."<br>8>".$upload_error_3."<br>9>".$upload_error_4."<br>10>".$res_batchtkt."<br>11>".$res_lastbatchno."<br>12>".$res_updatecount."<br>13>".$sql_tktin."<br>14>".$res_batchtktnew."<br>15>".$res_batchtkt."<br>16>".$res_updatecount_old."<br>17>".$res_audit1;
							//	echo $res_batchtktnew."<br>";
							//	echo $sql_updatecount_old;
								if ( ($res_task_details) && ($query_1) && ($query_2) && ($query_3)  && (!isset($error_formdata)) && ($res_tktin) && (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) && (!isset($upload_error_4)) && ($res_batchtkt) && ($res_lastbatchno) && ($res_updatecount) && ($sql_tktin) && ($res_batchtktnew) && ($res_batchtkt) && ($res_updatecount_old) && ($res_audit1) && ($res_batchmeta) && (!isset($error_batchoutdated)) )
									{
									mysql_query("COMMIT");	
									///////////////////////////// close check and insert new subscriber ////////////////////////////
									//redirect to the correct page
									?>
									<script language="javascript">
									window.location='go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>';
									</script>
									<?php
									exit;
									} else {
									mysql_query("ROLLBACK");
									//$error_system="<div class=\"msg_warning_small\">Sorry! Please try again</div>";
									?>
                                    <script language="javascript">
									 alert ('Sorry! Please Try Again!');
									</script>
                                    <?php
									if (isset($query_1)) { mysql_free_result($query_1);  }
									if (isset($query_2)) { mysql_free_result($query_2);  }
									if (isset($query_3)) { mysql_free_result($query_3); }
									mysql_free_result($res_task_details);	
									} //if the no error 1_1

								} //if no error form data
								
							} //close no error on action 2
				
				} //close action 2
				
				
				
				
///////////////  ACTION 4  ///////////////////////////////////////////////////////////////////////////////////////////////////
				
				if ($tktaction==4) { //Select Task action 4 ie: Invalidate Task
				
					//validate
					if (strlen($tkttskmsg4) < 1)
						{
						$error_4_1="<div class=\"msg_warning_small\">".$msg_warn_msgmis."</div>";
						}
						
					if ($tktinvalidid=="-1") //invalid has to be categorised 
						{
						$error_4_3="<div class=\"msg_warning_small\">".$msg_warning_tktinvalid."</div>";
						}
						
					if (($tktinvalidid=="0") && (strlen($tktinvalidnew)<1) ) 
						{
								
						$error_4_4="<div class=\"msg_warning_small\">".$msg_warning_invalidincomplete."</div>";

						}
					
						
					if ( (!isset($error_4_1)) &&  (!isset($error_4_3)) && (!isset($error_4_4)) )//if the no error 
						{
						
						mysql_query("BEGIN");
						
						//if it is a new invalidation category, then add and retrieve it's id
						if (($tktinvalidid=="0") && (!isset($error_4_4)))
							{
							//insert
							$sql_newinvalid="INSERT INTO wftskinvalidlist (wfttaskinvalidlistlbl,createdby,createdon)
							VALUES ('".$tktinvalidnew."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
							mysql_query($sql_newinvalid);
							
							//retrieve
							$sql_invid="SELECT idwftskinvalidlist FROM wftskinvalidlist WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftskinvalidlist DESC LIMIT 1";
							$res_invid=mysql_query($sql_invid);
							$fet_invid=mysql_fetch_array($res_invid);
							
							$idinvalidcat=$fet_invid['idwftskinvalidlist'];
							
							}
							
							if ($tktinvalidid >0) //invalid greater than zero 
								{
								$idinvalidcat = $tktinvalidid;
								}
							
						//insert invalidity record
						$sql_invalidrec="INSERT INTO wftskinvalid (wftskinvalidlist_idwftskinvalidlist,wftasks_idwftasks,createdby,createdon) 
						VALUES ('".$idinvalidcat."','".$_SESSION['wtaskid']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						$query_2=mysql_query($sql_invalidrec);
						
						//update this task 
						$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='4',wftskstatusglobal_idwftskstatusglobal='3',timeactiontaken='".$timenowis."',actedon_idusrrole=".$_SESSION['MVGitHub_iduserrole'].", actedon_idusrac='".$_SESSION['MVGitHub_idacname']."'  WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$query_3=mysql_query($sql_update_task);
						
						//create an update message on the record
						$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
						VALUES ('".$fet_idtask['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','3','4','".$_SESSION['wtaskid']."','".$tkttskmsg4."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						$query_4=mysql_query($sql_update_msg);
						//echo $sql_update_msg."<br>";
						
						//check if there is a form data and if so, go ahead and process this transaction with inserts or updates
					if ( (isset($_POST['formdata_available'])) && ($_POST['formdata_available']==1) )
						{
						//echo "processed <br>";
						//check the db for this field by reusing the sql statement above
			/*			
						$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets FROM wfprocassetsaccess 
						INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
						WHERE wftskflow_idwftskflow=".$fet_task['wftskflow_idwftskflow']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassets.ordering ASC";
			*/
						$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets,wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms
						FROM wfprocassetsaccess
						INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
						INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
						INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
						WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=".$fet_ticket['tktcategory_idtktcategory']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
			
						$res_val=mysql_query($sql_val);
						$num_val=mysql_num_rows($res_val);
						$fet_val=mysql_fetch_array($res_val);
//	echo $sql_val;
						if ($num_val > 0) //if there are some values, then
							{
							do {
							//master-checklist if  | it is required | there is a value | the data type to determine the field |  whether an update or insert
							
							//validate required
						//	echo "validation ";
						//	echo $_POST['required_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
						//	echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].''];
								if (
								(isset($_POST['required_'.$fet_val['idwfprocassetsaccess'].''])) 
								&& ($_POST['required_'.$fet_val['idwfprocassetsaccess'].'']==1) 
								&&  ($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']=="")   
								)
									{
									//echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
									$error_formdata=1;
									echo "<div class=\"msg_warning_small\"> Form : ".$fet_val['assetname']." is required</div>";
									
									}
								
							//if no error on the dataform, then process
							if (!isset($error_formdata))
								{	
								if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="INSERT")
									{
									//check the form item type first
									$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
										
										if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10)   ) //if textbox OR yes/no OR datepicker OR datetimepicker
											{
											$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
											
											//then process as below
											$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
											wfprocassetschoice_idwfprocassetschoice,
											wfprocassets_idwfprocassets,
											wftasks_idwftasks,
											value_choice,
											value_path,
											wftaskstrac_idwftaskstrac,
											tktin_idtktin,
											createdby,
											createdon)
											VALUES ('".$fet_val['idwfprocassetsaccess']."',
											'0',
											'".$fet_val['idwfprocassets']."',
											'".$_SESSION['wtaskid']."',
											'".$fvalue."',
											'',
											'".$_SESSION['wftaskstrac']."',
											'".$_SESSION['tktin_idtktin']."',
											'".$_SESSION['MVGitHub_idacname']."',
											'".$timenowis."'
											)";
											
											mysql_query($sql_insert);
											//echo $sql_insert;
											//exit;
											}
											
										if ($ttype==2)//if menulist
											{
											$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
											
											$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
											wfprocassetschoice_idwfprocassetschoice,
											wfprocassets_idwfprocassets,
											wftasks_idwftasks,
											value_choice,
											value_path,
											wftaskstrac_idwftaskstrac,
											tktin_idtktin,
											createdby,
											createdon)
											VALUES ('".$fet_val['idwfprocassetsaccess']."',
											'".$fvalue."',
											'".$fet_val['idwfprocassets']."',
											'".$_SESSION['wtaskid']."',
											'',
											'',
											'".$_SESSION['wftaskstrac']."',
											'".$_SESSION['tktin_idtktin']."',
											'".$_SESSION['MVGitHub_idacname']."',
											'".$timenowis."'
											)";
											
											mysql_query($sql_insert);
											
											}
											
										if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											//just keep the file name only
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
											//check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
												
												$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
												
												//validation before uploading										 
												//check if file exists
												if (file_exists($target_file)) 
													{
													$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
													}
												
												if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
													{
													$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
													}
												
												if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
													&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
													&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
													&& $imageFileType != "ppt" && $imageFileType != "pptx"  && $imageFileType != "csv"    ) {
														
													$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
													}
												//echo $upload_error_1.$upload_error_2.$upload_error_3;	
												//echo "Size -->".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
												if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
													{
													 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
														{
														$upload_success=1;
														//log the record into the Database
														$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
														wfprocassetschoice_idwfprocassetschoice,
														wfprocassets_idwfprocassets,
														wftasks_idwftasks,
														value_choice,
														value_path,
														wftaskstrac_idwftaskstrac,
														tktin_idtktin,
														createdby,
														createdon)
														VALUES ('".$fet_val['idwfprocassetsaccess']."',
														'0',
														'".$fet_val['idwfprocassets']."',
														'".$_SESSION['wtaskid']."',
														'',
														'".$file_name_only."',
														'".$_SESSION['wftaskstrac']."',
														'".$_SESSION['tktin_idtktin']."',
														'".$_SESSION['MVGitHub_idacname']."',
														'".$timenowis."'
														)";
														
														mysql_query($sql_insert);
														
														//create the audit log
														$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, wfprocassets_idwfprocassets) 
														VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."','".$fet_val['idwfprocassets']."')";
														mysql_query($sql_audit);
														
														} else {
															$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
														}
													} //no error
												} //where strlen > 4
											} //type==3
											
									
									}
									
								if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="UPDATE")
									{
									$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
									$itempk=mysql_real_escape_string(trim($_POST['itempk_'.$fet_val['idwfprocassetsaccess'].'']));
									
									//value captured - //this hack for checkbox
									if ((($ttype==3)||($ttype==4)) && (!isset($_POST['item_'.$fet_val['idwfprocassetsaccess'].''])) )
										{
										$fvalue=0;
										} else {
										$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
										}
									
									//only if there are records
									if (
										( ($fvalue > 0) || (strlen($fvalue) > 0) && ($ttype!=4) ) 
										|| 
										( ($ttype==4) && (($fvalue=='') || ($fvalue==0) || ($fvalue!=0)) ) 
										) 
									/*if ( ($fvalue!='') && (strlen($fvalue) > 0) )*/
										{	
									//check the form item type first

									if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10)   ) //if textbox OR yes/no OR datepicker OR datetimepicker
											{
											
											///audit log
											$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
											SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '".$fvalue."', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
											FROM wfassetsdata
											WHERE idwfassetsdata=".$itempk." AND value_choice!='".$fvalue."' ";
											//echo $sql_auditlog_form."<br>";
											mysql_query($sql_auditlog_form);
												
											//then process as below
											$sql_update="UPDATE wfassetsdata SET 
											value_choice='".$fvalue."',
											wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
											tktin_idtktin='".$_SESSION['tktin_idtktin']."',
											modifiedby='".$_SESSION['MVGitHub_idacname']."',
											modifiedon='".$timenowis."'
											WHERE idwfassetsdata=".$itempk." LIMIT 1";
											
											mysql_query($sql_update);
											//echo $sql_update;
											}
									
									if ( $ttype==2)//if menulist
											{
											//enter the audit trail only if there is a change
											$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
											SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'".$fvalue."', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
											FROM wfassetsdata
											WHERE idwfassetsdata=".$itempk." AND wfprocassetschoice_idwfprocassetschoice!='".$fvalue."' ";
											//echo $sql_auditlog_form."<br>";
											mysql_query($sql_auditlog_form);
											
											$sql_update="UPDATE wfassetsdata SET 
											wfprocassetschoice_idwfprocassetschoice='".$fvalue."',
											wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
											tktin_idtktin='".$_SESSION['tktin_idtktin']."',
											modifiedby='".$_SESSION['MVGitHub_idacname']."',
											modifiedon='".$timenowis."'
											WHERE idwfassetsdata=".$itempk." LIMIT 1";
											
											mysql_query($sql_update);
											}
											
									if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];

											////check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
											
													$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
													
													//validation before uploading											 
													//check if file exists
													if (file_exists($target_file)) 
														{
														$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
														}
													
													if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
														{
														$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
														}
													
													if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
														&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
														&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
														&& $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "csv"    ) {
															
														$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
														}

													if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
														{
														//echo "soo farr soo good";
														 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
															{
															$upload_success=1;
															
															$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
															SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'".$file_name_only."', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
															FROM wfassetsdata
															WHERE idwfassetsdata=".$itempk." AND value_path!='".$file_name_only."' ";
															//echo $sql_auditlog_form."<br>";
															mysql_query($sql_auditlog_form);
															
															//log the record into the Database
															$sql_update="UPDATE wfassetsdata SET 
															value_path='".$file_name_only."',
															modifiedby='".$_SESSION['MVGitHub_idacname']."',
															modifiedon='".$timenowis."'
															WHERE idwfassetsdata=".$itempk." LIMIT 1";

															mysql_query($sql_update);
															
															//create the audit log
															$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip,wfprocassets_idwfprocassets) 
															VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."',".$itempk.")";
															mysql_query($sql_audit);
																											
															} else {
																$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
															}
														} //no error
													} // if fvalue strlen>4
											
											} //type==3	
										}	
									
									} //end update
									
								}
								
							} while ($fet_val=mysql_fetch_array($res_val));
							
							} //if record is > 0
						
						} //close form data checker
						
						//Feedback SMS to send customer/sender a message
						if (  (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) )
							{
							$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext)  
							VALUES ('".$tktsenderphone."','".$_SESSION['MVGitHub_userteamshortname'].", Ticket ".$tktno."-".$tktsms."')";
							mysql_query($sql_smsout);
							}
					
							//Update the ticket status
							
								
								$sql_updatetkt="UPDATE tktin SET 
								tktstatus_idtktstatus='5',
								timeclosed='".$timenowis."'
								WHERE idtktinPK=".$_SESSION['tktupdate']." 
								LIMIT 1";								
								$query_5=mysql_query($sql_updatetkt);
								

							//notify if anyone is to be notified
							$sql_notify="SELECT idwfnotification,wfnotification.tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,idtktmsgs,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification 
							INNER JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
							WHERE wftskflow_idwftskflow=".$_SESSION['thistskflow']." ORDER BY idwfnotification ASC";
							$res_notify=mysql_query($sql_notify);
							$num_notify=mysql_num_rows($res_notify);
							$fet_notify=mysql_fetch_array($res_notify);
							
							if ($num_notify > 0 ) // if there is a notification setting
								{
								do {			
								//check for each of the settings 
									if ( ($fet_notify['notify_system']==1) && (strlen($fet_notify['tktmsg_dashboard'])>2) ) //system dashboard set on
										{
										$sql_dash="INSERT INTO tktmsglogs_dashboard (tktmsgs_idtktmsgs,msgto_roleid,msgto_subject,msgto_body,createdon,readon)
										VALUES ('".$fet_notify['idtktmsgs']."','".$fet_notify['usrrole_idusrrole']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname'].",'".$fet_notify['tktmsg_dashboard']." - ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
										mysql_query($sql_dash);									
										}// system dashboard set on
												
										//get this roles email address and phone numbers
										//ensure the account is active as well...
										$sql_rolecontacts="SELECT usremail,usrphone FROM usrac WHERE usrrole_idusrrole=".$fet_notify['usrrole_idusrrole']." AND acstatus=1 LIMIT 1";
										$res_rolecontacts=mysql_query($sql_rolecontacts);
										$fet_rolecontacts=mysql_fetch_array($res_rolecontacts);
										$num_rolecontacts=mysql_num_rows($res_rolecontacts);
										
										if ( ($fet_notify['notify_email']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usremail'])>6) && (strlen($fet_notify['tktmsg_email'])>2) )//email set on
											{
											$sql_email="INSERT INTO tktmsgslog_emails(tktmsgs_idtktmsgs,emailto,emailsubject,emailbody,createdon,senton) 
											VALUES ('".$fet_notify['idtktmsgs']."','".$fet_rolecontacts['usremail']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$fet_notify['tktmsg_email']." - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
												
											mysql_query($sql_email);
											}
											
											if ( ($fet_notify['notify_sms']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usrphone'])==13) )
											{
											$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext) 
											VALUES ('".$fet_rolecontacts['usrphone']."',' Auto Notification - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']." received')";
					
											mysql_query($sql_sms);
											}
												
									} while ($fet_notify=mysql_fetch_array($res_notify));								
										
								} //close - if there is a notification setting
					
					/////////////////////////////check and insert a new subscriber
					if ($fet_task['usrrole_idusrrole']==2) //if this is the first ticket from the customer, then do this...
						{
						//check if a subscriber with the same credentials matches
						$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
						$res_subis=mysql_query($sql_subis);
						$num_subis=mysql_num_rows($res_subis);
						
						//if not, add the new credentials
						if ($num_subis==0)
							{
							$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)
							VALUES ('".$_SESSION['wtaskid']."','".$fet_task['tktin_idtktin']."','".$fet_confirmloc['idloctowns']."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
							mysql_query($sql_subnew);
							}
						}
					///////////////////////////// close check and insert new subscriber ////////////////////////////
					
if ( (isset($batch_no)) && ($batch_no>0) && ($fet_ticket['wftasks_batch_idwftasks_batch']!=$batch_no) )
										{
									//first, lets check if this ticket already belonged to another batch before removing it
									$res_tktin=mysql_query("SELECT idtktinPK,wftasks_batch_idwftasks_batch,tktcategory_idtktcategory FROM tktin WHERE idtktinPK=".$fet_ticket['idtktinPK']."  ");
									$fet_tktin=mysql_fetch_array($res_tktin);
											
									if ($fet_tktin['wftasks_batch_idwftasks_batch']>0)
										{
										//update the tkt as well
										$sql_batchtkt="UPDATE tktin SET 
										wftasks_batch_idwftasks_batch='0',
										batch_number='0',
										voucher_number='0'
										WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
										
										//update the countbatch
										$sql_updatecount_old="UPDATE wftasks_batch SET countbatch=(countbatch-1) WHERE idwftasks_batch=".$fet_tktin['wftasks_batch_idwftasks_batch']."";
													
										//log audit 1
										$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
										VALUES ( 'MOVE', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '".$fet_tktin['wftasks_batch_idwftasks_batch']."', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
													
										} else {
										
										$sql_batchtkt="SELECT idtktinPK from tktin LIMIT 1";
										$sql_updatecount_old="SELECT idtktinPK from tktin LIMIT 1";
										
										//log audit 1
										$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
										VALUES ( 'NEW', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '0', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
										
										}
											
									$res_batchtkt=mysql_query($sql_batchtkt);
									$res_updatecount_old=mysql_query($sql_updatecount_old);
									$res_audit1=mysql_query($sql_audit1);
							
									//check the last batch_no
									$res_batchmeta=mysql_query("SELECT usrteamzone_idusrteamzone,wftasks_batchtype_idwftasks_batchtype FROM wftasks_batch WHERE idwftasks_batch=".$batch_no."");
									$fet_batchmeta=mysql_fetch_array($res_batchmeta);
									//changed to get the last max id given for this batch
						//			$sql_lastbatchno="SELECT max(voucher_number) as countbatch FROM tktin WHERE wftasks_batch_idwftasks_batch=".$batch_no."";
									$sql_lastbatchno="SELECT max(tktin.voucher_number) as countbatch,wftasks_batch.wftasks_batchtype_idwftasks_batchtype,wftasks_batch.batch_year FROM tktin
									INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
									WHERE wftasks_batch.wftasks_batchtype_idwftasks_batchtype=".$fet_batchmeta['wftasks_batchtype_idwftasks_batchtype']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$fet_batchmeta['usrteamzone_idusrteamzone']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  ";//AND YEAR(createdon)='".$this_year."'
									$res_lastbatchno=mysql_query($sql_lastbatchno);
									$fet_lastbatchno=mysql_fetch_array($res_lastbatchno);
									
									//quick validation to avoid crossing over to another year in an older batch
									if ( ($fet_lastbatchno['countbatch']!='') && ($fet_lastbatchno['batch_year']!=$this_year) )
										{
										$error_batchoutdated="<div style=\"color:#ff0000\">You can't assign ".$fet_lastbatchno['batch_year']." in ".$this_year."  </div>";
										//exit;
										}
									
									//create the new batch_no
									$new_batchno=($fet_lastbatchno['countbatch']+1);							
									
									//new update the batch_no meta table
									$sql_updatecount="UPDATE wftasks_batch SET countbatch=(countbatch+1) WHERE idwftasks_batch=".$batch_no."";
									$res_updatecount=mysql_query($sql_updatecount);
									
									//get the tktid to update the tktin as well
									$sql_tktin=mysql_query("SELECT tktin_idtktin FROM wftasks WHERE idwftasks=".$_SESSION['wtaskid']." ");
									$fet_tktin=mysql_fetch_array($sql_tktin);
									
									//update the tkt as well
									$sql_batchtktnew="UPDATE tktin SET 
									wftasks_batch_idwftasks_batch='".$batch_no."',
									batch_number='".$new_batchno."',
									voucher_number='".$new_batchno."'
									WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
									$res_batchtktnew=mysql_query($sql_batchtktnew);
									
									} else { //else if no batch now, then create some dummy queries to run the transction commit succssfully
									////////
									$res_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_lastbatchno=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$sql_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtktnew=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount_old=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_audit1=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchmeta=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									/////
									}//batch now
					
					//redirect to the correct page
							if ( ($query_2) && ($query_3)  && ($query_4) && ($query_5)  && (!isset($error_formdata))  && ($res_tktin) && (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) && (!isset($upload_error_4)) && ($res_batchtkt) && ($res_lastbatchno) && ($res_updatecount) && ($sql_tktin) && ($res_batchtktnew) && ($res_batchtkt) && ($res_updatecount_old) && ($res_audit1) && ($res_batchmeta) && (!isset($error_batchoutdated)))
									{
									mysql_query("COMMIT");	
									///////////////////////////// close check and insert new subscriber ////////////////////////////
									//redirect to the correct page
									?>
									<script language="javascript">
									window.location='go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>';
									</script>
									<?php
									exit;
									} else {
									mysql_query("ROLLBACK");
									?>
                                    <script language="javascript">
									 alert ('Sorry! Please Try Again!');
									</script>
                                    <?php
									if (isset($query_2)) { mysql_free_result($query_2);  }
									if (isset($query_3)) { mysql_free_result($query_3); }	
									mysql_free_result($query_4);
									} //if the no error 1_1
						
						
						} //close no error on action 4
				
				} //close action 4				
					

///////////////  ACTION 6  ///////////////////////////////////////////////////////////////////////////////////////////////////
				
				if ($tktaction==6) { //Select Task action 6 ie: status update
				
					//validate
					if (strlen($tkttskmsg6) < 1)
						{
						$error_6_1="<div class=\"msg_warning_small\">".$msg_warn_msgmis."</div>";
						}
						
						
					if (!isset($error_6_1) )//if the no error 
						{
						
						mysql_query("BEGIN");
						
						$tktnewdeadlinefinal=date("Y-m-d H:i:s",strtotime($tktnewdeadline_fin));

						//task details
						$sql_task_details = "SELECT wftasks.usrrole_idusrrole,wftasks.usrac_idusrac FROM wftasks 
						WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$res_task_details = mysql_query($sql_task_details);
						$fet_task_details = mysql_fetch_array($res_task_details);
						
						//update this task 
						$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='6',wftskstatusglobal_idwftskstatusglobal='2',timeactiontaken='".$timenowis."',actedon_idusrrole=".$_SESSION['MVGitHub_iduserrole'].", actedon_idusrac='".$_SESSION['MVGitHub_idacname']."'  WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$query_1=mysql_query($sql_update_task);						
						
						//create an update message on the record
						$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
						VALUES ('".$fet_idtask['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','2','6','".$_SESSION['wtaskid']."','".$tkttskmsg6."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						$query_2=mysql_query($sql_update_msg);
						
						
					//send email to the person CC'D
						if ( (isset($_POST['progress_update_emails'])) && (strlen($_POST['progress_update_emails'])>5) )
							{
							$emailcc=mysql_real_escape_string(trim($_POST['progress_update_emails']));
							//ensure it is clean with correct email format
							if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $emailcc)) 
								{
								$error_ccmail="<div class=\"msg_warning_small\">Wrong email address</div>";
								
								} else {
								
								//go ahead and process
								$to = $emailcc;
						
								$message = "
								Dear Sir/Madam, <br>
								The message below was sent to you via  ".$pagetitle.".
								<br>
								".$tkttskmsg6."
								<br>
								To get further details, please log in to your ".$pagetitle." account at ".$url_absolute."
								<br><br>
								From ".$_SESSION['MVGitHub_usrtitle']." ".$_SESSION['MVGitHub_usrfname'].",<br>
								Support Team,<br>
								".$pagetitle.".
								<br><br>
								<div>
								DISCLAIMER: You received this email because your email address was used on ".$pagetitle."
								The Information contained in this email, including the links, is intended solely for the use of the designated recipient.
								If you have received this e-mail message in error please notify the ".$pagetitle." team through e-mail ".$support_email." and delete it immediately
								</div>";
										
								// To send HTML mail, the Content-type header must be set
								$headers  = 'MIME-Version: 1.0' . "\r\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								
								// Additional headers
								$sendername=''.$_SESSION['MVGitHub_usrfname'].' '.$_SESSION['MVGitHub_usrlname'].'';
								//	$headers .= 'To: '..' <'.$youremail.'>' . "\r\n";
								$headers .= 'From: '.$_SESSION['MVGitHub_usrfname'].' '.$_SESSION['MVGitHub_usrlname'].' <'.$_SESSION['MVGitHub_usremail'].'>' . "\r\n";
										
								$subject = "".$pagetitle." Advice ";
								// Mail it
								//mail($to, $subject, $message, $headers);
									
							//	if ($mailserver_avail==1)
							//		{
							//		mail($to,$subject,$message,$headers);
							//		} else {
									//if mail server is not available, then save the function in a variable and parse this to the online server for processing
									$sql_mailout="INSERT INTO mdata_emailsout (email_to,email_subject,email_message,email_headers,createdon) 
									VALUES ('".$to."','".$subject."','".$message."','".$headers."','".$timenowis."')";
									mysql_query($sql_mailout);
							//		}
								
								} //no email error
							
							} //if cc mail update is set
						
							//check if there is a form data and if so, go ahead and process this transaction with inserts or updates
						if ( (isset($_POST['formdata_available'])) && ($_POST['formdata_available']==1) )
							{
						//echo "processed <br>";
						//check the db for this field by reusing the sql statement above
						/*
						$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets FROM wfprocassetsaccess 
						INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
						WHERE wftskflow_idwftskflow=".$fet_task['wftskflow_idwftskflow']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassets.ordering ASC";
						*/
						$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets,wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms
						FROM wfprocassetsaccess
						INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
						INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
						INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
						WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=".$fet_ticket['tktcategory_idtktcategory']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";

						$res_val=mysql_query($sql_val);
						$num_val=mysql_num_rows($res_val);
						$fet_val=mysql_fetch_array($res_val);
//	echo "sql val-->".$sql_val."<br><br>";
						if ($num_val > 0) //if there are some values, then
							{
							do {
							//master-checklist if  | it is required | there is a value | the data type to determine the field |  whether an update or insert
							
							//validate required
						//	echo "validation ";
						//	echo $_POST['required_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
						//	echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].''];
								if (
								(isset($_POST['required_'.$fet_val['idwfprocassetsaccess'].''])) 
								&& ($_POST['required_'.$fet_val['idwfprocassetsaccess'].'']==1) 
								&&  ($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']=="")   
								)
									{
									//echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
									$error_formdata=1;
									echo "<div class=\"msg_warning_small\"> Form : ".$fet_val['assetname']." is required</div>";
									
									}
								
							//if no error on the dataform, then process
							if (!isset($error_formdata))
								{	
								if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="INSERT")
									{
									//check the form item type first
									$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
																			
										if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10)) //if textbox OR yes/no OR datepicker OR datetimepicker
											{
											//
											$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
											
											//then process as below
											$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
											wfprocassetschoice_idwfprocassetschoice,
											wfprocassets_idwfprocassets,
											wftasks_idwftasks,
											value_choice,
											value_path,
											wftaskstrac_idwftaskstrac,
											tktin_idtktin,
											createdby,
											createdon)
											VALUES ('".$fet_val['idwfprocassetsaccess']."',
											'0',
											'".$fet_val['idwfprocassets']."',
											'".$_SESSION['wtaskid']."',
											'".$fvalue."',
											'',
											'".$_SESSION['wftaskstrac']."',
											'".$_SESSION['tktin_idtktin']."',
											'".$_SESSION['MVGitHub_idacname']."',
											'".$timenowis."'
											)";
											
											mysql_query($sql_insert);
										//	echo $sql_insert."<br>";

											//exit;
											}
											
										if ($ttype==2 )//if menulist
											{
											///
											$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
											//
											$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
											wfprocassetschoice_idwfprocassetschoice,
											wfprocassets_idwfprocassets,
											wftasks_idwftasks,
											value_choice,
											value_path,
											wftaskstrac_idwftaskstrac,
											tktin_idtktin,
											createdby,
											createdon)
											VALUES ('".$fet_val['idwfprocassetsaccess']."',
											'".$fvalue."',
											'".$fet_val['idwfprocassets']."',
											'".$_SESSION['wtaskid']."',
											'',
											'',
											'".$_SESSION['wftaskstrac']."',
											'".$_SESSION['tktin_idtktin']."',
											'".$_SESSION['MVGitHub_idacname']."',
											'".$timenowis."'
											)";
											
											mysql_query($sql_insert);
											
											}
										
										if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											//just keep the file name only
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
											//check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
												
												$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
												
												//validation before uploading										 
												//check if file exists
												if (file_exists($target_file)) 
													{
													$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
													}
												
												if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
													{
													$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
													}
												
												if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
													&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
													&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
													&& $imageFileType != "ppt" && $imageFileType != "pptx"  && $imageFileType != "csv"    ) {
														
													$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
													}
												//echo $upload_error_1.$upload_error_2.$upload_error_3;	
												//echo "Size -->".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
												if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
													{
													 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
														{
														$upload_success=1;
														//log the record into the Database
														$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
														wfprocassetschoice_idwfprocassetschoice,
														wfprocassets_idwfprocassets,
														wftasks_idwftasks,
														value_choice,
														value_path,
														wftaskstrac_idwftaskstrac,
														tktin_idtktin,
														createdby,
														createdon)
														VALUES ('".$fet_val['idwfprocassetsaccess']."',
														'0',
														'".$fet_val['idwfprocassets']."',
														'".$_SESSION['wtaskid']."',
														'',
														'".$file_name_only."',
														'".$_SESSION['wftaskstrac']."',
														'".$_SESSION['tktin_idtktin']."',
														'".$_SESSION['MVGitHub_idacname']."',
														'".$timenowis."'
														)";
														
														mysql_query($sql_insert);
														
														//create the audit log
														$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, wfprocassets_idwfprocassets) 
														VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."','".$fet_val['idwfprocassets']."')";
														mysql_query($sql_audit);
														
														} else {
															$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
														}
													} //no error
												} //where strlen > 4
											} //type==3
									
									}
							
							
								if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="UPDATE")
									{
									$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
									$itempk=mysql_real_escape_string(trim($_POST['itempk_'.$fet_val['idwfprocassetsaccess'].'']));
									
									//value captured - //this hack for checkbox
									if ((($ttype==3)||($ttype==4)) && (!isset($_POST['item_'.$fet_val['idwfprocassetsaccess'].''])) )
										{
										$fvalue=0;
										} else {
										$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
										}
									
									//only if there are records
									if (
										( ($fvalue > 0) || (strlen($fvalue) > 0) && ($ttype!=4) ) 
										|| 
										( ($ttype==4) && (($fvalue=='') || ($fvalue==0) || ($fvalue!=0)) ) 
										) 
									/*if ( ($fvalue!='') && (strlen($fvalue) > 0) )*/
										{
									//check the form item type first
									if (($ttype==1) ||($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9)  || ($ttype==10) ) //if textbox OR yes/no OR datepicker OR datetimepicker
											{											

											//then process as below
											
												//enter the audit trail only if there is a change
												$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
												SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '".$fvalue."', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
												FROM wfassetsdata
												WHERE idwfassetsdata=".$itempk." AND value_choice!='".$fvalue."' ";
												//echo $sql_auditlog_form."<br>";
												mysql_query($sql_auditlog_form);
												
												$sql_update="UPDATE wfassetsdata SET 
												value_choice='".$fvalue."',
												wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
												tktin_idtktin='".$_SESSION['tktin_idtktin']."',
												modifiedby='".$_SESSION['MVGitHub_idacname']."',
												modifiedon='".$timenowis."'
												WHERE idwfassetsdata=".$itempk." LIMIT 1";
											//	echo "<br><br><br><br><br><br><br><bR><br>".$sql_update;
												mysql_query($sql_update);
												
												
												}
											
									
									if ($ttype==2 )//if menulist
											{
											
											//enter the audit trail only if there is a change
												$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
												SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'".$fvalue."', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
												FROM wfassetsdata
												WHERE idwfassetsdata=".$itempk." AND wfprocassetschoice_idwfprocassetschoice!='".$fvalue."' ";
												//echo $sql_auditlog_form."<br>";
												mysql_query($sql_auditlog_form);
																					
												$sql_update="UPDATE wfassetsdata SET 
												wfprocassetschoice_idwfprocassetschoice='".$fvalue."',
												wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
												tktin_idtktin='".$_SESSION['tktin_idtktin']."',
												modifiedby='".$_SESSION['MVGitHub_idacname']."',
												modifiedon='".$timenowis."'
												WHERE idwfassetsdata=".$itempk." LIMIT 1";
												//echo "Status Update->--".$sql_update."<br><br>";
												mysql_query($sql_update);
											}
											
									if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];

											////check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
											
													$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
													
													//validation before uploading											 
													//check if file exists
													if (file_exists($target_file)) 
														{
														$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
														}
													
													if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
														{
														$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
														}
													
													if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
														&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
														&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
														&& $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "csv"    ) {
															
														$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
														}

													if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
														{
														//echo "soo farr soo good";
														 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
															{
															$upload_success=1;
															
															$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
															SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'".$file_name_only."', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
															FROM wfassetsdata
															WHERE idwfassetsdata=".$itempk." AND value_path!='".$file_name_only."' ";
															//echo $sql_auditlog_form."<br>";
															mysql_query($sql_auditlog_form);
															
															//log the record into the Database
															$sql_update="UPDATE wfassetsdata SET 
															value_path='".$file_name_only."',
															modifiedby='".$_SESSION['MVGitHub_idacname']."',
															modifiedon='".$timenowis."'
															WHERE idwfassetsdata=".$itempk." LIMIT 1";

															mysql_query($sql_update);
															
															//create the audit log
															$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip,wfprocassets_idwfprocassets) 
															VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."',".$itempk.")";
															mysql_query($sql_audit);
																											
															} else {
																$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
															}
														} //no error
													} // if fvalue strlen>4
											
											} //type==3		
										//echo ">>>".$sql_update."<br>";	
										
										} //closed if there data
										
									}
									
								}
								
							} while ($fet_val=mysql_fetch_array($res_val));
							
							} //if record is > 0
						
						} //close form data checker
						
						//Feedback SMS to send customer/sender a message
						if ( (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) )
							{
							$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext)  
							VALUES ('".$tktsenderphone."','".$_SESSION['MVGitHub_userteamshortname'].", Ticket ".$tktno."-".$tktsms."')";
							mysql_query($sql_smsout);
							}
					
						
							//notify if anyone is to be notified
							$sql_notify="SELECT idwfnotification,wfnotification.tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,idtktmsgs,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification 
							INNER JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
							WHERE wftskflow_idwftskflow=".$_SESSION['thistskflow']." ORDER BY idwfnotification ASC";
							$res_notify=mysql_query($sql_notify);
							$num_notify=mysql_num_rows($res_notify);
							$fet_notify=mysql_fetch_array($res_notify);
							
							if ($num_notify > 0 ) // if there is a notification setting
								{
								do {			
								//check for each of the settings 
									if ( ($fet_notify['notify_system']==1) && (strlen($fet_notify['tktmsg_dashboard'])>2) ) //system dashboard set on
										{
										$sql_dash="INSERT INTO tktmsglogs_dashboard (tktmsgs_idtktmsgs,msgto_roleid,msgto_subject,msgto_body,createdon,readon)
										VALUES ('".$fet_notify['idtktmsgs']."','".$fet_notify['usrrole_idusrrole']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname'].",'".$fet_notify['tktmsg_dashboard']." - ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
										mysql_query($sql_dash);									
										}// system dashboard set on
												
										//get this roles email address and phone numbers
										//ensure the account is active as well...
										$sql_rolecontacts="SELECT usremail,usrphone FROM usrac WHERE usrrole_idusrrole=".$fet_notify['usrrole_idusrrole']." AND acstatus=1 LIMIT 1";
										$res_rolecontacts=mysql_query($sql_rolecontacts);
										$fet_rolecontacts=mysql_fetch_array($res_rolecontacts);
										$num_rolecontacts=mysql_num_rows($res_rolecontacts);
										
										if ( ($fet_notify['notify_email']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usremail'])>6) && (strlen($fet_notify['tktmsg_email'])>2) )//email set on
											{
											$sql_email="INSERT INTO tktmsgslog_emails(tktmsgs_idtktmsgs,emailto,emailsubject,emailbody,createdon,senton) 
											VALUES ('".$fet_notify['idtktmsgs']."','".$fet_rolecontacts['usremail']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$fet_notify['tktmsg_email']." - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
												
											mysql_query($sql_email);
											}
											
											if ( ($fet_notify['notify_sms']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usrphone'])==13) )
											{
											$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext) 
											VALUES ('".$fet_rolecontacts['usrphone']."',' Auto Notification - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']." received')";
					
											mysql_query($sql_sms);
											}
												
									} while ($fet_notify=mysql_fetch_array($res_notify));								
										
								} //close - if there is a notification setting
					
					//redirect to the correct page
					//header('pop_viewtaskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];
					/////////////////////////////check and insert a new subscriber
					if ($fet_task['usrrole_idusrrole']==2) //if this is the first ticket from the customer, then do this...
						{
						//check if a subscriber with the same credentials matches
						$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
						$res_subis=mysql_query($sql_subis);
						$num_subis=mysql_num_rows($res_subis);
						
						//if not, add the new credentials
						if ($num_subis==0)
							{
							$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)
							VALUES ('".$_SESSION['wtaskid']."','".$fet_task['tktin_idtktin']."','".$fet_confirmloc['idloctowns']."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
							mysql_query($sql_subnew);
							}
						}
						
						if ( (isset($batch_no)) && ($batch_no>0) && ($fet_ticket['wftasks_batch_idwftasks_batch']!=$batch_no) )
										{
									//first, lets check if this ticket already belonged to another batch before removing it
									$res_tktin=mysql_query("SELECT idtktinPK,wftasks_batch_idwftasks_batch,tktcategory_idtktcategory FROM tktin WHERE idtktinPK=".$fet_ticket['idtktinPK']."  ");
									$fet_tktin=mysql_fetch_array($res_tktin);
											
									if ($fet_tktin['wftasks_batch_idwftasks_batch']>0)
										{
										//update the tkt as well
										$sql_batchtkt="UPDATE tktin SET 
										wftasks_batch_idwftasks_batch='0',
										batch_number='0',
										voucher_number='0'
										WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
										
										//update the countbatch
										$sql_updatecount_old="UPDATE wftasks_batch SET countbatch=(countbatch-1) WHERE idwftasks_batch=".$fet_tktin['wftasks_batch_idwftasks_batch']."";
													
										//log audit 1
										$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
										VALUES ( 'MOVE', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '".$fet_tktin['wftasks_batch_idwftasks_batch']."', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
													
										} else {
										
										$sql_batchtkt="SELECT idtktinPK from tktin LIMIT 1";
										$sql_updatecount_old="SELECT idtktinPK from tktin LIMIT 1";
										
										//log audit 1
										$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
										VALUES ( 'NEW', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '0', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
										
										}
											
									$res_batchtkt=mysql_query($sql_batchtkt);
									$res_updatecount_old=mysql_query($sql_updatecount_old);
									$res_audit1=mysql_query($sql_audit1);
							
									//check the last batch_no
									$res_batchmeta=mysql_query("SELECT usrteamzone_idusrteamzone,wftasks_batchtype_idwftasks_batchtype FROM wftasks_batch WHERE idwftasks_batch=".$batch_no."");
									$fet_batchmeta=mysql_fetch_array($res_batchmeta);
									//changed to get the last max id given for this batch
						//			$sql_lastbatchno="SELECT max(voucher_number) as countbatch FROM tktin WHERE wftasks_batch_idwftasks_batch=".$batch_no."";
									$sql_lastbatchno="SELECT max(tktin.voucher_number) as countbatch,wftasks_batch.wftasks_batchtype_idwftasks_batchtype,wftasks_batch.batch_year FROM tktin
									INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
									WHERE wftasks_batch.wftasks_batchtype_idwftasks_batchtype=".$fet_batchmeta['wftasks_batchtype_idwftasks_batchtype']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$fet_batchmeta['usrteamzone_idusrteamzone']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  ";//AND YEAR(createdon)='".$this_year."'
									$res_lastbatchno=mysql_query($sql_lastbatchno);
									$fet_lastbatchno=mysql_fetch_array($res_lastbatchno);
									
									//quick validation to avoid crossing over to another year in an older batch
									if ( ($fet_lastbatchno['countbatch']!='') && ($fet_lastbatchno['batch_year']!=$this_year) )
										{
										$error_batchoutdated="<div style=\"color:#ff0000\">You can't assign ".$fet_lastbatchno['batch_year']." in ".$this_year."  </div>";
										//exit;
										}
									
									//create the new batch_no
									$new_batchno=($fet_lastbatchno['countbatch']+1);							
									
									//new update the batch_no meta table
									$sql_updatecount="UPDATE wftasks_batch SET countbatch=(countbatch+1) WHERE idwftasks_batch=".$batch_no."";
									$res_updatecount=mysql_query($sql_updatecount);
									
									//get the tktid to update the tktin as well
									$sql_tktin=mysql_query("SELECT tktin_idtktin FROM wftasks WHERE idwftasks=".$_SESSION['wtaskid']." ");
									$fet_tktin=mysql_fetch_array($sql_tktin);
									
									//update the tkt as well
									$sql_batchtktnew="UPDATE tktin SET 
									wftasks_batch_idwftasks_batch='".$batch_no."',
									batch_number='".$new_batchno."',
									voucher_number='".$new_batchno."'
									WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
									$res_batchtktnew=mysql_query($sql_batchtktnew);
									
									} else { //else if no batch now, then create some dummy queries to run the transction commit succssfully
									////////
									$res_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_lastbatchno=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$sql_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtktnew=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount_old=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_audit1=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchmeta=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									/////
									}//batch now

					///////////////////////////// close check and insert new subscriber ////////////////////////////
							if ( ($query_1)  && (!isset($error_formdata)) && ($query_2) && (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) && (!isset($upload_error_4)) && ($res_tktin) && ($res_batchtkt) && ($res_lastbatchno) && ($res_updatecount) && ($sql_tktin) && ($res_batchtktnew) && ($res_batchtkt) && ($res_updatecount_old) && ($res_audit1) && ($res_batchmeta) && (!isset($error_batchoutdated)))
									{
									mysql_query("COMMIT");	
									///////////////////////////// close check and insert new subscriber ////////////////////////////
									//redirect to the correct page
									?>
									<script language="javascript">
									window.location='go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>';
									</script>
									<?php
									exit;
									} else {
									mysql_query("ROLLBACK");
									?>
                                    <script language="javascript">
									 alert ('Sorry! Please Try Again!');
									</script>
                                    <?php
									if ($query_1) { mysql_free_result($query_1);  }
									if ($query_2) { mysql_free_result($query_2);  }
									} //if the no error 1_1
						
						
						} //close no error on action 6
				
				} //close action 6
					
					
///////////////  ACTION 9  ///////////////////////////////////////////////////////////////////////////////////////////////////
				
				if ($tktaction==9) { //Select Task Action 9 ie: Return to Sender
				
					//validate
					if (strlen($tkttskmsg9) < 1)
						{
						$error_9_1="<div class=\"msg_warning_small\">".$msg_warn_msgmis."</div>";
						}
					if ($tktasito9<1)
						{
						$error_9_2="<div class=\"msg_warning_small\">".$msg_warn_assign."</div>";
						}
						
					
					if ( (!isset($error_9_1)) && (!isset($error_9_2)) )//if the no error 
						{
						
						mysql_query("BEGIN");
						
						//get the value of the person who originally sent the task
						$task_sent_from=mysql_real_escape_string(trim($_POST['task_sent_from']));	
						
						//update this task 
						$sql_update_task="UPDATE wftasks SET wftskstatustypes_idwftskstatustypes='2',wftskstatusglobal_idwftskstatusglobal='2',timeactiontaken='".$timenowis."'  WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$query_1=mysql_query($sql_update_task);

						
						//create an update message on the record
						$sql_update_msg="INSERT INTO wftskupdates (wftaskstrac_idwftaskstrac,usrrole_idusrrole,usrac_idusrac,wftskstatusglobal_idwftskstatusglobal,wftskstatustypes_idwftskstatustypes,wftasks_idwftasks,wftskupdate,createdby,createdon) 
						VALUES ('".$fet_idtask['wftaskstrac_idwftaskstrac']."','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','2','9','".$_SESSION['wtaskid']."','".$tkttskmsg9."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						$query_2=mysql_query($sql_update_msg);

						//get task details
						$sql_task_details = "SELECT wftasks.wftaskstrac_idwftaskstrac,wftasks.idwftasks,wftasks.usrrole_idusrrole,wftasks.wftasks_idwftasks,wftasks.wftskflow_idwftskflow,wftskflow.wfproc_idwfproc,wftasks.tktin_idtktin,wftasks.usrac_idusrac,wftasks.wftskstatustypes_idwftskstatustypes,wftasks.wftskstatusglobal_idwftskstatusglobal,wftasks.tasksubject,wftasks.taskdesc,wftasks.timeinactual,wftasks.timeoveralldeadline,wftasks.timetatstart,wftasks.timedeadline,wftasks.timeactiontaken,wftasks.sender_idusrrole,wftasks.sender_idusrac,wftskflow.listorder,wftskflow.idwftskflow,wftskflow.wftsktat,wfproc.wfproctat FROM wftasks 
						INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow 
						INNER JOIN wfproc ON wftskflow.wfproc_idwfproc=wfproc.idwfproc
						WHERE idwftasks=".$_SESSION['wtaskid']." LIMIT 1";
						$res_task_details = mysql_query($sql_task_details);
						$fet_task_details = mysql_fetch_array($res_task_details);
					
						////////////// START CALCULATION OF TIME /////////
						//find previous task step in this case should be back to the previous step
						$sql_nextwf="SELECT idwftskflow,wftskflow.wfsymbol_idwfsymbol as wfsymbol,wfactors.usrrole_idusrrole as usrrole,wfactors.usrgroup_idusrgroup as usrgroup,wftsktat,expubholidays,sender_idusrrole 
						FROM wftskflow 
						INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow 
						INNER JOIN wftasks ON wftskflow.idwftskflow=wftasks.wftskflow_idwftskflow
						WHERE 
						wftasks.sender_idusrrole=".$task_sent_from." 
						AND wftasks.usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']."
						AND wfproc_idwfproc=".$fet_task_details['wfproc_idwfproc']." 
						AND wftskflow.wfsymbol_idwfsymbol=2 
						AND listorder<'".$fet_task_details['listorder']."'
						GROUP BY idwftskflow ORDER BY listorder DESC LIMIT 1";
						$res_nextwf=mysql_query($sql_nextwf);
						$num_nextwf=mysql_num_rows($res_nextwf);
						$fet_nextwf=mysql_fetch_array($res_nextwf);
						
						
						//get user account id
						$sql_userid="SELECT idusrac FROM usrac WHERE usrrole_idusrrole=".$tktasito9." LIMIT 1";
						$res_userid=mysql_query($sql_userid);
						$fet_userid=mysql_fetch_array($res_userid);
						
						//insert new task for the recepeint
						$sql_new_task="INSERT INTO wftasks (wftaskstrac_idwftaskstrac,usrrole_idusrrole,wftasks_idwftasks,wftskflow_idwftskflow,tktin_idtktin,usrac_idusrac,wftskstatustypes_idwftskstatustypes,wftskstatusglobal_idwftskstatusglobal,tasksubject,taskdesc,timeinactual,timeoveralldeadline,timetatstart,timedeadline,timeactiontaken,sender_idusrrole,sender_idusrac,createdon) 
						VALUES ('".$fet_task_details['wftaskstrac_idwftaskstrac']."','".$tktasito9."','".$fet_task_details['idwftasks']."','".$fet_nextwf['idwftskflow']."','".$fet_task_details['tktin_idtktin']."','".$fet_userid['idusrac']."','0','1','".$fet_task_details['tasksubject']."','".$tkttskmsg9."','".$timenowis."','".$fet_task_details['timeoveralldeadline']."','".$fet_task_details['timetatstart']."','".$fet_task_details['timedeadline']."','0000-00-00 00:00:00','".$_SESSION['MVGitHub_iduserrole']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
						
						if ($fet_task_details['wftaskstrac_idwftaskstrac'] > 0)
							{
							$query_3=mysql_query($sql_new_task);
							}
					
						//check if there is a form data and if so, go ahead and process this transaction with inserts or updates
					if ( (isset($_POST['formdata_available'])) && ($_POST['formdata_available']==1) )
						{
						//echo "processed <br>";
						//check the db for this field by reusing the sql statement above
						/*
						$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets FROM wfprocassetsaccess 
						INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
						WHERE wftskflow_idwftskflow=".$fet_task['wftskflow_idwftskflow']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassets.ordering ASC";
						*/
						$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets,wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms
						FROM wfprocassetsaccess
						INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
						INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
						INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
						WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=".$fet_ticket['tktcategory_idtktcategory']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";

						$res_val=mysql_query($sql_val);
						$num_val=mysql_num_rows($res_val);
						$fet_val=mysql_fetch_array($res_val);
//	echo $sql_val;
						if ($num_val > 0) //if there are some values, then
							{
							do {
							//master-checklist if  | it is required | there is a value | the data type to determine the field |  whether an update or insert
							
							//validate required
						//	echo "validation ";
						//	echo $_POST['required_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
						//	echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].''];
								if (
								(isset($_POST['required_'.$fet_val['idwfprocassetsaccess'].''])) 
								&& ($_POST['required_'.$fet_val['idwfprocassetsaccess'].'']==1) 
								&&  ($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']=="")   
								)
									{
									//echo $_POST['item_'.$fet_val['idwfprocassetsaccess'].'']."<br>";
									$error_formdata=1;
									echo "<div class=\"msg_warning_small\"> Form : ".$fet_val['assetname']." is required</div>";
									
									}
								
							//if no error on the dataform, then process
							if (!isset($error_formdata))
								{	
								if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="INSERT")
									{
									//check the form item type first
									$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
										
										if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10)   ) //if textbox OR yes/no OR datepicker OR datetimepicker
											{
											$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
											
											//then process as below
											$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
											wfprocassetschoice_idwfprocassetschoice,
											wfprocassets_idwfprocassets,
											wftasks_idwftasks,
											value_choice,
											value_path,
											wftaskstrac_idwftaskstrac,
											tktin_idtktin,
											createdby,
											createdon)
											VALUES ('".$fet_val['idwfprocassetsaccess']."',
											'0',

											'".$fet_val['idwfprocassets']."',
											'".$_SESSION['wtaskid']."',
											'".$fvalue."',
											'',
											'".$_SESSION['wftaskstrac']."',
											'".$_SESSION['tktin_idtktin']."',
											'".$_SESSION['MVGitHub_idacname']."',
											'".$timenowis."'
											)";
											
											mysql_query($sql_insert);
											//echo $sql_insert;
											//exit;
											}
											
										if ($ttype==2)//if menulist
											{
											
											$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
											
											$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
											wfprocassetschoice_idwfprocassetschoice,
											wfprocassets_idwfprocassets,
											wftasks_idwftasks,
											value_choice,
											value_path,
											wftaskstrac_idwftaskstrac,
											tktin_idtktin,
											createdby,
											createdon)
											VALUES ('".$fet_val['idwfprocassetsaccess']."',
											'".$fvalue."',
											'".$fet_val['idwfprocassets']."',
											'".$_SESSION['wtaskid']."',
											'',
											'',
											'".$_SESSION['wftaskstrac']."',
											'".$_SESSION['tktin_idtktin']."',
											'".$_SESSION['MVGitHub_idacname']."',
											'".$timenowis."'
											)";
											
											mysql_query($sql_insert);
											
											}
										
										if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											//just keep the file name only
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
											//check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
												
												$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
												
												//validation before uploading										 
												//check if file exists
												if (file_exists($target_file)) 
													{
													$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
													}
												
												if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
													{
													$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
													}
												
												if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
													&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
													&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
													&& $imageFileType != "ppt" && $imageFileType != "pptx"  && $imageFileType != "csv"    ) {
														
													$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
													}
												//echo $upload_error_1.$upload_error_2.$upload_error_3;	
												//echo "Size -->".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];
												if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
													{
													 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
														{
														$upload_success=1;
														//log the record into the Database
														$sql_insert="INSERT INTO wfassetsdata (wfprocassetsaccess_idwfprocassetsaccess,
														wfprocassetschoice_idwfprocassetschoice,
														wfprocassets_idwfprocassets,
														wftasks_idwftasks,
														value_choice,
														value_path,
														wftaskstrac_idwftaskstrac,
														tktin_idtktin,
														createdby,
														createdon)
														VALUES ('".$fet_val['idwfprocassetsaccess']."',
														'0',
														'".$fet_val['idwfprocassets']."',
														'".$_SESSION['wtaskid']."',
														'',
														'".$file_name_only."',
														'".$_SESSION['wftaskstrac']."',
														'".$_SESSION['tktin_idtktin']."',
														'".$_SESSION['MVGitHub_idacname']."',
														'".$timenowis."'
														)";
														
														mysql_query($sql_insert);
														
														//create the audit log
														$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip, wfprocassets_idwfprocassets) 
														VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."','".$fet_val['idwfprocassets']."')";
														mysql_query($sql_audit);
														
														} else {
															$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
														}
													} //no error
												} //where strlen > 4
											} //type==3
									
									} //insert ends here
									
								if ($_POST['transtype_'.$fet_val['idwfprocassetsaccess'].'']=="UPDATE")
									{
									$ttype=$_POST['itemtype_'.$fet_val['idwfprocassetsaccess'].''];
									$itempk=mysql_real_escape_string(trim($_POST['itempk_'.$fet_val['idwfprocassetsaccess'].'']));
									
									//value captured - //this hack for checkbox
									if ((($ttype==3)||($ttype==4)) && (!isset($_POST['item_'.$fet_val['idwfprocassetsaccess'].''])) )
										{
										$fvalue=0;
										} else {
										$fvalue=mysql_real_escape_string(trim($_POST['item_'.$fet_val['idwfprocassetsaccess'].'']));
										}
									
									//only if there are records
									if (
										( ($fvalue > 0) || (strlen($fvalue) > 0) && ($ttype!=4) ) 
										|| 
										( ($ttype==4) && (($fvalue=='') || ($fvalue==0) || ($fvalue!=0)) ) 
										) 
									/*if ( ($fvalue!='') && (strlen($fvalue) > 0) )*/
										{
									//check the form item type first
									if (($ttype==1) || ($ttype==4) || ($ttype==5) || ($ttype==6) || ($ttype==7) || ($ttype==8) || ($ttype==9) || ($ttype==10)   ) //if textbox OR yes/no OR datepicker OR datetimepicker
											{
											
											///audit log
											$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
											SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '".$fvalue."', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
											FROM wfassetsdata
											WHERE idwfassetsdata=".$itempk." AND value_choice!='".$fvalue."' ";
											//echo $sql_auditlog_form."<br>";
											mysql_query($sql_auditlog_form);
											
											//then process as below
											$sql_update="UPDATE wfassetsdata SET 
											value_choice='".$fvalue."',
											wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
											tktin_idtktin='".$_SESSION['tktin_idtktin']."',
											modifiedby='".$_SESSION['MVGitHub_idacname']."',
											modifiedon='".$timenowis."'
											WHERE idwfassetsdata=".$itempk." LIMIT 1";
											
											mysql_query($sql_update);
											//echo $sql_update;
											}
									
									if ($ttype==2 )//if menulist
											{
											//enter the audit trail only if there is a change
											$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
											SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'".$fvalue."', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
											FROM wfassetsdata
											WHERE idwfassetsdata=".$itempk." AND wfprocassetschoice_idwfprocassetschoice!='".$fvalue."' ";
											//echo $sql_auditlog_form."<br>";
											mysql_query($sql_auditlog_form);
											
											$sql_update="UPDATE wfassetsdata SET 
											wfprocassetschoice_idwfprocassetschoice='".$fvalue."',
											wftaskstrac_idwftaskstrac='".$_SESSION['wftaskstrac']."',
											tktin_idtktin='".$_SESSION['tktin_idtktin']."',
											modifiedby='".$_SESSION['MVGitHub_idacname']."',
											modifiedon='".$timenowis."'
											WHERE idwfassetsdata=".$itempk." LIMIT 1";
											
											mysql_query($sql_update);
											}
									
									if ( ($ttype==3) && (isset($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])) && (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4) )//if file upload".$_SESSION['tktin_idtktin']."
											{
											$fvalue_upload=basename($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"]);
											$target_dir = "../documents/task_docs/".$today."/";
//											$docname=$_SESSION['tktin_idtktin']."_".basename($_FILES["fileToUpload"]["name"]);
											$docname=$_SESSION['tktin_idtktin']."_".$fvalue_upload;
											//we need to seed the document to make it unique_
											//lets include the ticket_ref number of the task to the name of the file
											$target_file = $target_dir . $docname;
											$uploadOk = 1;
											$file_name_only=$_SESSION['tktin_idtktin']."_".$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"];
											$file_size_only=$_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"];

											////check if there is any document before proceeding
											if (strlen($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["name"])>4)
												{
											
													$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
													
													//validation before uploading											 
													//check if file exists
													if (file_exists($target_file)) 
														{
														$upload_error_1 = "<div class=\"msg_warning_small\">File Missing</div>";
														}
													
													if ($_FILES["item_".$fet_val['idwfprocassetsaccess'].""]["size"] > 10485760) 
														{
														$upload_error_2 = "<div class=\"msg_warning_small\">File Max Size Exceeded( 10 MB)</div>";
														}
													
													if	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
														&& $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" 
														&& $imageFileType != "pdf" && $imageFileType != "xls" && $imageFileType != "xlsx" 
														&& $imageFileType != "ppt" && $imageFileType != "pptx" && $imageFileType != "csv"    ) {
															
														$upload_error_3 = "<div class=\"msg_warning_small\">Sorry, file format [".$imageFileType."] not allowed</div>";
														}

													if ( (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) )
														{
														//echo "soo farr soo good";
														 if (move_uploaded_file($_FILES['item_'.$fet_val['idwfprocassetsaccess'].'']["tmp_name"], $target_file)) 
															{
															$upload_success=1;
															
															$sql_auditlog_form="INSERT INTO audit_wfassetsdata (idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice_prev, wfprocassetschoice_idwfprocassetschoice_new, wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice_prev, value_choice_new, value_path_prev, value_path_new, wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon, modifiedby_new, modifiedon_new) 
															SELECT idwfassetsdata, wfprocassetsaccess_idwfprocassetsaccess, wfprocassetschoice_idwfprocassetschoice,'', wfprocassets_idwfprocassets, wftasks_idwftasks, wftskupdates_idwftskupdates, value_choice, '', value_path,'".$file_name_only."', wftaskstrac_idwftaskstrac, tktin_idtktin, createdby, createdon,".$_SESSION['MVGitHub_idacname'].",'".$timenowis."' 
															FROM wfassetsdata
															WHERE idwfassetsdata=".$itempk." AND value_path!='".$file_name_only."' ";
															//echo $sql_auditlog_form."<br>";
															mysql_query($sql_auditlog_form);
															
															//log the record into the Database
															$sql_update="UPDATE wfassetsdata SET 
															value_path='".$file_name_only."',
															modifiedby='".$_SESSION['MVGitHub_idacname']."',
															modifiedon='".$timenowis."'
															WHERE idwfassetsdata=".$itempk." LIMIT 1";

															mysql_query($sql_update);
															
															//create the audit log
															$sql_audit="INSERT INTO audit_docuploads ( doc_name, doc_ext, doc_size, tktin_idtktin, createdon, createdby, usersess, usrip,wfprocassets_idwfprocassets) 
															VALUES ('".$file_name_only."', '".$imageFileType."', '".$file_size_only."', '".$_SESSION['tktin_idtktin']."', '".$timenowis."', '".$_SESSION['MVGitHub_idacname']."', '".session_id()."', '".$_SERVER['REMOTE_ADDR']."',".$itempk.")";
															mysql_query($sql_audit);
																											
															} else {
																$upload_error_4 = "<div class=\"msg_warning_small\">Sorry, we are unable to upload that file</div>";
															}
														} //no error
													} // if fvalue strlen>4
											
											} //type==3			
										
									} //end update
									
									}
								}
								
							} while ($fet_val=mysql_fetch_array($res_val));
							
							} //if record is > 0
						
						} //close form data checker
												
						//Feedback SMS to send customer/sender a message
						if ( (isset($tktsms)) && (strlen($tktsms)>5) && (strlen($tktsenderphone)==12) )
							{
							$sql_smsout="INSERT INTO mdata_out_sms (destnumber,msgtext)  
							VALUES ('".$tktsenderphone."','".$_SESSION['MVGitHub_userteamshortname'].", Ticket ".$tktno."-".$tktsms."')";
							mysql_query($sql_smsout);
							}
					
							
							//notify if anyone is to be notified
							$sql_notify="SELECT idwfnotification,wfnotification.tktstatus_idtktstatus,usrrole_idusrrole,wftskflow_idwftskflow,notify_system,notify_email,notify_sms,idtktmsgs,tktmsg_sms,tktmsg_email,tktmsg_dashboard FROM wfnotification 
							INNER JOIN tktmsgs ON wfnotification.idwfnotification=tktmsgs.wfnotification_idwfnotification
							WHERE wftskflow_idwftskflow=".$_SESSION['thistskflow']." ORDER BY idwfnotification ASC";
							$res_notify=mysql_query($sql_notify);
							$num_notify=mysql_num_rows($res_notify);
							$fet_notify=mysql_fetch_array($res_notify);
							
							if ($num_notify > 0 ) // if there is a notification setting
								{
								do {			
								//check for each of the settings 
									if ( ($fet_notify['notify_system']==1) && (strlen($fet_notify['tktmsg_dashboard'])>2) ) //system dashboard set on
										{
										$sql_dash="INSERT INTO tktmsglogs_dashboard (tktmsgs_idtktmsgs,msgto_roleid,msgto_subject,msgto_body,createdon,readon)
										VALUES ('".$fet_notify['idtktmsgs']."','".$fet_notify['usrrole_idusrrole']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname'].",'".$fet_notify['tktmsg_dashboard']." - ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
										mysql_query($sql_dash);									
										}// system dashboard set on
												
										//get this roles email address and phone numbers
										//ensure the account is active as well...
										$sql_rolecontacts="SELECT usremail,usrphone FROM usrac WHERE usrrole_idusrrole=".$fet_notify['usrrole_idusrrole']." AND acstatus=1 LIMIT 1";
										$res_rolecontacts=mysql_query($sql_rolecontacts);
										$fet_rolecontacts=mysql_fetch_array($res_rolecontacts);

										$num_rolecontacts=mysql_num_rows($res_rolecontacts);
										
										if ( ($fet_notify['notify_email']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usremail'])>6) && (strlen($fet_notify['tktmsg_email'])>2) )//email set on
											{
											$sql_email="INSERT INTO tktmsgslog_emails(tktmsgs_idtktmsgs,emailto,emailsubject,emailbody,createdon,senton) 
											VALUES ('".$fet_notify['idtktmsgs']."','".$fet_rolecontacts['usremail']."','Notification - Tkt No : ".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$fet_notify['tktmsg_email']." - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']."','".$timenowis."','0000-00-00 00:00:00')";
												
											mysql_query($sql_email);
											}
											
											if ( ($fet_notify['notify_sms']==1) && ($num_rolecontacts>0) && (strlen($fet_rolecontacts['usrphone'])==13) )
											{
											$sql_sms="INSERT INTO mdata_out_sms (destnumber,msgtext) 
											VALUES ('".$fet_rolecontacts['usrphone']."',' Auto Notification - Tkt No:".$tktno.", ".$fet_ticketcat['tktcategoryname']." received')";
					
											mysql_query($sql_sms);
											}
												
									} while ($fet_notify=mysql_fetch_array($res_notify));								
										
								} //close - if there is a notification setting
					
					/////////////////////////////check and insert a new subscriber
					if ($fet_task['usrrole_idusrrole']==2) //if this is the first ticket from the customer, then do this...
						{
						//check if a subscriber with the same credentials matches
						$sql_subis="SELECT idsmssubs FROM ".$_SESSION['MVGitHub_tblsmsbc']." WHERE subnumber='".$tktsenderphone."' AND usrtype=1";
						$res_subis=mysql_query($sql_subis);
						$num_subis=mysql_num_rows($res_subis);
						
						//if not, add the new credentials
						if ($num_subis==0)
							{
							$sql_subnew="INSERT INTO ".$_SESSION['MVGitHub_tblsmsbc']." (wftskid,tktid,subnumber,idloctown,idusrteamzone,usrtype,createdon,createdby)

							VALUES ('".$_SESSION['wtaskid']."','".$fet_task['tktin_idtktin']."','".$fet_confirmloc['idloctowns']."','".$_SESSION['MVGitHub_userteamzoneid']."','1','".$timenowis."','".$_SESSION['MVGitHub_idacname']."')";
							mysql_query($sql_subnew);
							}
						}
					///////////////////////////// close check and insert new subscriber ////////////////////////////
					if ( (isset($batch_no)) && ($batch_no>0) && ($fet_ticket['wftasks_batch_idwftasks_batch']!=$batch_no) )
										{
									//first, lets check if this ticket already belonged to another batch before removing it
									$res_tktin=mysql_query("SELECT idtktinPK,wftasks_batch_idwftasks_batch,tktcategory_idtktcategory FROM tktin WHERE idtktinPK=".$fet_ticket['idtktinPK']."  ");
									$fet_tktin=mysql_fetch_array($res_tktin);
											
									if ($fet_tktin['wftasks_batch_idwftasks_batch']>0)
										{
										//update the tkt as well
										$sql_batchtkt="UPDATE tktin SET 
										wftasks_batch_idwftasks_batch='0',
										batch_number='0',
										voucher_number='0'
										WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
										
										//update the countbatch
										$sql_updatecount_old="UPDATE wftasks_batch SET countbatch=(countbatch-1) WHERE idwftasks_batch=".$fet_tktin['wftasks_batch_idwftasks_batch']."";
													
										//log audit 1
										$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
										VALUES ( 'MOVE', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '".$fet_tktin['wftasks_batch_idwftasks_batch']."', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
													
										} else {
										
										$sql_batchtkt="SELECT idtktinPK from tktin LIMIT 1";
										$sql_updatecount_old="SELECT idtktinPK from tktin LIMIT 1";
										
										//log audit 1
										$sql_audit1="INSERT INTO audit_wftasks_batch (action, actionby_idusrac, actionby_idusrrole, tktin_affected, batchid_old, batchid_new, result, browser_session, action_time, user_ip, user_ip_proxy) 
										VALUES ( 'NEW', '".$_SESSION['MVGitHub_idacname']."', '".$_SESSION['MVGitHub_iduserrole']."', '".$fet_ticket['idtktinPK']."', '0', '".$batch_no."', 'OK', '".session_id()."', '".$timenowis."', '".$_SERVER['REMOTE_ADDR']."', '".$realip."')";
										
										}
											
									$res_batchtkt=mysql_query($sql_batchtkt);
									$res_updatecount_old=mysql_query($sql_updatecount_old);
									$res_audit1=mysql_query($sql_audit1);
							
									//check the last batch_no
									$res_batchmeta=mysql_query("SELECT usrteamzone_idusrteamzone,wftasks_batchtype_idwftasks_batchtype FROM wftasks_batch WHERE idwftasks_batch=".$batch_no."");
									$fet_batchmeta=mysql_fetch_array($res_batchmeta);
									//changed to get the last max id given for this batch
						//			$sql_lastbatchno="SELECT max(voucher_number) as countbatch FROM tktin WHERE wftasks_batch_idwftasks_batch=".$batch_no."";
									$sql_lastbatchno="SELECT max(tktin.voucher_number) as countbatch,wftasks_batch.wftasks_batchtype_idwftasks_batchtype,wftasks_batch.batch_year FROM tktin
									INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch
									WHERE wftasks_batch.wftasks_batchtype_idwftasks_batchtype=".$fet_batchmeta['wftasks_batchtype_idwftasks_batchtype']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$fet_batchmeta['usrteamzone_idusrteamzone']."
									AND wftasks_batch.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."  ";//AND YEAR(createdon)='".$this_year."'
									$res_lastbatchno=mysql_query($sql_lastbatchno);
									$fet_lastbatchno=mysql_fetch_array($res_lastbatchno);
									
									//quick validation to avoid crossing over to another year in an older batch
									if ( ($fet_lastbatchno['countbatch']!='') && ($fet_lastbatchno['batch_year']!=$this_year) )
										{
										$error_batchoutdated="<div style=\"color:#ff0000\">You can't assign ".$fet_lastbatchno['batch_year']." in ".$this_year."  </div>";
										//exit;
										}
									
									//create the new batch_no
									$new_batchno=($fet_lastbatchno['countbatch']+1);							
									
									//new update the batch_no meta table
									$sql_updatecount="UPDATE wftasks_batch SET countbatch=(countbatch+1) WHERE idwftasks_batch=".$batch_no."";
									$res_updatecount=mysql_query($sql_updatecount);
									
									//get the tktid to update the tktin as well
									$sql_tktin=mysql_query("SELECT tktin_idtktin FROM wftasks WHERE idwftasks=".$_SESSION['wtaskid']." ");
									$fet_tktin=mysql_fetch_array($sql_tktin);
									
									//update the tkt as well
									$sql_batchtktnew="UPDATE tktin SET 
									wftasks_batch_idwftasks_batch='".$batch_no."',
									batch_number='".$new_batchno."',
									voucher_number='".$new_batchno."'
									WHERE idtktinPK=".$fet_ticket['idtktinPK']."";
									$res_batchtktnew=mysql_query($sql_batchtktnew);
									
									} else { //else if no batch now, then create some dummy queries to run the transction commit succssfully
									////////
									$res_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_lastbatchno=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$sql_tktin=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtktnew=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchtkt=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_updatecount_old=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_audit1=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									$res_batchmeta=mysql_query("SELECT idtktinPK from tktin LIMIT 1");
									/////
									}//batch now
					
					
					if ($fet_task_details['wftaskstrac_idwftaskstrac'] > 0 )
						{
					//redirect to the correct page
							if ( ($query_1) && ($query_2) && ($query_3)  && ($res_tktin) && (!isset($upload_error_1)) && (!isset($upload_error_2)) && (!isset($upload_error_3)) && (!isset($upload_error_4)) && ($res_batchtkt) && ($res_lastbatchno) && ($res_updatecount) && ($sql_tktin) && ($res_batchtktnew) && ($res_batchtkt) && ($res_updatecount_old) && ($res_audit1) && ($res_batchmeta) && (!isset($error_batchoutdated)) )
									{
									mysql_query("COMMIT");	
									///////////////////////////// close check and insert new subscriber ////////////////////////////
									//redirect to the correct page
									?>
									<script language="javascript">
									window.location='go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>';
									</script>
									<?php
									exit;
									} else {
									mysql_query("ROLLBACK");
									?>
                                    <script language="javascript">
									 alert ('Sorry! Please Try Again!');
									</script>
                                    <?php
									if (isset($query_1)) { mysql_free_result($query_1);  }
									if (isset($query_2)) { mysql_free_result($query_2);  }
									if (isset($query_3)) { mysql_free_result($query_3); }
									}
								}
									
							} //if the no error 1_1
									
                    } //close no error on action 9
				
				} //close action 9				
						
		
		} //close form submission

		if (isset($error_1)) { echo $error_1; }
		if (isset($error_2)) { echo $error_2; }
		if (isset($error_3)) { echo $error_3; }
		if (isset($error_4)) { echo $error_4; }
		if (isset($error_1_1)) { echo $error_1_1; }
		if (isset($error_1_2)) { echo $error_1_2; }
		if (isset($error_2_1)) { echo $error_2_1; }
		if (isset($error_2_2)) { echo $error_2_2; }
		if (isset($error_2_3)) { echo $error_2_3; }
		if (isset($error_2_4)) { echo $error_2_4; }
		if (isset($error_3_1)) { echo $error_3_1; }
		if (isset($error_3_2)) { echo $error_3_2; }
		if (isset($error_3_3)) { echo $error_3_3; }
		if (isset($error_3_4)) { echo $error_3_4; }
		if (isset($error_4_1)) { echo $error_4_1; }
		if (isset($error_4_3)) { echo $error_4_3; }
		if (isset($error_4_4)) { echo $error_4_4; }
		if (isset($error_5_1)) { echo $error_5_1; }
		if (isset($error_5_2)) { echo $error_5_2; }
		if (isset($error_6_1)) { echo $error_6_1; }
		if (isset($error_8_1)) { echo $error_8_1; }
		if (isset($error_8_2)) { echo $error_8_2; }
		if (isset($error_9_1)) { echo $error_9_1; }
		if (isset($error_9_2)) { echo $error_9_2; }
		if (isset($upload_error_1)) { echo $upload_error_1; }
		if (isset($upload_error_2)) { echo $upload_error_2; }
		if (isset($upload_error_3)) { echo $upload_error_3; }
		if (isset($upload_error_4)) { echo $upload_error_4.$imageFileType; }
		if (isset($error_batchoutdated)) { echo $error_batchoutdated; }
	?>
    </div>
    <?php
//	echo $fet_task['usrteam_idusrteam']."!=".$_SESSION['MVGitHub_idacteam'];
//check if this is an authorised user

	if ($fet_task['usrteam_idusrteam']!=$_SESSION['MVGitHub_idacteam'])
		{
		echo "<div class=\"msg_warning_small\">".$msg_warn_violation."</div>";
		exit;
		}

	

//	if ($num_task > 0) 
//	{
	?>
    <form method="post" action="" name="task" id="task" autocomplete="off" enctype="multipart/form-data">
	<div >
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        
          <td valign="top" width="50%" class="hline">
            <?php
			//Does this userprofile have permissions to edit/update TICKET VIEWING MODULE
			$sql_permupdate="SELECT permupdate FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=6 AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
			$res_permupdate=mysql_query($sql_permupdate);
			$num_permupdate=mysql_num_rows($res_permupdate);
			$fet_permupdate=mysql_fetch_array($res_permupdate);
		//	echo $sql_permupdate;
		// if any of the following conditions are true, then lock the forms
			if ( 
					($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
				||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
				||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
				||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
				 )
				{
				$field_status=" readonly=\"readonly\" style=\"background-color:#EFEFEF\" ";
				$button_status=" disabled=\"disabled\"";
				} else  {
				if ($num_permupdate > 0)
					{
					if ($fet_permupdate['permupdate']==1)
						{
						$field_status="";
						$button_status="";
						} else if ($fet_permupdate['permupdate']==0) {
						$field_status=" readonly=\"readonly\" style=\"background-color:#EFEFEF\" ";
						$button_status=" disabled=\"disabled\"";
						}
					} else if ($num_permupdate < 1) {
						$field_status=" readonly=\"readonly\" style=\"background-color:#EFEFEF\" ";
						$button_status=" disabled=\"disabled\"";
					}
				
				}

			?>
            <?php
				//ticket feedback SMS
				/*if (isset($new_proc))	
					{
				$sql_feedbacksms="SELECT idtktfeedback,wftskflow_idwftskflow,feedbacksms,feedbackemail FROM tktfeedback WHERE wftskflow_idwftskflow=".$_SESSION['thistskflow']." AND actionstatus=1 LIMIT 1";
					} else {
				$sql_feedbacksms="SELECT idtktfeedback,wftskflow_idwftskflow,feedbacksms,feedbackemail FROM tktfeedback WHERE wftskflow_idwftskflow=".$_SESSION['thistskflow']." AND actionstatus=1 LIMIT 1";					
					}
					*/
				$sql_feedbacksms="SELECT idtktfeedback,wftskflow_idwftskflow,feedbacksms,feedbackemail FROM tktfeedback WHERE wftskflow_idwftskflow=".$_SESSION['thistskflow']." AND actionstatus=1 LIMIT 1";
				$res_feedbacksms=mysql_query($sql_feedbacksms);
				$num_feedbacksms=mysql_num_rows($res_feedbacksms);
				$fet_feedbacksms=mysql_fetch_array($res_feedbacksms);
				//echo $sql_feedbacksms;
					if ($num_feedbacksms > 0)
						{
				?>
            <div >
                         <table border="0" width="100%" cellpadding="2" cellspacing="0">
                    		<tr>
                        	<td width="40%" class="tbl_data">
                            <em><img src="../assets_backend/icons/icon_mobile.png" border="0" align="absmiddle"> <?php echo $lbl_smsmsgtosub;?></em>
                            </td>
                            <td class="tbl_data">
                        	<textarea maxlength="100" style="font-weight:bold" <?php echo $field_status;?> cols="20" rows="2" name="txtsms" id="txtfeedback"><?php if (isset($tktsms)) { echo $tktsms;} /*else { echo $fet_feedbacksms['feedbacksms']; }*/?></textarea>
                        	</td>
							</tr>
							</table>                           
             </div>
                <?php
					} //if SMS feedback is set
				?>                    
                
                 <a href="#" style="text-decoration:none" rel="toggle[details]" data-openimage="../assets_backend/btns/btn_collapse.gif" data-closedimage="../assets_backend/btns/btn_expand.gif">
                <div class="divcol">
                <img src="../assets_backend/btns/btn_collapse.gif" border="0" align="absmiddle" /> <?php echo $lbl_ticketdetails;?>
                </div>
            	</a>

                  <div style="background-color:#FFFFFF" id="details">
                  <?php if (isset($msg_ticket_details))
				  	{
					echo $msg_ticket_details;
					}
					?>
                    <table border="0" width="100%" cellpadding="2" cellspacing="0">
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_ticketno;?></td>
                        <td class="tbl_data">
                        <input type="hidden" value="<?php echo $fet_permupdate['permupdate'];?>" name="up" />
						<input name="tktnumber" type="text" class="small_field" id="tktnumber" style="background-color:#EFEFEF" value="<?php echo $fet_ticket['refnumber'];?>" readonly="readonly" />
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data">Previous Ticket No?
                      <a href="#" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span><?php echo $msg_tip_recurr;?></span></a>
                        </td>
                        <td class="tbl_data">
						<input name="prev_tktnumber" type="text" class="small_field" id="prev_tktnumber" style="background-color:#EFEFEF" value="<?php if(strlen($fet_ticket['refnumber_prev'])>0) { echo $fet_ticket['refnumber_prev']; } ?>" readonly="readonly" />
                        </td>
                    </tr>
                    <tr>
                    	<td class="tbl_data">
                        <?php echo $lbl_tkttype;?>
                        </td>
                        <td class="tbl_data">
                        <?php
						
						echo $fet_ticket['tkttypename'];
						if ($fet_ticket['tkttype_idtkttype']==2) //if public, then show related if they exist
						{
						//count number of similar tickets 
						$sql_similar="SELECT count(*) as simtkts FROM tktin_public WHERE tktin_idtktin=".$fet_ticket['idtktinPK']."";
						$res_similar=mysql_query($sql_similar);
						$fet_similar=mysql_fetch_array($res_similar);
						echo "&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"pop_tickets_pub_drilldown.php\" target=\"_blank\"> Similar Tickets : &nbsp;".$fet_similar['simtkts']."</a>";
						}
						?>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_ticketchn;?></td>
                        <td class="tbl_data">
                        
						<input name="tktchannel" type="text" size="15" value="<?php echo $fet_ticket['tktchannelname'];?>" readonly="readonly" style="background-color:#EFEFEF" class="small_field" />
                        
						</td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_tktcat;?></td>
                      <td  class="tbl_data">
                      <?php //echo $fet_ticket['tktcategory_idtktcategory'];?>
                      <span>
                       <select name="tktcat" class="small_field" id="tktcat" >
                      <?php
					   $sql_cat="SELECT idtktcategory,tktcategory_idtktcategory,tktcategoryname FROM tktcategory WHERE tktcategory_idtktcategory=0";
						$res_cat=mysql_query($sql_cat);
						$num_cat=mysql_num_rows($res_cat);
						$fet_cat=mysql_fetch_array($res_cat);
						
							do {
								
						?>
                        <option <?php if ($fet_ticket['tktcategory_idtktcategory']==$fet_cat['idtktcategory'])  { echo "selected=\"selected\""; } else { echo "disabled=\"disabled\""; }     ?> value="<?php echo $fet_cat['idtktcategory'];?>"><?php echo $fet_cat['tktcategoryname'];?></option>
                        <?php
							} while ($fet_cat=mysql_fetch_array($res_cat));
						?>
                        </select>
                      </span>
                      </td>
                    </tr>
                    <tr>
                        <td width="40%" valign="top" class="tbl_data"><?php echo $lbl_ticketnmsg;?></td>
                      <td class="tbl_data">
                        <textarea cols="20" rows="2" <?php echo $field_status;?> name="txtmsg"  ><?php if (isset($_POST['txtmsg'])) { echo $_POST['txtmsg']; } else { echo $fet_ticket['tktdesc']; }?></textarea>
                      </td>
                    </tr>
                     <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_timereported;?></td>
                        <td class="tbl_data"><?php
        				echo date("D, M d, Y H:i",strtotime($fet_ticket['timereported'])); 
						?></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_deadline_tkt;?></td>
                        <td class="tbl_data"><?php
						if ($fet_ticket['timedeadline'] <= $timenowis)
							{
        					echo "<span class=\"txt_red\">".date("D, M d, Y H:i",strtotime($fet_ticket['timedeadline']))."</span>"; 
							} else {
							echo "<span class=\"txt_green\">".date("D, M d, Y H:i",strtotime($fet_ticket['timedeadline']))."</span>"; 
							}
						?></td>
                    </tr>
                     <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_waterac;?></td>
                        <td class="tbl_data">
                        <input type="text" readonly="readonly" style="background-color:#EFEFEF" <?php echo $field_status;?> class="small_field" value="<?php if (isset($tktacno)) { echo $tktacno; $_SESSION['customer_account_no']=$tktacno; } else { echo $fet_ticket['waterac']; $_SESSION['customer_account_no']=$fet_ticket['waterac']; }?>" name="acnumber" maxlength="20" size="20" />
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_kioskno;?></td>
                        <td class="tbl_data">
                        <input type="text" class="small_field" <?php echo $field_status;?> value="<?php if (isset($tktkiosk)) { echo $tktkiosk; } else { echo $fet_ticket['kioskno']; }?>" name="kiosk" maxlength="20" size="20" />
                        </td>
                    </tr>
                    <tr>
                    	<td></td>
                        <td style="padding:10px 5px">
                        <input <?php echo $button_status;?> type="submit" value="Save" class="text_small" name="update_ticket_details" /></a>
                        </td>
                    </tr>
                    </table>
              </div>
               
               <a href="#" style="text-decoration:none" rel="toggle[contacts]" data-openimage="../assets_backend/btns/btn_collapse.gif" data-closedimage="../assets_backend/btns/btn_expand.gif">
                <div class="divcol">
                <img src="../assets_backend/btns/btn_collapse.gif" border="0" align="absmiddle" /> <?php echo $lbl_contactdetails;?>                </div>
            </a>
            <?php
			if (isset($msg_location_details_ok)) { echo $msg_location_details_ok; }
			
			if (isset($msg_location_details_warn))
				{
				echo "<div class=\"msg_warning_small\">".$msg_location_details_warn;
				if (isset($error_details_1)) { echo $error_details_1; } 
				if (isset($error_details_2)) { echo $error_details_2; } 
				if (isset($error_details_3)) { echo $error_details_3; }
				echo "</div>";
				}
			?>
                  <div style="background-color:#FFFFFF" id="contacts">
                   <table border="0" width="100%" cellpadding="2" cellspacing="0">
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_cname;?></td>
                        <td class="tbl_data"><input <?php echo $field_status;?> name="sendername" type="text" class="small_field" id="sendername" value="<?php if (isset($tktsender)) { echo $tktsender;} else { echo $fet_ticket['sendername']; }?>" size="25" maxlength="80" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_gender;?></td>
                      <td class="tbl_data">
                      <select name="usrgender">
                    <option value="-"  >---</option>
                    <option value="F" <?php if ($fet_ticket['sendergender']=="F") { echo "selected=\"selected\""; } ?>  >Female</option>
                    <option value="M" <?php if ($fet_ticket['sendergender']=="M") { echo "selected=\"selected\""; } ?> >Male</option>
                    </select>
                      </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_mobile;?></td>
                        <td class="tbl_data">
                        <input onKeyUp="res(this,numb);" <?php
						if ($fet_ticket['tktchannel_idtktchannel']<5)
							{
						?> readonly="readonly"  style="background-color:#EFEFEF" <?php }?>  name="senderphone" type="text" class="small_field" id="senderphone" value="<?php echo $fet_ticket['senderphone'];?>" size="20" maxlength="12" /></td>
                    </tr>
                     <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_email;?></td>
                        <td class="tbl_data"><input  name="senderemail" type="text" class="small_field" id="senderemail" value="<?php if (isset($tktsenderemail)) { echo $tktsenderemail;} else { echo $fet_ticket['senderemail']; }?>" size="25" maxlength="70" <?php echo $field_status;?> /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_rdstreet;?></td>
                        <td class="tbl_data"><input <?php echo $field_status;?> name="roadstreet" type="text" class="small_field" id="roadstreet" value="<?php if (isset($tktstreet)) { echo $tktstreet; } else { echo $fet_ticket['road_street']; }?>" size="32" maxlength="80" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_bldestate;?></td>
                        <td class="tbl_data"><input <?php echo $field_status;?> name="building" type="text" class="small_field" id="building" value="<?php if (isset($tktbuilding)) { echo $tktbuilding; } else { echo $fet_ticket['building_estate']; }?>" size="32" maxlength="80" /></td>
                    </tr>
                     <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_unitno;?></td>
                        <td class="tbl_data"><input <?php echo $field_status;?> name="unitnumber" type="text" class="small_field" id="unitnumber" value="<?php if (isset($tktunitno)) { echo $tktunitno; } else { echo $fet_ticket['unitno']; }?>" size="32" maxlength="80" /></td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_location;?></td>
                        <td class="tbl_data">
                        <span style="background-color:#EFEFEF">
						<?php echo $fet_ticket['city_town']; ?>
                        </span>				
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" class="tbl_data"><?php echo $lbl_town_city;?></td>
                        <td class="tbl_data">
                        <input type="text" <?php echo $field_status;?> value="<?php if (isset($tktloc)) { echo $tktloc;} else { echo $fet_ticket['locationname']; } ?>" name="locationtown" id="locationtown" autocomplete="off" maxlength="100" size="32" class="small_field" />
                      </td>
                    </tr>
<tr>
                        <td width="40%" valign="top" class="tbl_data">Directions / Landmark</td>
                        <td class="tbl_data">
                        <textarea name="directions" cols="20" rows="1"><?php echo $fet_ticket['landmark']; ?></textarea>
                        </td>
                    </tr>

                    <tr>
                    	<td></td>
                        <td style="padding:10px 5px">
                        <input type="submit" value="Save" class="text_small" name="update_location_details" /></a>
                        </td>
                    </tr>
                    </table>
              </div>
            
          </td>
          	<td valign="top" width="50%" style="padding:3px 5px; background-image:url(../assets_backend/images/bg_tasks.png); background-repeat:repeat-x">
           <?php
if (isset($_POST['tktcat'])) { $tktcat=trim(mysql_real_escape_string($_POST['tktcat'])); }

	?>
<table border="0" width="100%" cellpadding="2" cellspacing="0" >
<!--
					<tr>
                    	<td height="17" colspan="2" class="divcol"><strong>
                        Action on this Task</strong></td>
			  </tr>
-->                        
   	 				 <tr>
                    	<td width="25%" valign="top" class="tbl_data">
                        <strong><?php echo $lbl_from;?></strong></td>
						<td width="75%" class="tbl_data">
                        <?php
						if ($fet_task['sender_idusrrole']>0)
							{ 
							$sql_sender="SELECT usrrole.usrrolename,usrac.utitle,usrac.lname,usrac.fname,idusrrole,usrac.usrname FROM usrrole,usrac	
							WHERE usrrole.idusrrole=".$fet_task['sender_idusrrole']." AND usrac.idusrac=".$fet_task['sender_idusrac']." LIMIT 1";
							$res_sender=mysql_query($sql_sender);
							$fet_sender=mysql_fetch_array($res_sender);
							echo $fet_sender['usrrolename'] ."<br><small>".$fet_sender['utitle']." ".$fet_sender['fname']." ".$fet_sender['lname']."</small>";
							//hidden field of the sender id
							echo "<input type=\"hidden\" name=\"task_sent_from\" value=\"".$fet_sender['idusrrole']."\" >";
							} else {
							echo $lbl_system;
							}
							//store this usrname account on a session temporarily
							if ($fet_sender['usrname']=="nwc19851") //temporary measure for nancy joel in Eastern Region
								{
								$_SESSION['NoRTS']=" usrname!='".$fet_sender['usrname']."' ";
								} else {
								$_SESSION['NoRTS']=" usrname!='0'";
								}
						?>                        </td>
                </tr>
                <?php
				//you have to query either way just incase it'a  new task without the batch yet
				$res_batch=mysql_query("SELECT idwftasks_batch,tktin.voucher_number,wftasks_batch.batch_no_verbose FROM wftasks 
				INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
				INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch 
				WHERE idwftasks=".$fet_task['idwftasks']." AND tktin.idtktinPK=".$_SESSION['tktin_idtktin']."");					
				$fet_batch=mysql_fetch_array($res_batch);
				$num_batch=mysql_num_rows($res_batch);
					
				//first check if thsi ticket is assigned to a batch
				if ($fet_ticket['wftasks_batch_idwftasks_batch']>0)
					{									
					if ($num_batch > 0)
						{
				?>
                <tr>
                	<td class="tbl_data">
                   <strong>Voucher No.</strong></td>
                    <td class="tbl_data">
                    <span style="background-color:#FFFFFF; font-size:14px; font-weight:bold">
					<?php					
					$voucher_raw=$fet_batch['batch_no_verbose']."/".str_pad($fet_batch['voucher_number'], 4, '0', STR_PAD_LEFT);
					echo str_replace('/',' / ',$voucher_raw);
					?>
                    </span>
                    </td>
                </tr>
                <?php
					} else {
					echo "<tr><td colspan=\"2\"><div class=\"msg_warning_small\">Batch Not Found!</div></td></tr>";
					}
				} //if batch exists
				?>
                  <tr>
                    	<td width="25%" valign="top" class="tbl_data">
                        <strong><?php echo $lbl_action_msg;?></strong></td>
						<td width="75%" valign="top" class="tbl_data" style="padding:0px">
                        <div style="padding:0px">
                        <div class="msg_b" style="padding:5px"><?php
                        echo $fet_task['taskdesc'];
						?>
                        </div>
                        <div style="padding:1px; background-color:#FFFFFF">
                            <div style="padding:2px 0px 2px 6px; font-weight:bold">
                            <small>
                            <?php if( (isset($_SESSION['prevtktid'])) && (isset($_SESSION['prevtskid'])) ) { ?>
                           	    <a href="go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>&task=<?php echo $_SESSION['wtaskid'];?>&tkt_st=<?php echo $_SESSION['prevtktid'];?>&task_st=<?php echo $_SESSION['prevtskid'];?>"> View Task History &raquo;</a>
							<?php } else { ?>     
                            	<a href="go_to_taskhistory.php?tkt=<?php echo $fet_task['tktin_idtktin'];?>&task=<?php echo $_SESSION['wtaskid'];?>"> View Task History &raquo;</a>
                            <?php } ?>
                            </small>                            </div>                           
                        </div>
                        </div>                        </td>
                  </tr>
                  <tr>
             		<td colspan="2" style="padding:0px">
                    <!-- START EXTRA FORMS LOAD HERE -->
                    <?php
                     //DISPLAY THE FORM FOR THIS TASK
                     //but first, determine if the taskflow is zero=0 and if so, get the one to use from the exceptions table
                   /*  if ($fet_task['wftskflow_idwftskflow'] > 0)
                        {
                        $wftskflow=$fet_task['wftskflow_idwftskflow'];
                        } else {
                        $sql_newtskflow="SELECT wftskflow_idwftskflow FROM wftasks_exceptions WHERE wftasks_idwftasks=".$fet_task['wftasks_idwftasks']." LIMIT 1";
                        $res_newtskflow=mysql_query($sql_newtskflow);
                        $fet_newtskflow=mysql_fetch_array($res_newtskflow);
                        
                        $wftskflow=$fet_newtskflow['wftskflow_idwftskflow'];
                        }
			 */
					 //the dataform is determined by
					 //a) userprofileid
					 //b) category of task
					$sql_formdata="SELECT idwfprocassetsaccess, assetname, perm_read, perm_write, perm_required, wfprocassets.wfprocdtype_idwfprocdtype, idwfprocassets, wfprocassetsgrouplbl,sysprofiles_idsysprofiles,wfprocassetsaccess.wfprocforms_idwfprocforms
					FROM wfprocassetsaccess
					INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets = wfprocassets.idwfprocassets
					INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup = wfprocassetsgroup.idwfprocassetsgroup
					INNER JOIN wfprocforms_cats ON wfprocassetsaccess.wfprocforms_idwfprocforms = wfprocforms_cats.wfprocforms_idwfprocforms 
					WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocforms_cats.tktcategory_idtktcategory=".$fet_ticket['tktcategory_idtktcategory']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
		/*			 $sql_formdata="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets,wfprocassetsgrouplbl FROM wfprocassetsaccess 
					 INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
					 INNER JOIN wfprocassetsgroup ON wfprocassets.wfprocassetsgroup_idwfprocassetsgroup=wfprocassetsgroup.idwfprocassetsgroup
					 WHERE wftskflow_idwftskflow=".$wftskflow." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassetsgrouplbl ASC,ordering ASC";
		*/			 //echo $sql_formdata;
					 $res_formdata=mysql_query($sql_formdata);
					 $num_formdata=mysql_num_rows($res_formdata);
					 $fet_formdata=mysql_fetch_array($res_formdata);
					//echo $sql_formdata;
					 $lastTFM_nest = ""; //for nesting
					//echo $sql_formdata;

				 if ($num_formdata > 0)
					{ //[001]
				/*	//process if form fields are required
					if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="process_task") )
						{
						//echo $_POST['formaction'];
						//echo "processed <br>";
						//check the db for this field by reusing the sql statement above
						$sql_val="SELECT idwfprocassetsaccess,assetname,perm_read,perm_write,perm_required,wfprocassets.wfprocdtype_idwfprocdtype,idwfprocassets FROM wfprocassetsaccess 
						INNER JOIN wfprocassets ON wfprocassetsaccess.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
						WHERE sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." AND wfprocassetsaccess.perm_read=1 ORDER BY wfprocassets.ordering ASC";
						$res_val=mysql_query($sql_val);
						$num_val=mysql_num_rows($res_val);
						$fet_val=mysql_fetch_array($res_val);
					
					//process if the form fields have values
						}*/
	
				echo "<input type=\"hidden\" name=\"formdata_available\" value=\"1\">";				
				 ?>
                    <?php
			$dmn=1;

			do 	{
				$TFM_nest = $fet_formdata['wfprocassetsgrouplbl'];
				
				if ($lastTFM_nest != $TFM_nest) 
					{ 
					$lastTFM_nest = $TFM_nest; 
					
					 if ($dmn>1)
						{
						echo "</div>";
						}
					?>	
            
                    <a href="#" style="text-decoration:none" rel="toggle[dataform<?php echo $dmn;?>]" data-openimage="../assets_backend/btns/btn_collapse.gif" data-closedimage="../assets_backend/btns/btn_expand.gif">
                    <div class="divcol">
                    <img src="../assets_backend/btns/btn_collapse.gif" border="0" align="absmiddle" /> <?php echo $fet_formdata['wfprocassetsgrouplbl'];?>                    </div>
                    </a>     
                    
                	<div id="dataform<?php echo $dmn;?>"> 
		            <?php 

					$dmn=$dmn+1;
					} //End of Basic-UltraDev Simulated Nested Repeat?>
                                
                    <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="25%" class="tbl_data">
                        <?php
                        if ($fet_formdata['perm_required']==1) 
                        {
                        echo $lbl_asterik;
                        }
                        echo $fet_formdata['assetname'];
                        ?>
                        </td>
                      <td class="tbl_data">
                        <?php
						if (
							($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole']) &&
							($fet_task['usrrole_idusrrole']!=$fet_delegated['idusrrole_from']) && 
							( (!isset($is_group_task)) || ($is_group_task==0) ) &&
							($num_co == 0)
								)
								{
								$lock_values=1;
								} else {
								$lock_values=0;
								}
                        //retreieve the date from the db
                        //check the db for values for this field /$fet_task['idwftasks']
                        
                        /*if ( ($fet_formdata['wfprocdtype_idwfprocdtype']==1) || ($fet_formdata['wfprocdtype_idwfprocdtype']==5) || ($fet_formdata['wfprocdtype_idwfprocdtype']==6) || ($fet_formdata['wfprocdtype_idwfprocdtype']==7))
                            {
                            $sql_data="SELECT idwfassetsdata,value_choice,wfprocassetschoice_idwfprocassetschoice FROM wfassetsdata WHERE wfprocassetsaccess_idwfprocassetsaccess=".$fet_formdata['idwfprocassetsaccess']." AND wftasks_idwftasks=".$fet_task['idwftasks']." LIMIT 1";
                            } else if ($fet_formdata['wfprocdtype_idwfprocdtype']==2) { //menulist
                            $sql_data="SELECT idwfassetsdata,value_choice,wfprocassetschoice_idwfprocassetschoice FROM wfassetsdata WHERE wfprocassetsaccess_idwfprocassetsaccess=".$fet_formdata['idwfprocassetsaccess']." AND wftasks_idwftasks=".$fet_task['idwftasks']." LIMIT 1";
                            }
                            */
    //						$sql_data="SELECT idwfassetsdata,value_choice,wfprocassetschoice_idwfprocassetschoice FROM wfassetsdata WHERE wfprocassetsaccess_idwfprocassetsaccess=".$fet_formdata['idwfprocassetsaccess']." AND wftasks_idwftasks=".$fet_task['idwftasks']." LIMIT 1";	
                        /*	$sql_data="SELECT idwfassetsdata,value_choice,wfprocassetschoice_idwfprocassetschoice FROM wfassetsdata 
                            INNER JOIN wfprocassets ON wfassetsdata.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets
                            WHERE wfprocassets.wfproc_idwfproc=".$_SESSION['wfproc_idwfproc']." 
                            AND wfprocassets.wfprocdtype_idwfprocdtype=".$fet_formdata['wfprocdtype_idwfprocdtype']."
                            AND wftaskstrac_idwftaskstrac=".$_SESSION['wftaskstrac']."
                            LIMIT 1";
                            */
                            $sql_data="SELECT idwfassetsdata,value_choice,value_path,wfprocassetschoice_idwfprocassetschoice,date(wfassetsdata.createdon) as asset_date FROM wfassetsdata 
                            INNER JOIN wfprocassets ON wfassetsdata.wfprocassets_idwfprocassets=wfprocassets.idwfprocassets 
                            WHERE wfprocassets.wfprocforms_idwfprocforms=".$fet_formdata['wfprocforms_idwfprocforms']."
                            AND wfprocassets.wfprocdtype_idwfprocdtype=".$fet_formdata['wfprocdtype_idwfprocdtype']."
                            AND wfassetsdata.wfprocassets_idwfprocassets=".$fet_formdata['idwfprocassets']."
                            AND wftaskstrac_idwftaskstrac=".$_SESSION['wftaskstrac']."
                            LIMIT 1";
                            
                            $res_data=mysql_query($sql_data);
                            $num_data=mysql_num_rows($res_data);
                            $fet_data=mysql_fetch_array($res_data);
//echo $sql_data."<br>";
                            //determine logic if update or delete depending on whether there is value in the db
                            if ($num_data > 0)
                                {
                                $transaction="UPDATE";
                                } else {
                                $transaction="INSERT";
                                }
    
                            //check for the primary key if record exists
                            if ( (isset($fet_data['idwfassetsdata'])) && ($fet_data['idwfassetsdata']>0) )
                                {
                                $idassetdata=$fet_data['idwfassetsdata'];
                                } else {
                                $idassetdata=0;
                                }	
                        //	echo $fet_data['idwfassetsdata'];
                        
                         //this is a text box
                         if ($fet_formdata['wfprocdtype_idwfprocdtype']==1) 
                            {
                            //check permissions
                        //	echo $fet_formdata['perm_write'];
                            if (
								($fet_formdata['perm_write']==0) 
								|| ($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
								||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
								||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
								||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
								)
                                {	
                                $readonly="readonly=\"readonly\" style=\"background-color:#f4f4f4\" ";
                                } else {
                                $readonly="";
                                }
                            
                            echo "<input type=\"text\" ".$readonly." ";
                            //check if it is a post
                                if (isset($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']))
                                    {
                                    echo " value=\"".$_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']."\" ";
                                    } else {
                                    echo " value=\"".$fet_data['value_choice']."\" ";
                                    }
                                //highlight document show if error
                            //	if (isset($error."_".$fet_formdata['idwfprocassetsaccess']))
                                if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                                                        
                            echo " name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" maxlength=\"50\">";
                            
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                            <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                            <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                            <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                            ";
                            
							 //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}
							}
                         
						   
						   
							
                        //select menu	
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==2) 
                            {
							
							if (
								($fet_formdata['perm_write']==0) 
								|| ($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
								||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
								||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
								||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
								)
                                {	
                                $readonly="disabled=\"disabled\" style=\"background-color:#f4f4f4\" ";
                                } else {
                                $readonly="";
                                }
                                                    
                            $sql_choices="SELECT idwfprocassetschoice,assetchoice FROM wfprocassetschoice WHERE wfprocassets_idwfprocassets=".$fet_formdata['idwfprocassets']."";
                            $res_choices=mysql_query($sql_choices);
                            $fet_choices=mysql_fetch_array($res_choices);
                        //echo $sql_choices;
						//echo $fet_formdata['idwfprocassets'];	
                            echo "<select name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" ";
                                
                                if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                            
                            echo "  >";
                            //get the select options data
                            echo "<option value=\"\" ".$readonly.">---</option>";
                                do {
                                    
                                    echo "<option ";
                                        //select the default if there is a value
                                        if ( (isset($fet_data['idwfassetsdata'])) && ($fet_data['wfprocassetschoice_idwfprocassetschoice']==$fet_choices['idwfprocassetschoice']) )
                                            {
                                            echo " selected=\"selected\" ";
                                            } else {
                                            echo  $readonly;
                                            }
                                    echo " value=\"".$fet_choices['idwfprocassetschoice']."\">".$fet_choices['assetchoice']."</option>";
                                } while ($fet_choices=mysql_fetch_array($res_choices));
                            echo "</select>";						
                            
                            //if readonly is set, then place a hidden value
                            if (strlen($readonly)>3)
                                {
                                echo "<input type=\"hidden\" name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_choices['idwfprocassetschoice']."\">";
                                }
                                
                                echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                                ";
                            
							 //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}
								
							}
							
							
                        
                        //file upload
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==3) 
                            {
                            //check permissions
                            if ($fet_formdata['perm_write']==0) 
                                {	
                                $readonly="disabled=\"disabled\"  title=\"You cannot replace this file\" ";
                                } else {
                                $readonly="";
                                }
							
							//display the file if it exists and if read permissions exist
							 if (($fet_formdata['perm_read']==1)  && (strlen($fet_data['value_path'])>4) )
                                {
								//get the file name explode by _ and get the first array 0
								//$file_path=explode('/',$fet_data['value_path']);
								$count_tkt=(strlen($_SESSION['tktin_idtktin'])+1);
								//$file_actual=explode('_',$fet_data['value_path']);
								echo "<div style=\"padding:5px 0px;\"><a class=\"thickbox\" href=\"download_file.php?f=".$idassetdata."&amp;i=".$fet_data['asset_date']."&amp;keepThis=true&TB_iframe=true&height=100&width=780&inlineId=hiddenModalContent&modal=false\"><img align=\"absmiddle\" border=\"0\" src=\"../assets_backend/btns/btn_download_small.jpg\" title=\"".$fet_data['value_path']."\">&nbsp;&nbsp;".substr($fet_data['value_path'],$count_tkt,50)."</a></div>";
								}
								
							//if there is a file, then warn user that he could replace this file
							if ( (strlen($fet_data['value_path'])>1) && ($fet_formdata['perm_write']==1) )
								{
								echo "Replace this Document ? &raquo;&nbsp;";
								}		
                            //show the following only if you have write permissions otherwise disable	
                            echo "<input type=\"file\" ".$readonly."  ";
                            
                            if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                            
                            echo " name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" size=\"10\"  >";
                            echo "<span style=\"cursor:pointer;color:red;\" onclick=\"document.task.item_".$fet_formdata['idwfprocassetsaccess'].".value=''\">unselect</span>";
                            
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                                ";
                            
                             //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}
                            
                            }
                            
                        //checkbox
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==4) 
                            {
                            
							if (
								($fet_formdata['perm_write']==0) 
								|| ($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
								||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
								||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
								||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
								)
                                {	
                                $readonly=1;
                                } else {
                                $readonly=0;
                                }
							
							if ((isset($fet_data['wfprocassetschoice_idwfprocassetschoice'])) && ($fet_data['value_choice']=="1"))
                                    {
                                    $value_chkbox=1;
                                    } else {
									$value_chkbox=0;
									}
							//echo $readonly;		
							echo "<label for=\"".$fet_formdata['idwfprocassetsaccess']."\">";
							
							//check if the persion has permission to edit the checkbox to know what to show 
							if ($readonly==1)
								{
								
								 echo "<input type=\"hidden\" value=\"".$value_chkbox."\" name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" id=\"".$fet_formdata['idwfprocassetsaccess']."\" value=\"1\">";
                            	
									if ($value_chkbox==1)
										{
										echo "<img border=\"0\" title=\"Edit Disabled\" align=\"absmiddle\" src=\"../assets_backend/icons/icon_fchkbox_on.png\">";
										} else {
										echo "<img border=\"0\"  title=\"Edit Disabled\"  align=\"absmiddle\" src=\"../assets_backend/icons/icon_fchkbox_off.png\">";
										}
									
								} else {
                           		 
								 echo "<input type=\"checkbox\" ";
                                	if ((isset($fet_data['wfprocassetschoice_idwfprocassetschoice'])) && ($fet_data['value_choice']=="1"))
                                    	{
	                                    echo " checked=\"checked\" ";
    	                                }
        			                    echo " name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" id=\"".$fet_formdata['idwfprocassetsaccess']."\" value=\"1\"> <small>( click to select )</small>";
                            
								}
							
                            
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                                ";
                            echo "</label>";
							
							 //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}
							
                            }
                        
                        
                        //yes no questions
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==5) 
                            {	
    						
							if (
								($fet_formdata['perm_write']==0) 
								|| ($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
								||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
								||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
								||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
								)
                                {	
                                $readonly=1;
                                } else {
                                $readonly=0;
                                }
							
							if ($readonly==0)
								{
                            	echo "<label for=\"radio_1\"><input id=\"radio_1\" ";
                            	if ((isset($fet_data['value_choice'])) && ($fet_data['value_choice']=="YES"))
                             	   {
                              	 	 echo " checked=\"checked\" ";
                               	 	}
                           		 echo " type=\"radio\" value=\"YES\" name=\"item_".$fet_formdata['idwfprocassetsaccess']."\"><strong> YES </strong></label>";
                           		 echo "<span style=\"padding:0px 15px 0px 15px\"></span>";
								                           
							
								echo "<label for=\"radio_2\"><input id=\"radio_2\" type=\"radio\" ";
    	                            if ((isset($fet_data['value_choice'])) && ($fet_data['value_choice']=="NO"))
        	                        {
            	                    echo " checked=\"checked\" ";
                	                }
                    	        echo " value=\"NO\" name=\"item_".$fet_formdata['idwfprocassetsaccess']."\"><strong> NO </strong></label>";
								
								} else {
								
								 if ($fet_data['value_choice']=="YES")
								 	{
									 echo "<img src=\"../assets_backend/icons/icon_radio_on.png\" border=\"0\" align=\"absmiddle\" > <strong>YES</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									 echo "<img src=\"../assets_backend/icons/icon_radio_off.png\" border=\"0\" align=\"absmiddle\" > <strong>NO</strong>";
									 } else if ($fet_data['value_choice']=="NO") {
									 echo "<img src=\"../assets_backend/icons/icon_radio_off.png\" border=\"0\" align=\"absmiddle\" > <strong>YES</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
									 echo "<img src=\"../assets_backend/icons/icon_radio_on.png\" border=\"0\" align=\"absmiddle\" > <strong>NO</strong>";
									 }
								
								
								}
    
                                echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                                ";
                            
							 //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}
							
                            }
                        
                        //date only
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==6) 
                            {
							
							if (
								($fet_formdata['perm_write']==0) 
								|| ($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
								||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
								||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
								||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
								)
                                {	
                                $readonly_off="";
								$readonly_click="";
								$readonly_style=" style=\"background-color:#f4f4f4\" ";
                                } else {
                                $readonly_off="<script language=\"javascript\">
                                $('#item_".$fet_formdata['idwfprocassetsaccess']."').datepicker({
                                    controlType: 'select',
                                    dateFormat: 'dd/mm/yy'
                                });
                                </script>";
								$readonly_click=" onClick=\"datetimepicker('item_".$fet_formdata['idwfprocassetsaccess']."')\"; ";
								$readonly_style=" ";
                                }
							
							
                            echo "<input size=\"10\" ";
                                if (isset($fet_data['value_choice']))
                                {
                                echo " value=\"".$fet_data['value_choice']."\" ";
                                } else {
                                echo " value=\"\" ";
                                }
                            
                            //display if value missing
                            if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                            
                            echo  $readonly_click." ".$readonly_style."  name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" type=\"text\" id=\"item_".$fet_formdata['idwfprocassetsaccess']."\"  readonly=\"readonly\" >" ;					
                            echo $readonly_off;
                                
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                                ";	
                             //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}    
								
                            }
                        
                        
                        //date & time 
                        if ($fet_formdata['wfprocdtype_idwfprocdtype']==7) 
                            {
							
							if (
								($fet_formdata['perm_write']==0) 
								|| ($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
								||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
								||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
								||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
								)
                                {	
                                $readonly_off="";
								$readonly_click="";
								$readonly_style=" style=\"background-color:#f4f4f4\" ";
                                } else {
                                $readonly_off="<script language=\"javascript\">
                                $('#item_".$fet_formdata['idwfprocassetsaccess']."').datetimepicker({
                                        controlType: 'select',
                                        timeFormat: 'hh:mm tt',
                                        dateFormat: 'dd/mm/yy'
                                });
                                </script>";	
								$readonly_style="";
								$readonly_click=" onClick=\"datetimepicker('item_".$fet_formdata['idwfprocassetsaccess']."')\"; ";
                                }
							
                            echo "<input size=\"25\" ";
                            
                            if (isset($fet_data['value_choice']))
                                {
                                echo " value=\"".$fet_data['value_choice']."\" ";
                                } else {
                                echo " value=\"\" ";
                                }
                            
                            //show if required value missing
                            if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
                                    
                            echo  $readonly_click." ".$readonly_style." name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" type=\"text\" id=\"item_".$fet_formdata['idwfprocassetsaccess']."\" readonly=\"readonly\" >" ;
                                
                                echo $readonly_off;	

                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                                <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                                <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                                <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                                ";	
                              //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								} 
							            
                            }
                        
                        
                         //this is a text box (numbers only)
                         if ($fet_formdata['wfprocdtype_idwfprocdtype']==8) 
                            {
                            //check permissions
                        //	echo $fet_formdata['perm_write'];
                           if (
						   		($fet_formdata['perm_write']==0) 
								|| ($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
								||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
								||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
								||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
								) 
                                {	
                                $readonly="readonly=\"readonly\" style=\"background-color:#f4f4f4\" ";
                                } else {
                                $readonly="";
                                }											
                            
                            echo "<input onKeyUp=\"res(this,numb);calculate_".$fet_calc['result_id']."();\" type=\"text\" ".$readonly." ";
                            
                            if (isset($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']))
                                {
                                echo " value=\"".$_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']."\" ";
                                } else {
                                echo " value=\"".$fet_data['value_choice']."\" ";
                                }
                                
                            //show if value is missing
                            if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }	
                                
                            echo " name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" maxlength=\"50\">";
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                            <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                            <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                            <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                            ";
							
							 //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}
								
                            }
                            
                            
                            //this are approvals values
                      if ($fet_formdata['wfprocdtype_idwfprocdtype']==9) 
					 	{
						//check permissions
					//	echo $fet_formdata['perm_write'];
						
						
						$sql_choicesapprovals="SELECT idwfprocdtype_approvals,wfprocdtype_approvalslbl FROM wfprocdtype_approvals ";
						$res_choicesapprovals=mysql_query($sql_choicesapprovals);
						$fet_choicesapprovals=mysql_fetch_array($res_choicesapprovals);
						//echo $sql_choices;	
						
						echo "<select name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" ";
						
						//show if required value is missing
						if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
								{
								echo " style=\"border:1px solid #ff0000\" ";
								}
						
						echo " >";
						//get the select options data
						if (!isset($fet_data['value_choice']))
							{
							echo "<option value=\"\">---</option>";
							}
							
							do {
								
								echo "<option ";
									//select the default if there is a value
									if ( (isset($fet_data['idwfassetsdata'])) && ( ($fet_data['value_choice']==$fet_choicesapprovals['idwfprocdtype_approvals']) ) || ( (isset($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']==$fet_choicesapprovals['idwfprocdtype_approvals']) ) )
										{
										echo " selected=\"selected\" ";
									//	} else {
									//	echo " disabled=\"disabled\" ";
										}	
										
									if (($fet_data['value_choice']!=$fet_choicesapprovals['idwfprocdtype_approvals']) && ($fet_formdata['perm_write']==0) || ($fet_ticket['tktstatus_idtktstatus']>3) || ($lock_values==1)  ) 
										{	
										echo "disabled=\"disabled\" ";
										} 
																		
								echo " value=\"".$fet_choicesapprovals['idwfprocdtype_approvals']."\">".$fet_choicesapprovals['wfprocdtype_approvalslbl']."</option>";
							} while ($fet_choicesapprovals=mysql_fetch_array($res_choicesapprovals));
						echo "</select>";						
						
						//if readonly is set, then place a hidden value
						/*if (strlen($readonly)>3)
							{
							echo "<input type=\"hidden\" name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_choicesapprovals['idwfprocassetschoice']."\">";
							}*/
						
						echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
						<input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
						<input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
						<input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
						";
						
						 //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a title=\"Audit Trail\" href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}
						
						}
                        
						
						
						 //this is a text area
                         if ($fet_formdata['wfprocdtype_idwfprocdtype']==10) 
                            {
                            //check permissions
                        //	echo $fet_formdata['perm_write'];
                            if (
								($fet_formdata['perm_write']==0) 
								|| ($fet_task['wftskstatustypes_idwftskstatustypes']==1) 
								||  ($fet_task['wftskstatustypes_idwftskstatustypes']==4) 
								||  ($fet_task['usrac_idusrac']!=$_SESSION['MVGitHub_idacname']) 
								||  ($fet_task['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
								)
                                {	
                                $readonly="readonly=\"readonly\" style=\"background-color:#f4f4f4\" ";
                                } else {
                                $readonly="";
                                }
                            
                            echo "<textarea ".$readonly."  name=\"item_".$fet_formdata['idwfprocassetsaccess']."\" maxlength=\"450\" ";
                                //highlight document show if error
                            //	if (isset($error."_".$fet_formdata['idwfprocassetsaccess']))
                                if ( (isset($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].''])) && ($_POST['required_'.$fet_formdata['idwfprocassetsaccess'].'']==1) &&  ($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']=="") )
                                    {
                                    echo " style=\"border:1px solid #ff0000\" ";
                                    }
							echo ">";
							if (isset($_POST['item_'.$fet_formdata['idwfprocassetsaccess'].'']))
                                    {
                                    echo $_POST['item_'.$fet_formdata['idwfprocassetsaccess'].''];
                                    } else {
                                    echo $fet_data['value_choice'];
                                    }                   
                            echo "</textarea>";
                            
                            echo "<input type=\"hidden\" name=\"required_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['perm_required']."\">\r\n
                            <input type=\"hidden\" name=\"transtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$transaction."\" > \r\n
                            <input type=\"hidden\" name=\"itemtype_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$fet_formdata['wfprocdtype_idwfprocdtype']."\">
                            <input type=\"hidden\" name=\"itempk_".$fet_formdata['idwfprocassetsaccess']."\" value=\"".$idassetdata."\">
                            ";
                            
							 //audit trail check for this value
							$res_auditcheck=mysql_query('SELECT idwfassetsdata FROM audit_wfassetsdata WHERE idwfassetsdata='.$idassetdata.' LIMIT 1');
							$num_auditcheck=mysql_num_rows($res_auditcheck);
							if ($num_auditcheck>0)
								{
								echo "<a href=\"pop_audit_xforms.php?fvid=".$idassetdata."&amp;&amp;tabview=1&amp;tabview=1&keepThis=true&TB_iframe=true&height=300&width=600&inlineId=hiddenModalContent&modal=false\" class=\"thickbox\" href=\"pop_audit_xforms.php\"><img src=\"../assets_backend/btns/btn_at.jpg\" border=\"0\" align=\"absmiddle\"></a>";
								}
							
							}
						
						
						
                        ?>
                        </td>
                        </tr>
                        </table>
                
             		<?php			
				 	} while ($fet_formdata=mysql_fetch_array($res_formdata));
				} //[001] close if num > 0
			//close after checking
				?>

                    <!-- END EXTRA FORM  -->
                    </td>
              </tr>

                 <?php
				
				
				//ensure you have not delegated tasks out
				if ($_SESSION['delegated']==0) 
				 	{
				 ?>
                  <tr>
                    	<td width="25%" class="tbl_data" >
                        <strong><img src="../assets_backend/images/arrow_red.png" width="8" align="absmiddle" height="10"> Appropriate Action<?php // echo $lbl_action;?></strong></td>
                        <td class="tbl_data">
			<?php
			
			if (($fet_task['wftskstatustypes_idwftskstatustypes']==1) || ($fet_task['wftskstatustypes_idwftskstatustypes']==4)) //if task is closed then halt
				{
				echo "<strong>".$lbl_closedtask."</strong>";
				
				} else {
					

					//ensure first that this task it not an exception before running the query checks below
					//if  ( ($fet_task['sender_idusrrole']!=2) && ($fet_task['wftskflow_idwftskflow']>0) ) //if it is not sent by a customer, then
					if ( ($fet_task['sender_idusrrole']!=2) && ($fet_task['wftskflow_idwftskflow']!=0) ) //if $fet_task['sender_idusrrole']!=2
							{
							
							//creation of the menu if there are valid permissions at THIS STEP
							$sql_listactions="SELECT wftskstatustype,idwftskstatustypes,wftskstatustypedesc,idwftskstatus FROM wftskstatustypes
							INNER JOIN wftskstatus ON wftskstatustypes.idwftskstatustypes=wftskstatus.wftskstatustypes_idwftskstatustypes 
							WHERE wftskflow_idwftskflow=".$fet_task['wftskflow_idwftskflow']." AND idwftskstatustypes!=8 AND is_visible=1 ORDER BY wftskstatustypes.listorder ASC";
							$res_listactions=mysql_query($sql_listactions);
							$fet_listactions=mysql_fetch_array($res_listactions);
							$num_listactions=mysql_num_rows($res_listactions);
							//echo "<span style=\"color:#ffffff\">1</span>";
//							echo $sql_listactions;
							//check the last place this task is and which role has that task
							//check the previous task - if it was a Request For Transfer RFT
							$sql_rft="SELECT wftskstatustypes_idwftskstatustypes FROM wftasks WHERE idwftasks=".$fet_task['wftasks_idwftasks']." AND wftskstatustypes_idwftskstatustypes=5 LIMIT 1";
							$res_rft=mysql_query($sql_rft);
							$num_rft=mysql_num_rows($res_rft);
							
							if ($num_rft > 0 )	//if RFT is 5, then go ahead and create menu for Transfer 
								{
								$sql_transmenu="SELECT idwftskstatustypes,wftskstatustype FROM wftskstatustypes WHERE idwftskstatustypes=3";
								$res_transmenu=mysql_query($sql_transmenu);
								$fet_transmenu=mysql_fetch_array($res_transmenu);
								
								$transfer_menu="<option value=".$fet_transmenu['idwftskstatustypes'].">".$fet_transmenu['wftskstatustype']."</option>";
								
								} else {
								$transfer_menu="";
								}
								
							if (isset($fet_task['listorder']))
								{
								//transfer back to sender checker
								//check if this task should be allowed
								$sql_returnto="SELECT wftasks.usrrole_idusrrole,wftasks.wftskflow_idwftskflow,wftskflow.listorder,wftasks.sender_idusrrole,utitle,usrrole.usrrolename,fname,lname FROM wftasks 
								INNER JOIN wftskflow ON wftasks.wftskflow_idwftskflow=wftskflow.idwftskflow
								INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole
								INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
								WHERE wftskflow.listorder < '".$fet_task['listorder']."'
								AND wftskflow.wfproc_idwfproc=".$fet_task['wfproc_idwfproc']."  
								AND wftasks.wftaskstrac_idwftaskstrac=".$fet_task['wftaskstrac_idwftaskstrac']." 
								AND ((wftasks.wftskstatustypes_idwftskstatustypes=0) OR (wftasks.wftskstatustypes_idwftskstatustypes=2)) 
								ORDER BY wftskflow.listorder DESC LIMIT 1";
								$res_returnto=mysql_query($sql_returnto);
								$fet_returnto=mysql_fetch_array($res_returnto);
								$num_returnto=mysql_num_rows($res_returnto);
								}
								
					
						} else { //else if it is an exception, then just create some default menus from the system
						
							$sql_listactions="SELECT wftskstatustype,idwftskstatustypes,wftskstatustypedesc FROM wftskstatustypes
							WHERE (idwftskstatustypes=1 OR idwftskstatustypes=2 OR idwftskstatustypes=4 OR idwftskstatustypes=6 OR idwftskstatustypes=9) AND is_visible=1 ORDER BY wftskstatustypes.listorder ASC";
							$res_listactions=mysql_query($sql_listactions);
							$fet_listactions=mysql_fetch_array($res_listactions);
							$num_listactions=mysql_num_rows($res_listactions);
							//echo "<span style=\"color:#ffffff\">2</span>";
							//where is the task at	
							/*$sql_taskat="SELECT usrrole_idusrrole,wftaskstrac_idwftaskstrac FROM wftasks WHERE wftaskstrac_idwftaskstrac=".$fet_task['wftaskstrac_idwftaskstrac']." ORDER BY idwftasks DESC LIMIT 1";
							$res_taskat=mysql_query($sql_taskat);
							$fet_taskat=mysql_fetch_array($res_taskat);*/
							//echo $sql_taskat."<br>";
						}
					//construct the ACTION DROP DOWN MENU condition		
					/*if ( $num_delegated > 0 )
						{
						$qry_recepient = " ( (".intval($fet_task['usrrole_idusrrole']==$_SESSION['MVGitHub_iduserrole']).") || (".intval($fet_task['usrrole_idusrrole']==$fet_delegated['idusrrole_from']).") ) ";
						$delegated_to_me = 1;
						} else {
						$qry_recepient = " (".$fet_task['usrrole_idusrrole']==$_SESSION['MVGitHub_iduserrole'].") ";
						$delegated_to_me = 0;
						}	
					*/	
					//if (($num_listactions > 0) && $qry_recepient) //$qry_recepient has been constructed at the top of this document
					//echo "<span style=\"color:#ffffff\">if (".$num_listactions.">0) && (".$fet_taskat['usrrole_idusrrole']."==".$_SESSION['MVGitHub_iduserrole'].") || (".$fet_task['usrrole_idusrrole']."==".$fet_delegated['idusrrole_from'].")</span><br>";
					
/*					echo "if (
						(".$num_listactions." > 0) && 
						((".$fet_taskat['usrrole_idusrrole']."==".$_SESSION['MVGitHub_iduserrole'].") || 
						(".$fet_task['usrrole_idusrrole']."==".$fet_delegated['idusrrole_from'].") || 
						((isset(".$is_group_task.")) && (".$is_group_task." > 0) ) ) 
						)";
*/
					
					if (
						($num_listactions > 0) && 
						(  ($fet_taskat['usrrole_idusrrole']==$_SESSION['MVGitHub_iduserrole']) 
						|| ($fet_task['usrrole_idusrrole']==$fet_delegated['idusrrole_from']) 
						|| ((isset($is_group_task)) && ($is_group_task > 0))  ) 
						)
												
						{	
						?>
						<select name="action_to" class="switchaction" id="action_msg"  >
						<option value="0">---</option>
					<?php do { ?>
                    	<option <?php if ( ($fet_listactions['idwftskstatustypes']==8) || ($fet_listactions['idwftskstatustypes']==9) ) { echo "disabled=\"disabled\""; } // disable Assign(8) and RTS(9) ?> value="<?php echo $fet_listactions['idwftskstatustypes'];?>"><?php echo $fet_listactions['wftskstatustype'];?></option>
					<?php } while ($fet_listactions=mysql_fetch_array($res_listactions)) ;?>
                    
					<?php if (isset($transfer_menu)) { echo $transfer_menu; } //show the extra Transfer menu for RFT tickets if set
							if ( (isset($num_returnto)) && ($num_returnto> 0)  )
								{
						?>
						<option <?php //if sender is customer, then u cannot return to customer
						/* if ( ($fet_returnto['usrrole_idusrrole']==$_SESSION['MVGitHub_iduserrole']) || ($fet_returnto['usrrole_idusrrole']==2)  ) { echo "disabled=\"disabled\""; }*/ echo "disabled=\"disabled\"";  ?> value="9">** Return to Sender! ** </option>
							<?php
								}
							?>
                    </select>
						<?php } else {
						$sql_itswith="SELECT usrrolename,utitle,lname FROM usrrole 
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE usrrole.idusrrole=".$fet_taskat['usrrole_idusrrole']." ";
						//echo $sql_itswith;
						$res_itswith=mysql_query($sql_itswith);
						$fet_itswith=mysql_fetch_array($res_itswith);
						//echo $sql_itswith;
						echo "<small>Currently assigned to:</small><br><strong> ".$fet_itswith['usrrolename']." (".$fet_itswith['utitle']." ".$fet_itswith['lname'].")</strong>";
						 } 
						  
					}  //close if task is closed
					
				/*	echo 
					"(".$num_listactions." > 0) && 
						(  (".$fet_taskat['usrrole_idusrrole']."==".$_SESSION['MVGitHub_iduserrole'].") 
						|| (".$fet_task['usrrole_idusrrole']."==".$fet_delegated['idusrrole_from'].") 
						|| ((isset(".$is_group_task.")) && (".$is_group_task." > 0))  ) 
						)";
						echo "<br>".$fet_task['usrrole_idusrrole']."---".$fet_task['usrac_idusrac'];
				*/	
					?>				</td>
				</tr>
                <?php
				} else { //else if delegated out, give this message
				?>
                <tr>
                	<td colspan="2">
                    <div class="msg_warning_small">
                    You cannot action on this tasks because you have delegated all your Tasks out to <?php echo $_SESSION['delegated_to'];?>                    </div>                    </td>
                </tr>
                <?php
				} //close delegated OUT
				?>
                
                    <tr>
                        <td valign="top" colspan="2" style="padding:0px; margin:0px" align="left">
                        
<div class="scroll-content">

	<div class="actionlist 1" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_1"><?php if (isset($tkttskmsg1)) { echo $tkttskmsg1; }?></textarea>
            <?php

               /*             $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_1') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg1)) 
							{
							$oFCKeditor->Value =$tkttskmsg1 ;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <tr>
        	<td class="tbl_data"></td>
            <td class="tbl_data">
            <label for="1"><input type="checkbox" value="1" name="close_1" id="1" /><strong> <?php echo $lbl_confirmtktclose;?> </strong></label>            </td>
        </tr>
        <tr>
        	<td></td>
            <td height="50">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_close" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
	</div>
	
	<div class="actionlist 2" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_2"><?php if (isset($tkttskmsg2)) { echo $tkttskmsg2; }?></textarea>
            <?php
                         /*   $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_2') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg2)) 
							{
							$oFCKeditor->Value =$tkttskmsg2;
							} else {
                            $oFCKeditor->Value = '-';
							}                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <?php
		//if the current task is an exception, then show the milestones option avaiable on this workflow
		if ($fet_task['wftskflow_idwftskflow']==0)
			{
			//get the milestones in this workflow from the system
				$sql_ms="SELECT idwftskflow,wftskflowname FROM wftskflow WHERE wfproc_idwfproc=".$fet_wfproc['wfproc_idwfproc']." AND is_milestone=1 ORDER BY wftskflowname ASC";
				$res_ms=mysql_query($sql_ms);
				$num_ms=mysql_num_rows($res_ms);
				$fet_ms=mysql_fetch_array($res_ms);
				
			//is next step variable
			$is_nextstep=1;
				
		?>
		<tr>
        	<td class="tbl_data">
            <?php echo $lbl_asterik;?><strong>Next STEP</strong>            </td>
            <td  class="tbl_data"><!-- onChange="nextstep(this.value)"-->
            <select name="next_milestone" onChange="nextstep(this.value)" id="next_milestone">
            	<option value="">---</option>
                <option value="other_exception" style="background-color:#FFFFCC;color:#CC0000;font-weight:bold">*** Not Listed / I Don't Know ***</option>
                <?php
				if ($num_ms > 0)
					{
					$milestone_avail=1;
					do {
					echo "<option value=\"".$fet_ms['idwftskflow']."\">".$fet_ms['wftskflowname']."</option>";
					} while ($fet_ms=mysql_fetch_array($res_ms));
				} //close if is_milestone=1
				?>
            </select>            </td>
        </tr>
		<?php	
			} //close if ==0
		?>
        <tr>
        	<td>            </td>
            <td style="margin:0px; padding:5px 4px;">
            <div id="nextstepdiv">            </div>            </td>
        </tr>
        <?php	
		if ($fet_task['wftskflow_idwftskflow']!=0)//if it is not an exception, then show the list be [0887]
			{
		?>
        <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_sendto;?></strong>            </td>
            <td class="tbl_data">
<?php
//check the next VALID taskflow after this one
	/*
						$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,limit_to_zone,limit_to_dpt 
						FROM wftskflow 
						WHERE 
						wfproc_idwfproc=".$fet_task['wfproc_idwfproc']." AND 
						listorder>".$fet_task['listorder']." 
						ORDER BY listorder ASC LIMIT 1";
					
						//first, check the related role or group so that we can filter by the roles Regions or Department if it applies
	*/					
	
	//WE NEED TO CHECK FOR A VALID NEXT WORKFLOW 
						
						//CHECK BASED ON ROLE
						$sql_checkrole="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,limit_to_zone,limit_to_dpt 
						FROM wftskflow 
						INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow
						INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE ( 
						(wftskflow.limit_to_zone=0 AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=1 AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=0 AND wftskflow.limit_to_dpt=".$_SESSION['MVGitHub_usrdpts']." AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftskflow.limit_to_dpt=".$_SESSION['MVGitHub_usrdpts'].") 
						)
						AND 
						wfactors.usrrole_idusrrole>0 AND
						wftskflow.wfproc_idwfproc=".$fet_task['wfproc_idwfproc']." AND 
						wftskflow.listorder>".$fet_task['listorder']." AND
						usrac.acstatus=1
						ORDER BY wftskflow.listorder ASC LIMIT 1";
						$res_checkrole=mysql_query($sql_checkrole);	
						$fet_checkrole=mysql_fetch_array($res_checkrole);
						
				//		echo $sql_checkrole."<br>";
						
						//CHECK BASED ON GROUP
						$sql_checkroleg="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,limit_to_zone,limit_to_dpt 
						FROM wftskflow 
						INNER JOIN wfactors ON wftskflow.idwftskflow=wfactors.wftskflow_idwftskflow
						INNER JOIN link_userrole_usergroup ON wfactors.usrgroup_idusrgroup=link_userrole_usergroup.usrgroup_idusrgroup 	
						INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole=usrrole.idusrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE ( 
						(wftskflow.limit_to_zone=0 AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=1 AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=0 AND wftskflow.limit_to_dpt=".$_SESSION['MVGitHub_usrdpts']." AND wftskflow.limit_to_dpt=0) OR 
						(wftskflow.limit_to_zone=".$_SESSION['MVGitHub_userteamzoneid']." AND wftskflow.limit_to_dpt=".$_SESSION['MVGitHub_usrdpts'].") 
						)
						AND
						wfactors.usrgroup_idusrgroup>0 AND
						wftskflow.wfproc_idwfproc=".$fet_task['wfproc_idwfproc']." AND 
						wftskflow.listorder>".$fet_task['listorder']." AND
						usrac.acstatus=1
						ORDER BY wftskflow.listorder ASC LIMIT 1";
						$res_checkroleg=mysql_query($sql_checkroleg);	
						$fet_checkroleg=mysql_fetch_array($res_checkroleg);
						
				//		echo $sql_checkroleg;
				
				//WHAT IF ITS IS THE END OF THE WORKFLOW AND HENCE THE RESULTS ABOVE RETURN A NIL RESULT -
				//THEN IN THAT CASE, MAKE IT AN EXCEPTION AND SHOW THE MENU FOR OTHER :)
				
			if (($fet_checkrole['idwftskflow'] < 1) && ($fet_checkroleg['idwftskflow'] < 1)  ) //if condition MAINEMAINE
				{
				echo "<input type=\"hidden\" name=\"wftaskflow_id\" value=\"0\">"; //by default, the next wkflow id is zero because wer are moving out of the workflow again.
				echo "<select style=\"width:250px\" name=\"assign_to_2\" id=\"assign_to_2\" onchange=\"showstuff(this.value);\">";
						echo "<option value=\"\">---Click Here---</option>";
							echo "<option disabled=\"disabled\">-----------------</option>";	
							echo "<option style=\"color:#ff0000;font-weight:bold;background-color:#ffffcc\" title=\"If Recepient is not Listed, then make this an Exception\" value=\"other_exception\">[ Not Listed Above ]</option>";
							echo "</select>";	
				
				} else {			
				
						
						//take the least - else if the same, take whichever idtskwkflow
						if ($fet_checkrole['idwftskflow'] > 0)
							{
							$tskflowid_role = $fet_checkrole['idwftskflow'];							
							}
							
						if ($fet_checkroleg['idwftskflow'] > 0)
							{							
							$tskflowid_grp = $fet_checkroleg['idwftskflow'];
							}
							
						//pick the lower number
						if ( (isset($tskflowid_role)) && (isset($tskflowid_grp)) )
							{
								if ($tskflowid_role < $tskflowid_grp)
									{
									$next_tskflowid=$tskflowid_role;
									} else if ($tskflowid_role > $tskflowid_grp) {
									$next_tskflowid=$tskflowid_grp;
									} else {
									$next_tskflowid=$tskflowid_role;
									}
									
							} else if ( (isset($tskflowid_role)) && (!isset($tskflowid_grp)) ) {
								
								$next_tskflowid=$tskflowid_role;	
														
							} else if ( (!isset($tskflowid_role)) && (isset($tskflowid_grp)) ) {
								
								$next_tskflowid=$tskflowid_grp;	
								
							} else {
								
								$next_tskflowid=0;	
								
							}
							
						
							$sql_nextwf="SELECT idwftskflow,wfsymbol_idwfsymbol,wfproc_idwfproc,limit_to_zone,limit_to_dpt 
							FROM wftskflow 
							WHERE idwftskflow=".$next_tskflowid." ";
							$res_nextwf=mysql_query($sql_nextwf);
							$fet_nextwf=mysql_fetch_array($res_nextwf);
							$num_nextwf=mysql_num_rows($res_nextwf);
							//echo $sql_nextwf."<br>";
							if ($num_nextwf > 0)//if there is a record
								{ 
								
								//check if it is limit region
								if ($fet_nextwf['limit_to_zone']==1)
									{
									//limit this users region
									$limit_to_zone_qry1=" AND usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." ";
									} else {
									$limit_to_zone_qry1="";
									}
									
								//check if it is limit region
								if ($fet_nextwf['limit_to_dpt']==1)
									{
									//limit this user to their department
									$limit_to_dpt_qry1=" AND usrrole.usrdpts_idusrdpts=".$_SESSION['MVGitHub_usrdpts']." ";
									} else {
									$limit_to_dpt_qry1="";
									}
								
								if ($fet_nextwf['wfsymbol_idwfsymbol']==10) //if it is the end of the process
									{
									
									$next_step="last_step";
									
									} else { //else if not end of the process, continue
									
										//confirm whether the actors are a group or individual role
										$sql_actors="SELECT usrrole_idusrrole,usrgroup_idusrgroup FROM wfactors WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." LIMIT 1 ";
										$res_actors=mysql_query($sql_actors);
										$fet_actors=mysql_fetch_array($res_actors);
										$num_actors=mysql_num_rows($res_actors);
										//echo $sql_actors;
										if ($fet_actors['usrrole_idusrrole'] >0 ) //if more than 0, then it is a allocated to a role
											{
											//find out the actual account assigned this role
											$sql_userac="SELECT idusrac,usrrolename,idusrrole,usrac.utitle,usrac.lname,usrac.fname,usrteamzone.region_pref FROM wfactors
											INNER JOIN usrrole ON wfactors.usrrole_idusrrole=usrrole.idusrrole
											INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
											INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
											WHERE wfactors.wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ".$limit_to_zone_qry1." ".$limit_to_dpt_qry1." AND acstatus=1 ORDER BY usrteamzone.idusrteamzone";
											$res_userac=mysql_query($sql_userac);
											$fet_userac=mysql_fetch_array($res_userac);
											$num_userac=mysql_num_rows($res_userac);
										//	echo "<span style=color:#ffffff>".$sql_userac."</spa<br>";
	
											if ($num_userac > 0)
												{
												
												$menu_item="";
												$menu_exvalues="";
													do {
														if ($fet_userac['idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
															{
															$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname']."\" value=\"".$fet_userac['idusrrole']."\">[".$fet_userac['region_pref']."] ".$fet_userac['usrrolename']." ( ".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname'].")</option>";
															} else {
															$menu_item.="<option title=\"".$fet_userac['utitle']." ".$fet_userac['fname']." ".$fet_userac['lname']."\" value=\"".$fet_userac['idusrrole']."\">*** [ To My TasksIN ]</option>";
															} //end //list only if not current user
														
														//create the exvalues
														$menu_exvalues.= "AND idusrrole!=".$fet_userac['idusrrole']." ";
														
														} while ($fet_userac=mysql_fetch_array($res_userac));
										
												} else {
												
												echo "<div class=\"msg_warning_small\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
												
												} //user exists
											} //close usrrole
									
										if ($fet_actors['usrgroup_idusrgroup'] > 0 ) //if allocated to a group, then do the following
											{ 
											//if group, check only those roles that do actually have users (active status) mapped to them
											//check who has had most work in the last 7 days (one week) in terms of hours
											//last 7 days
											//$timenow = ; //capture current time. You can adjust based on server settings
											$sevendaysago = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s",time())) - (7*86400)); //7 days ago
											
											//echo $sevendaysago."<br>-----";
												
											$sql_workdistr="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrrole.usrrolename,usrteamzone.region_pref FROM wftasks 
											INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
											INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
											INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
											INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
											WHERE wftasks.createdon>='".$sevendaysago."' AND wftasks.createdon<='".$timenowis."' ".$limit_to_zone_qry1." ".$limit_to_dpt_qry1." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND acstatus=1 GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
											//echo "test";
											//echo $sql_workdistr."<br>";
											$res_workdistr=mysql_query($sql_workdistr);
											$num_workdistr=mysql_num_rows($res_workdistr);
											$fet_workdistr=mysql_fetch_array($res_workdistr);
												
												
											//check in case the group has not received anything in the last 7 days
											$sql_workdistolder7="SELECT SUM(TIMESTAMPDIFF(MINUTE,timetatstart,timedeadline)) AS minutes, usrac.idusrac, usrac.usrrole_idusrrole,usrac.utitle,usrac.lname,usrac.fname,usrrole.usrrolename,usrteamzone.region_pref FROM wftasks 
											INNER JOIN usrrole ON wftasks.usrrole_idusrrole=usrrole.idusrrole 
											INNER JOIN link_userrole_usergroup ON usrrole.idusrrole = link_userrole_usergroup.usrrole_idusrrole
											INNER JOIN usrac ON link_userrole_usergroup.usrrole_idusrrole = usrac.usrrole_idusrrole
											INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
											WHERE wftasks.createdon<='".$timenowis."' AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ".$limit_to_zone_qry1." ".$limit_to_dpt_qry1." AND acstatus=1 GROUP BY wftasks.usrac_idusrac ORDER BY minutes ASC";
											//echo "test";
											//echo $sql_workdistolder7."<br>";
											$res_workdistolder7=mysql_query($sql_workdistolder7);
											$num_workdistolder7=mysql_num_rows($res_workdistolder7);
											$fet_workdistolder7=mysql_fetch_array($res_workdistolder7);	
											
												
											//check also for any new user who perhaps has never received a task - new user
											$sql_newuser="SELECT idusrac, usrac.usrrole_idusrrole, usrrole.usrrolename,usrac.utitle,usrac.lname,usrac.fname,usrteamzone.region_pref
											FROM link_userrole_usergroup
											INNER JOIN usrrole ON link_userrole_usergroup.usrrole_idusrrole = usrrole.idusrrole
											INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
											INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
											WHERE link_userrole_usergroup.usrrole_idusrrole NOT
											IN (
											
											SELECT usrrole_idusrrole
											FROM wftasks
											)
											AND link_userrole_usergroup.usrgroup_idusrgroup=".$fet_actors['usrgroup_idusrgroup']." 
											".$limit_to_zone_qry1." ".$limit_to_dpt_qry1."
											AND acstatus=1";
											$res_newuser=mysql_query($sql_newuser);
											$num_newuser=mysql_num_rows($res_newuser);
											$fet_newuser=mysql_fetch_array($res_newuser);
									
								//	echo $sql_newuser;
											//if record exists, then pick 
											
											if ($num_newuser>0) //if there is a new user and user exists in the workflow
												{
												
												$menu_item3="";
												$menu_exvalues3="";
												
													do {
														if ($fet_newuser['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole']) //list only if not current user
															{
															$menu_item3.="<option title=\"".$fet_newuser['utitle']."  ".$fet_newuser['fname']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">[".$fet_newuser['region_pref']."] ".$fet_newuser['usrrolename']." (".$fet_newuser['utitle']."  ".$fet_newuser['fname']." ".$fet_newuser['lname'].")</option>";
															} //end //list only if not current user
														
												//create the exvalues
												$menu_exvalues3.=" AND idusrrole!=".$fet_newuser['usrrole_idusrrole']." ";
														
														} while ($fet_newuser=mysql_fetch_array($res_newuser));	
												//$menu_item="<option selected=\"selected\" title=\"".$fet_newuser['utitle']." ".$fet_newuser['lname']."\" value=\"".$fet_newuser['usrrole_idusrrole']."\">".$fet_newuser['usrrolename']."[2]</option>";
													
													
												} else if ($num_newuser==0) { //else if no one is new 
													
														if ($num_workdistr > 0 ) //if there are already users in the tasks
															{
															$menu_item2="";
															$menu_exvalues2="";
																
																do {
																//don't list the current logged in user on the menu
																if ($fet_workdistr['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
																		{
																		$menu_item2.="<option title=\"".$fet_workdistr['utitle']." ".$fet_workdistr['fname']." ".$fet_workdistr['lname']."\"  value=\"".$fet_workdistr['usrrole_idusrrole']."\">[".$fet_workdistr['region_pref']."] ".$fet_workdistr['usrrolename']." (".$fet_workdistr['utitle']." ".$fet_workdistr['fname']." ".$fet_workdistr['lname'].")</option>";
																		}
																		
																//exvalue here	
																$menu_exvalues2.=" AND idusrrole!=".$fet_workdistr['usrrole_idusrrole']." ";	
																	
																	} while ($fet_workdistr=mysql_fetch_array($res_workdistr));
															
															} else { //else create a task for the admin
															
																//check if older than 7 days
																if ($num_workdistolder7 > 0)
																	{
																	$menu_item2="";
																	$menu_exvalues2="";
																	
																	do {
																		//don't list the current logged in user on the menu
																		if ($fet_workdistolder7['usrrole_idusrrole']!=$_SESSION['MVGitHub_iduserrole'])
																				{
																				$menu_item2.="<option title=\"".$fet_workdistolder7['utitle']." ".$fet_workdistolder7['fname']." ".$fet_workdistolder7['lname']."\"  value=\"".$fet_workdistolder7['usrrole_idusrrole']."\">[".$fet_workdistolder7['region_pref']." ] ".$fet_workdistolder7['usrrolename']." (".$fet_workdistolder7['utitle']." ".$fet_workdistolder7['fname']." ".$fet_workdistolder7['lname'].")</option>";
																				}
																				
																			//exvalue here	
																			$menu_exvalues2.=" AND idusrrole!=".$fet_workdistolder7['usrrole_idusrrole']." ";	
																			
																			} while ($fet_workdistolder7=mysql_fetch_array($res_workdistolder7));
																	
																	} else {
																	//create new task for the admin
																	echo "<div class=\"msg_warning_small\">--No Active User--<br><small>(Please Contact Admin)</small></div>";
																	}
												
															} //user exists
													
													} //close new user
	
												} //close user group
	
									} //not last step
								
								} else { //if no record
								
								$next_step="end_of_road";
								} //close if there is a record

							
						if ( (isset($menu_item)) || (isset($menu_item2)) || (isset($menu_item3)) )
							{
							
						//let's create the exception list to carry over to the ajaxfile on the other end
						if (isset($menu_exvalues))
							{
							$menu_exvalues_vals=$menu_exvalues;
							} else {
							$menu_exvalues_vals="";
							}
						if (isset($menu_exvalues2))
							{
							$menu_exvalues2_vals=$menu_exvalues2;
							} else {
							$menu_exvalues2_vals="";
							}
						if (isset($menu_exvalues3))
							{
							$menu_exvalues3_vals=$menu_exvalues3;
							} else {
							$menu_exvalues3_vals="";
							}	
						
						
						$_SESSION['next_tskflowid']=$next_tskflowid; //store in a session and see if it appears the other file
						
						$_SESSION['exempt']=$menu_exvalues_vals.$menu_exvalues2_vals.$menu_exvalues3_vals; //excempt this ids from the excemption list
										
						
						echo "<input type=\"hidden\" name=\"wftaskflow_id\" value=\"".$next_tskflowid."\">";				
						echo "<select style=\"width:250px\" name=\"assign_to_2\" id=\"assign_to_2\" onchange=\"showstuff(this.value);\">";
						echo "<option value=\"\">---Click Here---</option>";
						
								if(isset($menu_item)) { echo $menu_item; }
								if(isset($menu_item2)) { echo $menu_item2; }
								if(isset($menu_item3)) { echo $menu_item3; }

								//find out if there is a group for this
								$sql_groupname="SELECT idwfactorsgroupname,groupname FROM wfactorsgroupname WHERE wftskflow_idwftskflow=".$fet_nextwf['idwftskflow']." LIMIT 1";
								$res_groupname=mysql_query($sql_groupname);
								$fet_groupname=mysql_fetch_array($res_groupname);						
										
							if ($fet_groupname['idwfactorsgroupname'] > 0)
								{	
								echo "<option title=\"Send to a Group but only one will Action\" value=\"GRP".$fet_groupname['idwfactorsgroupname']."\">[Group] ".$fet_groupname['groupname']."</option>";
								}
							echo "<option disabled=\"disabled\">-----------------</option>";	
							echo "<option style=\"color:#ff0000;font-weight:bold;background-color:#ffffcc\" title=\"If Recepient is not Listed, then make this an Exception\" value=\"other_exception\">[ Not Listed Above ]</option>";
							echo "</select>";	
								
							} //select menu
					
						} //close if condition MAINEMAINE
					
						?>            </td>
        </tr>
        <?php
			} //list this only if this is not an exeception [0887]
		?>
        <tr>
        	<td class="tbl_data">
            <?php
			//echo $is_nextstep."-";
			//echo $fet_task['sender_idusrrole']."-".$fet_task['wftskflow_idwftskflow'];
				//change the display from none to block if it is an exception
				if  ( ($fet_task['sender_idusrrole']!=2) && ($fet_task['wftskflow_idwftskflow']==0) && (!isset($is_nextstep))  ) //if exception
					 { 
					 $lbl_sendto="<strong>".$lbl_asterik.$lbl_sendto."</strong>";
					 $display="block" ;
					 
					 } else {
					 	
					 	$lbl_sendto="";
					 	$display="none";
					 }
				echo $lbl_sendto;
				?>            </td>
            
            <td class="tbl_data">
           	<?php
			if ($fet_task['wftskflow_idwftskflow']!=0) { //if not an exception 
			?>
            <div id="other_exception" style="display:<?php echo $display;?>;">
                <div><span style="font-size:11px; font-weight:bold;color:#CC0000; background-color:#FFFFCC">Type Name or Role to Send To :</span></div>
                <div>
				<input type="text" name="recepient_alt" id="recepient_alt" value="" size="38" />
                </div>      
                </div>
            <?php
			}
			?>            </td>
        </tr>
        
        <tr>
        	<td></td>
            <td height="50">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_passiton" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
	</div>
    
	<div class="actionlist 3" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_3"><?php if (isset($tkttskmsg3)) { echo $tkttskmsg3; }?></textarea>
            <?php
			
                            /*$sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_3') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg3)) 
							{
							$oFCKeditor->Value =$tkttskmsg3;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_transto;?></strong>            </td>
            <td class="tbl_data">
            <?php
						//confirm what the next workflow id is for this task
						$sql_role="SELECT idusrrole,idusrac,usrrolename,utitle,lname FROM usrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
						WHERE (usrteamzone.idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." OR usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam'].") AND idusrrole!=".$_SESSION['MVGitHub_iduserrole']."  ORDER BY usrrolename ASC";
						$res_role=mysql_query($sql_role);
						$fet_role=mysql_fetch_array($res_role);
						$num_role=mysql_num_rows($res_role);

								
						echo "<select name=\"assign_to_3\" >";
						echo "<option value=\"\">---</option>";
						do {
						echo "<option value=\"".$fet_role['idusrrole']."\">".$fet_role['usrrolename']." (".$fet_role['utitle']." ".$fet_role['lname'].")</option>";
						} while ($fet_role=mysql_fetch_array($res_role));
						echo "</select>";	
						?>            </td>
        </tr>
         <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_newdeadline;?></strong>            </td>
            <td class="tbl_data">
            <input type="text" name="newdeadline" value="<?php if (isset($tktnewdeadline)) { echo $tktnewdeadline;}?>" readonly="readonly" onClick="javascript:show_calendar('document.task.newdeadline', document.task.newdeadline.value);" />
            <a href="javascript:show_calendar('document.task.newdeadline', document.task.newdeadline.value);">
            <img src="../assets_backend/btns/cal.gif" width="30" align="absmiddle" height="30" border="0" alt="Click Here to Pick up the timestamp"></a>            </td>
        <tr>
        	<td></td>
            <td height="40">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_submit" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
	</div>
    
    <div class="actionlist 4" style="margin:0; padding:0">
        <table border="0" width="100%" cellpadding="0" cellspacing="0" >
 <tr>
        	<td class="tbl_data" width="25%" valign="top">
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_reasoninvalid;?></strong>            </td>
            <td class="tbl_data">
            <?php
						//confirm what the next workflow id is for this task
						$sql_role="SELECT idwftskinvalidlist,wfttaskinvalidlistlbl FROM wftskinvalidlist ORDER BY wfttaskinvalidlistlbl ASC";
						$res_role=mysql_query($sql_role);
						$fet_role=mysql_fetch_array($res_role);
						$num_role=mysql_num_rows($res_role);
						
					//	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>";		
						echo "<select name=\"invalid_id\" id=\"invalid_id\">";
						echo "<option value=\"-1\" selected>----</option>";
						do {
						echo "<option value=\"".$fet_role['idwftskinvalidlist']."\">".$fet_role['wfttaskinvalidlistlbl']."</option>";
						} while ($fet_role=mysql_fetch_array($res_role));
						echo "<option value=\"0\">".$lbl_notlistedadd."</option>";
						echo "</select>";	
						
					//	echo "</td><td>";
						echo "<div id=\"0\" class=\"invalid_new\"  style=\"display:none; padding:10px 0px 10px 0px\">".$lbl_newreason." : <input type=\"text\" name=\"add_reason\"></div>";
					//	echo "</td></tr></table>";
						?>            </td>
        </tr>
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_4"><?php if (isset($tkttskmsg4)) { echo $tkttskmsg4; }?></textarea>
            <?php
                           /* $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_4') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg4)) 
							{
							$oFCKeditor->Value =$tkttskmsg4;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
       
        <tr>
        	<td></td>
            <td height="50">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_invalid" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
    </div>
    
    <div class="actionlist 5" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_youraction_msg?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_5"><?php if (isset($tkttskmsg5)) { echo $tkttskmsg5; }?></textarea>
            <?php
                            /*$sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_5') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg5)) 
							{
							$oFCKeditor->Value =$tkttskmsg5;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_sendto;?></strong>            </td>
            <td class="tbl_data">
            <?php
						//confirm what the next workflow id is for this task
						$sql_role="SELECT idusrrole,idusrac,usrrolename,utitle,lname FROM usrrole
						INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
						WHERE idusrrole=".$_SESSION['MVGitHub_reportingto']."  ORDER BY usrrolename ASC";
						$res_role=mysql_query($sql_role);
						$fet_role=mysql_fetch_array($res_role);
						$num_role=mysql_num_rows($res_role);

								
						echo "<select name=\"assign_to_5\" >";
						do {
						echo "<option value=\"\">---</option>";
						echo "<option value=\"".$fet_role['idusrrole']."\">".$fet_role['usrrolename']." (".$fet_role['utitle']." ".$fet_role['lname'].")</option>";
						} while ($fet_role=mysql_fetch_array($res_role));
						echo "</select>";	
						?>            </td>
        </tr>
        <tr>
        	<td></td>
            <td height="40">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_submit" onClick="document.forms['task'].submit()" ></a></td>
        </tr>
	</table>
	</div>
    
     <div class="actionlist 6" style="margin:0; padding:0">
    <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_update_prog;?></strong>            </td>
            <td class="tbl_data">
            <?php
			$sql_progup="SELECT idwftskupdates_class,wftskupdates_classlbl,wftskupdates_classdesc FROM wftskupdates_class";
			$res_progup=mysql_query($sql_progup);
			$num_progup=mysql_num_rows($res_progup);
			$fet_progup=mysql_fetch_array($res_progup);
//			echo $sql_progup;
echo "<select name=\"progress_update_status\">";
			do {
			echo "<option value=\"".$fet_progup['idwftskupdates_class']."\">".$fet_progup['wftskupdates_classlbl']."</option>";
			} while ($fet_progup=mysql_fetch_array($res_progup));
echo "</select>";			
			?>            </td>
       </tr>
       <tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_nemail_notify;?></strong>
            <a href="#" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span>Enter the email of the one you wish to notify. If many emails, separate with a comma eg: abc@emai.com,efg@email.com</span></a>            </td>
            <td class="tbl_data">
            <input type="text" name="progress_update_emails" size="30" maxlength="150" />            </td>
		</tr>
    	<tr>
            <td width="25%" valign="top" class="tbl_data" align="left" >
            <strong><?php echo $lbl_asterik;?><?php echo $lbl_update_msg;?></strong>            </td>
            <td class="tbl_data" >
            <textarea cols="25" rows="4" name="task_msg_6"><?php if (isset($tkttskmsg6)) { echo $tkttskmsg6; }?></textarea>
            <?php
              /*              $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_6') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg6)) 
							{
							$oFCKeditor->Value =$tkttskmsg6;
							} else {
                            $oFCKeditor->Value = '-';
							}
                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
       
        <tr>
        	<td></td>
            <td height="40">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_progup" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
	</div>
    
	
    <div class="actionlist 9" style="margin:0; padding:0">
     <table border="0" width="100%" cellpadding="0" cellspacing="0" >
    	<tr>
            <td colspan="2" valign="top" class="tbl_data" align="left" >
            <div style="color:#FFFFFF; font-weight:bold; background-color:#FF0000; padding:2px"><img src="../assets_backend/icons/warning.gif" border="0" align="absmiddle"> This means that you are not able to work on this task!            </div>            </td>
            </tr>
            <tr>
            <td class="tbl_data" width="25%">
            <strong><?php echo $lbl_asterik;?>Your Message</strong></td>
            <td class="tbl_data">
            <textarea cols="25" rows="4" name="task_msg_9"><?php if (isset($tkttskmsg9)) { echo $tkttskmsg9; }?></textarea>
            <?php
                         /*   $sBasePath = $_SERVER['PHP_SELF'] ;
                            $sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) ) ;
                            $oFCKeditor = new FCKeditor('task_msg_2') ;
                            $oFCKeditor->Height = '150' ;
                            $oFCKeditor->BasePath	= "fckeditor/".$sBasePath ;
							if (isset($tkttskmsg2)) 
							{
							$oFCKeditor->Value =$tkttskmsg2;
							} else {
                            $oFCKeditor->Value = '-';
							}                            $oFCKeditor->ToolbarSet = 'Basic' ;
                            $oFCKeditor->Create() ;*/
                            ?>            </td>
		</tr>
        <tr>
        	<td class="tbl_data">
            <strong><?php echo $lbl_sendto;?></strong>            </td>
            <td class="tbl_data">
            <?php	
				echo "<select name=\"assign_to_9\">";
				echo "<option value=\"".$fet_sender['idusrrole']."\"  \" >".$fet_sender['usrrolename']." (".$fet_sender['utitle']." ".$fet_sender['fname']." ".$fet_sender['lname'].")"."</option>";
				echo "</select>";	
							
					
						?>            </td>
        </tr>
        <tr>
        	<td></td>
            <td height="50">
            <input type="hidden" value="process_task" name="formaction" />
    		<a href="#" id="button_return" onClick="document.forms['task'].submit()" ></a>            </td>
        </tr>
	</table>
    </div>    
</div></td>
	</tr>
</table>
			  
          </td>
        </tr>
    </table>
	</div>
  </form>
   <?php
//   }
   ?>
<?php
//echo mysql_affected_rows();
?>      
</div>  
<div style="color:#CCCCCC; text-align:right; font-size:small">
<?php
if (isset($fet_task['wftskflow_idwftskflow'])) { echo $fet_task['wftskflow_idwftskflow']; } echo " - "; if (isset($next_tskflowid)) { echo $next_tskflowid; }
?>
</div>
</body>
</html>
<?php
//echo "<span style=\"color:#ffffff\">".$_SESSION['NoRTS']."</span>";
//echo "<span style=\"color:#ffffff\">". $sql_task."</span>";
/*echo "<span style=\"color:#ffffff\">".$fet_batch['idwftasks_batch']."==".$fet_listbatch['idwftasks_batch']."<br><br>".$sql_listbatch."<br><br><br>SELECT idwftasks_batch,tktin.voucher_number,wftasks_batch.batch_no_verbose FROM wftasks 
				INNER JOIN tktin ON wftasks.tktin_idtktin=tktin.idtktinPK
				INNER JOIN wftasks_batch ON tktin.wftasks_batch_idwftasks_batch=wftasks_batch.idwftasks_batch 
				WHERE idwftasks=".$fet_task['idwftasks']."</span>";
				*/
mysql_close($connSystem);
?>