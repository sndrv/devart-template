# CODE

```
$lat = $_GET['lat'];
$lon = $_GET['lon'];
```



```
$factor = 1000000;
$flat = floor($lat*$factor/($factor*4*$mlat))*4*$mlat;
$flon = floor($lon*$factor/($factor*4*$mlon))*4*$mlon;
```


```
for ($x=-7;$x<8;$x++) {
	for ($y=-7;$y<8;$y++) {
	
		// calculate the lat/lon 
		$ilat = $x * 4 * $mlat + $flat; 
		$ilon = $y * 4 * $mlon + $flon; 
		
```

```
$poi["lat"] = $ilat*1000000;	// no floats accepted, convert to int
$poi["lon"] = $ilon*1000000;
```	      

```
$response["hotspots"][$i] = $poi;
$i++
```

```
echo json_encode( $response );
```
