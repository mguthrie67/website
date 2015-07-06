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

$sql="select email_before_subject, email_after_subject, email_after_salutation, email_after_message from systememail";

if(!$results = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}

$row=mysqli_fetch_row($results);
$email_before_subject=$row[0];
$email_after_subject=$row[1];
$email_after_salutation=$row[2];
$email_after_message=$row[3];

$message = $email_before_subject . $subject .  $email_after_subject . "<p>Dear Test Person,</p><br>" . $email_after_salutation
           . $body . $email_after_message;

#### TESTING

#$myfile = fopen("c:\\temp\\testmail.html", "w") or die("Unable to open file!");
#fwrite($myfile, $message);
#fclose($myfile);

#### TESTING

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= "From: 17 Ways Events <events@17ways.com.au>" . "\r\n";

 if  (mail("mark.guthrie@17ways.com.au", $subject , $message,$headers)) {
    echo "Mail Sent";
 } else {
    echo "Error sending mail.";
 }



?>