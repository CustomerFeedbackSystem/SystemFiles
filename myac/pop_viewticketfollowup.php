<?php
require_once('../assets_backend/be_includes/config.php');

require_once('../assets_backend/be_includes/check_login_easy.php');

//get the title
if (isset($_GET['title']))
	{
	$_SESSION['wtitle']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['title'])));
	}

if (isset($_GET['tkt']))
	{
	$_SESSION['tktid']=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['tkt'])));
	}	

//get the general details of this ticket and ensure it belongs to this viewer, and that it is overdue
$qry_ov="SELECT orgesc.idusrteam_to,tktin.idtktinPK, tktin.timereported,tktin.timedeadline, tktin.refnumber,tktcategory.tktcategoryname,tktin.tktdesc,tktin.sendername,tktin.senderphone,loctowns.locationname,tktstatus.tktstatusname,tktstatus.status_color,TIMESTAMPDIFF(MINUTE, NOW(), tktin.timedeadline) AS time_to_deadline,(SELECT DISTINCT tktactivityowner.idtktinPK FROM tktactivityowner WHERE tktactivityowner.idtktinPK=tktin.idtktinPK) as tktowned FROM tktin 
INNER JOIN orgesc ON tktin.usrteam_idusrteam = orgesc.idusrteam_from 
INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus  
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory  
INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns WHERE  orgesc.idusrteam_to=".$_SESSION['MVGitHub_idacteam']." AND tktin.tktstatus_idtktstatus<4  AND tktin.idtktinPK=".$_SESSION['tktid']."  AND  tktin.timedeadline < '".$timenowis."'  ";
$res_ov=mysql_query($qry_ov);
$num_ov=mysql_num_rows($res_ov);
$fet_ov=mysql_fetch_array($res_ov);

//set variables to determine the state of the buttons here
if ($fet_ov['tktowned']>0)
	{
	$tktistaken=1;
	} else {
	$tktistaken=0;
	}

if ($num_ov < 1)
	{
	echo "<span class=\"msg_warning\">".$msg_warn_violation."</span>";
	exit;
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Task Details</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="../scripts/datetimepicker.js"></script>
</head>
<body>
<table border="0" width="100%" cellpadding="2" cellspacing="0" >
	<tr>
    	<td class="tbl_sh">
        	<table border="0" cellpadding="0" cellspacing="0" width="100%">
            	<tr>
               	  <td width="700">
                  <?php
				  echo $_SESSION['wtitle'];
				  ?>
                  </td>
                   
                    <td>
                   <a href="#" onClick="parent.tb_remove();" id="button_closewin"></a>
                    </td>
				</tr>
			</table>
            </td>
    </tr>
    <tr>
    	<td>
        <table border="0" cellpadding="0" cellspacing="0">
  <tr>
					<td style="padding:5px 5px 0px 5px">
					<a href="<?php echo $_SERVER['PHP_SELF'];?>?btnview=1" id="button_tkthis"></a>
					</td>
					<td style="padding:5px 5px 0px 5px">
                    <?php if ($tktistaken==1){ ?>
                    <a href="#" id="button_astome_disabled"></a>
                    <?php } else { ?>
					<a href="<?php echo $_SERVER['PHP_SELF'];?>?btnview=2" id="button_astome"></a>
                    <?php } ?>
					</td> 
					<td style="padding:5px 5px 0px 5px">
                    <?php if ($tktistaken==1){ ?>
					<a href="<?php echo $_SERVER['PHP_SELF'];?>?btnview=3" id="button_autosms"></a>
                    <?php } else { ?>
                    <a href="#" id="button_autosms_disabled"></a>
                    <?php } ?>
					</td>
					<td style="padding:5px 5px 0px 5px">
                    <?php if ($tktistaken==1){ ?>
					<a href="<?php echo $_SERVER['PHP_SELF'];?>?btnview=4" id="button_actreport"></a>
                    <?php } else { ?>
                    <a href="#" id="button_actreport_disabled"></a>
                    <?php } ?>
					</td>
                  <!--  <td style="padding:5px 5px 0px 5px">
                    <?php //if ($tktistaken==1){ ?>
					<a href="<?php // echo $_SERVER['PHP_SELF'];?>?btnview=5" id="button_esctkt"></a>
                    <?php //} else { ?>
                    <a href="#" id="button_esctkt_disabled"></a>
                    <?php //} ?>
					</td>
                  -->
                </tr>
                <tr>
                	<td align="center" valign="top">
                    <?php
					if ($_SESSION['viewbtn']==1)
						{
						echo "<img src=\"../assets_backend/images/arrow_here.gif\" border=\"0\" />";
						}
					?>                    </td>
                  <td align="center" valign="top"><?php
					if ($_SESSION['viewbtn']==2)
						{
						echo "<img src=\"../assets_backend/images/arrow_here.gif\" border=\"0\" />";
						}
					?>                    </td>
                  <td align="center" valign="top"><?php
					if ($_SESSION['viewbtn']==3)
						{
						echo "<img src=\"../assets_backend/images/arrow_here.gif\" border=\"0\" />";
						}
					?>                    </td>
                  <td align="center" valign="top"><?php
					if ($_SESSION['viewbtn']==4)
						{
						echo "<img src=\"../assets_backend/images/arrow_here.gif\" border=\"0\" />";
						}
					?>                    </td>
                 <!-- <td align="center" valign="top"><?php
					/*if ($_SESSION['viewbtn']==5)
						{
						echo "<img src=\"../assets_backend/images/arrow_here.gif\" border=\"0\" />";
						}*/
					?>                    </td>-->
          </tr>
			</table>
      </td>
    </tr>
    <tr>
    	<td>
        <?php
		if ($_SESSION['viewbtn']==1)
			{
			require_once('pop_viewticketfollowup_tab_1.php');
			}
		if ($_SESSION['viewbtn']==2)
			{
			require_once('pop_viewticketfollowup_tab_2.php');
			}
		if ($_SESSION['viewbtn']==3)
			{
			require_once('pop_viewticketfollowup_tab_3.php');
			}
		if ($_SESSION['viewbtn']==4)
			{
			require_once('pop_viewticketfollowup_tab_4.php');
			}
		?>
        </td>
    </tr>
    </table>
</body>
</html>
