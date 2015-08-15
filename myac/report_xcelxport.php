<?php
//echo trim($_POST['report_print']);
$filename=$_POST['report_name'];
header("Content-type: application/octet-stream");
 
# replace excelfile.xls with whatever you want the filename to default to
header("Content-Disposition: attachment; filename=Export_Report.xls");
header("Pragma: no-cache");
header("Expires: 0");

?>