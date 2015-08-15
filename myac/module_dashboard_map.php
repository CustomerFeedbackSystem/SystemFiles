<?php
require_once('../assets_backend/be_includes/config.php');
require_once('../assets_backend/be_includes/check_login_easy.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<link href="../assets_backend/css/style.css" rel="stylesheet" type="text/css" />

<title>Google Maps</title>
<?php
if (!isset($_REQUEST['map_action']))
	{
	echo "<center><div style=\"text-align:center; font-family:arial; font-size:12px; font-weight:bold; margin:60px 0px; padding:10px 0px 0px 200px; \"><a id=\"button_loadmap\" href=\"".$_SERVER['PHP_SELF']."?map_action=show_map\"></a></div></center>";
	exit;
	}


//echo $connected;
//
if ((isset($_SESSION['sec_mod'])) && (isset($_SESSION['MVGitHub_iduserprofile'])) && (isset($_SESSION['sec_submod'])) ) //check if sub module is set
	{
	$sql_permglobal="SELECT global_access FROM systemprofileaccess WHERE syssubmodule_idsyssubmodule=".$_SESSION['sec_submod']." AND sysprofiles_idsysprofiles=".$_SESSION['MVGitHub_iduserprofile']." LIMIT 1";
	$res_permglobal=mysql_query($sql_permglobal);
	$num_permglobal=mysql_num_rows($res_permglobal);
	$fet_permglobal=mysql_fetch_array($res_permglobal);
	//echo "<span style=\"color:#ffffff\">".$sql_permglobal."</span>";
	if ($num_permglobal > 0)
		{
		$is_perm_global=$fet_permglobal['global_access'];
		} else {
			$is_perm_global=0;
		}
	} else {
	$is_perm_global=0;
	}		

//default google maps coordinates
$sql_coord="SELECT loctowns.lng,loctowns.lat FROM usrteamzone INNER JOIN loctowns ON usrteamzone.loctowns_idloctowns=loctowns.idloctowns WHERE idusrteamzone=".$_SESSION['MVGitHub_userteamzoneid']." LIMIT 1";
$res_coord=mysql_query($sql_coord);
$fet_coord=mysql_fetch_array($res_coord);

if ($is_perm_global==1)
	{
	$url_map="dashboard_map_1_global.php";
	} else {
	$url_map="dashboard_map_1.php";
	}


//load maps script only when needed to improve the speed
//check if there is net in order to bother with this script
if  (($_SESSION['sec_mod']==1) && (isset($connected)) && ($connected=="OK") )
		{
?>	
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;key=<?php echo $google_maps_key;?>"  type="text/javascript"></script>
<script type="text/javascript">
    //<![CDATA[

    var customIcons = {
      bubble_1: {
        icon: '../assets_backend/gmaps_icons/bubble_1.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
      bubble_2: {
        icon: '../assets_backend/gmaps_icons/bubble_2.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_3: {
        icon: '../assets_backend/gmaps_icons/bubble_3.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_4: {
        icon: '../assets_backend/gmaps_icons/bubble_4.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_5: {
        icon: '../assets_backend/gmaps_icons/bubble_5.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_6: {
        icon: '../assets_backend/gmaps_icons/bubble_6.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_7: {
        icon: '../assets_backend/gmaps_icons/bubble_7.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_8: {
        icon: '../assets_backend/gmaps_icons/bubble_8.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_9: {
        icon: '../assets_backend/gmaps_icons/bubble_9.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_10: {
        icon: '../assets_backend/gmaps_icons/bubble_10.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      }
	  
    };

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(<?php echo $fet_coord['lat'];?>, <?php echo $fet_coord['lng'];?>),
        zoom: 11,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl("<?php echo $url_map;?>", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var type = markers[i].getAttribute("type");
		  var linkurl = markers[i].getAttribute("linkurl");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<span class=text_body><b>" + name + "</b> <br/>" + address + " <br/><a target=\"_parent\" href="+ linkurl +">Details</a></span>";
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>
  </script>
<?php  
}
//close map check requirement 
?> 


<?php
//load maps script only when needed to improve the speed
if ( ($_SESSION['sec_mod']==20) && ($_SESSION['sec_submod']==55)  && (isset($connected)) &&  ($connected=="OK") )
	{
?>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&amp;key=<?php echo $google_maps_key;?>"  type="text/javascript"></script>
<script type="text/javascript">
    //<![CDATA[

    var customIcons = {
      bubble_44_0: {
        icon: '../assets_backend/gmaps_icons/bubble_44_0.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
      bubble_59_45: {
        icon: '../assets_backend/gmaps_icons/bubble_59_45.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_74_60: {
        icon: '../assets_backend/gmaps_icons/bubble_74_60.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      },
	  bubble_100_75: {
        icon: '../assets_backend/gmaps_icons/bubble_100_75.png',
        shadow: '../assets_backend/gmaps_icons/shadow.png'
      }
	  
    };

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(<?php echo $fet_coord['lat'];?>, <?php echo $fet_coord['lng'];?>),
        zoom: 7,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl("dashboard_map_all_tickets.php", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var type = markers[i].getAttribute("type");
		  var linkurl = markers[i].getAttribute("linkurl");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<span class=text_body><b>" + name + "</b> <br/>" + address + " <br/><a target=\"_parent\" href="+ linkurl +">Details</a></span>";
          var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>
  </script>
<?php 
} //close map check requirement 
?> 

</head>
<body <?php if ( (isset($connected)) && ($connected=="OK") ) { echo "onLoad=\"load()\""; } ?>>
<?php
if ( (isset($connected)) && ($connected=="OK"))
	{
?>	
<div id="map" style="width:550px;height:350px"></div>  
<?php 
} else { 
echo "<div class=\"msg_warning\">Unable to load Google Maps!<br>Your Internet Connection too slow or unavailable...</div>";
}
?>
</body>
</html>
