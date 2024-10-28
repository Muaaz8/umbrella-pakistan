$(document).ready(function () {
    $(".search_zip_codes_shipping").click(function (e) {
        e.preventDefault();

        let zipCode = $(".zipcodeSearchInput_shipping").val();

        if (zipCode.length == 0) {
            alert("Please Enter zipcode.");
        } else {
            $.ajax({
                //url: "/get_cart_counter/" + session_id,
                url: "/get_maps_locations/" + zipCode,
                type: "GET",
                processData: false,
                contentType: false,
                success: function (response) {
                    var ress = JSON.parse(response);

                    var latitude = ress[0].lat;
                    var longitude = ress[0].long;

                    var map = new google.maps.Map(
                        document.getElementById("map_shipping"),
                        {
                            center: new google.maps.LatLng(latitude, longitude),
                            zoom: 12,
                        }
                    );
                    var infoWindow = new google.maps.InfoWindow();

                    $.ajax({
                        url:
                            "/get_near_locations/" + latitude + "/" + longitude,
                        type: "GET",
                        processData: false,
                        contentType: false,
                        success: function (res) {
                            let test = "/test.xml";
                            downloadUrl(test, function (data) {
                                var xml = data.responseXML;
                                var markers = xml.documentElement.getElementsByTagName(
                                    "marker"
                                );
                                Array.prototype.forEach.call(markers, function (
                                    markerElem
                                ) {
                                    var id = markerElem.getAttribute("id");
                                    var name = markerElem.getAttribute("name");
                                    var address = markerElem.getAttribute(
                                        "address"
                                    );
                                    var type = markerElem.getAttribute("type");
                                    var point = new google.maps.LatLng(
                                        parseFloat(
                                            markerElem.getAttribute("lat")
                                        ),
                                        parseFloat(
                                            markerElem.getAttribute("lng")
                                        )
                                    );

                                    var infowincontent = document.createElement(
                                        "div"
                                    );
                                    var strong = document.createElement(
                                        "strong"
                                    );
                                    strong.textContent = name;
                                    infowincontent.appendChild(strong);
                                    infowincontent.appendChild(
                                        document.createElement("br")
                                    );

                                    var text = document.createElement("text");
                                    text.textContent = address;
                                    infowincontent.appendChild(text);
                                    var icon = customLabel[type] || {};
                                    var marker = new google.maps.Marker({
                                        map: map,
                                        position: point,
                                        label: icon.label,
                                    });
                                    marker.addListener("click", function () {
                                        infoWindow.setContent(infowincontent);
                                        infoWindow.open(map, marker);
                                    });
                                });
                            });
                        },
                    });
                },
            });
        }
    });
});

var customLabel = {
    restaurant: {
        label: "P",
    },
    bar: {
        label: "B",
    },
};

function initMap() {}

function downloadUrl(url, callback) {
    var request = window.ActiveXObject
        ? new ActiveXObject("Microsoft.XMLHTTP")
        : new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
        }
    };

    request.open("GET", url, true);
    request.send(null);
}

function doNothing() {}
