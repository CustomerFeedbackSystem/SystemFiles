<?php
$address=urlencode($_GET['address']).",Kenya";

define("MAPS_HOST", "maps.google.com");
define("KEY", "AIzaSyAX_mnz_-hc79K2g16A6frr07K7SafBPLU");

// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

// Iterate through the rows, geocoding each address
   $geocode_pending = true;

    $address = $address;
    $request_url = $base_url . "&q=" . urlencode($address);
    $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = split(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];
		
	echo "Longitude : ".$lat."<br>";
	echo "Latitude : ".$lng;
    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } else {
      // failure to geocode
      $geocode_pending = false;
      echo "Address " . $address . " failed to geocode. ";
      echo "Received status " . $status . "\n";
    }
    usleep($delay);

?>