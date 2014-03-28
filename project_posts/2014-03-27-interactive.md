# INTERACTIVE 

Now that the server-script is functioning and the output is rendered correctly in the Layar browser, the next step is to turn the field of pins to an interactive reactive environment that traces passers by. It's not making going to make use of specific location-based hardware or sensors, but that's what makes it possible to let the the vitual sculpture be available anywhere on the globe.

Whenever a user is within a certain treshold vicinity of a pin, the database entry will be be updated with a timestamp. Freshly 'touched' pins will be animating. The speed of the rotation animation will slow down over time. This is being calculated based on the update timestamp of the pin. User walking within the scope of the same field, will see each others' movements being registered in the pins. This is the database scheme used:

![database Image](../project_images/database.png?raw=true "database Image")

To make this a truly global interactive artwork, the location of each interaction is registered. Not just as an indecipherable lat/lon coordinate, but the Google Maps API is used to retrieve the city/country information and this information is being shown to the next user in the grid. It illustrates that the piece is truly global, with interactions coming from (possibly) all continents of the world.

For the sake of request efficiency, the retrieved information is stored at the time of the first interaction with the pins, so the Google Maps API only needs to be called once in every area:

```
$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&output=json&sensor=true";
			
$json = @file_get_contents($url);

$jsondata = json_decode($json,true);

foreach($jsondata['results'][0]['address_components'] as $k=>$found){ 
  
	if( ($country == "") && (in_array("country", $found['types']) ) ) {
	       
	       $country = $found['long_name'];
	       
	       print_r($ids);
	       
	       
	}
}

```

![data Image](../project_images/data.jpg?raw=true "data Image")

