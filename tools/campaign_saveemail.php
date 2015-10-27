<?php

//
// Called to save the email while it is being created.
// Expects id=[number]&subject=[string]&body=[string]&sender=[string]
//
//
//


// security stuff
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() !== true) {
    die("Error - not logged in!");
}

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_CAMPAIGN);

$id = $_POST["id"];
$subject = mysqli_real_escape_string($db, $_POST["subject"]);
$body = mysqli_real_escape_string($db, $_POST["body"]);
$sender = mysqli_real_escape_string($db, $_POST["sender"]);

// check if anything is there already
$sql = "select subject, body, sender from event_email where campaign_id=" . $id;

if(!$results = $db->query($sql)){
    echo "Oops!! Error accessing database.";
    die();
}

// nothing there
if ($results->num_rows==0){

    $sql = "INSERT INTO event_email (campaign_id, subject, body, sender)
            VALUES($id, '$subject', '$body', '$sender')";

    if (mysqli_query($db, $sql)) {
        echo "Saved";
    } else {
        echo "Error: " . $sql . " " . mysqli_error($db);
    }

// Already has data
} else {
    $sql = "UPDATE event_email SET subject='$subject', body='$body', sender='$sender'
            WHERE campaign_id=$id";

    if (mysqli_query($db, $sql)) {
        echo "Updated";
    } else {
        echo "Error: " . $sql . " " . mysqli_error($db);
    }

}

?>