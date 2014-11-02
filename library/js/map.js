var Acetate_all = L.tileLayer('http://a{s}.acetate.geoiq.com/tiles/acetate-hillshading/{z}/{x}/{y}.png', {
	attribution: '&copy;2012 Esri & Stamen, Data from OSM and Natural Earth',
	subdomains: '0123',
	minZoom: 2,
	maxZoom: 18
})

var Hydda_Full = L.tileLayer('http://{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png', {
  minZoom: 0,
  maxZoom: 18,
  attribution: 'Tiles courtesy of <a href="http://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
});

var Stamen_TonerLite = L.tileLayer('http://{s}.tile.stamen.com/toner-lite/{z}/{x}/{y}.png', {
  attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> &mdash; Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
  subdomains: 'abcd',
  minZoom: 0,
  maxZoom: 20
});
var Esri_WorldImagery = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
  attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
});



 var crimeLayer = L.layerGroup();
 var crimeMarker = [];

 var planningLayer = L.layerGroup();
 var planningMarker = [];

 var propertyLayer = L.layerGroup();
 var propertyMarker = [];

 var floodLayer = L.layerGroup();
 var floodArea = [];

 var bounds = new L.LatLngBounds();
 var layersOnMap = [];

 var clusterLayer = new L.MarkerClusterGroup({
    maxClusterRadius: 1,
    spiderfyOnMaxZoom: true, 
    showCoverageOnHover: false, 
    zoomToBoundsOnClick: true,
    animateAddingMarkers: true
  });

planningIcon  = L.AwesomeMarkers.icon({icon: 'fa-file-text', markerColor: 'red', prefix: 'fa'}) 
crimeIcon     = L.AwesomeMarkers.icon({icon: 'fa-gavel', markerColor: 'blue', prefix: 'fa'}) 
propertyIcon  = L.AwesomeMarkers.icon({icon: 'fa-gbp', markerColor: 'green', prefix: 'fa'}) 
postcodeIcon  = L.AwesomeMarkers.icon({icon: 'fa-home', markerColor: 'orange', prefix: 'fa'}) 
loadingIcon   = L.AwesomeMarkers.icon({icon: 'fa-refresh', markerColor: 'orange', prefix: 'fa', spin: true}) 


$.each(floodData.ProximityFloodAlerts, function(i, feature){
    var floodAlertBounds = [[feature.Bounds.BottomRight.Latitude, feature.Bounds.BottomRight.Longitude], [feature.Bounds.TopLeft.Latitude, feature.Bounds.TopLeft.Longitude]];
    floodArea[i] =  L.rectangle(floodAlertBounds, {
                                  color: "#E0753C", 
                                  fillColor: "#E0753C", 
                                  fillOpacity: 0.2, 
                                  weight: 3, 
                                  dashArray:10
                                })
                      .bindPopup("<b>Environment Agency Flood Alert</b>"
                                +"<br><b>Date Issued: </b>" + feature.FloodAlert.Raised.substring(0, 10)
                                +"<br><b>Location: </b>" + feature.FloodAlert.AreaDescription
                                +"<br><b>Severity: </b>" + feature.FloodAlert.Severity
                          )
                      .addTo(floodLayer);
    layersOnMap.push(floodLayer);

})


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
    clusterLayer.addLayer(planningLayer);
});

$.each(crimeData, function(i, feature) {
  //console.log(crime.location.latitude)
    crimeMarker[i] = L.marker([feature.location.latitude, feature.location.longitude], {icon: crimeIcon})
                    .bindPopup('<b>Date: </b>'+feature.month.substring(0, 7)
                              +'<br><b>Crime Type: </b>'+toTitleCase(feature.crime_category).split('-').join(' ')
                              +'<br>'+'<b>Location: </b>'+feature.street_name)
                    .addTo(crimeLayer);
    bounds.extend([feature.location.latitude, feature.location.longitude]);
    layersOnMap.push(crimeLayer);
    clusterLayer.addLayer(crimeLayer);
});

$.each(houseData, function(i, feature) {
    propertyMarker[i] = L.marker([feature.location.latitude, feature.location.longitude], {icon: propertyIcon})
                    .bindPopup('<b>Date of sale: </b>'+feature.date_of_transfer.substring(0, 10)
                                +'<br><b>Price:</b> £'+numberWithCommas(feature.price)
                                +'<br><b>Type of property: </b>' + typeOfProperty(feature.property_type)
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
    clusterLayer.addLayer(propertyLayer);
});

var map = L.map('map',{
    attributionControl: false,
    maxZoom: 18,
    layers: Hydda_Full
  }).setView([51.477106,-2.690277], 10);
  map.addLayer(clusterLayer);
   var dummyCrimeLayer = L.layerGroup().addTo(map);
   var dummyPlanningLayer = L.layerGroup().addTo(map);
   var dummyPropertyLayer = L.layerGroup().addTo(map);
/*

    var map = L.map('map', {
        layers: layersOnMap
    });

    */

    var baseLayers = {
        //"Acetate": Acetate_all,
        "Road Map": Hydda_Full,
        //"Satmen Toner Lite": Stamen_TonerLite,
        "Satellite Imagery": Esri_WorldImagery 

    };

    var overlays = {
        "Crimes": dummyCrimeLayer,
        "Planning Applications": dummyPlanningLayer,
        "Property Sales": dummyPropertyLayer,
        "Flood Alerts": floodLayer
    };
   var drawnItems = new L.FeatureGroup();
   var postcodeRadius = L.circle([searchedForPostcode[1],searchedForPostcode[0]], 500, {
                                color: 'red', 
                                fillColor:'red',
                                fillOpacity: 0.1, 
                                opacity: 1, 
                               // dashArray: '20',
                                zIndexOffset: 9999
                              }).addTo(drawnItems);

    L.control.layers(baseLayers, overlays).addTo(map);
    var postcodeMarker = L.marker([searchedForPostcode[1],searchedForPostcode[0]], {icon: postcodeIcon, zIndexOffset:999}).bindPopup('Your location').addTo(map);
    bounds.extend([searchedForPostcode[1],searchedForPostcode[0]]);
    map.fitBounds(bounds);
   // map.addLayer(clusterLayer);

// toggle email/list checkboxes when map layer control is changed.
map.on("overlayadd", function(e) {
  if (e.layer === dummyCrimeLayer) {
    clusterLayer.addLayer(crimeLayer);
    //console.log('dummylayer crime added')
    $('#crimes_check').prop('checked', true);
  }
  if (e.layer === dummyPlanningLayer) {
    //console.log('dummylayer plan added')
    clusterLayer.addLayer(planningLayer);
    $('#planning-applications_check').prop('checked', true);
  } 
  if (e.layer === dummyPropertyLayer) {
    //console.log('dummylayer prop added')
    clusterLayer.addLayer(propertyLayer);
    $('#house-sales_check').prop('checked', true);
  } 
});
map.on("overlayremove", function(e) {
  if (e.layer === dummyCrimeLayer) {
    //console.log('dummylayer crime removed');
    clusterLayer.removeLayer(crimeLayer);
    $('#crimes_check').prop('checked', false);
  }
  if (e.layer === dummyPlanningLayer) {
    //console.log('dummylayer planning removed')
    clusterLayer.removeLayer(planningLayer);
    $('#planning-applications_check').prop('checked', false);
  } 
  if (e.layer === dummyPropertyLayer) {
   // console.log('dummylayer property removed')
    clusterLayer.removeLayer(propertyLayer);
    $('#house-sales_check').prop('checked', false);
  } 
});

//Change map content when email/list checkboxes are changed
$('#crimes_check').on('change', function(){
  if ($('#crimes_check').prop('checked')){
    clusterLayer.addLayer(crimeLayer)
  }else{
    clusterLayer.removeLayer(crimeLayer)
  }
})
$('#planning-applications_check').on('change', function(){
  if ($('#planning-applications_check').prop('checked')){
    clusterLayer.addLayer(planningLayer)
  }else{
    clusterLayer.removeLayer(planningLayer)
  }
})
$('#house-sales_check').on('change', function(){
  if ($('#house-sales_check').prop('checked')){
    clusterLayer.addLayer(propertyLayer)
  }else{
    clusterLayer.removeLayer(propertyLayer)
  }
})
$('#flood-risk_check').on('change', function(){
  if ($('#flood-risk_check').prop('checked')){
    map.addLayer(floodLayer);
  }else{
    map.removeLayer(floodLayer);
  }
})

$('#custom-search-button').on("click", function(){
  if ($('#custom-search-button').html() === "Change"){
    setCustomRadius();
    $('#custom-search-button').html("Update map content");
  }else{
    saveCustomRadius();
    $('#custom-search-button').html("Loading content...");
    postcodeMarker.setIcon(loadingIcon);
    map.removeLayer(clusterLayer);
    map.removeLayer(floodLayer);
    bounds = [];
  }

})

var editing_radius;
map.on("zoomend", function(e) {
 if (editing_radius === true)  {
   // map.addLayer(clusterLayer);
      setTimeout(function() {
               $('.awesome-marker, .leaflet-shadow-pane, .marker-cluster').addClass('fade-markers');
      }, 1);
  }
});
map.on("zoomstart", function(e) {
 if (editing_radius === true)  {
     // map.removeLayer(clusterLayer);
      setTimeout(function() {
               $('.awesome-marker, .leaflet-shadow-pane, .marker-cluster').addClass('fade-markers');
      }, 1);
  }
});

map.on("mouseup", function(e) {
  if (editing_radius === true)  {
    $('#user-radius').val(Math.round(drawnItems.getLayers()[0]._mRadius))
    $('#user-lat-hidden').val(drawnItems.getLayers()[0]._latlng.lat)
    $('#user-long-hidden').val(drawnItems.getLayers()[0]._latlng.lng)
    postcodeMarker.setLatLng([drawnItems.getLayers()[0]._latlng.lat,drawnItems.getLayers()[0]._latlng.lng]);
    console.log("Radius of drawn circle: " + Math.round(drawnItems.getLayers()[0]._mRadius) + "m around "+drawnItems.getLayers()[0]._latlng.lat+','+drawnItems.getLayers()[0]._latlng.lng)
  
  }
});


function setCustomRadius(){
   //$('.awesome-marker, .leaflet-shadow-pane').css({'opacity':'0.2'});
    $('.awesome-marker, .leaflet-shadow-pane, .marker-cluster').addClass('fade-markers');
    postcodeRadius.editing.enable();
    postcodeMarker.closePopup();
    map.addLayer(drawnItems);
    $('.leaflet-editing-icon').addClass('radiusDragPoints');
    editing_radius = true;
}

function saveCustomRadius(){
  //$('.awesome-marker, .leaflet-shadow-pane').css({'opacity':'1'});
  $('.awesome-marker, .leaflet-shadow-pane, .marker-cluster').removeClass('fade-markers');
  postcodeRadius.editing.disable();
  map.removeLayer(drawnItems);
  console.log("Radius of drawn circle: " + Math.round(drawnItems.getLayers()[0]._mRadius) + "m around "+drawnItems.getLayers()[0]._latlng.lat+','+drawnItems.getLayers()[0]._latlng.lng)
  $('#user-radius').val(Math.round(drawnItems.getLayers()[0]._mRadius))
  $('#user-lat-hidden').val(drawnItems.getLayers()[0]._latlng.lat)
  $('#user-long-hidden').val(drawnItems.getLayers()[0]._latlng.lng)
  postcodeMarker.setLatLng([drawnItems.getLayers()[0]._latlng.lat,drawnItems.getLayers()[0]._latlng.lng]);
  postcodeMarker.unbindPopup().bindPopup("Your custom location");
  //window.location = "/report/" + drawnItems.getLayers()[0]._latlng.lat + ',' + drawnItems.getLayers()[0]._latlng.lng;
  editing_radius = false;
  $('form').submit();
}


// functions to clean up popup text
function toTitleCase(str){
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function typeOfProperty(type){
  switch (type) {
    case "D":
        fullType = "Detached house";
        break;
    case "S":
        fullType = "Semi-detached house";
        break;
    case "T":
        fullType = "Terrace";
        break;
    case "F":
        fullType = "Flat";
        break;
  }
  return fullType;
}



