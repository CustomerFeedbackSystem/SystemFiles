<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');
$ryear=intval($_GET['ryear']);

$query="SELECT DISTINCT idwpquarters,wpquarter FROM wpquarters 
INNER JOIN wpdetails ON wpquarters.idwpquarters=wpdetails.wpquarters_idwpquarters 
INNER JOIN wpheader ON wpdetails.wpheader_idwpheader=wpheader.idwpheader WHERE 
wpheader.yearfrom=".$ryear." ";
$result=mysql_query($query);
//echo $query;
?>
<select name="rqtr" onchange="getactivities(<?php echo $ryear;?>,this.value)">
<option value="-1"> -- Select Quarter -- </option>
<?php while($row=mysql_fetch_array($result)) { ?>
<option value=<?php echo $row['idwpquarters']; ?>><?php echo $row['wpquarter']; ?></option>
<?php } ?>
</select>