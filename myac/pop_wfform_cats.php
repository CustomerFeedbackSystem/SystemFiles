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
	

	//first, check if user owns the form about to be associated with this category
	$sql_owner="SELECT idwfprocforms FROM wfprocforms		
	WHERE wfprocforms.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND idwfprocforms=".$_SESSION['idform']." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	$num_owner=mysql_num_rows($res_owner);

	//echo "<br><br><br>".$sql_owner;
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
		$sql_exists="SELECT idwfprocforms_cats FROM wfprocforms_cats WHERE tktcategory_idtktcategory=".$accessid." AND wfprocforms_idwfprocforms=".$_SESSION['idform']." LIMIT 1";
		$res_exists=mysql_query($sql_exists);
		$num_exists=mysql_num_rows($res_exists);
		//echo "<br><br><br>".$sql_exists."<br>";
		//echo "<br><br><br>".$num_exists;
		if ($num_exists < 1) //if no record
			{
			//then if command
			if ($docmdval==1)
				{
				$sql_priv="INSERT INTO wfprocforms_cats (wfprocforms_idwfprocforms,tktcategory_idtktcategory,createdby,createdon)
				VALUES ('".$_SESSION['idform']."','".$accessid."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
				mysql_query($sql_priv);
				}
			} ///
			
		if ($docmdval==0)
				{
				$sql_priv="DELETE FROM wfprocforms_cats WHERE wfprocforms_idwfprocforms=".$_SESSION['idform']."
				AND tktcategory_idtktcategory=".$accessid." LIMIT 1";
				mysql_query($sql_priv);
				}
				
		}//delete_error
	}//do 


	
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
    <table border="0" cellpadding="0" cellspacing="0" width="610">
  		<tr>
        	<td >Form Categories Access</td>
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
        	<td valign="top" width="100%" style="padding:2px 0px">
	  <div>
                <div class="table_header" style="padding:8px 5px">Categories</div>
            	<div>
                <?php
				//list the profiles associated with this form
				$sql_cats="SELECT idtktcategory,tktcategoryname,
				(SELECT tktcategory_idtktcategory FROM wfprocforms_cats WHERE tktcategory_idtktcategory=idtktcategory AND wfprocforms_idwfprocforms=".$_SESSION['idform'].") as iko
				FROM tktcategory";
				$res_cats=mysql_query($sql_cats);
				$num_cats=mysql_num_rows($res_cats);
				$fet_cats=mysql_fetch_array($res_cats);
				//echo $sql_cats;
				if ($num_cats > 0)
					{
					do {
					?>
                    <div class="tbl_data" <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
                    <table border="0" width="100%" cellpadding="0" cellspacing="0">
                    	<tr>
                        	<td width="25">
		                    <a id="button_check<?php if ($fet_cats['iko']==$fet_cats['idtktcategory']) { $docmd="off"; echo "_on"; } else { $docmd="on"; } ?>" href="<?php echo $_SERVER['PHP_SELF']."?doto=perm_cat&accessid=".$fet_cats['idtktcategory']."&docmd=".$docmd."";?>"></a>                            </td>
                            <td align="left">                    
	                        <?php echo $fet_cats['tktcategoryname']; ?>                            </td>
						</tr>
					</table>
                    </div>
                    <?php 
// technocurve arc 3 php mv block3/3 start
if ($mocolor == $mocolor1) {
	$mocolor = $mocolor2;
} else {
	$mocolor = $mocolor1;
}
// technocurve arc 3 php mv block3/3 end
?>
                    <?php
						} while ($fet_cats=mysql_fetch_array($res_cats));
					} //greater than 0
				
				?>                  
                </div>
           </div>
            <!--- BR --->            
          </td>
            <td width="36%" valign="top">&nbsp;</td>
      </tr>
    </table> 

	</div>
</div>    
</body>
</html>
