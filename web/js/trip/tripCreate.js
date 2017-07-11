var map;
var homeInfo;
var destinationInfo;
var homeMarker = null;
var destinationMarker = null;
var infoWindow = null;
//set tootltip on coordinates inputs so users know how to use them
$(document).ready(function () {
    var useMapTooltip = 'Use the map from the bottom of the page to get coordinates';
    $('#homeLatitude, #homeLongitude, #destinationLatitude, #destinationLongitude')
            .tooltip({'trigger': 'focus', 'title': useMapTooltip});
    $('[data-toggle="popover"]').popover();
});
function initMap() {

    homeInfo = new google.maps.InfoWindow({
        content: 'Home'
    });
    destinationInfo = new google.maps.InfoWindow({
        content: 'Destination'
    });
    var coordinates = {lat: 44.431, lng: 26.086};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15
    });
    var adress = 'New York';
    console.log('DestinationCity: ' + coordinates.toString());
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': adress}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            console.log('Geocoder results:' + results[0].geometry.location);
            map.setCenter(results[0].geometry.location);

        }

        //get current position of the user
        infoWindow = new google.maps.InfoWindow;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setCenter(pos);
                
                //if geolocation works put markers on current adress
                putMarker();
            }, function () {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }

    });

}

//put the home and destination markers and set the drag events
function putMarker()
{

    var center = map.getCenter();
    console.log(center);
    var homeMarker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: map.getCenter()
    });
    var destinationMarker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: map.getCenter()
    });

    //the coordinates are stored when the dragevent ends
    google.maps.event.addListener(homeMarker, "dragend", function (event) {
        var latitude = event.latLng.lat();
        var longitude = event.latLng.lng();
        $('#homeLatitude').val(latitude);
        $('#homeLongitude').val(longitude);
        console.log(latitude + ', ' + longitude);
    });
    //the coordinates are stored when the dragevent ends
    google.maps.event.addListener(destinationMarker, "dragend", function (event) {
        var latitude = event.latLng.lat();
        var longitude = event.latLng.lng();
        $('#destinationLatitude').val(latitude);
        $('#destinationLongitude').val(longitude);
        console.log(latitude + ', ' + longitude);
    });

    //add the info
    homeMarker.addListener('mouseover', function () {
        homeInfo.open(map, homeMarker);
    });
    destinationMarker.addListener('mouseover', function () {
        destinationInfo.open(map, destinationMarker);
    });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
    
    //If geolocation isn't available use a default starting location
    putMarker();
}