
@if (isset($element) && is_object($element))
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyA4Q5VgK-858jgeSbJKHbclop_XIJs3lXs&sensor=true"></script>
    <script>

        var geocoder_timer;
        var geo_prefix = "{{ isset($geo_prefix) ? $geo_prefix : '' }}";

        // Run geocoder
        jQuery(function ($) {
            $("input[name='fields[{{ isset($field_address) ? $field_address : 'address' }}]']").keyup(function (el) {
                var $this = $(this);
                clearTimeout(geocoder_timer);
                geocoder_timer = setTimeout(function () {
                    var for_geocode = geo_prefix + ' ' + $this.val();
                    //alert(for_geocode);
                    codeAddress(for_geocode);
                }, {{ isset($keyup_timer) ? $keyup_timer : 1200 }});
            });
        });

        // Center map
        var map_lat = {{ $element->field('lat') > 0 ? $element->field('lat') : (isset($default_lat) ? $default_lat : '62.148051') }};
        var map_lng = {{ $element->field('lng') > 0 ? $element->field('lng') : (isset($default_lng) ? $default_lng : '96.659506') }};
        var map_zoom = {{ $element->field('lat') > 0 && $element->field('lng') > 0 ? 17 : (isset($default_zoom) ? $default_zoom : '3') }};
        var myLatlng = new google.maps.LatLng(map_lat, map_lng);

        // Init map
        var mapOptions = {
                    center: myLatlng,
                    zoom: map_zoom,
                    mapTypeId: {{ isset($map_type) ? $map_type : 'google.maps.MapTypeId.ROADMAP' }},
                },
                map = new google.maps.Map(document.getElementById('{{ isset($map_id) ? $map_id : 'map' }}'), mapOptions),
                geocoder = new google.maps.Geocoder(), // геокодер
                marker = new google.maps.Marker({ // маркер
                    visible: false,
                    draggable: true, // DRAG & DROP
                    map: map
                });

        // Set lat & lng on drop of the marker
        google.maps.event.addListener(marker, 'dragend', function () {
            //console.log(marker.getPosition());
            setCoordsVal(marker.getPosition());
            //geocodePosition(marker.getPosition());
        });

        // Set marker
        marker.setOptions({
            visible: true,
            title: '{{ $element->name }}',
            position: myLatlng
        });
        map.setZoom(map_zoom);

        // Set values of coordinates to inputs on the form
        function setCoordsVal(pos) {
            var lat = pos.lat().toFixed(6) || '';
            var lng = pos.lng().toFixed(6) || '';
            $("input[name='fields[{{ isset($field_lat) ? $field_lat : 'lat' }}]']").val(lat);
            $("input[name='fields[{{ isset($field_lng) ? $field_lng : 'lng' }}]']").val(lng);
        }

        // Geo Coder: address to coordinates
        // http://javascript.ru/forum/dom-window/35145-poisk-po-adresu-geokoding-google-maps-api-v3.html
        function codeAddress(address) {
            //var address = document.getElementById('adr').value;
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setOptions({
                        visible: true,
                        title: address,
                        position: results[0].geometry.location
                    });
                    map.setZoom(17);

                    console.log(results[0].geometry.location);
                    setCoordsVal(results[0].geometry.location);

                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }

        // Geo Coder: coordinates to address
        // http://stackoverflow.com/questions/5688745/google-maps-v3-draggable-marker
        function geocodePosition(pos) {
            //geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                        latLng: pos
                    },
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            console.log(results[0]);
                            //alert(results[0].formatted_address);

                            //$("#mapSearchInput").val(results[0].formatted_address);
                            //$("#mapErrorMsg").hide(100);
                        } else {
                            //$("#mapErrorMsg").html('Cannot determine address at this location.'+status).show(100);
                        }
                    }
            );
        }

    </script>
@endif