var map;
var destinationCityCoordinates;
function initMap() {

    var coordonates = {lat: 44.431, lng: 26.086};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15
    });
    var adress = destinationCity;
    console.log('DestinationCity: ' + destinationCity);
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': adress}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            console.log('Geocoder results:' + results[0].geometry.location);
            destinationCityCoordinates = results[0].geometry.location;
            map.setCenter(destinationCityCoordinates);
           
        }

    });
    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: map.getCenter()
    });
    google.maps.event.addListener(marker, "click", function (event) {
        var latitude = event.latLng.lat();
        var longitude = event.latLng.lng();
        console.log(latitude + ', ' + longitude);
    });

    function displayRoute() {

        //     var start = new google.maps.LatLng(44.431, 26.086);
        //     var end = new google.maps.LatLng(44.4432184, 26.0867691);
        var start = homeCoordinates;
        var end = destinationCoordinates;
        var directionsDisplay = new google.maps.DirectionsRenderer();// also, constructor can get "DirectionsRendererOptions" object
        directionsDisplay.setMap(map); // map should be already initialized.

        var request = {
            origin: start,
            destination: end,
            travelMode: google.maps.TravelMode.DRIVING
        };
        var directionsService = new google.maps.DirectionsService();
        directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
//                google.maps.event.addListener(directionsDisplay, 'directions_changed', function () {
//                    map.setCenter(destinationCityCoordinates);
//                });
                //i have to wait for setDirections to finish before i recenter the map
                setTimeout(function () {
                    map.setCenter(destinationCityCoordinates);
                }, 500);
            }
        });
    }

    displayRoute();
}