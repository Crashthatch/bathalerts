var Acetate_all = L.tileLayer('http://a{s}.acetate.geoiq.com/tiles/acetate-hillshading/{z}/{x}/{y}.png', {
	attribution: '&copy;2012 Esri & Stamen, Data from OSM and Natural Earth',
	subdomains: '0123',
	minZoom: 2,
	maxZoom: 18
})

 var crimeLayer = L.layerGroup()
 var crimeMarker = [];
 var planningLayer = L.layerGroup()
 var planningMarker = [];
 var propertyLayer = L.layerGroup()
 var propertyMarker = [];

 var bounds = new L.LatLngBounds();
 var layersOnMap = [];
 layersOnMap.push(Acetate_all);



planningIcon  = L.AwesomeMarkers.icon({icon: 'fa-gavel', markerColor: 'red', prefix: 'fa'}) 
crimeIcon     = L.AwesomeMarkers.icon({icon: 'fa-frown-o', markerColor: 'blue', prefix: 'fa'}) 
propertyIcon  = L.AwesomeMarkers.icon({icon: 'fa-gbp', markerColor: 'green', prefix: 'fa'}) 
postcodeIcon  = L.AwesomeMarkers.icon({icon: 'fa-home', markerColor: 'orange', prefix: 'fa'}) 

$.each(crimeData, function(i, feature) {
	//console.log(crime.location.latitude)
    crimeMarker[i] = L.marker([feature.location.latitude, feature.location.longitude], {icon: crimeIcon})
                    .bindPopup('<b>Date: </b>'+feature.month+'<br><b>Crime Type: </b>'+feature.crime_category+'<br>'+'<b>Location: </b>'+feature.street_name)
                    .addTo(crimeLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
    layersOnMap.push(crimeLayer);
});

$.each(planningData, function(i, feature) {
    planningMarker[i] = L.marker([feature.location.latitude, feature.location.longitude], {icon: planningIcon})
                    .bindPopup('<b>Reference: </b>'+feature.casereference+'<br>'+'<b>Description: </b>'+feature.casetext)
                    .addTo(planningLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
    layersOnMap.push(planningLayer);
});

$.each(houseData, function(i, feature) {
    propertyMarker[i] = L.marker([feature.location.latitude, feature.location.longitude], {icon: propertyIcon})
                    .bindPopup('<b>Date of sale: </b>'+feature.date_of_transfer+'<br>'+'<b>Price:</b> £'+feature.price)
                    .addTo(propertyLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
    layersOnMap.push(propertyLayer);
});

L.marker([feature.location.latitude, feature.location.longitude], {icon: postcodeIcon}).bindPopup('Your Postcode').addTo(map);

    var map = L.map('map', {
        layers: layersOnMap
    });

    var baseLayers = {
        "Acetate": Acetate_all
    };

    var overlays = {
        "Crimes": crimeLayer,
        "Planning Applications": planningLayer,
        "Property Sales": propertyLayer
    };

    L.control.layers(baseLayers, overlays).addTo(map);

    var highlight = L.geoJson(null).addTo(map);
    map.fitBounds(bounds);

