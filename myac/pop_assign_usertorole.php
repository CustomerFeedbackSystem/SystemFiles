<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login.php');

if (isset($_GET['roleid']))
	{
	$_SESSION['this_roleid']=mysql_escape_string(trim($_GET['roleid']));
	
	//get the rolename 
	$sql_role="SELECT idusrrole,usrrolename,userteamzonename,idusrac FROM usrrole 
	INNER JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone 
	LEFT JOIN usrac ON usrrole.idusrrole=usrac.usrrole_idusrrole
	WHERE idusrrole=".$_SESSION['this_roleid']." AND usrteamzone.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_role=mysql_query($sql_role);
	$fet_role=mysql_fetch_array($res_role);
	$num_role=mysql_num_rows($res_role);
	
	if ($num_role<1)
		{
		echo "<div class=\"msg_warning\">".$ec100."</div>";
		exit;
		} else {
		$_SESSION['usrroleid_to']=$fet_role['idusrrole'];
		$_SESSION['usrrolename_to']=$fet_role['usrrolename'];
		$_SESSION['usrrolenamezone_to']=$fet_role['userteamzonename'];
		$_SESSION['occupied_by']=$fet_role['idusrac'];
	
		}
	
	}

if ((isset($_POST['formaction'])) && ($_POST['formaction']==1) && (strlen($_POST['account_usr'])>0) )
	{
	//clean up
	$usr_ac=mysql_escape_string(trim($_POST['account_usr']));
	
	//get the man number
	$usr_ac_ex = explode(',',$usr_ac);
	
	$usr_name=trim($usr_ac_ex[1]);
	
	//make sure it belongs to this water company - for cloud service 
	$sql_idusr="SELECT idusrac,usrrolename,userteamzonename,fname,lname,usrname,utitle,idusrrole,usrname FROM usrac 
	LEFT JOIN usrrole ON usrac.usrrole_idusrrole=usrrole.idusrrole 
	LEFT JOIN usrteamzone ON usrrole.usrteamzone_idusrteamzone=usrteamzone_idusrteamzone
	WHERE usrname='".$usr_name."' AND usrac.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_idusr=mysql_query($sql_idusr);
	$fet_idusr=mysql_fetch_array($res_idusr);
	$num_idusr=mysql_num_rows($res_idusr);
	//echo $sql_idusr;
	//if it does not, terminate process and report
	if ($num_idusr < 1)
		{
		echo "<div class=\"msg_warning\">".$ec100."</div>";
		exit;
		$show_form_2="NO";
		} else {
		$show_form_2="YES";
		$_SESSION['user_designate']=$fet_idusr['fname']." ".$fet_idusr['lname']." (".$fet_idusr['usrname'].")";
		//from this role
		}
	
	}


if ((isset($_POST['formaction'])) && ($_POST['formaction']==2) ) //if confirmation submitted then
	{
	//clean up records
	$vacate_usrrole=trim($_POST['v_usrrole']);
	$vacate_usrid=trim($_POST['v_usrac']);
	
	//audit_role releasing if occupied
	if  ( (isset($_SESSION['occupied_by'])) && ($_SESSION['occupied_by'] > 0) )
		{
		$sql_audit_1="INSERT INTO audit_usrrole_log (idusrrole,sysprofiles_idsysprofiles, usrteamzone_idusrteamzone, usrrolename, usrroledesc, reportingto,joblevel,usrdpts_idusrdpts,usrac_idusrac,modifiedby,modifiedon)
		SELECT idusrrole,sysprofiles_idsysprofiles, usrteamzone_idusrteamzone, usrrolename, usrroledesc, reportingto,joblevel,usrdpts_idusrdpts,'0','".$_SESSION['MVGitHub_idacname']."','".$timenowis."'
		FROM usrrole
		WHERE idusrrole=".$vacate_usrrole."";
		mysql_query($sql_audit_1);
		
		//then release the destination
		$sql_release_role="UPDATE usrac SET usrrole_idusrrole=NULL WHERE idusrac=".$_SESSION['occupied_by']." AND usrrole_idusrrole=".$_SESSION['this_roleid']."  LIMIT 1";
		mysql_query($sql_release_role);
		//echo $sql_release_role."<br>";
		}
	

	//then assign new role
	$sql_assign="UPDATE usrac SET usrrole_idusrrole='".$_SESSION['this_roleid']."' WHERE idusrac=".$vacate_usrid."";
	mysql_query($sql_assign);
	//echo $sql_assign;
	//audit_new role allocation
	$sql_audit_2="INSERT INTO audit_usrrole_log (idusrrole,sysprofiles_idsysprofiles, usrteamzone_idusrteamzone, usrrolename, usrroledesc, reportingto,joblevel,usrdpts_idusrdpts,usrac_idusrac,modifiedby,modifiedon)
	SELECT idusrrole,sysprofiles_idsysprofiles, usrteamzone_idusrteamzone, usrrolename, usrroledesc, reportingto,joblevel,usrdpts_idusrdpts,'".$vacate_usrid."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."'
	FROM usrrole
	WHERE idusrrole=".$_SESSION['this_roleid']."";
	mysql_query($sql_audit_2);
		
	//give confirmation message on a new page
	?>
    <script language="javascript">
	window.location="pop_assign_usrtorole_done.php";
	</script>
    <?php
	exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery.js"></script>
<script type="text/javascript" src="../scripts/jquery.autocomplete.js"></script>
<script type="text/javascript">
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
	
	
$().ready(function() {
	$("#account_usr").autocomplete("ajax_getuser.php", {
		width: 350,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
});
</script>
</head>

<body>
<div>
	<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px">
    <table border="0" cellpadding="0" cellspacing="0" width="<?php echo $_SESSION['tb_width'];?>">
  		<tr>
        	<td >
           <?php echo $_SESSION['usrrolename_to']." Located at  ".$_SESSION['usrrolenamezone_to'];?>            </td>
        	<td align="right">
            	<table border="0" cellpadding="0" cellspacing="0">
                	<tr>
                    	<td>&nbsp;</td>
                      <td><a href="#" onClick="parent.tb_remove();" id="button_closewin"></a></td>
                    </tr>
                 </table>
            	
            </td>
      </tr>
    </table>
    </div>
    <div style="padding:45px 10px 10px 10px">
<table border="0" width="100%">    
	<tr>
    	<td width="50%" valign="top">
       <?php
	   if ( (!isset($show_form_2)) ||  ((isset($show_form_2)) && ($show_form_2=="NO")) ) 
	   	{
		?>
        <form method="post" action="" autocomplete="off">
            <table border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td width="392" class="bg_section">
                    Step 1 : Find the User Account                </td>
                </tr>
                <tr>
                    <td height="37" valign="bottom" class="text_small_bold">
                     Assign <span style="color:#FF6600">
					 <?php echo $_SESSION['usrrolename_to']." Located at ".$_SESSION['usrrolenamezone_to'];?></span> to...</td>
              </tr>
                <tr>
                    <td class="text_body">
                    <input type="hidden" name="formaction" value="1" />
                   <input type="text" size="40" name="account_usr" id="account_usr" /> <input type="submit" value="NEXT" />
                    </td>
                </tr>
                </table>
        </form>
        <?php
		}
		?>
       <?php
	   if ((isset($show_form_2)) && ($show_form_2=="YES")) 
	   	{
		?>
        <form method="post" action="">
    	<table border="0" cellpadding="2" cellspacing="0">
        	<tr>
            	<td width="392" class="bg_section" colspan="2">
                Step 2 : Save &amp; Confirm
                  New Role</td>
			</tr>
            <tr>
            	<td height="30" class="tbl_data">Name </td>
                <td height="30" class="tbl_data"><?php echo $fet_idusr['utitle'];?> <?php echo $fet_idusr['fname'];?> <?php echo $fet_idusr['lname'];?></td>
          	</tr>
             <tr>
            	<td height="30" class="tbl_data">Account </td>
                <td height="30" class="tbl_data"><?php echo $fet_idusr['usrname'];?>
                <input type="hidden" value="<?php echo $fet_idusr['idusrrole'];?>" name="v_usrrole" />
                <input type="hidden" value="<?php echo $fet_idusr['idusrac'];?>" name="v_usrac" />
                </td>
          	</tr>
             <tr>
            	<td height="30" class="tbl_data">Current Role [ From ] </td>
                <td height="30" class="tbl_data">
				<?php 
				if ($fet_idusr['idusrrole']>0) { echo "<strong>".$fet_idusr['usrrolename']."</strong><br />".$fet_idusr['userteamzonename']; } else { echo "<span style=\"color:#ff0000\">---Not in Any Role ---</span>"; }?>
                </td>
          	</tr>
             <tr>
            	<td height="30" bgcolor="#E8FFE8" class="tbl_data"><strong>New Role [ To ] </strong></td>
                <td height="30" bgcolor="#E8FFE8" class="tbl_data">
				<?php echo "<strong>".$_SESSION['usrrolename_to']."</strong><br>".$_SESSION['usrrolenamezone_to'];
				?></td>
          	</tr>
            <tr>
            	<td height="50">
                <input type="button" onClick="window.location='pop_assign_usertorole.php'" value="&laquo; BACK" />
                </td>
            	<td class="text_body">
                <input type="hidden" name="formaction" value="2" />
                <input onClick="return confirm('Are you sure you want to proceed?\rClick OK to proceed or CANCEL');" type="submit" value="SAVE & CONFIRM" />
                </td>
            </tr>
            </table>
    </form>
    <?php
	}
	?>
    </td>
    <td width="50%" valign="top">
    <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <tr>
                    <td width="392" class="bg_section">Role-to-account Change History</td>
                </tr>
                <tr>
                  <td class="text_body" valign="top">
                  	<table border="0" width="100%" cellpadding="0" cellspacing="0">
  <tr>
                        	<td class="tbl_h2">
                            Role Name                            </td>
              <td class="tbl_h2">
                            Assigned To                            </td>
              <td class="tbl_h2">Date</td>
              <td class="tbl_h2">
                            By</td>
                      </tr>
                      <?php
					  //get initial creation date
					  $sql_audit="SELECT usrrolename,usrname,fname,lname,audit_usrrole_log.modifiedby,audit_usrrole_log.modifiedon,
					  (SELECT usrname FROM usrac WHERE idusrac=audit_usrrole_log.modifiedby) as changer
					  FROM audit_usrrole_log 
					  LEFT JOIN usrac ON audit_usrrole_log.usrac_idusrac=usrac.idusrac
					  WHERE audit_usrrole_log.idusrrole=".$_SESSION['this_roleid']."
					  ORDER BY audit_usrrole_log.modifiedon DESC";
					  $res_audit=mysql_query($sql_audit);
					  $fet_audit=mysql_fetch_array($res_audit);
					  $num_audit=mysql_num_rows($res_audit);
					  //echo $sql_audit;
					  if ($num_audit > 0)
					  	{
						do {
					  ?>
                      <tr>
                        <td class="tbl_data"><?php echo $fet_audit['usrrolename'];?></td>
						<td class="tbl_data"><?php echo $fet_audit['fname'];?> <?php echo $fet_audit['lname'];?> <br>
                        <?php echo $fet_audit['usrname'];?></td>
						<td class="tbl_data"><?php echo $fet_audit['modifiedon'];?></td>
						<td class="tbl_data"><?php echo $fet_audit['changer'];?></td>
                      </tr>
                     <?php
					 } while ($fet_audit=mysql_fetch_array($res_audit));
					 }
					 ?>
                    </table>
                  </td>
                </tr>
                </table>
    </td>
</tr>
</table>
    </div>
</div>    
</body>
</html>

