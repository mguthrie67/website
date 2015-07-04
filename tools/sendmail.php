<?php

// security stuff
require_once("config/db.php");
require_once("classes/Login.php");
$login = new Login();
if ($login->isUserLoggedIn() !== true) {
    die("Error - not logged in!");
}

$subject = $_POST["subject"];
$body = $_POST["body"];

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_CAMPAIGN);

$sql="select email_header, email_footer from systememail";

if(!$results = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

$row=mysqli_fetch_row($results);
$header=$row[0];
$footer=$row[1];

$message = $header . $body . $footer;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= "From: 17 Ways Events <events@17ways.com.au>" . "\r\n";

 if  (mail("mark.guthrie@17ways.com.au", $subject , $message,$headers)) {
    echo "Mail Sent";
 } else {
    echo "Error sending mail.";
 }



?>