var map = L.map('map').setView([ 51.38773470077388, -2.366824150085449], 17);;

var Acetate_all = L.tileLayer('http://a{s}.acetate.geoiq.com/tiles/acetate-hillshading/{z}/{x}/{y}.png', {
	attribution: '&copy;2012 Esri & Stamen, Data from OSM and Natural Earth',
	subdomains: '0123',
	minZoom: 2,
	maxZoom: 18
}).addTo(map);


crimeURL = "http://data.bathhacked.org/resource/e46f-mhfs.json?$where=month>'2014-08-01' AND month<'2014-10-31' AND within_box(location, 51.387, -2.366, 51.3, -2.3)"; //" AND month IS NOT NULL";

$.getJSON(crimeURL, function (data) {
	$.each(data, function(i) {
    	console.log(data[i].crime_id)
	});
  	crimes = L.geoJson(data).addTo(map); 
}); 


