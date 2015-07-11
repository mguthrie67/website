function updateMap() {
    var loc = document.getElementById("location").value;
    loc=loc.replace(" ", "+");

    if (loc.length>8) {
        document.getElementById("map").innerHTML = "<iframe width='100%' height='310' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://www.google.com/maps/embed/v1/search?key=AIzaSyBimDKYQtLr5Us6EbldvgtMqROoYrXAn9U&q=" + loc + "'></iframe><br />";
    }
}

function sendTestMail() {
    var xmlhttp = new XMLHttpRequest();
    var vars = "subject="+document.getElementById("subject").value+"&body="+document.getElementById("body").value;

    document.getElementById("StatusArea").innerHTML = "Trying...";

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("StatusArea").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST", "sendmail.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(vars);
}

function saveMail(id) {
    var xmlhttp = new XMLHttpRequest();
    var vars = "id="+id+"&subject="+document.getElementById("subject").value+"&body="+document.getElementById("body").value;

    document.getElementById("StatusArea").innerHTML = "Trying...";

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("StatusArea").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST", "saveemail.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(vars);
}

function uploadFile() {

//    alert("hello");

    var form = document.getElementById("file-form");
    var formData = new FormData(form);

    event.preventDefault();

//    var fileData = new FormData(document.getElementById("fileToUpload"));
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "uploadImage.php", true);
//    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//    xhr.setRequestHeader('Accept', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("StatusArea").innerHTML = xhr.responseText;
        }
    }

    xhr.send(formData);
}

function testWebMail(id) {
    var strWindowFeatures = "location=yes,height=570,width=520,scrollbars=yes,status=yes";
    var URL = "testweb.php?id=" + id +"&subject="+document.getElementById("subject").value+"&body="+document.getElementById("body").value;
    alert(URL);
    var win = window.open(URL, "_blank", strWindowFeatures);
}
