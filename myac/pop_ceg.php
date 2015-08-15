<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login.php');

if (!isset($_SESSION['tabview_con'])) //set default tab
	{
	$_SESSION['tabview_con'] = 1;
	}

if ( (isset($_SESSION['tabview_con'])) && ($_SESSION['tabview_con']!=1) && ($_SESSION['tabview_con']!=2) ) //set default tab
	{
	$_SESSION['tabview_con'] = 1;
	}

if (isset($_GET['tabview']))
	{
	$_SESSION['tabview_con'] = mysql_escape_string(trim($_GET['tabview']));
	}

if (isset($_GET['region'])) { $_SESSION['region']=mysql_real_escape_string(trim($_GET['region'])); }
if (isset($_GET['cat'])) { $_SESSION['cat']=mysql_real_escape_string(trim($_GET['cat'])); }

$res_cat=mysql_query("SELECT tktcategoryname FROM tktcategory WHERE idtktcategory=".$_SESSION['cat']."");
$fet_cat=mysql_fetch_array($res_cat);

$res_reg=mysql_query("SELECT userteamzonename FROM usrteamzone WHERE idusrteamzone=".$_SESSION['region']."");
$fet_reg=mysql_fetch_array($res_reg);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>

<!--<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>-->
<script type="text/javascript" src="../scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-timepicker-addon_.js"></script>

<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../thickbox/thickbox_be.js"></script>
<script type="text/javascript" src="../scripts/jquery.autocomplete.js"></script>

<title>Overdue Team Tasks</title>
<script language="javascript">
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

//select all check boxes or disselect
function selectAll(state) {
for (i = 0; i < document.form.elements.length; i++) {
var checkbox = document.form.elements[i];
checkbox.checked = state;
}
}

//checkbox mass list validate
function checkbox_tasks() {
if (document.form.sel_action.value=="")
	{
	alert ('Please select the action you wish to perform');
	document.form.sel_action.focus();
	return false;
	}
	
	if (document.form.sel_action.value=="transfer")
	{
	return confirm ('Are you sure you want to Transfer the selected tasks?');
	}

return true;
}

//autocomplete the staff 
$().ready(function() {
	$("#recepient_alt").autocomplete("findrole_alt_transfer_ceg.php", {
		width: 410,
		matchContains: true,
		//mustMatch: true
		//minChars: 0,
		//multiple: true,
		//highlight: true,
		//multipleSeparator: ",",
		selectFirst: true
	});
});



</script>
<script type="text/javascript">
//search page 
function hidetxt()
	{
		if (document.send_msg.recepient_alt.value=="Search a User by Name or Role")
			{
			document.send_msg.recepient_alt.value='';
			}
	return true;
	}

function showtxt()
	{
		if (document.send_msg.recepient_alt.value=='')
			{
			document.send_msg.recepient_alt.value='Search a User by Name or Role';
			}
	return true;
	}
</script>
<script type="text/javascript" src="../uilock/jquery.uilock.js"></script>
<script language="javascript">
//Preloader for Batch Processing
			$(document).ready(function() {
				//$('#lock').click(function(){
				$('#button_transfer').click(function(){
				
					// To lock user interface interactions
					// Optinal: put html on top of the lock section,
					// like animated loading gif
					
					//$.uiLock('some html and <a href="#" onclick="$.uiUnlock();">unlock</a>');
				$.uiLock('<center class=msg_ok_overlay>Transferring... Please wait...</center>');
					
				});
				
				
				// To unlock user interface interactions
				//$.uiUnlock();

			});
</script>	
</head>
<body>
<div>
<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px; width:<?php echo $_SESSION['tb_width'];?>">
<table border="0" cellpadding="0" cellspacing="0" width="<?php echo $_SESSION['tb_width'];?>">
	<tr>
    	<td width="100%" >
		<div>
        <?php echo $fet_cat['tktcategoryname'];?> tickets - <?php echo $_SESSION['odue'];?> Days overdue - <?php echo $fet_reg['userteamzonename'];?>
		</div>
		</td>
		<td align="right">
		<a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
		</td>
	</tr>
</table>
</div>
<div style="padding:35px 0px">
	<div>
    	<div class="tab_area">
            <span class="tab<?php if ($_SESSION['tabview_con']==1){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=1"><?php echo $fet_reg['userteamzonename'];?> users with tasks</a></span>
            <span class="tab<?php if ($_SESSION['tabview_con']==3){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=3">Head Office users with tasks</a></span>
            <span class="tab<?php if ($_SESSION['tabview_con']==2){ echo "_on"; } ?>"><a href="<?php $_SERVER['PHP_SELF'];?>?tabview=2">Send Specific Message</a></span>
        </div>
	</div>
    <div>
    <?php
			if (isset($_SESSION['tabview_con']))
				{
					if ($_SESSION['tabview_con']==1)
						{
						require_once('pop_ceg_1.php');
						}
					if ($_SESSION['tabview_con']==2)
						{
						require_once('pop_ceg_2.php');
						}
					if ($_SESSION['tabview_con']==3)
						{
						require_once('pop_ceg_3.php');
						}

			}
	?>
    </div>
</div>
</div>
</body>
</html>