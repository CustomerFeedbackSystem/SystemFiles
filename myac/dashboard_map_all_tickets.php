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

$url_pref="report_mapview.php";
$url_suff="";

// Select all the rows in the markers table
//$query = "SELECT * FROM markers WHERE 1";
/*$query = "SELECT tktcategoryname,locationname,loctowns.lng, loctowns.lat,tktcategory.idtktcategory,tktcategory.gmap_bubble,tktin.timereported,tktin.refnumber,tktin.road_street,tktin.building_estate,tktin.unitno,tktstatusname FROM tktin
INNER JOIN loctowns ON tktin.loctowns_idloctowns=loctowns.idloctowns
INNER JOIN tktstatus ON tktin.tktstatus_idtktstatus=tktstatus.idtktstatus 	
INNER JOIN tktcategory ON tktin.tktcategory_idtktcategory=tktcategory.idtktcategory WHERE tktin.usrteamzone_idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']."";
*/
$query = "SELECT idusrteam,idusrteamzone,tktin.usrteam_idusrteam,count(*) as total, usrteamname,usrteamshortname,userteamzonename,lat,lng
FROM tktin 
INNER JOIN usrteam ON tktin.usrteam_idusrteam=usrteam.idusrteam
INNER JOIN usrteamzone ON tktin.usrteamzone_idusrteamzone=usrteamzone.idusrteamzone
WHERE idusrteamzone>1
GROUP BY tktin.usrteamzone_idusrteamzone";
$res=mysql_query($query);
$row=mysql_fetch_array($res);
//echo $query;
//exit;
/*$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}*/

header("Content-type: text/xml");

// Start XML file, echo parent node
echo '<markers>';

// Iterate through the rows, printing XML nodes for each
//while ($row = @mysql_fetch_assoc($result)){
	do {
	
	$sql_pc="SELECT count(*) as OD FROM tktin WHERE usrteamzone_idusrteamzone=".$row['idusrteamzone']." AND 
	tktstatus_idtktstatus !=4 AND  	usrteamzone_idusrteamzone>1
	AND timeclosed = '0000-00-00 00:00:00'";
	$res_pc=mysql_query($sql_pc);
	$fet_pc=mysql_fetch_array($res_pc);
//	echo $fet_pc['OD']."<br>";
//	echo $sql_pc;
//	exit;
	
	$pc=(($fet_pc['OD']/$row['total'])*100);
	
	if ($pc < '45')
		{
		$bubble="bubble_100_75";
		}
	if (($pc > '44') && ($pc < '60'))
		{
		$bubble="bubble_74_60";
		}
	if (($pc > '59') && ($pc < '75'))
		{
		$bubble="bubble_59_45";
		}
	if ($pc > '74')
		{
		$bubble="bubble_44_0";
		}
//calculate the value

  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . parseToXML($row['usrteamshortname']." - ".$row['userteamzonename']) . '" ';
  echo 'address="' . parseToXML(number_format($pc,0)). ' %" ';
  echo 'linkurl="' . parseToXML($url_pref."".$row['idusrteam']."".$url_suff) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  echo 'type="' . $bubble . '" ';
  echo '/>';

} while ($row=mysql_fetch_array($res));

// End XML file
echo '</markers>';

?>
