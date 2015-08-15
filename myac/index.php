<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);
require_once('../assets_backend/be_includes/check_login.php');

//include the following file only when required
include("fckeditor/fckeditor.php");

################

################

if (isset($_POST['map_filter_cat']))
	{
	//register the sessions for the map report here
	//echo "test";
    }

//if the filter for user profile is set
if (isset($_GET['filter_region']))
	{
	$_SESSION['filter_region']=trim($_GET['filter_region']);
	}

//if filter for user roles is set
if (isset($_GET['userrole_find']))
	{
	$_SESSION['userrole_find']=mysql_escape_string(trim($_GET['userrole_find']));
	}
if (isset($_GET['zone']))
	{
	$_SESSION['zone']=mysql_escape_string(trim($_GET['zone']));
	}
if (isset($_GET['profile']))
	{
	$_SESSION['profile']=mysql_escape_string(trim($_GET['profile']));
	}	

//mass transfer the tasks if selected
//process the transference
if ( (isset($_GET['formaction'])) && ($_GET['formaction']=="mass_assign"))
	{

	if ( (isset($_GET['seltask'])) && ($_GET['seltask']!="")) 
		{
		$count=count($_GET['seltask']);
		//echo "<br><br><br><br>";

		if ($count > 0) //if a record has been chosen
			{
				$seltask="";
					for ($i=0; $i<$count; $i++) //then pick and store the variables
						{
						$seltask.=$_GET['seltask'][$i].",";
						}
					//then redirect the user to the pop_myteam_tasks_transfer.php page :)
					$_SESSION['tasks']=substr($seltask,0,-1); //the values
					$_SESSION['tasks_no']=$count;
				
				//create a new batch
			/*	$sql_newbatch="INSERT INTO wftasks_transfers_batch (createdby_iduser,createdon) 
				VALUES ('".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
				mysql_query($sql_newbatch);
				
				//get that value frmo the db
				$sql_batch="SELECT idtransferbatch FROM wftasks_transfers_batch WHERE createdby_iduser=".$_SESSION['MVGitHub_idacname']." ORDER BY idtransferbatch DESC LIMIT 1";
				$res_batch=mysql_query($sql_batch);
				$fet_batch=mysql_fetch_array($res_batch);
				
				$_SESSION['batch']=$fet_batch['idtransferbatch'];
			*/	
				?>
				<script language="javascript">
				window.location='index.php?mod=2&submod=20002&uction=view_submod&c=<?php echo $count;?>';
				</script>                
                <?php
				exit;
				
			} else {
			$_SESSION['error']="<div class=\"msg_warning\">Please select the tasks you wish to Pass on...</div>";
			}
		} else {
		$_SESSION['error']="<div class=\"msg_warning\">Please select the tasks you wish to Pass on...</div>";
		}
	
	}	
	
	
//////////////////////////////////	

if ((isset($_GET['resolv']))  && (isset($_GET['saction'])) )
	{
	if 	((isset($_GET['saction'])) && ($_GET['saction']="delete_step") && (isset($_SESSION['wfselected'])) )
		{
		$record=mysql_escape_string(trim($_GET['resolv']));
	
		//delete the record if owned by this organization
		$sql_deltskflow="DELETE FROM wftskflow WHERE wfproc_idwfproc=".$_SESSION['wfselected']." AND idwftskflow=".$record." LIMIT 1";
		mysql_query($sql_deltskflow);

		$_SESSION['success_message']=$msg_success_delete;
		
		//echo $sql_delete_tskflow;
		}
	}

//echo $sql_delete_tskflow;

//SELECT Default Sub Menu on the Left	
if ((isset($_SESSION['sec_submod'])) && ($_SESSION['sec_submod']==0) && (isset($_SESSION['sec_uction'])) && ($_SESSION['sec_uction']=="view_mod"))
	{
	
	$sql_def_submode="SELECT idsyssubmodule FROM syssubmodule 
	INNER JOIN systemprofileaccess ON syssubmodule.idsyssubmodule=systemprofileaccess.syssubmodule_idsyssubmodule
	WHERE syssubmodule.sysmodule_idsysmodule=".$_SESSION['sec_mod']." AND is_default=1 AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
	$res_def_submode=mysql_query($sql_def_submode);
	$fet_def_submode=mysql_fetch_array($res_def_submode);
	$num_def_submode=mysql_num_rows($res_def_submode);
	
	if ($num_def_submode > 0 )
		{
		//echo $sql_def_submode."<br>";
		$_SESSION['sec_submod'] = $fet_def_submode['idsyssubmodule'];
		$_SESSION['sec_uction']="view_submod";
		
		} else {
		
		$sql_def_c="SELECT idsyssubmodule FROM syssubmodule 
		INNER JOIN systemprofileaccess ON syssubmodule.idsyssubmodule=systemprofileaccess.syssubmodule_idsyssubmodule
		WHERE syssubmodule.sysmodule_idsysmodule=".$_SESSION['sec_mod']." AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." ORDER BY idsyssubmodule ASC LIMIT 1";
		$res_def_c=mysql_query($sql_def_c);
		$fet_def_c=mysql_fetch_array($res_def_c);
		$num_def_c=mysql_num_rows($res_def_c);
		
		$_SESSION['sec_submod'] = $fet_def_c['idsyssubmodule'];
		$_SESSION['sec_uction']="view_submod";
		
		} //close if num_def_submode >0
	
	} //close if sectsubmode=0
/////END OF SELECTING SUB MENU	

//check the global permissions for the selected submdule
//get global permission where necessary
if ((isset($_SESSION['sec_mod'])) && (isset($_SESSION['MVGitHub_iduserprofile'])) && (isset($_SESSION['sec_submod'])) ) //check if sub module is set
	{
	$sql_permglobal="SELECT global_access FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$_SESSION['sec_submod']." AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
	$res_permglobal=mysql_query($sql_permglobal);
	$num_permglobal=mysql_num_rows($res_permglobal);
	$fet_permglobal=mysql_fetch_array($res_permglobal);
	//echo "<span style=\"color:#ff0000\">".$sql_permglobal."</span>";
	if ($num_permglobal > 0)
		{
		$is_perm_global=$fet_permglobal['global_access'];
		} else {
			$is_perm_global=0;
		}
	} else {
	$is_perm_global=0;
	}	

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<meta content="utf-8" http-equiv="encoding">
<title><?php echo $pagetitle;?> - <?php echo $_SESSION['MVGitHub_acteam'];?></title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/ajaxtabs.js"></script>
<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../thickbox/thickbox_be.js"></script>
<script language="javascript" type="text/javascript" src="../scripts/datetimepicker.js"></script>
<script type="text/javascript" src="../uilock/jquery.uilock.js"></script>
<script language="javascript">
//Preloader for Batch Processing
	$(document).ready(function() {
			//$('#lock').click(function(){
		/*	$('#button_procbatch').click(function(){
			
				// To lock user interface interactions
				// Optinal: put html on top of the lock section,
				// like animated loading gif
				
				//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
			$.uiLock('<center class=msg_ok_overlay>One Moment Please as your Batch is Processed...</center>');
				
			});
			*/
			
			////////////////////////////////
			
			$('#button_saveandcont').click(function(){
			
				// To lock user interface interactions
				// Optinal: put html on top of the lock section,
				// like animated loading gif
				
				//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
			$.uiLock('<center class=msg_ok_overlay>One Moment Please ...</center>');
				
			});
			
			
			////////////////////////////////
			
			$('#button_passiton').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');

				$.uiLock('<center class=msg_ok_overlay>One Moment Please ... This could take a while...</center>');
					
				});
			
			
			////////////////////////////////
			
			$('#button_duty_on').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>One Moment Please ... This could take a while...</center>');
					
				});			
			
			////////////////////////////////
			
			$('#button_duty_off').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>One Moment Please ... This could take a while...</center>');
					
				});		
			
		
		});
		

//Logging in preloader
			$(document).ready(function() {
				//$('#lock').click(function(){
				$('#button_submit').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>One Moment... </center>');
					
				});
				
				
				// To unlock user interface interactions
				//$.uiUnlock();

			});
			
		
//restrict to numbers or alpha
var numb = "0123456789.,-";
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
	
	function getregion(countryId) {		
		
		var strURL="findregion.php?country="+countryId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('regiondiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
	
	function getprofile(zoneId) {		
		
		var strURL="findprofile.php?zone="+zoneId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('zoneprofilediv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
	
	function getregionrole(zoneId) {		
		
		var strURL="findregionrole.php?zone="+zoneId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('zonerolediv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
	
	function getcounty(countryId,regionId) {		
		var strURL="findcounty.php?country="+countryId+"&region="+regionId;
		var req = getAJAXHTTPREQ();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('countydiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
				
	}
	
	
function lookup(locationtown) {
		if(document.newteamzone.locationtown.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("findlocation.php", {queryString: ""+locationtown+""}, function(data){
				if(data.length >0) {
				
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#locationtown').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}

</script>
<?php
if ( (isset($_SESSION['sec_submod'])) && ($_SESSION['sec_submod']==2) ) { //if tasksin, then refresh page body after 60 seconds
?>
<script>
 $(document).ready(function() {
 	 $("#responsecontainer").load("module_tasksin.php?parentviewtab=<?php echo $_SESSION['parenttabview'];?>");
   var refreshId = setInterval(function() {
      $("#responsecontainer").load('module_tasksin.php?parentviewtab=<?php echo $_SESSION['parenttabview'];?>');
   }, 60000);
   $.ajaxSetup({ cache: false });
});
</script>
<?php
} //refresh tasks in 

if ((isset($_SESSION['sec_submod'])) && ($_SESSION['sec_submod']==17) && ($_SESSION['sec_uction']=="view_submod") )
	{
?>	
	<script>
 $(document).ready(function() {
 	 $("#responsecontainer").load("module_workflowtocat_ajax.php");
   var refreshId = setInterval(function() {
     $("#responsecontainer").load('module_workflowtocat_ajax.php');
   }, 30000);
   $.ajaxSetup({ cache: false });
});
</script>
<?php
}//refresh the workflow_ajax page
?>

<?php

if ((isset($_SESSION['sec_submod'])) && ($_SESSION['sec_submod']==17) && ($_SESSION['sec_uction']=="edit_workflow") )
	{
?>	
	<script>
 $(document).ready(function() {
 	 $("#responsecontainer").load("module_editworkflow_ajax_v3.php");
   var refreshId = setInterval(function() {
     $("#responsecontainer").load('module_editworkflow_ajax_v3.php');
   }, 30000);
   $.ajaxSetup({ cache: false });
});
</script>
<?php
}//refresh the workflow edit page
?>


<?php //sms broadcast display of menus 
if ((isset($_SESSION['sec_submod'])) && ($_SESSION['sec_submod']==22) && ($_SESSION['sec_mod']==10) )
	{ 
?>
 <script language="javascript" type="application/javascript">
     function GetXmlHttpObject(handler)  
    {  
       var objXMLHttp=null  
       if (window.XMLHttpRequest)  
       {  
           objXMLHttp=new XMLHttpRequest()  
       }  
       else if (window.ActiveXObject)  
       {  
           objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")  
       }  
       return objXMLHttp  
    }  
      
    function stateChanged()  
    {  
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")  
       {  
               document.getElementById("txtResult").innerHTML= xmlHttp.responseText;  
       }  
       else {  
               //alert(xmlHttp.status);  
       }  
    }  
      
    // Will populate data based on input  
    function htmlData(url, qStr)  
    {  
       if (url.length==0)  
       {  
           document.getElementById("txtResult").innerHTML="";  
           return;  
       }  
       xmlHttp=GetXmlHttpObject()  
       if (xmlHttp==null)  
       {  
           alert ("Browser does not support HTTP Request");  
           return;  
       }  
      
       url=url+"?"+qStr;  
       url=url+"&sid="+Math.random();  
       xmlHttp.onreadystatechange=stateChanged;  
       xmlHttp.open("GET",url,true) ;  
       xmlHttp.send(null);  
    }  
 </script>
<?php  } //close broadcast script?>
<script type="text/javascript">
//select all check boxes or disselect
function selectAll(state) {
for (i = 0; i < document.form.elements.length; i++) {
var checkbox = document.form.elements[i];
checkbox.checked = state;
}
}

//checkbox mass list validate
function checkbox_assign() {
if (document.form.assign_to.value=="")
	{
	alert ('Please select the user to assign to');
	document.form.assign_to.focus();
	return false;
	}
return true;
}	
</script>

<?php
//only load the script below when in the relevant module
if ($_SESSION['sec_mod']==16)
	{
?>
<script language="javascript" type="text/javascript">

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
	

	function getqtr(ryearId) {		
		
		var strURL="findQtr.php?ryear="+ryearId;
		var req = getAJAXHTTPREQ();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('qtrdiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}
	
	function getactivities(ryearId,rqtrId) {		
		var strURL="findActivities.php?ryear="+ryearId+"&rqtr="+rqtrId;
		var req = getAJAXHTTPREQ();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('activitydiv').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
				
	}
	
	
	function getdisagg(ryearId,rqtrId,ractId) {		
		var strURL="findDisagg.php?ryear="+ryearId+"&rqtr="+rqtrId+"&ract="+ractId;
		var req = getAJAXHTTPREQ();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('disaggdiv').innerHTML=req.responseText;						
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
<?php
	} //close only load on relevant module 
	

?>
<script type="text/javascript" src="../scripts/ajax_sections_refresh.js"></script>
<?php

//only load the script below when in My Tasks
if (($_SESSION['sec_submod']==2) && ($_SESSION['sec_uction']=="view_submod") )
    {
	?>
<script type="text/javascript" src="../scripts/ajax_sections_refresh_tabs_tasks.js"></script>    
	<?php
	}
?>


<?php
//use the script below if in BATCH VIEW LIST
if ($_SESSION['sec_submod']=="299999999")
	{
?>
<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
$("comment, .hide").hide();

$(".show").click(function() {
    $(this).parents('tr').find("comment,.hide").show();
    $(this).parents('tr').find(".show").hide();
});

$(".hide").click(function() {
    $(this).parents('tr').find("comment, .hide").hide();
    $(this).parents('tr').find(".show").show();
});
});//]]>  

</script>
<?php
}
?>

<script language="javascript">
function hide_tkt()
{
if (document.filter.tktno.value == "Ticket No.")
document.filter.tktno.value = "";
}

function show_tkt()
{
if (document.filter.tktno.value == "")
document.filter.tktno.value = "Ticket No.";
}

function hide_mobile()
{
if (document.filter.telno.value == "Mobile No.")
document.filter.telno.value = "";
}

function show_mobile()
{
if (document.filter.telno.value == "")
document.filter.telno.value = "Mobile No.";
}

function hide_ac()
{
if (document.filter.acno.value == "Account No.")
document.filter.acno.value = "";
}

function show_ac()
{
if (document.filter.acno.value == "")
document.filter.acno.value = "Account No.";
}

function hide_date()
{
if (document.filter.timestart.value == "Reported On")
document.filter.timestart.value = "";
}

function show_date()
{
if (document.filter.timestart.value == "")
document.filter.timestart.value = "Reported On";
}



</script>
<noscript>
<div id="warning" >
<p>Your browser doesn't support JavaScript or JavaScript support has been disabled. You must enable JavaScript to use this application.</p>
</div>
</noscript>
<style type="text/css">
/* <![CDATA[ */
/* THE DIV TO TABLE TECHNIQUE */
  #container {
    width: 100%;
    padding:0;
    display: table;
	background-color:#ffffff;
    }

  #row  {
    display: table-row;
    }


  #middle {
    padding: 10px;
    background:#ffffff;
    display: table-cell;
    }

    /* IE ONLY */
  * html #container {
   
    }
  * html #row {
    
    }
  * html #left {
    float:left;
    }
  * html #right {
    float:right;
    }
  * html #middle {
    float: left;
    width: expression((document.body.clientWidth * 0.8 - 322) + "px");
    }
  * html .cleaner {
    display:block;
    }

/* ]]> */
</style>
</head>

<body >

<div>
<?php require_once('../assets_backend/be_includes/header_loggedin.php');?>
</div>
<div id="container">
		<?php if ($_SESSION['sec_mod']!=1){ ?>
		<div id="left">
			<?php
            require_once('includes/menu_left.php');
             ?>
		</div>
		<?php } ?>
		<div id="middle">
		<?php
		if (isset($_SESSION['sec_submod']))
			{
            //get the section heading
            $sql_heading = "SELECT modulename,submodule FROM sysmodule INNER JOIN syssubmodule ON sysmodule.idsysmodule=syssubmodule.sysmodule_idsysmodule WHERE idsyssubmodule=".$_SESSION['sec_submod']." LIMIT 1 ";
            $res_heading = mysql_query($sql_heading);
            $fet_heading = mysql_fetch_array($res_heading);
            }
           //load the include files depending on the selected sub module
	if ($_SESSION['sec_mod']==1)
	{
	//require_once('module_dashboard.php'); //load the dashboard
	require_once('module_dashboard-.php'); //load the dashboard
	}
                            
	if (($_SESSION['sec_submod']==2) && ($_SESSION['sec_uction']=="view_submod") )
    {
	//by default, unless otherwise set the following tab as the default
	$_SESSION['parenttabview']=1;
                            if (isset($_GET['pageNum_rs_list']))
                                {
                                $_SESSION['pageNum_rs_list']=mysql_escape_string(trim($_GET['pageNum_rs_list']));
                                } else {
                                $_SESSION['pageNum_rs_list']=0;
                                }
                                
                                //listing                               
                                //check if ordering has been set
                                if (isset($_GET['orderlist']))
                                    {
                                    
                                    //clean the input and check against the following conditions with a default set for security purposes
                                        $order_logix=mysql_escape_string(trim($_GET['orderlist']));
                                        if ($order_logix=="descending")
                                            {
                                            $_SESSION['order_logix']=" DESC ";
                                            } else if ($order_logix=="ascending") {
                                            $_SESSION['order_logix']=" ASC ";
                                            } else {
                                            $_SESSION['order_logix']=" DESC ";
                                            }
            
                                            } else {
                                            $_SESSION['order_logix']=" DESC ";
                                            }
                                        
                                        if (isset($_GET['orderwhat']))
                                            {
                                            $orderwhat=mysql_escape_string(trim($_GET['orderwhat']));
                                            if ($orderwhat=="from")
                                                {
                                                $_SESSION['order_field']=" usrrole.usrrolename ";
                                                } else if ($orderwhat=="datein") {
                                                $_SESSION['order_field']=" wftasks.timeinactual ";
                                                } else if ($orderwhat=="deadline") {
                                                $_SESSION['order_field']=" wftasks.timedeadline ";
                                                } else if ($orderwhat=="trem") {
												 $_SESSION['order_field']=" time_to_deadline ";
												}
                                                
                                            } else {
                                            $_SESSION['order_field']=" wftasks.timetatstart ";
                                            }
                            if (isset($_GET['searchbox'])) //search box
                                { 
                                $_SESSION['searchbox']= preg_replace('/[^a-z\-_0-9\.:\/\s]/i','',mysql_escape_string(strip_tags(trim($_GET['searchbox'])))); 
                                }
							if (isset($_GET['search_region']))
								{
								$_SESSION['search_region']=mysql_escape_string(trim($_GET['search_region']));
								}
                            
                            echo "<div id=\"responsecontainer\"></div>";
                            }
                        
						if (($_SESSION['sec_submod']==299999999) && ($_SESSION['sec_uction']=="view_submod") )
                            {
							require_once('module_tasksin_new_batchlist.php'); //batch list
							}
							
						if (($_SESSION['sec_submod']==20002) && ($_SESSION['sec_uction']=="view_submod") )
/*						if ((isset($_GET['sel_action'])) && ($_GET['sel_action']=="assign")
							&& (isset($_GET['formaction']))	&& ($_GET['formaction']=="mass_assign")	
							&& ($_SESSION['sec_submod']==20002) && ($_SESSION['sec_uction']=="view_submod"))
*/							
                            {
							require_once('module_tasksin_mass_transfer.php'); //batch list
							}	
						
						if (($_SESSION['sec_submod']==59) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_mod']==23) )
                            {
                            require_once('module_escalations.php');//escalations
                            }
						
						if (($_SESSION['sec_submod']==60) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_mod']==24) )
                            {
                             require_once('module_notifications.php');//notification
                            }
							
						if ( ($_SESSION['sec_submod']==68) && ($_SESSION['sec_mod']==29)  &&  ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_ceg.php');
                            }	
							
						if (($_SESSION['sec_submod']==61) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_mod']==25) )
                            {
                            //require_once('module_tasksout.php');
							require_once('module_teamtasks.php');//notification
                            }
						
                        if (($_SESSION['sec_submod']==3) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_tasksout.php');
                            }
						if (($_SESSION['sec_submod']==41) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_tickets_followup.php');
                            }
						if (($_SESSION['sec_submod']==5) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_myteam.php');
                            }
						
						//REPORT CENTER
						if (($_SESSION['sec_mod']==5) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_reportcenter.php');
                            }
						
							
						if (($_SESSION['sec_submod']==22) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_smsbroadcast.php');
                            }
						
						if (($_SESSION['sec_submod']==23) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_smsbroadcast_stats.php');
                            }	
						
						if (($_SESSION['sec_submod']==87) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_sms_loading.php');
                            }	
							
                        if (($_SESSION['sec_submod']==6) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['parenttabview']!=2) )
                            {
                            require_once('module_tickets.php');
                            }
                        if (($_SESSION['sec_submod']==6) && ($_SESSION['parenttabview']==2) )
                            {
                            require_once('module_tickets_filter.php');
                            }
							
						if (($_SESSION['sec_submod']==63) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['parenttabview']!=2) )
                            {
                            require_once('module_tickets_admin.php');
                            }
                        if (($_SESSION['sec_submod']==63) && ($_SESSION['parenttabview']==2) )
                            {
                            require_once('module_tickets_filter_admin.php');
                            }	
                    
                        if (($_SESSION['sec_submod']==24) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_newticket.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==14) && ($_SESSION['sec_uction']=="view_submod")  && (isset($_SESSION['parenttabview'])) )
                            {
                            require_once('module_useracs.php'); //load the userprofiles
                            }
							
					/*	if (($_SESSION['sec_submod']==14) && ($_SESSION['sec_uction']=="view_submod")  && (isset($_SESSION['parenttabview']))  && ($_SESSION['parenttabview']==3))
                            {
                            require_once('../myac/module_useracs_search.php'); //load the userprofiles
                            }
					*/	
                        if (($_SESSION['sec_submod']==14) && ($_SESSION['sec_uction']=="add_usr") )
                            {
                           // echo "-000";
							require_once('module_useracsnew.php'); //load the userprofiles
							
                            }
                        if (($_SESSION['sec_submod']==14) && ($_SESSION['sec_uction']=="edit_submod") )
                            {
                            require_once('module_useracsedit.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==16) && ($_SESSION['sec_uction']=="view_submod"))
                            {
                            require_once('moduleaccess_team.php'); //load the module access main page
                            }
                        if (($_SESSION['sec_submod']==16) && ($_SESSION['sec_uction']=="add_submod_access"))
                            {
                            require_once('moduleaccess_team_add.php'); //load the module access main page
                            }
                        if (($_SESSION['sec_submod']==16) && ($_SESSION['sec_uction']=="edit_submod"))
                            {
                            require_once('moduleaccess_team_edit.php'); //load the module access main page
                            }
                        if (($_SESSION['sec_submod']==15) && ($_SESSION['parenttabview']!=3) && ($_SESSION['sec_uction']=="view_submod"))
                            {
                            require_once('moduleaccess_userprofiles.php'); //load the userprofiles
                            }
						if (($_SESSION['sec_submod']==15) && ($_SESSION['parenttabview']==3) && ($_SESSION['sec_uction']=="view_submod"))
							{
							 require_once('moduleaccess_userprofiles_filter.php'); //load the userprofiles
							}
                        if (($_SESSION['sec_submod']==15) && ($_SESSION['sec_uction']=="add_userprof_access"))
                            {
                            require_once('moduleaccess_userprofile_add.php'); //add user profiles
                            }
                        if (($_SESSION['sec_submod']==15) && ($_SESSION['sec_uction']=="edit_submod"))
                            {
                            require_once('moduleaccess_userprofile_edit.php'); //add user profiles
                            }
                        if (($_SESSION['sec_submod']==18) && ($_SESSION['sec_uction']=="view_submod"))
                            {
                            require_once('moduleaccess_teamconfig.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==18) && ($_SESSION['sec_uction']=="add_team"))
                            {
                            require_once('moduleaccess_teamadd.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==18) && ($_SESSION['sec_uction']=="edit_submod"))
                            {
                            require_once('moduleaccess_teamedit.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==18) && ($_SESSION['sec_uction']=="view_subsubmod") && ($_SESSION['parenttabview']==2) )
                            {
                            require_once('moduleaccess_zoneview.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==18) && ($_SESSION['sec_uction']=="edit_subsubmod") && ($_SESSION['parenttabview']==2) )
                            {
                            require_once('module_editzone.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==18) && ($_SESSION['sec_uction']=="add_subsubmod") && ($_SESSION['parenttabview']==2) )
                            {
                            require_once('module_newzone.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==17) && ($_SESSION['sec_uction']=="view_submod"))
                            {
                            echo "<div id=\"responsecontainer\"></div>";
                            //require_once('module_workflowtocat.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==17) && ($_SESSION['sec_uction']=="view_wfs"))
                            {
                            require_once('module_workflows.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==17) && ($_SESSION['sec_uction']=="new_workflow"))
                            {
                            require_once('module_newworkflow.php'); //load the userprofiles
                            }
                        if (($_SESSION['sec_submod']==17) && ($_SESSION['sec_uction']=="edit_workflow"))
                            {
                            //require_once('module_editworkflow.php'); //load the userprofiles
                            if (isset($_GET['wf']))
                                {
                                $_SESSION['wfselected'] = mysql_escape_string(trim($_GET['wf']));	
                                }
                            echo "<div id=\"responsecontainer\"></div>";
                            }
						if (($_SESSION['sec_submod']==17) && ($_SESSION['sec_uction']=="view_forms"))
                            {
                            require_once('module_workforms.php'); //load the userprofiles
                            }
						
						if ( ($_SESSION['sec_submod']==19) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['parenttabview']==3) )
                            		{
									 require_once('module_rolesgroups2_filter.php'); //load the userprofiles
									 exit;
                            		}
						
                        if ($_SESSION['sec_submod']==19) 
							{
							/*reset parent tab*/
							if ($_SESSION['parenttabview']==1)
									{
									$_SESSION['parenttabview']=1;
									}
							
								if (($_SESSION['sec_uction']=="view_submod") && (($_SESSION['parenttabview']==1) || ($_SESSION['parenttabview']==0)) )
									{
									require_once('module_rolesgroups2.php'); //load the userprofiles
									}
								if (($_SESSION['sec_uction']=="view_submod") && ($_SESSION['parenttabview']==4) )
									{
									require_once('module_rolesgroups_vacant.php'); //load the userprofiles
									}
                            }
							
                        if ($_SESSION['sec_submod']==19) 
							{
							/*reset parent tab*/
							if (($_SESSION['parenttabview'] >2) && ($_SESSION['parenttabview']!=3))
									{
									$_SESSION['parenttabview']=1;
									}
								if ( ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['parenttabview']==2) )
                            		{
									 require_once('module_groups.php'); //load the userprofiles
                            		}
							}
						
							
							
                        if ($_SESSION['sec_submod']==19)
							{
							/*reset parent tab*/
							if ($_SESSION['parenttabview'] >2)
									{
									$_SESSION['parenttabview']=1;
									}
									if (($_SESSION['sec_uction']=="add_role") && ($_SESSION['parenttabview']==1)  )
                            		{
		                            require_once('module_newrole.php'); //load the userprofiles
									}
                            }
							
                        if ($_SESSION['sec_submod']==19) 
							{
							/*reset parent tab*/
							if ($_SESSION['parenttabview'] >2)
									{
									$_SESSION['parenttabview']=1;
									}
									if ( ($_SESSION['sec_uction']=="edit_submod") && ($_SESSION['parenttabview']==1) )
                            		{
							         require_once('module_editrole.php'); //load the userprofiles
									}
                            }
							
                        if ($_SESSION['sec_submod']==19) 
							{
							/*reset parent tab*/
							if ($_SESSION['parenttabview'] >2)
									{
									$_SESSION['parenttabview']=1;
									}
								if (($_SESSION['sec_uction']=="add_group") && ($_SESSION['parenttabview']==2) )
                            	{
	                            require_once('module_newgroup.php'); //load the userprofiles
								}
                            }
                        if ($_SESSION['sec_submod']==19)
                            {
							/*reset parent tab*/
							if ($_SESSION['parenttabview'] >2)
									{
									$_SESSION['parenttabview']=1;
									}
								if  (($_SESSION['sec_uction']=="edit_submod") && ($_SESSION['parenttabview']==2) )
									{
		                            require_once('module_editgroup.php'); //load the userprofiles
									}
                            }
                        if (($_SESSION['sec_mod']==11) && ($_SESSION['sec_submod']==32) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_myaccount.php'); //load the userprofiles
                            }
						if (($_SESSION['sec_mod']==11) && ($_SESSION['sec_submod']==33) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_myaccountpwd.php'); //load the userprofiles
                            }

						
                        if (($_SESSION['sec_mod']==13) && ($_SESSION['sec_uction']=="view_submod") )
                            {
                            require_once('module_help.php'); //load the userprofiles
                            }
						if (($_SESSION['sec_mod']==15) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==44) )
                            {
                            require_once('module_wp_view.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==15) && ($_SESSION['sec_submod']==45) )
                            {
                            require_once('module_wp_new.php'); //local workplans
                          }
						if (($_SESSION['sec_mod']==15) && ($_SESSION['sec_submod']==44)  && ($_SESSION['sec_uction']=="edit_submod") )
                         {
                           require_once('module_wp_details.php'); //local workplans
	                      }
						if (($_SESSION['sec_mod']==16) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==46) )
                            {
                            require_once('module_wpevents_view.php'); //local workplans
                            }
                        if (($_SESSION['sec_mod']==16) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==47) )
                            {
                            require_once('module_wpevents_new.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==16) && ($_SESSION['sec_uction']=="edit_submod") && ($_SESSION['sec_submod']==46) )
                            {
                            require_once('module_wpevents_details.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==17) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==48) )
                            {
                            require_once('module_projects_view.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==17) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==49) )
                            {
                            require_once('module_projects_new.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==18) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==50) )
                            {
                            require_once('module_assets_view.php'); //local workplans
                            }
						
						if (($_SESSION['sec_mod']==18) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==51) )
                            {
                            require_once('module_asset_new.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==20) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==54) )
                            {
                            require_once('module_tickets_escalatedin.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==21) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==56) )
                            {
                            require_once('module_wp_review.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==21) && ($_SESSION['sec_uction']=="edit_submod") && ($_SESSION['sec_submod']==56) )
                            {
                            require_once('module_wp_review_details.php'); //local workplans
                            }
						if (($_SESSION['sec_mod']==20) && ($_SESSION['sec_uction']=="view_submod") && ($_SESSION['sec_submod']==55) )
                            {
                            require_once('module_mapview.php'); //local workplans
                            }
	
                    ?>
		</div>

</div>
<div id="footer" >
<?php require_once('../assets_backend/be_includes/footer_persistent.php'); ?>
</div>
<?php
mysql_close($connSystem);
?>
</body>
</html>