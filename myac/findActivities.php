<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');
$ryearId=intval($_GET['ryear']);
$rqtrId=intval($_GET['rqtr']);

$query="SELECT DISTINCT idtktactivitytype,activitytype FROM wpdetails 
INNER JOIN tktactivitytype ON wpdetails.tktactivitytype_idtktactivitytype=tktactivitytype.idtktactivitytype
INNER JOIN wpheader ON wpdetails.wpheader_idwpheader=wpheader.idwpheader
WHERE wpheader.yearfrom=".$ryearId." AND wpdetails.wpquarters_idwpquarters=".$rqtrId."";
$result=mysql_query($query);
//echo $query;
?>
<select name="ract" onchange="getdisagg(<?php echo $rqtrId;?>,this.value)"  >
<option value="-1">-- Select Activity --</option>
<?php while($row=mysql_fetch_array($result)) { ?>
<option value="<?php echo $row['idtktactivitytype']; ?>"><?php echo $row['activitytype'];?></option>
<?php } ?>
</select>
