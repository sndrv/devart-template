# INTERACTIVE 

Now that the server-script is functioning and the output is rendered correctly in the Layar browser, the next step is to turn the field of pins to an interactive reactive environment that traces passers by. It's not making going to make use of specific location-based hardware or sensors, but that's what makes it possible to let the the vitual sculpture be available anywhere on the globe.

Whenever a user is within a certail treshold vicinity of a pin, the database entry will be be updated with a timestamp. Freshly 'touched' pins will be animating. The speed of the rotation animation will slow down over time. This is being calculated based on the update timestamp of the pin. User walking within the scope of the same field, will see each others' movements being registered in the pins.

But to make this a truly global interactive artwork, the location of each interaction is registered. Not just as an indecipherable lat/lon coordinate, but the Google Maps API is used to retrieve the city/country information and this information is being shown to the next user in the grid. It illustrates that the piece is truly global, with interactions coming from (possibly) all continents of the world.


