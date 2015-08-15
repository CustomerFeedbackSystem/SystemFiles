<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

//echo "<br><br><br><br><br><br>";
//echo $password_connSystem;
if (isset($_GET['cat']))
	{
	$_SESSION['thisticketcat']=mysql_escape_string(trim($_GET['cat']));
	}
//if user selects, then run
if (isset($_GET['assignwf']))
	{
	//echo "tst";
	//clean up the value
	$wftoassign=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['assignwf'])));	
	$ptodo=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['todo'])));

	
	// if it is set to 1, then it means you add
	//check if this user owns this record
	$sql_owner="SELECT * FROM wfproc WHERE wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." LIMIT 1";
	$res_owner=mysql_query($sql_owner);
	$num_owner=mysql_num_rows($res_owner);
		
	if ($num_owner<1)
		{
		echo "<div class=\"msg_warning\">".$ec100."</div>";
		exit;
		}
		
	//check the value selected
	if ($ptodo==1)
		{
		//if a record already exists
		$sql_exists="SELECT wfproc_idwfproc,tktcategory_idtktcategory FROM link_tskcategory_wfproc 
		WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND wfproc_idwfproc=".$ptodo." AND tktcategory_idtktcategory=".$_SESSION['thisticketcat']." LIMIT 1";
		$res_exists=mysql_query($sql_exists);
		$num_exists=mysql_num_rows($res_exists);
		//echo $sql_exists;
		
		$sql_exists_alt="SELECT wfproc_idwfproc,tktcategory_idtktcategory FROM link_tskcategory_wfproc 
		WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND tktcategory_idtktcategory=".$_SESSION['thisticketcat']." LIMIT 1";
		$res_exists_alt=mysql_query($sql_exists_alt);
		$num_exists_alt=mysql_num_rows($res_exists_alt);
		

		//before adding, ensure it is not already there
		if ($num_exists<1) //if it is zero, then add it
			{
			$sql_add="INSERT INTO link_tskcategory_wfproc (wfproc_idwfproc,usrteam_idusrteam,usrteamzone_idusrteamzone,tktcategory_idtktcategory,createdby,createdon) 
			VALUES ('".$wftoassign."','".$_SESSION['MVGitHub_idacteam']."','".$_SESSION['MVGitHub_userteamzoneid']."','".$_SESSION['thisticketcat']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			$results_add=mysql_query($sql_add);
			
		//	echo $sql_add;
			if ($results_add==true)
				{
				$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
				}
			
			} else if ($num_exists>0) { //else if it exists, then delete
			
			$sql_del="DELETE FROM link_tskcategory_wfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND tktcategory_idtktcategory=".$_SESSION['thisticketcat']."  ";
			mysql_query($sql_del);
		//	echo $sql_del;
			$sql_add="INSERT INTO link_tskcategory_wfproc (wfproc_idwfproc,usrteam_idusrteam,usrteamzone_idusrteamzone,tktcategory_idtktcategory,createdby,createdon) 
			VALUES ('".$wftoassign."','".$_SESSION['MVGitHub_idacteam']."','".$_SESSION['MVGitHub_userteamzoneid']."','".$_SESSION['thisticketcat']."','".$_SESSION['MVGitHub_idacname']."','".$timenowis."')";
			mysql_query($sql_add);
			
			$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
			
			}
		
		}//close todo
	
	if ($ptodo==0) //before removing, first check to ensure you have the record already
		{
		
		//if a record already exists
		$sql_exists="SELECT wfproc_idwfproc,tktcategory_idtktcategory FROM link_tskcategory_wfproc 
		WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND tktcategory_idtktcategory=".$_SESSION['thisticketcat']." LIMIT 1";
		$res_exists=mysql_query($sql_exists);
		$num_exists=mysql_num_rows($res_exists);
	//	echo $sql_exists;
		
		//before adding, ensure it is not already there
		if ($num_exists>0) //if it prsent, then
			{
			//if yes, ensure that the Workflow is NOT Active
			$sql_active="SELECT wfstatus FROM wfproc WHERE idwfproc=".$wftoassign." LIMIT 1";
			$res_active=mysql_query($sql_active);
			$num_active=mysql_num_rows($res_active);
			$fet_active=mysql_fetch_array($res_active);
			
			if ($fet_active['wfstatus']==1)
				{
				$msg="<div class=\"msg_warning\">".$msg_warning_procdel."</div>";
				} else 	{
			$sql_deleterec="DELETE FROM link_tskcategory_wfproc WHERE wfproc_idwfproc=".$wftoassign." AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND  tktcategory_idtktcategory=".$_SESSION['thisticketcat']." LIMIT 1";
			mysql_query($sql_deleterec);
		//	echo $sql_deleterec;
			$msg="<div class=\"msg_success\">".$msg_changes_saved."</div>";
				}
			}//if num exists
		
		}//close todo
	
	
	} //form action	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle;?></title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body <?php //if ((isset($_GET['reload'])) && ($_GET['reload']=="parent")){ echo "onload=\"parent.location.reload(1);\""; } ?>>
<div>
<div class="tbl_sh" style="position:fixed; margin:0px; padding:0px; top:0px">
<table border="0" width="800" cellpadding="0" cellspacing="0">
						<tr>
                            <td>
                            <?php echo $lbl_select_workflow; ?>
                            </td>
                            <td align="right" style="position:absolute; right:5px">
                            <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                            </td>
                        </tr>
                    </table>
</div>
<div style="padding:20px 0px 0px 0px">
<table border="0" cellpadding="2" cellspacing="0" width="100%">

            <tr>
            	<td colspan="5">
                <?php
				if (isset($msg)) { echo $msg; } 
				?>
                </td>
            </tr>
            <tr>
            	<td class="tbl_h">&nbsp;</td>
              <td class="tbl_h"><?php echo $lbl_wf;?></td>
                <td class="tbl_h"><?php echo $lbl_description;?></td>
                <td class="tbl_h"><?php echo $lbl_tat;?></td>
                <td class="tbl_h"><?php echo $lbl_lastedit;?></td>

            </tr>
            <?php
			$sql_wf="SELECT wfproc.idwfproc,wfproc.usrteamzone_idusrteamzone,wfproc.wfprocname,wfproc.wfproctat,wfproc.wfprocdesc,wfproc.mobileaccess,wfproc.modifiedon,wfproc.createdon FROM wfproc 
			WHERE wfproc.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." GROUP BY wfproc.idwfproc ORDER BY wfprocname ASC";
			$res_wf=mysql_query($sql_wf);
			$num_wf=mysql_num_rows($res_wf);
			$fet_wf=mysql_fetch_array($res_wf);
			//echo $sql_wf;
			if ($num_wf > 0) 
				{
			do {
			?>
            <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
				<td width="30" class="tbl_data">
                <?php
				$sql_exists="SELECT tktcategory_idtktcategory FROM link_tskcategory_wfproc WHERE usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND wfproc_idwfproc=".$fet_wf['idwfproc']." AND tktcategory_idtktcategory=".$_SESSION['thisticketcat']." LIMIT 1";
				$res_exists=mysql_query($sql_exists);
				$fet_exists=mysql_fetch_array($res_exists);
				//echo $sql_exists;
				//echo $_SESSION['thisticketcat']."==".$fet_exists['tktcategory_idtktcategory']
				?>
                <a name="<?php echo $fet_wf['idwfproc'];?>"></a>
               <a id="button_check<?php if ($_SESSION['thisticketcat']==$fet_exists['tktcategory_idtktcategory']){ echo "_on"; }  ?>" href="<?php echo $_SERVER['PHP_SELF'];?>?assignwf=<?php echo $fet_wf['idwfproc'];?>&amp;todo=<?php if ($_SESSION['thisticketcat']==$fet_exists['tktcategory_idtktcategory']){ echo "0"; } else { echo "1"; } ?>" ></a>
               </td>
            	<td class="tbl_data">
				<?php 
				echo $fet_wf['wfprocname'];?>
                </td>
                <td class="tbl_data">
                <?php echo $fet_wf['wfprocdesc'];?>
                </td>
                <td class="tbl_data">
                <?php 
				if ($fet_wf['wfproctat']>"172800")
					{ //if greater than 48 hours, then make it days, else keep it hours
					echo ($fet_wf['wfproctat']/(60 * 60 * 24))."&nbsp;".$lbl_days;
					} else {
					echo ($fet_wf['wfproctat']/(60 * 60))."&nbsp;".$lbl_hours;
					}
				?>
                </td>
              <td class="tbl_data">
                <?php
					 if ($fet_wf['modifiedon']!='0000-00-00 00:00:00') // if there was a modified, then
					 	{
						echo date("j M Y H:i",strtotime($fet_wf['modifiedon'])); 
						} else {
						echo date("j M Y H:i",strtotime($fet_wf['createdon'])); 
						}
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
			} while ($fet_wf=mysql_fetch_array($res_wf));
			
			} else {
			?>
            <tr>
            	<td colspan="5" align="center" height="50">
                <span class="msg_warning" >
                <?php echo $msg_warning_nowf; ?>
                </span>
                </td>
            </tr>
            <?php
			}
			?>
	</table>
</div>
</div>
</body>
</html>
