function changeColor(row) {
    row.style.backgroundColor = "#FFEB99";
}


var request;
var marker;

function showDetails(url) {
    if (window.XMLHttpRequest) {
        request = new XMLHttpRequest();

    } else if (window.ActiveXObject) {
        request = new ActiveXObject("Microsoft.XMLHTTP");
    }

    request.open("GET", url, true);
    request.send(null);

    document.getElementById("detalji").innerHTML = '<img src="assets/loading.gif"/>';

    request.onreadystatechange = function() {
        if (request.readyState == 4) { // state is DONE
            if (request.status == 200) {
                document.getElementById("detalji").innerHTML = request.responseText;
            } else {
                alert("Status: " + request.statusText);
            }
        }

    };
}

function mapMe(lat, lon, naziv, web) {
    document.getElementById("mapid").style.visibility = 'visible';
    mymap.setView([lat, lon], 5);
    try {
        mymap.removeLayer(marker);
    } catch (err) {}
    marker = L.marker([lat, lon]).addTo(mymap);
    marker.bindPopup('Film: ' + naziv + '<br/>Geo. širina: ' + lat + '<br/>Geo. dužina: ' + lon + '<br/>Wikipedia: ' + web).openPopup();
}