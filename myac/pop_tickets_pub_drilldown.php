<?php
require_once('../assets_backend/be_includes/config.php');
//require_once('../Connections/connSystem.php');
//mysql_select_db($database_connSystem, $connSystem);

require_once('../assets_backend/be_includes/check_login_easy.php');

//only display the public tickets for this company
$sql_ticket="SELECT tktin_public.sendername,tktin_public.senderphone,tktin_public.tktdesc,tktin.refnumber,tktin_public.timereported,tktin_public.city_town FROM tktin_public 
INNER JOIN tktin ON tktin_public.tktin_idtktin=tktin.idtktinPK 
WHERE tktin_public.tktin_idtktin=".$_SESSION['tktupdate']." AND tktin.usrteam_idusrteam=".$_SESSION['MVGitHub_idacteam']." ORDER BY tktin_public.timereported DESC";
$res_ticket=mysql_query($sql_ticket);
$num_ticket=mysql_num_rows($res_ticket);
$fet_ticket=mysql_fetch_array($res_ticket);
//echo $sql_ticket;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle;?></title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div>
	<div class="table_header">Similar Public Tickets | <?php echo $fet_ticket['refnumber'];?></div>
    <?php
	if ($num_ticket > 0 )
		{
	?>
    <div>
    <table border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td class="tbl_h2">
           <?php echo $lbl_timereported;?>
            </td>
        	<td class="tbl_h2">
            <?php echo $lbl_fname;?>
            </td>
            <td class="tbl_h2">
            <?php echo $lbl_mobile;?>
            </td>
            <td class="tbl_h2">
            <?php echo $lbl_location;?>
            </td>
              <td class="tbl_h2">
           <?php echo $lbl_ticketnmsg;?>
            </td>
        </tr>
        <?php
		do {
		?>
        <tr>
        	<td class="tbl_data"><?php echo date("D, M d, Y H:i",strtotime($fet_ticket['timereported'])); ?></td>
        	<td class="tbl_data"><?php echo $fet_ticket['sendername'];?></td>
            <td class="tbl_data"><?php echo $fet_ticket['senderphone'];?></td>
            <td class="tbl_data"><?php echo $fet_ticket['city_town'];?></td>
            <td class="tbl_data"><?php echo $fet_ticket['tktdesc'];?></td>
        </tr>
        <?php } while ($fet_ticket=mysql_fetch_array($res_ticket)); ?>
    </table>
    </div>
    <?php 
	} else {  
	echo "<div class=\"msg_warning\">".$msg_no_record."</div>";
	}
	?>
	
</div>
</body>
</html>
