

var map
function loadmap(){
    //this to be decided with a formula based on the median of all patient's locations
    var vaxjo = [14.781932, 56.890053];



    //creating the map
    map = new ol.Map({target: 'map', layers: [new ol.layer.Tile({source: new ol.source.OSM()})],
    view: new ol.View({center: ol.proj.fromLonLat(vaxjo),zoom: 14})});
    map.on('click', function(evt) {
  showdata(ol.proj.toLonLat(evt.coordinate));});

   
    
}
function loadlocations(lat, lang){
    //this to be retrieved from database and passed on as Lat/Lan
    var patient =[lat, lang];
    console.log(patient);
    //creating vectors for patients locations
    var patient1 = new ol.layer.Vector({source: new ol.source.Vector({features: [new ol.Feature({geometry: new ol.geom.Point(ol.proj.fromLonLat(patient))})
                                                                                ]
                                                                    }
                                                                    )});

    
    
    //adding vectors to map
    map.addLayer(patient1);
     
  
}
function showdata(lat){   
    var latlonid; 
    lat[0] = Math.round(1000*lat[0])/1000;
    lat[1] = Math.round(1000*lat[1])/1000;
    console.log(lat);
    latlonid = lat[0]+'_'+lat[1];
    console.log(latlonid);
    window.location.assign('visualize.php?latlonid='+latlonid);
}


