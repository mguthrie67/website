function updateMap() {
    var loc = document.getElementById("location").value;
    loc=loc.replace(" ", "+");

    if (loc.length>8) {
        document.getElementById("map").innerHTML = "<iframe width='100%' height='310' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://www.google.com/maps/embed/v1/search?key=AIzaSyBimDKYQtLr5Us6EbldvgtMqROoYrXAn9U&q=" + loc + "'></iframe><br />";
    }
}

function sendTestMail() {
    var xmlhttp = new XMLHttpRequest();
    var vars = "subject="+document.getElementById("subject").value+"&body="+document.getElementById("body").value+"&from="+document.getElementById("from").value;

    document.getElementById("StatusArea").innerHTML = "Trying...";

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("StatusArea").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST", "campaign_sendmail.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(vars);
}

function saveMail(id) {
    var xmlhttp = new XMLHttpRequest();
    var vars = "id="+id+"&subject="+document.getElementById("subject").value+"&body="+document.getElementById("body").value+"&sender="+document.getElementById("from").value;

    document.getElementById("StatusArea").innerHTML = "Trying...";

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("StatusArea").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST", "campaign_saveemail.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(vars);

    // enable buttons
    step3_on();
}

function uploadFile() {

    var form = document.getElementById("file-form");
    var formData = new FormData(form);

    event.preventDefault();

//    var fileData = new FormData(document.getElementById("fileToUpload"));
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "campaign_uploadImage.php", true);
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
//  Open a window and post to the testweb.php script to show a preview.


    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "campaign_testweb.php");

// setting form target to a window named 'formresult'
    form.setAttribute("target", "formresult");

    var field1 = document.createElement("input");
    field1.setAttribute("name", "subject");
    field1.setAttribute("value", document.getElementById("subject").value);
    form.appendChild(field1);

// Can't work out how to use textarea so convert \n to <br> and use a standard input

    var str = document.getElementById("body").value;
    str = str.replace(/(?:\r\n|\r|\n)/g, '<br />');

    var field2 = document.createElement("input");
    field2.setAttribute("name", "body");
    field2.setAttribute("value", str);
    form.appendChild(field2);

    var field3 = document.createElement("input");
    field3.setAttribute("name", "from");
    field3.setAttribute("value", document.getElementById("from").value);
    form.appendChild(field3);

    document.body.appendChild(form);

// creating the 'formresult' window with custom features prior to submitting the form
    window.open("campaign_testweb.php", 'formresult', 'scrollbars=no,menubar=no,height=800,width=1000,resizable=yes,toolbar=no,status=no');

    form.submit();

// delete form

    form.parentNode.removeChild(form);

}

function getInsightly() {
    var xmlhttp = new XMLHttpRequest();
    var vars = "type="+document.getElementById("type").value+"&search="+document.getElementById("search").value;

    document.getElementById("StatusArea").innerHTML = "Trying...";

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("StatusArea").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST", "campaign_getInsightly.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(vars);
}


function step3_off() {
// turn off the options if the text has changed. Force a save to re-enable
    document.getElementById("testWeb").disabled = true;
    document.getElementById("testMail").disabled = true;
    document.getElementById("Next").disabled = true;
    document.getElementById("Save").disabled = false;
}

function step3_on() {
// turn oon the options if save has been hit
    document.getElementById("testWeb").disabled = false;
    document.getElementById("testMail").disabled = false;
    document.getElementById("Next").disabled = false;
    document.getElementById("Save").disabled = true;
}