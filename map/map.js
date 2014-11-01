

var Acetate_all = L.tileLayer('http://a{s}.acetate.geoiq.com/tiles/acetate-hillshading/{z}/{x}/{y}.png', {
	attribution: '&copy;2012 Esri & Stamen, Data from OSM and Natural Earth',
	subdomains: '0123',
	minZoom: 2,
	maxZoom: 18
})



var crimesLayer2 = new L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
       // console.log(feature)
      return L.marker(latlng, {
        //icon: getMakiIcon(feature.properties.Type, feature.properties.Visited),
        title: feature.crime_id
      });
    },
    onEachFeature: function (feature, layer) {

        var content = feature.crime_id;
          
        layer.bindPopup(content);

        layer.on({
            click: function (e) {
            // highlight.clearLayers().addLayer(L.ellipse([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], [500, 200], 0,{
                highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], {
                stroke: false,
                fillColor: "#767474",
                fillOpacity: 0.7,
                radius: 10
              })
            )}
        })
    }
});
 var crimesLayer = L.layerGroup()
 var crimeMarker = [];

//crimeURL = "http://data.bathhacked.org/resource/e46f-mhfs.json?$where=month>'2014-08-01' AND month<'2014-10-31' AND within_box(location, 51.387, -2.366, 51.3, -2.3)"; //" AND month IS NOT NULL";
//crimeURL = "http://localhost:30080/crimetest.php?pc=ba12lw"
//$.getJSON(crimeURL, function (data) {
$.each(crimeData, function(i, crime) {
	//console.log(crime.location.latitude)
    crimeMarker[i] = L.marker([crime.location.latitude, crime.location.longitude]).bindPopup('crime_'+i+': '+crime.crime_id).addTo(crimesLayer);

});



    var map = L.map('map', {
       center: [51.38773470077388, -2.366824150085449],
        zoom: 15,
        layers: [Acetate_all, crimesLayer]
    });

    var baseLayers = {
        "Acetate": Acetate_all
    };

    var overlays = {
        "Crimes": crimesLayer
    };

    L.control.layers(baseLayers, overlays).addTo(map);

    var highlight = L.geoJson(null).addTo(map);
   //  map.fitBounds(crimesLayer.getBounds());
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





