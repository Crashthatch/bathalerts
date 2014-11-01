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
 var bounds = new L.LatLngBounds();


$.each(crimeData, function(i, feature) {
	//console.log(crime.location.latitude)
    crimeMarker[i] = L.marker([feature.location.latitude, feature.location.longitude])
                    .bindPopup('<b>Crime Type: </b>'+feature.crime_category+'<br>'+'<b>Location: </b>'+feature.street_name)
                    .addTo(crimeLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
});

$.each(planningData, function(i, feature) {
  //console.log(crime.location.latitude)
    crimeMarker[i] = L.marker([feature.location.latitude, feature.location.longitude])
                    .bindPopup('<b>Reference: </b>'+feature.casereference+'<br>'+'<b>Description: </b>'+feature.casetext)
                    .addTo(planningLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
});



//addDataToMap(planningData,planningLayer,"planning","red","feature.casetext");
//addDataToMap(crimeData,crimeLayer,"crime","blue","crime_id");

/*
$.each(data, function(i, feature) {
    planningMarker[i] = L.marker([feature.location.latitude, feature.location.longitude])
                        .bindPopup()
                        .addTo(layer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
});

*/


    var map = L.map('map', {
       //center: [51.38773470077388, -2.366824150085449],
        //zoom: 15,
        layers: [Acetate_all, crimeLayer, planningLayer]
    });

    var baseLayers = {
        "Acetate": Acetate_all
    };

    var overlays = {
        "Crimes": crimeLayer,
        "Planning Applications": planningLayer
    };

    L.control.layers(baseLayers, overlays).addTo(map);

    var highlight = L.geoJson(null).addTo(map);
    map.fitBounds(bounds);
/*


$.getJSON("assets/php/getMarkers.php", function (data) {
         $.each(data.features, function(index, feature) {
               if (feature.properties.Visited === 'true'){
                   visitedMarkers.addData(feature);
                   //console.log(index + ": visited"); 
               }else{
                   notVisitedMarkers.addData(feature);
                   //console.log(index + ": not visited"); 
               }
         });
*/
