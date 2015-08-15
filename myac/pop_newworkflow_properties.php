<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

if (isset($_GET['symbol']))
	{
	$_SESSION['asymbol']=mysql_escape_string(trim($_GET['symbol']));
	}

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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Window</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
//restrict to numbers or alpha
var numb = "0123456789";
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
</script>
<SCRIPT TYPE="text/JavaScript">
    function validateHhMm(inputField) {
        var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(inputField.value);

        if (isValid) {
            inputField.style.backgroundColor = '#bfa';
        } else {
            inputField.style.backgroundColor = '#fba';
        }

        return isValid;
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
</SCRIPT>

</head>
<body>
<div>
	<div class="tbl_sh">
    <table border="0" cellpadding="0" cellspacing="0" width="793">
  <tr>
        	<td width="660">
            <?php echo $_SESSION['wtitle'];?>
       		</td>
          	<td width="133" align="right">
            <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
            </td>
      </tr>
    </table>
    </div>
	<div>
    	<div class="tab_area">
        	<span class="tab_on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?tabview=1"><?php echo $tab_genprops;?></a></span>
<?php if ($_SESSION['asymbol']==2) { ?><span class="tab_off"><?php echo $lbl_actors;?></a></span><?php } ?>
<?php if ($_SESSION['asymbol']==2) { ?><span class="tab_off"><?php echo $tab_actions;?></a></span><?php } ?>
<?php if (($_SESSION['asymbol']==1) || ($_SESSION['asymbol']==2) ) { ?><span class="tab_off"><?php echo $tab_workinghrs;?></a><?php } ?></span>
<?php if (($_SESSION['asymbol']==1) || ($_SESSION['asymbol']==2) ) { ?><span class="tab_off"><?php echo $tab_wfnotify;?></a><?php } ?></span>
<?php if ($_SESSION['asymbol']==2) { ?><span class="tab_off"><?php echo $tab_esclations;?></a></span><?php } ?>
<?php if ($_SESSION['asymbol']==2) { ?><span class="tab_off"><?php echo $tab_customer_feedback;?></span><?php } ?>  
        </div>
    </div>
    <div style="padding:5px">
    <?php
	require_once('tabview_newproperties.php');  
	?>
    </div>
</div>
</body>
</html>
