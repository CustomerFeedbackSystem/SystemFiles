<?php
session_start();
require_once('../Connections/connSystem.php');
mysql_select_db($database_connSystem, $connSystem);

function parseToXML($htmlStr) 
{ 
$xmlStr=str_replace('<','&lt;',$htmlStr); 
$xmlStr=str_replace('>','&gt;',$xmlStr); 
$xmlStr=str_replace('"','&quot;',$xmlStr); 
$xmlStr=str_replace("'",'&#39;',$xmlStr); 
$xmlStr=str_replace("&",'&amp;',$xmlStr); 
return $xmlStr; 
} 

$url_pref="index.php?filter_loc=&filter_cat=&filter_stat=&filter_chn=&tktno=";
$url_suff="&parentviewtab=2&mod=4&submod=6&uction=view_submod";

// Select all the rows in the markers table
//$query = "SELECT * FROM markers WHERE 1";
$query = "SELECT tktcategoryname,locationname,loctowns.lng, loctowns.lat,tktcategory.idtktcategory,tktcategory.gmap_bubble,tktin.timereported,tktin.refnumber,tktin.road_street,tktin.building_estate,tktin.unitno,tktstatusname FROM tktin
INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns
INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus 	
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."";
$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . parseToXML($row['refnumber']." - ".$row['tktcategoryname']) . '" ';
  echo 'address="' . parseToXML($row['locationname']." ".$row['road_street']." ".$row['building_estate']) . '" ';
  echo 'linkurl="' . parseToXML($url_pref."".$row['refnumber']."".$url_suff) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  echo 'type="' . $row['gmap_bubble'] . '" ';
  echo '/>';
}

// End XML file
echo '</markers>';

?>
