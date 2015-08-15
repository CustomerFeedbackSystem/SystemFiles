<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle;?></title>
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="tbl_sh">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
    	<td width="100%" >
		<div>
        <?php
		if ( (isset($display_content)) && ($display_content=="OK" ))
			{
			echo $fet_memberdetails['utitle']." ".$fet_memberdetails['fname']." ".$fet_memberdetails['lname'];
			}
		?>
		</div>
		</td>
		<td align="right">
		<a href="#" onClick="parent.tb_remove();parent.location.reload(1);" id="button_closewin"></a>
		</td>
	</tr>
</table>
</div>

<div class="msg_success">
<?php echo $_SESSION['tasks_no'];?> Tasks Successfully Transferred as per the following table...
</div>
<div style="padding:15px">
	<table border="0" cellpadding="2" cellspacing="0" width="80%">
<tr>
        	<td class="tbl_h">Tasks</td>
  <td class="tbl_h">
            From            </td>
  <td class="tbl_h">
            To            </td>
      </tr>
        <?php
		$sql="SELECT count(*) as tasks, fname,lname,usrroleid_to,usrroleid_from,usracid_from,usrrolename,
		(SELECT usrrolename FROM usrrole WHERE idusrrole=usrroleid_to) as rolename,
		(SELECT fname FROM usrac WHERE idusrac=usracid_from) as fname_from
		FROM wftasks_transfers 
		INNER JOIN usrac ON wftasks_transfers.usracid_to=usrac.idusrac 
		INNER JOIN usrrole ON wftasks_transfers.usrroleid_from=usrrole.idusrrole
		WHERE 
		transfer_batch=".$_SESSION['batch']." GROUP BY usrroleid_to";
		$res=mysql_query($sql);
		$fet=mysql_fetch_array($res);
		
		do {
		?>
        <tr <?php 
// technocurve arc 3 php mv block2/3 start
echo " style=\"background-color:$mocolor\" onMouseOver=\"this.style.backgroundColor='$mocolor3'\" onMouseOut=\"this.style.backgroundColor='$mocolor'\"";
// technocurve arc 3 php mv block2/3 end
?>>
        	<td height="30" class="tbl_data">
            <?php echo $fet['tasks'];?>
            </td>
          <td height="30" class="tbl_data">
         	<?php echo $fet['fname_from']; ?> ( <?php echo $fet['usrrolename'];?> )
        	</td>
          <td height="30" class="tbl_data">
           <?php echo $fet['fname']." ".$fet['lname'];?> ( <?php echo $fet['rolename'];?> )
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
	  	} while ($fet=mysql_fetch_array($res));
	  ?>
    </table>
</div>
</body>
</html>
