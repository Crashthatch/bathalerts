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



planningIcon  = L.AwesomeMarkers.icon({icon: 'fa-file-text', markerColor: 'red', prefix: 'fa'}) 
crimeIcon     = L.AwesomeMarkers.icon({icon: 'fa-gavel', markerColor: 'blue', prefix: 'fa'}) 
propertyIcon  = L.AwesomeMarkers.icon({icon: 'fa-gbp', markerColor: 'green', prefix: 'fa'}) 
postcodeIcon  = L.AwesomeMarkers.icon({icon: 'fa-home', markerColor: 'orange', prefix: 'fa'}) 

$.each(crimeData, function(i, feature) {
	//console.log(crime.location.latitude)
    crimeMarker[i] = L.marker([feature.location.latitude, feature.location.longitude], {icon: crimeIcon})
                    .bindPopup('<b>Date: </b>'+feature.month.substring(0, 7)+'<br><b>Crime Type: </b>'+feature.crime_category+'<br>'+'<b>Location: </b>'+feature.street_name)
                    .addTo(crimeLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
    layersOnMap.push(crimeLayer);
});

$.each(planningData, function(i, feature) {
    planningMarker[i] = L.marker([feature.location.latitude, feature.location.longitude], {icon: planningIcon})
                    .bindPopup('<b>Date Submitted: </b>'+feature.casedate.substring(0,10)
                              +'<br><b>Reference: </b>'+feature.casereference
                              +'<br><b>Description: </b>'+feature.casetext
                              +'<br><b>Location: </b>'+feature.locationtext
                              +'<br><b>Status: </b>'+feature.banesstatus
                            )
                    .addTo(planningLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
    layersOnMap.push(planningLayer);
});

$.each(houseData, function(i, feature) {
    propertyMarker[i] = L.marker([feature.location.latitude, feature.location.longitude], {icon: propertyIcon})
                    .bindPopup('<b>Date of sale: </b>'+feature.date_of_transfer.substring(0, 10)
                                +'<br><b>Price:</b> £'+numberWithCommas(feature.price)
                                +'<br><b>Address: </b>' + (feature.primary_addressable_object_name ? toTitleCase(feature.primary_addressable_object_name) + ', ' : '')
                                                        + (feature.secondary_addressable_object_name ? toTitleCase(feature.secondary_addressable_object_name) + ', ' : '')
                                                        + (feature.locality ? toTitleCase(feature.locality) + ', ' : '')
                                                        + (feature.town_city ? toTitleCase(feature.town_city) + ', ' : '')
                                                        + (feature.district ? toTitleCase(feature.district) + ', ' : '')
                                                        + feature.postcode
                              )
                    .addTo(propertyLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
    layersOnMap.push(propertyLayer);

    //{"old_new":"N","date_of_transfer":"2014-03-07T00:00:00","property_type":"T","duration":"L","price":"925000","primary_addressable_object_name":"34","town_city":"BATHWICK","locality":"ST JOHNS ROAD","district":"BATH"}];
});


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
    var postcodeMarker = L.marker([searchedForPostcode[1],searchedForPostcode[0]], {icon: postcodeIcon, zIndexOffset:999999}).bindPopup('Your Postcode').addTo(map);
    var postcodeRadius;
    var highlight = L.geoJson(null).addTo(map);
    map.fitBounds(bounds);


function toTitleCase(str){
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


