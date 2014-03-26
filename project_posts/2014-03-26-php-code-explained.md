# CODE EXPLAINED

The layar browser requests a webserver to get the POI (points of interest) to display. In the call, it passes lat/lon parameters to the server 


```
json.php / v0.5

```

```
$lat = $_GET['lat'];
$lon = $_GET['lon'];
```

We want to position the pins on a grid, and keep them on the same grid for all users. The sculpture will be a multi-user experience. To prevent pre-calculation of all pins all over the world, any lat/lon coordinate is mapped to a coordinate that is rounded off. In this case, there's 4m of spacing between te grid points:

```
$factor = 1000000;
$flat = floor($lat*$factor/($factor*4*$mlat))*4*$mlat;
$flon = floor($lon*$factor/($factor*4*$mlon))*4*$mlon;
```

Based on the flat/flon coordinate, a full grid of points is created in x,y directions:

```
for ($x=-7;$x<8;$x++) {
	for ($y=-7;$y<8;$y++) {
	
		// calculate the lat/lon 
		$ilat = $x * 4 * $mlat + $flat; 
		$ilon = $y * 4 * $mlon + $flon; 
		
```

On each of these coordinates, a "poi" will appear with specs defined in a $poi object. Lat, lon, title, size etc will be specified:

```
$poi["lat"] = $ilat*1000000;	// no floats accepted, convert to int
$poi["lon"] = $ilon*1000000;
```	      

Each POI is added to the response array:

```
$response["hotspots"][$i] = $poi;
$i++
```

When the response array is ready, it is returned to the layar augmented reality browser in JSON format:

```
echo json_encode( $response );
```
