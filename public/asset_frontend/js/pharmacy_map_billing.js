$(document).ready(function () {
    $(".billingPharmacyZipCodeBtn").click(function (e) {
        e.preventDefault();

        let zipCode = $(".billingPharmacyZipCodeInput").val();

        if (zipCode.length == 0) {
            alert("Please Enter zipcode.");
        } else {
            $(".showPharmacyMap #pharmacyMapBox").show();
            $.ajax({
                //url: "/get_cart_counter/" + session_id,
                url: "/get_maps_locations/" + zipCode,
                type: "GET",
                processData: false,
                contentType: false,
                success: function (response) {
                    var ress = JSON.parse(response);

                    if (ress === 0) {
                        alert("No Locations are found against this zipcode.");
                    } else {
                        $(".selectPharmacyNearbyLocation").html("");
                        $(".selectPharmacyNearbyLocation").append(
                            `<option value="">Select Your Neareast Pharmacy Location</option>`
                        );
                        ress.locations.forEach((item) => {
                            $(".selectPharmacyNearbyLocation").append(
                                `<option value="` +
                                    item.id +
                                    `">` +
                                    item.name +
                                    ` - ` +
                                    item.address +
                                    `</option>`
                            );
                        });

                        var latitude = ress.data[0].lat;
                        var longitude = ress.data[0].long;

                        var map = new google.maps.Map(
                            document.getElementById("pharmacyMapBox"),
                            {
                                center: new google.maps.LatLng(
                                    latitude,
                                    longitude
                                ),
                                zoom: 9,
                            }
                        );
                        var infoWindow = new google.maps.InfoWindow();

                        $.ajax({
                            url:
                                "/get_near_locations/" +
                                latitude +
                                "/" +
                                longitude,
                            type: "GET",
                            processData: false,
                            contentType: false,
                            success: function (res) {
                                let test = "/test.xml";
                                downloadUrl(test, function (data) {
                                    var customLabel = {
                                        pharmacy: {
                                            label: "P",
                                        },
                                    };

                                    var xml = data.responseXML;
                                    var markers =
                                        xml.documentElement.getElementsByTagName(
                                            "marker"
                                        );
                                    Array.prototype.forEach.call(
                                        markers,
                                        function (markerElem) {
                                            var id =
                                                markerElem.getAttribute("id");
                                            var name =
                                                markerElem.getAttribute("name");
                                            var address =
                                                markerElem.getAttribute(
                                                    "address"
                                                );
                                            var type =
                                                markerElem.getAttribute("type");
                                            var point = new google.maps.LatLng(
                                                parseFloat(
                                                    markerElem.getAttribute(
                                                        "lat"
                                                    )
                                                ),
                                                parseFloat(
                                                    markerElem.getAttribute(
                                                        "lng"
                                                    )
                                                )
                                            );

                                            var infowincontent =
                                                document.createElement("div");
                                            var strong =
                                                document.createElement(
                                                    "strong"
                                                );
                                            strong.textContent = name;
                                            infowincontent.appendChild(strong);
                                            infowincontent.appendChild(
                                                document.createElement("br")
                                            );

                                            var text =
                                                document.createElement("text");
                                            text.textContent = address;
                                            infowincontent.appendChild(text);
                                            var icon = customLabel[type] || {};
                                            var marker = new google.maps.Marker(
                                                {
                                                    map: map,
                                                    position: point,
                                                    label: icon.label,
                                                }
                                            );
                                            // console.log(icon)
                                            marker.addListener(
                                                "click",
                                                function () {
                                                    infoWindow.setContent(
                                                        infowincontent
                                                    );
                                                    infoWindow.open(
                                                        map,
                                                        marker
                                                    );
                                                }
                                            );
                                        }
                                    );
                                });
                            },
                        });
                    }
                },
            });
        }
    });
});

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
