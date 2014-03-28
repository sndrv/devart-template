<?php

include ("../databaseconnect.php");

$mlat = 0.000030;
$mlon = 0.000055;
  
$lat = $_GET['lat'];
$lon = $_GET['lon'];
$accuracy = $_GET['accuracy'];

$city = "";
$country = "";


if ($lat == 0 || $lon == 0) {
	
	$message = "Please wait 10 seconds";
	
       $response["refreshInterval"] = 10;

	$response["fullRefresh"] = true;
	$response["refreshDistance"] = 5;
	
	$response["errorCode"] = 0;
	$response["errorString"] = "Waiting for GPS signal";

	$jsonresponse = json_encode( $response );
	
	echo $jsonresponse;
	
	exit;
	
}


$factor = 1000000;

$flat = floor($lat*$factor/($factor*4*$mlat))*4*$mlat;
$flon = floor($lon*$factor/($factor*4*$mlon))*4*$mlon;

$ids = array();


$a = 0;

if ($accuracy > 300) {
	
	$distance = 300000;
	
} else {
	
	$distance = 200;		

	
	for ($x=-7;$x<8;$x++) {
		for ($y=-7;$y<8;$y++) {
			
		
			$ilat = $x * 4 * $mlat + $flat; // insert lat/lon
			$ilon = $y * 4 * $mlon + $flon; 
				
			$color = "";
					
			$q = " select * from grid where lat LIKE '".$ilat."' AND lon LIKE '".$ilon."' limit 1";
			
			$res = mysql_query($q);
			
			if ($row = mysql_fetch_assoc($res) ) {
			
				if ($country == "") {
					$country = $row['country'];
					$city = $row['city'];
					
				}
				
				
			} else {
				
				$q = "insert into grid (moment,lat,lon) VALUES (NOW()-100000, '".$ilat."','".$ilon."' ) ";
				
				mysql_query($q);
				
				$ids[] = mysql_insert_id();
				
				$a++;
				
				
			}
		}
	}

}



$q = "select * from grid";

if ($city != "") { 			// The location of the user has been traced
	
	 $q = $q." where city NOT LIKE '".$city."' ";
	 
} 

$q = $q."order by moment DESC limit 1";

$res = mysql_query($q);
	
if ($row = mysql_fetch_assoc($res) ) {

	$latest = $row['country']."/".$row['city'];
	
} else {
	
	$latest = "";
}

 
try {
	
	$response = array();
	
	
       $response["refreshInterval"] = 10;

	$response["fullRefresh"] = false;
	$response["refreshDistance"] = 5;
	
	$response["layer"] = $value["layerName"];
	
  
     $i=0;
     $d=0;
     
     $q = "SELECT *, 6371010 * 2 * asin(
	    sqrt(
		pow(sin((radians(" . addslashes($lat) . ") - radians(lat)) / 2), 2)
		+
		cos(radians(" . addslashes($lat) . ")) * cos(radians(lat)) * pow(sin((radians(" . addslashes($lon) . ") - radians(lon)) / 2), 2)
	    )
	) AS distance, TIME_TO_SEC( TIMEDIFF( NOW( ) , moment ) ) as sec
	FROM grid having distance < ".$distance;
	
	$q .= " ORDER BY distance ASC limit 150";

	$res = mysql_query($q);
	
	
	while ($i< 150 && $row = mysql_fetch_assoc($res) ) {	
	
		if ($row['country'] != "") {
			
			$country = $row['country'];
			$city  = $row['city'];
		}
		
		$id = $row['id'];
		
		$status = $row['status'];
		
		$sec = $row['sec'];
		
		$distance = $row['distance'];
			
		$treshold = 15;	
		
		if ($distance < $treshold) {
			
			$q2 = "update grid set moment = NOW() where id = ".$id;
			mysql_query($q2);
			
		}
		
	        $poi['id'] = "pin-".$id;
		$poi['actions'] = array();
		 
		$poi ['title'] = "G.P.S.";
		
		$poi['line2'] = "Global Participative Sculpture";
		
		$poi['line3'] = $lat.",".$lon;
		
		$poi['attribution'] = "Activity: ".$latest;
		
		$poi["showSmallBiw"] = false;
	     
	      $poi['object']['baseURL'] = "http://sndrv.nl/layar/gpsculpture/"; 
	      
	     $color = $row['color'];
	     
	     $model = "pin.png";
	   
	      $poi['object']['full'] = $model."?v=11"; 
	    
	      $poi['object']['reduced'] =  $poi['object']['full'];
	    	  
	      $poi['object']['icon'] = $poi['icon'];
	      
	      $rotation = 0;
	      
	      $poi['transform']['imageURL'] = "http://sndrv.nl/layar/gpsculpture/trans.png";
	      $poi['imageURL'] = "http://sndrv.nl/layar/gpsculpture/trans.png";
	      
	      $poi['transform']['rel'] = true;
	      
	      $poi['transform']['angle'] = $rotation;
	      
	      $poi['transform']['scale'] = 2;
	      
	      $size = 5;
	      
	      $poi['object']['size'] = $size;
	     
	      $alt = -4;
	      
	      $poi["alt"] = null;
	      $poi["relativeAlt"] = $alt;
	      
	      $poi["lat"] = $row['lat']*1000000;
	      $poi["lon"] = $row['lon']*1000000;
	      
	      $poi['dimension'] = 2;
	      
	      $poi["type"] = 0;
	      
	      $poi['distance'] = 10;
		
	      $actions = 0;
	     
	      $poi["distance"] = 0;
	      
	      	$poi["doNotIndex"] = 1;
		 $poi["inFocus"] = 0;
	      
		$poi['object']['icon'] = "http://sndrv.nl/layar/gpsculpture/trans.png";
		
	
		$update = 0;
         
		
		if ($sec > 0) {			// no animation on pins that haven't been touched yet
			
			$dur = $sec/10*1000;
			
			$from = 0;
			$to = 360;
			
		      $poi['animations']['onCreate'][$update]['length'] = $dur;
		      $poi['animations']['onCreate'][$update]['type'] = "rotate";
		      $poi['animations']['onCreate'][$update]['persist'] = true;
		      $poi['animations']['onCreate'][$update]['repeat'] = true;
		      $poi['animations']['onCreate'][$update]['from'] = $from;
		      $poi['animations']['onCreate'][$update]['to'] = $to;
		   
		      $poi['animations']['onCreate'][$update]['axis']['x'] = 0;
		      $poi['animations']['onCreate'][$update]['axis']['y'] = 0;
		      $poi['animations']['onCreate'][$update]['axis']['z'] = 1;
		    
		      
		      $poi['animations']['onUpdate'][$update]['length'] = $dur;
		      $poi['animations']['onUpdate'][$update]['type'] = "rotate";
		      $poi['animations']['onUpdate'][$update]['persist'] = true;
		      $poi['animations']['onUpdate'][$update]['repeat'] = true;
		      $poi['animations']['onUpdate'][$update]['from'] = $from;
		      $poi['animations']['onUpdate'][$update]['to'] = $to;
		   
		      $poi['animations']['onUpdate'][$update]['axis']['x'] = 0;
		      $poi['animations']['onUpdate'][$update]['axis']['y'] = 0;
		      $poi['animations']['onUpdate'][$update]['axis']['z'] = 1;
	    
		}
		
	    $response["hotspots"][$i] = $poi;
	    $i++; 
		    
	}
	
	if ($country == "") { 	// country still unknown? Check it with the Google Maps API
				
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&output=json&sensor=true";
			
		$json = @file_get_contents($url);
		
		$jsondata = json_decode($json,true);
		
		foreach($jsondata['results'][0]['address_components'] as $k=>$found){ 
		  
		     if( ($country == "") && (in_array("country", $found['types']) ) ) {
			       
		       	       $country = $found['long_name'];
		       	       
		       	       
		       	       
		     }
		}
		
		foreach($jsondata['results'][0]['address_components'] as $k=>$found){ 
		  
		     if( ($city == "") && (in_array("locality", $found['types']) ) ) {
			       
		       	       $city = $found['long_name'];
		       	       
		       	       
		     }
		}
		
		
		if ($country != "") {
		
			$imploded = implode(", ", $ids);
				       
		      $q = "update grid set city = '".$city."', country = '".$country."' where id in (".$imploded.")";
		      
		      mysql_query($q);
		      
		}
		

			
	}
      
	$response["errorCode"] = 0;
  	$response["errorString"] = "OK";
  	
	$jsonresponse = json_encode( $response );
	echo $jsonresponse;

}
catch( PDOException $e ){
    echo $e->getMessage();
}



?>
