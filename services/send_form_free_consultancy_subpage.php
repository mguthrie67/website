<?php 

$send_email_to = "mark.guthrie@17ways.com.au";

$name = $_POST['name'];
$email = $_POST['email'];
$company = $_POST['company'];
$request = $_POST['request'];

if (strlen($name)<2){
    die("Please enter your name.");
}
if (strlen($company)<1){
    die("Please enter your company.");
}
if (strlen($email)<5){
    die("Please enter your email address.");
}

$subject = "INCOMING CONSULTANCY >>>> " . $_POST['company'];

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= "From: ".$email. "\r\n";
$message = "<p style='font-family:Verdana;font-size:13px;'>";
$message .= "<table noborder><tr><td><strong>Email Address: </strong><td>".$email."</tr>";
$message .= "<tr><td><strong>Sender: </strong><td>".$name."</tr>";
$message .= "<tr><td><strong>URL: </strong><td>".$request."</tr>";
$message .= "<tr><td><strong>Company: </strong><td>".$company."</tr></table><br>";

if (@mail($send_email_to, $subject, $message,$headers)) {
    echo "Thank you, we'll be in touch soon.";
} else {
    echo "Error sending email. Please call use on 1300 17WAYS or email us at info@17ways.com.au to get your free appointment.";
}

?>
