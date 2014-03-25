<?php

// Global Participative Sculpture
// 
// json.php
//
// Version 0.5 - testing the static generation of a grid 
//
// The Layar augmented reality browser reads this file from the webserver after the layar is opened.
// This file generates the JSON code which specifies the properties of all the augmented reality items.



// These parameters are provided to this script in each url request by the layar browser
$lat = $_GET['lat'];
$lon = $_GET['lon'];
$accuracy = $_GET['accuracy'];

// temporarily, use the values below as an indication of the length of a meter on the lat/lon scale
$mlat = 0.000030;
$mlon = 0.000055;
  
// this calculation below maps any lat/lon value to a grid points with an interval of 4 "lat/lon meters" 
$factor = 1000000;
$flat = floor($lat*$factor/($factor*4*$mlat))*4*$mlat;
$flon = floor($lon*$factor/($factor*4*$mlon))*4*$mlon;

// start creating the array containing all the points of interest (POI)
$response = array();

// first some values defining the whole layar
$response["refreshInterval"] = 10;	// the fastest refresh timeout is 10 seconds
$response["fullRefresh"] = false;	// POIs will be updated if necessary, instead of redrawn
$response["refreshDistance"] = 5;	// when the user moves 5 meter, refresh the content (with an update)
$response["layer"] = "global";		// this is the layar name specified in the develop environment
	
	
$i=0;

// create a grid 
for ($x=-7;$x<8;$x++) {
	for ($y=-7;$y<8;$y++) {
	
		// calculate the lat/lon 
		$ilat = $x * 4 * $mlat + $flat; 
		$ilon = $y * 4 * $mlon + $flon; 
		
		// properties specifying a specific POI
		$poi['id'] = $i;
		
		$poi['actions'] = array(); // empty array. No actions.
		 
		$poi ['title'] = "G.P.S";
		
		$poi['line2'] = "Global Participative Sculpture";
		$poi['line3'] = $lat.",".$lon;
		
		$poi['attribution'] = "Sander Veenhof";
		
		$poi["showSmallBiw"] = false;
	     
	      $poi['object']['baseURL'] = "http://sndrv.nl/layar/gpsculpture/"; // path for graphical content

	      $poi['object']['full'] = "pin.png"; // 
	      $poi['object']['reduced'] =  $poi['object']['full'];
	    
	      $poi['transform']['imageURL'] = "http://sndrv.nl/layar/gpsculpture/trans.png";
	      // $poi['imageURL'] = "http://sndrv.nl/layar/oerol2013/trans.png";
	     
	      $poi['transform']['rel'] = true;	// turned towards viewer
	      $poi['transform']['angle'] = 0;
	      $poi['transform']['scale'] = 2; 	
	      $poi['object']['size'] = 5;	
	     
	      $poi["alt"] = null;		// Smartphone do not always give an appropriate altitude. 
	      $poi["relativeAlt"] = 0;		// Use relativeAlt instead
	      
	      $poi["lat"] = $ilat*1000000;	// no floats accepted, convert to int
	      $poi["lon"] = $ilon*1000000;
	       
	      $poi['dimension'] = 2;
	    
	      $poi["type"] = 0;			// n.a.
	      $poi["distance"] = 0;		// n.a.
	      $poi["doNotIndex"] = 1;
	      $poi["inFocus"] = 0;
	      
	      $poi['object']['icon'] = "http://sndrv.nl/layar/gpsculpture/trans.png";
		
     		
	      $response["hotspots"][$i] = $poi;
	      $i++; 
		    
		    
	}
}

      
$response["errorCode"] = 0;
$response["errorString"] = "OK";

echo json_encode( $response );

?>