<?php
require_once('../assets_backend/be_includes/config.php');

//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

$sno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['sentby'])));
$sacno=preg_replace('/[^a-z\-_0-9\.:\/]/i','',mysql_escape_string(trim($_GET['ac'])));
//create a filter for ACCOUNT NUMBER
if (strlen($sacno) > 0)
	{
	$filter_ac=" OR waterac=".$sacno." ";
	} else {
	$filter_ac=" ";
	}

$sql_exists="SELECT sendername,senderemail,senderphone,refnumber,timereported,tktstatusname,locationname,tktdesc,tktchannelname,idtktinPK,waterac,tkttype_idtkttype FROM tktin 
INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus 	
INNER JOIN loctowns ON tktin.loctowns_idloctowns =loctowns.idloctowns 
INNER JOIN tktchannel ON tktin.tktchannel_idtktchannel=tktchannel.idtktchannel 
WHERE (senderphone='".$sno."' ".$filter_ac." ) AND usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." AND timereported>='".$fifteen_months_ago."' ORDER BY timereported DESC";
$res_exists=mysql_query($sql_exists);
$num_exists=mysql_num_rows($res_exists);
$fet_exists=mysql_fetch_array($res_exists);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Possible Duplicate Record</title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<link href="../thickbox/original_thickbox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../thickbox/thickbox_be.js"></script>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
</head>
<body>
<div>

	<div class="tbl_sh">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
        	<td width="100%">
            <?php echo $lbl_listduptkt;?>

            </td>
        	<td align="right">
            <a href="#" onClick="parent.tb_remove();parent.tb_remove(2)" id="button_closewin"></a>
            </td>
      </tr>
    </table>
    </div>
<div>
<div class="msg_instructions">Please see possible similar tickets below. </div>
</div>
  <div >
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
        	<td height="30" class="tbl_h2">
            Ticket Number            </td>
   	  <td height="30" class="tbl_h2">
            <?php echo $lbl_from;?>            </td>

      <td height="30" class="tbl_h2">
            <?php echo $lbl_acno;?>        </td>
      <td height="30" class="tbl_h2">
            <?php echo $lbl_date;?>            </td>
      <td height="30" class="tbl_h2">
            <?php echo $lbl_town_city;?>            </td>
      <td height="30" class="tbl_h2">
            <?php echo $lbl_action_msg;?>            </td>
      <td height="30" class="tbl_h2"><?php echo $lbl_ticketchn;?></td>
      <td height="30" class="tbl_h2">&nbsp;</td>
      </tr>
        <?php
		if ($num_exists>0 )
			{
			do {
		?>
    	<tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
         <td height="30" valign="middle" class="tbl_data">
        <div>
        <a href="#" onclick="tb_open_new('go_to_taskhistory.php?duplicate=1&amp;tkt=<?php echo $fet_exists['idtktinPK'];?>&amp;title=<?php echo $fet_exists['refnumber'];?>&amp;tabview=1&amp;keepThis=true&amp;TB_iframe=true&amp;height=500&amp;width=900&amp;modal=true')">
		  <?php echo $fet_exists['refnumber'];?>         </a>         </div>          </td>
       	  <td height="30" valign="middle" bgcolor="#F9F9F9" class="tbl_data"><?php echo $fet_exists['sendername'];?><br />
          <strong>
		  <?php if ($sno==$fet_exists['senderphone']) { echo "<span style=\"background-color:#FFFF99\">".$fet_exists['senderphone']."</span>"; } else { echo $fet_exists['senderphone']; }?>
          </strong>          </td>
          <td height="30" valign="middle" class="tbl_data">
          <?php if ($sacno==$fet_exists['waterac']) { echo "<span style=\"background-color:#FFFF99\">".$fet_exists['waterac']."</span>"; } else { echo $fet_exists['waterac']; }?>          </td>
          <td height="30" valign="middle" bgcolor="#F9F9F9" class="tbl_data">
		  <?php echo date("d/m/Y H:i",strtotime($fet_exists['timereported']));?>
          <br />
          <strong><small>
          <?php
		
			//if time exceeded the deadline, then just use
			$seconds=abs(strtotime($fet_exists['timereported']) - strtotime($timenowis));
			$minutes=abs($seconds/60);
			$d = floor ($minutes / 1440);
			$h = floor (($minutes - $d * 1440) / 60);
			$m = number_format($minutes - ($d * 1440) - ($h * 60),0);

			echo "<span >{$d}d {$h}hr {$m}m ago</span>";
		  ?>
          </small>          </strong>          </td>
          <td height="30" valign="middle" class="tbl_data"><?php echo $fet_exists['locationname'];?></td>
          <td height="30" valign="middle" bgcolor="#F9F9F9" class="tbl_data">
          <?php echo $fet_exists['tktdesc'];?>          </td>
          <td height="30" valign="middle" class="tbl_data"><?php echo $fet_exists['tktchannelname'];?></td>
          <td height="30" valign="middle" bgcolor="#F9F9F9" class="tbl_data">
          <?php //if ticket is public 
		 // if ( $fet_exists['tkttype_idtkttype']==2) { ?>
          <a href="#" onclick="alert('Feature not active in your profile. Please Contact Admin!')" title="Link the new complaint with this one instead of creating the same ticket twice (duplicate)" id="button_linkticket"></a>
          <?php //} ?>
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
?>
       <?php
	   		} while ($fet_exists=mysql_fetch_array($res_exists));
		}
	   ?>
    </table>
  </div>


</div>
</body>
</html>
