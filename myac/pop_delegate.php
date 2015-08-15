<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

//echo "<br><br><br><br><br>";

$sql_deleg="SELECT idwftasksdeleg_meta,usrrolename,utitle,fname,lname,wftasksdeleg_meta.time_request,usremail FROM wftasksdeleg_meta
INNER JOIN usrrole ON wftasksdeleg_meta.idusrrole_to=usrrole.idusrrole
INNER JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
WHERE wftasksdeleg_meta.idusrrole_from=".$_SESSION['MVGitHub_iduserrole']." AND wftasksdeleg_meta.deleg_status=1 LIMIT 1";
$res_deleg=mysql_query($sql_deleg);
$num_deleg=mysql_num_rows($res_deleg);
$fet_deleg=mysql_fetch_array($res_deleg);
//echo $sql_deleg;

if ($num_deleg > 0) //if this user has already delegated, then show a different interface
	{
	$form_one="HIDE"; //hide the data entry form
	$form_two="SHOW"; //show form two
	} else {
	$form_one="SHOW"; //else display the entry form
	$form_two="HIDE"; //hide the recall form
	}
	
//RECALL TASK PROCESS
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="recall") )
	{
	//clean up
	$var_msg=trim(mysql_escape_string($_POST['msg_deleg']));
	if (isset($_POST['confirm']))
		{
	$var_confirm=trim(mysql_escape_string($_POST['confirm']));
		}
	
	//validate
	if (strlen($var_msg)<4) //
		{
		$error_1="<div class=\"msg_warning\">Please compose a message to notify that you have taken back your tasks</div>";
		} //
	
	if (!isset($var_confirm)) //
		{
		$error_2="<div class=\"msg_warning\">Please check the Recall my Task option below</div>";
		} //
		
	if ( (!isset($error_1)) && (!isset($error_2)) )
		{
		//update the record
		$sql_update="UPDATE wftasksdeleg_meta SET 
		time_recall='".$timenowis."',
		msg_recall='".$var_msg."',
		recall_by_idusrac='".$_SESSION['MVGitHub_idacname']."',
		recall_by_idrole='".$_SESSION['MVGitHub_iduserrole']."',
		deleg_status='2',
		modifiedby='".$_SESSION['MVGitHub_idacname']."',
		modifiedon='".$timenowis."'
		WHERE idwftasksdeleg_meta=".$fet_deleg['idwftasksdeleg_meta']."";
		
		mysql_query($sql_update);
		//echo $sql_update;
		//display success message
		$msg_success="<div class=\"msg_success\">Your tasks have been recalled successfully and you can now Act on them</div>";
		
		//email the sender
		$recepient_mail=$fet_deleg['usremail'];
		
		if (strlen($recepient_mail) > 5)
			{
						$message = "Dear ".$fet_deleg['utitle']." ".$fet_deleg['lname'].",\n
						". $_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname'] ." wanted to alert you that he/she has recalled his/her tasks.\n########################\n".$var_msg.".\n\n\nBest Regards,\n\nSupport Team,\n".$pagetitle.".\n\nDISCLAIMER: You received this email because your email address was used on ".$pagetitle.".The Information contained in this email, including the links, is intended solely for the use of the designated recipient.If you have received this e-mail message in error please notify the ".$pagetitle." team through e-mail ".$support_email." and delete it immediately";
							// Additional headers
							$sendername=$_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname'];	
							
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers .= 'From: '.$_SESSION['MVGitHub_usrfname'].' <'.$support_email.'>' . "\r\n";
							$headers .= "Reply-To: ".$support_email."\r\n";
							$headers .= "Return-Path: ".$support_email."\r\n";
								
							$subject = "".$pagetitle." Task Delegation";
							
							$sql_mailout="INSERT INTO mdata_emailsout (email_to,email_subject,email_message,email_headers,createdon) 
							VALUES ('".$fet_deleg['usremail']."','".$subject."','".$message."','".$headers."','".$timenowis."')";
							mysql_query($sql_mailout);
							
							
			}
			
			$form_two="HIDE";
			$form_one="HIDE";
		
		} //if no error
	
	} //end Recall IF

//DELEGATE TASKS PROCESS
if ( (isset($_POST['formaction'])) && ($_POST['formaction']=="delegate") )
	{
	//clean up the values
	$delegate_to=trim(mysql_escape_string($_POST['delegate_to']));
	$msg_deleg=trim(mysql_escape_string($_POST['msg_deleg']));
	$cctrim = trim($_POST['facebook-demo']);
	
	//if delegation key is required
	if (isset($_POST['delegation_key'])) //if a key is required
		{
		$keygen=trim(mysql_escape_string($_POST['delegation_key']));
		$key_required=1;
		
		} else {
		
		$key_required=0;
		
		}
		
	if ($delegate_to <1)
		{
		$error_1="<div class=\"msg_warning\">Please Select the Recepient</div>";
		}
	
	if (strlen($msg_deleg)<2)
		{
		$error_2="<div class=\"msg_warning\">Please enter a Message for the Recepient </div>";
		}
	
	if ( ($key_required==1) && (strlen($keygen)<6) )
		{
		$error_3="<div class=\"msg_warning\">Please enter the Key </div>";
		}
		
	if ( (!isset($error_3)) && ($key_required==1) ) //if no error above and there is a key required, then validate this key
		{
		//query the db for an available key by this person
		$sql_key="SELECT authkey FROM wftasksdeleg_key WHERE authkey='".$keygen."' AND requested_by_idrole=".$delegate_to." AND use_status=0 LIMIT 1";
		$res_key=mysql_query($sql_key);
		$num_key=mysql_num_rows($res_key);

		if ($num_key<1)
			{
			$sql_rec="SELECT usrac.utitle,usrac.fname,usrac.lname FROM usrac WHERE usrrole_idusrrole=".$delegate_to." LIMIT 1";
			$res_rec=mysql_query($sql_rec);
			$fet_rec=mysql_fetch_array($res_rec);
			$error_4="<div class=\"msg_warning\">Invalid Key. Please request ".$fet_rec['utitle']." ".$fet_rec['fname']." ".$fet_rec['lname']." for a Key</div>";
			}
		
		}
		
	if ( (!isset($error_1)) || (!isset($error_2)) || (!isset($error_3)) || (!isset($error_4)) ) //validate
		{
		//recepient details
		$sql_recepient="SELECT utitle,fname,lname FROM usrac WHERE  usrrole_idusrrole=".$delegate_to." LIMIT 1";
		$res_recepient=mysql_query($sql_recepient);
		$fet_recepient=mysql_fetch_array($res_recepient);
		
		if (isset($_POST['delegation_key'])) //if delegation key is set
			{
			//first, if the key goes well, then mark it as used
			$sql_updatekey="UPDATE wftasksdeleg_key SET use_status='1' WHERE authkey='".$keygen."' AND requested_by_idrole=".$delegate_to." LIMIT 1";
			mysql_query($sql_updatekey);
			}
		//second, effect this transaction in the following stages
		
		//1. //////////////////find out and loop ALL TSKFLOWS that this user is involved at the role level
		$sql_mytasks="SELECT wftskflow_idwftskflow FROM wfactors WHERE usrgroup_idusrgroup=0 AND usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." ORDER BY idwfactors";
		$res_mytasks=mysql_query($sql_mytasks);
		$num_mytasks=mysql_num_rows($res_mytasks);
		$fet_mytasks=mysql_fetch_array($res_mytasks);
		
		//Insert into wftaskdeleg
		if (isset($_POST['delegation_key'])) //if delegation key is set
			{
			$keygen_isset=1;
			$sql_delegate="INSERT INTO wftasksdeleg_meta (idusrrole_from, idusrrole_to, time_request, authenticate_key, msg_request, deleg_status, createdby,createdon) 
			VALUES ('".$_SESSION['MVGitHub_iduserrole']."','".$delegate_to."','".$timenowis."','".$keygen."','".$msg_deleg."','1','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			} else {
			$keygen_isset=0;
			$sql_delegate="INSERT INTO wftasksdeleg_meta (idusrrole_from, idusrrole_to, time_request, authenticate_key, msg_request, deleg_status, createdby,createdon) 
			VALUES ('".$_SESSION['MVGitHub_iduserrole']."','".$delegate_to."','".$timenowis."','0','".$msg_deleg."','1','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			}
		mysql_query($sql_delegate);
		
		//retrieve that value of the deleg
		$sql_delegateid="SELECT idwftasksdeleg_meta FROM wftasksdeleg_meta WHERE createdby=".$_SESSION['MVGitHub_idacname']." ORDER BY idwftasksdeleg_meta DESC LIMIT 1";
		$res_delegateid=mysql_query($sql_delegateid);
		$fet_delegateid=mysql_fetch_array($res_delegateid);
	//	echo $sql_delegateid."<br>";
		do {
		//1b insert this into the database for each taskflow
			$sql_delegate="INSERT INTO wftasksdeleg (wftasksdeleg_meta_idwftasksdeleg_meta, wftskflow_idwftskflow, time_transaction, createdby,createdon ) 
			VALUES ('".$fet_delegateid['idwftasksdeleg_meta']."','".$fet_mytasks['wftskflow_idwftskflow']."','".$timenowis."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_delegate);
			//echo $sql_delegate;
			} while ($fet_delegateid=mysql_fetch_array($res_delegateid));
			
		
		//2. ///////////////////////find out and loop ALL TSKFLOWS that this user is involved at the group level
		$sql_mytasks2="SELECT wfactors.wftskflow_idwftskflow FROM wfactors
		INNER JOIN link_userrole_usergroup ON wfactors.usrgroup_idusrgroup=link_userrole_usergroup.usrgroup_idusrgroup
		WHERE wfactors.usrgroup_idusrgroup>0 AND link_userrole_usergroup.usrrole_idusrrole=".$_SESSION['MVGitHub_iduserrole']." 
		ORDER BY idwfactors";
		$res_mytasks2=mysql_query($sql_mytasks2);
		$num_mytasks2=mysql_num_rows($res_mytasks2);
		$fet_mytasks2=mysql_fetch_array($res_mytasks2);
		
		do {
		//2b insert this into the database for each taskflow
			if ($keygen_isset==1)
				{
				$sql_delegate="INSERT INTO wftasksdeleg (wftskflow_idwftskflow, idusrrole_from, idusrrole_to, time_request, time_authenticated, authenticate_key, msg_request, deleg_status, from_level, to_level) 
				VALUES ('".$fet_mytasks2['wftskflow_idwftskflow']."','".$_SESSION['MVGitHub_iduserrole']."','".$delegate_to."','".$timenowis."','".$timenowis."','".$keygen."','".$msg_deleg."','1','".$_SESSION['MVGitHub_joblevel']."','0')";
				} else {
				$sql_delegate="INSERT INTO wftasksdeleg (wftskflow_idwftskflow, idusrrole_from, idusrrole_to, time_request, time_authenticated, authenticate_key, msg_request, deleg_status, from_level, to_level) 
				VALUES ('".$fet_mytasks2['wftskflow_idwftskflow']."','".$_SESSION['MVGitHub_iduserrole']."','".$delegate_to."','".$timenowis."','".$timenowis."','0','".$msg_deleg."','1','".$_SESSION['MVGitHub_joblevel']."','0')";
				}
			mysql_query($sql_delegate);
			
			} while ($fet_mytasks2=mysql_fetch_array($res_mytasks2));
				
			
			
		//third, if cc is filled in, then we need to do the following
		if ($cctrim > 0) //cc
			{
				$ccarray=  str_replace("###",",","".$cctrim."");
				//print_r (explode(",",$tcvalue));
				$ccxplod =  (explode(",",$ccarray));
				
				$ccvals="";	
				
				foreach($ccxplod as $cc)
					{
					$cleancc = trim(strip_tags($cc)); 
					
					//loop and find the names for each id and the value
					$sql_cc="SELECT idusrac,fname,lname,usremail,utitle FROM usrac WHERE idusrac=".$cleancc." LIMIT 1";
					$res_cc=mysql_query($sql_cc);
					$fet_cc=mysql_fetch_array($res_cc);
					
					$ccvals.="<li value=\"".$fet_cc['idusrac']."\">".$fet_cc['fname']." ".$fet_cc['lname']."</li>\r\n";
					
					//email if the mail is valid and exists
					if (strlen($fet_cc['usremail']) > 5)//email >5
						{		
						$message = "Dear ".$fet_cc['utitle']." ".$fet_cc['lname'].",\n
						". $_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname'] ." wanted to alert you that he/she has just delegated his/her tasks to ".$fet_recepient['utitle']." ".$fet_recepient['fname']." ".$fet_recepient['lname']." .\n########################\n".$msg_deleg.".\n\n\nBest Regards,\n\nSupport Team,\n".$pagetitle.".\n\nDISCLAIMER: You received this email because your email address was used on ".$pagetitle.".The Information contained in this email, including the links, is intended solely for the use of the designated recipient.If you have received this e-mail message in error please notify the ".$pagetitle." team through e-mail ".$support_email." and delete it immediately";
							// Additional headers
							$sendername=$_SESSION['MVGitHub_usrfname']." ".$_SESSION['MVGitHub_usrlname'];	
							
							// To send HTML mail, the Content-type header must be set
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers .= 'From: '.$_SESSION['MVGitHub_usrfname'].' <'.$support_email.'>' . "\r\n";
							$headers .= "Reply-To: ".$support_email."\r\n";
							$headers .= "Return-Path: ".$support_email."\r\n";
								
							$subject = "".$pagetitle." Task Delegation";
							
							$sql_mailout="INSERT INTO mdata_emailsout (email_to,email_subject,email_message,email_headers,createdon) 
							VALUES ('".$fet_cc['usremail']."','".$subject."','".$message."','".$headers."','".$timenowis."')";
							mysql_query($sql_mailout);
						} //email >5
//					echo $cleancc."<br>";
					}
				
			} //cc
			$form_one="HIDE"; //hide the data entry form
			$form_two="HIDE";
			$msg_success="<div class=\"msg_success\">Your Tasks have be Delegated to  ".$fet_recepient['utitle']." ".$fet_recepient['fname']." ".$fet_recepient['lname']." Successfully! </div>";
			
		} //if validate
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="protomultiselect/proto.css" type="text/css" media="screen" charset="utf-8" />
<script src="protomultiselect/protoculous-effects-shrinkvars.js" type="text/javascript" charset="utf-8"></script>
<script src="protomultiselect/textboxlist.js" type="text/javascript" charset="utf-8"></script>
<script src="protomultiselect/proto.js" type="text/javascript" charset="utf-8"></script>
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
	
	
function getkey(recepientId) {		
		
		var strURL="pop_delegate_key.php?recepientId="+recepientId;
		var req = getAJAXHTTPREQ();
			
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('keydiv').innerHTML=req.responseText;
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
</head>
<body>
<div>
	<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
  		<tr>
        	<td width="660">
            Delegate your Tasks
            </td>
            <td>&nbsp;</td>
        	<td align="right">
            	<a href="#" onClick="parent.tb_remove();<?php if (isset($msg_success)) { echo "parent.location.reload(1)"; } ?>" id="button_closewin"></a>
            </td>
      </tr>
    </table>
    </div>
  
    <div style="padding:20px 10px 10px 10px">
<?php
if ( ($form_one=="HIDE") && (isset($msg_success)) ) {	echo $msg_success;	}
if ( ($form_one=="HIDE") && ($form_two=="SHOW") )
	{
	if (isset($error_1)) { echo $error_1; }
	if (isset($error_2)) { echo $error_2; }
	
?>
<div style="background-image:url(../assets_backend/images/bg_section.jpg); background-repeat:repeat-x">
<form method="post" action="" name="recall" >
<table border="0" width="710" cellpadding="2" cellspacing="0">
	<tr>
    	<td class="tbl_data" colspan="2">
        <div class="msg_instructions">
        All your Tasks are Currently Delegated to :
        <strong><?php echo $fet_deleg['usrrolename']; ?></strong>
        (<?php echo $fet_deleg['utitle']." ".$fet_deleg['fname']." ".$fet_deleg['lname'];?>)
        </div>
        </td>
    </tr>
	<tr>
		<td width="245" height="40" class="tbl_data">
        <strong>Delegated On :</strong>
		</td>
		<td width="457" height="40" class="tbl_data">
        <?php echo date("D, M d, Y H:i",strtotime($fet_deleg['time_request'])); ?>
        </td>
	</tr>
    <tr>
		<td height="23" colspan="2" class="tbl_h2" >Recall your Tasks</td>
	</tr>
    <tr>
		<td width="245" height="40" valign="top" class="tbl_data">
        <strong>Compose a Message :</strong><br />
        <small>(Will be sent to <?php echo $fet_deleg['utitle']." ".$fet_deleg['fname']." ".$fet_deleg['lname'];?> as an email)</small>
        </td>
		<td width="457" height="40" class="tbl_data" valign="top">
        <textarea cols="40" rows="4" name="msg_deleg" tabindex="4"><?php if (isset($_POST['msg_deleg'])) { echo $_POST['msg_deleg']; }?></textarea>
        </td>
	</tr>
     <tr>
     	<td  class="tbl_data"></td>
		<td width="245" height="40" valign="middle" class="tbl_data">
        <label for="confirm">
        <strong>
        <input name="confirm" type="checkbox" id="confirm" value="1" /> 
        I would like to recall back all my Delegated Tasks        </strong>
        </label>
        </td>
	</tr>
    <tr>
    	<td></td>
        <td height="55">
        <input type="hidden" value="recall" name="formaction" />
        <a href="#" onclick="document.forms['recall'].submit()" id="button_submit"></a>        </td>
    </tr>
</table>
</form>
</div>
<?php
	}
?>

<?php
if ( ($form_two=="HIDE") && ($form_one=="SHOW") )
	{
?>    
    
    <div>
    <?php
    if (isset($error_1)) { echo $error_1; }
	if (isset($error_2)) { echo $error_2; }
	if (isset($error_3)) { echo $error_3; }
	if (isset($error_4)) { echo $error_4; }
	if (isset($msg_success)) { echo $msg_success; }
	?>
    </div>
    <div>
    <div class="msg_instructions">
    Please fill in the form below to Delegate all your tasks
    </div>
    </div>
    <div style="background-image:url(../assets_backend/images/bg_section.jpg); background-repeat:repeat-x">
    <form method="post" action="" name="delegate" accept-charset="utf-8" autocomplete="off">
    	<table border="0" width="710" cellpadding="2" cellspacing="0">
  <tr>
				<td width="245" height="40" class="tbl_data">
                <strong>Tasks to ( Recepient ) :</strong>                </td>
		  <td width="457" height="40" class="tbl_data">
                <select name="delegate_to" tabindex="1" onchange="getkey(this.value)" onblur="getkey(this.value)"  >
                	<option value="">---</option>
                    <?php
					$sql_users="SELECT idusrac,usrname,idusrrole,usrrolename,fname,lname,utitle FROM usrac 
					INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole 
					WHERE usrrole.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." 
					AND idusrac!=".$_SESSION['MVGitHub_idacname']."
					ORDER BY fname ASC";
					$res_users=mysql_query($sql_users);
					$fet_users=mysql_fetch_array($res_users);
					
					do {
						echo "<option ";
						if ( (isset($_POST['delegate_to'])) && ($_POST['delegate_to']==$fet_users['idusrrole']) ) 
							{
							echo " selected=\"selected\" ";
							}					
						echo " value=\"".$fet_users['idusrrole']."\">".$fet_users['fname']." ".$fet_users['lname']." (".$fet_users['usrrolename'].")</option>";
					} while ($fet_users=mysql_fetch_array($res_users));
					?>
                </select>
                </td>
		  </tr>
          
            <tr>
				<td height="40" class="tbl_data">
                <strong>Person(s) to Notify <a tabindex="-1" href="#" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span>To Send email to Many, please separate the names with a , (comma) </span></a> :</strong></td>
			  <td height="40" class="tbl_data">
<ol>
	<li id="facebook-list" class="input-text">
	<input type="text" value="" tabindex="2" id="facebook-demo" name="facebook-demo" />
	<div id="facebook-auto">
	<div class="default">Type the name of the person </div> 
	<ul class="feed">
     <?php if (isset($ccvals)) { echo $ccvals; } ?>
    </ul>
    </div>
	</li>
</ol>  
              </td>
		  </tr>
          <tr>
          	<td colspan="2" style="margin:0px; padding:0px 0px 0px 10px">
          <div id="keydiv" style="display:block" >
          <?php
if (isset($_POST['delegation_key']))
	{
//query the db see if this users level is higher or equal to the recepient
$sql_lvl="SELECT idusrac,usrname,idusrrole,usrrolename,fname,lname,joblevel,utitle FROM usrac 
		INNER JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole 
		WHERE usrrole.idusrrole=".$delegate_to." LIMIT 1";	
$res_lvl=mysql_query($sql_lvl);		
$fet_lvl=mysql_fetch_array($res_lvl);

if ($fet_lvl['joblevel'] <=$_SESSION['MVGitHub_joblevel']) //only if the Job Level am passing to is Higher or Equal to mine
	{
		echo "<table border=\"0\">
			<tr>
				<td width=\"226px\" style=\"padding:0px 0px 0px 2px; margin:0px\" height=\"40\" class=\"tbl_data\">
				<strong>Delegation Key <a href=\"#\" 
				style=\"text-decoration:none;\" class=\"tooltip\">
				<img src=\"../assets_backend/icons/help.gif\" border=\"0\" 
				align=\"absmiddle\" />
				<span>You need to request ".$fet_lvl['utitle']." ".$fet_lvl['fname']." ".$fet_lvl['lname']." for this Key</span></a> :</strong>
				</td>
				<td style=\"padding:0px 0px 0px 2px; margin:0px; \"  bgcolor=#ffffcc>
				
				 <div class=\"border_thin\" style=\"padding:3px\">
					   <div>
					   <input type=\"text\" maxlength=\"6\" onkeyup=\"this.value=this.value.toUpperCase();\"  value=\"\" tabindex=\"3\" name=\"delegation_key\" size=\"10\" />
					   </div>
					   <div class=\"text_small\">
					   <strong>
					   Please request ".$fet_lvl['utitle']." ".$fet_lvl['fname']." ".$fet_lvl['lname']." for this Key
					   </strong>
					   </div>
				  </div>			
				</td>
			</tr>
		</table>";
		}
	}
		  ?>
          </div>
          	</td>
		</tr>
		<tr>
			<td height="94" valign="top" class="tbl_data">
			<strong>Your Message <a href="#" tabindex="-1" style="text-decoration:none;" class="tooltip"><img src="../assets_backend/icons/help.gif" border="0" align="absmiddle" /><span>A message to the person you are delegating your tasks to </span></a> :</strong>                </td>
		  <td valign="top" class="tbl_data">
			<textarea cols="40" rows="4" name="msg_deleg" tabindex="4"><?php if (isset($_POST['msg_deleg'])) { echo $_POST['msg_deleg']; }?></textarea>		  </td>
		</tr>
        <tr>
        	<td></td>
            <td height="55">
            <input type="hidden" value="delegate" name="formaction" />
            <a href="#" onClick="tlist2.update(); $('facebook-demo').value = $F('facebook-demo');document.forms['delegate'].submit()" id="button_send"></a>            </td>
        </tr>
      </table>
    </form>
    </div>
    </div>
</div>
   <script language="JavaScript">
        document.observe('dom:loaded', function() {
        
        
          // init
          tlist2 = new FacebookList('facebook-demo', 'facebook-auto',{fetchFile:'find_cc.php'});
          
          // fetch and feed
          new Ajax.Request('find_cc.php', {
            onSuccess: function(transport) {
                transport.responseText.evalJSON(true).each(function(t){tlist2.autoFeed(t)});
            }
          });
        });    
    </script>
<?php
}
?>    
</body>
</html>
